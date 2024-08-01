<?php

namespace App\Http\Controllers;

use App\Repositories\StoreRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoreWebController extends Controller
{

    private StoreRepository $storeRepository;

    public function __construct(StoreRepository $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function create(): View
    {
        return view('stores.create');
    }

    public function store(Request $request): RedirectResponse
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'status' => 'required|string|in:open,closed',
            'type' => 'required|string|in:takeaway,restaurant,shop',
            'max_delivery_distance' => 'required|integer',
        ]);

        $storeData = [
            'name' => $validatedData['name'],
            'coords' => [
                'latitude' => $validatedData['latitude'],
                'longitude' => $validatedData['longitude'],
            ],
            'status' => $validatedData['status'],
            'type' => $validatedData['type'],
            'max_delivery_distance' => $validatedData['max_delivery_distance'],
        ];

        $this->storeRepository->create($storeData);

        return redirect()
            ->route('stores.create')
            ->with('success', 'Store created successfully');
    }

}
