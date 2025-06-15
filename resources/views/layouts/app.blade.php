<!-- Hello curious ! -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Hart Print - Schedule Optimization')</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  @include('partials.header')
  <main class="main-content" role="main">
      <div class="container">
          <div class="row">
              @yield('content')
            </div>
        </div>
    </main>
    @include('partials.footer')
    <script>console.log("%chello", "color: #fff; font-weight: bold; font-size: 16px; background: #01b0f0; padding: 2px 6px; border-radius: 5px;");</script>
    <script>console.log("%ccurious !", "color: #fff; font-weight: bold; font-size: 16px; background: #ea3b90; padding: 2px 6px; border-radius: 5px;");</script>
</body>
</html>
