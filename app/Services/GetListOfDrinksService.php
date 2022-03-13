<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GetListOfDrinksService
{
    public function __construct(private string $itemsPerPage, private string|int $page)
    {
        // $this->$itemsPerPage = $itemsPerPage;
        // $this->$page = $page;
    }

    public function get():array
    {
        $response = Http::get('https://api.punkapi.com/v2/beers?per_page='.$this->itemsPerPage.'&page=' . $this->page);
        
        if(! $response->successful()){
            return ['status' => 'error', 'message' => 'Can\'t fetch data, api has error'];
        }

        return ['data'=> $response->json(), 'status'=> 'success' ];

    }
}