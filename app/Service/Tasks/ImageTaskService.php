<?php

namespace App\Service\Tasks;

use App\Models\Tasks\ImageTask;
use Illuminate\Support\Facades\DB;

class ImageTaskService
{
    public static function createTask(array $input, int $type)
    {
        try {

            if (empty($input)) {
                throw new \Exception();
            }

            DB::beginTransaction();

            ImageTask::query()->insert([

            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
