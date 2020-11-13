<?php echo $__env->make('common.top', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.3"></script>
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
                <span class="x-red">*</span>客户名称
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="name" name="name" required="" lay-verify="required"
                        autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="linkman" class="layui-form-label">
                <span class="x-red"></span>联系人
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="linkman" name="linkman" required="" lay-verify=""
                        autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="phone" class="layui-form-label">
                <span class="x-red">*</span>联系电话
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="phone" name="phone" required="" lay-verify="required"
                        autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="trade" class="layui-form-label">
                <span class="x-red"></span>行业
            </label>
            <div class="layui-input-inline">
                <input  type="text" id="trade" name="trade" required="" lay-verify=""
                        autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="address" class="layui-form-label">
                <span class="x-red">*</span>公司地址
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
    layui.use(['form','layer'], function(){
        var $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //监听提交
        form.on('submit(add)', function(data){
            obj = $(this);
            obj.hide();

            var params = data.field;
            var url = "<?php echo e(url('customer/create')); ?>";
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