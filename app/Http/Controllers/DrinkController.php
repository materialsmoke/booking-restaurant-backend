<?php

namespace App\Http\Controllers;

use App\Services\Drink\GetListOfDrinksInterface;
use Illuminate\Http\Request;
use App\Services\Drink\Punkapi\GetListOfDrinksService;

class DrinkController extends Controller
{
    public function index(Request $request, GetListOfDrinksInterface $getListOfDrinks)
    {
        $response = $getListOfDrinks->get($request->itemsPerPage,$request->page);

        if($response['status'] === "success"){
            return (response()->json($response['data']));
        }

        return response()->json(['error' => true, 'Message' => "Can't fetch list of drinks"]);

    }
}
