layui.use(['laydate','layer','form','jquery'], function() {
    var layer = layui.layer;
    var laydate = layui.laydate;
     var form = layui.form();
    var $ = layui.jquery;

    isshow_div(1);
    load_data();

    function load_data(){


        //设置框度            
        $('.cash .box1').attr("style","height:" + getBodyHeigh(90) + "px;");
        $('.cash .box2').attr("style","height:" + getBodyHeigh(90) + "px;");
        $('.product-lists').attr("style","height:" + getBodyHeigh(130) + "px;");

        //设置自动刷新
        setInterval("set_mtime()",3000);

        //加载数据
        refresh_table();

        $(window).bind("wheel",function(event){
            //event.preventDefault();
           // return false;
        });
    }

    //刷新桌台
    function refresh_table(){
        var data = {};
        load_data_Lists("/cash/getTableLists",data,0,"tpl-lists","view1");
    }

    //刷新菜单列表
    function refresh_product_menu(table_id){
        get_order_lists(table_id)
    }

     //3分钟后自动刷新
    set_mtime = function  (){
        var m = parseInt($("#refresh").text()) - 1;
        if(m == 0){
            refresh_table();
            $("#refresh").text(180);
        }else if (m > 0) {
            $("#refresh").text(m);
        }
    }

    //获取餐台菜单
    get_order_lists = function (table_id){
        var data = {"table_id":table_id};
        load_data_Lists("/cash/getOrderLists",data,0,"order-lists","view2");
    }

    function istable_empty(){
        var table_name = $('').val();
        if(table_name == '---'){
            layer.msg('请选择餐台!', {icon: 0});
            return false;
        }
        return true;
    }
    
    //获取菜品列表
    get_product_lists = function (data){
        load_data_Lists("/cash/getProductLists",data,0,"product-lists","view3");
    }

    //返回桌台页
    return_table = function () {
        refresh_table();
        isshow_div(1);
    }

    //选菜
    select_cai = function (tableid,orderid){
        $('#tableid').val(tableid);
        $('#orderid').val(orderid);
        get_product_lists({});
        isshow_div(0);
    }

    //分类菜品
    select_cate_cai = function(cid){
        get_product_lists({"cid":cid});
    }

    //添加菜
    add_cai = function(product_id){
        var table_id = $('#tableid').val();
        var orderid = $('#orderid').val();

        //新加的菜加入订单中
        $.post("/cash/addCai", {"tableid":table_id,"product_id":product_id,"orderid":orderid}, function (ret) {
            layer.msg(ret.msg,{icon: ret.status});
            if (ret.status == 1) {
                $('#orderid').val(ret.data);
                refresh_product_menu(table_id);
            }
        },'json');
        return;
    }

    //返回餐台
    return_table = function(){
        isshow_div(1);
        var data = {};
        load_data_Lists("/cash/getTableLists",data,0,"tpl-lists","view1");
    }

    //打印
    print_order = function (tableid,orderid){
        var $printFrom = $('#rprint');

        // //获取数字
        // $.post("/cash/printOrder", {"orderid":orderid}, function (ret) {
        //     layer.msg(ret.msg,{icon: ret.status});
        //     if (ret.status == 1) {
        //         //刷新
        //         refresh_product_menu(tableid);
        //         refresh_table();
        //     }
        // },'json');
        var post_data =  {"orderid":orderid,"tableid":tableid};
        load_data_Lists("/cash/printOrder",post_data,0,"rprint-lists","rprint");

        var winform = layer.open({
            type: 1,
            title: '打印菜单',
            area: ['360px', '620px'],
            content: $printFrom,
            skin: 'cash-btn',
            btn: ['确认', '关闭'],
            yes: function () {
                $("#rprint").jqprint();
                layer.close();
            },
            btn2: function(){
                layer.close();
            }
        });
        return;
        //更换
        // $.post("/cash/printOrder", {"orderid":orderid}, function (ret) {
        //     layer.msg(ret.msg,{icon: ret.status});
        //     if (ret.status == 1) {
        //         //刷新
        //         refresh_product_menu(tableid);
        //         refresh_table();
        //     }
        // },'json');
        // return;
    }

    //结帐
    pay_order = function (tableid,orderid){
        var order_price = parseFloat($("#order-price-" + orderid).text());
        var $payFrom = $('#pay'), $inpuForm = $('#pay_form');
        $inpuForm[0].reset();//重置表单

        $("#pay-price").text(toDecimal2(order_price));
        $("#pay-total-price").text(toDecimal2(order_price));
        $("#add_fee").focus();
        $("#add_fee").select();

        //事件触发
        $("#add_fee").bind("blur",function(){
            var add_fee = parseFloat($(this).val());
            var yh_fee = parseFloat($("#yh_fee").val());
            var pay_total_price = order_price + add_fee - yh_fee;
            $("#pay-total-price").text(toDecimal2(pay_total_price));
        });

        $("#yh_fee").bind("blur",function(){
            var yh_fee = parseFloat($(this).val());
            var add_fee = parseFloat($("#add_fee").val());
            var pay_total_price = order_price + add_fee - yh_fee;
            if(pay_total_price < 0){
                $(this).focus();
                $(this).select();
                layer.msg("实际应金额不能小于0",{icon: 0});
                return false;
            }
            $("#pay-total-price").text(toDecimal2(pay_total_price));
        });

        var winform = layer.open({
            type: 1,
            title: '消费结帐',
            area: ['360px', 'auto'],
            content: $payFrom,
            skin: 'cash-btn',
            btn: ['确认', '取消'],
            yes: function () {
                var postData=  $inpuForm.serialize() + "&orderid=" + orderid;
                $.post("/cash/payorder", postData, function (ret) {
                    layer.msg(ret.msg,{icon: ret.status});
                    if (ret.status == 1) {
                        //刷新
                        refresh_product_menu(tableid);
                        refresh_table();
                    }
                    layer.close(winform);
                },'json');
                return;
            },
            btn2: function(){
                layer.close();
            }
        });
    }

    //关闭
    close_table = function (tableid,orderid){

        layer.msg('确定清餐台吗', {
                icon: 0,
                time: 10000, //10s后自动关闭
                skin: 'cash-btn',
                btn: ['确认', '关闭'],
                yes: function(){
                
                    $.post("/cash/updateOrderStatus", {"orderid":orderid}, function (ret) {
                        layer.msg(ret.msg,{icon: ret.status});
                        if (ret.status == 1) {
                            //刷新
                            refresh_product_menu(tableid);
                            refresh_table();
                        }
                    },'json');
                     return;
                },
                btn2: function(){
                    layer.close();
                }
            });
    }

    //换台
    chanage_table = function (orderid) {

        var $changeTableFrom = $('#chanage_table'), $inpuForm = $('#change_table_form');
        $inpuForm[0].reset();//重置表单

        var winform = layer.open({
            type: 1,
            title: '请选择桌台',
            area: ['360px', 'auto'],
            content: $changeTableFrom,
            skin: 'cash-btn',
            btn: ['确认', '取消'],
            success: function(layero, index){
                //初始化餐台列表
                var data = {};
                $.post("/cash/getEmptyTableLists", data,function (ret) {
                    var opt = '<option value="0">请选择</option>';
                    if (ret.status == 1) {
                        var data = ret.data;
                        for (i in data) {
                            opt += '<option value="' + data[i]['id'] + '">' + data[i]['name'] + '</option>';
                            
                        }
                    }
                    $inpuForm.find('select[name="tableid"]').empty().append(opt);
                    form.render('select');
                },'json');

            },
            yes: function () {
                var tableid = $inpuForm.find('select[name="tableid"]').val();
                tableid = parseInt(tableid);  
                if(tableid == 0){
                    layer.msg('请选择更换的餐台!', {icon: 0});
                    return false;
                }

                //更换
                $.post("/cash/chanageTable", {"tableid":tableid,"orderid":orderid}, function (ret) {
                    layer.msg(ret.msg,{icon: ret.status});
                    layer.close(winform);
                    if (ret.status == 1) {
                        //刷新
                        refresh_product_menu(tableid);
                        refresh_table();
                    }
                     return;
                },'json');

            }
        });
    }

    //删除
    delete_product_record = function (obj,tableid) {

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
                            refresh_product_menu(tableid); //刷新
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

    //加载数据
    function load_data_Lists(url, data, k,tplid,insertid) {
       
        $.post(url, data, function (ret) {
            //失败提示
            if (ret.status == 0) {
                layer.msg(ret.msg,{icon: 0});
                return;
            }

            //处理模板
            layui.use(['laytpl', 'laypage'], function () {
                var laytpl = layui.laytpl;
                laytpl.config({
                    open: '@{@'
                    , close: '@}@'
                });
                var getTpl = document.getElementById(tplid).innerHTML;
                laytpl(getTpl).render(ret, function (html) {
                    document.getElementById(insertid).innerHTML = html;
                });

                if (k == 0) {
                    //显示分页
                    var laypage = layui.laypage;
                    laypage({
                        cont: 'pagesize',
                        pages: Math.ceil(ret.total / 20),
                        groups: 5,
                        jump: function (obj, first) {
                            var curr = obj.curr;
                            data.page = curr;
                            if (k > 0) {
                                load_data_Lists(url, data, 1,tplid,insertid,isupdate)
                            }
                            k = 1;
                        }
                    });
                }
            });

        }, 'json');
    }
    

     function isshow_div (isnot){
        if(isnot == 1){     //显示餐台
            $("#show-product").hide();
            $("#show-table").show();
        }else{  //显示菜谱
            $("#show-table").hide();
            $("#show-product").show();
        }
    }

});


 
    
        