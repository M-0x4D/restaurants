<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use App\Helper\Helper;

class RoomController extends Controller
{
    function create_room(Request $request)
    {
        try {
            $room = Room::create([
                'room_id' => $request->room_id,
                'user_id' => $request->user_id,
                'driver_id' => $request->driver_id,
            ]);
            return Helper::responseJson(200 , 'success' , null , null , $room , 200);
            // return response()->json([
            //     'status' => 200,
            //     'message' => 'success',
            //     'result' => $room,
            // ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return Helper::responseJson(422 , 'failed' , $th->getMessage() , $th->getCode() , null , 422);
            // return response()->json([
            //     'status' => 422,
            //     'message' => $th->getMessage(),
            //     'errors' => $th->getCode(),
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }
        
    }

    
    function return_rooms(Request $request)
    {
        try {
            //code...
            $rooms = Room::where('user_id' , $request->user()->id)->get();
            return Helper::responseJson(200 , 'success' , null , null , $rooms , 200);
            // return response()->json([
            //     'status' => 200,
            //     'message' => 'success',
            //     'result' => $rooms,
            // ], 200);
        } catch (\Throwable $th) {
            //throw $th;
return Helper::responseJson(422 , 'failed' ,$th->getMessage() , $th->getCode() , null , 422 );
            // return response()->json([
            //     'status' => 422,
            //     'message' => $th->getMessage(),
            //     'errors' => $th->getCode(),
            //     'result' => 'failed',
            //     'data' => null
            // ], 422);
        }
    }


}
