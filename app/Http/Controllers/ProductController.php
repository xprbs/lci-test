<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::where('user_id', Auth::user()->id)->latest()->get();
        return new ProductResource(true, 'Data Product', $data);
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name_product' => 'required|string|max:255',
                'price' => 'required|numeric|between:0,99999999.99',
                'description' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) throw new \Exception($validator->errors());
            
            $image = $request->file('image');
            $image->storeAs('public/product', $image->hashName());

            $product = Product::create([
                'user_id' => Auth::user()->id,
                'name_product' => $request->name_product,
                'price' => $request->price,
                'description' => $request->description,
                'image' => $image->hashName(),
            ]);

            DB::commit();
            return new ProductResource(true, 'Product berhasil ditambahkan', $product);
        } catch (\Exception $e) {
            DB::rollback();
            Storage::delete('public/product/'.$image->hashName());
            return new ProductResource(false, 'Product Gagal ditambahkan', $e->getMessage());
        }
    }

    public function update(Request $request, Product $product)
    {
        try {
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'name_product' => 'required|string|max:255',
                'price' => 'required|numeric|between:0,99999999.99',
                'description' => 'required|string',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) throw new \Exception($validator->errors());
            if ($request->hasFile('image')) {

                $newImage = $request->file('image');
                $newImage->storeAs('public/product', $newImage->hashName());
                Storage::delete('public/product/'.$product->image);
                

                $product->update([
                    'name_product' => $request->name_product,
                    'price' => $request->price,
                    'description' => $request->description,
                    'image' => $newImage->hashName(),
                ]);
            } else {
                $product->update([
                    'name_product' => $request->name_product,
                    'price' => $request->price,
                    'description' => $request->description,
                ]);
            }

            DB::commit();
            return new ProductResource(true, 'Product berhasil diupdate', $product);
        } catch (\Exception $e) {
            DB::rollback();
            return new ProductResource(false, 'Product gagal diupdate', $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        try {
            Storage::delete('public/product/'.$product->image);
            $product->delete();
            return new ProductResource(true, 'Product berhasil dihapus!', null);
        } catch (\Exception $e) {
            return new ProductResource(true, 'Product gagal dihapus!', $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        return new ProductResource(true, 'Detail data product', $product);
    }
}
