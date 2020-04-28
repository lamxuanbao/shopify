<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    @stack('styles')
</head>
<body>
<div class="container">
    @yield('content')
</div>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.13.2/firebase-database.js"></script>
{{--<script src="https://www.gstatic.com/firebasejs/7.12.0/firebase-messaging.js"></script>--}}

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "{{env('FIREBASE_API_KEY')}}",
        authDomain: "{{env('FIREBASE_AUTH_DOMAIN')}}",
        databaseURL: "{{env('FIREBASE_DATABASE_URL')}}",
        projectId: "{{env('FIREBASE_PROJECt_ID')}}",
        storageBucket: "{{env('FIREBASE_STORAGE_BUCKET')}}",
        messagingSenderId: "{{env('FIREBASE_MESSAGING_SENDER_ID')}}",
        appId: "1:314284626914:web:4793306aa581d2e4"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>
@stack('scripts')
</body>
</html>
