<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('index', ['images' => Image::all()]);   
    }

    public function upload(Request $request): RedirectResponse
    {
        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $titleImage = $request->title;
    
                $name = $image->hashName();
    
                $url = $image->store('uploads', 'public', $name);
                
                Image::query()->create([
                    'title' => $titleImage,
                    'url'   => $url
                ]);
            } catch (Exception $error) {
                Storage::disk('public')->delete($url);
            }

        }
        return back();
    }

    public function delete(int $idImage): RedirectResponse
    {
        $image = Image::query()->findOrFail($idImage);
        $imageUrl = $image->url;
        $storage = Storage::disk('public');
        
        if ($storage->exists($imageUrl)) {
            $storage->delete($imageUrl);
            $image->delete();

            return back();
        }
    }
}
