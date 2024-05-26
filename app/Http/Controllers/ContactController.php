<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Requests\ContactUpadateRequest;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactController extends Controller
{
    public function create(ContactCreateRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $contact = new Contact($data);
        $contact->user_id = $user->id;
        $contact->save();

        return new ContactResource($contact);
    }

    public function get(int $id)
    {
        $user = Auth::user();

        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        if (!$contact) {
            return response()->json([
                "errors" => [
                    "message" => ["not found"]
                ]
            ])->setStatusCode(404);
        }
        return new ContactResource($contact);
    }

    public function update(int $id, ContactUpadateRequest $request)
    {
        $user = Auth::user();

        $contact = Contact::where('id', $id)->where('user_id', $user->id)->first();
        if (!$contact) {
            return response()->json([
                "errors" => [
                    "message" => ["not found"]
                ]
            ])->setStatusCode(404);
        }

        $data = $request->validated();

        $contact->firstname = $data["firstname"] ?? $contact->firstname;
        $contact->lastname = $data["lastname"] ?? $contact->lastname;
        $contact->email = $data["email"] ?? $contact->email;
        $contact->phone = $data["phone"] ?? $contact->phone;

        $contact->save();

        return new ContactResource($contact);
    }

    public function delete(int $id)
    {
        $user = Auth::user();

        $contact = Contact::where('id', $id)->where('id_user', $user->id);
        if (!$contact) {
            return response()->json([
                "errors" => [
                    "message" => ["not found"]
                ]
            ])->setStatusCode(404);
        }

        return response()->json([
            "data" => true
        ]);
    }
}
