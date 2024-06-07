<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\PasswordHistory;

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
            'password' => 'required', // Assure que le mot de passe est fourni
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

        // Stocker l'ancien mot de passe avant de le modifier
        $oldPassword = Crypt::decryptString($password->password);

        $password->name = $request->input('name');
        $password->username = $request->input('username');
        $password->password = Crypt::encryptString($request->input('password'));
        $password->save();

        PasswordHistory::create([
            'password_id' => $password->id,
            'old_password' => Crypt::encryptString($oldPassword),
            'new_password' => Crypt::encryptString($request->input('password')),
            'changed_at' => now(),
        ]);

        return redirect('/keepass');
    }

    public function destroy($id)
    {
        $password = Password::find($id);
        $password->delete();
        return redirect('/keepass');
    }

    public function history($id)
    {
        $password = Password::find($id);
        $history = $password->passwordHistories;

        return view('keepass.history', ['history' => $history]);
    }
}
