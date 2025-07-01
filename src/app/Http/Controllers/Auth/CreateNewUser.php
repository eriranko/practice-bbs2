<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    public function create(array $data)
    {
        // バリデーション
        $this->validate($data);

        return User::create([
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'nickname' => $data['nickname'],
            'gender' => $data['gender'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function validate(array $data)
    {
        $validator = \Validator::make($data, [
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female,other',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}