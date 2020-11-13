@include('common.top')
<style>
    .layui-form-label {
        float: left;
        display: block;
        padding: 9px 15px;
        width: 110px;
        font-weight: 400;
        line-height: 20px;
        text-align: right;
    }
</style>
<body>
<div class="x-body">
    <form class="layui-form" id="admin-form">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="x-red">*</span>员工姓名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="username" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="job_no" class="layui-form-label">
                <span class="x-red">*</span>员工工号
            </label>
            <div class="layui-input-inline">
                <input type="text" id="job_no" name="job_no" required="" lay-verify="required"
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="position" class="layui-form-label">
                <span class="x-red"></span>职位
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="position" name="position" required="" lay-verify=""
                        autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="mobile" class="layui-form-label">
                <span class="x-red"></span>手机号码
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="mobile" name="mobile" required="" lay-verify=""
                        autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="email" class="layui-form-label">
                <span class="x-red"></span>邮箱地址
            </label>
            <div class="layui-input-inline" style="width: 500px;">
                <input type="text" id="email" name="email" required="" lay-verify=""
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <input type="hidden" name="id" value="{{$id}}">
            <button  class="layui-btn" lay-filter="edit" lay-submit="">
                编辑
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer'], function(){
        var $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //数据加载渲染
        load_init({{$id}});

        //自定义验证规则
        /*form.verify({
            nikename: function(value){
                if(value.length < 5){
                    return '昵称至少得5个字符啊';
                }
            }
            ,pass: [/(.+){6,12}$/, '密码必须6到12位']
            ,repass: function(value){
                if($('#L_pass').val()!=$('#L_repass').val()){
                    return '两次密码不一致';
                }
            }
        });*/

        //监听提交
        form.on('submit(edit)', function(data){
            var params = data.field;
            var url = "{{url('user/updates')}}";
            params['_token'] = "{{csrf_token()}}";

            $.post(url,params,function(ret){
                //发异步，把数据提交给php
                if(ret.status == 1){
                    layer.alert(ret.msg, {icon: 6},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                        parent.location.reload();
                    });
                }else if(ret.status == 2){
                    layer.alert(ret.msg, {icon: 7});
                }else {
                    layer.alert(ret.msg, {icon: 2}, function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    });
                }
            },'json');
            return false;
        });
    });


    //加载渲染
    function load_init(id){
        var url = "{{url('user/get_edit')}}";
        var params = {'_token': '{{csrf_token()}}',
            'id': id,
        };
        $.post(url,params,function(ret){
            if(ret.status == 1){
                var data = ret.data;

                //基本数据渲染
                $('#username').val(data.username);
                $('#job_no').val(data.job_no);
                $('#mobile').val(data.mobile);
                $('#email').val(data.email);
                $('#position').val(data.position);

                //执行渲染
                layui.form.render();
            }else {
                layer.alert(ret.msg, {icon: 2});
                // 获得frame索引
                var index = parent.layer.getFrameIndex(window.name);
                //关闭当前frame
                parent.layer.close(index);
                return false;
            }
        },'json');
    }
</script>

</body>

</html>