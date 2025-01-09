<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();
        return response()->json([
            'status'=> true,
            'message' => 'ALL POST DATA.',
            'data' => $data,
        ],200);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatorUser = Validator::make(  // we created variable which checks for validation
            $request->all(),
            [
                'title'=> 'required',
                'description'=> 'required',
                'image'=> 'required|mimes:png,jpg,jpeg,gif',
            ]
            );
            if($validatorUser->fails()){
                return response()->json([
                    'status'=> false,
                    'message' => 'Validation Error',
                    'errors' => $validatorUser->errors()->all()
                ],401);
            }

            $img = $request->image;
            $ext = $img->getClientOriginalExtension();
            $imageName = time().'.'. $ext;
            $img->move(public_path().'/uploads', $imageName);

            $post = Post::create([
                'title'=> $request->title,
                'description'=> $request->description,
                'image'=> $imageName,
                ]);
            return response()->json([
                    'status'=> true,
                    'message' => 'Post Created Successfully',
                    'post'=> $post,
                ],200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::select(
            'id',
            'title',
            'description',
            'image',
        )->where(['id' => $id])->get();

        return response()->json([
            'status'=> true,
            'message' => 'Your Single Post',
            'data'=> $data,
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatorUser = Validator::make(  // we created variable which checks for validation
            $request->all(),
            [
                'title'=> 'required',
                'description'=> 'required',
                'image'=> 'required|mimes:png,jpg,jpeg,gif',
            ] 
        );

            if($validatorUser->fails()){
                return response()->json([
                    'status'=> false,
                    'message' => 'Validation Error',
                    'errors' => $validatorUser->errors()->all()
                ],401);
            }

            $postImage = Post::select('id','image')
            ->where(['id'=> $id])->get();

            if($request->image != ''){
                $path = public_path().'/uploads';

                if($postImage[0]->image != '' && $postImage[0]->image != null){
                    $old_file = $path.$postImage[0]->image;
                    if(file_exists($old_file)){
                        unlink($old_file);
                    }
                }
                $img = $request->image;
                $ext = $img->getClientOriginalExtension();
                $imageName = time().'.'. $ext;
                $img->move(public_path(). '/uploads', $imageName);

            }else{
                $imageName = $postImage->image;
            }

            $post = Post::where(['id'=> $id])->update([
                'title'=> $request->title,
                'description'=> $request->description,
                'image'=> $imageName,
                ]);
            return response()->json([
                    'status'=> true,
                    'message' => 'Post Updated Successfully',
                    'post'=> $post,
                ],200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagePath = Post::select('image')->where('id', $id)->get ();
        $filepath = public_path().'/uploads/'.$imagePath[0]['image'];

        unlink($filepath);
        
        $post = Post::where('id',$id)->delete();


        return response()->json([
            'status'=> true,
            'message' => 'Post has been removed Successfully',
            'post'=> $post,
        ],200);
    }
}
