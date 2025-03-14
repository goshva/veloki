
<!DOCTYPE html>
<html>
<head>
    <title>Edit Price</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <form action="{{ route('prices.update', $price) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="bike_group" class="form-label">Bike Group</label>
                <select class="form-control @error('bike_group') is-invalid @enderror" id="bike_group" name="bike_group">
                    <option value="mechanical" {{ old('bike_group', $price->bike_group) == 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                    <option value="electric" {{ old('bike_group', $price->bike_group) == 'electric' ? 'selected' : '' }}>Electric</option>
                </select>
                @error('bike_group')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="duration_hours" class="form-label">Duration (Hours)</label>
                <input type="number" class="form-control @error('duration_hours') is-invalid @enderror" id="duration_hours" name="duration_hours" value="{{ old('duration_hours', $price->duration_hours) }}" min="0">
                <small class="form-text text-muted">Enter 0 for "Until 20:00"</small>
                @error('duration_hours')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (₽)</label>
                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $price->price) }}">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <input type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $price->description) }}">
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('prices.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
