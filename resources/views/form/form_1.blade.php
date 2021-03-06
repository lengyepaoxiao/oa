<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>打印</title>
</head>
<style type="text/css">
    #print_box{
        width: 100%;
        margin: 20px auto 0;
        text-align: center;
    }
    #print{
        display: inline-block;
        width: 100px;
        height: 40px;
        line-height: 20px;
        border-radius: 5px;
        font-weight: 500;
    }
</style>
<body>
<div id="content">
    <style type="text/css">
        *{
            padding: 0;
            margin: 0;
        }
        #content{
            margin: auto;
            width: 80%;
        }
        #title{
            text-align: center;
            height: 50px;
            line-height: 50px;
            font-size: 28px;
            font-weight: 500;
        }
        table{
            width: 100%;
        }
        #tb2{
            border-right: 1px solid black;
            border-bottom: 1px solid black;
            text-align: center;
        }
        #tb2 td{
            border-left:1px solid black;
            border-top:1px solid black;
        }
        #tb2 #ul1{
            list-style: none;
            width: 100%;
            text-align: left;
        }
        #tb2 .div-sty{
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            text-align: center;
        }
        #tb2 .div-sty > div{
            flex: 1;
        }
        table tr{
            height: 40px;
            line-height: 40px;
        }
    </style>
    <div id="title">
        派 工 单
    </div>
    <table id="tb1" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width='35%'>派工时间：&nbsp;&nbsp;{{$data['job_date']}}</td>
            <td width='20%'></td>
            <td width='38%' align="right">编号：</td>
            <td width='12%'>    {{$order_no}}</td>
        </tr>
    </table>
    <table id="tb2" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width='20%'>客户名称</td>
            <td colspan="2" width='30%'>{{$data['name']}}</td>
            <td width='20%'>服务工程师</td>
            <td width='30%'>{{$data['job_no']}}</td>
        </tr>
        <tr>
            <td>联系地址</td>
            <td colspan="2">{{$data['address']}}</td>
            <td>开始时间-结束时间</td>
            <td>{{$data['stime']}} - {{$data['etime']}}</td>
        </tr>
        <tr>
            <td>联系电话</td>
            <td colspan="2">{{$data['phone']}}</td>
            <td>前往方式</td>
            <td>{{$data['method']}}</td>
        </tr>
        <tr>
            <td height='90px;'>服务内容</td>
            <td colspan="4" align="left">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$data['content']}}</p>
            </td>
        </tr>
        <tr>
            <td rowspan="5">客户意见及确认</td>
            <td>服务态度</td>
            <td colspan="3">
                <div class="div-sty">
                    <div>（&nbsp;&nbsp;&nbsp;&nbsp;）好</div>
                    <div>（&nbsp;&nbsp;&nbsp;&nbsp;）一般</div>
                    <div>（&nbsp;&nbsp;&nbsp;&nbsp;）差</div>
                </div>
            </td>
        </tr>
        <tr>
            <td>工作质量</td>
            <td colspan="3">
                <div class="div-sty">
                    <div>（&nbsp;&nbsp;&nbsp;&nbsp;）好</div>
                    <div>（&nbsp;&nbsp;&nbsp;&nbsp;）一般</div>
                    <div>（&nbsp;&nbsp;&nbsp;&nbsp;）差</div>
                </div>
            </td>
        </tr>
        <tr>
            <td rowspan="3" colspan="4" align="right">
                <div style="height:40px; padding-right: 10%; overflow: hidden;">
                    <div style="float: right; border-bottom: 2px solid black; width: 20%; height: 30px;"></div>
                    <div style="float: right;">客户确认：</div>
                </div>
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
    </table>
</div>
<div id="print_box">
    <input type="button" id="print" value="打印"/>
</div>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    window.onload = function()
    {
        var btnPrint = document.getElementById("print");//“打印”按钮
        btnPrint.onclick = function()//为“打印”按钮添加点击事件
        {
            printTable();//打印表格
            let url = "{{url('task/create_form')}}";
            let params = {
                '_token': "{{csrf_token()}}",
                'num': "{{$num}}",
                'curDayZeroTs': "{{$curDayZeroTs}}"
            }
            $.post(url, params, function(){},'json')
        }
    }

    //打印表格
    function printTable()
    {
        var tableToPrint = document.getElementById('content');//将要被打印的表格
        var newWin= window.open("");//新打开一个空窗口
        newWin.document.write(tableToPrint.outerHTML);//将表格添加进新的窗口
        newWin.document.close();//在IE浏览器中使用必须添加这一句
        newWin.focus();//在IE浏览器中使用必须添加这一句

        newWin.print();//打印
        newWin.close();//关闭窗口
    }
</script>
</body>
</html>