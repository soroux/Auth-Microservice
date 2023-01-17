<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function showAllUsers($application_id,$service_id,$withRelations,$status, $first_name,$last_name, $email, $page, $limit = 15, $sortBy = 'created_at')
    {
        $query = User::query();

        if ($first_name) {
            $query = $query->where('first_name', 'like', '%' . $first_name . '%');
        }
        if ($last_name) {
            $query = $query->where('last_name', 'like', '%' . $last_name . '%');
        }
        if ($email) {
            $query = $query->where('email', $email);
        }
        if ($status) {
        $query = $query->where('status', $status);
        }
        if ($application_id) {
            $query = $query->where('application_id', $application_id);
        }
        if ($service_id) {
            $query = $query->where('service_id', $service_id);
        }

        switch ($sortBy) {
            case 'last_name':
                $query = $query->orderBy("last_name", "desc");
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

    public function storeUser(array $item)
    {
        return User::create($item);
    }

    public function getUser($id, $withRelations = null)
    {
        $query = User::query();
        $query->where('id', $id);
        if ($withRelations) {
            $query = $query->with($withRelations);
        }
        return $query->first();
    }

    public function getUsersWhereIn(array $ids, $withRelations = null)
    {
        $query = User::query();
        $query->whereIn('id', $ids);
        if ($withRelations) {
            $query = $query->with($withRelations);
        }
        return $query->get();
    }

    public function updateUser(array $item, $User)
    {


        return $User->update($item);

    }

    public function destroyUser($User)
    {
        return $User->delete($User);

    }
}
