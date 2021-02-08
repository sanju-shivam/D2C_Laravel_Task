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
    	$row_seat_count = [];
        $SeatCount =0;
        for ($j=0; $j <12 ; $j++) { 
            $row_seat_count[$j]    =   Seat::where('row_id','=',$j+1)->first()->Seat_Count;
            $SeatCount += $row_seat_count[$j];
        }
        // In response Number Of seats that are booked row wise
    	return  response([
                    'status'    =>  2000,
                    'row_ids'   =>  $row_seat_count,
                    'seat_count'=>  $SeatCount
                ]);
    }

    public function book_seats(Request $request)
    {
        try{
            for ($i=1; $i <= 12; $i++){ 
                if(Seat::where('Seat_Count','>=',$request->seat)->where('row_id','=',$i)->count() == 1){
                    $seat =     Seat::where('Seat_Count','>=',$request->seat)
                                    ->where('row_id','=',$i)
                                    ->first()
                                    ->Seat_Count;

                    $success = Seat::where('row_id','=',$i)->update([
                        'Seat_Count' => $seat - $request->seat, 
                    ]);

                    $row_seat_count = [];
                    for ($j=0; $j <12 ; $j++) { 
                        $row_seat_count[$j]    =   Seat::where('row_id','=',$j+1)->first()->Seat_Count;
                    }
                    
                    return  response()
                            ->json([
                                'status'    =>  2001,
                                'row_ids'   =>  $row_seat_count,
                            ]);
                }
            }

            $seat_required  = $request->seat;
            for ($i=1; $i <= 12; $i++){
                $seat_left      = Seat::where('row_id','=',$i)->first()->Seat_Count;
                $seat_required  = $seat_required - $seat_left;

                if($seat_required > 0){
                    Seat::where('row_id','=',$i)->update([
                        'Seat_Count' => 0,
                    ]);
                }else{
                    Seat::where('row_id','=',$i)->update([
                        'Seat_Count' =>  abs($seat_required),
                    ]);
                    $row_seat_count = [];
                    for ($j=0; $j <12 ; $j++) { 
                        $row_seat_count[$j]    =   Seat::where('row_id','=',$j+1)->first()->Seat_Count;
                    }
                    return  response()
                            ->json([
                                'status'    =>  2002,
                                'row_ids'   =>  $row_seat_count,
                            ]);
                    break;
                }
            }
        }
        catch(\Exception $error){
            return  response()
                    ->json([
                        'status'=>400,
                        "error" => $error->getMessage()
                    ]);
        }
    }

    public function reset()
    {
        Seat::find(1)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(2)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(3)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(4)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(5)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(6)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(7)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(8)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(9)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(10)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(11)->update([
            'Seat_Count' => 7
        ]);
        Seat::find(12)->update([
            'Seat_Count' => 3
        ]);

        return response()->json(['status' => 2003]);
    }
}
