<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AddressResource;
use App\Http\Requests\AddressCreateRequest;

class AddressController extends Controller
{
    public function create(AddressCreateRequest $request, int $contactId)
    {
        $data = $request->validated();
        $user =  Auth::user();
        $contact = Contact::where('user_id', $user->id)
            ->where('id', $contactId)->first();

        if ($contact == null) {
            return response()->json([
                'message' => 'Contact not found'
            ], 404);
        }

        $address = new Address($data);
        $address->contact_id = $contact->id;
        $address->save();

        return new AddressResource($address);
    }
}
