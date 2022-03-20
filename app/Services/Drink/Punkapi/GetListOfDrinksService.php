<?php

namespace App\Services\Drink\Punkapi;

use Illuminate\Support\Facades\Http;
use App\Services\Drink\GetListOfDrinksInterface;

class GetListOfDrinksService implements GetListOfDrinksInterface
{

    public function get(string $itemsPerPage, string|int $page):array
    {
        $response = Http::get('https://api.punkapi.com/v2/beers?per_page='.$itemsPerPage.'&page=' . $page);
        
        if(! $response->successful()){
            return ['status' => 'error', 'message' => 'Can\'t fetch data, api has error'];
        }

        return ['data'=> $response->json(), 'status'=> 'success' ];
    }
}
