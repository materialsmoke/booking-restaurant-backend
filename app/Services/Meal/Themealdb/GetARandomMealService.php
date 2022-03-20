<?php

namespace App\Services\Meal\Themealdb;

use App\Services\Meal\GetARandomMealInterface;
use Illuminate\Support\Facades\Http;

class GetARandomMealService implements GetARandomMealInterface
{
    public function get():array
    {
        $response = Http::get('https://www.themealdb.com/api/json/v1/1/random.php');
        
        if(! $response->successful()){
            return ['status' => 'error', 'message' => 'Can\'t fetch data for a random meal, api has error'];
        }

        return ['data'=> $response->json(), 'status'=> 'success' ];
    }
}