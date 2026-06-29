<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    public function index()
    {
        return response()->json(Alumno::with('grupo')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'numero_lista' => 'required|integer|min:1',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'edad' => 'nullable|integer|min:5|max:120',
            'numero_celular' => 'nullable|string|max:20',
        ]);

        // Evitar duplicación de número de lista en el mismo grupo
        $exists = Alumno::where('grupo_id', $request->grupo_id)
            ->where('numero_lista', $request->numero_lista)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'El número de lista ya existe en este grupo.'
            ], 422);
        }

        $alumno = Alumno::create($validated);
        return response()->json($alumno->load('grupo'), 201);
    }

    public function show(Alumno $alumno)
    {
        return response()->json($alumno->load('grupo'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $validated = $request->validate([
            'grupo_id' => 'required|exists:grupos,id',
            'numero_lista' => 'required|integer|min:1',
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'edad' => 'nullable|integer|min:5|max:120',
            'numero_celular' => 'nullable|string|max:20',
        ]);

        // Evitar duplicación si se cambia el número de lista a uno que ya pertenece a otro alumno del mismo grupo
        $exists = Alumno::where('grupo_id', $request->grupo_id)
            ->where('numero_lista', $request->numero_lista)
            ->where('id', '!=', $alumno->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'El número de lista ya existe en este grupo para otro alumno.'
            ], 422);
        }

        $alumno->update($validated);
        return response()->json($alumno->load('grupo'));
    }

    public function destroy(Alumno $alumno)
    {
        $alumno->delete();
        return response()->json(null, 204);
    }

    public function importarCSV(Request $request, Grupo $grupo)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        // Abrir y procesar el archivo CSV
        if (($handle = fopen($filePath, 'r')) === false) {
            return response()->json(['message' => 'No se pudo abrir el archivo CSV.'], 400);
        }

        $rows = [];
        $isFirstRow = true;
        $delimiter = ',';

        // Detectar delimitador (a veces es punto y coma en Excel)
        $firstLine = fgets($handle);
        if ($firstLine !== false) {
            if (strpos($firstLine, ';') !== false && strpos($firstLine, ',') === false) {
                $delimiter = ';';
            }
            rewind($handle);
        }

        while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
            // Limpiar datos
            $data = array_map('trim', $data);

            // Ignorar filas vacías
            if (empty($data) || count($data) < 3 || $data[0] === '') {
                continue;
            }

            // Omitir encabezados comunes
            if ($isFirstRow) {
                $isFirstRow = false;
                if (!is_numeric($data[0])) {
                    continue;
                }
            }

            $rows[] = [
                'numero_lista' => (int)$data[0],
                'nombre' => $data[1],
                'apellido_paterno' => $data[2],
                'apellido_materno' => $data[3] ?? null,
                'edad' => isset($data[4]) && $data[4] !== '' ? (int)$data[4] : null,
                'numero_celular' => $data[5] ?? null,
            ];
        }
        fclose($handle);

        if (empty($rows)) {
            return response()->json(['message' => 'El archivo CSV está vacío o no tiene el formato correcto (numero_lista, nombre, apellido_paterno).'], 422);
        }

        // Ejecutar la importación dentro de una transacción
        try {
            DB::transaction(function () use ($grupo, $rows) {
                foreach ($rows as $row) {
                    Alumno::updateOrCreate(
                        [
                            'grupo_id' => $grupo->id,
                            'numero_lista' => $row['numero_lista']
                        ],
                        [
                            'nombre' => $row['nombre'],
                            'apellido_paterno' => $row['apellido_paterno'],
                            'apellido_materno' => $row['apellido_materno'],
                            'edad' => $row['edad'],
                            'numero_celular' => $row['numero_celular'],
                        ]
                    );
                }
            });

            return response()->json([
                'message' => 'Alumnos importados correctamente.',
                'imported_count' => count($rows)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al importar los alumnos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function inscribirPorQR(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido_paterno' => 'required|string|max:100',
            'apellido_materno' => 'nullable|string|max:100',
            'edad' => 'nullable|integer|min:5|max:120',
            'numero_celular' => 'required|string|max:20',
            'celular_ultimos_cuatro' => 'nullable|string|size:4',
        ]);

        $celular = trim($request->numero_celular);
        $alumnoExistente = Alumno::where('numero_celular', $celular)->first();

        if ($alumnoExistente) {
            // Si ya existe, requerimos los últimos 4 dígitos
            if (!$request->celular_ultimos_cuatro) {
                return response()->json([
                    'requiere_validacion' => true,
                    'message' => 'Este número de celular ya está registrado. Para confirmar e inscribirte a este grupo, por favor proporciona los últimos 4 dígitos de tu celular.'
                ], 422);
            }

            // Comparar los últimos 4 dígitos
            $ultimosCuatroRegistrados = substr($alumnoExistente->numero_celular, -4);
            if ($request->celular_ultimos_cuatro !== $ultimosCuatroRegistrados) {
                return response()->json([
                    'message' => 'Los últimos 4 dígitos de validación son incorrectos.'
                ], 403);
            }

            // Si es correcto, lo movemos de grupo si es diferente
            if ($alumnoExistente->grupo_id !== $grupo->id) {
                $nextNumero = (Alumno::where('grupo_id', $grupo->id)->max('numero_lista') ?? 0) + 1;
                $alumnoExistente->update([
                    'grupo_id' => $grupo->id,
                    'numero_lista' => $nextNumero,
                    'nombre' => $request->nombre,
                    'apellido_paterno' => $request->apellido_paterno,
                    'apellido_materno' => $request->apellido_materno,
                    'edad' => $request->edad,
                ]);
                return response()->json([
                    'message' => "Te has inscrito con éxito al grupo {$grupo->nombre}. Tu nuevo número de lista es {$nextNumero}.",
                    'alumno' => $alumnoExistente->load('grupo')
                ]);
            } else {
                return response()->json([
                    'message' => "Ya te encuentras inscrito en el grupo {$grupo->nombre}.",
                    'alumno' => $alumnoExistente->load('grupo')
                ]);
            }
        }

        // Si no existe el celular, buscamos si hay un alumno con el mismo nombre y apellido paterno
        // en el grupo que NO tenga celular registrado para poder vincularlo
        $existingByName = Alumno::where('grupo_id', $grupo->id)
            ->whereNull('numero_celular')
            ->where('nombre', 'like', $request->nombre)
            ->where('apellido_paterno', 'like', $request->apellido_paterno)
            ->first();

        if ($existingByName) {
            $existingByName->update([
                'apellido_materno' => $request->apellido_materno ?? $existingByName->apellido_materno,
                'edad' => $request->edad ?? $existingByName->edad,
                'numero_celular' => $celular,
            ]);
            return response()->json([
                'message' => "Datos vinculados e inscripción confirmada en el grupo {$grupo->nombre}.",
                'alumno' => $existingByName->load('grupo')
            ]);
        }

        // De lo contrario, lo creamos como nuevo alumno asignándole el siguiente número de lista disponible
        $nextNumero = (Alumno::where('grupo_id', $grupo->id)->max('numero_lista') ?? 0) + 1;
        $alumno = Alumno::create([
            'grupo_id' => $grupo->id,
            'numero_lista' => $nextNumero,
            'nombre' => $request->nombre,
            'apellido_paterno' => $request->apellido_paterno,
            'apellido_materno' => $request->apellido_materno,
            'edad' => $request->edad,
            'numero_celular' => $celular,
        ]);

        return response()->json([
            'message' => "Te has inscrito con éxito al grupo {$grupo->nombre}. Tu número de lista asignado es {$nextNumero}.",
            'alumno' => $alumno->load('grupo')
        ], 201);
    }
}
