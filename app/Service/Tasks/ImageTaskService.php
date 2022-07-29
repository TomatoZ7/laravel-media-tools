<?php

namespace App\Service\Tasks;

use App\Enums\ImageTaskEnum;
use App\Models\Tasks\ImageTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImageTaskService
{
    /**
     * Create Task.
     * @param array $input
     * @return array
     */
    public static function createTask(array $input): array
    {
        try {

            if (empty($input)) {
                throw new \Exception('参数错误');
            }

            DB::beginTransaction();

            ImageTask::query()->insert([
                'name' => $input['name'] ?? '未命名任务' . Str::random(6),
                'type' => (int)($input['type'] ?? 0),
                'input' => json_encode($input['exec_params'] ?? []),
                'status' => ImageTaskEnum::STATUS_WAIT,
                'notify' => $input['notify'] ?? ''
            ]);

            // TODO:redis list

            DB::commit();

            return [0, 'success'];

        } catch (\Exception $e) {
            DB::rollBack();
            return [1, $e->getMessage()];
        }
    }
}
