<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Comment::all(), 200);; 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request,$post)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        $validatedData = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->body = $validatedData['body'];
        $comment->post_id = $post; 
        $comment->user_id = Auth::id(); 
        try {

            \Log::info('Search Request', ['post'=>$post,'user_id' => Auth::id(), 'request' => $request->all()]);

            $comment->save();
            return response()->json($comment, 201); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not create comment'], 500); 
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $commentId)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $request->validate([
            'body' => 'sometimes|required|string',
        ]);

        try {
            $comment = Comment::findOrFail($commentId); 

            if ($comment->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            $comment->update($request->all());
            return response()->json($comment, 200); 
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Comment not found'], 404); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not update comment'], 500); 
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        
        if ($comment->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            $comment->delete();
            return response()->json(null, 204); 
        } catch (\Exception $e) {
            return response()->json(['error' => 'Could not delete comment'], 500); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    
    public function show($id)
    {
        try {
            $comment = Comment::findOrFail($id); 
            return response()->json($comment, 200); 
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Comment not found'], 404); 
        }
    }
}
