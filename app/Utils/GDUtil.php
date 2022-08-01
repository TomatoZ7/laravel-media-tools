<?php

namespace App\Utils;

use Exception;

class GDUtil
{
    /**
     * Zoom image.
     *
     * @param string $src_file
     * @param string $dst_file
     * @param int $dst_w
     * @param int $dst_h
     * @param array $ext
     * @return array
     */
    public static function zoom(string $src_file, string $dst_file, int $dst_w, int $dst_h, array $ext = []): array
    {
        try {

            $src_info = getimagesize($src_file);
            if (!$src_info) {
                $image_fmt = $ext['type'] ?? '';
                if (!empty($image_fmt)) {
                    $src_info = [null, null, $image_fmt];
                } else {
                    throw new Exception('Parse width&height fail.');
                }
            }
            list($src_w, $src_h, $src_fmt, $src_txt) = $src_info;

            switch ($src_fmt) {
                case 1:
                    if (!function_exists('imagecreatefromgif')) {
                        throw new Exception('The GD can\'t support .gif.');
                    }
                    $src_image = imagecreatefromgif($src_file);
                    break;
                case 2:
                    if (!function_exists('imagecreatefromjpeg')) {
                        throw new Exception('The GD can\'t support .jpeg.');
                    }
                    $src_image = imagecreatefromjpeg($src_file);
                    break;
                case 3:
                    if (!function_exists('imagecreatefrompng')) {
                        throw new Exception('The GD can\'t support .png.');
                    }
                    $src_image = imagecreatefrompng($src_file);
                    break;
                default:
                    throw new Exception('Invalid image type.');
            }

            $src_w = $src_w ?: imagesx($src_image);
            $src_h = $src_h ?: imagesy($src_image);

            $dst_image = imagecreatetruecolor($dst_w, $dst_h);
            if ($dst_image) {
                // This way can maintain high definition
                imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
            } else {
                $dst_image = imagecreate($dst_w, $dst_h);
                imagecopyresized($dst_image, $src_image, 0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
            }

            // save
            switch ($src_fmt) {
                case 1:
                    imagegif($dst_image, $dst_file);
                    break;
                case 2:
                    imagejpeg($dst_image, $dst_file);
                    break;
                case 3:
                    imagepng($dst_image, $dst_file);
                    break;
            }
            imagedestroy($src_image);
            imagedestroy($dst_image);

            return [0, 'success'];

        } catch (Exception $e) {
            return [1, $e->getMessage()];
        }
    }
}
