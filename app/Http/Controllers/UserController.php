<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('pages.pengguna.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('pages.pengguna.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'role' => 'required'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'role.required' => 'Role wajib dipilih'
        ]);

        $user = User::create([
            'name' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        if (!$user) {
            return back()->withErrors('error', 'Pengguna gagal ditambahkan');
        }

        $user->assignRole($request->role);
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function edit(User $user)
    {
        // return view('pages.pengguna.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(Request $request)
    {
        // dd($request->all());
        $request->route('id');
        $user = User::find($request->id);
        if(!$user){
            return back()->withErrors('error', 'Pengguna tidak ditemukan');
        }
        $user->delete();
        return redirect()->route('pengguna.index')->with('success', 'Pengguna berhasil dihapus');
    }   
}
