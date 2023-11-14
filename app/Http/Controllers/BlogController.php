<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index() : JsonResponse
    {

        $size = 10;

        $blogs = Blog::with('author:id,name')->paginate($size);

        $formattedBlogs = $blogs->map(function ($blog) {
            return [
                "id" => $blog->id,
                "author" => $blog->author->name,
                "title" => $blog->title,
                "slug" => $blog->slug,
                "content" => $blog->content,
                "updated_at" => $blog->updated_at,
            ];
        });
    
        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'data' => $formattedBlogs,
            'page' => [
                'size' => $size,
                'total' => $blogs->total(),
                'totalPages' => $blogs->lastPage(),
                'current' => $blogs->currentPage(),
                'next' => $blogs->nextPageUrl(),
                'previous' => $blogs->previousPageUrl(),
            ],
        ], 200);

    }

    public function detail($id)
    {
        
        $blog = Blog::findBySlug($id);
        
        if(!$blog) {
            $blog = Blog::find($id);
        }

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'data' => [
                "id" => $blog->id,
                "title" => $blog->title,
                "slug" => $blog->slug,
                "content" => $blog->content,
                "author" => $blog->author->name,
            ],
        ], 200);

    }

    public function store(Request $request)
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

        $blog = new Blog();
        $blog->user_id = auth()->user()->id;
        $blog->title = ucwords($request->title);
        $blog->content = $request->content;
        $blog->save();

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

        $blog = Blog::find($request->id);
        $blog->title = ucwords($request->title);
        $blog->content = $request->content;
        $blog->save();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => 'Data successfully updated'
        ], 200);

    }

    public function delete($id)
    {

        $blog = Blog::find($id);
        $blog->delete();

        return response()->json([
            'code' => 200,
            'status' => 'ok',
            'message' => 'Data successfully deleted'
        ], 200);
        
    }
}
