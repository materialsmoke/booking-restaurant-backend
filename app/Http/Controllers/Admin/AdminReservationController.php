<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminReservationController extends Controller
{
    public function index()
    {
        return datatables()->of(Reservation::with('user'))->toJson();
    }
}
