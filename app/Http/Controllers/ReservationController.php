<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Services\RegisterOrLoginUserService;
use App\Http\Requests\StoreReservationRequest;

class ReservationController extends Controller
{
    private int $allTablesNumber = 10;

    public function store(StoreReservationRequest $request)
    {
        $numberOfPersons = $request->numberOfPersons;
        $email =  $request->email;
        $meal = $request->meal;
        $drink = $request->drink;
        $reservationStartTime =  $request->reservationStartTime;

        $hourCheck = (int) Carbon::make($reservationStartTime)->format('H');
        if( ($hourCheck < 16) || ($hourCheck > 20) ){
            return response()->json(['status' => 'error', 'message' => 'reservation time should be between 16:00 and 20:00']);
        }

        $time = Carbon::make($reservationStartTime);

        $numberOfFreeTables = $this->getNumberOfFreeTables($time);

        // check if we don't have enough table(s) then return error
        $neededTables = (int) ceil($numberOfPersons / 2);
        if($numberOfFreeTables < $neededTables){
            return response()->json([
                'status' => 'error', 
                'message' => "There is not enough place for $numberOfPersons persons. We only have place for " 
                    . $numberOfFreeTables * 2 . ' persons from ' . Carbon::make($reservationStartTime)->format('H:i')
                    . ' to ' . Carbon::make($reservationStartTime)->addHours(2)->format('H:i'),
                'availablePersons' => $numberOfFreeTables * 2,
            ]);
        }
        
        $service = new RegisterOrLoginUserService($email);
        $user = $service->getUser();

        // check if user already reserved a time, we won't allow to reserve the same again but one email is able to book again with different data
        $Reservation = Reservation::where('user_id', $user->id)
            ->where('user_id', $user->id)
            ->where('meal_name', $meal)
            ->where('drink_name', $drink)
            ->where('number_of_reservation', $numberOfPersons)
            ->where('reservation_start_time', Carbon::make($reservationStartTime))
            ->first();
        
        if($Reservation){
            return response()->json([
                'status' => 'error',
                'message' => 'You already booked a time with this information',
            ]);                        
        }
        
        // check if we have a free free table(s) then book the time
        $Reservation = Reservation::create([
            'user_id'=> $user->id,
            'meal_name'=> $meal,
            'drink_name'=> $drink,
            'number_of_reservation'=> $numberOfPersons,
            'reservation_start_time'=> Carbon::make($reservationStartTime),
        ]);
        
        return response()->json(new ReservationResource($Reservation));
    }

    /**
     * get number of free tables by start_hour
     */
    public function getNumberOfFreeTables(Carbon $reservationStartTime)//:int
    {
        $startTime = Carbon::make($reservationStartTime)->addHours(-2);
        $endTime = Carbon::make($reservationStartTime)->addHours(2);
        
        // $allPossibleReservationTimes = Reservation::where('reservation_start_time', '>', $startTime)
        //     ->where('reservation_start_time', '<', $endTime)->get();//not correct

        // $allAfterReservationTimes = Reservation::where('reservation_start_time', '>=', $reservationStartTime)// >= ok
        //     ->where('reservation_start_time', '<', $endTime)->get();//not correct // < ok

        // $allDuringReservationTimes = Reservation::where('reservation_start_time', '>' , $startTime) // 
        //     ->where('reservation_start_time', '<',  $reservationStartTime )->get(); // 

        //should be unique
        $allReservationTimes = Reservation::where('reservation_start_time', '>' , $startTime) // 
            ->where('reservation_start_time', '<',  $endTime )->get(); // 
        // dd($allAfterReservationTimes);
        // dd($allDuringReservationTimes);
        // dd($allReservationTimes);
        // we don't count the tables are reserved for example 1 hour ago and they are not empty!
        $numberOfReservedTable = 0;
        foreach($allReservationTimes as $item){
            $persons = $item['number_of_reservation'];
            if($persons % 2 == 1){
                $persons++;
            }
            $numberOfReservedTable += $persons;
        }

        return $this->allTablesNumber - $numberOfReservedTable / 2;
    }

    /**
     * We could keep future records and delete the old records. for better performance
     * and dispatch a job to copy all the information in a history table... but it's not part of the task...
     * so I didn't implement it...
     */
    // public function deleteOldRecodes()
    // {
    //     $time = Carbon::now();
    //     $todayTime = $time->format('Y-m-d');
    //     Reservation::where('reservation_start_time', '<', $todayTime)->delete();
    // }
}
