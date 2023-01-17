<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TypeRequests\StoreTypeRequest;
use App\Http\Requests\TypeRequests\UpdateTypeRequest;
use App\Http\Resources\TypeResources\TypeCollection;
use App\Http\Resources\TypeResources\TypeResource;
use App\Models\Type;
use App\Services\TypeService;
use Illuminate\Http\Request;

/**
 * @group Type management
 *
 * APIs for managing types
 */
class TypeController extends Controller
{
    /**
     * @var TypeService
     */
    private $typeService;

    public function __construct(TypeService $typeService){
        $this->typeService = $typeService;


    }
    /**
     * Display a listing of the resource.
     *
     * @bodyParam name string The name of the application. Example: test
     * @bodyParam withRelations array The array of the requested relations. Example: creator
     * @bodyParam page int page number. Example: 1
     * @bodyParam limit int per page results. Example: 15
     * @bodyParam sortBy string requested sort by. Example: created_at
     *
     * @return TypeCollection
     */
    public function index(Request $request)
    {
        //
        $types =  $this->typeService->showAllTypes($request->withRelations,$request->name,$request->page,$request->limit,$request->sortBy);
        return new TypeCollection($types);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TypeRequests\StoreTypeRequest  $request
     * @return TypeResource
     */
    public function store(StoreTypeRequest $request)
    {
        //
        $input = $request->all();
        $application = $this->typeService->destroyType($input);
        return new TypeResource($application);
    }

    /**
     * Display the specified resource.
     *
     * @bodyParam withRelations array The array of the requested relations. Example: creator
     *
     * @param  \App\Models\Type  $type
     * @return TypeResource
     */
    public function show(Type $type,Request $request)
    {
        //

        $type = $this->typeService->getType($type->id,$request->withRelations);
        return new TypeResource($type);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TypeRequests\UpdateTypeRequest  $request
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTypeRequest $request, Type $type)
    {
        //
        $input = $request->all();
         $this->typeService->updateType($input,$type);
        return  response()->json('success',200);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Type $type)
    {
        //
         $this->typeService->destroyType($type);
        return  response()->json('success',200);


    }
}
