<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryControllerAPI extends Controller
{
    public function getCategoriesByType($type) {
        $categories = DB::table('categories')->where('type', $type)->get();

        return $categories;
    }
}
