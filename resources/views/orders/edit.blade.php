<!DOCTYPE html>
<html>
<head>
    <title>Редактировать заказ ✏️</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        <form action="{{ route('orders.update', $order) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="client_id" class="form-label">Клиент 👤</label>
                <select class="form-control @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $order->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->phone }} ({{ $client->name ?? 'Без имени' }})
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Время начала 🕒</label>
                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', $order->start_time->format('Y-m-d\TH:i')) }}">
                @error('start_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="end_time" class="form-label">Время окончания 🕔</label>
                <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', $order->end_time ? $order->end_time->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                @error('end_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="bike_ids" class="form-label">Велосипеды 🚲</label>
                <select multiple class="form-control @error('bike_ids') is-invalid @enderror" id="bike_ids" name="bike_ids[]">
                    @foreach ($bikes as $bike)
                        <option value="{{ $bike->id }}" {{ in_array($bike->id, old('bike_ids', $order->bikes->pluck('id')->toArray())) ? 'selected' : '' }}>
                            {{ $bike->name }} ({{ $bike->group ?? 'механический' }})
                        </option>
                    @endforeach
                </select>
                @error('bike_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Статус 📊</label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Ожидает ⏳</option>
                    <option value="active" {{ old('status', $order->status) == 'active' ? 'selected' : '' }}>Активен ▶️</option>
                    <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Завершён ✅</option>
                    <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Отменён 🚫</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="acceptor" class="form-label">Способ оплаты 💳</label>
                <select class="form-control @error('acceptor') is-invalid @enderror" id="acceptor" name="acceptor">
                    <option value="" {{ old('acceptor', $order->acceptor) == '' ? 'selected' : '' }}>Нет 🚫</option>
                    <option value="cash" {{ old('acceptor', $order->acceptor) == 'cash' ? 'selected' : '' }}>Наличные 💵</option>
                    <option value="cardR" {{ old('acceptor', $order->acceptor) == 'cardR' ? 'selected' : '' }}>Карта (Рома) 💳</option>
                    <option value="cardM" {{ old('acceptor', $order->acceptor) == 'cardM' ? 'selected' : '' }}>Карта (Миша) 💳</option>
                </select>
                @error('acceptor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="total_price" class="form-label">Сумма платежа 💰</label>
                <input type="text" class="form-control" id="total_price" name="total_price" value="{{ number_format($totalPrice, 2) }} RUB" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Обновить 💾</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Отмена ⬅️</a>
        </form>
    </div>
</body>
</html>
       