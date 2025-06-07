<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Admin Panel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sales.index') }}">Data Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('prediksi.index') }}">Prediksi</a>
                </li>
                <li><a class="nav-link" href="{{ route('prediksi.grafik') }}">Grafik Prediksi</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-link nav-link" type="submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
