<?php
public function update(UserUpdateRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();

        if (isset($data["username"])) {
            $user->username = $data["username"];
        }
        if (isset($data["password"])) {
            $user->password = Hash::make($data["password"]);
        }

        $user->save();

        return new UserResource($user);
    }