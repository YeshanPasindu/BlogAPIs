<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post; 
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
 
    public function index()
    {
        $userId = Auth::id();

        $posts = Post::with('user', 'comments.user')
            ->where('user_id', $userId) 
            ->paginate(10);

        return response()->json($posts);
    }


    public function store(Request $request)
    {
        try{

            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'required|string',
                'status' => 'required|string|in:published,draft',
            ]);

            $post = new Post();
            $post->title = $validatedData['title'];
            $post->body = $validatedData['body'];
            $post->status = $validatedData['status'];
            $post->user_id = Auth::id(); 

            $post->save();

            return response()->json($post, 201); 

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        }
    }

    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'body' => 'required|string',
                'status' => 'required|in:published,draft',
            ]);

            $post->update($validatedData);

            return response()->json($post, 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 400);
        }
    }

    public function destroy(Post $post)
    {
        if (Auth::user()->id !== $post->user_id && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $post->delete();
            return response()->json(null, 204); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not delete post'], 500); 
        }
    }

    public function show($postId)
    {
        try {
            $post = Post::with('user', 'comments.user')->findOrFail($postId);

            return response()->json($postId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Post not found'], 404); 
        }
    }

    public function getAll()
    {
        try {

            $posts = Post::with('user', 'comments.user')->where('status', 'published')->paginate(10);

            return response()->json($posts);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not retrieve posts'], 500); 
        }
    }

    public function searchAndFilter(Request $request)
    {

        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $searchQuery = $request->input('title');
        $statusFilter = $request->input('status');

        $query = Post::with('user', 'comments.user');
        
        if ($searchQuery) {
            $query->where('title', 'like', '%' . $searchQuery . '%');
        }

        if ($statusFilter && in_array($statusFilter, ['published', 'draft'])) {
            $query->where('status', $statusFilter);
        }

        $posts = $query->paginate(10);

        return response()->json($posts);
    }


}
