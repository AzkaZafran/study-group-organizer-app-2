<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Error' }}</title>
</head>
<body>
    <h2>{{ $title ?? 'An Error Has Ocurred' }}</h2>
    <h3>{{ $description ?? 'Something went wrong.' }}</h3>
    <a style="margin-top: 100px;" href="{{ route('dashboard') }}">Back to dashboard</a>
</body>
</html>