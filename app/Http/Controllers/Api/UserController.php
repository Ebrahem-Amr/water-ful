<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('jwt.verify:user', ['only' => ['logout', 'refresh','adminProfile']]);
        $this->middleware('assign.guard:user', ['only' => ['login', 'register','verify','index']]);
    }
    
    public function index(){
        $user=User::Get();
        return response()->json($user);

    }


   
    public function login(Request $request){
        

    	$validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token); 
        
    }


   
    public function register(Request $request ,TwilioService $twilioService) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $verificationCode = rand(100000, 999999);

        // $twilioService->sendSms($request->email, "Your verification code is $verificationCode");

        session(['verification_code' => $verificationCode]);


        return response()->json(['message' => 'Verification code = '.$verificationCode], 200);

        
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required|string|min:6',
            'verification_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if(Session::has('verification_code')){

            if ($request->verification_code == session('verification_code')) {
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);
    
                session()->forget('verification_code');
    
                return response()->json(['message' => 'Verification successful and User successfully registered.', 'user' => $user], 200);
            } else {
                return response()->json(['error' => 'The verification code is incorrect. '.session('verification_code')], 422);
            }
        }else{
            return response()->json(['message' => 'No verification code found in session'], 404);
        }

        
    }
    
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    

    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }
    
    
    public function userProfile() {
        return response()->json(auth()->user());
    }
    
    
    protected function createNewToken($token){
        return response()->json([
            'user' => auth()->user()->name,
            'token' => $token,
            
        ]);
    }
}
