<!DOCTYPE html>
<html>
<head>
    <title>Edit Bike</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        
        <form action="{{ route('bikes.update', $bike) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $bike->name) }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="group" class="form-label">Group</label>
                <select class="form-control @error('group') is-invalid @enderror" id="group" name="group">
                    <option value="mechanical" {{ old('group', $bike->group) == 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                    <option value="electric" {{ old('group', $bike->group) == 'electric' ? 'selected' : '' }}>Electric</option>
                </select>
                @error('group')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $bike->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('bikes.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>