<?php echo $__env->make('common.top', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<body>
<!-- 顶部开始 -->
<div class="container">
    <div class="logo"><a href="<?php echo e(url('index')); ?>">派单系统管理平台</a></div>
    <div class="left_open">
        <i title="展开左侧栏" class="iconfont">&#xe699;</i>
    </div>
    <?php /*<ul class="layui-nav left fast-add" lay-filter="">*/ ?>
        <?php /*<li class="layui-nav-item">*/ ?>
            <?php /*<a href="javascript:;">+新增</a>*/ ?>
            <?php /*<dl class="layui-nav-child"> <!-- 二级菜单 -->*/ ?>
                <?php /*<dd><a onclick="x_admin_show('资讯','http://www.baidu.com')"><i class="iconfont">&#xe6a2;</i>资讯</a></dd>*/ ?>
                <?php /*<dd><a onclick="x_admin_show('图片','http://www.baidu.com')"><i class="iconfont">&#xe6a8;</i>图片</a></dd>*/ ?>
                <?php /*<dd><a onclick="x_admin_show('用户','http://www.baidu.com')"><i class="iconfont">&#xe6b8;</i>用户</a></dd>*/ ?>
            <?php /*</dl>*/ ?>
        <?php /*</li>*/ ?>
    <?php /*</ul>*/ ?>
    <ul class="layui-nav right" lay-filter="">
        <li class="layui-nav-item">
            <a href="javascript:;"><?php echo e($username); ?></a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
                <?php if($username == 'admin'): ?>
                <dd style="cursor: pointer;"><a onclick="x_admin_show('用户管理','<?php echo e(url("admin/lists")); ?>')">用户管理</a></dd>
                <?php endif; ?>
                <dd><a href="<?php echo e(url('login/quit')); ?>">退出</a></dd>
            </dl>
        </li>
    </ul>

</div>
<!-- 顶部结束 -->
<!-- 中部开始 -->
<!-- 左侧菜单开始 -->
<div class="left-nav">
    <div id="side-nav">
        <ul id="nav">
            <?php if($uid == 1): ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe725;</i>
                    <cite>客户管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo e(url('customer/lists')); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>客户列表</cite>
                        </a>
                    </li >
                </ul>
            </li>
            <?php endif; ?>
            <?php if($uid == 1): ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe726;</i>
                    <cite>员工管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo e(url('user/lists')); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>员工列表</cite>
                        </a>
                    </li >
                </ul>
            </li>
            <?php endif; ?>
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe730;</i>
                    <cite>任务管理</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="<?php echo e(url('task/lists')); ?>">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>任务列表</cite>
                        </a>
                    </li >
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- <div class="x-slide_left"></div> -->
<!-- 左侧菜单结束 -->
<!-- 右侧主体开始 -->
<div class="page-content">
    <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
        <ul class="layui-tab-title">
            <li>我的桌面</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src="welcome" frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
        </div>
    </div>
</div>
<div class="page-content-bg"></div>
<!-- 右侧主体结束 -->
<!-- 中部结束 -->
<!-- 底部开始 -->
<div class="footer"></div>
</body>
</html>