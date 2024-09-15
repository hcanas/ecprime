<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MeasurementUnit;
use Illuminate\Http\Request;

class MeasurementUnitSuggestionController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json(MeasurementUnit::where('name', 'LIKE', '%'.$request->get('query').'%')
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->pluck('name')
        );
    }
}
