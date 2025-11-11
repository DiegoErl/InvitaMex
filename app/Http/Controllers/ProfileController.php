<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('perfil-edit');
    }

    public function update(Request $request)
    {
        $user = \App\Models\User::find(Auth::id());

        // Validación
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:2',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'firstName.required' => 'El nombre es obligatorio',
            'lastName.required' => 'El apellido es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este email ya está en uso',
            'current_password.required_with' => 'Debes ingresar tu contraseña actual para cambiarla',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'new_password.confirmed' => 'Las contraseñas no coinciden',
            'profile_photo.image' => 'El archivo debe ser una imagen',
            'profile_photo.max' => 'La imagen no debe pesar más de 2MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar contraseña actual si se intenta cambiar
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'errors' => ['current_password' => ['La contraseña actual es incorrecta']]
                ], 422);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Manejar la foto de perfil
        if ($request->hasFile('profile_photo')) {
            // Eliminar foto anterior si existe
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Guardar nueva foto
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        // Actualizar datos
        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->country = $request->country;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => '¡Perfil actualizado exitosamente!',
            'user' => [
                'firstName' => $user->firstName,
                'lastName' => $user->lastName,
                'email' => $user->email,
                'profile_photo' => $user->profile_photo ? asset('storage/' . $user->profile_photo) : null,
            ]
        ], 200);
    }

    public function deletePhoto()
    {
        $user = \App\Models\User::find(Auth::id());

        if ($user && $user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
            $user->profile_photo = null;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil eliminada exitosamente'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No hay foto de perfil para eliminar'
        ], 400);
    }
}