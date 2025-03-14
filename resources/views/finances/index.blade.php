<!DOCTYPE html>
<html>
<head>
    <title>Финансовые записи 💸</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navigation')
    <div class="container mt-5">
        
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <a href="{{ route('finances.create') }}" class="btn btn-primary mb-3">Добавить запись ➕</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Дата 📅</th>
                    <th>Дневной результат (₽) 💰</th>
                    <th>Баланс (₽) ⚖️</th>
                    <th>Примечания 📝</th>
                    <th>🛠️</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($finances as $finance)
                    <tr>
                        <td>{{ $finance->date }}</td>
                        <td>{{ number_format($finance->daily_result, 2) }}</td>
                        <td>{{ number_format($finance->balance, 2) }}</td>
                        <td>{{ $finance->notes ?? 'Нет' }}</td>
                        <td>
                            <a href="{{ route('finances.show', $finance) }}" class="btn btn-info btn-sm">👀</a>
                            <a href="{{ route('finances.edit', $finance) }}" class="btn btn-warning btn-sm">✏️</a>
                            <form action="{{ route('finances.destroy', $finance) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены? Это пересчитает последующие балансы.')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>