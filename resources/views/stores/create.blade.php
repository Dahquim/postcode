<!-- resources/views/stores/create.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Create Store</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="container">
    <h1>Create Store</h1>
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
    <form action="{{ route('stores.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="latitude">Latitude:</label>
            <input type="number" step="any" id="latitude" name="latitude" required>
        </div>
        <div>
            <label for="longitude">Longitude:</label>
            <input type="number" step="any" id="longitude" name="longitude" required>
        </div>
        <div>
            <label for="status">Status:</label>
            <select id="status" name="status" required>
                <option value="open">Open</option>
                <option value="closed">Closed</option>
            </select>
        </div>
        <div>
            <label for="type">Type:</label>
            <select id="type" name="type" required>
                <option value="takeaway">Takeaway</option>
                <option value="restaurant">Restaurant</option>
                <option value="shop">Shop</option>
            </select>
        </div>
        <div>
            <label for="max_delivery_distance">Max Delivery Distance:</label>
            <input type="number" id="max_delivery_distance" name="max_delivery_distance" required>
        </div>
        <button type="submit">Create Store</button>
    </form>
</div>
</body>
</html>
