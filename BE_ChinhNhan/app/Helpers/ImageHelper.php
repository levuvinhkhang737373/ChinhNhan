<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('uploadBase64Image')) {

    function uploadBase64Image($base64String, $folder = 'uploads/news', $disk = 'public')
    {
        if (!$base64String) {
            return null;
        }
        @list($type, $fileData) = explode(';', $base64String);
        @list(, $fileData) = explode(',', $fileData);
        if (!$fileData) {
            return null;
        }
        $fileData = base64_decode($fileData);
        @list(, $extension) = explode('/', $type);
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $path = $folder . '/' . $filename;
        Storage::disk($disk)->put($path, $fileData);
        return $path;
    }
}

if (!function_exists('deleteImage')) {

    function deleteImage($path,$disk='public')
    {
        if (!$path) {
            return false;
        }
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        return false;
    }
}
if (!function_exists('updateImage')) {

    function updateImage($file, $oldPath = null, $folder = 'uploads', $disk = 'public')
    {
        if (!$file) {
            return $oldPath;
        }
        deleteImage($oldPath, $disk);
        return uploadImage($file, $folder, $disk);
    }
}
if (!function_exists('uploadImage')) {

    function uploadImage($file, $folder = 'uploads', $disk = 'public')
    {
        if (!$file) {
            return null;
        }
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, $disk);
        return $path;
    }
}
