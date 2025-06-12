<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found</title>
    <link rel="stylesheet" href="{{ asset('css/error-pages.css') }}">
</head>
<body>
<section class="error-container">
    <h1 class="error-code">404</h1>
    <p>We couldn't find the page you're looking for.</p>

    <section class="navigation-links">
        <a href="{{ route('tasks.index') }}">Go Home</a>
        <a href="{{ url()->previous() }}">Go Back</a>
    </section>
</section>
</body>
</html>
