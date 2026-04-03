<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

trait HasMedia
{
    /**
     * Save new media files.
     */
    public function saveMedia($model, $mediaData)
    {
        $mediaData = is_array($mediaData) ? $mediaData : [$mediaData];

        foreach ($mediaData as $file) {
            if (!($file instanceof UploadedFile)) continue;

            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType() ?? 'application/octet-stream';
            $size = $file->getSize() ?? 0;

            // Unique storage filename
            $uniqueName = uniqid() . '_' . $originalName;
            $path = $file->storeAs('uploads', $uniqueName, 'private');

            $model->media()->create([
                'url' => $path,
                'original_filename' => $originalName,
                'mime_type' => $mimeType,
                'size' => $size,
                'uploaded_at' => now(),
            ]);
        }
    }

    /**
     * Update existing media (replace old file with new file)
     */
    public function updateMedia($model, UploadedFile $file)
    {
        if (!$file) return;

        $mediaRecord = $model->media()->first();

        $originalName = $file->getClientOriginalName();
            // dd($originalName);//"bootstrap.jpg" // app/Traits/HasMedia.php:48

        $mimeType = $file->getClientMimeType() ?? 'application/octet-stream';
        // dd($mimeType);//"image/jpeg" // app/Traits/HasMedia.php:51
        $size = $file->getSize() ?? 0;
        // dd($size);//16595 // app/Traits/HasMedia.php:53

        $uniqueName = uniqid() . '_' . $originalName;
        // dd($uniqueName);//"691ddd79e0422_laravel.jpg" // app/Traits/HasMedia.php:56
        $path = $file->storeAs('uploads', $uniqueName, 'private');
        // dd($path);//"uploads/691ddd984c2b4_laravel.jpg" // app/Traits/HasMedia.php:58

        if ($mediaRecord) {
            // dd('ok');//"ok" // app/Traits/HasMedia.php:61
            // Delete old file
            Storage::disk('private')->delete($mediaRecord->url);

            $mediaRecord->update([
                'url' => $path,
                'original_filename' => $originalName,
                'mime_type' => $mimeType,
                'size' => $size,
                'uploaded_at' => now(),
            ]);
        } else {
            $model->media()->create([
                'url' => $path,
                'original_filename' => $originalName,
                'mime_type' => $mimeType,
                'size' => $size,
                'uploaded_at' => now(),
            ]);
        }
    }

    /**
     * Delete all media related to a model
     */
    public function deleteMedia($model)
    {
        $model->media()->each(function ($media) {
            Storage::disk('private')->delete($media->url);
            $media->delete();
        });
    }
}
