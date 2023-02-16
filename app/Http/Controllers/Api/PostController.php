<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponseTrait;

    public function index(){

        $posts = PostResource::collection(Post::get());

        return $this->apiResponse($posts,'ok',200);
    }

    public function show($id){

        // $posts = Post::find($id);
        $posts = Post::find($id);
        if($posts){
            return $this->apiResponse(new PostResource($posts,'ok',200));
        }
        return $this->apiResponse(null,'this post not found',404);
    }

    public function store(Request $request){


        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse($validator->errors(),'The post Not Save',400);
        }

        $post = Post::create($request->all());

        if($post){
            return $this->apiResponse(new PostResource($post),'The post Save',201);
        }

    }

}
