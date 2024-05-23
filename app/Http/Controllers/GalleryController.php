<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Models\Image;
use App\Http\Services\ImageService;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function __construct(protected ImageService $imageService)
    { }

    public function index(): View
    {
        return view('index', ['images' => Image::all()]);   
    }

    public function upload(FileUploadRequest $request): RedirectResponse
    {
        $request->validated();
        $image = $request->file('image');
        $titleImage = $request->title;
        
        try {
            $url = $this->imageService->storeImageInDisk($image);
            
            Image::query()->create([
                'title' => $titleImage,
                'url'   => $url
            ]);
        } catch (Exception $error) {
            $this->imageService->deleteImageFromDisk($url);
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
