<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ParentCategory;


class Product extends Model
{
    use HasFactory;

    public function parent_category()
    {
        return $this->belongsTo(ParentCategory::class);
    }
}
