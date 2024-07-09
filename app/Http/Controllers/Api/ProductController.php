<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //

    public function __construct() {
        $this->middleware('jwt.verify:admin', ['except' => ['index']]);
    }

    public function index(){
        $products=Product::Get();
        return response()->json($products);

    }

    
    public function create_product(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products',
            'type' => 'required|string',
            'price' => 'required|integer',
            'Quantity' => 'required|integer',
            'duration' => 'string',
            'image' => 'required|file|image|max:2048',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $path = $request->file('image')->store('images');
        $validatedData = $validator->validated();
        $validatedData['image'] = $path; 


        $product = Product::create($validatedData);

        return response()->json([
            'message' => 'Admin successfully create product',
            'product' => $product
        ], 201);
    }

    public function delete_product($id){
        $productId = $id;
        
        // Get the model of the specified department
        $product = Product::find($productId);

    
        // Check the existence of the department
        if (!$product) {
            return response()->json([
                'error' => 'product not found'
            ], 404);
        }
        if (Storage::exists($product->image)) 
            Storage::delete($product->image);
    
        // Delete product
        $product->delete();
    
        return response()->json([
            'message' => 'Product deleted successfully'
        ], 201);
    }
    

    public function update_product($id, Request $request){
        $productId = $id;
        
        // Find the product
        $product = Product::find($productId);
    
        // Check the existence of the product
        if (!$product) {
            return response()->json(['error' => 'Product not found']);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products',
            'type' => 'required|string',
            'price' => 'required|integer',
            'Quantity' => 'required|integer',
            'duration' => 'string',
            'image' => 'required|file|image|max:2048',
        ]);
        if($validator->fails()){
            
            return response()->json($validator->errors()->toJson(), 400);
        }
        if (Storage::exists($product->image)) 
            Storage::delete($product->image);
    
        $path = $request->file('image')->store('images');
        $validatedData = $validator->validated();
        $validatedData['image'] = $path; 

        // Update the product with the new data
        $product->update($validatedData);
    
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ]);
    }
    


   

}
