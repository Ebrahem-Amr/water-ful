<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomerServiceController extends Controller
{
    //
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('jwt.verify:customerservice', ['only' => ['logout', 'refresh','customerserviceProfile']]);
        $this->middleware('assign.guard:customerservice', ['only' => ['login', 'register','index']]);
    }

    public function index(){
        $customerservice=CustomerService::Get();
        return response()->json($customerservice);

    }


   
    public function login(Request $request){
        

    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => $token], 401);
        }
        return $this->createNewToken($token); 
        
    }


   
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:100|unique:customer_services',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        $customerservice = CustomerService::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'CustomerService successfully registered',
            'customerservice' => $customerservice
        ], 201);
    }

    
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'CustomerService successfully signed out']);
    }
    

    public function refresh() {
        return $this->createNewToken(Auth::refresh());
    }
    
    
    public function customerserviceProfile() {
        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(auth()->user());
    }
    
    
    protected function createNewToken($token){
        return response()->json([
            'user' => auth()->user()->name,
            'token' => $token,
            
        ]);
    }
}
