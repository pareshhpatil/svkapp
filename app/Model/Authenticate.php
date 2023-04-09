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
use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Authenticate extends Model {

    public function saveUser($user_id, $userType, $gender, $maritalStatus, $designation, $code, $name, $email, $mobile, $userTPAUrl, $loginEmailSentDate, $passwordChangedDate, $passwordResetDate, $lastLogin, $roles, $accessRights, $isSuperUser, $iaBranch, $groupAccess, $companyAccess) {
        try {
            $id = DB::table('users')->insertGetId(
                    [
                        'user_id' => $user_id,
                        'userType' => $userType,
                        'gender' => $gender,
                        'maritalStatus' => $maritalStatus,
                        'designation' => $designation,
                        'code' => $code,
                        'name' => $name,
                        'email' => $email,
                        'mobile' => $mobile,
                        'userTPAUrl' => $userTPAUrl,
                        'loginEmailSentDate' => $loginEmailSentDate,
                        'passwordChangedDate' => $passwordChangedDate,
                        'passwordResetDate' => $passwordResetDate,
                        'lastLogin' => $lastLogin,
                        'roles' => $roles,
                        'accessRights' => $accessRights,
                        'isSuperUser' => $isSuperUser,
                        'iaBranch' => $iaBranch,
                        'groupAccess' => $groupAccess,
                        'companyAccess' => $companyAccess
                    ]
            );
            return $id;
        } catch (Exception $e) {
            Log::error(__CLASS__ . '[AUTH001] Error while save user  Error: ' . $e->getMessage());
        }
    }

    public function updateUser($user_id, $userType, $gender, $maritalStatus, $designation, $code, $name, $email, $mobile, $userTPAUrl, $loginEmailSentDate, $passwordChangedDate, $passwordResetDate, $lastLogin, $roles, $accessRights, $isSuperUser, $iaBranch, $groupAccess, $companyAccess) {
        try {
            DB::table('users')
                    ->where('user_id', $user_id)
                    ->update([
                        'userType' => $userType,
                        'gender' => $gender,
                        'maritalStatus' => $maritalStatus,
                        'designation' => $designation,
                        'code' => $code,
                        'name' => $name,
                        'email' => $email,
                        'mobile' => $mobile,
                        'userTPAUrl' => $userTPAUrl,
                        'loginEmailSentDate' => $loginEmailSentDate,
                        'passwordChangedDate' => $passwordChangedDate,
                        'passwordResetDate' => $passwordResetDate,
                        'lastLogin' => $lastLogin,
                        'roles' => $roles,
                        'accessRights' => $accessRights,
                        'isSuperUser' => $isSuperUser,
                        'iaBranch' => $iaBranch,
                        'groupAccess' => $groupAccess,
                        'companyAccess' => $companyAccess
            ]);
        } catch (Exception $e) {
            Log::error(__CLASS__ . '[AUTH002] Error while update user  Error: ' . $e->getMessage());
        }
    }

    public function isExistUser($user_id) {
        try {
            $retObj = DB::table('users')
                    ->select(DB::raw('user_id'))
                    ->where('user_id', $user_id)
                    ->first();
            if (!empty($retObj)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . '[AUTH003] Error while is exist user  Error: ' . $e->getMessage());
        }
    }

    public function isExistIAUser($user_id) {
        try {
            $retObj = DB::table('ia_user')
                    ->select(DB::raw('user_id'))
                    ->where('user_id', $user_id)
                    ->first();
            if (!empty($retObj)) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . '[AUTH004] Error while is exist ia user  Error: ' . $e->getMessage());
        }
    }

    public function getEmployeeID($code, $company_id) {
        try {
            $retObj = DB::table('employee')
                    ->select(DB::raw('employee_id'))
                    ->where('employee_code', $code)
                    ->where('corporate_id', $company_id)
                    ->first();
            if (!empty($retObj)) {
                return $retObj->employee_id;
            } else {
                return '';
            }
        } catch (Exception $e) {
            Log::error(__CLASS__ . '[AUTH005] Error while get employee id  Error: ' . $e->getMessage());
        }
    }

}
