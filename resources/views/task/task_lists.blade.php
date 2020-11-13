@include('common.top')
<body>
<div class="x-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 x-so" id="_search_form">
            <input class="layui-input" placeholder="开始日" name="start" id="start">
            <input class="layui-input" placeholder="截止日" name="end" id="end">
            <input type="text" name="name"  placeholder="请输入客户名称" autocomplete="off" class="layui-input">
            <input type="text" name="employee_name"  placeholder="请输入员工名称" autocomplete="off" class="layui-input">
            <div class="layui-input-inline">
                <select name="status">
                    <option value="" >全部</option>
                    <option value="0" >待完成</option>
                    <option value="1">已完成</option>
                </select>
            </div>
            <a class="layui-btn" href="javascript:;" onclick="searchList();"   lay-filter="sreach">
                <i class="layui-icon">&#xe615;</i>
            </a>
        </form>
    </div>
    <xblock>
        {{--<button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>--}}
        <button class="layui-btn" onclick="x_admin_show('添加','{{url("task/add")}}',1000,800)"><i class="layui-icon"></i>添加</button>
        <button class="layui-btn" id="print">打印</button>
        <span class="x-right" style="line-height:40px">共有数据：<span id="total_num">0</span>条</span>
    </xblock>
    <table class="layui-table">
        <thead>
        <tr>
            {{--<th>
                <div class="layui-unselect header layui-form-checkbox" lay-skin="primary"><i class="layui-icon">&#xe605;</i></div>
            </th>--}}
            <th>ID</th>
            <th>客户名称</th>
            <th>派工日期</th>
            <th>开始时间</th>
            <th>报修地址</th>
            <th>联系电话</th>
            <th>维修人员</th>
            <th>任务图片</th>
            <th>完成状态</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="view"></tbody>
    </table>
    <div class="page">
        <div id="pagesize"></div>
    </div>
    <div id="print_table"></div>
</div>
<script>
    let public_data = '';
    layui.use(['laydate','layer','form','jquery'],function(){
        var layer = layui.layer;
        var laydate = layui.laydate;
        var $ = layui.jquery;

        //自动加载
        var url = "{{url("task/get_lists")}}";
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
        <td>@{@ item.name @}@</td>
        <td>@{@ item.job_date @}@</td>
        <td>@{@ item.stime @}@</td>
        <td>@{@ item.address @}@</td>
        <td>@{@ item.phone @}@</td>
        <td>@{@ item.employee_name @}@</td>
        <td>
            @{@# if(item.img === ''){ @}@
                暂无上传任务图片
            @{@# }else{ @}@
                <a href="@{@ item.img @}@" target="_blank" >点击预览</a>
            @{@# } @}@
        </td>
        <td>
            @{@# if(item.status === 0){ @}@
                <span style="color: orangered;">待完成</span>
            @{@# }else{ @}@
                <span style="color: green;">已完成</span>
            @{@# } @}@
        </td>
        <td>@{@ item.created_at @}@</td>
        <td>

            <a onclick="x_admin_show('编辑','{{url('task/edit/@{@ item.id @}@')}}')" href="javascript:;"  title="设置">
                <i class="layui-icon">&nbsp;&#xe642;</i>
            </a>
            @if($uid == 1)
            <a title="删除" onclick="deletes(this, '@{@ item.id @}@', '@{@ item.isdel @}@')" href="javascript:;">
                <i class="layui-icon">&nbsp;&#xe640;</i>
            </a>
            @endif
            <a onclick="x_admin_show('制作表单','{{url('task/make_form/@{@ item.id @}@/@{@ item.type @}@')}}')" href="javascript:;"  title="制作表单">
                <i class="layui-icon">&nbsp;&#xe637;</i>
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
        var url = '{{url("task/deletes")}}';
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

    /*搜索栏*/
    function searchList(){
        var search_start = $('#_search_form input[name="start"]').val();
        var search_end = $('#_search_form input[name="end"]').val();
        var search_name = $('#_search_form input[name="name"]').val();
        var search_employee_name = $('#_search_form input[name="employee_name"]').val();
        var search_status = $('#_search_form select[name="status"]').val();
        var url = "{{url('task/get_lists')}}";
        var search_data = {
            "start": search_start,
            "end": search_end,
            'name': search_name,
            'employee_name': search_employee_name,
            'status': search_status
        }
        var data = {
            "_token": "{{csrf_token()}}",
            "search_data": search_data
        };
        getDataLists(url, data, 0);
    }

    window.onload = function() {
        var btnPrint = document.getElementById("print");//“打印”按钮
        btnPrint.onclick = function ()//为“打印”按钮添加点击事件
        {
            printTable();//打印表格
        }
    }

    //打印表格
    function printTable(){
        let print_html = '';
        print_html += '<style type="text/css">';
        print_html += '#print_table{width:90%; margin: 20px auto;}';
        print_html += 'table{border-right: 1px solid black; border-bottom: 1px solid black;}';
        print_html += 'table th{border-left:1px solid black; border-top:1px solid black;}';
        print_html += 'table td{border-left:1px solid black; border-top:1px solid black;}';
        print_html += '</style>';
        print_html += '<table width="100%"><tr>';
        print_html += '<th>ID</th>';
        print_html += '<th>客户名称</th>';
        print_html += '<th>派工日期</th>';
        print_html += '<th>开始时间</th>';
        print_html += '<th>报修地址</th>';
        print_html += '<th>联系电话</th>';
        print_html += '<th width="10%">维修人员工号</th>';
        print_html += '<th>维修人员</th>';
        print_html += '<th>完成状态</th>';
        print_html += '<th>创建时间</th></tr>';
        for(let i = 0; i < public_data.length; i++){
            print_html += '<tr><td>'+ public_data[i]['id'] +'</td>';
            print_html += '<td>'+ public_data[i]['name'] +'</td>';
            print_html += '<td>'+ public_data[i]['job_date'] +'</td>';
            print_html += '<td>'+ public_data[i]['stime'] +'</td>';
            print_html += '<td>'+ public_data[i]['address'] +'</td>';
            print_html += '<td>'+ public_data[i]['phone'] +'</td>';
            print_html += '<td>'+ public_data[i]['job_no'] +'</td>';
            print_html += '<td>'+ public_data[i]['employee_name'] +'</td>';
            print_html += '<td>'+ (public_data[i]['status'] == 1 ? '已完成' : '未完成') +'</td>';
            print_html += '<td>'+ public_data[i]['created_at'] +'</td></tr>';
        }
        print_html += '</table>';
        $('#print_table').append(print_html);

        var tableToPrint = document.getElementById('print_table');//将要被打印的表格
        var newWin= window.open("");//新打开一个空窗口
        newWin.document.write(tableToPrint.outerHTML);//将表格添加进新的窗口
        newWin.document.close();//在IE浏览器中使用必须添加这一句
        newWin.focus();//在IE浏览器中使用必须添加这一句

        newWin.print();//打印
        newWin.close();//关闭窗口
        $('#print_table').empty();
        print_html = '';
    }
</script>
</body>
</html>