<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequests\AddTypeServiceRequest;
use App\Http\Requests\ServiceRequests\RemoveTypeServiceRequest;
use App\Http\Requests\ServiceRequests\StoreServiceRequest;
use App\Http\Requests\ServiceRequests\UpdateServiceRequest;
use App\Http\Resources\ServiceResources\ServiceCollection;
use App\Http\Resources\ServiceResources\ServiceResource;
use App\Models\Service;
use App\Services\ServiceService;
use Illuminate\Http\Request;

/**
 * @group Service management
 *
 * APIs for managing Services
 */
class ServiceController extends Controller
{

    /**
     * @var ServiceService
     */
    private $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;


    }

    /**
     * Display a listing of the resource.
     *
     * @bodyParam name string The name of the application. Example: test
     * @bodyParam user_id int The id of the user. Example: 1
     * @bodyParam withRelations array The array of the requested relations. Example: creator
     * @bodyParam page int page number. Example: 1
     * @bodyParam limit int per page results. Example: 15
     * @bodyParam sortBy string requested sort by. Example: created_at
     * @return ServiceCollection
     */
    public function index(Request $request)
    {
        //
        $services = $this->serviceService->showAllServices($request->application_id, $request->withRelations, $request->name, $request->user_id, $request->page, $request->limit, $request->sortBy);
        return new ServiceCollection($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ServiceRequests\StoreServiceRequest $request
     * @return ServiceResource
     */
    public function store(StoreServiceRequest $request)
    {
        //
        $input = $request->all();
        $service = $this->serviceService->storeService($input);
        return new ServiceResource($service);
    }

    /**
     * Display the specified resource.
     *
     * @bodyParam withRelations array The array of the requested relations. Example: creator
     *
     * @param \App\Models\Service $service
     * @return ServiceResource
     */
    public function show(Service $service, Request $request)
    {
        //
        $service = $this->serviceService->getService($service->id, $request->withRelations);
        return new ServiceResource($service);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ServiceRequests\UpdateServiceRequest $request
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        //
        $input = $request->all();

         $this->serviceService->updateService($input, $service);
        return  response()->json('success',200);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Service $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Service $service)
    {
        //
         $this->serviceService->destroyService($service);
        return  response()->json('success',200);


    }
    /**
     * add type to the specified appService.
     *
     * @param  \App\Http\Requests\ServiceRequests\AddTypeServiceRequest  $request
     * @return ServiceResource
     */
    public function addType(Service $service, AddTypeServiceRequest $request)
    {
        //
        $this->authorize('update',$service);

        $input = $request->all();
        $service = $this->serviceService->addTypes($input,$service);
        return new ServiceResource($service);

    }
    /**
     * remove type to the specified appService.
     *
     * @param  \App\Http\Requests\ServiceRequests\AddTypeServiceRequest  $request
     * @return ServiceResource
     */
    public function removeType(Service $service, AddTypeServiceRequest $request)
    {
        //
        $this->authorize('update',$service);

        $input = $request->all();
        $service = $this->serviceService->removeTypes($input,$service);
        return new ServiceResource($service);

    }

}
