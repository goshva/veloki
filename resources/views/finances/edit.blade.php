<!DOCTYPE html>
<html>
<head>
    <title>Edit Finance Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <form action="{{ route('finances.update', $finance) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', $finance->date) }}">
                <small class="form-text text-muted">Daily result will be recalculated from completed orders.</small>
                @error('date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('finances.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>