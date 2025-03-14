<!DOCTYPE html>
<html>
<head>
    <title>Bikes List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('bikes.create') }}" class="btn btn-primary mb-3">Add New Bike</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Group</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bikes as $bike)
                    <tr>
                        <td>{{ $bike->id }}</td>
                        <td>{{ $bike->name }}</td>
                        <td>{{ ucfirst($bike->group) }}</td>
                        <td>{{ $bike->description }}</td>
                        <td>
                            <a href="{{ route('bikes.show', $bike) }}" class="btn btn-info btn-sm">View</a>
                            <a href="{{ route('bikes.edit', $bike) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('bikes.destroy', $bike) }}" method="POST" style="display:inline;">
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