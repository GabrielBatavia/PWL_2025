<!DOCTYPE html>
<html>
<head>
    <title>POS System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">POS System</a>
            <div class="navbar-nav">
            <a class="nav-link" href="{{ url('/') }}">Home</a>
            <a class="nav-link" href="{{ url('/category/food-beverage') }}">Products</a>
            <a class="nav-link" href="{{ url('/sales') }}">Sales</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>