<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    

    public function createProduct(Request $request) {
        $this->validate($request, [
            'item_name' => 'required|unique:products',
            'quantity_brought' => 'required',
            'buying_price' => 'required',
            'selling_price' => 'required'
        ]);

        $product = Product::create([
            'item_name' => $request->item_name,
            'quantity_brought' => $request->quantity_brought,
            'buying_price' => $request->buying_price,
            'selling_price' => $request->selling_price
        ]);

        if($product) {
            return response()->json(['success' => true, 'message' => 'Successfully Created A Product'], 200);
        }
        return User::create([
            'item_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $request->photo,
            'type' => $request->type,
            'bio' => $request->bio,
        ]);
    }

    public function updateProduct(Request $request) {
        $this->validate($request, [
            'item_name' => 'required|unique:products,item_name,' . $request->id,
            'quantity_brought' => 'required',
            'buying_price' => 'required',
            'selling_price' => 'required'
        ]);

        $product = Product::findOrFail($request->id);

        $product->item_name = $request->item_name;
        $product->quantity_brought = $request->quantity_brought;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;

        $product->save();

        return response()->json(['success' => true, 'message' => 'Successfully Updated A Product'], 200);
    }

    public function getAllProducts() {
       return ProductResource::collection(Product::latest()->paginate(10));
    }

    public function searchingProduct()
    {
        if ($search = \Request::get('q')) {
            return ProductResource::collection(Product::where(function($query) use ($search) {
                $query->where('item_name','LIKE',"%$search%");
            })->paginate(10));
        }else {
            return ProductResource::collection(Product::latest()->paginate(10));

        }
    }


}
