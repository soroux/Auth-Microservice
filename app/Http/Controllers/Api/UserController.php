<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequests\StoreUserRequest;
use App\Http\Requests\UserRequests\UpdateUserRequest;
use App\Http\Resources\UserResources\UserCollection;
use App\Http\Resources\UserResources\UserResource;
use App\Models\User;
use App\Services\TypeService;
use App\Services\UserService;
use Illuminate\Http\Request;
/**
 * @group user management
 *
 * APIs for managing users
 */
class UserController extends Controller
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;


    }
    /**
     * Display a listing of the resource.
     *
     * @bodyParam first_name string The first_name of the user. Example: test
     * @bodyParam last_name string The last_name of the user. Example: test
     * @bodyParam withRelations array The array of the requested relations. Example: creator
     * @bodyParam page int page number. Example: 1
     * @bodyParam limit int per page results. Example: 15
     * @bodyParam sortBy string requested sort by. Example: created_at
     *
     * @return UserCollection
     */
    public function index(Request $request)
    {
        //
        $users =  $this->userService->showAllUsers($request->application_id,$request->service_id,$request->withRelations,$request->status,$request->first_name,$request->last_name,$request->email,$request->page,$request->limit,$request->sortBy);
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserRequests\StoreUserRequest  $request
     * @return UserResource
     */
    public function store(StoreUserRequest $request)
    {
        //
        $input = $request->all();
        $user = $this->userService->storeUser($input);
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @bodyParam withRelations array The array of the requested relations. Example: creator
     *
     * @param  \App\Models\User  $user
     * @return UserResource
     */
    public function show(User $user,Request $request)
    {
        //
        $user = $this->userService->getUser($user->id,$request->withRelations);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserRequests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
        $input = $request->all();
         $this->userService->updateUser($input,$user);
        return  response()->json('success',200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        //
         $this->userService->destroyUser($user);
        return  response()->json('success',200);

    }
}
