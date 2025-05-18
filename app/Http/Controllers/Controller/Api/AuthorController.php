<?php

namespace App\Http\Controllers\Controller\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;

class AuthorController extends Controller
{

    public function index()
    {
        $authors = Author::select('id', 'name')->get();
        return response()->json($authors);
    }


}
