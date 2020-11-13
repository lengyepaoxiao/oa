/**
 * Created by tony.feng on 2016/12/14.
 */

layui.use('jquery', function() {
    var $ = layui.jquery;

    //获取交易数据
    getTradeCount = function (url, data) {
        $.post(url, data, function (ret) {
            //失败提示
            if (ret.status == 0) {
                layer.msg(ret.msg,{icon: 0});
                return;
            }
            $(".trade-count > .box1").text("交易笔数 " + ret.data.quantity);
            $(".trade-count > .box2").text("交易金额 " + (ret.data.total_fee) + " 元");
        }, 'json');
    }

    getDataLists = function (url, data, k) {
        $.post(url, data, function (ret) {
            //失败提示
            if (ret.status == 0) {
                layer.msg(ret.msg,{icon: 0});
                return;
            }

            $('#total_num').html(ret.total);
            public_data = ret.data;

            //处理模板
            layui.use(['laytpl','laypage'], function () {
                var laytpl = layui.laytpl;
                var laypage = layui.laypage;
                laytpl.config({
                    open: '@{@'
                    , close: '@}@'
                });
                var getTpl = document.getElementById("tpl-lists").innerHTML;
                laytpl(getTpl).render(ret, function (html) {
                    view.innerHTML = html;
                });
            // 分页处理
            if (k == 0) {
                laypage.render({
                    elem: 'pagesize', //注意，这里的 test1 是 ID，不用加 # 号
                    count: ret.total, //数据总数，从服务端得到
                    limit: ret.pagesize,
                    first: '首页',
                    last:  '尾页',
                    groups: 5,
                    jump: function (obj, first) {
                        var curr = obj.curr;
                        data.page = curr;
                        if (k > 0) {
                            getDataLists(url, data, 1)
                        }
                        k = 1;
                    }
                });
            }
        });

        }, 'json');
        return
    }
    
    

    get_pic_option_one = function (ret, type) {

        //统计类型
        if (type == 1) { //交易笔数
            var title = ['交易笔数'];
            var json_data = [
                {
                    name: '交易笔数',
                    type: 'line',
                    stack: '总量',
                    data: ret.data.y_count
                }
            ]
        } else if (type == 2) {//交易金额
            var title = ['交易金额'];
            var json_data = [
                {
                    name: '交易金额',
                    type: 'line',
                    stack: '总量',
                    data: ret.data.y_fee
                },
            ]
        } else {
            var title = ['交易笔数', '交易金额'];
            var json_data = [
                {
                    name: '交易笔数',
                    type: 'line',
                    stack: '总量',
                    data: ret.data.y_count
                },
                {
                    name: '交易金额',
                    type: 'line',
                    stack: '总量',
                    data: ret.data.y_fee
                },
            ]
        }

        var option = {
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: title
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: ret.data.x_time
            },
            yAxis: {
                type: 'value'
            },
            series: json_data
        };

        return option;
    }

    get_pic_option_two = function (ret, type) {

        //统计类型
        var json_data = ret.data.x_time;
        if (type == 1) { //交易笔数
            var title = "交易笔数";
            var series_json = ret.data.y_count;
        } else if (type == 2) {//交易金额
            var title = "交易金额";
            var series_json = ret.data.y_fee;
        }

        var option = {
            color: ['#3398DB'],
            tooltip: {
                trigger: 'axis',
                axisPointer: {            // 坐标轴指示器，坐标轴触发有效
                    type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                }
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            xAxis: [
                {
                    type: 'category',
                    data: json_data,
                    axisTick: {
                        alignWithLabel: true
                    }
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: title,
                    type: 'bar',
                    barWidth: '60%',
                    data: series_json
                }
            ]
        };

        return option;
    }

    get_pic_option_three = function (ret, type) {

        //统计类型
        var json_data = ret.data.x_time;
        if (type == 1) { //交易笔数
            var title = "交易笔数";
            var series_json = ret.data.y_count;
        } else if (type == 2) {//交易金额
            var title = "交易金额";
            var series_json = ret.data.y_fee;
        }
        var format_data = [];
        var len = series_json.length;
        for (i = 0; i < len; i++) {
            var val = {value: series_json[i], name: json_data[i]};
            format_data[i] = val;
        }

        var option = {
            title: {
                text: title,
                x: 'center'
            },
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                x: 'center',
                y: 'bottom',
                data: json_data
            },
            toolbox: {
                show: true,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {
                        show: true,
                        type: ['pie', 'funnel']
                    },
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            calculable: true,
            series: [
                {
                    name: '半径模式',
                    type: 'pie',
                    radius: [20, 110],
                    center: ['25%', '50%'],
                    roseType: 'radius',
                    label: {
                        normal: {
                            show: false
                        },
                        emphasis: {
                            show: true
                        }
                    },
                    lableLine: {
                        normal: {
                            show: false
                        },
                        emphasis: {
                            show: true
                        }
                    },
                    data: format_data
                },
                {
                    name: '面积模式',
                    type: 'pie',
                    radius: [30, 110],
                    center: ['75%', '50%'],
                    roseType: 'area',
                    data: format_data
                }
            ]
        };
        return option;
    }

    getTradeEchart = function (url, data, type) {

        $.post(url, data, function (ret) {
            //失败提示
            if (ret.status == 0) {
                layer.msg(ret.msg,{icon: 0});
                return;
            }

            //图形类型
            var option = [];
            var pictype = data.type2;
            if (pictype == 2) {
                option = get_pic_option_two(ret, type);
            } else if (pictype == 3) {
                option = get_pic_option_three(ret, type);
            } else {
                option = get_pic_option_one(ret, type);
            }
            //初始化
            var chart_obj = echarts.init(document.getElementById('echart'));
            chart_obj.setOption(option);

        }, 'json');
    }

    //验证表单
    load_verify_form = function () {

        layui.use(['form'], function() {
            var form = layui.form();
            var $ = layui.jquery;

            form.verify({
                loginname: function (value) {
                    var reg = /^1\d{10}$/;
                    if (value == "" || !reg.test(value)) {
                        return "请输入手机号码";
                    }
                },
                password: function (value) {
                    if (value == "") {
                        return "请输入密码";
                    }
                },
                captcha: function (value) {
                    if (value == "") {
                        return "请输入验证码";
                    }
                },
                old_password: function (value) {
                    if (value == "") {
                        return "请输入原始密码";
                    }
                },
                new_password: function (value) {
                    if (value == "") {
                        return "请设置密码";
                    }

                    var reg = /^[\S]{6,12}$/;
                    if(!reg.test(value)){
                        return "密码必须6到12位，且不能出现空格";
                    }
                },
                ok_password: function (value) {
                    if (value == "") {
                        return "请确认密码";
                    }
                    var new_password = $("#password").val();
                    if(new_password != value){
                        return "确认密码不正确";
                    }

                },
                total_fee: function(value){
                    if(value == ""){
                        return '金额项不能为空';
                    }
                    var reg = /(^[1-9]([0-9]+)?(\.[0-9]{1,2})?$)|(^(0){1}$)|(^[0-9]\.[0-9]([0-9])?$)/;
                    if(!reg.test(value)){
                        return '输入金额格式不正确';
                    }
                }

            });
        });
    }

    //发送验证码
    send_sms = function(){

        //不为空等待
        if($("#smscode").children("font").text() != ""){
            return false;
        }

        var loginname = $("#loginname").val();
        var url = $("#smscode").attr("url");
        var _token = "{{csrf_token()}}";

        if(loginname == ""){
            layer.msg("请输入手机号码",{icon: 0});
            return false;
        }
        $.post(url, {"loginname":loginname,"_token":_token},function(ret) {
            layer.msg(ret.msg,{icon: ret.status});
            if(ret.status == 1){
                $("#smscode").children("font").text(120) ;
                var t = setInterval("mtime()",1000);
                var m = parseInt($("#smscode").children("font").text());
                if(m == 1){
                    window.clearInterval(t);
                }
            }
        },'json');
        return false;
    }

    mtime = function (){
        var m = parseInt($("#smscode").children("font").text()) - 1;
        if(m == 0){
            $("#smscode").children("font").text("") ;
        }else if (m > 0) {
            $("#smscode").children("font").text(m);
        }
    }

    load_upload = function (url) {

        layui.upload({
            url: url //上传接口
            , before: function (input) {
                $(input).parent().parent().next().html("正在上传中...")
            }
            , success: function (res, input) { //上传成功后的回调
                var imghtml = "<img src=\"http://image.yftechnet.com/appimg/" + res.imgstr + "\" style=\"width:100px;height:auto;marign-top:10px;\"/>";
                var obj = $(input).parent().parent();
                obj.prev().attr('value', res.data);
                obj.next().html(imghtml);
            }
        }); //
    }

    load_city = function (url) {
        layui.use(['form','jquery'], function() {
            var form = layui.form();
            form.on('select(province)', function (data) {
                $.get(url+ '/' + data.value, function (ret) {
                    if (ret.status == 1) {
                        var citylists = ret.data;
                        var city_html = '';
                        for (var i = 0; i < citylists.length; i++) {
                            city_html += '<option value="' + citylists[i].id + '">' + citylists[i].name + '</option>';
                        }
                        $("form").find('select[name=cityid]').html(city_html);
                        form.render();
                    }
                }, 'json');
            });
        });
    }

    load_subcate = function (url) {
        layui.use(['form','jquery'], function() {
            var form = layui.form();
            form.on('select(catepid)', function (data) {
                $.get(url + '/'+ data.value, function (ret) {
                    if (ret.status == 1) {
                        var citylists = ret.data;
                        var city_html = '';
                        for (var i = 0; i < citylists.length; i++) {
                            city_html += '<option value="' + citylists[i].id + '">' + citylists[i].name + '</option>';
                        }
                        $("form").find('select[name=categoryid]').html(city_html);
                        form.render();
                    }
                }, 'json');
            });
        });
    }

    //删除
    delete_record = function (obj) {

        layui.use('layer', function(){
            var layer = layui.layer;
            layer.msg('确定要删除吗', {
                icon: 0,
                time: 10000, //10s后自动关闭
                btn: ['确认', '关闭'],
                yes: function(){
                    var url = $(obj).attr("action");
                    $.get(url, function (ret) {
                        layer.msg(ret.msg,{icon: ret.status});
                        if (ret.status == 1) {
                            $(obj).parent().parent().remove();
                        }
                    },'json');
                    return;
                },
                btn2: function(){
                    layer.close();
                }
            });
        });
    }

    click_link = function (url){
        location.href = url;
    }


    load_flow_data = function (url,params,viewid,tplid){

        layui.use(['flow','laytpl','jquery'], function() {
            var $ = layui.jquery;
            var flow = layui.flow;
            var laytpl = layui.laytpl;
            laytpl.config({
                open: '@{@'
                , close: '@}@'
            });

            flow.load({
                elem: viewid //流加载容器
                , isAuto: true
                , done: function (page, next) { //执行下一页的回调
                    $.post(url, params,function (ret) {
                        if (ret.status == 0) {
                            layer.msg(ret.msg, {icon: 0});
                            return;
                        }

                        //初始化选中的菜数量
                        var data_num = $("#data_num").val();
                        if(data_num){
                            data_num = eval("("+data_num+")");
                        }
                        for(var i = 0; i < ret.data.length;i++){
                            ret.data[i]['num'] = 0;
                            if(data_num){
                                var n = data_num[ret.data[i]['id']];
                                if(n != undefined){
                                    ret.data[i]['num'] = n;
                                }
                            }
                        }

                        //模板输出
                        var getTpl = document.getElementById(tplid).innerHTML;
                        laytpl(getTpl).render(ret, function (html) {
                            var pages = 0;
                            if(page == 1 && ret.total == 0){
                                $('.nodata').show();
                            }else{
                                $('.nodata').hide();
                                pages = Math.ceil(ret.total / 20);
                            }
                            next(html, page < pages); //假设总页数为 10
                        });
                    }, 'json');
                }
            });
        });
    }

    load_cate_event = function (url){
        $(".zhezhao").show();
        $(".category a").each(function (i) {
            var cid = $(this).attr("cid");
            $(this).on('click', function () {
                $(".where a").each(function (i) {
                    $(this).removeClass("select");
                    if(i == 0){
                        $(this).addClass("select");
                    }
                });
                $(".category a").each(function (i) {
                    $(this).removeClass("select");
                });
                $(this).addClass("select");

                //加载分类
                //var url = '{{url("product/lists")}}';
                var params = {};
                if (cid > 0) {
                    params = {"cid": cid};
                }
                $('#view').html(""); //切换时清除
                is_show(0);
                load_flow_data(url, params, '#view', "tpl-dclists");
            });
        });

    }


    is_show = function(s){
        if(s == 1){
            $('.category').show();
            $('.opico').attr('style','right:122px;');
            $('.opico a').attr('onclick','is_show(0);');
            $('.opico a').text('隐藏');
            $(".zhezhao").show();
        }else{
            $('.category').hide();
            $('.opico').attr('style','right:0;');
            $('.opico a').attr('onclick','is_show(1);');
            $('.opico a').text('菜类');
            $(".zhezhao").hide();
        }
    }

    load_orderby_select = function(url) {
        $(".where a").each(function (i) {
            var orderby = $(this).attr("orderby");
            $(this).on('click', function () {
                $(".where a").each(function (i) {
                    $(this).removeClass("select");
                });
                $(this).addClass("select");

                var params = {};
                if (orderby > 0) {
                    var cid = 0;
                    $(".cate .lists a").each(function (i) {
                        if ($(this).attr('class')) {
                            cid = $(this).attr('cid');
                        }
                    });
                    params = {"cid": cid, "orderby": orderby};
                }
                $('#view').html(""); //切换时清除
                load_flow_data(url, params, '#ftpl-lists', "tpl-dclists");
            });
        });
    }


    load_buy_event = function (url){
        layui.use(['layer','jquery'], function(){
            var layer = layui.layer;
            var $ = layui.jquery;
            $(".buy_clik").click(function(){
                var index = layer.open({
                    type: 2,
                    title:'',
                    shade: [0.5, '#000'],
                    shadeClose: true,
                    closeBtn:0,
                    offset: 'b',
                    area: ['100%', '100%'],
                    content: url
                });
            });
        });
    }

    buyop = function (obj,url,product_id,action) {
        if(action == 2){  //减
            var o = $(obj).next();
            var n = parseInt(o.text());
            if(n > 0){
                o.text(n - 1);
            }
        }else{  //加
            var o = $(obj).prev();
            var n = parseInt(o.text());
            o.text(n + 1);
        }
        var params = {"product_id":product_id,"action":action};
        $.post(url, params,function (ret) {
            if (ret.status == 0) {
                layer.msg(ret.msg, {icon: 0});
                return;
            }
            $(".total").text(ret.data.total);
            $(".total_fee").text(toDecimal2(ret.data.total_fee));
        }, 'json');
    }

    load_flow_order_data = function (url,params,viewid,tplid){

        layui.use(['flow','laytpl','jquery'], function() {
            var $ = layui.jquery;
            var flow = layui.flow;
            var laytpl = layui.laytpl;
            laytpl.config({
                open: '@{@'
                , close: '@}@'
            });
            
            flow.load({
                elem: viewid //流加载容器
                , isAuto: true
                , done: function (page, next) { //执行下一页的回调
                    $.post(url, params,function (ret) {
                        if (ret.status == 0) {
                            layer.msg(ret.msg, {icon: 0});
                            return;
                        }

                        //模板输出
                        var getTpl = document.getElementById(tplid).innerHTML;
                        laytpl(getTpl).render(ret, function (html) {
                            var pages = Math.ceil(ret.total / 20);
                            next(html, page < pages); //假设总页数为 10
                        });
                    }, 'json');
                }
            });
        });
    }

});

function toDecimal2(x) {
    var f = parseFloat(x);
    if (isNaN(f)) {
        return false;
    }
    var f = Math.round(x*100)/100;
    var s = f.toString();
    var rs = s.indexOf('.');
    if (rs < 0) {
        rs = s.length;
        s += '.';
    }
    while (s.length <= rs + 2) {
        s += '0';
    }
    return s;
}

function getBodyHeigh(offset){
    var winHeight = 0;
    if (window.innerHeight){
        winHeight = window.innerHeight;
    }else if ((document.body) && (document.body.clientHeight)){
        winHeight = document.body.clientHeight;
    }
    winHeight = winHeight - offset;
    if( winHeight < 650){
        winHeight = 650;
    }
    return winHeight;
}
