<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Listar todos los usuarios Administrador
    public function index()
    {
        $users = User::with(['role', 'userState'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Lista de usuarios obtenida correctamente',
            'data' => $users
        ], 200);
    }

       //listar todos los usuario nutricionistas
    public function nutritionists()
    {
        $nutritionists = User::with(['role', 'userState'])->where('role_id', 2)->get();

        return response()->json([
            'success' => true,
            'message' => 'Lista de nutricionistas obtenida correctamente',
            'data' => $nutritionists
        ], 200);
    }

    // listar todos los usuarios pacientes
    public function patients()
    {
        $patients = User::with(['role', 'userState'])->where('role_id', 3)->get();

        return response()->json([
            'success' => true,
            'message' => 'Lista de pacientes obtenida correctamente',
            'data' => $patients
        ], 200);
    }

    // Mostrar un usuario por ID
    public function show($id)
    {
        $user = User::with(['role', 'userState'])->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Usuario encontrado',
            'data' => $user
        ], 200);
    }

    //Eliminar usuario por id siendo administrador
    public function destroy($id)
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401);
        }

        // Solo administradores (role_id = 1) pueden eliminar usuarios
        if ($authUser->role_id !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado. Solo administradores pueden eliminar usuarios'
            ], 403);
        }

        // Prevenir eliminación accidental de la propia cuenta del administrador
        if ($authUser->id == $id) {
            return response()->json([
                'success' => false,
                'message' => 'No puede eliminar su propia cuenta'
            ], 400);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario'
            ], 500);
        }
    }
}
