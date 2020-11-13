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
            <td width='35%'>派工时间：&nbsp;&nbsp;<?php echo e($data['job_date']); ?></td>
            <td width='20%'></td>
            <td width='38%' align="right">编号：</td>
            <td width='12%'>    <?php echo e($order_no); ?></td>
        </tr>
    </table>
    <table id="tb2" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width='15%'>客户名称</td>
            <td width='20%'><?php echo e($data['name']); ?></td>
            <td width='20%'></td>
            <td width='20%'>服务工程师</td>
            <td width='25%'><?php echo e($data['job_no']); ?></td>
        </tr>
        <tr>
            <td>保修地址</td>
            <td><?php echo e($data['address']); ?></td>
            <td></td>
            <td>开始时间-结束时间</td>
            <td><?php echo e($data['stime']); ?> - <?php echo e($data['etime']); ?></td>
        </tr>
        <tr>
            <td>联系电话</td>
            <td><?php echo e($data['phone']); ?></td>
            <td></td>
            <td>前往方式</td>
            <td><?php echo e($data['method']); ?></td>
        </tr>
        <tr>
            <td height='90px;'>报修内容</td>
            <td colspan="4" align="left">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo e($data['content']); ?></p>
            </td>
        </tr>
        <tr>
            <td rowspan="5">故障解决情况</td>
            <td colspan="4" align="left">①（&nbsp;&nbsp;&nbsp;&nbsp;）已解决&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;（&nbsp;&nbsp;&nbsp;&nbsp;）待解决</td>
        </tr>
        <tr>
            <td rowspan="4" colspan="4">
                <ul id="ul1">
                    <li>②（&nbsp;&nbsp;&nbsp;&nbsp;）设备是否需带回</li>
                    <li style="overflow: hidden; height: 40px;">
                        <div style="float: left;">设备信息：</div>
                        <div style="float: left; border-bottom: 1px solid black; width: 80%; height: 30px;"></div>
                    </li>
                    <li style="overflow: hidden; height: 40px;">
                        <div style="float: left;">预计返回时间：</div>
                        <div style="float: left; border-bottom: 1px solid black; width: 78%; height: 30px;"></div>
                    </li>
                </ul>
            </td>
        </tr>
        <tr></tr>
        <tr></tr>
        <tr></tr>
        <tr>
            <td rowspan="6">维修费用</td>
            <td>材料费（元）</td>
            <td>人工费（元）</td>
            <td>其他（元）</td>
            <td>共计（元）</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
<script>
    window.onload = function()
    {
        var btnPrint = document.getElementById("print");//“打印”按钮
        btnPrint.onclick = function()//为“打印”按钮添加点击事件
        {
            printTable();//打印表格
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