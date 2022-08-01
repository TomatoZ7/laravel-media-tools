<?php

namespace App\Utils;

use Illuminate\Support\Str;

class FileUtil
{
    /**
     * Get file's type.
     *
     * @param string $url
     * @return string|null
     */
    public static function getTypeByUrl(string $url): string|null
    {
        try {

            // pathinfo
            return pathinfo($url, PATHINFO_EXTENSION);

            // TODO: pathinfo 无法识别的情况

        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Create dir.
     *
     * @return string
     */
    public static function createTmpDir(): string
    {
        $dir = storage_path('app/temp/' . date('Ymd') . '/');

        if (empty($dir)) {
            return '';
        }

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    /**
     * Download network resource.
     *
     * @param string $url
     * @param string $file_path
     * @return array
     */
    public static function downloadByUrl(string $url, string $file_path): array
    {
        try {

            $file = fopen($file_path, 'wb');

            $dl_file = @fopen($url, 'rb');
            if ($dl_file == false) {
                throw new \Exception('无法打开文件句柄');
            }

            // 针对大文件，规定每次读取文件的字节数为 2048 字节
            $read_buffer = 2048;
            // 总的缓冲的字节数
            $sum_buffer = 0;
            // 只要没到文件尾，就一直读取并写入保存
            while (!feof($dl_file)) {
                fwrite($file, fread($dl_file, $read_buffer));
                $sum_buffer += $read_buffer;
            }
            // 关闭句柄
            fclose($dl_file);
            fclose($file);

            return [0, 'success', $file_path];

        } catch (\Exception $e) {
            return [1, $e->getMessage(), null];
        }
    }
}
