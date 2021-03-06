<?php namespace ImageProcess;

class Corner extends Filter
{
    protected $radius;
    protected $positions;
    protected $background;

    protected function processObject(ImageProcess $imageProcess, ImageObject $object1, ImageObject $object2 = null)
    {
        $radius = (int)$this->radius;
        $doubleRadius = $radius * 2;

        if ((string)$this->positions) {
            $positions = (string)$this->positions;
        } else {
            $positions = '1111';
        }

        if ($background = (string)$this->background) {
            $r = hexdec(substr($background, 0, 2));
            $g = hexdec(substr($background, 2, 2));
            $b = hexdec(substr($background, 4, 2));
            $transparentColor = imagecolorallocate($object1->getGDResource(), $r, $g, $b);
        } else {
            $transparentColor = imagecolorallocatealpha($object1->getGDResource(), 255, 255, 255, 127);
        }

        if ($radius > 15) {
            $q = 50;
        } else {
            $q = 0;
        }

        $tempImage = $imageProcess->getEmptyImageObject($object1->getWidth() * 2, $object1->getHeight() * 2);

        imagecopyresampled($tempImage->getGDResource(), $object1->getGDResource(), 0, 0, 0, 0, $tempImage->getWidth(), $tempImage->getHeight(), $object1->getWidth(), $object1->getHeight());

        imageAlphaBlending($tempImage->getGDResource(), false);
        $powRadius = pow($doubleRadius, 2);


        if (substr($positions, 0, 1) == '1') {
            //left top
            for ($x = 0; $x < $doubleRadius; $x++) {
                for ($y = 0; $y < $doubleRadius; $y++) {
                    $pif = pow($doubleRadius - $x, 2) + pow($doubleRadius - $y, 2);
                    if ($pif + $q >= $powRadius) {
                        imagesetpixel($tempImage->getGDResource(), $x, $y, $transparentColor);
                    }
                }
            }
        }

        if (substr($positions, 1, 1) == '1') {
            //right top
            for ($x = $tempImage->getWidth(); $tempImage->getWidth() - $x < $doubleRadius; $x--) {
                for ($y = 0; $y < $doubleRadius; $y++) {
                    $pif = pow($doubleRadius - ($tempImage->getWidth() - $x), 2) + pow($doubleRadius - $y, 2);
                    if ($pif + $q >= $powRadius) {
                        imagesetpixel($tempImage->getGDResource(), $x, $y, $transparentColor);
                    }
                }
            }
        }

        if (substr($positions, 2, 1) == '1') {
            //right bottom
            for ($x = $tempImage->getWidth(); $tempImage->getWidth() - $x < $doubleRadius; $x--) {
                for ($y = $tempImage->getHeight(); $tempImage->getHeight() - $y < $doubleRadius; $y--) {
                    $pif = pow($doubleRadius - ($tempImage->getWidth() - $x), 2) + pow($doubleRadius - ($tempImage->getHeight() - $y), 2);
                    if ($pif + $q >= $powRadius) {
                        imagesetpixel($tempImage->getGDResource(), $x, $y, $transparentColor);
                    }
                }
            }
        }

        if (substr($positions, 3, 1) == '1') {
            //left bottom
            for ($x = 0; $x < $doubleRadius; $x++) {
                for ($y = $tempImage->getHeight(); $tempImage->getHeight() - $y < $doubleRadius; $y--) {
                    $pif = pow($doubleRadius - $x, 2) + pow($doubleRadius - ($tempImage->getHeight() - $y), 2);
                    if ($pif + $q >= $powRadius) {
                        imagesetpixel($tempImage->getGDResource(), $x, $y, $transparentColor);
                    }
                }
            }
        }


        $resultImage = $imageProcess->getEmptyImageObject($object1->getWidth(), $object1->getHeight());

        imagecopyresampled($resultImage->getGDResource(), $tempImage->getGDResource(), 0, 0, 0, 0, $object1->getWidth(), $object1->getHeight(), $tempImage->getWidth(), $tempImage->getHeight());

        return $resultImage;
    }
}

