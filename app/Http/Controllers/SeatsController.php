<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seat;
use DB;

class SeatsController extends Controller
{
	// Fetch All the number of booked seats 
    public function fetch_all_seats()
    {
        header('Access-Control-Allow-Origin','*');
    	$SeatBooked	=	Seat::all();
        if(empty($SeatBooked)){
            $SeatBooked = 0;
        }
        // In response Number Of seats that are booked
    	return response()->json($SeatBooked);
    }

    public function book_seats(Request $request)
    {
        header('Access-Control-Allow-Origin','*');
    	try{
    		// Count The Number Of seats Already Booked 
    		$SeatBooked	=	Seat::select('Seat_Count')->first();

    		// For The First Time Till No Seat Is Booked This Function runs
    		if(empty($SeatBooked)){
    			Seat::create([
    				'Seat_Count'	=>	$request->seat,
    			]);
                // In response  
                    #New_Seats_Booked contains New added seats
    			return 	response()
    				->json([
    					'status'			=>	200,
    					'SeatAlreadyBooked'	=>	0,
    					'New_Seats_Booked'	=>	$request->seat,
    				]);
    		}

    		// Update SeatBooked Count In Table
    		Seat::find(1)->update([
    			'Seat_Count'	=>	$SeatBooked->Seat_Count+$request->seat,
    		]);

    		// In response 
    			#SeatAlreadyBooked contain Count of seats booked seats before new booking 
                #New_Seats_Booked contains New added seats
    		return 	response()
    				->json([
    					'status'			=>	200,
    					'SeatAlreadyBooked'	=>	$SeatBooked->Seat_Count,
    					'New_Seats_Booked'	=>	$request->seat,
    				]);
    	}
    	catch(\Exception $error){
            header('Access-Control-Allow-Origin','*');
    		return 	response()
    				->json([
    					'status'=>400,
    					"error" => $error->getMessage()
    				]);
    	}
    }
}
