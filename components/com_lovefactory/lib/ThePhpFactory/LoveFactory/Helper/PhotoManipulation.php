<?php

/**
-------------------------------------------------------------------------
lovefactory - Love Factory 4.4.7
-------------------------------------------------------------------------
 * @author thePHPfactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thePHPfactory.com
 * Technical Support: Forum - http://www.thePHPfactory.com/forum/
-------------------------------------------------------------------------
*/

namespace ThePhpFactory\LoveFactory\Helper;

defined('_JEXEC') or die;

class PhotoManipulation
{
    public static function cacheAndPixelate($userId, $src)
    {
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.folder');
        $filename = basename($src);

        $folder = JPATH_SITE . '/cache/com_lovefactory/pixelated';
        $cache = $folder . '/' . $filename;

        if (!file_exists($cache)) {
            if (!\JFolder::exists($folder)) {
                \JFolder::create($folder);
            }

            $app = \LoveFactoryApplication::getInstance();
            $from = $app->getUserFolder($userId) . '/' . $filename;

            \JFile::copy($from, $cache);

            self::pixelate($from, $cache);
        }

        return $filename;
    }

    private static function pixelate($image, $output, $pixelate_x = 6, $pixelate_y = 6)
    {
        // get the input file extension and create a GD resource from it
        $ext = pathinfo($image, PATHINFO_EXTENSION);

        if ($ext == "jpg" || $ext == "jpeg")
            $img = imagecreatefromjpeg($image);
        elseif ($ext == "png")
            $img = imagecreatefrompng($image);
        elseif ($ext == "gif")
            $img = imagecreatefromgif($image);
        else
            echo 'Unsupported file extension';

        // now we have the image loaded up and ready for the effect to be applied
        // get the image size
        $size = getimagesize($image);
        $height = $size[1];
        $width = $size[0];

        // start from the top-left pixel and keep looping until we have the desired effect
        for ($y = 0; $y < $height; $y += $pixelate_y + 1) {

            for ($x = 0; $x < $width; $x += $pixelate_x + 1) {
                // get the color for current pixel
                $rgb = imagecolorsforindex($img, imagecolorat($img, $x, $y));

                // get the closest color from palette
                $color = imagecolorclosest($img, $rgb['red'], $rgb['green'], $rgb['blue']);
                imagefilledrectangle($img, $x, $y, $x + $pixelate_x, $y + $pixelate_y, $color);

            }
        }

        imagejpeg($img, $output);
        imagedestroy($img);
    }
}
