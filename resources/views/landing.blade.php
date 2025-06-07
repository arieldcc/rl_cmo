<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Prediction App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fc;
        }
        .hero {
            padding: 100px 0;
            text-align: center;
        }
        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">SalesPrediction</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a href="{{ route('login') }}" class="nav-link">Login</a></li>
                    <li class="nav-item"><a href="#" class="nav-link">Register</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <h1>Selamat Datang di Aplikasi Prediksi Target Sales</h1>
            <p>Menggunakan metode Regresi Linier Berganda untuk memprediksi performa penjualan lebih akurat dan data-driven.</p>
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Mulai Sekarang</a>
        </div>
    </section>

    <footer class="bg-light text-center text-muted py-4">
        <small>© {{ date('Y') }} SalesPrediction App. All rights reserved.</small>
    </footer>

</body>
</html>
