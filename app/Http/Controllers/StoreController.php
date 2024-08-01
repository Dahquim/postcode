<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Repositories\StoreRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StoreController
{
    private StoreRepository $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'coords' => 'required|array',
            'coords.latitude' => 'required|numeric',
            'coords.longitude' => 'required|numeric',
            'status' => 'required|string|in:open,closed',
            'type' => 'required|string|in:takeaway,restaurant,shop',
            'max_delivery_distance' => 'required|integer|min:1',
        ]);

        $store = $this->storeRepository
            ->create($validatedData);

        return response()->json(['message' => 'Store created successfully', 'store' => $store], 201);
    }

    public function getStoresNearPostcode(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1',
        ]);

        $stores = $this->storeRepository
            ->findStoresNear(
                $validatedData['latitude'],
                $validatedData['longitude'],
                $validatedData['radius']
            );

        return response()->json(['stores' => $stores], 200);
    }

    public function getStoresDeliveringToPostcode(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $stores = $this->storeRepository
            ->findStoresDeliveringTo(
                $validatedData['latitude'],
                $validatedData['longitude']
            );

        return response()->json(['stores' => $stores], 200);
    }

}
