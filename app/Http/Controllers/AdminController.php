<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function addTag(Request $request){
        $this->validate($request, [
           'tagName' => 'required'
        ]);
        return Tag::create([
                'tagName' => $request->tagName
            ]
        );
    }

    public function editTag(Request $request){
        $this->validate($request, [
            'tagName' => 'required',
            'id' => 'required',
        ]);
        return Tag::where('id' ,$request->id)->update([
                'tagName' => $request->tagName
            ]
        );
    }

    public function deleteTag(Request $request){
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Tag::where('id' ,$request->id)->delete();
    }

    public function get_tags(Request $request){
        return Tag::orderBy('id','desc')->get();
    }

    public function upload(Request $request){
        $this->validate($request, [
            'file' => 'required|mimes:jpeg,jpg,png'
        ]);
        $picName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'),$picName);
        return $picName;
    }

    public function delete_image(Request $request) {
        $imageName = $request->imageName;
        $this->deleteFileFromServer($imageName);
    }

    public function deleteFileFromServer($fileName){
        $imagePath = public_path().'/uploads/'.$fileName;
        if(file_exists($imagePath)) {
            @unlink($imagePath);
        }
    }

    public function addCategory(Request $request){
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);


        return Category::create([
                'categoryName' => $request->categoryName,
                'iconImage' => $request->iconImage
            ]
        );
    }

}

//https://youtu.be/rZ0gLHPmBFw?t=535
