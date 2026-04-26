<?php

namespace App\Http\Controllers;

use App\Library\conexionDB;
use App\Models\Jugador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class loginController extends Controller
{

    public function login(Request $request)
    {
        // dd($request);
        // return response()->json([
        //     'debug_correo' => $request->correo
        // ]);
        // 🔥 VALIDAR
        $validator = Validator::make($request->all(), [
            'correo' => 'required|email',
            'password' => 'required|min:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $correo = trim($request->correo);


        $jugador = Jugador::whereRaw('LTRIM(RTRIM(Correo)) = ?', [$correo])->first();

        // ❌ NO EXISTE
        if (!$jugador) {
            return response()->json([
                'ok' => false,
                'message' => 'Usuario no encontrado como vez loco'
            ], 404);
        }

        // // 🔐 VALIDAR PASSWORD
        if (!Hash::check($request->password, $jugador->Password)) {
            return response()->json([
                'ok' => false,
                'message' => 'Contraseña incorrecta' . $request->password . ' - ' . $jugador->Password
            ], 401);
        }

        // // ✅ LOGIN OK
        return response()->json([
            'ok' => true,
            'message' => 'Login correcto',
            'user' => $jugador
        ]);
    }
    public function crearJugador(Request $request)
    {
        // 🔥 VALIDACIÓN
        $validator = Validator::make($request->all(), [
            'nombre'     => 'required|string|max:100',
            'numero'     => 'required|integer|min:1|max:99',
            'edad'       => 'required|integer|min:5|max:60',
            'posicion'   => 'nullable|string|max:50',
            'correo'     => 'required|email|max:150',
            'estatura'   => 'nullable|numeric|min:100|max:250',
            'liga'       => 'required|integer',
            'categoria'  => 'required|integer',
            'password'   => 'required|min:4',
            'logo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // 📸 GUARDAR IMAGEN
        $rutaImagen = null;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $nombreArchivo = time() . '_' . $file->getClientOriginalName();
            $rutaImagen = $file->storeAs('logos', $nombreArchivo, 'public');
        }

        // 🔐 ENCRIPTAR PASSWORD
        $passwordEncriptado = Hash::make($data['password']);

        // 💾 GUARDAR EN BD (MAPEO CORRECTO)
        $jugador = Jugador::create([
            'NombreCompleto' => $data['nombre'],
            'Edad'           => $data['edad'],
            'Numero'         => $data['numero'],
            'Posicion'       => $data['posicion'] ?? null,
            'Id_Liga'        => $data['liga'],
            'Correo'         => $data['correo'],
            'Estatura'       => $data['estatura'] ?? null,
            'Foto'           => $rutaImagen,
            'Password'       => $passwordEncriptado,
            'Estatus'        => 'Activo',

            // 🔥 ESTOS TE ESTÁN ROMPIENDO TODO
            'Id_Equipo'      => 1,
            'NumCampeonatos' => 0,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Usuario creado correctamente',
            'data' => $jugador
        ]);
    }
}
