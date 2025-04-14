<?php
namespace App\Http\Controllers;

use App\Models\Pais;
use Illuminate\Http\Request;

class ApiViagemController extends Controller
{
    public function listPais(Request $request)
    {
        $paises = Pais::orderBy('nome', 'asc')->get();

        return response()->json($paises);
    }
}
