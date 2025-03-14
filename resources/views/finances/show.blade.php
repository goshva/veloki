<!DOCTYPE html>
<html>
<head>
    <title>Детали финансовой записи 💸</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Дата: {{ $finance->date }} 📅</h5>
                <p class="card-text"><strong>Дневной результат 💰:</strong> {{ number_format($finance->daily_result, 2) }} ₽</p>
                <p class="card-text"><strong>Баланс ⚖️:</strong> {{ number_format($finance->balance, 2) }} ₽</p>
                <p class="card-text"><strong>Примечания 📝:</strong> {{ $finance->notes ?? 'Нет' }}</p>
                <p class="card-text"><strong>Создано 🕒:</strong> {{ $finance->created_at }}</p>
                <p class="card-text"><strong>Обновлено 🕔:</strong> {{ $finance->updated_at }}</p>
            </div>
        </div>
        <a href="{{ route('finances.index') }}" class="btn btn-secondary mt-3">Назад к списку ⬅️</a>
    </div>
</body>
</html>