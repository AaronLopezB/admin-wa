<?php

namespace App\Http\Controllers\DashBoard;

use App\Models\Cars;
use App\Models\Code;
use App\Models\User;
use App\Models\Reservations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashBoardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function search(Request $request)
    {
        // Handles search functionality for different models based on the search query format.
        // This method inspects the incoming search query from the request. If the search string contains a colon (':'),
        // it interprets the part before the colon as a model key and the part after as the search term.
        // It supports searching across multiple models (User, Reservations, Cars, Code) with specific fields for each.
        // If the model key is not recognized, it returns an empty result.

        // Get the search string from the request
        $search = $request->search;

        // Find the position of ':' in the search string
        $pos = strpos($search, ':');

        // If ':' is found, parse the model key and search word
        if ($pos !== false) {
            // Extract model key (before ':')
            $modelKey = trim(substr($search, 0, $pos));
            // Extract search word (after ':')
            $word = trim(substr($search, $pos + 1));

            // Define supported models and their search fields
            $models = [
                'u' => [
                    'model' => User::class,
                    'fields' => ['id', 'name', 'email'],
                    'label' => 'user'
                ],
                'r' => [
                    'model' => Reservations::class,
                    'fields' => ['id', 'nombre', 'apellidos', 'email', 'telefono', 'fecha_reservacion', 'hora_reservacion', 'created', 'total', 'estatus'],
                    'label' => 'reservation',
                    'with' => ['carros']
                ],
                'p' => [
                    'model' => Cars::class,
                    'fields' => ['id', 'nombre', 'identidicador', 'location'],
                    'label' => 'product'
                ],
                'c' => [
                    'model' => Code::class,
                    'fields' => ['id', 'codigo', 'descuento'],
                    'label' => 'code'
                ],
            ];

            // If the model key is not recognized, return empty result
            if (!isset($models[$modelKey])) {
                return response()->json(['data' => [], 'model' => null], 200);
            }

            // Get model info for the given key
            $modelInfo = $models[$modelKey];
            // Start building the query for the model
            $query = $modelInfo['model']::query();

            // Eager load relationships if specified
            if (!empty($modelInfo['with'] ?? [])) {
                $query->with($modelInfo['with']);
            }

            // Add where conditions for each searchable field
            $query->where(function ($q) use ($modelInfo, $word) {
                foreach ($modelInfo['fields'] as $field) {
                    $q->orWhere($field, 'like', "%{$word}%");
                }
            });

            // Select only the specified fields
            if (isset($modelInfo['fields'])) {
                $query->select($modelInfo['fields']);
            }

            // Order results by 'id' descending and get them
            $results = $query->orderBy('id', 'DESC')->get();

            // Return the results as JSON with the model label
            return response()->json(['data' => $results, 'model' => $modelInfo['label']], 200);
        }

        // If no ':' is found, default to searching Reservations model
        $query = Reservations::with('carros')
            ->where(function ($q) use ($search) {
                // Search across these fields
                foreach (['id', 'nombre', 'apellidos', 'email', 'telefono', 'fecha_reservacion', 'hora_reservacion'] as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            })
            // Select only these fields
            ->select('id', 'nombre', 'apellidos', 'email', 'telefono', 'fecha_reservacion', 'hora_reservacion', 'created', 'total', 'estatus')
            // Order by 'id' descending
            ->orderBy('id', 'DESC')
            ->get();

        // Return the results as JSON with the model label 'reservations'
        return response()->json(['data' => $query, 'model' => 'reservations'], 200);
    }

    public function details($id, $model)
    {

        switch ($model) {
            case 'reservations':
                $query = collect($this->showReservas($id));
                break;
            case 'user':
                $query = collect($this->showUser($id));
                break;
            case 'product':
                $query = collect($this->showProduct($id));
                break;
            case 'code':
                $query = collect($this->showCode($id));
                break;
            default:
                $query = collect($this->showReservas($id));
                break;
        }
        return response()->json(['model' => $model, 'data' => $query], 200);
    }

    protected function showReservas($id)
    {
        return Reservations::select(
            'id',
            'nombre',
            'apellidos',
            'telefono',
            'email',
            'estatusfintour',
            'fecha_reservacion',
            'hora_reservacion',
            'created',
            'total',
            'note',
            'terminos',
            'licensia'
        )
            ->with([
                'carros:id,nombre', // selecciona solo ciertos campos de la relación
                'persons:id,id_reserva,persons', // selecciona solo ciertos campos de la relación
                'note.user' // selecciona solo ciertos campos de la relación
            ])
            ->find($id);
    }

    protected function showUser($id)
    {
        return User::find($id);
    }

    protected function showProduct($id)
    {
        return Cars::find($id);
    }

    protected function showCode($id)
    {
        return Code::find($id);
    }
}
