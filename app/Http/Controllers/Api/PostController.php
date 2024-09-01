<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     * 5.a. Authenticated users can view only their posts.
     * i. Pinned Posts should appear first for every user.
     */
    public function index()
    {
        //
        return auth()->user()->posts()
            ->orderBy("pinned","DESC")
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * b. Authenticated users can store new posts.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'cover_image' => 'required|image',
            'pinned' => 'required|boolean',
            'tags' => 'required',
        ]);
        $tags=explode(",",$request->tags);
//        dd($tags);
        $post=auth()->user()->posts()->create($request->all());

        $post->tags()->sync($tags);

        return response()->json(["message"=>"Post Created Successfully",$post],201);
    }

    /**
     * Display the specified resource.
     * c. Authenticated users can view a single post of their posts.
     */
    public function show(Post $post)
    {

        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * d. Authenticated users can update single post of their posts.
     */
    public function update(Request $request, Post $post)
    {
        //
        $request->validate([
            'title' => 'sometimes|required|max:255',
            'body' => 'sometimes|required',
            'cover_image' => 'sometimes|image',
            'pinned' => 'sometimes|required|boolean',
            'tags' => 'sometimes',
        ]);

        $post->update($request->all());

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return response()->json(["message"=>"Post Updated Successfully",$post],201);
    }

    /**
     * Remove the specified resource from storage.
     * e. Authenticated users can delete (Softly) single post of their posts.
     */
    public function destroy(Post $post)
    {
        //
        $post->delete();

        return response()->json(["message"=>"Post Soft Deleted Successfully"],201);
    }

    //f. Authenticated users can view their deleted posts.
    public function softDeletedPosts()
    {
        return auth()->user()->posts()->onlyTrashed()->get();
    }

    //g. Authenticated users can restore one of their deleted posts.
    public function restore($id)
    {
        $post = auth()->user()->posts()->onlyTrashed()->findOrFail($id);

        $post->restore();

        return response()->json(['message' => 'Post restored successfully']);
    }


    public function pin($id)
    {
        $post = auth()->user()->posts()->findOrFail($id);

        $post->pinned=1;
        $post->save();

        return response()->json(['message' => 'Post Pinned successfully']);
    }
}
