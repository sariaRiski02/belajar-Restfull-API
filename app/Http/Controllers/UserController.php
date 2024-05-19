<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        if (User::where("username", $data["username"])->exists()) {
            return response([
                "errors" => [
                    "username" => [
                        "The username has already been taken."
                    ]
                ]
            ], 422);
        }
        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        return new UserResource($user);
    }
}
