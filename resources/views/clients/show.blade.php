
<!DOCTYPE html>
<html>
<head>
    <title>Client Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $client->phone }}</h5>
                <p class="card-text"><strong>Name:</strong> {{ $client->name ?? 'Unnamed' }}</p>
                <p class="card-text"><strong>Created At:</strong> {{ $client->created_at }}</p>
                <p class="card-text"><strong>Updated At:</strong> {{ $client->updated_at }}</p>
            </div>
        </div>
        <a href="{{ route('clients.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    </div>
</body>
</html>
