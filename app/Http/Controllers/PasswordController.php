<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class PasswordController extends Controller
{
    public function index()
    {
        $passwords = auth()->user()->pwds;
        return view("keepass.index", compact("passwords"));
    }

    public function show(Password $password)
    {
        $decryptedPassword = Crypt::decryptString($password->password);
        return view('keepass.show', compact('password', 'decryptedPassword'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $password = new Password();
        $password->name = $request->input('name');
        $password->username = $request->input('username');
        $password->password = Crypt::encryptString($request->input('password'));
        $password->url = $request->input('url');
        $password->user_id = auth()->user()->id;
        $password->save();
        return redirect('/keepass');
    }

    public function create()
    {
        return view('keepass.create');
    }

    public function edit($id)
    {
        $password = Password::findOrFail($id);
        $decryptedPassword = Crypt::decryptString($password->password);
        return view('keepass.edit', compact('password', 'decryptedPassword'));
    }


    public function update(Request $request, $id)
    {
        $password = Password::find($id);

        $password->name = $request->input('name');
        $password->username = $request->input('username');
        $password->password = Crypt::encryptString($request->input('password')); // Crypter le nouveau mot de passe
        $password->save();

        return redirect('/keepass');
    }


    public function destroy($id)
    {
        $password = Password::find($id);
        $password->delete();
        return redirect('/keepass');
    }
}
