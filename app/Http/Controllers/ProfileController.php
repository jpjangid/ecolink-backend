<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        return view('profile.edit');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . auth()->user()->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'password' => 'nullable| min:8| max:24 |confirmed',
            'password_confirmation' => 'nullable| min:8'
        ], [
            'email.required'    => 'Email is required',
            'password.min'      => 'Password must not be small then 8 characters',
            'password.confirmed' => 'Confirm password does not match or either field is blank'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $image_name = $file->getClientOriginalName();
            $user_id = auth()->user()->id;
            $path = Storage::putFileAs('public/profile/' . $id, $file, $image_name);
            User::where('id', $id)->update([
                'profile_image' => $image_name
            ]);
        }

        if (!empty($request->password)) {
            User::where('id', $id)->update(
                [
                    'email'    => $request->email,
                    'password' =>  Hash::make($request['password'])
                ]
            );
        } else {
            User::where('id', $id)->update(
                [
                    'email'    => $request->email
                ]
            );
        }

        return back()->with('success', 'Profile is updated');
    }

    public function destroy($id)
    {
        //
    }
}
