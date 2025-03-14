
<!DOCTYPE html>
<html>
<head>
    <title>Price Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ ucfirst($price->bike_group) }} - {{ $price->duration_hours == 0 ? 'Until 20:00' : $price->duration_hours . ' Hours' }}</h5>
                <p class="card-text"><strong>Price:</strong> {{ number_format($price->price, 2) }} ₽</p>
                <p class="card-text"><strong>Description:</strong> {{ $price->description ?? 'N/A' }}</p>
                <p class="card-text"><strong>Created At:</strong> {{ $price->created_at }}</p>
                <p class="card-text"><strong>Updated At:</strong> {{ $price->updated_at }}</p>
            </div>
        </div>
        <a href="{{ route('prices.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>
