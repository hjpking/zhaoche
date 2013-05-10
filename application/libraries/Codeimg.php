<?php

/**
 * 3D Captcha
 * (C) 2006 by Marc S. Ressl
 */

// Get captcha text
class Codeimg
{
    private $captchaText = 123456;

    function __construct($params = NULL)
    {
        if (isset($params['code'])) {
            $this->captchaText = $params['code'];
        }
    }

// Functions
    function addVector($a, $b)
    {
        return array($a[0] + $b[0], $a[1] + $b[1], $a[2] + $b[2]);
    }

    function scalarProduct($vector, $scalar)
    {
        return array($vector[0] * $scalar, $vector[1] * $scalar, $vector[2] * $scalar);
    }

    function dotProduct($a, $b)
    {
        return ($a[0] * $b[0] + $a[1] * $b[1] + $a[2] * $b[2]);
    }

    function norm($vector)
    {
        return sqrt($this->dotProduct($vector, $vector));
    }

    function normalize($vector)
    {
        return $this->scalarProduct($vector, 1 / $this->norm($vector));
    }

// http://en.wikipedia.org/wiki/Cross_product
    function crossProduct($a, $b)
    {
        return array(
            ($a[1] * $b[2] - $a[2] * $b[1]),
            ($a[2] * $b[0] - $a[0] * $b[2]),
            ($a[0] * $b[1] - $a[1] * $b[0])
        );
    }

    function vectorProductIndexed($v, $m, $i)
    {
        return array(
            $v[$i + 0] * $m[0] + $v[$i + 1] * $m[4] + $v[$i + 2] * $m[8] + $v[$i + 3] * $m[12],
            $v[$i + 0] * $m[1] + $v[$i + 1] * $m[5] + $v[$i + 2] * $m[9] + $v[$i + 3] * $m[13],
            $v[$i + 0] * $m[2] + $v[$i + 1] * $m[6] + $v[$i + 2] * $m[10] + $v[$i + 3] * $m[14],
            $v[$i + 0] * $m[3] + $v[$i + 1] * $m[7] + $v[$i + 2] * $m[11] + $v[$i + 3] * $m[15]
        );
    }

    function vectorProduct($v, $m)
    {
        return $this->vectorProductIndexed($v, $m, 0);
    }

    function matrixProduct($a, $b)
    {
        $o1 = $this->vectorProductIndexed($a, $b, 0);
        $o2 = $this->vectorProductIndexed($a, $b, 4);
        $o3 = $this->vectorProductIndexed($a, $b, 8);
        $o4 = $this->vectorProductIndexed($a, $b, 12);

        return array(
            $o1[0], $o1[1], $o1[2], $o1[3],
            $o2[0], $o2[1], $o2[2], $o2[3],
            $o3[0], $o3[1], $o3[2], $o3[3],
            $o4[0], $o4[1], $o4[2], $o4[3]
        );
    }

// http://graphics.idav.ucdavis.edu/education/GraphicsNotes/Camera-Transform/Camera-Transform.html
    function cameraTransform($C, $A)
    {
        $w = $this->normalize($this->addVector($C, $this->scalarProduct($A, -1)));
        $y = array(0, 1, 0);
        $u = $this->normalize($this->crossProduct($y, $w));
        $v = $this->crossProduct($w, $u);
        $t = $this->scalarProduct($C, -1);

        return array(
            $u[0], $v[0], $w[0], 0,
            $u[1], $v[1], $w[1], 0,
            $u[2], $v[2], $w[2], 0,
            $this->dotProduct($u, $t), $this->dotProduct($v, $t), $this->dotProduct($w, $t), 1
        );
    }

// http://graphics.idav.ucdavis.edu/education/GraphicsNotes/Viewing-Transformation/Viewing-Transformation.html
    function viewingTransform($fov, $n, $f)
    {
        $fov *= (M_PI / 180);
        $cot = 1 / tan($fov / 2);

        return array(
            $cot, 0, 0, 0,
            0, $cot, 0, 0,
            0, 0, ($f + $n) / ($f - $n), -1,
            0, 0, 2 * $f * $n / ($f - $n), 0
        );
    }

    public function display()
    {
        // 3dcha parameters
        $fontsize = 24;
        $fontfile = dirname(__FILE__) . '/3DCaptcha.ttf';

        $details = imagettfbbox($fontsize, 0, $fontfile, $this->captchaText);
        $image2d_x = $details[4] + 4;
        $image2d_y = $fontsize * 1.3;

        $bevel = 4;

        // Create 2d image
        $image2d = imagecreatetruecolor($image2d_x, $image2d_y);
        $black = imagecolorallocate($image2d, 0, 0, 0);
        $white = imagecolorallocate($image2d, 255, 255, 255);

        // Paint 2d image
        imagefill($image2d, 0, 0, $black);
        imagettftext($image2d, $fontsize, 0, 2, $fontsize, $white, $fontfile, $this->captchaText);

        // Calculate projection matrix
        $T = $this->cameraTransform(
            array(rand(-15, 15), -200, 200),
            array(0, 0, 0)
        );
        $T = $this->matrixProduct(
            $T,
            $this->viewingTransform(60, 300, 3000)
        );

        // Calculate coordinates
        $coord = array($image2d_x * $image2d_y);
        $count = 0;
        for ($y = 0; $y < $image2d_y; $y += 2) {
            for ($x = 0; $x < $image2d_x; $x++) {
                // calculate x1, y1, x2, y2
                $xc = $x - $image2d_x / 2;
                $zc = $y - $image2d_y / 2;
                $yc = -(imagecolorat($image2d, $x, $y) & 0xff) / 256 * $bevel;
                $xyz = array($xc, $yc, $zc, 1);
                $xyz = $this->vectorProduct($xyz, $T);

                $coord[$count] = $xyz;
                $count++;
            }
        }

        // Create 3d image
        $image3d_x = 190;
        //$image3d_y = $image3d_x / 1.618;
        $image3d_y = $image3d_x * 10 / 32;
        $image3d = imagecreatetruecolor($image3d_x, $image3d_y);
        $fgcolor = imagecolorallocate($image3d, 255, 255, 255);
        $bgcolor = imagecolorallocate($image3d, 0, 0, 0);
        imageantialias($image3d, true);
        imagefill($image3d, 0, 0, $bgcolor);

        $count = 0;
        $scale = 1.6 - $image2d_x / 400;
        for ($y = 0; $y < $image2d_y; $y++) {
            for ($x = 0; $x < $image2d_x; $x++) {
                if ($x > 0) {
                    $x0 = isset($coord[$count - 1][0]) ? $coord[$count - 1][0] * $scale + $image3d_x / 2 : 0;
                    $y0 = isset($coord[$count - 1][1]) ? $coord[$count - 1][1] * $scale + $image3d_y / 2 : 0;
                    $x1 = isset($coord[$count][0]) ? $coord[$count][0] * $scale + $image3d_x / 2 : 0;
                    $y1 = isset($coord[$count][1]) ? $coord[$count][1] * $scale + $image3d_y / 2 : 0;
                    imageline($image3d, $x0, $y0, $x1, $y1, $fgcolor);
                }
                $count++;
            }
        }

        header("Content-type: image/jpeg");
        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Mon, 03 Apr 1977 11:05:00 GMT"); // Date in the very past, guess what it is
        imagejpeg($image3d);
    }
}

//$img = new codeImg(666);
//$img->display();
?>