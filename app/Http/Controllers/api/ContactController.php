<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ContactQuestion;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'    =>  'required',
            'last_name'     =>  'required',
            'address_1'     =>  'required',
            'email'         =>  'required|email',
            'type'          =>  'required',
            'phone'         =>  'numeric|digits:10'
        ],[
            'first_name.required'       =>  'Please Enter First Name',
            'last_name.required'        =>  'Please Enter Last Name',
            'address_1.required'        =>  'Please Enter Address',
            'email'                     =>  'Please Enter Email',
            'type'                      =>  'Please Enter Contact Type',
            'phone.numeric'             =>  'Please Enter Mobile No. in digits',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $contact = ContactUs::create([
            'first_name'        =>  $request['first_name'], 
            'last_name'         =>  $request['last_name'], 
            'phone'             =>  $request['phone'],
            'address_1'         =>  $request['address_1'],
            'address_2'         =>  $request['address_2'],
            'city'              =>  $request['city'],
            'state'             =>  $request['state'],
            'zip'               =>  $request['zip'],
            'country'           =>  $request['country'],
            'email'             =>  $request['email'],
            'type'              =>  $request['type'],
        ]);

        ContactQuestion::create([
            'contact_id'    =>  $contact->id,
            'input_1'       =>  $request['input_1'], 
            'input_2'       =>  $request['input_2'], 
            'input_3'       =>  $request['input_3'], 
            'input_4'       =>  $request['input_4'], 
            'input_5'       =>  $request['input_5'], 
            'input_6'       =>  $request['input_6'], 
            'input_7'       =>  $request['input_7'], 
            'input_8'       =>  $request['input_8'], 
            'input_9'       =>  $request['input_9'], 
            'input_10'      =>  $request['input_10'],
        ]);

        return response()->json(['message' => 'Contact Details Added Successfully', 'code' => 200], 200);
    }
}
