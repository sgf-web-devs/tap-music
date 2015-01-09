<!DOCTYPE html>
<html lang="en" ng-app="tapmusicApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale = 1.0, user-scalable = no">
    <title id="app-title">TapMusic</title>


    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/main.css">


    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.26/angular.min.js"></script>
    <script src="https://cdn.firebase.com/js/client/2.0.4/firebase.js"></script>
    <script src="https://cdn.firebase.com/libs/angularfire/0.9.1/angularfire.min.js"></script>
    <script src="//js.pusher.com/2.2/pusher.min.js"></script>
    <script src="http://rubaxa.github.io/Sortable/Sortable.js"></script>
    <script src="http://rubaxa.github.io/Sortable/ng-sortable.js"></script>
    <script src="//cdn.jsdelivr.net/angular.pusher/latest/pusher-angular.min.js"></script>
    <script src="/js/vendor/ui-bootstrap-custom-tpls-0.12.0.min.js"></script>
    @yield('header')


</head>
<body>


@yield('content')



@include('layouts.partials.footer')

@yield('footer')


</body>
</html>
