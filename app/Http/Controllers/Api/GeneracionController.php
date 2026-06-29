<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Generacion;
use Illuminate\Http\Request;

class GeneracionController extends Controller
{
    public function index()
    {
        return response()->json(Generacion::orderBy('anio_inicio', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'anio_inicio' => 'required|integer',
            'anio_fin' => 'required|integer|gte:anio_inicio',
        ]);

        $generacion = Generacion::create($validated);
        return response()->json($generacion, 201);
    }

    public function show(Generacion $generacion)
    {
        return response()->json($generacion->load('grupos'));
    }

    public function update(Request $request, Generacion $generacion)
    {
        $validated = $request->validate([
            'anio_inicio' => 'required|integer',
            'anio_fin' => 'required|integer|gte:anio_inicio',
        ]);

        $generacion->update($validated);
        return response()->json($generacion);
    }

    public function destroy(Generacion $generacion)
    {
        $generacion->delete();
        return response()->json(null, 204);
    }
}
