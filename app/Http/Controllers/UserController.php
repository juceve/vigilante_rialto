<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:users.index')->only('index');
        // $this->middleware('can:users.create')->only('create', 'store');
        $this->middleware('can:users.edit')->only('edit', 'update', 'cambiaestado');
        // $this->middleware('can:users.destroy')->only('destroy');
    }
    public function index()
    {
        $users = User::all();

        return view('admin.user.index', compact('users'))
            ->with('i', 0);
    }

    public function create()
    {
        $user = new User();
        return view('admin.user.create', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',

        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                // 'status' => $request->status,
            ])->assignRole('Usuario');

            DB::commit();
            return redirect()->route('users.index')
                ->with('success', 'Usuario creado correctamente.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->route('users.index')
                ->with('error', 'Ha ocurrido un error.');
        }
    }

    public function show($id)
    {
        $user = User::find($id);

        return view('user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        request()->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')
            ->with('success', 'Usuario editado correctamente');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Usuario eliminado correctamente');
        } else {
            return redirect()->route('users.index')
                ->with('error', 'Usuario no encontrado');
        }
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $rules = User::$rulesUpdate;
        $rules['email'] = 'required|string|email|max:255|unique:users,email,' . $user->id;

        $request->validate($rules);

        if ($user && $user instanceof \App\Models\User) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return redirect()->route('profile')
                ->with('success', 'Perfil actualizado correctamente');
        } else {
            return redirect()->route('profile')
                ->with('error', 'Usuario no encontrado');
        }
    }

    public function updatePassword(Request $request)
    {
        $user = User::find(auth()->id());

        $request->validate(User::$rulesPassword);

        if (!$user) {
            return redirect()->route('profile')
                ->with('error', 'Usuario no encontrado');
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('profile')
                ->with('error', 'La contraseña actual no es correcta');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile')
            ->with('success', 'Contraseña actualizada correctamente');
    }

    public function updateAvatar(Request $request)
    {
        $user = auth()->user();

        $request->validate(User::$rulesAvatar);

        if ($request->hasFile('avatar')) {
            // Eliminar avatar anterior si existe
            if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar))) {
                unlink(public_path('uploads/avatars/' . $user->avatar));
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();

            // Crear directorio si no existe
            if (!file_exists(public_path('uploads/avatars'))) {
                mkdir(public_path('uploads/avatars'), 0755, true);
            }

            $file->move(public_path('uploads/avatars'), $filename);

            $user->avatar = $filename;
            if ($user instanceof \App\Models\User) {
                $user->save();
            }

            return redirect()->route('profile')
                ->with('success', 'Avatar actualizado correctamente');
        }

        return redirect()->route('profile')
            ->with('error', 'Error al subir la imagen');
    }

    public function asinaRol(User $user)
    {
        $roles = Role::all();
        return view('user.asignaRol', compact('user', 'roles'));
    }


    public function updateRol(Request $request, User $user)
    {
        $user->roles()->sync($request->roles);

        return redirect()->route('users.asignaRol', $user)
            ->with('success', 'Rol actualizado correctamente');
    }

    public function cambiaestado(User $user)
    {
        $user->status = !$user->status;
        $user->save();

        return redirect()->route('users')
            ->with('success', 'Estado del usuario actualizado correctamente');
    }
}
