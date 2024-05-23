<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserUpdateRequest;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Auth;

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

    public function login(UserLoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where("username", $data["username"])->first();

        if (!$user || !Hash::check($data["password"], $user->password)) {
            return response(
                [
                    "errors" => [
                        "message" => [
                            "username or password is incorrect."
                        ]
                    ]
                ],
                400
            );
        }
        $user->token = Str::uuid()->toString();
        $user->save();


        return new UserResource($user);
    }


    public function get(Request $request)
    {
        $user = Auth::user();
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        if (isset($data['name'])) {
            $user->name = $data['name'];
        }
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        if ($user instanceof User) {
            $user->save();
        }

        return new UserResource($user);
    }
}
