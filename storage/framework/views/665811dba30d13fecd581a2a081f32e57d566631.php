<?php echo $__env->make('common.top', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
                       autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="appid" class="layui-form-label">
                <span class="x-red">*</span>设置密码
            </label>
            <div class="layui-input-inline">
                <input style="width:700px;" type="password" id="password" name="password"
                       autocomplete="off" lay-verify="required" class="layui-input" placeholder="">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                增加
            </button>
        </div>
    </form>
</div>
<script>
    layui.use(['form','layer'], function(){
        var $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;



        //监听提交
        form.on('submit(add)', function(data){
            var params = data.field;
            var url = "<?php echo e(url('admin/create')); ?>";
            params['_token'] = "<?php echo e(csrf_token()); ?>";


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

</script>

</body>

</html>