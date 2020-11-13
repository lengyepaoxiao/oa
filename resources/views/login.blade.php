<!doctype html>
<html lang="en">
<head>
  	<meta charset="UTF-8">
  	<title>派单系统登录</title>
  	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="stylesheet" href="{{asset('/css/font.css')}}">
    <link rel="stylesheet" href="{{asset('/css/xadmin.css')}}">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{asset('/js/layui/layui.js')}}" charset="utf-8"></script>
    <script type="text/javascript" src="{{asset('/js/xadmin.js')}}"></script>

</head>
<body class="login-bg">
    
    <div class="login">
        <div class="message">派单系统管理平台</div>
        <div id="darkbannerwrap"></div>
        
        <form method="post" class="layui-form" >
            <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
    </div>

    <script>
        $(function  () {
            layui.use('form', function(){
              var form = layui.form;
                $ = layui.jquery;
                form.render();
              // layer.msg('玩命卖萌中', function(){
              //   //关闭后的操作
              //   });
              //监听提交
              form.on('submit(login)', function(data) {
                  var url = '{{url("login/check")}}';
                  var params = data.field;
                  params['_token'] = "{{csrf_token()}}";
                  $.post(url, params, function (ret) {
                      if (ret.status == 1) {
                          layer.alert(ret.msg,{icon: 6}, function () {
                              location.href = '{{url("index")}}';
                          });
                      }else{
                          layer.alert(ret.msg, {icon: 7}, function () {
                              location.href = '{{url("login")}}';
                          });
                      }
                  }, 'json');
                  return false;
              });
            });
        })
    </script>
</body>
</html>