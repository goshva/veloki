
<!DOCTYPE html>
<html>
<head>
    <title>📊 | Велоки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    @include('layouts.navigation')

    <div class="container mt-5">
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Всего заказов 📋</h5>
                        <p class="card-text display-4">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Активные заказы ▶️</h5>
                        <p class="card-text display-4">{{ $activeOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Завершённые заказы ✅</h5>
                        <p class="card-text display-4">{{ $completedOrders }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Общая выручка 💰</h5>
                        <p class="card-text display-4">{{ number_format($totalRevenue, 2) }} ₽</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Текущий баланс ⚖️</h5>
                        <p class="card-text display-4">{{ number_format($currentBalance, 2) }} ₽</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="mt-5">Последние заказы 📋</h2>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Клиент 👤</th>
                    <th>Велосипеды 🚲</th>
                    <th>Начало 🕒</th>
                    <th>Статус 📊</th>
                    <th>Стоимость 💰</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recentOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->client->phone }} ({{ $order->client->name ?? 'Без имени' }})</td>
                        <td>{{ $order->bikes->pluck('name')->implode(', ') }}</td>
                        <td>{{ $order->start_time }}</td>
                        <td>
                            @switch($order->status)
                                @case('active')
                                    Активен ▶️
                                    @break
                                @case('completed')
                                    Завершён ✅
                                    @break
                                @case('pending')
                                    Ожидает ⏳
                                    @break
                                @case('cancelled')
                                    Отменён 🚫
                                    @break
                            @endswitch
                        </td>
                        <td>{{ number_format($order->total_price ?? 0, 2) }} ₽</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
