<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Http\JsonResponse;

class StoreRepository
{
    public function create(array $data): Store
    {
        $store = new Store();
        $store->name = $data['name'];
        $store->coords = json_encode($data['coords']);
        $store->status = $data['status'];
        $store->type = $data['type'];
        $store->max_delivery_distance = $data['max_delivery_distance'];
        $store->save();

        return $store;
    }

    public function findStoresNear(mixed $latitude, mixed $longitude, mixed $radius)
    {
        return Store::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( JSON_EXTRACT(coords, '$.latitude') ) ) * cos( radians( JSON_EXTRACT(coords, '$.longitude') ) - radians(?) ) + sin( radians(?) ) * sin( radians( JSON_EXTRACT(coords, '$.latitude') ) ) ) ) AS distance", [$latitude, $longitude, $latitude])
            ->having("distance", "<", $radius)
            ->orderBy("distance")
            ->get();
    }

    public function findStoresDeliveringTo(mixed $latitude, mixed $longitude)
    {
        return Store::selectRaw("*, ( 6371 * acos( cos( radians(?) ) * cos( radians( JSON_EXTRACT(coords, '$.latitude') ) ) * cos( radians( JSON_EXTRACT(coords, '$.longitude') ) - radians(?) ) + sin( radians(?) ) * sin( radians( JSON_EXTRACT(coords, '$.latitude') ) ) ) ) AS distance", [$latitude, $longitude, $latitude])
            ->having("distance", "<=", "max_delivery_distance")
            ->orderBy("distance")
            ->get();
    }

}
