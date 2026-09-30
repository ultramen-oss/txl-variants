<?php /*a:1:{s:67:"/www/wwwroot/154.219.101.15/app/admin/view/admin/edit_password.html";i:1749557052;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改密码</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css" media="all" />
    <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
    <style>
        body {
            background-color: #f5f5f5;
            padding: 0;
            margin: 0;
        }
        .password-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 380px;
            margin: 20px auto;
            padding: 25px;
            box-sizing: border-box;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        .password-input {
            width: 100%;
            height: 45px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 0 45px 0 15px;
            background-color: #f9f9fe;
            font-size: 14px;
            color: #333;
            box-sizing: border-box;
        }
        .password-input:focus {
            border-color: #6f42c1;
            outline: none;
            box-shadow: 0 0 0 2px rgba(111, 66, 193, 0.1);
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6f42c1;
            cursor: pointer;
            font-size: 18px;
            background: none;
            border: none;
            padding: 0;
        }
        .password-tips {
            background-color: #f9f9fe;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .password-tips h4 {
            color: #6f42c1;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: bold;
        }
        .password-tips p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        .submit-btn {
            width: 100%;
            height: 45px;
            background-color: #6f42c1;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #5a32a3;
        }
        
        /* 状态通知弹窗样式 */
        .status-toast {
            position: fixed;
            top: -100px;
            right: 20px;
            min-width: 250px;
            max-width: 350px;
            background: linear-gradient(145deg, #4CAF50, #2E7D32);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            display: flex;
            align-items: center;
            font-size: 15px;
            transform: translateY(0);
            opacity: 0;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .status-toast.error {
            background: linear-gradient(145deg, #f44336, #d32f2f);
        }

        .status-toast.warning {
            background: linear-gradient(145deg, #ff9800, #ed6c02);
        }

        .status-toast.show {
            transform: translateY(120px);
            opacity: 1;
        }

        .status-toast-icon {
            font-size: 24px;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-toast-content {
            flex: 1;
        }

        .status-toast-title {
            font-weight: 600;
            margin-bottom: 2px;
            display: block;
            font-size: 16px;
        }

        .status-toast-message {
            opacity: 0.95;
            font-size: 14px;
        }

        .status-toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 0 0 8px 8px;
            overflow: hidden;
        }

        .status-toast-progress-bar {
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 0 0 8px 8px;
            animation: toast-progress 3s linear forwards;
        }

        @keyframes toast-progress {
            0% {
                width: 100%;
            }
            100% {
                width: 0;
            }
        }

        /* 移动端响应式适配 */
        @media screen and (max-width: 480px) {
            .password-container {
                width: 90%;
                margin: 15px auto;
                padding: 20px 15px;
                border-radius: 6px;
            }
            
            .input-group {
                margin-bottom: 15px;
            }
            
            .password-input {
                height: 42px;
                font-size: 13px;
            }
            
            .toggle-password {
                font-size: 16px;
                right: 10px;
            }
            
            .password-tips {
                padding: 12px;
                margin-bottom: 15px;
            }
            
            .password-tips h4 {
                font-size: 13px;
            }
            
            .password-tips p {
                font-size: 11px;
                margin: 4px 0;
            }
            
            .submit-btn {
                height: 42px;
                font-size: 14px;
            }
            
            /* 弹窗样式调整 */
            .status-toast {
                min-width: 220px;
                max-width: 280px;
                padding: 12px 16px;
                font-size: 13px;
                right: 15px;
            }
            
            .status-toast.show {
                transform: translateY(60px);
            }
            
            .status-toast-icon {
                font-size: 20px;
                margin-right: 10px;
            }
            
            .status-toast-title {
                font-size: 14px;
                margin-bottom: 1px;
            }
            
            .status-toast-message {
                font-size: 12px;
            }
            
            .status-toast-progress {
                height: 2px;
            }
        }
        
        .layui-form-danger {
            border-color: #f44336 !important;
        }
        
        .layui-form-danger:focus {
            border-color: #f44336 !important;
            box-shadow: 0 0 0 2px rgba(244, 67, 54, 0.1) !important;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <form class="layui-form" id="passwordForm">
            <div class="input-group">
                <input type="password" name="oldpassword" id="old_password" placeholder="请输入旧密码" class="password-input">
                <button type="button" class="toggle-password" data-target="old_password">
                    <i class="fa fa-eye-slash"></i>
                </button>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" id="new_password" placeholder="请输入新密码" class="password-input">
                <button type="button" class="toggle-password" data-target="new_password">
                    <i class="fa fa-eye-slash"></i>
                </button>
            </div>
            
            <div class="input-group">
                <input type="password" name="password_confirm" id="confirm_password" placeholder="请确认新密码" class="password-input">
                <button type="button" class="toggle-password" data-target="confirm_password">
                    <i class="fa fa-eye-slash"></i>
                </button>
            </div>
            
            <div class="password-tips">
                <h4><i class="fa fa-lock"></i> 密码安全说明</h4>
                <p>密码需要至少6个字符</p>
                <p>支持数字、字母或组合方式</p>
                <p>建议不要使用与其他网站相同的密码</p>
                <p>定期更换密码可以提高账户安全性</p>
            </div>
            
            <button type="submit" class="submit-btn">修改</button>
        </form>
    </div>
    
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
    
    <script src="/static/layui/layui.js"></script>
    <script src="/static/public/jquery/jquery.min.js"></script>
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
            
            // 设置时间后隐藏通知
            setTimeout(function() {
                $toast.removeClass('show');
            }, duration);
        }
        
        layui.use(['form', 'layer'], function() {
            var form = layui.form;
            var layer = layui.layer;
            
            // 自定义验证规则
            form.verify({
                required: function(value, item) {
                    if (!value) {
                        // 返回错误消息，但不会显示，我们会在下面的verifyError中处理
                        return '必填项不能为空';
                    }
                }
            });
            
            // 监听表单验证错误事件
            $(document).on('mousedown keydown', function() {
                $('.layui-form-danger').removeClass('layui-form-danger');
            });
            
            // 监听验证失败
            var verifyError = function(elem, msg) {
                elem.focus().addClass('layui-form-danger');
                showToast(msg, 'error');
                return false;
            };
            
            // 密码显示/隐藏切换
            $('.toggle-password').on('click', function() {
                var targetId = $(this).data('target');
                var passwordInput = $('#' + targetId);
                var icon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
            
            // 表单提交处理
            $('#passwordForm').on('submit', function(e) {
                e.preventDefault(); // 阻止默认提交
                
                // 验证旧密码
                var oldPassword = $('#old_password');
                if (!oldPassword.val()) {
                    return verifyError(oldPassword, '请输入旧密码');
                }
                
                // 验证新密码
                var newPassword = $('#new_password');
                if (!newPassword.val()) {
                    return verifyError(newPassword, '请输入新密码');
                }
                
                // 验证确认密码
                var confirmPassword = $('#confirm_password');
                if (!confirmPassword.val()) {
                    return verifyError(confirmPassword, '请确认新密码');
                }
                
                // 获取表单数据
                var formData = {
                    oldpassword: oldPassword.val(),
                    password: newPassword.val(),
                    password_confirm: confirmPassword.val()
                };
                
                // 验证两次密码是否一致
                if (formData.password !== formData.password_confirm) {
                    showToast('两次输入的密码不一致', 'error');
                    return false;
                }
                
                // 验证密码长度
                if (formData.password.length < 6) {
                    showToast('密码长度不能少于6位', 'error');
                    return false;
                }
                
                // 禁用提交按钮防止重复提交
                var submitBtn = $('.submit-btn');
                submitBtn.prop('disabled', true);
                submitBtn.text('处理中...');
                
                // 提交表单
                $.ajax({
                    url: "<?php echo url('admin/admin/editPassword'); ?>",
                    type: 'post',
                    data: formData,
                    success: function(res) {
                        if (res.code == 1) {
                            showToast(res.msg || '修改密码成功', 'success', 2000);
                            setTimeout(function() {
                                if (res.url) {
                                    top.location.href = res.url;
                                }
                            }, 2000);
                        } else {
                            showToast(res.msg || '修改密码失败', 'error');
                            // 重新启用提交按钮
                            submitBtn.prop('disabled', false);
                            submitBtn.text('修改');
                        }
                    },
                    error: function() {
                        showToast('网络异常，请稍后重试', 'error');
                        // 重新启用提交按钮
                        submitBtn.prop('disabled', false);
                        submitBtn.text('修改');
                    }
                });
                
                return false;
            });
        });
    </script>
    
    <!-- 账号状态检查脚本 -->
    <script src="/static/admin/js/account-status-monitor.js"></script>
</body>
</html> 