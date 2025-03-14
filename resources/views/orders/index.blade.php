<!DOCTYPE html>
<html>
<head>
    <title>Список заказов 📋</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('orders.create') }}" class="btn btn-primary mb-3">➕</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>👤</th>
                    <th>🚲</th>
                    <th>🕒</th>
                    <th>🕔</th>
                    <th> 💰</th>
                    <th>📊</th>
                    <th>💳</th>
                    <th>🛠️</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->client->phone }} ({{ $order->client->name ?? 'Без имени' }})</td>
                        <td>{{ $order->bikes->pluck('name')->implode(', ') }}</td>
                        <td>{{ $order->start_time }}</td>
                        <td>{{ $order->end_time ?? 'В процессе' }}</td>
                        <td>{{ number_format($order->total_price ?? 0, 2) }}</td>
                        <td>
                            @switch($order->status)
                                @case('completed')
                                    ✅
                                    @break
                                @default
                                🔄
                            @endswitch
                        </td>
                        <td>{{ $order->acceptor ?? 'Нет' }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-info btn-sm">👀</a>
                            <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm">✏️</a>
                            <form action="{{ route('orders.destroy', $order) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>