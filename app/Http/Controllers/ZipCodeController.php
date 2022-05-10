<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZipCodeResource;
use App\Models\ZipCodeModel;
use Illuminate\Http\Request;

class ZipCodeController extends Controller
{
    public function get_zip_code()
    {
        $zip_code = request('zip_code');

        if( !filter_var($zip_code, FILTER_VALIDATE_INT) )
            abort(404);

        $repsonse =(new ZipCodeResource(
            ZipCodeModel::with([
                'municipality','federal_entity','settlements' => function($query){
                    $query->with('settlement_type');
                }
            ])
                ->where('zip_code',$zip_code)
                ->first()
        ));

        return response()->json(
            $repsonse,200,
            [
                'Content-Type' => 'application/json; charset=UTF-8'
            ]
        );
    }
}
