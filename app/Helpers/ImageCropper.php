<?php
/**
 * User: pau
 * Date: 12/16/22
 */
namespace App\Helpers;

class ImageCropper
{

    private $imageCreateFunc = [
        'png' => 'imagecreatefrompng',
        'gd' => 'imagecreatefromgd',
        'gif' => 'imagecreatefromgif',
        'jpg' => 'imagecreatefromjpeg',
        'jpeg' => 'imagecreatefromjpeg',
    ];

    private $imageWriteFunc = [
        'png' => 'imagepng',
        'gd' => 'imagegd',
        'gif' => 'imagegif',
        'jpg' => 'imagejpeg',
        'jpeg' => 'imagejpeg',
    ];

    /**
     * @TODO implement
     * @param string $fileName
     * @return string name of file
     */
    public static function cropTopHalf(string $fileName, $config): string
    {
        return $fileName;
        /*
         * do some cropping depeninding on the configuration (car bill id field) for the incoming image
        $mime = mime_content_type($fileName);
        # if mime is png
        $image = imagecreatefrompng($fileName); // or from jpg, etc
        $size = getimagesize($fileName);
        $cropped = imagecrop($image, ['x' => 0, 'y' => $size[1]/2, 'width' => $size[0], 'height' => $size[1]]);
        if ($cropped !== false) {
            $croppedFileName = "cropped_$fileName.png"; # but get baseName
            imagepng($cropped, $croppedFileName);
            imagedestroy($cropped);
        }
        imagedestroy($image);
        return $croppedFileName ?? $fileName;
        */
    }

}
