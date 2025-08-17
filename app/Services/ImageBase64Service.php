<?php


namespace App\Services;


class ImageBase64Service
{
    public static function imageUpload($image, $namePrefix, $destination)
    {
        list($type, $file) = explode(';', $image);
        list(, $extension) = explode('/', $type);
        list(, $file) = explode(',', $file);
        $fileNameToStore = $namePrefix . rand(1, 100000000) . '.' . $extension;
        $source = fopen($image, 'r');
        $destination = fopen($destination . $fileNameToStore, 'w');
        stream_copy_to_stream($source, $destination);
        fclose($source);
        fclose($destination);
        return $fileNameToStore;
    }

    public static function imageResizeUpload($image, $namePrefix, $destination)
    {
        list($type, $file) = explode(';', $image);
        list(, $extension) = explode('/', $type);
        list($dd , $file) = explode(',', $file);
        $image_resource = imagecreatefromstring(base64_decode($file));
        $resized_image = imagescale($image_resource, 500,300);
        $fileNameToStore = $namePrefix . rand(1, 100000000) . '.' . $extension;
        imagejpeg($resized_image, $destination.$fileNameToStore);
        return $fileNameToStore;
    }

    public static function uploadBase64Image($base64String,$uploadDirectory='uploads/',$prfix='') {
        if(empty($base64String)) {
            return null;
        }
        $imageParts = explode(",", $base64String);
        $image_base64 = (count($imageParts) >1) ?  base64_decode($imageParts[1]) : base64_decode($imageParts[0]);
        if(!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory,0777,true);
        }
        if($prfix !='') {
            $prfix = $prfix."_";
        }
        $imageName = $prfix. time().'_'.rand(1,20).'.jpg';

        $file = $uploadDirectory.'/'. $imageName;
        file_put_contents($file, $image_base64);
        return $imageName;
    }
}
