<?php

namespace App\Http\Controllers;

use App\Model\Dashboard;
use Session;
use Log;
use Illuminate\Http\Request;
use App\Lib\Encryption;

class DashboardController extends Controller {

    private $dashboard_model;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->dashboard_model = new Dashboard();
    }

    public function admin() {
        try {
            $this->validateSession(1);
            $data['title'] = 'Admin Dashboard';
            return view('dashboard.admin', $data);
        } catch (Exception $e) {
            Log::error('DB001 Error while admin dashboard Error: ' . $e->getMessage());
            return $this->setGenericError();
        }
    }

    public function employee() {
        try {
            $this->validateSession(2);
            $data['title'] = 'Employee Dashboard';
            return view('dashboard.employee', $data);
        } catch (Exception $e) {
            Log::error('DB003 Error while employee dashboard Error: ' . $e->getMessage());
            return $this->setGenericError();
        }
    }

    public function profile() {
        try {
            $this->validateSession(1);
            $detail = $this->dashboard_model->getAdminDetail($this->admin_id);
            $data['det'] = $detail;
            $data['title'] = 'Admin Profile';
            return view('dashboard.profile', $data);
        } catch (Exception $e) {
            Log::error('DB003 Error while employee dashboard Error: ' . $e->getMessage());
            return $this->setGenericError();
        }
    }

    public function profilesave(Request $request) {
        $this->validateSession(1);
        $this->dashboard_model->profilesave($request, $request->name, $request->email, $request->mobile, $request->gst, $request->address, $request->pan, $request->sac, $request->logo, $this->admin_id, $this->user_id);
        $this->setSuccess('Profile has been saved successfully');
        header('Location: /admin/profile');
        exit;
    }

}
