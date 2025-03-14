<!DOCTYPE html>
<html>
<head>
    <title>Prices List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('prices.create') }}" class="btn btn-primary mb-3">Add New Price</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bike Group</th>
                    <th>Duration</th>
                    <th>Price (₽)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prices as $price)
                    <tr>
                        <td>{{ $price->id }}</td>
                        <td>{{ ucfirst($price->bike_group) }}</td>
                        <td>{{ $price->duration }}</td>
                        <td>{{ number_format($price->price, 2) }}</td>
                        <td>
                            <a href="{{ route('prices.show', $price) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('prices.edit', $price) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('prices.destroy', $price) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>