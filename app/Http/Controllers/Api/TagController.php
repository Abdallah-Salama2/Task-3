<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     * 4.a. Authenticated users can view all tags.
     */
    public function index()
    {
        //
        return Tag::all();
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
     * 4.b. Authenticated users can store new tags.
     *
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);
        Tag::create([
            'name'=>$request->name
        ]);
        return response()->json(["Tag Created Successfully"],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 4.c. Authenticated users can update single tag.
     */
    public function update(Request $request, Tag $tag)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);
        $tag->update($request->all());
        return response()->json(["Tag Updated Successfully"],201);
    }

    /**
     * Remove the specified resource from storage.
     * 4.d. Authenticated users can delete single tag.
     */
    public function destroy(Tag $tag)
    {
        //
        $tag->delete();
        return response()->json(["Tag Deleted Successfully"],201);

    }
}
