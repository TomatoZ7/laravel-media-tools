<?php

namespace App\Service\Tasks;

class ImageTaskService
{

    public static function createTask(array $input)
    {
        try {

            if (empty($input)) {
                throw new \Exception();
            }

        } catch (\Exception $e) {

        }
    }
}
