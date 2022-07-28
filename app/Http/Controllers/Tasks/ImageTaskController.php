<?php

namespace App\Http\Controllers\Tasks;

use App\Enums\ImageTaskEnum;
use App\Service\Tasks\ImageTaskService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class ImageTaskController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function create(Request $request)
    {
        try {

            // validator
            $validator = Validator::make($request->all(), [
                'type' => ['required'],
                'exec_params' => ['required']
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    'status' => 1,
                    'msg' => $validator->errors()->first()
                ], 400);
            }

            $params = $request->all();
            ImageTaskService::createTask($params, (int)$type);

        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
