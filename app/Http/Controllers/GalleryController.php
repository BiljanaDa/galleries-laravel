<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateGalleryRequest;
use App\Http\Requests\EditGalleryRequest;
use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    

    public function index()
    {
        $query = Gallery::with('comments', 'user', 'images');
        $galleries = $query->orderBy('id', 'desc')->paginate(10);

        return response()->json($galleries);
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
     */
    public function store(CreateGalleryRequest $request)
    {
        $validated = $request->validated();

        $gallery = Gallery::create([
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description']
        ]);

        $images = $request->get('images', []);
        foreach ($images as $image) {
            Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image['url']
            ]);
        }
        $gallery->load('images', 'user', 'comments', 'comments.user');

        return response()->json($gallery, 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $gallery = Gallery::with(['images', 'user', 'comments', 'comments.user'])->find($id);

        return response()->json($gallery);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, EditGalleryRequest $request)
    {
        $validated = $request->validated();

        $gallery = Gallery::findOrFail($id);
        $gallery->update($validated);

        $images = $request->get('images', []);
        foreach ($images as $image) {
            $imagesArr[] = Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image['url']
            ]);
        }
        $gallery->load('images', 'user', 'comments', 'comments.user');
        return response()->json($gallery);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();
        return response()->noContent();
    }
}
