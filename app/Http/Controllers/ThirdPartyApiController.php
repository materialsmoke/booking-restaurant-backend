<?php

namespace App\Http\Controllers;

use App\Services\GetARandomMealService;
use App\Services\GetListOfDrinksService;
use Illuminate\Http\Request;

class ThirdPartyApiController extends Controller
{
    public function listOfDrinks(Request $request)
    {

        $service = new GetListOfDrinksService($request->itemsPerPage,$request->page);
        $response = $service->get();

        if($response['status'] === "success"){
            return (response()->json($response['data']));
        }

        return response()->json(['error' => true, 'Message' => "Can't fetch list of drinks"]);

    }

    public function aRandomMeal()
    {
        $service = new GetARandomMealService;
        $response = $service->get();

        if($response['status'] === "success"){
            return (response()->json($response['data']));
        }

        return response()->json(['error' => true, 'Message' => "Can't fetch a random meal"]);
    }
}
