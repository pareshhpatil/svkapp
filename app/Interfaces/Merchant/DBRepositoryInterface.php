<?php

namespace App\Interfaces\Merchant;

interface DBRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function show($id);

    public function delete($id);
}