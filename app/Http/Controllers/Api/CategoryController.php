<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    public function __construct() {
        $this->middleware('jwt.verify:admin', ['except' => ['index']]);
    }

    public function index(){
        $Categories=Category::with('products')->get();
        return response()->json($Categories);

    }

    
    public function create_category(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $category = Category::create($validator->validated());

        return response()->json([
            'message' => 'Admin successfully create category',
            'category' => $category
        ], 201);
    }

    public function delete_category($id){
        $categoryId = $id;
        
        $category = Category::find($categoryId);
        // Delete category
        $category->delete();
    
        return response()->json([
            'message' => 'Category deleted successfully'
        ], 201);
    }
    

    public function update_category($id, Request $request){
        $categoryId = $id;
        
        // Find the category
        $category = Category::find($categoryId);
    
        // Check the existence of the category
        if (!$category) {
            return response()->json(['error' => 'Category not found']);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories',
        ]);
        if($validator->fails()){ 
            return response()->json($validator->errors()->toJson(), 400);
        }
        
        // Update the category with the new data
        $category->update($validator->validated());
    
        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }
    


}
