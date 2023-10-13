<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParentCategory;

class CategoryController extends Controller
{
    public function index(){
        $parent_category=ParentCategory::all();
        return response()->json([
            'status'=>'success',
            'data'=>[
                'parent_category'=>$parent_category
            ]
        ]);
    }
}