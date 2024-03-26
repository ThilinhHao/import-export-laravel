<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;


trait ImageTrait
{
    /**
     * Lưu ảnh vào storage và trả về đường dẫn.
     *
     * @param  \Illuminate\Http\UploadedFile  $image
     * @param  string  $folder
     * @param  string|null  $filename
     * @return string|null
     */
    public function uploadImage($image, $folder, $filename = null)
    {
        if (!$image instanceof \Illuminate\Http\UploadedFile) {
            return null;
        }

        $filename = $filename ?? uniqid();
        $extension = $image->getClientOriginalExtension();
        $filenameWithExtension = $filename . '.' . $extension;

        $imagePath = $image->storeAs($folder, $filenameWithExtension);

        return $filenameWithExtension;
    }

    /**
     * Xóa ảnh từ storage.
     *
     * @param  string|null  $imagePath
     * @return void
     */
    public function deleteImage($imagePath)
    {
        if ($imagePath) {
            Storage::delete($imagePath);
        }
    }
}
