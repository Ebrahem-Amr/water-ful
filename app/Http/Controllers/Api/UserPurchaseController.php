<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserPurchaseController extends Controller
{
    //
    public function __construct() {
        $this->middleware('jwt.verify:user', ['except' => ['index']]);
        $this->middleware('jwt.verify:admin', ['only' => ['index']]);
    }

    // Display all purchases for all users
    public function index()
    {
        $purchases = UserPurchase::with('user', 'product')->get();
        return response()->json($purchases);
    }

    // Display purchases for a specific user
    public function userPurchases()
    {
        $purchases = UserPurchase::where('user_id', Auth::id())->get();
        return response()->json($purchases);
    }

    // Add a new purchase
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product = UserPurchase::where('user_id', Auth::id())
                        ->where('product_id', $request->product_id)
                        ->first();

        if ($product) {
            $product->quantity += $request->quantity;
            $product->save();
        } else {
            UserPurchase::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['success' => 'Product purchased successfully']);
    }

    public function delete($id)
    {
        $purchase = UserPurchase::find($id);
        if ($purchase && $purchase->user_id == Auth::id()) {
            $purchase->delete();
            return response()->json(['success' => 'Purchase removed successfully']);
        }
        return response()->json(['error' => 'Invalid purchase item'], 404);
    }
}
