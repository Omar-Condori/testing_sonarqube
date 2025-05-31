<?php

namespace App\Http\Controllers\API\PageGeneral;

use App\Http\Controllers\Controller;
use App\Models\Municipalidad;
use Illuminate\Http\Request;

class MunicipalidadController extends Controller
{
    public function index(Request $request)
    {
        $q = Municipalidad::query();

        if ($request->filled('departamento')) {
            $q->where('departamento', $request->departamento);
        }
        if ($request->filled('activo')) {
            $q->where('activo', filter_var($request->activo, FILTER_VALIDATE_BOOLEAN));
        }
        if ($request->filled('search')) {
            $term = $request->search;
            $q->where(fn($sub) =>
                $sub->where('nombre','like',"%{$term}%")
                    ->orWhere('codigo','like',"%{$term}%")
            );
        }

        $pag = $q->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Municipalidades obtenidas correctamente',
            'data'    => $pag,
        ]);
    }

    public function show($id)
    {
        $m = Municipalidad::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Municipalidad obtenida correctamente',
            'data'    => $m->load(['slidersPrincipales','slidersSecundarios']),
        ]);
    }

    public function store(Request $request)
    {
        $v = $request->validate(Municipalidad::rules());
        $m = Municipalidad::create($v);

        return response()->json([
            'success' => true,
            'message' => 'Municipalidad creada correctamente',
            'data'    => $m,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $m = Municipalidad::findOrFail($id);
        $v = $request->validate(Municipalidad::rules($id));
        $m->update($v);

        return response()->json([
            'success' => true,
            'message' => 'Municipalidad actualizada correctamente',
            'data'    => $m,
        ]);
    }

    public function destroy($id)
    {
        $m = Municipalidad::findOrFail($id);
        $m->delete();

        return response()->json([
            'success' => true,
            'message' => 'Municipalidad eliminada correctamente',
        ]);
    }
}
