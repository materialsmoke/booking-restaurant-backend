<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Reservation::insert([
            ['number_of_reservation'=> '10','reservation_start_time'=> Carbon::make("16:00"),'user_id'=> '1','meal_name'=> 'meal 1','drink_name'=> 'drink 1',],
            ['number_of_reservation'=> '8','reservation_start_time'=> Carbon::make("16:00"),'user_id'=> '1','meal_name'=> 'meal 1','drink_name'=> 'drink 1',],
            
            ['number_of_reservation'=> '2','reservation_start_time'=> Carbon::make("18:00"),'user_id'=> '1','meal_name'=> 'meal 1','drink_name'=> 'drink 1',],
            ['number_of_reservation'=> '3','reservation_start_time'=> Carbon::make("18:00"),'user_id'=> '1','meal_name'=> 'meal 1','drink_name'=> 'drink 1',],
            ['number_of_reservation'=> '2','reservation_start_time'=> Carbon::make("19:00"),'user_id'=> '1','meal_name'=> 'meal 1','drink_name'=> 'drink 1',],
            ['number_of_reservation'=> '2','reservation_start_time'=> Carbon::make("20:00"),'user_id'=> '1','meal_name'=> 'meal 1','drink_name'=> 'drink 1',],
            ['number_of_reservation'=> '7','reservation_start_time'=> Carbon::make("20:00"),'user_id'=> '1','meal_name'=> 'meal 1','drink_name'=> 'drink 1',],
        ]);
    }
}
