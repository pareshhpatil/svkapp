<?php

namespace App\Repositories;

use App\Interfaces\Merchant\DBRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DBRepository implements DBRepositoryInterface
{
    /**
    * Eloquent model instance.
    */
    protected $model;

    /**
     * DBRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function all()
    {
        return $this->model->get();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        return $this->model->insert($data);
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
            $result =  $this->model->where('id', $id)->update($data);
        } catch (\Exception $exception) {

            throw new \Exception('Unable to update data');
        }

        return $result;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

}