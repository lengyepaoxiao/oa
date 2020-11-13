<?php echo $__env->make('common.top', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<body>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" id="_search_form">
            <?php /*<input class="layui-input" placeholder="开始日" name="start" id="start">*/ ?>
            <?php /*<input class="layui-input" placeholder="截止日" name="end" id="end">*/ ?>
            <input type="text" name="name"  placeholder="请输入客户名称" autocomplete="off" class="layui-input">
            <input type="text" name="linkman"  placeholder="请输入联系人" autocomplete="off" class="layui-input">
            <input type="text" name="phone"  placeholder="请输入联系电话" autocomplete="off" class="layui-input">
            <a class="layui-btn" href="javascript:;" onclick="searchList();"   lay-filter="sreach">
                <i class="layui-icon">&#xe615;</i>
            </a>
        </form>
    </div>
    <xblock>
        <?php /*<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>*/ ?>
        <button class="layui-btn" onclick="x_admin_show('添加','<?php echo e(url("customer/add")); ?>',1000,800)"><i class="layui-icon"></i>添加</button>
        <span class="x-right" style="line-height:40px">共有数据：<span id="total_num">0</span>条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            <?php /*<th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>*/ ?>
            <th>ID</th>
            <th>客户名称</th>
            <th>联系人</th>
            <th>联系电话</th>
            <th>行业</th>
            <th>公司地址</th>
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
        var url = "<?php echo e(url("customer/get_lists")); ?>";
        var data = {"_token": "<?php echo e(csrf_token()); ?>"};
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
        <?php /*<td><div class="layui-unselect layui-form-checkbox" lay-skin="primary" data-id='2'><i class="layui-icon">&#xe605;</i></div></td>*/ ?>
        <td>@{@ item.id @}@</td>
        <td>@{@ item.name @}@</td>
        <td>@{@ item.linkman @}@</td>
        <td>@{@ item.phone @}@</td>
        <td>@{@ item.trade @}@</td>
        <td>@{@ item.address @}@</td>
        <td>@{@ item.created_at @}@</td>
        <td>
            <a onclick="x_admin_show('编辑','<?php echo e(url('customer/edit/@{@ item.id @}@')); ?>')" href="javascript:;"  title="设置">
                <i class="layui-icon">&nbsp;&#xe642;</i>
            </a>
            <a title="删除" onclick="deletes(this, '@{@ item.id @}@', '@{@ item.isdel @}@')" href="javascript:;">
                <i class="layui-icon">&nbsp;&#xe640;</i>
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

    /*逻辑-删除*/
    function deletes(obj,id,isdel){
        var url = '<?php echo e(url("customer/deletes")); ?>';
        var data = {'_token': '<?php echo e(csrf_token()); ?>',
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

    /*搜索栏*/
    function searchList(){
        var search_start = $('#_search_form input[name="start"]').val();
        var search_end = $('#_search_form input[name="end"]').val();
        var search_name = $('#_search_form input[name="name"]').val();
        var search_linkman = $('#_search_form input[name="linkman"]').val();
        var search_phone = $('#_search_form input[name="phone"]').val();
        var search_address = $('#_search_form input[name="address"]').val();
        var search_cate_id = $('#_search_form select[name="cate_id"]').val();
        var search_status = $('#_search_form select[name="status"]').val();
        var search_is_top = $('#_search_form select[name="is_top"]').val();
        var search_is_recommend = $('#_search_form select[name="is_recommend"]').val();
        var search_is_ad = $('#_search_form select[name="is_ad"]').val();
        var url = "<?php echo e(url('customer/get_lists')); ?>";
        var search_data = {
            "start": search_start,
            "end": search_end,
            "name": search_name,
            "linkman": search_linkman,
            "phone": search_phone,
            "address": search_address,
            'cate_id': search_cate_id,
            'status': search_status,
            'is_top': search_is_top,
            "is_recommend": search_is_recommend,
            'is_ad': search_is_ad
        }
        var data = {
            "_token": "<?php echo e(csrf_token()); ?>",
            "search_data": search_data
        };
        getDataLists(url, data, 0);
    }

</script>
</body>
</html>