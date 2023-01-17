<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class ServiceService
{
    public function showAllServices($application_id, $withRelations, $name, $user_id, $page, $limit = 15, $sortBy = 'created_at')
    {
        $query = Service::query();
        if ($application_id) {
            $query = $query->where('application_id', $application_id);
        }
        if ($name) {
            $query = $query->where('name', 'like', '%' . $name . '%');
        }
        if ($user_id) {
            $query = $query->where('user_id', $user_id);
        }

        switch ($sortBy) {
            case 'created_at':
                $query = $query->orderBy("created_at", "desc");
                break;
            case 'name':
                $query = $query->orderBy("name", "desc");
                break;
            default:
                $query = $query->orderBy("created_at", "desc");
                break;
        }
        if ($withRelations) {
            $query = $query->with($withRelations);
        }
        if ($page) {
            return $query->paginate($limit);
        }

        return $query->get();
    }

    public function storeService(array $item)
    {
        return Service::create($item);
    }

    public function getService($id, $withRelations = null)
    {
        $query = Service::query();
        $query->where('id', $id);
        if ($withRelations) {
            $query = $query->with($withRelations);
        }
        return $query->first();
    }

    public function getServicesWhereIn(array $ids, $withRelations = null)
    {
        $query = Service::query();
        $query->whereIn('id', $ids);
        if ($withRelations) {
            $query = $query->with($withRelations);
        }
        return $query->get();
    }

    public function updateService(array $item, $Service)
    {


        return $Service->update($item);

    }

    public function destroyService($Service)
    {
        return $Service->delete($Service);

    }
    public function addTypes(array $item,Service $service){
        $service->types()->attach($item);
        return $service->load('types');
    }
    public function removeTypes(array $item,Service $service){
        $service->types()->detach($item);
        return $service->load('types');
    }
}
