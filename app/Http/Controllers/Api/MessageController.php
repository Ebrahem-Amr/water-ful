<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function __construct( )
    {
        $this->middleware('jwt.verify:customerservice', ['only' => ['getMessageOfCustomerService', 'storeMessageOfCustomerService']]);
        $this->middleware('jwt.verify:user', ['only' => ['getMessageOfUser', 'storeMessageOfUser']]);
    }

    // public function index($userId = null)
    // {
    //     // If userId is passed, use it, otherwise use the logged in user's ID
    //     $userId = $userId ?? Auth::id();
    //     $messages = Message::where('user_id', $userId)->get();
    //     return response()->json($messages);
    // }
    // public function store(Request $request ,$user_id = null)
    // {
    //     if ($user_id == null) {
    //         $user_id = Auth::id();
    //         $customer_service_id = null;
    //     }else {
    //         $customer_service_id = Auth::id();
    //     }
    //     $validator = Validator::make($request->all(), [
    //         // 'user_id' => 'required|exists:users,id',
    //         'message' => 'required|string'
    //     ]);
    //     if($validator->fails()){
    //         return response()->json($validator->errors()->toJson(), 400);
    //     }

    //     $message = Message::create([
    //         'user_id' => $user_id,
    //         'customer_service_id' => $customer_service_id,
    //         'message' => $request->message,
    //     ]);
    //     return response()->json($message);
    // }

   
    public function getMessageOfCustomerService($userId)
    {
        $messages = Message::where('user_id', $userId)->get();
        return response()->json($messages);
    }

    public function storeMessageOfCustomerService(Request $request ,$user_id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $message = Message::create([
            'user_id' => $user_id,
            'customer_service_id' => Auth::id(),
            'message' => $request->message,
        ]);
        event(new MessageSent($message));
        return response()->json($message);
    }
    public function getMessageOfUser()
    {
        $messages = Message::where('user_id', Auth::id())->get();
        return response()->json($messages);
    }

    public function storeMessageOfUser(Request $request )
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $message = Message::create([
            'user_id' => Auth::id(),
            'customer_service_id' => null,
            'message' => $request->message,
        ]);
        event(new MessageSent($message));
        return response()->json($message);
    }
}
