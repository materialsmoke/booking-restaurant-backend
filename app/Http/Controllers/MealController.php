<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GetARandomMealService;
use App\Services\Meal\GetARandomMealInterface;

class MealController extends Controller
{
    public function aRandomMeal(GetARandomMealInterface $getARandomMeal)
    {
        $response = $getARandomMeal->get();

        if($response['status'] === "success"){
            return (response()->json($response['data']));
        }

        return response()->json(['error' => true, 'Message' => "Can't fetch a random meal"]);
    }
}
