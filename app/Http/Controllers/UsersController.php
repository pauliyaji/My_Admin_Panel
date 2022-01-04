<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
          'name' => 'required',
          'email' => 'required:unique',
          'phone' => 'required',
          'password' => 'required',
        ]);

        if($request->hasFile('photo')){
            $filenamewithExt = $request->file('photo')->getClientOriginalName();
            $imgPath = $request->file('photo')->storeAs('public/imgs', $filenamewithExt);
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->photo = $filenamewithExt;
            $user->save();
        }
        else{
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();
        }
        return redirect()->back()->with('success', 'User added successfully');
    }

    public function show($id)
    {
        $data = User::find($id);
        return view('users.show', compact('data'));
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get()
        ]);
    }

    public function update(Request $request, $id)
    {
        if($request->hasFile('photo')){
            $filenamewithExt = $request->file('photo')->getClientOriginalName();
            $imgPath = $request->file('photo')->storeAs('public/imgs', $filenamewithExt);
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
           // $user->password = Hash::make($request->password);
            $user->password = $request->password;
            $user->photo = $filenamewithExt;
            $user->save();
            $user->syncRoles($request->get('role'));
        }
        else{
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->save();
            $user->syncRoles($request->get('role'));
        }
        $data = $id;
        return redirect()->back()->with('success', 'User updated successfully');

    }

    public function destroy()
    {
        //
    }
}
