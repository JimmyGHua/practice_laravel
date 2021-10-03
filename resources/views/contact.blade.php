{{--
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<body class="antialiased">
    <div class="container">
        <h1>Contact Page</h1>
    </div>
</body>

</html> --}}

@extends('layouts.app')

{{-- 把section內的內容填至 layouts/app.blade.php 中的yield區域內 --}}
@section('content')
    <h1>Contact Page</h1>

    @if (count($people))
        <ul>
            @foreach ($people as $person)
                <li>{{ $person }}</li>
            @endforeach
        </ul>
    @endif
    {{-- 此@endsection可替換為@stop --}}
@endsection
{{-- 此頁多一個footer執行alert js --}}
@section('footer')
    {{-- <script>
        alert('test')

    </script> --}}
@endsection
