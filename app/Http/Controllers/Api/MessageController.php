<?php

namespace App\Http\Controllers\Api;

use App\Events\NewChatMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Helper\Helper;

class MessageController extends Controller
{
    public function broadcast(Request $request) {

        if (! $request->filled('message')) {
            return Helperr::responseJson(422 , 'failed' , 'No message to send' , null,null,422);
            // return response()->json([
            //     'message' => 'No message to send'
            // ], 422);
        }

        // TODO: Sanitize input

        event(new NewChatMessage($request->from, $request->to, $request->order_id, $request->message));

        // return response()->json([], 200);
        return Helper::responseJson(200 , 'success', null , null ,null, 200 );

    }




    function create_message(Request $request)
    {
        try {
            
            $messageContent = base64_encode($request->message); 
            $message = Message ::create([
                'message' => $messageContent ,
                'user_id' => $request->user_id ,
                'driver_id' => $request->driver_id
            ]);
            return Helper::responseJson(200 ,'success' , null , null , $message , 200 );

            // response()->json([
            //     'status' => 200,
            //     'message' => 'success',
            //     'result' => $message,
            // ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return Helper::responseJson(422 ,'failed' , $th->getMessage() , $th->getCode() , null , 422 );
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
