<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Services\Product\IndexProductService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * @param Request $request
     * @param IndexProductService $service
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, IndexProductService $service)
    {
        return ProductResource::collection($service->execute($request->all()));
    }
}
