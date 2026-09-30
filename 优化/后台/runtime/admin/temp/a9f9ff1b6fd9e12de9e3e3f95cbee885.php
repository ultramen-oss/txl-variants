<?php /*a:1:{s:65:"/www/wwwroot/154.64.230.166/app/admin/view/appv1/link_qrcode.html";i:1749557264;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>绑定群二维码</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
    <style>
        body {
            background-color: #fff;
            margin: 0;
            padding: 0;
            font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", Arial, sans-serif;
        }
        
        .alert-container {
            width: 100%;
            max-width: 480px;
            margin: 0 auto;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .alert-header {
            background-color: #e53935;
            color: #fff;
            padding: 12px 0;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        
        .alert-content {
            padding: 15px 15px 20px;
            background-color: #fff;
        }
        
        .message-box {
            text-align: center;
            margin-bottom: 15px;
            line-height: 1.5;
            font-size: 14px;
            color: #333;
        }
        
        .notice-box {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .notice-text {
            display: inline-block;
            font-size: 12px;
            color: #e53935;
        }
        
        .btn-container {
            text-align: center;
            display: flex;
            justify-content: space-between;
            padding: 0 15px;
        }
        
        .btn {
            border: none;
            border-radius: 4px;
            padding: 8px 25px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .cancel-btn {
            background-color: #f0f0f0;
            color: #333;
        }
        
        .cancel-btn:hover {
            background-color: #e0e0e0;
        }
        
        .confirm-btn {
            background-color: #e53935;
            color: #fff;
        }
        
        .confirm-btn:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>
    <div class="alert-container">
        <div class="alert-header">
            警告！危险操作，请考虑后再执行
        </div>
        
        <div class="alert-content">
            <div class="message-box">
                已经发送命令给服务，准备开始指定扫描对群组，预计<br>
                15分钟之内完成。
            </div>
            
            <div class="notice-box">
                <span class="notice-text">
                    注意：(开始执行行为违法请在半个小时内完成对接操作)
                </span>
            </div>
            
            <div class="btn-container">
                <button class="btn cancel-btn" id="cancelBtn">取消</button>
                <button class="btn confirm-btn" id="confirmBtn">确定</button>
            </div>
        </div>
    </div>

    <script src="/static/jquery/jquery.min.js"></script>
    <script src="/static/layui/layui.js"></script>
    <script>
        layui.use(['layer'], function() {
            var layer = layui.layer;
            var $ = layui.jquery;
            
            // 确认按钮点击事件
            $('#confirmBtn').click(function() {
                parent.layer.closeAll();
            });
            
            // 取消按钮点击事件
            $('#cancelBtn').click(function() {
                parent.layer.closeAll();
            });
        });
    </script>
</body>
</html> 