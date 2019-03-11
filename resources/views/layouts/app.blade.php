<!doctype html>
<html lang="ru" class="h-100">
  <head>
    <meta charset="utf-8">
    <title>index</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
    <style type="text/css">
       @yield('styles')
    </style>

    </head>

  <body class="d-flex flex-column h-100">
    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Главная</a>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="/groups">Работа с группами юзера <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="/profiles">Работа с профилями</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <div class="container" style="margin-top:60px;">
        @yield('content')
    </div>

</body>
@yield('scripts')
</html>