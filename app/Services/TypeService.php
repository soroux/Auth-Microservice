<?php

namespace App\Services;

use App\Models\Type;

class TypeService
{
    public function showAllTypes($withRelations, $name, $status, $page, $limit = 15, $sortBy = 'created_at')
    {
        $query = Type::query();

        if ($name) {
            $query = $query->where('name', 'like', '%' . $name . '%');
        }
        if ($status) {
            $query = $query->where('status', $status);
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

    public function storeType(array $item)
    {
        return Type::create($item);
    }

    public function getType($id, $withRelations = null)
    {
        $query = Type::query();
        $query->where('id', $id);
        if ($withRelations) {
            $query = $query->with($withRelations);
        }
        return $query->first();
    }

    public function getTypesWhereIn(array $ids, $withRelations = null)
    {
        $query = Type::query();
        $query->whereIn('id', $ids);
        if ($withRelations) {
            $query = $query->with($withRelations);
        }
        return $query->get();
    }

    public function updateType(array $item, $Type)
    {


        return $Type->update($item);

    }

    public function destroyType($Type)
    {
        return $Type->delete($Type);

    }
}
