<?php

namespace App\Service\Tasks;

use App\Enums\ImageTaskEnum;
use App\Jobs\ProcessImageTask;
use App\Models\Tasks\ImageTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
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

            $image_task = ImageTask::query()->create([
                'name' => $input['name'] ?? '未命名任务' . Str::random(6),
                'type' => (int)($input['type'] ?? 0),
                'input' => json_encode($input['exec_params'] ?? []),
                'status' => ImageTaskEnum::STATUS_WAIT,
                'notify' => $input['notify'] ?? ''
            ]);

            // 调度任务
            ProcessImageTask::dispatch($image_task)
                ->onConnection('redis')
                ->onQueue('image_task');

            DB::commit();

            return [0, 'success', $image_task['id']];

        } catch (\Exception $e) {
            DB::rollBack();
            return [1, $e->getMessage(), null];
        }
    }
}
