<?php

namespace App\Model;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of User
 *
 * @author Paresh
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Login extends Model {
    public function hasEmailId($user_name, $password) {
        $retObj = DB::table('user as u')
                ->join('admin as a', 'a.admin_id', '=', 'u.admin_id')
                ->select(DB::raw('user_id,u.name,a.logo,a.company_name,u.admin_id,user_type'))
                ->where('user_name', $user_name)
                ->where('password', $password)
                ->where('u.is_active', 1)
                ->first();
        return $retObj;
    }

    /**
     * 
     * @return type
     */
    public function createProfile() {
        $created_date = date("Y-m-d H:i:s");
        $id = DB::table('users')->insertGetId([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'type' => $this->type,
            'status' => 1,
            'socialid' => $this->socialid,
            'created_at' => $created_date
        ]);

        //set token for newly created user
        $this->setToken($id);

        $userObj['id'] = $id;
        $userObj['name'] = $this->name;
        $userObj['email'] = $this->email;
        $userObj['password'] = $this->password;
        $userObj['type'] = $this->type;
        $userObj['status'] = 1;
        $userObj['socialid'] = $this->socialid;
        $userObj['image_url'] = getenv('BASE_IMG_URL');
        $userObj['log_level'] = (int) getenv('LOG_LEVEL');
        $userObj['token'] = $this->token;
        $showAd = (int) getenv('SHOW_AD');

        if ($showAd == 1) {
            $userObj['show_ad'] = 1;
            $adTag = $this->getAdTag();
            $userObj['ad_tag'] = $adTag;
        } else {
            $userObj['show_ad'] = 0;
            $userObj['ad_tag'] = "";
        }
        return $userObj;
    }

    /**
     * 
     * @param type $id_
     */
    public function setToken($id_) {
        $str = date('Y-m-d H:i:s') . '-' . $id_ . 'emu20160120';
        $token = Hash::make($str);

        $pdo = DB::connection()->getPdo();
        $sql = 'UPDATE users SET token = ?, token_validity = DATE_ADD(NOW(), INTERVAL 3 HOUR) WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $token);
        $stmt->bindParam(2, $id_);
        $stmt->execute();
        $stmt->closeCursor();

        $this->token = $token;
    }

    /**
     * 
     * @param type $email_
     */
    public function fetchForgotPasswdRecord($user_id) {
        $retObj = DB::table('forgot_passwd')
                ->select(DB::raw('count'))
                ->where('userid', $user_id)
                ->first();
        return $retObj;
    }

    public function createForgotPassword($user_id) {
        $created_date = date("Y-m-d H:i:s");
        $id = DB::table('forgot_passwd')->insertGetId([
            'userid' => $user_id,
            'count' => 1,
            'last_update_by' => $user_id,
            'updated_at' => $created_date
        ]);
    }

    /**
     * 
     * @param type $email_
     */
    public function updateForgotPassword($user_id, $count) {
        $count = $count + 1;
        $pdo = DB::connection()->getPdo();
        $sql = 'UPDATE forgot_passwd SET count = ? WHERE userid = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $count);
        $stmt->bindParam(2, $user_id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function updateUserPassword($user_id, $password) {
        $pdo = DB::connection()->getPdo();
        $sql = 'UPDATE users SET password = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $password);
        $stmt->bindParam(2, $user_id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    public function updateUserStatus($user_id, $status) {
        $pdo = DB::connection()->getPdo();
        $sql = 'UPDATE users SET status = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $status);
        $stmt->bindParam(2, $user_id);
        $stmt->execute();
        $stmt->closeCursor();
    }

    /**
     * 
     * @return type
     */
    public function updateProfile() {
        DB::table('users')
                ->where('id', $this->id)
                ->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                    'socialid' => $this->socialid,
                    'type' => 10
        ]);
        //set token for newly created user
        $this->setToken($this->id);

        $userObj['id'] = (int) $this->id;
        $userObj['name'] = $this->name;
        $userObj['email'] = $this->email;
        $userObj['password'] = $this->password;
        $userObj['type'] = 10;
        $userObj['status'] = 1;
        $userObj['socialid'] = $this->socialid;
        $userObj['token'] = $this->token;
        $showAd = (int) getenv('SHOW_AD');

        if ($showAd == 1) {
            $userObj['show_ad'] = 1;
            $adTag = $this->getAdTag();
            $userObj['ad_tag'] = $adTag;
        } else {
            $userObj['show_ad'] = 0;
            $userObj['ad_tag'] = "";
        }

        return $userObj;
    }

    /**
     * 
     * @param type $userId_
     * @param type $tokenStr_
     */
    public function validateToken($userId_, $tokenStr_) {
        //select count(*) from users where id=? and token = ? and token_validity > now()
        $count = 0;
        $now = 'now()';
        $count = DB::table('users')
                ->where('id', '=', $userId_)
                ->where('token', '=', $tokenStr_)
                ->whereRaw('token_validity > now()')
                ->count();

        Log::info("count is $count for id : $userId_ and token : $tokenStr_");
        if ($count == 1) {
            return true;
        } else {
            return false;
        }
    }

}
