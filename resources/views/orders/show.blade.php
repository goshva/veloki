<!DOCTYPE html>
<html>
<head>
    <title>Детали заказа 📋</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Заказ #{{ $order->id }}</h5>
                <p class="card-text"><strong>👤:</strong> {{ $order->client->phone }} ({{ $order->client->name ?? 'Без имени' }})</p>
                <p class="card-text"><strong>🚲:</strong> {{ $order->bikes->pluck('name')->implode(', ') }}</p>
                <p class="card-text"><strong>🕒:</strong> {{ $order->start_time }}</p>
                <p class="card-text"><strong>🕔:</strong> {{ $order->end_time ?? 'В процессе' }}</p>
                <p class="card-text"><strong>Стоимость 💰:</strong> {{ number_format($order->total_price ?? 0, 2) }} ₽</p>
                <p class="card-text"><strong>📊:</strong> 
                    @switch($order->status)
                        @case('completed')
                            ✅
                            @break
                        @default
                            {{ ucfirst($order->status) }}
                    @endswitch
                </p>
                <p class="card-text"><strong>💳:</strong> {{ $order->acceptor ?? 'Нет' }}</p>
            </div>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-3">Назад к списку ⬅️</a>
    </div>
</body>
</html>