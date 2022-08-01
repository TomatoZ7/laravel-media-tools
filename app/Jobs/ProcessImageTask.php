<?php

namespace App\Jobs;

use App\Enums\ImageTaskEnum;
use App\Models\Tasks\ImageTask;
use App\Service\Tasks\ImageProcessorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImageTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * image task 实例
     *
     * @var ImageTask
     */
    protected ImageTask $image_task;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ImageTask $image_task)
    {
        $this->onConnection('redis')
            ->onQueue('image_task');
        $this->image_task = $image_task;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageProcessorService $image_processor)
    {
        $this->image_task['status'] = ImageTaskEnum::STATUS_PROCESSING;
        $this->image_task->save();

        $task_type = $this->image_task['type'];
        $input = json_decode($this->image_task['input'], true);

        switch ($task_type) {
            case ImageTaskEnum::TYPE_CROP_IMAGE:
                list($code, $msg, $res) = $image_processor->handleProcessImage($input);
                break;
            default:
                $code = 1;
                $msg = 'Invalid task type.';
                $res = [];
        }

        $this->image_task->update([
            'status' => $code ? ImageTaskEnum::STATUS_FAIL : ImageTaskEnum::STATUS_SUCCESS,
            'result' => $code ? null : json_encode($res),
        ]);
    }
}
