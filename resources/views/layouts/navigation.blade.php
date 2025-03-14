
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">Велоки 🚲</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">📊</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('orders.index') }}">Заказы 📋</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('finances.index') }}">Финансы 💸</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clients.index') }}">Клиенты 👤</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bikes.index') }}">Велосипеды 🚲</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('prices.index') }}">Цены 💰</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
