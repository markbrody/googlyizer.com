<?php

namespace App;

use Illuminate\Http\UploadedFile;

class Image
{
    const MAX_SIZE = 20971520; // 20MB

    protected $uploaded_file;

    protected $data;

    protected $resource;

    protected $original_width;

    protected $original_height;

    public $mime_type;

    protected $ratio;

    protected $exif_orientation;

    private static $exif_orientations = [
        3 => 180,
        6 => -90,
        8 => 90,
    ];

    public function __construct(UploadedFile $uploaded_file) {
        $this->uploaded_file = $uploaded_file;
        if ($this->_validate()) {
            $this->_dimensions();
            $this->data = file_get_contents($uploaded_file);
            $this->resource = imagecreatefromstring($this->data);
            if ($this->exif_orientation)
                $this->resource = imagerotate(
                    $this->resource,
                    self::$exif_orientations[$this->exif_orientation] ?? 0,
                    0
                );
        }
    }

    protected function _save($filename, $new_width, $new_height) {
        $image = imagecreatetruecolor($new_width, $new_height);
        if ($this->ratio > $new_width / $new_height)
            $this->_crop_x($image, $new_width, $new_height);
        else
            $this->_crop_y($image, $new_width, $new_height);
        switch($this->mime_type) {
            case "image/png": $output = "imagepng"; break;
            default: $output = "imagejpeg";
        }
        $output($image, $filename);
        imagedestroy($image);
    }

    private function _crop_x($image) {
        $width = imagesx($image);
        $height = imagesy($image);
        $resized_width = $this->original_width * ($height / $this->original_height);
        return imagecopyresized(
            $image,
            imagescale($this->resource, $resized_width),
            0,
            0,
            ($resized_width - $width) / 2,
            0,
            $width,
            $height,
            $width,
            $height
        );
    }

    private function _crop_y($image) {
        $width = imagesx($image);
        $height = imagesy($image);
        $resized_height = $this->original_height * ($width / $this->original_width);
        return imagecopyresized(
            $image,
            imagescale($this->resource, $width),
            0,
            0,
            0,
            ($resized_height - $height) / 2,
            $width,
            $height,
            $width,
            $height
        );
    }

    private function _validate() {
        if (preg_match("/^image\/(jpeg|png)$/", $this->uploaded_file->getMimeType()))
            return $this->mime_type = $this->uploaded_file->getMimeType();
        return false;
    }

    private function _dimensions() {
        $image_size = getimagesize($this->uploaded_file);
        $exif_data = @exif_read_data($this->uploaded_file);
        $this->original_width = $image_size[0];
        $this->original_height = $image_size[1];
        $this->exif_orientation = $exif_data['Orientation'] ?? 0;
        $this->_ratio();
    }

    private function _ratio() {
        if ($this->exif_orientation == 6 || $this->exif_orientation == 8) {
            $original_width = $this->original_width;
            $this->original_width = $this->original_height;
            $this->original_height = $original_width;
        }
        $this->ratio = $this->original_width / $this->original_height;
    }

}
