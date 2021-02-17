<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Full stack blog</title>
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
            <div id="app">
                <mainapp></mainapp>
            </div>
    </body>

<script src="{{mix('/js/app.js')}}"></script>
</html>
{{--https://www.youtube.com/watch?v=NMTEfaPEYB8&list=PLKpfEl4N7tRqIx4VZmuZBnUQ6vG4uDgq7&index=2--}}
