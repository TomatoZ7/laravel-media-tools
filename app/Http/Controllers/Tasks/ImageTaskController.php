<?php

namespace App\Http\Controllers\Tasks;

use App\Enums\ImageTaskEnum;
use App\Service\Tasks\ImageTaskService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ImageTaskController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function create(Request $request)
    {
        try {

            $params = $request->all();

            // Validator
            $validator = Validator::make($params, [
                'type' => ['required', Rule::in(ImageTaskEnum::TYPE_SCOPE)],
                'exec_params' => ['required', 'array']
            ]);

            if ($validator->stopOnFirstFailure()->fails()) {
                return response()->json([
                    'status' => 1,
                    'msg' => $validator->errors()->first()
                ], 400);
            }

            list($code, $msg) = ImageTaskService::createTask($params);
            if ($code) {
                throw new \Exception('error.', 500);
            }

            return response()->json([
                'msg' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'msg' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
