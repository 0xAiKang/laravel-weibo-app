<!DOCTYPE html>
<html>
<head>
{{--  yield 的作用其实 更像是一个定一个变量，作为占位符  --}}
{{--  第一个参数的作用是变量名称，第二个参数作用是默认值  --}}
  <title>@yield('title', 'Weibo App') - Laravel 入门教程</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
  {{--include 是Blade 提供的视图引用方法 --}}
  @include('layouts._header')
<div class="container">
  @include('shared._message')
  {{--yield 是Blade 提供的占位符方法--}}
  @yield('content')
  @include('layouts._footer')
</div>
</body>
</html>
