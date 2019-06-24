<?php
namespace App\Repositories;

use App\Tools\Setting;

interface RepositoryContract
{
    public function load($object);
    public function all($columns = array('*'));
    public function paginate($perPage = Setting::PAGE_SIZE, $columns = array('*'));
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function find(int $id, $columns = array('*'));
    public function findBy($field, $value, $columns = array('*'));
}
