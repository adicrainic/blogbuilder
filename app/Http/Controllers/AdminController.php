<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
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

    public function get_category(Request $request) {
        return Category::orderBy('id','desc')->get();

    }

    public function deleteCategory(Request $request){
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Category::where('id' ,$request->id)->delete();
    }

    public function editCategory(Request $request){
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required',
            'id' => 'required',
        ]);
        return Category::where('id' ,$request->id)->update([
                'categoryName' => $request->categoryName,
                'iconImage' => $request->iconImage
            ]
        );
    }

    public function get_admins(Request $request){
        return User::where('userType','!=','User')->get();
    }

    public function addAdmin(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail| required | email | unique:users',
            'password' => 'bail | required | min:6',
            'userType' => 'required'
        ]);

        $password = bcrypt($request->password);


        return User::create([
                'fullName' => $request->fullName,
                'email' => $request->email,
                'password' => $password,
                'userType' => $request->userType
            ]
        );
    }
    public function editAdmin(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail | required | email | unique:users,email,{$request->id}",
            'password' => 'min:6',
            'userType' => 'required'
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'userType' => $request->userType
        ];
        if($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        return User::where('id' ,$request->id)->update($data);
    }
}

