<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserCartController extends Controller
{
    //
    public function __construct() {
        // $this->middleware('assign.guard:user');
        $this->middleware('jwt.verify:user');
    }
    
    public function index()
    {
        $cartItems = UserCart::where('user_id', Auth::id())->get();
        return response()->json($cartItems);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $cartItem = UserCart::where('user_id', Auth::id())
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            UserCart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['success' => 'Product added to cart']);
    }

    public function delete($id)
    {
        $cartItem = UserCart::find($id);
        if ($cartItem && $cartItem->user_id == Auth::id()) {
            $cartItem->delete();
            return response()->json(['success' => 'Product removed from cart']);
        }
        return response()->json(['error' => 'Invalid cart item'], 404);
    }

}
