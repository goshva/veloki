<!DOCTYPE html>
<html>
<head>
    <title>Create Price</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <form action="{{ route('prices.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="bike_group" class="form-label">Bike Group</label>
                <select class="form-control @error('bike_group') is-invalid @enderror" id="bike_group" name="bike_group">
                    <option value="mechanical" {{ old('bike_group') == 'mechanical' ? 'selected' : '' }}>Mechanical</option>
                    <option value="electric" {{ old('bike_group') == 'electric' ? 'selected' : '' }}>Electric</option>
                </select>
                @error('bike_group')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration</label>
                <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" placeholder="e.g., 1 hour, until 20:00">
                @error('duration')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price (₽)</label>
                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('prices.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>