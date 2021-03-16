<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page not found</title>
    <link rel="stylesheet" href="/css/all.css">

    <script>
        (function () {
            window.Laravel = {
                csrfToken: '{{csrf_token()}}'
            };
        })();
    </script>
</head>
<body class="">
<h1 class="_text_center">You don't have enough permissions to access this page</h1>
</body>


</html>
