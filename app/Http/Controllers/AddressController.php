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
            ], 400);
        }

        $address = new Address($data);
        $address->contact_id = $contact->id;
        $address->save();

        return new AddressResource($address);
    }

    public function get(int $contactId, int $addressId)
    {
        $user = Auth::user();
        $contact = Contact::where('id', $contactId)->where('user_id', $user->id)->first();
        if (!$contact) {
            return response([
                "message" => "Contact not found"
            ]);
        }
        $address = $contact->address->where('id', $addressId);
        return new AddressResource($address);
    }
}
