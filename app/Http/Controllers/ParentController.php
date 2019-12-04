<?php

namespace App\Http\Controllers;

use App\Services\ParentService;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index(Request $request, ParentService $parentService)
    {
        $requestData = $request->all();
        $parents = $parentService->getAllParents($requestData);
        return response()->json($parents);
    }
}
