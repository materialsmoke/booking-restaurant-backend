<?php

namespace App\Services\Drink;

interface GetListOfDrinksInterface
{
    public function get(string $itemPerPage, string|int $page):array;
}