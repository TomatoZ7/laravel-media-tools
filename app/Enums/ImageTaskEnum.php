<?php

namespace App\Enums;

class ImageTaskEnum
{
    const TYPE_SCOPE = [1];
    const TYPE_CROP_IMAGE = 1;

    const STATUS_WAIT = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_FAIL = 4;
}
