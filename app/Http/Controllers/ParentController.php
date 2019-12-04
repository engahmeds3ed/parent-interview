<?php

namespace App\Http\Controllers;

use App\Services\ParentService;
use Illuminate\Http\Request;

/**
 * Class ParentController
 * @package App\Http\Controllers
 */
class ParentController extends Controller
{
    /**
     * list all parents from different sources
     * @param Request $request
     * @param ParentService $parentService
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ParentService $parentService)
    {
        $requestData = $request->all();
        $parents = $parentService->getAllParents($requestData);
        return response()->json($parents);
    }
}
