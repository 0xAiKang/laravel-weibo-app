<!DOCTYPE html>
<html>
<head>
{{--  yield 的作用其实 更像是一个定一个变量，作为占位符  --}}
{{--  第一个参数的作用是变量名称，第二个参数作用是默认值  --}}
  <title>@yield('title', 'Weibo App') - Laravel 新手入门教程 </title>
</head>
<body>
@yield('content')
</body>
</html>
