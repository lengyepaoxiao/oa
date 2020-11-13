<?php echo $__env->make('common.top', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
<!-- include select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
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
            <label for="customer_id" class="layui-form-label">
                <span class="x-red">*</span>客户名称
            </label>
            <div class="layui-input-inline">
                <select id="customer_id" name="customer_id" lay-filter="" lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach($customer_data as $v): ?>
                        <option value="<?php echo e($v['id']); ?>"><?php echo e($v['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="employee" class="layui-form-label">
                <span class="x-red">*</span>选择员工
            </label>
            <div class="layui-input-inline">
                <select id="employee" name="employee" lay-filter="" lay-verify="required">
                    <option value="">请选择</option>
                    <?php foreach($user_data as $v): ?>
                        <option value="<?php echo e($v['id']); ?>"><?php echo e($v['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="job_date" class="layui-form-label">
                <span class="x-red">*</span>派工日期
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="job_date" name="job_date" required="" lay-verify="required"
                        autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="stime" class="layui-form-label">
                <span class="x-red"></span>开始时间
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="stime" name="stime" required="" lay-verify=""
                        autocomplete="off" placeholder="如: 9:30" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="etime" class="layui-form-label">
                <span class="x-red"></span>结束时间
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="etime" name="etime" required="" lay-verify=""
                        autocomplete="off" placeholder="如: 17:30" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="method" class="layui-form-label">
                <span class="x-red">*</span>出乘方式
            </label>
            <div class="layui-input-inline">
                <select id="method" name="method" lay-filter="" lay-verify="required">
                    <option value="">请选择</option>
                    <option value="公交">公交</option>
                    <option value="地铁">地铁</option>
                    <option value="出租车类">出租车类</option>
                    <option value="公司派车">公司派车</option>
                    <option value="步骑行">步骑行</option>
                    <option value="其他">其他</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label for="content" class="layui-form-label">
                <span class="x-red">*</span>报修内容
            </label>
            <div class="layui-input-block">
                <textarea placeholder="请输入内容" id="content" name="content" class="layui-textarea" lay-verify="required"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="address" class="layui-form-label">
                <span class="x-red">*</span>报修地址
            </label>
            <div class="layui-input-inline">
                <input  style="width:700px;" type="text" id="address" name="address" required="" lay-verify="required"
                        autocomplete="off" class="layui-input" onblur="searchByStationName();">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="address" class="layui-form-label"></label>
            <div class="layui-input-inline">
                <div style="margin: auto; width: 1000px; height: 600px; border: 2px solid gray; " id="container"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <label for="lng" class="layui-form-label">
                <span class="x-red"></span>经度
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="lng" name="lng" required="" lay-verify="required"
                        autocomplete="off" class="layui-input" readonly>
            </div>
            <label for="lat" class="layui-form-label">
                <span class="x-red"></span>纬度
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="lat" name="lat" required="" lay-verify="required"
                        autocomplete="off" class="layui-input" readonly>
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
    layui.use('laydate', function(){
        var laydate = layui.laydate;

        //执行一个laydate实例
        laydate.render({
            elem: '#job_date' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
            elem: '#time' //指定元素
        });
    });
</script>
<script>
    layui.use(['form','layer'], function(){
        var $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        $('[name="customer_id"]').siblings(".layui-form-select").hide();
        $('[name="customer_id"]').select2();
        $('[name="employee"]').siblings(".layui-form-select").hide();
        $('[name="employee"]').select2();

        //监听提交
        form.on('submit(add)', function(data){
            var params = data.field;
            var url = "<?php echo e(url('task/create')); ?>";
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

    // function changeStyle(obj) {
    //     obj.find('select').siblings(".layui-form-select").hide();
    //     obj.find('select').select2();
    //     obj.prop("onclick",null).off("click"); 移除点击事件
    // }
</script>

<script type="text/javascript">

    var longitude = 114.064552;

    var latitude = 22.548456;

    var map = new BMap.Map("container");

    map.setDefaultCursor("crosshair");

    map.enableScrollWheelZoom();

    var point = new BMap.Point(longitude, latitude);

    map.centerAndZoom(point, 13);

    var gc = new BMap.Geocoder();

    map.addControl(new BMap.NavigationControl());

    map.addControl(new BMap.OverviewMapControl());

    //map.addControl(new BMap.OverviewMapControl({ isOpen: true, anchor: BMAP_ANCHOR_BOTTOM_RIGHT }));   //右下角，打开

    map.addControl(new BMap.ScaleControl());

    map.addControl(new BMap.MapTypeControl());

    map.addControl(new BMap.CopyrightControl());



    var marker = new BMap.Marker(point);

    map.addOverlay(marker);

    

    marker.enableDragging();

    marker.addEventListener("dragend",

        function (e) {

            gc.getLocation(e.point,

                function (rs) {

                    showLocationInfo(e.point, rs);
                    $('#address').val(rs.address);
                    $('#lng').val(e.point.lng);
                    $('#lat').val(e.point.lat);
                });

        });

    function showLocationInfo(pt, rs) {

        var opts = {

            width: 250,

            height: 150,

            title: "当前位置"

        };

        var addComp = rs.addressComponents;

        var addr = "当前位置：" + addComp.province + ", " + addComp.city + ", " + addComp.district + ", " + addComp.street + ", " + addComp.streetNumber + "<br/>";

        addr += "纬度: " + pt.lat + ", " + "经度：" + pt.lng;




        var infoWindow = new BMap.InfoWindow(addr, opts);

        marker.openInfoWindow(infoWindow);

    }



    var localSearch = new BMap.LocalSearch(map);

    localSearch.enableAutoViewport(); //允许自动调节窗体大小



    function searchByStationName() {

        map.clearOverlays();//清空原来的标注

        var keyword = document.getElementById("address").value;

        localSearch.setSearchCompleteCallback(function (searchResult) {

            var poi = searchResult.getPoi(0);

            document.getElementById("lng").value = poi.point.lng;

            document.getElementById("lat").value = poi.point.lat;

            map.centerAndZoom(poi.point, 13);

            var marker11 = new BMap.Marker(new BMap.Point(poi.point.lng, poi.point.lat));  // 创建标注，为要查询的地方对应的经纬度

            map.addOverlay(marker11);



            marker11.addEventListener("click",

                function (e) {

                    document.getElementById("lonlat").value = e.point.lng;

                    document.getElementById("lonlat2").value = e.point.lat;

                });

            marker11.enableDragging();

            marker11.addEventListener("dragend",

                function (e) {

                    gc.getLocation(e.point,

                        function (rs) {

                            showLocationInfo(e.point, rs);

                        });

                });



        });

        localSearch.search(keyword);

    }

</script>

</body>

</html>