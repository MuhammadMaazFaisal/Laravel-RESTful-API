<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{

    private function checkAllowedFields(Request $request, array $allowedFields)
    {
        $requestData = $request->all();

        if (empty($allowedFields) && !empty($requestData)) {
            return response()->json([
                'status' => 400,
                'message' => 'No fields are allowed',
            ]);
        }
        $invalidFields = array_diff(array_keys($request->all()), $allowedFields);
        if (!empty($invalidFields)) {
            return response()->json([
                'status' => 400,
                'message' => 'Undefined fields: ' . implode(', ', $invalidFields),
            ]);
        }
        return null;
    }

    public function index(Request $request)
    {
        $response = $this->checkAllowedFields($request, []);
        if ($response) return $response;
        $products = Product::with('parent_category')->get();
        if (count($products) == 0) {
            return response()->json([
                'status' => 404,
                'message' => 'No products found'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'data' => [
                    'products' => $products
                ]
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        $response = $this->checkAllowedFields($request, []);
        if ($response) return $response;
        $products = Product::find($id);
        if (!$products) {
            return response()->json([
                'status' => 404,
                'message' => 'No products found with id ' . $id
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'data' => [
                    'products' => $products
                ]
            ]);
        }
    }

    public function store(Request $request)

    {

        $response = $this->checkAllowedFields($request, ['product_name', 'price', 'parent_category_id']);
        if ($response) return $response;

        $validation = Validator::make($request->all(), [
            'product_name' => 'required|unique:products|max:255',
            'price' => 'required|numeric',
            'parent_category_id' => [
                'required',
                'numeric',
                Rule::exists('parent_categories', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
        ]);


        if ($validation->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validation->errors()
            ]);
        }
        $product = new Product;
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->parent_category_id = $request->parent_category_id;
        if ($product->save()) {
            return response()->json([
                'status' => 200,
                'message' => 'Product added successfully',
                'data' => $product
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Product could not be added',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $response = $this->checkAllowedFields($request, ['product_name', 'price', 'parent_category_id']);
        if ($response) return $response;

        $validation = Validator::make($request->all(), [
            'product_name' => 'sometimes|unique:products|max:255',
            'price' => 'sometimes|numeric',
            'parent_category_id' => [
                'sometimes',
                'numeric',
                Rule::exists('parent_categories', 'id')->where(function ($query) {
                    $query->where('status', 1);
                }),
            ],
        ]);
        if ($validation->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validation->errors()
            ]);
        }
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product with id ' . $id . ' not found'
            ]);
        } else {
            $product->product_name = $request->product_name ? $request->product_name : $product->product_name;
            $product->price = $request->price ? $request->price : $product->price;
            $product->parent_category_id = $request->parent_category_id ? $request->parent_category_id : $product->parent_category_id;
            if ($product->save()) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Product updated successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Product could not be updated',
                ]);
            }
        }
    }

    public function destroy(Request $request, $id)
    {
        $response = $this->checkAllowedFields($request, []);
        if ($response) return $response;

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product with id ' . $id . ' not found'
            ]);
        }
        if (!$product->delete()) {
            return response()->json([
                'status' => 400,
                'message' => 'Product could not be deleted',
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'message' => 'Product deleted successfully',
            ]);
        }
    }
}
