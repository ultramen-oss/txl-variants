<?php /*a:1:{s:59:"/www/wwwroot/154.64.230.166/app/admin/view/login/index.html";i:1749557468;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>通讯录后台</title>
        <link rel="stylesheet" href="/static/css/login.css">
        <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css">
    </head>
<body>
    <div class="container">
        <div class="drop">
            <div class="content">
                <h2 class="company-title">通讯后台</h2>
                <form method="post" id="login-form">
                    <div class="inputBox">
                        <input type="text" id="username" name="name" required placeholder="请输入账号" <?php if(!(empty($usermember) || (($usermember instanceof \think\Collection || $usermember instanceof \think\Paginator ) && $usermember->isEmpty()))): ?>value="<?php echo htmlentities((string) $usermember); ?>"<?php endif; ?>>
                    </div>
                    <div class="inputBox">
                        <input type="password" id="password" name="password" required placeholder="请输入密码">
                    </div>
                    <div class="captchaBox">
                        <input type="text" id="captcha-input" name="captcha" required placeholder="验证码">
                        <img src="<?php echo captcha_src(); ?>" alt="captcha" onclick="this.src='<?php echo captcha_src(); ?>?seed='+Math.random()" id="captcha">
                    </div>
                    <div class="inputBox" id="login-btn-container">
                        <input type="submit" value="登录">
                    </div>
                    <input type="hidden" name="__token__" value="<?php echo token(); ?>" />
                </form>
            </div>


    <!-- 弹窗容器 -->
    <div class="toast-container" id="toast-container"></div>

    <!-- 状态通知弹窗 -->
    <div class="status-toast" id="statusToast">
        <div class="status-toast-icon">
            <i class="fa fa-check-circle"></i>
        </div>
        <div class="status-toast-content">
            <span class="status-toast-title">操作成功</span>
            <span class="status-toast-message">操作已成功完成</span>
        </div>
        <div class="status-toast-progress">
            <div class="status-toast-progress-bar"></div>
        </div>
    </div>

    <script src="/static/public/jquery/jquery.min.js"></script>
    <script src="/static/layui/layui.js"></script>
    <script>
        // 显示自定义弹窗
        function showToast(message, type = 'info', duration = 3000) {
            var $toast = $('#statusToast');
            var icon = 'fa-check-circle';
            var background = 'linear-gradient(145deg, #4CAF50, #2E7D32)';
            var title = '操作成功';
            
            // 设置图标和背景色
            if (type === 'error') {
                icon = 'fa-times-circle';
                background = 'linear-gradient(145deg, #f44336, #d32f2f)';
                title = '操作失败';
                $toast.addClass('error').removeClass('warning');
            } else if (type === 'warning' || type === 'info') {
                icon = 'fa-exclamation-triangle';
                background = 'linear-gradient(145deg, #ff9800, #ed6c02)';
                title = type === 'warning' ? '警告' : '提示';
                $toast.addClass('warning').removeClass('error');
            } else {
                $toast.removeClass('error warning');
            }
            
            // 设置图标
            $toast.find('.status-toast-icon i').attr('class', 'fa ' + icon);
            
            // 设置标题和消息
            $toast.find('.status-toast-title').text(title);
            $toast.find('.status-toast-message').text(message);
            
            // 设置背景色
            $toast.css('background', background);
            
            // 重置进度条动画
            var $progressBar = $toast.find('.status-toast-progress-bar');
            $progressBar.remove();
            $toast.find('.status-toast-progress').append('<div class="status-toast-progress-bar"></div>');
            
            // 显示通知
            $toast.addClass('show');
            
            // 3秒后隐藏通知
            setTimeout(function() {
                $toast.removeClass('show');
            }, duration);
        }

        layui.use(['layer', 'form'], function() {
            var layer = layui.layer,
                $ = layui.jquery,
                form = layui.form;
            
            // 页面加载后刷新验证码
            $('#captcha').attr('src', '<?php echo captcha_src(); ?>?seed=' + Math.random());
            
            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                
                // 验证表单
                const username = $('#username').val().trim();
                const password = $('#password').val().trim();
                const captcha = $('#captcha-input').val().trim();
                
                if (!username) {
                    showToast('请输入账号', 'error');
                    return;
                }
                
                if (!password) {
                    showToast('请输入密码', 'error');
                    return;
                }
                
                if (!captcha) {
                    showToast('请输入验证码', 'error');
                    return;
                }
                
                // 禁用提交按钮防止重复提交
                const submitBtn = $(this).find('input[type="submit"]');
                submitBtn.prop('disabled', true);
                submitBtn.val('登录中...');
                
                $.ajax({
                    url: "<?php echo url('admin/login/checkLogin'); ?>",
                    data: $(this).serialize(),
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        if(res.code == 1) {
                            showToast('登录成功，正在跳转...', 'success');
                            setTimeout(function() {
                                // 确保URL是一个有效的字符串
                                var redirectUrl = typeof res.url === 'string' ? res.url : '<?php echo url("admin/index/index"); ?>';
                                window.location.href = redirectUrl;
                            }, 1000);
                        } else {
                            // 刷新验证码
                            $('#captcha').attr('src', '<?php echo captcha_src(); ?>?seed=' + Math.random());
                            // 显示错误信息
                            showToast(res.msg || '登录失败，请检查账号密码和验证码', 'error');
                            // 启用提交按钮
                            submitBtn.prop('disabled', false);
                            submitBtn.val('登录');
                        }
                    },
                    error: function() {
                        // 刷新验证码
                        $('#captcha').attr('src', '<?php echo captcha_src(); ?>?seed=' + Math.random());
                        // 显示错误信息
                        showToast('网络错误，请稍后重试', 'error');
                        // 启用提交按钮
                        submitBtn.prop('disabled', false);
                        submitBtn.val('登录');
                    }
                });
            });
        });
    </script>
</body>
</html> 