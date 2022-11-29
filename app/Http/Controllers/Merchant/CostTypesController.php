<?php

namespace App\Http\Controllers\Merchant;

use App\Constants\Models\IColumn;
use App\Constants\Models\IModel;
use App\Http\Controllers\AppController;
use App\Libraries\Helpers;
use App\Model\Merchant\CostType;
use App\Repositories\Merchant\CostTypeRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Validator;

class CostTypesController extends AppController
{
    protected $repository;

    public function __construct(CostTypeRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    /***
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $title = 'Cost Type list';

        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $data['costTypes'] = $this->repository->all();
        $data['datatablejs'] = 'table-no-export';

        return view('app/merchant/cost-types/index', $data);
    }

    /**
     * Show the form for creating a new cost type.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $title = 'Create Cost Type';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        return view('app/merchant/cost-types/create', $data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'abbrevation' => 'unique:cost_types|max:2'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            $abbrevation = $request->get('abbrevation');
            if (empty($abbrevation)) {
                $abbrevation = Str::upper(substr($request->get('name'), 0, 1));
            }

            //Check If abbrevation already exists
            $exists = CostType::query()
                                ->where(IColumn::ABBREVATION, $abbrevation)
                                ->exists();

            if($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'abbrevation' => 'The abbrevation has already been taken.'
                    ]);
            }

            $this->repository->create([
                'name' => $request->get('name'),
                'abbrevation' => $abbrevation,
                'merchant_id' => $this->merchant_id,
                'created_by' => $this->user_id,
                'last_update_by' => $this->user_id
            ]);

            return redirect()->to('merchant/cost-types/index')->with('success', "Cost type has been created");
        } catch (Exception $exception) {

            Log::error($exception->getMessage());

            return redirect()->to('merchant/cost-types')->with('error', "Something went wrong!");
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $title = 'Update Cost Type';
        $data = Helpers::setBladeProperties($title, ['units', 'template'], []);

        $data['costType'] = $this->repository->show($id);

        return view('app/merchant/cost-types/edit', $data);
    }


    public function update($id, Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'abbrevation' => 'max:2'
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator->messages());
            }

            $abbrevation = $request->get('abbrevation');
            if (empty($abbrevation)) {
                $abbrevation = Str::upper(substr($request->get('name'), 0, 1));
            }

            //Check If abbrevation already exists
            $exists = CostType::query()
                ->where(IColumn::ID, IModel::NOT_EQUALS_TO, $id)
                ->where(IColumn::ABBREVATION, $abbrevation)
                ->exists();

            if($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors([
                        'abbrevation' => 'The abbrevation has already been taken.'
                    ]);
            }

            $this->repository->update([
                'name' => $request->get('name'),
                'abbrevation' => $abbrevation,
                'merchant_id' => $this->merchant_id,
                'last_update_by' => $this->user_id,
            ], $id);

            return redirect()->to('merchant/cost-types/index')->with('success', "Cost type has been updated");
        } catch (Exception $exception) {
            Log::error($exception->getMessage());

            return redirect()->to('merchant/cost-types/index')->with('error', "Something went wrong!");
        }
    }
}
