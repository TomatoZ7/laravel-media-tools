<?php

namespace App\Service\Tasks;

use App\Utils\FileUtil;
use App\Utils\GDUtil;
use Exception;
use Illuminate\Support\Str;

class ImageProcessorService
{
    /**
     * Process image task.
     *
     * @param array $input
     * @return array
     */
    public function handleProcessImage(array $input): array
    {
        try {

            sleep(10);

            $url = $input['url'] ?? '';
            if (empty($url)) {
                throw new Exception('Invalid url.');
            }

            $file_type = FileUtil::getTypeByUrl($url);
            if (!$file_type) {
                throw new Exception('parse file type failed.');
            }

            // var
            $dir = FileUtil::createTmpDir();
            $output_file_path = $dir . Str::random() . '.' . $file_type;

            // download
            $file_path = $dir . Str::random() . '.' . $file_type;
            list($code, $msg, $file_path) = FileUtil::downloadByUrl($url, $file_path);
            if ($code || is_null($file_path)) {
                throw new Exception($msg);
            }

            // zoom
            $is_zoom = $input['is_zoom'] ?? false;
            if ($is_zoom) {
                $zoom_width = $input['zoom_width'] ?? 0;
                $zoom_height = $input['zoom_height'] ?? 0;
                list($code, $msg) = GDUtil::zoom($file_path, $output_file_path, $zoom_width, $zoom_height, ['type' => $file_type]);
                if ($code) {
                    throw new Exception($msg);
                }
            }

            // TODO UPLOAD

            return [0, 'success', compact('output_file_path')];

        } catch (Exception $e) {
            return [1, $e->getMessage(), []];
        }
    }
}
