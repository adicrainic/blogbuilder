<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $request){
        return Tag::create([
                'tagName' => $request->tagName
            ]
        );
    }

    public function get_tags(Request $request){
        return Tag::orderBy('id','desc')->get();
    }
}

//https://www.youtube.com/watch?v=X08Y_8DkRNc
