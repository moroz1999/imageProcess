<?php namespace ImageProcess;

class FillBackground extends Filter
{
    protected function processObject(ImageProcess $imageProcess, ImageObject $object1, ImageObject $object2 = null)
    {
        $width = $object1->getWidth();
        $height = $object1->getHeight();

        $newObject = $imageProcess->getEmptyImageObject($width, $height);
        if (isset($this->color) && $this->color != '') {
            $colors = $this->hex2rgb($this->color);
            $color = imagecolorallocate($newObject->getGDResource(), $colors[0], $colors[1], $colors[2]);
        } else {
            $color = imagecolorallocate($newObject->getGDResource(), 255, 255, 255);
        }
        imagefilledrectangle($newObject->getGDResource(), 0, 0, $width, $height, $color);

        imagealphablending($newObject->getGDResource(), true);
        imagecopy(
            $newObject->getGDResource(),
            $object1->getGDResource(),
            0,
            0,
            0,
            0,
            $width,
            $height
        );
        return $newObject;
    }

    protected function hex2rgb($hex)
    {
        $hex = str_replace("#", "", $hex);

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $rgb = [$r, $g, $b];
        return $rgb;
    }
}

