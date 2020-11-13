@include('common.top')

<body>
<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">角色列表</a>
      </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="x-body">
    <xblock>
        <button class="layui-btn" onclick="x_admin_show('添加商品','{{url("goods/add")}}',800,600)"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：88 条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            {{--<th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>--}}
            <th>ID</th>
            <th width="400">权限规则</th>
            <th width="400">权限名称</th>
            <th width="400">权限描述</th>
            <th>操作</th>
        </thead>
        <tbody id="view"></tbody>
    </table>
    <div class="page">
        <div id="pagesize"></div>
    </div>

</div>
<script>
    layui.use(['laydate','layer','form','jquery'],function(){
        var layer = layui.layer;
        var laydate = layui.laydate;
        var $ = layui.jquery;

        //自动加载
        var url = "{{url("admin/role_getList")}}";
        var data = {"_token": "{{csrf_token()}}",
            'witch_module':0};
        getDataLists(url,data,0);
    });
</script>
<script id="tpl-lists" type="text/html">
    @{@# if(d.data.length === 0){ @}@
    <tr>
        <td colspan="8" align="center">没有数据</td>
    </tr>
    @{@#  } @}@
    @{@#  layui.each(d.data, function(index, item){ @}@
    <tr>
        <td>@{@ item.id @}@</td>
        <td>@{@ item.action @}@</td>
        <td>@{@ item.name @}@</td>
        <td>@{@ item.remarks @}@</td>
        <td>
            <a title="编辑"  onclick="x_admin_show('编辑','xxx.html')" href="javascript:;">
                <i class="layui-icon">&#xe642;</i>
            </a>
            <a title="恢复" onclick="role_del(this, @{@ item.id @}@, @{@ item.isdel @}@)" href="javascript:;">
                <i class="layui-icon">&#xe618;&nbsp;</i>
            </a>
        </td>
    </tr>
    @{@#  }); @}@
</script>
<script>
    layui.use('laydate', function(){
        var laydate = layui.laydate;



        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });
    });

    /*用户管理角色-逻辑删除*/
    function role_del(obj,id,isdel){
        var url = "{{url('admin/role_del')}}";
        var data = {"_token": "{{csrf_token()}}",
            "id":id,
            "isdel":isdel};
        layer.confirm('确认要删除吗？',function(index){
            $.post(url,data,function(ret){
                if(ret.status == 1) {
                    //发异步删除数据
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!', {icon: 1, time: 1000});
                }else{
                    layer.msg('删除失败,请重试!', {icon: 2, time: 1000});
                }
            },'json');
        });
    }
</script>
<script>var _hmt = _hmt || []; (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();</script>
</body>

</html>