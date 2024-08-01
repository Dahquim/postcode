<?php

use App\Http\Controllers\StoreController;
use App\Models\Store;
use App\Repositories\StoreRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tests\TestCase;

class StoreTest extends TestCase
{
    public function test_creating_a_store(): void
    {
        $faker = Faker\Factory::create();

        $storeData = [
            'name' => $faker->company,
            'coords' => [
                'latitude' => $faker->latitude(50.1, 60.1),
                'longitude' => $faker->longitude(-7.6, 1.8)
            ],
            'status' => $faker->randomElement(['open', 'closed']),
            'type' => $faker->randomElement(['takeaway', 'restaurant', 'shop']),
            'max_delivery_distance' => $faker->numberBetween(1, 20),
            'created_at' => now(),
            'updated_at' => now(),
        ];


        $mockStoreRepository = Mockery::mock(StoreRepository::class);
        $mockStoreRepository->shouldReceive('create')
            ->once()
            ->with(Mockery::on(function ($arg) use ($storeData) {
                return $arg['name'] === $storeData['name'] &&
                    $arg['coords']['latitude'] === $storeData['coords']['latitude'] &&
                    $arg['coords']['longitude'] === $storeData['coords']['longitude'] &&
                    $arg['status'] === $storeData['status'] &&
                    $arg['type'] === $storeData['type'] &&
                    $arg['max_delivery_distance'] === $storeData['max_delivery_distance'];
            }))
            ->andReturn(new Store($storeData));

        $controller = new StoreController($mockStoreRepository);

        $request = Request::create('/api/stores', 'POST', $storeData);
        $response = $controller->store($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('Store created successfully', $response->getData()->message);
        $this->assertEquals($storeData['name'], $response->getData()->store->name);

    }

    public function test_get_stores_near_postcode(): void
    {
        $faker = Faker\Factory::create();

        $latitude = $faker->latitude(50.1, 60.1);
        $longitude = $faker->longitude(-7.6, 1.8);
        $radius = $faker->numberBetween(1, 20);

        $stores = [
            new Store([
                'name' => 'Store 1',
                'coords' => ['latitude' => 51.509865, 'longitude' => -0.118092],
                'status' => 'open',
                'type' => 'restaurant',
                'max_delivery_distance' => 10,
            ]),
            new Store([
                'name' => 'Store 2',
                'coords' => ['latitude' => 51.509865, 'longitude' => -0.118092],
                'status' => 'open',
                'type' => 'shop',
                'max_delivery_distance' => 15,
            ]),
        ];

        $mockStoreRepository = Mockery::mock(StoreRepository::class);
        $mockStoreRepository->shouldReceive('findStoresNear')
            ->once()
            ->with($latitude, $longitude, $radius)
            ->andReturn(collect($stores));

        $controller = new StoreController($mockStoreRepository);

        $request = Request::create('/api/stores/near', 'GET', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
        ]);
        $response = $controller->getStoresNearPostcode($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $response->getData()->stores);
        $this->assertEquals('Store 1', $response->getData()->stores[0]->name);
        $this->assertEquals('Store 2', $response->getData()->stores[1]->name);

    }

    public function test_get_stores_delivering_to_postcode(): void
    {
        $faker = \Faker\Factory::create();

        $latitude = $faker->latitude(50.1, 60.1);
        $longitude = $faker->longitude(-7.6, 1.8);

        $stores = [
            new Store([
                'name' => 'Store 1',
                'coords' => ['latitude' => 51.509865, 'longitude' => -0.118092],
                'status' => 'open',
                'type' => 'restaurant',
                'max_delivery_distance' => 10,
            ]),
            new Store([
                'name' => 'Store 2',
                'coords' => ['latitude' => 51.509865, 'longitude' => -0.118092],
                'status' => 'open',
                'type' => 'shop',
                'max_delivery_distance' => 15,
            ]),
        ];

        $mockStoreRepository = Mockery::mock(StoreRepository::class);
        $mockStoreRepository->shouldReceive('findStoresDeliveringTo')
            ->once()
            ->with($latitude, $longitude)
            ->andReturn(collect($stores));

        $controller = new StoreController($mockStoreRepository);

        $request = Request::create('/api/stores/delivering', 'GET', [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
        $response = $controller->getStoresDeliveringToPostcode($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertCount(2, $response->getData()->stores);
        $this->assertEquals('Store 1', $response->getData()->stores[0]->name);
        $this->assertEquals('Store 2', $response->getData()->stores[1]->name);
    }
}
