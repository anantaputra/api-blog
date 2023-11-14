<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index($id)
    {

        $size = 10;

        $comments = Comment::where('blog_id', $id)
            ->paginate($size);
        
        $formattedComments = $comments->map(function ($comment) {
            return [
                "id" => $comment->id,
                "author" => $comment->author->name,
                "comment" => $comment->comment,
                "updated_at" => $comment->updated_at,
            ];
        });
    
        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'data' => $formattedComments,
            'page' => [
                'size' => $size,
                'total' => $comments->total(),
                'totalPages' => $comments->lastPage(),
                'current' => $comments->currentPage(),
                'next' => $comments->nextPageUrl(),
                'previous' => $comments->previousPageUrl(),
            ],
        ], 200);

    }
    
    public function store(Request $request)
    {

        $rules = [
            'comment' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {

            return response()->json([
                'code' => 400,
                'status' => 'bad request',
                'errors' => $validator->errors()
            ], 400);

        }

        $comment = new Comment();
        $comment->user_id = auth()->user()->id;
        $comment->blog_id = $request->blog_id;
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => 'Data successfully stored'
        ], 200);

    }

    public function edit(Request $request)
    {
        
        $rules = [
            'title' => 'required',
            'content' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {

            return response()->json([
                'code' => 400,
                'status' => 'bad request',
                'errors' => $validator->errors()
            ], 400);

        }

        $comment = Comment::find($request->id);
        $comment->comment = $request->comment;
        $comment->save();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => 'Data successfully updated'
        ], 200);

    }

    public function delete($id)
    {

        $comment = Comment::find($id);
        $comment->delete();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => 'Data successfully deleted'
        ], 200);
        
    }
}
