@include('common.top')

<body>
<div class="x-body">
    <xblock>
        {{--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>--}}
        <button class="layui-btn" onclick="x_admin_show('添加','{{url("admin/add")}}',1000,800)"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：<span id="total_num">0</span>条
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            {{--<th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>--}}
            <th>ID</th>
            <th>用户名称</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
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
        var url = "{{url("admin/get_lists")}}";
        var data = {"_token": "{{csrf_token()}}"};
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
        {{--<td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='2'><i class="layui-icon">&#xe605;</i></div></td>--}}
        <td>@{@ item.id @}@</td>
        <td>@{@ item.username @}@</td>
        <td>@{@ item.created_at @}@</td>
        <td>

            <a onclick="x_admin_show('设置','{{url('admin/edit/@{@ item.id @}@')}}')" href="javascript:;"  title="设置">
                <i class="layui-icon">&nbsp;&#xe642;</i>
            </a>
            @{@# if(item.id > 1){ @}@
            <a title="删除" onclick="deletes(this, '@{@ item.id @}@', '@{@ item.isdel @}@')" href="javascript:;">
                <i class="layui-icon">&nbsp;&#xe640;</i>
            </a>
            @{@# } @}@
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

    /*逻辑-删除*/
    function deletes(obj,id,isdel){
        var url = '{{url("admin/deletes")}}';
        var data = {'_token': '{{csrf_token()}}',
            'id': id,
            'isdel': isdel};
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            $.post(url,data,function(ret){
                if(ret.status){
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                }else{
                    layer.msg('操作失败，请重试!',{icon:2,time:1000});
                }
            },'json');

        });
    }

</script>
</body>
</html>