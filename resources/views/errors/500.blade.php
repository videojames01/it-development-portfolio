<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error</title>
    <link rel="stylesheet" href="{{ asset('css/error-pages.css') }}">
</head>
<body>
<section class="error-container">
    <h1 class="error-code">500</h1>
    <p>We're experiencing technical difficulties. Please try again later.</p>

    <section class="navigation-links">
        <a href="{{ route('tasks.index') }}">Go Home</a>
    </section>
</section>
</body>
</html>
