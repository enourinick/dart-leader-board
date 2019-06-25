<?php

namespace App\Repositories;

use App\User;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository
{
    protected $fillable = [
        'email',
        'name'
    ];

    public function fill(array $data, $object, array $fillable = [])
    {
        $result = parent::fill($data, $object, $fillable);

        if(array_key_exists('password', $data)) {
            $result->password = Hash::make($data['password']);
        }

        return $result;
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return User::class;
    }
}
