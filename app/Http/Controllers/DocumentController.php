<?php

namespace App\Http\Controllers;

use App\Models\DocumentModel;
use App\Models\MasterModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /** Menu ids — must match rows inserted via database/sql/documents_menu.sql */
    public const MENU_PARENT = 200;
    public const MENU_LIST = 201;
    public const MENU_CREATE = 202;
    public const MENU_REPORT = 203;

    public $model = null;

    public function __construct()
    {
        $this->model = new DocumentModel();
    }

    public function list()
    {
        $data['selectedMenu'] = [self::MENU_PARENT, self::MENU_LIST];
        $data['menus'] = Session::get('menus');
        $data['documents'] = $this->model->getDocumentList(Session::get('project_access') ?: []);
        return view('web.document.list', $data);
    }

    public function create($id = null)
    {
        $data['selectedMenu'] = [self::MENU_PARENT, self::MENU_CREATE];
        $data['menus'] = Session::get('menus');
        $data['det'] = $id ? $this->model->getRow((int) $id) : null;
        if ($id && !$data['det']) {
            return redirect('/document/list')->withErrors(['error' => 'Document not found.']);
        }
        $master = new MasterModel();
        $data['project_list'] = $master->getProject(Session::get('project_access') ?: []);
        $data['categoryLabels'] = DocumentModel::categoryLabels();
        $data['subtypesByCategory'] = DocumentModel::subtypesByCategory();
        $data['user_list'] = DB::table('users')
            ->where('is_active', 1)
            ->orderBy('name')
            ->select('id', 'name', 'mobile')
            ->get();
        $data['driver_list'] = $this->model->getTableList('driver', 'is_active', 1, 0, []);
        $data['vehicle_list'] = $this->model->getTableList('vehicle', 'is_active', 1, 0, []);
        return view('web.document.create', $data);
    }

    public function save(Request $request)
    {
        $userId = Session::get('user_id');
        $id = (int) $request->input('id', 0);

        $category = $request->document_category;
        $subtype = $request->document_type;

        if (!is_string($category) || !array_key_exists($category, DocumentModel::categoryLabels())) {
            return redirect()->back()->withInput()->withErrors(['document_category' => 'Invalid category.']);
        }

        $allowedSubtypes = DocumentModel::subtypesForCategory($category);
        if (!is_string($subtype) || !array_key_exists($subtype, $allowedSubtypes)) {
            return redirect()->back()->withInput()->withErrors(['document_type' => 'Invalid document type for this category.']);
        }

        $driverId = $request->filled('driver_id') ? (int) $request->driver_id : null;
        $vehicleId = $request->filled('vehicle_id') ? (int) $request->vehicle_id : null;

        if ($category === DocumentModel::CATEGORY_DRIVER) {
            if ($driverId === null || $driverId < 1) {
                return redirect()->back()->withInput()->withErrors(['driver_id' => 'Please select a driver.']);
            }
            if (!DB::table('driver')->where('id', $driverId)->where('is_active', 1)->exists()) {
                return redirect()->back()->withInput()->withErrors(['driver_id' => 'Invalid driver.']);
            }
            $vehicleId = null;
        } elseif ($category === DocumentModel::CATEGORY_VEHICLE) {
            if ($vehicleId === null || $vehicleId < 1) {
                return redirect()->back()->withInput()->withErrors(['vehicle_id' => 'Please select a vehicle.']);
            }
            if (!DB::table('vehicle')->where('vehicle_id', $vehicleId)->where('is_active', 1)->exists()) {
                return redirect()->back()->withInput()->withErrors(['vehicle_id' => 'Invalid vehicle.']);
            }
            $driverId = null;
        } else {
            $driverId = null;
            $vehicleId = null;
        }

        $array = [
            'project_id' => $request->project_id !== null && $request->project_id !== '' ? (int) $request->project_id : null,
            'name' => $request->name,
            'document_category' => $category,
            'document_type' => $subtype,
            'driver_id' => $driverId,
            'vehicle_id' => $vehicleId,
            'issue_date' => $request->issue_date ? $this->sqlDate($request->issue_date) : null,
            'expiry_date' => $request->expiry_date ? $this->sqlDate($request->expiry_date) : null,
            'description' => $request->description,
            'assigned_user_id' => $request->assigned_user_id !== null && $request->assigned_user_id !== '' ? (int) $request->assigned_user_id : null,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $fileName = 'compliance_documents/' . date('Ymd') . '_' . $safeName . '_' . rand(10, 99) . '.' . $extension;
            Storage::disk('s3')->putFileAs('', $file, $fileName);
            $array['file_path'] = Storage::disk('s3')->url($fileName);
        }

        if ($id > 0) {
            $array['last_update_by'] = $userId;
            $array['last_update_date'] = date('Y-m-d H:i:s');
            $this->model->updateArray('ridetrack_compliance_document', 'id', $id, $array);
            return redirect('/document/list')->withSuccess('Document updated successfully');
        }

        $this->model->saveTable('ridetrack_compliance_document', $array, $userId);
        return redirect('/document/list')->withSuccess('Document saved successfully');
    }

    public function report(Request $request)
    {
        $tab = $request->query('tab', 'expiring');
        if (!in_array($tab, ['expiring', 'expired'], true)) {
            $tab = 'expiring';
        }

        $window = $request->query('window', '1m');
        $daysMap = [
            '1w' => 7,
            '1m' => 30,
            '3m' => 90,
            '6m' => 180,
        ];
        if (!isset($daysMap[$window])) {
            $window = '1m';
        }
        $days = $daysMap[$window];

        $start = date('Y-m-d');
        $end = date('Y-m-d', strtotime('+' . $days . ' days'));

        $data['selectedMenu'] = [self::MENU_PARENT, self::MENU_REPORT];
        $data['menus'] = Session::get('menus');
        $data['tab'] = $tab;
        $data['window'] = $window;

        if ($tab === 'expired') {
            $data['documents'] = $this->model->getExpiredDocuments(Session::get('project_access') ?: []);
        } else {
            $data['documents'] = $this->model->getExpiringBetween($start, $end, Session::get('project_access') ?: []);
            $data['rangeLabel'] = date('d M Y', strtotime($start)) . ' — ' . date('d M Y', strtotime($end));
        }

        return view('web.document.report', $data);
    }

    public function delete($id)
    {
        $this->model->updateArray('ridetrack_compliance_document', 'id', (int) $id, [
            'is_active' => 0,
            'last_update_by' => Session::get('user_id'),
            'last_update_date' => date('Y-m-d H:i:s'),
        ]);
        return redirect()->back()->withSuccess('Document removed successfully');
    }
}
