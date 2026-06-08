<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // GET /api/users — ambil semua user
    public function index()
    {
        $users = User::all();
        return new UserResource($users, 'Success', 'List of users');
    }

    // POST /api/users — buat user baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'nim'      => 'required|string|unique:users,nim',
            'email'    => 'required|email|unique:users,email',
            'fakultas' => 'required|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return new UserResource(null, 'Failed', $validator->errors());
        }

        $user = User::create($request->only(['name', 'nim', 'email', 'fakultas', 'no_hp']));

        return new UserResource($user, 'Success', 'User created successfully');
    }

    // GET /api/users/{id} — ambil satu user
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return new UserResource(null, 'Failed', 'User not found');
        }

        return new UserResource($user, 'Success', 'User found');
    }

    // PUT /api/users/{id} — update user
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return new UserResource(null, 'Failed', 'User not found');
        }

        $validator = Validator::make($request->all(), [
            'name'     => 'sometimes|string|max:255',
            'nim'      => 'sometimes|string|unique:users,nim,' . $id,
            'email'    => 'sometimes|email|unique:users,email,' . $id,
            'fakultas' => 'sometimes|string|max:255',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return new UserResource(null, 'Failed', $validator->errors());
        }

        $user->update($request->only(['name', 'nim', 'email', 'fakultas', 'no_hp']));

        return new UserResource($user, 'Success', 'User updated successfully');
    }

    // DELETE /api/users/{id} — hapus user
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return new UserResource(null, 'Failed', 'User not found');
        }

        $user->delete();

        return new UserResource(null, 'Success', 'User deleted successfully');
    }
}
