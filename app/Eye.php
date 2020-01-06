<?php

namespace App;

class Eye
{

    const EYE_DIRECTORY = "media/eyes/";

    public $x;
    public $y;
    public $width;
    public $height;
    public $rotation;
    public $image;
    private $init_x;
    private $init_y;
    private $init_width;
    private $init_height;
    private $scale;
    private $multiplier;
    private $eye_type;
    private $dimensions = [
        'eyes-googly'       => 512,
        'eyes-robot'        => 512,
        'eyes-droopy'       => 565,
        'eyes-heart'        => 512,
        'eyes-emerald'      => 512,
        'eyes-bloodshot'    => 512,
        'eyes-evil'         => 585,
        'eyes-rockstar'     => 512,
        'eyes-lashed'       => 512,
        'eyes-rainbow'      => 512,
        'eyes-anime'        => 512,
        'eyes-crazy'        => 585,
        'eyes-browserstack' => 512
    ];

    public function __construct($eye, $coordinates, $eye_type, $rotation=null) {
        $this->eye = preg_match('/^os$/i', $eye) ? 'os' : 'od';
        $this->init_x = intval($coordinates->x);
        $this->init_y = intval($coordinates->y);
        $this->init_width = intval($coordinates->w);
        $this->init_height = intval($coordinates->h);

        if (array_key_exists($eye_type->label, $this->dimensions))
            $this->eye_type = $eye_type->label;
        else
            $this->eye_type = 'googly';

        $this->randomize_eye($rotation);
    }

    public function superimpose($image, $face) {
        imagecopyresampled($image, $this->image, $face->x + $this->x,
                           $face->y + $this->y, 0, 0, $this->width,
                           $this->height, $this->width, $this->height);

    }

    private function randomize_eye($rotation) {
        $this->rotation = self::rotate($rotation);
        $this->multiplier = $this->dimensions[$this->eye_type] / 512;
        $this->width = $this->scale_dimensions($this->init_width);
        $this->height = $this->scale_dimensions($this->init_height);
        $this->x = $this->offset('x');
        $this->y = $this->offset('y');
        $this->create_image();
    }

    private function create_image() {
        if ($this->eye_type == 'eyes-googly')
            $filename = "eyes-googly-{$this->rotation}.png";
        else
            $filename = "{$this->eye_type}-{$this->eye}.png";
        $filename = str_replace("app", self::EYE_DIRECTORY, app_path()) . $filename;
        $eye = imagecreatefrompng($filename);
        $this->image = imagecreatetruecolor($this->width, $this->height);
        imagealphablending($this->image, false);
        imagesavealpha($this->image, true);
        imagecopyresampled($this->image, $eye, 0, 0, 0, 0, $this->width,
                           $this->height, $this->dimensions[$this->eye_type],
                           $this->dimensions[$this->eye_type]);
    }

    private function scale_dimensions($length) {
        if (empty($this->scale))
            if ($this->eye_type == 'eyes-googly')
                $this->scale = rand(120, 150);
            else
                $this->scale = rand(145, 165);
        return $this->multiplier * $length * $this->scale / 100;
    }

    private function offset($axis) {
        if (preg_match('/^[xy]$/', $axis)) {
            // Centered eyes
            if ($axis == 'x') 
                $i = $this->init_x - ($this->width - $this->init_width) / 2;
            else
                $i = $this->init_y - ($this->height - $this->init_height) / 2;

            // Offset eyes
            if ($this->multiplier > 1) {
                $eye_width = $this->width / $this->multiplier;
                $offset = ($this->width - $eye_width) * -1;
                if ($axis == 'x' && $this->eye == 'os')
                    $i += abs($offset);
                else
                    $i += $offset;
            }

            return $this->$axis = round($i);  // Get it, round eye?
        }
        return false;
    }

    private static function rotate($rotation=null) {
        if ($rotation === null)
            $rotation = self::rotate_new();
        else
            $rotation = self::rotate_similar($rotation);
        return $rotation;
    }

    private static function rotate_new() {
        return rand(0, 23);
    }

    private static function rotate_similar($rotation) {
        $rotation = $rotation + rand(-4, 4);
        if ($rotation < 0)
            $rotation = $rotation + 24;
        return $rotation % 24;
    }

}
