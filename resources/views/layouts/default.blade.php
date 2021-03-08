<!DOCTYPE html>
<html>
<head>
{{--  yield 的作用其实 更像是一个定一个变量，作为占位符  --}}
{{--  第一个参数的作用是变量名称，第二个参数作用是默认值  --}}
  <title>@yield('title', 'Weibo App') - Laravel 入门教程</title>
  <link rel="stylesheet" href="/css/app.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/">Weibo App</a>
    <ul class="navbar-nav justify-content-end">
      <li class="nav-item"><a class="nav-link" href="/help">帮助</a></li>
      <li class="nav-item" ><a class="nav-link" href="#">登录</a></li>
    </ul>
  </div>
</nav>

<div class="container">
  @yield('content')
</div>
</body>
</html>
