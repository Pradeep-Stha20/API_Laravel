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

        //this part is optional manual code before optimization
        // return response()->json([
        //     'status'=> true,
        //     'message' => 'ALL POST DATA.',
        //     'data' => $data,
        // ],200);
        return $this->sendResponse($data,'ALL POST DATA.');
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
                //this is also response code for fail before code sanitaion.
                // return response()->json([
                //     'status'=> false,
                //     'message' => 'Validation Error',
                //     'errors' => $validatorUser->errors()->all()
                // ],401);
                return $this->sendError('Validation Error',$validatorUser->errors()->all());


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

            //this is manual code befor optimization
            // return response()->json([
            //         'status'=> true,
            //         'message' => 'Post Created Successfully',
            //         'post'=> $post,
            //     ],200);
             return $this->sendResponse($post,'Post Created Successfully');

        
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

        //this part is manual code before optimization.
        // return response()->json([
        //     'status'=> true,
        //     'message' => 'Your Single Post',
        //     'data'=> $data,
        // ],200);
        return $this->sendResponse($data,'Your Single Post');

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
                //this is also response code for fail before code sanitaion.
                // return response()->json([
                //     'status'=> false,
                //     'message' => 'Validation Error',
                //     'errors' => $validatorUser->errors()->all()
                // ],401);
                return $this->sendError('Validation Error',$validatorUser->errors()->all());

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

            //this is response code before code sanitation.
            // return response()->json([
            //         'status'=> true,
            //         'message' => 'Post Updated Successfully',
            //         'post'=> $post,
            //     ],200);
                return $this->sendResponse($post,'Post Updated Successfully');

        
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

        //this is response code part before sanitation.
        // return response()->json([
        //     'status'=> true,
        //     'message' => 'Post has been removed Successfully',
        //     'post'=> $post,
        // ],200);

        return $this->sendResponse($post,'Post has been removed Successfully');

    }
}
