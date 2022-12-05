<?php

namespace App\Http\Controllers;

use Exception;
use \Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Validator;
use Log;
use App\Model\User;
use App\Http\Controllers\UserController;
use App\Libraries\Encrypt;
use Illuminate\Support\Facades\Session;

class JwtController extends Controller
{

    public function verify($type = null)
    {
        try {
            if (isset($_POST['jwt_token'])) {
                $token = $_POST['jwt_token'];
            } else {
                $token = file_get_contents('php://input');
            }
            $errorlist = array();
            Log::debug('Trade india JWT token initiated ' . $token);
            $key = env('JWT_SECRET_KEY');
            $data = JWT::decode($token, $key, array('HS256'));
            $data = json_decode(json_encode($data), 1);
            //$decoded_json = '{"gst":"27AAGCC1604F1Z4","industry_type":"Exporter, Importer, Manufacturer, Supplier, Trading Company, Wholesaler, Dealer","city":"Mumbai","address":"250 MKPO NOIDA","full_name":"Mr Kuldeep Kumar","userid":26293,"country":"India","company_type":"Private Limited Company","company_name":"Tradeindia","state":"Maharashtra","mobile":"+918130047669","pan_no":null,"email":"ruchi3@tradeindia.com","zip_code":"110030","profile_id":3578581}';
            //$data = json_decode($decoded_json, 1);
            $request = new Request($data);
            $validation = [
                'full_name' => 'required|max:100',
                'userid' => 'required|max:20',
                'profile_id' => 'required|max:20',
                'company_name' => 'required|max:100',
                'mobile' => 'required|max:20',
                'email' => 'required|max:250',
            ];
            $validator = Validator::make($request->all(), $validation);
            if ($validator->fails()) {
                $errors = json_decode(json_encode($validator->errors()), 1);
                $errorlist = array();
                foreach ($errors as $er) {
                    $errorlist[] = $er[0];
                }
                Log::info('Trade india JWT token data validation failed JSON: ' . $decoded_json . ' Error: ' . json_encode($errorlist));
                throw new Exception('Validation failed');
            } else {
                Log::info('Trade india JWT token validated');

                if ($request->source == 'WEB') {
                    $source = 'WEB';
                } else {
                    $source = 'APP';
                }
                Session::put('source', $source);
                $redirect_url = $this->handleUserData($request, $type);
                if (env('APP_ENV') != 'LOCAL') {
                    return redirect()->secure($redirect_url);
                } else {
                    return redirect($redirect_url);
                }
            }
        } catch (Exception $e) {
            if (empty($errorlist)) {
                Log::info('Trade india JWT Invalid token error: ' . $e->getMessage());
                abort(401, 'Invalid Token');
            } else {
                abort(422, 'Invalid data Error: ' . json_encode($errorlist));
            }
        }
    }

    function handleUserData($request, $type)
    {
        if ($type == 'swastik') {
            $plan_id = 2;
            $campaign_id = 0;
            $service_id = 12;
            $merchant_domain = 1;
            $partner_id = 1;
        } else {
            $plan_id = 2;
            $campaign_id = 23;
            $service_id = 2;
            $merchant_domain = 2;
            $partner_id = 2;
        }

        $model = new User();
        $user = new UserController();
        $user_detail = $model->getTableRow('user', 'partner_user_id', $request->userid);
        if ($user_detail == false) {
            $merchant_detail = $model->getTableRow('merchant', 'partner_merchant_id', $request->profile_id);
            $names = $this->getLastName($request->full_name);
            $mobile = str_replace('+91', '', $request->mobile);
            $password = password_hash(time(), PASSWORD_DEFAULT);
            $industry_type = $model->getConfigKey('industry_type', $request->industry_type);
            $entity_type = $this->getUserType($request->company_type);
            if ($merchant_detail == false) {
                $result = $model->merchantRegister($request->email, $names['first_name'], $names['last_name'], '+91', $mobile, $password, $request->company_name, $plan_id, $campaign_id, $service_id);
                $model->updateMerchantDetails($result->merchant_id, $request->profile_id, $request->gst, $request->pan_no, $industry_type, $entity_type, $partner_id, $merchant_domain);
                $model->updateMerchantAddress($result->merchant_id, $result->user_id, $request->address, $request->city, $request->zip_code, $request->state, $request->country);
                $model->updateUserDetails($result->user_id, $request->userid, 12);
                $user_id = $result->user_id;
            } else {
                $result = $model->subuserRegister($merchant_detail->user_id, $request->email, $names['first_name'], $names['last_name'], $mobile, $password);
                $model->updateUserDetails($result->user_id, $request->userid, 20);
                $user_id = $result->user_id;
            }
        } else {
            $user_id = $user_detail->user_id;
        }
        $redirect_url = $user->setTokenLoginDetails($user_id, Encrypt::encode($service_id));
        //dd($redirect_url);
        return $redirect_url;
    }

    function getLastName($name)
    {
        $name = trim($name);
        $name = str_replace('Mr ', '', $name);
        $space_position = strpos($name, ' ');
        if ($space_position > 0) {
            $data['first_name'] = substr($name, 0, $space_position);
            $data['last_name'] = substr($name, $space_position);
        } else {
            $data['first_name'] = $name;
            $data['last_name'] = '';
        }
        return $data;
    }

    function getUserType($type)
    {
        switch ($type) {
            case 'Limited Liability Partnership (LLP)':
                $user_type = 3;
                break;
            case 'Partnership':
                $user_type = 3;
                break;
            case 'Private Limited Company':
                $user_type = 2;
                break;
            case 'Proprietorship':
                $user_type = 4;
                break;
            case 'Public Limited Company':
                $user_type = 2;
                break;
            case 'Public Sector Undertaking':
                $user_type = 2;
                break;
            default:
                $user_type = 0;
                break;
        }
        return $user_type;
    }
}
