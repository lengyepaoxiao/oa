<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>@yield('title') - 派单系统</title>
    @yield('style')
    @yield('script_header')

</head>
<body>

<!-- 主体内容-->
@yield('content')

</body>
</html>
<script type="text/javascript" src="/js/jquery-3.2.1.min.js"></script>
@yield('script_footer')

