<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
    //Like or Unlike
    public function likeOrUnlike($id){
        $post = Post::find($id);

        if(!$post){
            return response([
                'message' => 'Post not found'
            ],403);
        }

        $like = $post->like()->where('user_id',auth()->user()->id)->first();

        //if not like then like
        if(!$like){
            Like::create([
                'post_id'=>$id,
                'user_id'=>auth()->user()->id
            ]);

            return response([
                'message' => 'Liked'
            ],200);
        }
        //else dislike it
        $like->delete();
        return response([
            'message' => 'Disliked'
        ],200);
    }
}
