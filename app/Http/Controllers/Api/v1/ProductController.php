<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json([
            'status' => 'succes',
            'data' => [
                'products' => $products
            ]
        ]);
    }

    public function show($id)
    {
        $products = Product::all();
        return response()->json([
            'status' => 'success',
            'data' => [
                'products' => $products
            ]
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => [
                    'id' => 6,
                    'name' => $request->name,
                    'price' => $request->price
                ]
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => [
                    'id' => $id,
                    'name' => $request->name,
                    'price' => $request->price
                ]
            ]
        ]);
    }

    public function destroy($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => [
                    'id' => $id,
                    'name' => 'Product ' . $id,
                    'price' => $id * 1000
                ]
            ]
        ]);
    }
}