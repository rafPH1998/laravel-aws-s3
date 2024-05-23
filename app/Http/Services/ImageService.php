<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Storage;

class ImageService { 

    public function storeImageInDisk($image): string
    {
       return $image->store('uploads', 'public');
    }

    public function deleteImageFromDisk(string $imagePath): void
    {
        Storage::disk('public')->delete($imagePath);
    }
}