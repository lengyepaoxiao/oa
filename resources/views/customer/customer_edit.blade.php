@include('common.top')
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
            var url = "{{url('customer/updates')}}";
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
        var url = "{{url('customer/get_edit')}}";
        var params = {'_token': '{{csrf_token()}}',
            'id': id,
        };
        $.post(url,params,function(ret){
            if(ret.status == 1){
                var data = ret.data;

                //基本数据渲染
                $('#name').val(data.name);
                $('#linkman').val(data.linkman);
                $('#phone').val(data.phone);
                $('#trade').val(data.trade);
                $('#address').val(data.address);
                $('#lng').val(data.lng);
                $('#lat').val(data.lat);

                load_map(data.lng, data.lat);
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

<script type="text/javascript">

    function load_map(lng, lat) {
        var longitude = lng;

        var latitude = lat;

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
    }

</script>
</body>

</html>