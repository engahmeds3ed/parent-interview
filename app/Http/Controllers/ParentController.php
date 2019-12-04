<?php

namespace App\Http\Controllers;

use App\Services\ParentService;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function index(Request $request, ParentService $parentService)
    {
        $requestData = $request->all();
        $filters = [];

        if(array_key_exists('provider', $requestData)){
            $filters['provider'] = $requestData['provider'];
        }
        if(array_key_exists('statusCode', $requestData)){
            $filters['statusCode'] = $requestData['statusCode'];
        }
        if(array_key_exists('balanceMin', $requestData)){
            $filters['balanceMin'] = $requestData['balanceMin'];
        }
        if(array_key_exists('balanceMax', $requestData)){
            $filters['balanceMax'] = $requestData['balanceMax'];
        }
        if(array_key_exists('currency', $requestData)){
            $filters['currency'] = $requestData['currency'];
        }

        $parents = $parentService->getAllParents($filters);
        return response()->json($parents);
    }
}
