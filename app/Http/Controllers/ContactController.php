<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ContactCreateRequest;
use App\Http\Resources\ContactResource;

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
}
