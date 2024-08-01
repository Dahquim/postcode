<?php

namespace App\Repositories;

use App\Models\Store;

class StoreRepository
{
    /**
     * @param array $data
     * @return Store
     */
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

    /**
     * @param mixed $latitude
     * @param mixed $longitude
     * @param mixed $radius
     * @return mixed
     */
    public function findStoresNear(mixed $latitude, mixed $longitude, mixed $radius): mixed
    {
        return $this->getStoresWithDistance($latitude, $longitude)
            ->having("distance", "<", $radius)
            ->orderBy("distance")
            ->get();
    }

    /**
     * @param mixed $latitude
     * @param mixed $longitude
     * @return mixed
     */
    public function findStoresDeliveringTo(mixed $latitude, mixed $longitude): mixed
    {
        return $this->getStoresWithDistance($latitude, $longitude)
            ->having("distance", "<=", "max_delivery_distance")
            ->where('status', 'open')
            ->orderBy("distance")
            ->get();
    }

    /**
     * @param mixed $latitude
     * @param mixed $longitude
     * @return mixed
     */
    private function getStoresWithDistance(mixed $latitude, mixed $longitude): mixed
    {
        $distanceCalculation = "(6371 * acos(cos(radians(?)) * cos(radians(JSON_EXTRACT(coords, '$.latitude'))) * cos(radians(JSON_EXTRACT(coords, '$.longitude')) - radians(?)) + sin(radians(?)) * sin(radians(JSON_EXTRACT(coords, '$.latitude')))))";

        return Store::selectRaw("*, ROUND($distanceCalculation, 2) AS distance", [$latitude, $longitude, $latitude]);
    }

}
