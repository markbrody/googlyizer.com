<?php

namespace App;

use App\Image;
use Illuminate\Http\UploadedFile;
use Storage;

class Googlyizer extends Image
{
    const IMAGE_DIRECTORY = "uploads/";
    const IMAGE_WIDTH = 768;

    private $filename;
    public $uploaded_file;
    public $result;
    private $eye_type;
    private $detection;
    private $image;
    private $debug = false;

    public function __construct(UploadedFile $uploaded_file, EyeType $eye_type) {
        parent::__construct($uploaded_file);
        if (!Storage::exists(self::IMAGE_DIRECTORY))
            Storage::makeDirectory(self::IMAGE_DIRECTORY);
        $this->uniqid = uniqid();
        $this->filename = Storage::path(self::IMAGE_DIRECTORY) . $this->uniqid;
        $this->eye_type = $eye_type;
    }

    public function save() {
        $copy = null;
        if ($this->mime_type == "image/png")
            $copy = imagecreatefrompng($this->uploaded_file->getPathname());
        elseif ($this->mime_type == "image/jpeg")
            $copy = imagecreatefromjpeg($this->uploaded_file->getPathname());
        if ($copy) {
            $this->image = imagecreatetruecolor($this->original_width, $this->original_height);
            imagecopyresampled($this->image, $copy, 0, 0, 0, 0, 
                               $this->original_width, $this->original_height,
                               $this->original_width, $this->original_height);
            foreach ($this->detection->faces as $face)
                $this->superimpose_eyes($face);
            if ($this->debug)
                $this->outline($face);

            if ($this->original_width > self::IMAGE_WIDTH)
                $this->image = imagescale($this->image, self::IMAGE_WIDTH);
            imagejpeg($this->image, $this->filename); 
            imagedestroy($this->image);
            return $this->uniqid;
        }
    }

    private function superimpose_eyes($coordinates, $rotation=null) {
        $od = new Eye('od', $coordinates->od, $this->eye_type, $rotation);
        $os = new Eye('os', $coordinates->os, $this->eye_type, $od->rotation);
        // Bigger eye overlaps the smaller eye
        if ($od->width > $os->width) {
            $os->superimpose($this->image, $coordinates->face);
            $od->superimpose($this->image, $coordinates->face);
        }
        else {
            $od->superimpose($this->image, $coordinates->face);
            $os->superimpose($this->image, $coordinates->face);
        }
    }

    private function outline($coordinates) {
        // Extract coordinates
        $od = $coordinates->od;
        $os = $coordinates->os;
        $face = $coordinates->face;
        // Define colors
        $blue = imagecolorallocate($this->image, 0, 0, 255);
        $green = imagecolorallocate($this->image, 0, 255, 0);
        // Outline face
        imagerectangle($this->image, $face->x, $face->y, $face->x + $face->w,
                       $face->y + $face->h, $blue);
        // Outline eyes
        imagerectangle($this->image, $face->x + $od->x, $face->y + $od->y,
                       $face->x + $od->x + $od->w, $face->y + $od->y + $od->h,
                       $green);
        imagerectangle($this->image, $face->x + $os->x, $face->y + $os->y,
                       $face->x + $os->x + $os->w, $face->y + $os->y + $os->h,
                       $green);
    }

    public function detect() {
        chdir(str_replace("app", "bin", app_path()));
        $cmd = "python detect.py " . $this->uploaded_file->getPathname();
        $output = exec("$cmd alt");
        $result = @json_decode(str_replace("'", '"', $output));
        if (property_exists($result, 'faces') && count($result->faces) == 0) {
            $output = shell_exec("$cmd default");
            $result = @json_decode(str_replace("'", '"', $output));
        }
        if ($result) {
            $this->detection = $result;
            return true;
        }
        return false;
    }
}
