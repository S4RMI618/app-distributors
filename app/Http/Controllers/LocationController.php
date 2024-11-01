<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LocationController extends Controller
{
    public function getMunicipalities(Request $request)
    {
        // Obtener el departamento seleccionado desde la solicitud
        $department = $request->input('department');
        
        // Leer el archivo JSON con los departamentos y municipios
        $json = File::get(resource_path('json/departments_municipalities.json'));
        $data = json_decode($json, true);
        
        // Verificar si existe el departamento en el archivo JSON
        if (array_key_exists($department, $data)) {
            $municipalities = $data[$department];

            sort($municipalities);
            return response()->json($municipalities);
        }

        // En caso de no encontrar el departamento
        return response()->json([], 404);
    }
}
