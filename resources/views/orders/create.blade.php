<!DOCTYPE html>
<html>

<head>
    <title>Создать заказ ➕</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .autocomplete-container {
            position: relative;
        }

        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            background: white;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .autocomplete-suggestion {
            padding: 8px 12px;
            cursor: pointer;
        }

        .autocomplete-suggestion:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    @include('layouts.navigation')
    <div class="container mt-5">

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="mb-3 autocomplete-container">
                <label for="phone" class="form-label">Телефон клиента 📞</label>
                <input type="text" class="form-control @error('client_id') is-invalid @enderror" id="phone" name="phone"
                    placeholder="Введите номер телефона" autocomplete="off">
                <input type="hidden" id="client_id" name="client_id">
                <div id="phone-suggestions" class="autocomplete-suggestions"></div>
                @error('client_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="client_name" class="form-label">Имя клиента 👤</label>
                <input type="text" class="form-control" id="client_name" name="client_name"
                    placeholder="Имя заполнится автоматически">
            </div>
            <div class="mb-3">
                <label for="start_time" class="form-label">Время начала 🕒</label>
                <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror"
                    id="start_time" name="start_time" value="{{ now()->format('Y-m-d\TH:i') }}">
                @error('start_time')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="bike_ids" class="form-label">Велосипеды 🚲</label>
                <select multiple class="form-control @error('bike_ids') is-invalid @enderror" id="bike_ids"
                    name="bike_ids[]">
                    @foreach ($bikes as $bike)
                        <option value="{{ $bike->id }}">{{ $bike->name }} ({{ $bike->group ?? 'механический' }})</option>
                    @endforeach
                </select>
                @error('bike_ids')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Статус 📊</label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="pending">Ожидает ⏳</option>
                    <option value="active" selected>Активен ▶️</option>
                    <option value="completed">Завершён ✅</option>
                    <option value="cancelled">Отменён 🚫</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="acceptor" class="form-label">Способ оплаты 💳</label>
                <select class="form-control @error('acceptor') is-invalid @enderror" id="acceptor" name="acceptor">
                    <option value="">Нет 🚫</option>
                    <option value="cash">Наличные 💵</option>
                    <option value="cardR" selected>Карта (Рома) 💳</option>
                    <option value="cardM">Карта (Миша) 💳</option>
                </select>
                @error('acceptor')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Сохранить 💾</button>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Отмена ⬅️</a>
        </form>
    </div>

    <script>
        const phoneInput = document.getElementById('phone');
        const clientIdInput = document.getElementById('client_id');
        const clientNameInput = document.getElementById('client_name');
        const suggestionsDiv = document.getElementById('phone-suggestions');

        phoneInput.addEventListener('input', debounce(async function () {
            const query = this.value.trim();
            console.log('Query entered:', query); // Debug: Log the query

            if (query.length < 3) {
                suggestionsDiv.innerHTML = '';
                suggestionsDiv.style.display = 'none';
                return;
            }

            try {
                const url = `/search-clients?query=${encodeURIComponent(query)}`;
                console.log('Fetching:', url); // Debug: Log the URL
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const clients = await response.json();
                console.log('Response:', clients); // Debug: Log the response

                suggestionsDiv.innerHTML = '';
                if (clients.length === 0) {
                    suggestionsDiv.innerHTML = '<div class="autocomplete-suggestion">Нет совпадений</div>';
                    suggestionsDiv.style.display = 'block';
                    return;
                }

                clients.forEach(client => {
                    const suggestion = document.createElement('div');
                    suggestion.className = 'autocomplete-suggestion';
                    suggestion.textContent = `${client.phone} ${client.name ? '(' + client.name + ')' : ''}`;
                    suggestion.addEventListener('click', function () {
                        phoneInput.value = client.phone;
                        clientIdInput.value = client.id;
                        clientNameInput.value = client.name || '';
                        suggestionsDiv.style.display = 'none';
                    });
                    suggestionsDiv.appendChild(suggestion);
                });

                suggestionsDiv.style.display = 'block';
            } catch (error) {
                console.error('Error fetching clients:', error);
                suggestionsDiv.innerHTML = '<div class="autocomplete-suggestion">Ошибка поиска</div>';
                suggestionsDiv.style.display = 'block';
            }
        }, 300));

        phoneInput.addEventListener('change', function () {
            if (!this.value) {
                clientIdInput.value = '';
                clientNameInput.value = '';
            }
        });
        phoneInput.addEventListener('blur', function () {
            if (!clientIdInput.value && this.value) {
                console.log('New client will be created with phone:', this.value);
                // Optionally display a UI hint
                clientNameInput.placeholder = 'Новый клиент будет создан';
            }
        });
        document.addEventListener('click', function (e) {
            if (!phoneInput.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.style.display = 'none';
            }
        });

        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }
    </script>
</body>

</html>