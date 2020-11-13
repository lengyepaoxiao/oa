<?php echo $__env->make('common.top', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
            obj = $(this);
            obj.hide();

            var params = data.field;
            var url = "<?php echo e(url('user/create')); ?>";
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
                    obj.show();
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