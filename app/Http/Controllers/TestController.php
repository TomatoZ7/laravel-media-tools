<?php

namespace App\Http\Controllers;

use App\Utils\FileUtil;
use Illuminate\Routing\Controller;

class TestController extends Controller
{
    public function test()
    {
//        dd(FileUtil::downloadByUrl('https://img-baofun.zhhainiao.com/fs/8d7339be4e51d15f15d5d601d41260de.jpg'));

        dd(FileUtil::createTmpDir());
    }
}
