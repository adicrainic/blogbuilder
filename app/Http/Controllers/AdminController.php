<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AdminController extends Controller
{
    public function index(Request $request) {
        if(!Auth::check() && $request->path() != 'login'){
            return redirect('/login');
        }

        if(!Auth::check() && $request->path() == 'login'){
            return view('welcome');
        }

        $user = Auth::user();
        if($user->role->isAdmin == 0) {
            return redirect('/login');
        }
        if($request->path() == 'login') {
            return redirect('/');
        }

        return $this->checkForPermission($user, $request);

    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    public function checkForPermission($user, $request) {
        $permission = json_decode($user->role->permission);
        $hasPermission = false;
        if($permission) {
            foreach ($permission as $p) {
                if ($p->name == $request->path()) {
                    if ($p->read) {
                        $hasPermission = true;
                    }
                }
            }
        }
        if($hasPermission)
            return view('welcome');

        return view('welcome');

    }

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
        return User::all();
    }

    public function addAdmin(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => 'bail| required | email | unique:users',
            'password' => 'bail | required | min:6',
            'role_id' => 'required'
        ]);

        $password = bcrypt($request->password);


        return User::create([
                'fullName' => $request->fullName,
                'email' => $request->email,
                'password' => $password,
                'role_id' => $request->role_id
            ]
        );
    }
    public function editAdmin(Request $request){
        $this->validate($request, [
            'fullName' => 'required',
            'email' => "bail | required | email | unique:users,email,{$request->id}",
            'password' => 'min:6',
            'role_id' => 'required'
        ]);

        $data = [
            'fullName' => $request->fullName,
            'email' => $request->email,
            'role_id' => $request->role_id
        ];
        if($request->password) {
            $password = bcrypt($request->password);
            $data['password'] = $password;
        }

        return User::where('id' ,$request->id)->update($data);
    }

    public function adminLogin(Request $request) {
        $this->validate($request, [
            'email' => "bail|required|email",
            'password' => 'bail|required|min:6'
        ]);
        $credentials = $request->only('email', 'password');

        if(Auth::attempt ($credentials)) {
            $user = Auth::user();
            if($user->role->isAdmin == 0) {
                Auth::logout();
                return response()->json([
                    'msg' => 'Incorrect login details !'
                ],401);
            }
            return response()->json([
                'msg' => 'You are logged in',
                'user' => $user
            ]);

        }else{
            return response()->json([
                'msg' => 'Incorrect login details !'
            ],401);
        }
    }

    public function deleteAdmin(Request $request){
        $this->validate($request, [
            'id' => 'required',
        ]);
        return User::where('id' ,$request->id)->delete();
    }

    public function get_roles(Request $request){
        return Role::all();
    }

    public function addRole(Request $request){
        $this->validate($request, [
            'name' => 'required'
        ]);

        return Role::create([
                'name' => $request->name
            ]
        );
    }

    public function editRole(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'id' => 'required',
        ]);
        return Role::where('id' ,$request->id)->update([
                'name' => $request->name
            ]
        );
    }

    public function deleteRole(Request $request){
        $this->validate($request, [
            'id' => 'required',
        ]);
        return Role::where('id' ,$request->id)->delete();
    }

    public function assignRole(Request $request){
        $this->validate($request, [
            'permission' => 'required',
        ]);
        return Role::where('id' ,$request->id)->update([
                'permission' => $request->permission
            ]
        );
    }

    public function uploadEditorImage(Request $request){
        $this->validate($request, [
           'image' => 'required|mimes:jpeg,jpg,png'
        ]);

        $picName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads'),$picName);
        $fileUrl = env('APP_URL').":".env("APP_PORT")."/uploads/$picName";
        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $fileUrl,

            ],
        ]);
    }

    public function slug(){
        $title = 'This is the title';
        return Blog::create([
           'title' => $title,
           'post' => 'some post',
           'post_excerpt' => 'abc',
           'user_id' => 1,
           'metaDescription' => 'abc'
        ]);
    }

    public function createBlog(Request $request){
        return Blog::create([
            'title' => $request->title,
            'post' => $request->post,
            'post_excerpt' => $request->post_excerpt,
            'user_id' => Auth::user()->id,
            'metaDescription' => $request->metaDescription,
            'jsonData' => $request->jsonData
        ]);
    }
}

