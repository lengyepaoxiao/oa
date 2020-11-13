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
            <label for="name" class="layui-form-label">
                <span class="x-red">*</span>帐号名称
            </label>
            <div class="layui-input-inline">
                <input  style="width:700px;" type="text" id="username" name="username" required="" lay-verify="required"
                        autocomplete="off" class="layui-input" readonly>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="appid" class="layui-form-label">
                <span class="x-red">*</span>设置密码
            </label>
            <div class="layui-input-inline">
                <input style="width:700px;" type="text" id="password" name="password"
                       autocomplete="off" lay-verify="required" class="layui-input" placeholder="">
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <input type="hidden" name="id" >
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

        load_init({{$id}});

        //监听提交
        form.on('submit(edit)', function(data){
            var params = data.field;
            var url = "{{url('admin/updates')}}";
            params['_token'] = "{{csrf_token()}}";

            $.post(url,params,function(ret){
                //发异步，把数据提交给php
                if(ret.status == 1){
                    layer.alert(ret.msg, {icon: 6},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
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

    function load_init(id){
        var url = "{{url('admin/get_edit')}}";
        var params = {'_token': '{{csrf_token()}}',
            'id': id,
        };
        $.post(url,params,function(ret){
            if(ret.status == 1){
                var data = ret.data;
                $("[name = 'id']").val(data.id);
                $('#username').val(data.username);

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