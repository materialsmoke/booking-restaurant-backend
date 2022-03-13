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
    // number of total tables of the restaurant
    private int $allTablesNumber = 10;

    // the store method
    public function store(StoreReservationRequest $request)
    {
        $numberOfPersons = $request->numberOfPersons;
        $email =  $request->email;
        $meal = $request->meal;
        $drink = $request->drink;
        $reservationStartTime =  $request->reservationStartTime;
        
        // check if reservation hour is between 16:00 to 20:00
        // I don't know reservation time should be a select with 3 options "16:00-18:00" and "16:00-18:00" and "16:00-18:00"?
        // or user can choose any hour, but reservation is for 2 hours? 
        // I went for second approach, because customer is more satisfied with the second one¯\_(ツ)_/¯
        $time = Carbon::make($reservationStartTime);
        $date = $time->format('Y-m-d');
        $startHour = Carbon::make($date)->format('Y-m-d 16:00');
        $endHour = Carbon::make($date)->format('Y-m-d 20:00');

        if (! Carbon::make($reservationStartTime)->between($startHour, $endHour)) {
            
            return response()->json(['status' => 'error', 'message' => "reservation time should be between $startHour and $endHour"]);
        }

        // check if reservation time is older than now(), return error.
        if($time->lessThan(now())){

            return response()->json(['status' => 'error', 'message' => "reservation time must be greater than now"]);
        }

        // get number of available table for the given time
        $numberOfFreeTables = $this->getNumberOfFreeTables($time);

        // check if we don't have enough table(s) then return error
        $neededTables = (int) ceil($numberOfPersons / 2);
        if($numberOfFreeTables < $neededTables){
            return response()->json([
                'status' => 'error', 
                'availablePersons' => $numberOfFreeTables * 2,
                'message' => "There is not enough place for $numberOfPersons persons. We only have place for " 
                    . $numberOfFreeTables * 2 . ' persons from ' . Carbon::make($reservationStartTime)->format('H:i')
                    . ' to ' . Carbon::make($reservationStartTime)->addHours(2)->format('H:i'),
            ]);
        }
        
        // register the new email if email is not in the database. I would like to have the email of the user, so it's easier to send email or etc...
        $service = new RegisterOrLoginUserService($email);
        $user = $service->getUser();

        // check if user already reserved a time, we won't allow to reserve the same again but one email is able to book again with different data
        // it's not in the project's description, I just try to prevent double submit with the exact same data...
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
        
        // reserve the time
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
        //     ->where('reservation_start_time', '<',  $reservationStartTime )->get(); // not correct

        $allReservationTimes = Reservation::where('reservation_start_time', '>' , $startTime) // 
            ->where('reservation_start_time', '<',  $endTime )->get(); // 

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
