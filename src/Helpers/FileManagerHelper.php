<?php
namespace Aslnbxrz\FileManager\Helpers;

class FileManagerHelper
{
    public static function getThumbsImage()
    {
        if(!config('image.thumbs')){
            throw new \DomainException("'thumbs' params is not founded");
        }
        return config('image.thumbs');
    }

    public static function getImagesExt()
    {
        if(!config('image.images_ext')){
            throw new \DomainException("'images_ext' params is not founded");
        }
        return config('image.images_ext');
    }
}
