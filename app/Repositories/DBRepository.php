<?php

namespace App\Repositories;

use App\Constants\Models\IColumn;
use App\Interfaces\Merchant\DBRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DBRepository implements DBRepositoryInterface
{
    /**
    * Eloquent model instance.
    */
    protected $model;

    protected $merchantID;

    /**
     * DBRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model, $merchantID)
    {
        $this->model = $model;
        $this->merchantID = $merchantID;
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return $this->model->where(IColumn::MERCHANT_ID, $this->merchantID)
                            ->get();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        return $this->model->fill($data)->save();
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function update(array $data, $id)
    {
        try {
            return $this->model
                        ->where(IColumn::ID, $id)
                        ->update($data);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            return $this->model
                        ->where(IColumn::MERCHANT_ID, $this->merchantID)
                        ->findOrFail($id);
        } catch (\Exception $exception) {
            throw new \Exception('Unable to update data');
        }
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->model
                    ->destroy($id);
    }

}