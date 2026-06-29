<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        return response()->json(Grupo::with('generacion')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'generacion_id' => 'required|exists:generaciones,id',
            'nombre' => 'required|string|max:50',
        ]);

        $grupo = Grupo::create($validated);
        return response()->json($grupo->load('generacion'), 201);
    }

    public function show(Grupo $grupo)
    {
        return response()->json($grupo->load(['generacion', 'alumnos']));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $validated = $request->validate([
            'generacion_id' => 'required|exists:generaciones,id',
            'nombre' => 'required|string|max:50',
        ]);

        $grupo->update($validated);
        return response()->json($grupo->load('generacion'));
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return response()->json(null, 204);
    }

    public function showPublic(Grupo $grupo)
    {
        return response()->json([
            'id' => $grupo->id,
            'nombre' => $grupo->nombre,
        ]);
    }
}
