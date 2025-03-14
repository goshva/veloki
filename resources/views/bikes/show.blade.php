<!DOCTYPE html>
<html>
<head>
    <title>Bike Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $bike->name }}</h5>
                <p class="card-text"><strong>Group:</strong> {{ ucfirst($bike->group) }}</p>
                <p class="card-text"><strong>Description:</strong> {{ $bike->description ?? 'No description' }}</p>
                <p class="card-text"><strong>Created At:</strong> {{ $bike->created_at }}</p>
                <p class="card-text"><strong>Updated At:</strong> {{ $bike->updated_at }}</p>
            </div>
        </div>
        <a href="{{ route('bikes.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>