<?php
namespace App\Helper;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Trait  Helper to upload
 */
Trait UploadFilesHelper
{
    public static $disk = 'public_uploads';

    /**
     * Helper to upload
     * @param mixed $file File to upload
     * @param int $width
     * @param int $height
     * @param string $oldFile
     * @param string $folder
     * @return array|string|null
     */
    public static function upload($file, $folder = null, $oldFile = null, $width= 200, $height= 200)
    {
        if(!$file) {
            return null;
        }
        // check mime
//        $mime = $file->getMimeType();
//        if(!in_array($mime, config('lfm.valid_file_mimetypes'))){
//            return ['errors' => __('main.file_not_allow')];
//        }

        $path = pathinfo($file->getClientOriginalName());
        $ext = $path['extension']; //$file->extension();
        $name = rand().'_'.time().'.' . $ext;

        if ($folder){
            $isFolderExists = Storage::disk(self::$disk)->exists($folder);
            if (!$isFolderExists){
                Storage::disk(self::$disk)->makeDirectory($folder);
            }
        }

        $uploadedFile = $file->storeAs('/'.$folder, $name,['disk' => self::$disk]);

        // delete old file
        if ($oldFile){
            if (file_exists(public_path().'/'.$oldFile)){
                File::delete(public_path().'/'.$oldFile);
            }
            if(Storage::disk(self::$disk)->exists($oldFile)) {
                Storage::disk(self::$disk)->delete($oldFile);
            }
            if(is_file(storage_path(self::$disk."/".$oldFile))){
                File::delete(storage_path(self::$disk."/".$oldFile));
            }
        }

        return 'uploads/images/'.$uploadedFile;
    }

    /**
     * Delete image
     * @param string $file
     * @return bool|null
     */
    public static function delete(string $file): ?bool
    {

        if (file_exists(public_path().'/'.$file)){
            File::delete(public_path().'/'.$file);
        }

        if ($file && Storage::disk(self::$disk)->exists($file)) {
            return Storage::disk(self::$disk)->delete($file);
        }
        return false;
    }

    /**
     * Url of the image
     * @param $file
     * @return string|null
     */
    public static function url($file): ?string
    {
        if (file_exists(public_path().'/'.$file)){
            File::get(public_path().'/'.$file);
        }

        if ($file && Storage::disk(self::$disk)->exists($file)) {
            return Storage::disk(self::$disk)->url($file);
        }
        return null;
    }

    /**
     *  File Path to download it
     * @param string $file
     * @return string|null
     */
    public static function path(string $file) :?string
    {
        if ($file && Storage::disk(self::$disk)->exists($file)) {
            return Storage::disk(self::$disk)->path($file);
        }
        return null;
    }

    public static function getFullPath($file)
    {
        return request()->root().'/'.trim($file, '/');
    }
}
