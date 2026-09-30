<?php /*a:1:{s:64:"/www/wwwroot/154.219.101.15/app/admin/view/admin/admin_list.html";i:1749557036;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>管理员列表</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css" media="all" />
    <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
    <style type="text/css">
        body {
            background-color: #f5f7fa;
            padding: 20px;
            margin: 0;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .admin-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        /* 头部信息区域 */
        .header-info {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #8b5cf6;
            color: #fff;
            position: relative;
        }
        .header-info i {
            font-size: 20px;
            margin-right: 10px;
        }
        .header-info .title {
            font-size: 18px;
            font-weight: 500;
        }
        .header-info .lock-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.2);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* 功能按钮区域 */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }
        .action-button {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 8px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            font-weight: 500;
        }
        .action-button.purple {
            background-color: #8b5cf6;
            color: white;
        }
        .action-button.blue {
            background-color: #3b82f6;
            color: white;
        }
        .action-button.green {
            background-color: #10b981;
            color: white;
        }
        
        /* 表格样式 */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
        }
        .admin-table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: 600;
            text-align: left;
            padding: 15px 20px;
            font-size: 14px;
        }
        .admin-table td {
            padding: 15px 20px;
            border-top: 1px solid #f1f5f9;
            font-size: 14px;
            color: #475569;
        }
        .admin-table tr:hover {
            background-color: #faf5ff;
        }
        
        /* 用户类型标签 */
        .user-type {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
        }
        .user-type.admin {
            background-color: #8b5cf6;
            color: white;
        }
        .user-type.super-admin {
            background: linear-gradient(135deg, #f43f5e, #ec4899);
            color: white;
            box-shadow: 0 2px 5px rgba(236, 72, 153, 0.3);
        }
        .user-type.normal {
            background-color: #10b981;
            color: white;
        }
        
        /* 操作按钮 - 现代化风格 */
        .operation-btns {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            position: relative;
        }
        .operation-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
        }
        .operation-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
        }
        .operation-btn.edit {
            background-color: #3b82f6;
            color: white;
        }
        .operation-btn.delete {
            background-color: #ef4444;
            color: white;
        }
        .operation-btn.reset {
            background-color: #f59e0b;
            color: white;
        }
        
        /* 邀请码区域 */
        .invite-code-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .invite-code {
            font-family: monospace;
            background-color: #f1f5f9;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        .copy-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 4px;
            background-color: #e2e8f0;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
        }
        .copy-btn:hover {
            background-color: #cbd5e1;
            color: #334155;
        }
        .copy-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #10b981;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            z-index: 1000;
            opacity: 0;
            transform: translateY(-20px);
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        .copy-success.show {
            opacity: 1;
            transform: translateY(0);
        }
        .copy-success i {
            margin-right: 8px;
            font-size: 18px;
        }
        
        /* 分页样式 */
        .pagination-container {
            padding: 20px;
            display: flex;
            justify-content: flex-end;
        }
        
        .modern-pagination {
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .modern-pagination .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .modern-pagination .pagination li {
            display: inline-flex;
            margin: 0;
        }
        .modern-pagination .pagination li a,
        .modern-pagination .pagination li span {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            color: #555;
            background-color: #fff;
            border: none;
            text-decoration: none;
            min-width: 40px;
            transition: all 0.2s ease;
            font-size: 14px;
            border-right: 1px solid #f0f0f0;
        }
        .modern-pagination .pagination li:last-child a,
        .modern-pagination .pagination li:last-child span {
            border-right: none;
        }
        .modern-pagination .pagination li a:hover {
            background-color: #f7f7f7;
            color: #8b5cf6;
        }
        .modern-pagination .pagination .active span {
            background-color: #8b5cf6;
            color: #fff;
            font-weight: 500;
        }
        .modern-pagination .pagination .disabled span {
            color: #ccc;
            cursor: not-allowed;
            background-color: #f9f9f9;
        }

        /* 角色标签 */
        .role-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        .role-badge.super-admin {
            background-color: #ef4444;
            color: white;
        }
        .role-badge.admin {
            background-color: #8b5cf6;
            color: white;
        }
        .role-badge.user {
            background-color: #10b981;
            color: white;
        }
        
        /* 权限设置开关 */
        .permission-switch {
            margin-top: 0;
            position: absolute;
            right: 0;
            top: 100%;
            display: flex;
            align-items: center;
            background-color: #f9fafb;
            padding: 4px 8px;
            border-radius: 4px;
            margin-top: 6px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .permission-switch .layui-form-switch {
            margin-top: 0;
            min-width: 64px;
            padding: 0 12px;
        }
        .permission-label {
            display: block;
            margin-bottom: 0;
            margin-right: 5px;
            font-size: 12px;
            color: #64748b;
            white-space: nowrap;
        }
        
        /* 权限弹窗样式 */
        .permission-dialog {
            padding: 0;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
        }
        /* 调整权限开关样式 */
        .permission-item .layui-form-switch {
            min-width: 100px !important;
            height: 28px;
            line-height: 28px;
        }
        .permission-item .layui-form-switch em {
            font-size: 13px;
            font-style: normal;
            white-space: nowrap;
            width: auto;
        }
        .permission-item .layui-form-onswitch em {
            margin-left: -46px;
        }
        .permission-item .layui-form-onswitch i {
            margin-left: 70px;
        }
        .permission-dialog-title {
            display: flex;
            align-items: center;
            gap: 10px;
            background: white;
            color: #374151;
            font-size: 18px;
            font-weight: 600;
            padding: 20px;
            border-bottom: 1px solid #f3f4f6;
        }
        .permission-dialog-title i {
            color: #6366f1;
            background: rgba(99, 102, 241, 0.1);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-size: 20px;
        }
        .permission-item {
            margin: 20px;
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            transition: all 0.2s;
            border: 1px solid #f3f4f6;
        }
        .permission-item:hover {
            background: #f5f7ff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .permission-item-label {
            margin-bottom: 15px;
            font-weight: 600;
            color: #4b5563;
            display: flex;
            align-items: center;
            font-size: 16px;
        }
        .permission-item-label i {
            margin-right: 10px;
            color: #3b82f6;
            font-size: 18px;
        }
        .permission-tip {
            background: #f0f9ff;
            border: 1px solid #e0f2fe;
            border-radius: 8px;
            padding: 15px 20px;
            margin: 0 20px 20px;
            color: #0369a1;
        }
        .permission-tip p {
            margin-bottom: 8px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
        }
        .permission-tip p:last-child {
            margin-bottom: 0;
        }
        .permission-tip p i {
            margin-right: 10px;
            color: #0ea5e9;
            flex-shrink: 0;
            margin-top: 3px;
        }
        .permission-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px;
            border-top: 1px solid #f3f4f6;
        }
        .permission-actions .layui-btn {
            height: 40px;
            font-weight: 500;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 0 20px;
            transition: all 0.2s;
        }
        .permission-actions .layui-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* 用户选择弹窗样式 */
        .user-select-dialog {
            padding: 25px !important;
        }
        .user-select-item {
            border-radius: 6px;
            margin-bottom: 6px;
            transition: all 0.2s;
        }
        .user-select-item:hover {
            background-color: #f8f9fa;
        }
        #userSelectForm {
            border-radius: 8px !important;
            border: 1px solid #e5e7eb !important;
            padding: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        #userSearchInput {
            border-radius: 6px;
            height: 38px;
        }
        #userSearchBtn {
            height: 38px;
            border-radius: 6px;
        }
        
        /* 角色筛选按钮样式 */
        .role-filter-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 15px 20px;
        }
        
        .role-filter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            background-color: #f1f5f9;
            color: #475569;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .role-filter:hover {
            background-color: #e2e8f0;
            color: #334155;
        }
        
        .role-filter.active {
            background-color: #8b5cf6;
            color: white;
        }
        
        .role-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            background-color: rgba(255,255,255,0.3);
            font-size: 12px;
            font-weight: 600;
        }
        
        @media screen and (max-width: 768px) {
            .role-filter-buttons {
                overflow-x: auto;
                padding-bottom: 5px;
                flex-wrap: nowrap;
            }
            
            .role-filter {
                white-space: nowrap;
            }

            /* 优化移动端表格显示 */
            .admin-table {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .admin-table th, 
            .admin-table td {
                white-space: nowrap;
                padding: 10px 15px;
            }
            
            /* 调整按钮大小和间距 */
            .action-buttons {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 5px;
                -webkit-overflow-scrolling: touch;
            }
            
            .action-button {
                white-space: nowrap;
                flex-shrink: 0;
            }
            
            /* 优化操作按钮显示 */
            .operation-btns {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .operation-btn {
                margin-bottom: 5px;
                width: 100%;
                justify-content: center;
            }
            
            /* 头部信息区域调整 */
            .header-info .title {
                font-size: 16px;
            }
            
            /* 调整分页控件 */
            .modern-pagination .pagination li a,
            .modern-pagination .pagination li span {
                padding: 8px 12px;
                min-width: 30px;
            }
            
            /* 弹窗尺寸优化 */
            .layui-layer-page .layui-layer-content {
                overflow-x: hidden !important;
            }
        }
        
        /* 针对更小屏幕的优化 */
        @media screen and (max-width: 480px) {
            body {
                padding: 10px;
            }
            
            .admin-container {
                border-radius: 6px;
            }
            
            .header-info {
                padding: 15px;
            }
            
            .header-info .lock-icon {
                width: 28px;
                height: 28px;
                right: 15px;
            }
            
            .action-buttons {
                padding: 10px 15px;
            }
            
            .invite-code {
                max-width: 60px;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            
            /* 状态开关样式优化 */
            .status-switch {
                width: 100px;
            }
            
            /* 状态通知弹窗位置调整 */
            .status-toast.show {
                transform: translateY(80px);
            }
        }
        
        /* 增加弹窗样式 */
        .modern-dialog {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .modern-dialog .layui-layer-content {
            overflow: visible;
        }

        /* 设置开关宽度 */
        .layui-form-switch {
            width: 100px !important;
            margin-top: 0;
        }
        .layui-form-switch em {
            width: auto !important;
            font-size: 14px !important;
        }
        .layui-form-onswitch i {
            left: 80px !important;
            margin-left: 0 !important;
        }

        /* 更现代化的开关按钮样式 */
        .custom-switch-container {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 140px;
            height: 34px;
            margin: 0;
        }
        
        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .custom-switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e0e0e0;
            transition: .4s;
            border-radius: 34px;
            overflow: hidden;
        }
        
        .custom-switch-slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            z-index: 2;
        }
        
        .custom-switch-labels {
            position: absolute;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            font-weight: 500;
            z-index: 1;
        }
        
        .custom-switch-label-on {
            position: absolute;
            left: 12px;
            color: white;
            visibility: hidden;
        }
        
        .custom-switch-label-off {
            position: absolute;
            right: 12px;
            color: #666;
        }
        
        input:checked + .custom-switch-slider {
            background-color: #3b82f6;
        }
        
        input:checked + .custom-switch-slider:before {
            transform: translateX(106px);
        }
        
        input:checked + .custom-switch-slider .custom-switch-label-on {
            visibility: visible;
        }
        
        input:checked + .custom-switch-slider .custom-switch-label-off {
            visibility: hidden;
        }
        
        .custom-switch-description {
            margin-top: 8px;
            color: #666;
            font-size: 13px;
        }

        /* 状态开关现代样式优化 */
        .status-switch {
            position: relative;
            display: inline-block;
            width: 120px;
            height: 36px;
            border-radius: 36px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
            cursor: pointer;
            font-family: "Microsoft YaHei", "微软雅黑", sans-serif;
        }
        
        .status-switch.disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
        
        .status-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .status-slider {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 36px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.2);
            background-color: #e0e0e0;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        /* 左右两侧文字容器 */
        .status-labels {
            position: relative;
            height: 100%;
            width: 100%;
            display: flex;
        }
        
        /* 启用状态的文字区域 */
        .status-enabled {
            position: absolute;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 15px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            background: linear-gradient(90deg, #2196F3, #4FC3F7);
            z-index: 1;
            transition: all 0.3s ease;
            opacity: 0;
        }
        
        /* 禁用状态的文字区域 */
        .status-disabled {
            position: absolute;
            right: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 15px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            background: linear-gradient(90deg, #9E9E9E, #BDBDBD);
            z-index: 1;
            transition: all 0.3s ease;
            opacity: 0;
        }
        
        /* 滑动的圆形按钮 */
        .status-slider:before {
            position: absolute;
            content: "";
            height: 28px;
            width: 28px;
            top: 4px;
            left: 4px;
            background-color: white;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.25);
            z-index: 2;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        /* 启用状态样式 */
        input:checked + .status-slider {
            background: linear-gradient(90deg, #2196F3, #4FC3F7);
        }
        
        input:checked + .status-slider:before {
            transform: translateX(84px);
        }
        
        input:checked + .status-slider .status-enabled {
            opacity: 1;
        }
        
        input:checked + .status-slider .status-disabled {
            opacity: 0;
        }
        
        /* 禁用状态样式 */
        input:not(:checked) + .status-slider .status-enabled {
            opacity: 0;
        }
        
        input:not(:checked) + .status-slider .status-disabled {
            opacity: 1;
        }
        
        /* 交互效果 */
        .status-switch:hover .status-slider:before {
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        /* 状态更改通知弹窗样式 */
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
        
        /* 添加新的样式类 */
        .admin-manage-btn {
            background-color: #0ea5e9;
            color: white;
        }
        
        .admin-manage-btn.disabled {
            opacity: 0.6;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* 复制成功提示框样式 */
        .copy-success-toast {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 12px 20px;
            border-radius: 6px;
            font-size: 14px;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .copy-success-toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="header-info">
            <i class="fa fa-users"></i>
            <div class="title">管理员列表</div>
            <div class="lock-icon">
                <i class="fa fa-shield"></i>
            </div>
        </div>
        
        <div class="action-buttons">
            <?php if($admin['role_type'] == 'super_admin' || $admin['role_type'] == 'admin'): ?>
            <button class="action-button purple" onclick="addAdmin()">
                <i class="fa fa-plus"></i> 添加管理员
            </button>
            <?php endif; ?>
            <button class="action-button blue" onclick="refreshList()">
                <i class="fa fa-refresh"></i> 刷新
            </button>
        </div>

        <!-- 角色分组筛选按钮 -->
        <div class="role-filter-buttons">
            <button class="role-filter active" data-role="all">
                <i class="fa fa-users"></i> 全部
                <span class="role-count"><?php echo htmlentities((string) count($admin_list)); ?></span>
            </button>
            <button class="role-filter" data-role="super_admin">
                <i class="fa fa-shield"></i> 超级管理员
            </button>
            <button class="role-filter" data-role="admin">
                <i class="fa fa-user-circle"></i> 普通管理员
            </button>
            <button class="role-filter" data-role="user">
                <i class="fa fa-user"></i> 普通账号
            </button>
        </div>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <!-- 移除ID列 -->
                    <th>头像</th>
                    <th>昵称</th>
                    <th>用户名</th>
                    <th>角色类型</th>
                    <th>邀请码</th>
                    <th>状态</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if(is_array($admin_list) || $admin_list instanceof \think\Collection || $admin_list instanceof \think\Paginator): $i = 0; $__LIST__ = $admin_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;if($admin['role_type'] == 'user' && $admin['id'] != $vo['id']): else: ?>
                <tr>
                    <!-- 移除ID单元格 -->
                    <!-- 增加头像单元格 -->
                    <td>
                        <?php if(!empty($vo['thumb'])): ?>
                        <img src="<?php echo htmlentities((string) $vo['thumb']); ?>" alt="<?php echo htmlentities((string) $vo['nickname']); ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                        <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #8b5cf6; color: white; display: flex; align-items: center; justify-content: center; font-weight: 500;"><?php echo mb_substr($vo['nickname'], 0, 1, 'utf-8'); ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlentities((string) $vo['nickname']); ?></td>
                    <td><?php echo htmlentities((string) $vo['name']); ?></td>
                    <td>
                        <?php 
                        // 在模板中确定角色类型
                        $roleType = '';
                        $roleName = '';
                        
                        if (isset($vo['role_type']) && !empty($vo['role_type'])) {
                            $roleType = $vo['role_type'];
                        } else {
                            // 根据规则判断角色类型
                            if ($vo['id'] == 1 || $vo['name'] == 'admin') {
                                $roleType = 'super_admin';
                            } else if ($vo['admin_cate_id'] == 1 || $vo['admin_cate_id'] == 2) {
                                $roleType = 'admin';
                            } else {
                                $roleType = 'user';
                            }
                        }
                        
                        if ($roleType == 'super_admin') {
                            $roleName = '超级管理员';
                        } else if ($roleType == 'admin') {
                            $roleName = '普通管理员';
                        } else {
                            $roleName = '普通账号';
                        }
                         ?>
                        <div class="user-type <?php if($roleType == 'super_admin'): ?>super-admin<?php elseif($roleType == 'admin'): ?>admin<?php else: ?>normal<?php endif; ?>">
                            <?php echo htmlentities((string) $roleName); ?>
                        </div>
                    </td>
                    <td>
                        <?php if(!empty($vo['invite_code'])): ?>
                        <div class="invite-code-container">
                            <span class="invite-code"><?php echo htmlentities((string) $vo['invite_code']); ?></span>
                            <span class="copy-btn" onclick="copyInviteCode('<?php echo htmlentities((string) $vo['invite_code']); ?>')">
                                <i class="fa fa-copy"></i>
                            </span>
                        </div>
                        <?php else: ?>
                        --
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($roleType == 'super_admin'): ?>
                        <!-- 超级管理员始终显示为启用状态 -->
                        <label class="status-switch disabled">
                            <input type="checkbox" checked disabled>
                            <div class="status-slider">
                                <div class="status-labels">
                                    <span class="status-enabled">启用</span>
                                    <span class="status-disabled">禁用</span>
                                </div>
                            </div>
                        </label>
                        <?php else: ?>
                        <!-- 修改：当前账号无法修改自己的状态 -->
                        <?php if($admin['id'] == $vo['id']): ?>
                        <label class="status-switch disabled">
                            <input type="checkbox" data-id="<?php echo htmlentities((string) $vo['id']); ?>" <?php if($vo['status'] == 1): ?>checked<?php endif; ?> disabled>
                            <div class="status-slider">
                                <div class="status-labels">
                                    <span class="status-enabled">启用</span>
                                    <span class="status-disabled">禁用</span>
                                </div>
                            </div>
                        </label>
                        <!-- 普通账号无法修改其他账号状态 -->
                        <?php elseif($admin['role_type'] == 'user'): ?>
                        <label class="status-switch disabled">
                            <input type="checkbox" data-id="<?php echo htmlentities((string) $vo['id']); ?>" <?php if($vo['status'] == 1): ?>checked<?php endif; ?> disabled>
                            <div class="status-slider">
                                <div class="status-labels">
                                    <span class="status-enabled">启用</span>
                                    <span class="status-disabled">禁用</span>
                                </div>
                            </div>
                        </label>
                        <?php else: ?>
                        <label class="status-switch">
                            <input type="checkbox" class="status-toggle" data-id="<?php echo htmlentities((string) $vo['id']); ?>" <?php if($vo['status'] == 1): ?>checked<?php endif; ?>>
                            <div class="status-slider">
                                <div class="status-labels">
                                    <span class="status-enabled">启用</span>
                                    <span class="status-disabled">禁用</span>
                                </div>
                            </div>
                        </label>
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="operation-btns">
                            <?php if(($admin['role_type'] == 'super_admin' || ($admin['role_type'] == 'admin' && $roleType != 'super_admin')) || ($admin['role_type'] == 'user' && $admin['id'] == $vo['id'])): ?>
                            <a class="operation-btn edit" href="<?php echo url('admin/admin/publish', ['id' => $vo['id']]); ?>">
                                <i class="fa fa-edit"></i> 编辑
                            </a>
                            <?php endif; if(($admin['role_type'] == 'super_admin' || ($admin['role_type'] == 'admin' && $roleType == 'user')) && $vo['id'] != 1): ?>
                            <a class="operation-btn delete" href="javascript:;" onclick="confirmDelete('<?php echo htmlentities((string) $vo['id']); ?>')">
                                <i class="fa fa-trash"></i> 删除
                            </a>
                            <?php endif; if($admin['role_type'] == 'super_admin' || ($admin['role_type'] == 'admin' && $roleType != 'super_admin') || ($admin['role_type'] == 'user' && $admin['id'] == $vo['id'])): ?>
                            <a class="operation-btn reset" href="javascript:;" onclick="resetPassword('<?php echo htmlentities((string) $vo['id']); ?>')">
                                <i class="fa fa-key"></i> 重置密码
                            </a>
                            <?php endif; if($admin['role_type'] == 'super_admin' || $admin['role_type'] == 'admin' || ($admin['role_type'] == 'user' && $admin['id'] == $vo['id'])): ?>
                            <a class="operation-btn" style="background-color: #38bdf8; color: white;" href="javascript:;" onclick="viewLoginLog('<?php echo htmlentities((string) $vo['id']); ?>')">
                                <i class="fa fa-history"></i> 登录日志
                            </a>
                            <?php endif; if($roleType == 'user' && ($admin['role_type'] == 'super_admin' || $admin['role_type'] == 'admin')): ?>
                            <a class="operation-btn" style="background-color: #8b5cf6; color: white;" href="javascript:;" onclick="editPermissions('<?php echo htmlentities((string) $vo['id']); ?>', '<?php echo htmlentities((string) $vo['can_delete_user']); ?>', '<?php echo htmlentities((string) (isset($vo['can_export_data']) && ($vo['can_export_data'] !== '')?$vo['can_export_data']:0)); ?>')">
                                <i class="fa fa-shield"></i> 权限设置
                            </a>
                            <?php endif; if($roleType == 'admin' && $admin['role_type'] == 'super_admin'): ?>
                            <a class="operation-btn" style="background-color: #8b5cf6; color: white;" href="javascript:;" onclick="editAdminPermissions('<?php echo htmlentities((string) $vo['id']); ?>', '<?php echo htmlentities((string) (isset($vo['view_all_invites']) && ($vo['view_all_invites'] !== '')?$vo['view_all_invites']:0)); ?>')">
                                <i class="fa fa-shield"></i> 权限设置
                            </a>
                            <?php endif; ?>

                            <!-- 修改：普通管理员不显示下级用户管理按钮，只有超级管理员可以看到 -->
                            <?php if($roleType == 'admin' && $admin['role_type'] == 'super_admin'): ?>
                            <a class="operation-btn admin-manage-btn<?php if($vo['view_all_invites'] == 1): ?> disabled<?php endif; ?>"
                               <?php if($vo['view_all_invites'] != 1): ?>
                               href="<?php echo url('admin/admin/viewAssignedUsers', ['id' => $vo['id']]); ?>"
                               <?php else: ?>
                               href="javascript:;"
                               onclick="showViewAllMessage()"
                               <?php endif; ?>
                            >
                                <i class="fa fa-eye"></i> 下级用户管理
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
        
        <div class="pagination-container modern-pagination">
            <?php echo $page; ?>
        </div>
    </div>
    
    <!-- 复制成功提示 -->
    <div class="copy-success-toast" id="copySuccess">
        <i class="fa fa-check-circle"></i> 已复制到剪贴板
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
    <script src="/static/jquery/jquery.min.js"></script>
    <script>
        layui.use(['layer', 'form'], function() {
            var layer = layui.layer,
                form = layui.form;
            
            // 监听状态切换
            form.on('switch(statusSwitch)', function(data) {
                var id = this.value;
                var status = this.checked ? 1 : 0;
                
                // 发送AJAX请求更新状态
                $.ajax({
                    url: "<?php echo url('admin/admin/changeStatus'); ?>",
                    type: 'post',
                    data: {id: id, status: status},
                    success: function(res) {
                        if(res.code == 1) {
                            layer.msg(res.msg, {icon: 1});
                            // 如果状态变更为禁用，则禁止下次登录
                            if (status === 0) {
                                // 发送额外请求来确保账号无法登录
                                $.ajax({
                                    url: "<?php echo url('admin/admin/forceLogout'); ?>",
                                    type: 'post',
                                    data: {id: id},
                                    success: function(logoutRes) {
                                        if(logoutRes.code == 1) {
                                            console.log('账号已强制登出');
                                        }
                                    }
                                });
                            }
                        } else {
                            layer.msg(res.msg, {icon: 2});
                            // 恢复开关状态
                            $(data.elem).prop('checked', !data.elem.checked);
                            form.render('checkbox');
                        }
                    },
                    error: function() {
                        layer.msg('网络错误，请重试', {icon: 2});
                        // 恢复开关状态
                        $(data.elem).prop('checked', !data.elem.checked);
                        form.render('checkbox');
                    }
                });
            });
            
            // 监听删除权限切换
            form.on('switch(deletePermissionSwitch)', function(data) {
                var id = this.value;
                var can_delete = this.checked ? 1 : 0;
                
                // 发送AJAX请求更新权限
                $.ajax({
                    url: "<?php echo url('admin/admin/changeDeletePermission'); ?>",
                    type: 'post',
                    data: {id: id, can_delete_user: can_delete},
                    success: function(res) {
                        if(res.code == 1) {
                            layer.msg(res.msg, {icon: 1});
                        } else {
                            layer.msg(res.msg, {icon: 2});
                            // 恢复开关状态
                            $(data.elem).prop('checked', !data.elem.checked);
                            form.render('checkbox');
                        }
                    }
                });
            });

            // 添加状态开关点击事件
            $(document).on('change', '.status-toggle', function() {
                var id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                var $switch = $(this);
                
                // 添加动画效果
                if(status === 1) {
                    $switch.closest('.status-switch').find('.status-slider').css('background', 'linear-gradient(90deg, #2196F3, #4FC3F7)');
                } else {
                    $switch.closest('.status-switch').find('.status-slider').css('background', '#e0e0e0');
                }
                
                // 发送AJAX请求更新状态
                $.ajax({
                    url: "<?php echo url('admin/admin/changeStatus'); ?>",
                    type: 'post',
                    data: {id: id, status: status},
                    success: function(res) {
                        if(res.code == 1) {
                            // 使用自定义通知替代layer.msg
                            showStatusToast({
                                type: 'success',
                                title: '操作成功',
                                message: status === 1 ? '账号已成功启用' : '账号已成功禁用'
                            });
                            
                            // 如果状态变更为禁用，则禁止下次登录
                            if (status === 0) {
                                // 发送额外请求来确保账号无法登录
                                $.ajax({
                                    url: "<?php echo url('admin/admin/forceLogout'); ?>",
                                    type: 'post',
                                    data: {id: id},
                                    success: function(logoutRes) {
                                        if(logoutRes.code == 1) {
                                            console.log('账号已强制登出');
                                        }
                                    }
                                });
                            }
                        } else {
                            // 使用自定义通知显示错误
                            showStatusToast({
                                type: 'error',
                                title: '操作失败',
                                message: res.msg || '状态更新失败'
                            });
                            
                            // 恢复开关状态
                            $switch.prop('checked', !$switch.prop('checked'));
                            // 恢复样式
                            if(!$switch.prop('checked')) {
                                $switch.closest('.status-switch').find('.status-slider').css('background', '#e0e0e0');
                            } else {
                                $switch.closest('.status-switch').find('.status-slider').css('background', 'linear-gradient(90deg, #2196F3, #4FC3F7)');
                            }
                        }
                    },
                    error: function() {
                        // 使用自定义通知显示错误
                        showStatusToast({
                            type: 'error',
                            title: '网络错误',
                            message: '请检查网络连接并重试'
                        });
                        
                        // 恢复开关状态
                        $switch.prop('checked', !$switch.prop('checked'));
                        // 恢复样式
                        if(!$switch.prop('checked')) {
                            $switch.closest('.status-switch').find('.status-slider').css('background', '#e0e0e0');
                        } else {
                            $switch.closest('.status-switch').find('.status-slider').css('background', 'linear-gradient(90deg, #2196F3, #4FC3F7)');
                        }
                    }
                });
            });
        });
        
        // 复制邀请码
        function copyInviteCode(code) {
            if (!code) return;
            
            // 创建临时textarea元素
            var textarea = document.createElement('textarea');
            textarea.value = code;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            
            // 选择文本并复制
            textarea.select();
            document.execCommand('copy');
            
            // 移除临时元素
            document.body.removeChild(textarea);
            
            // 显示成功提示
            var successEl = document.getElementById('copySuccess');
            successEl.classList.add('show');
            
            // 3秒后隐藏提示
            setTimeout(function() {
                successEl.classList.remove('show');
            }, 3000);
            
            // 同时显示现代化的状态通知
            showStatusToast({
                type: 'success',
                title: '复制成功',
                message: '邀请码已成功复制到剪贴板'
            });
        }
        
        // 添加管理员
        function addAdmin() {
            location.href = "<?php echo url('admin/admin/publish'); ?>";
        }
        
        // 刷新列表
        function refreshList() {
            location.reload();
        }
        
        // 查看登录日志
        function viewLoginLog(id) {
            layer.open({
                type: 2,
                title: '登录日志',
                shadeClose: true,
                shade: 0.3,
                maxmin: false, // 不显示最大化/最小化按钮
                area: ['700px', '500px'],
                content: "<?php echo url('admin/admin/loginLog'); ?>" + "?id=" + id,
                scrollbar: false
            });
        }
        
        // 确认删除
        function confirmDelete(id) {
            layer.confirm('确定要删除该管理员吗？', {
                btn: ['确定', '取消']
            }, function() {
                // 发送AJAX请求删除
                $.ajax({
                    url: "<?php echo url('admin/admin/delete'); ?>",
                    type: 'post',
                    data: {id: id},
                    success: function(res) {
                        if(res.code == 1) {
                            layer.close(index);
                            showStatusToast({
                                type: 'success',
                                title: '删除成功',
                                message: res.msg || '管理员已成功删除'
                            });
                            // 刷新页面
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            layer.close(index);
                            showStatusToast({
                                type: 'error',
                                title: '删除失败',
                                message: res.msg || '删除操作失败'
                            });
                        }
                    },
                    error: function() {
                        layer.close(index);
                        showStatusToast({
                            type: 'error',
                            title: '网络错误',
                            message: '请检查网络连接并重试'
                        });
                    }
                });
            });
        }
        
        // 打开重置密码弹窗
        function resetPassword(id) {
            layer.open({
                type: 2,
                title: '重置密码',
                shadeClose: true,
                shade: 0.3,
                maxmin: false, // 不显示最大化/最小化按钮
                area: ['400px', '400px'],
                content: "<?php echo url('admin/admin/resetPassword'); ?>" + "?id=" + id,
                scrollbar: false
            });
        }
        
        // 打开权限设置弹窗
        function editPermissions(id, canDeleteUser, canExportData = 0) {
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shadeClose: true,
                shade: 0.4,
                area: ['500px', 'auto'],
                skin: 'modern-dialog',
                content: `
                    <div class="permission-dialog">
                        <div class="permission-dialog-title">
                            <i class="fa fa-shield"></i> 普通账号权限设置
                        </div>
                        
                        <div class="permission-item">
                            <div class="permission-item-label">
                                <i class="fa fa-trash"></i> 删除用户权限
                            </div>
                            <div class="custom-switch-container">
                                <label class="custom-switch">
                                    <input type="checkbox" id="can_delete_switch" ${canDeleteUser == 1 ? 'checked' : ''}>
                                    <div class="custom-switch-slider">
                                        <div class="custom-switch-labels">
                                            <span class="custom-switch-label-on">允许</span>
                                            <span class="custom-switch-label-off">禁止</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="custom-switch-description">设置该账号是否可以删除自己邀请的用户数据</div>
                        </div>
                        
                        <div class="permission-item">
                            <div class="permission-item-label">
                                <i class="fa fa-download"></i> 导出数据权限
                            </div>
                            <div class="custom-switch-container">
                                <label class="custom-switch">
                                    <input type="checkbox" id="can_export_switch" ${canExportData == 1 ? 'checked' : ''}>
                                    <div class="custom-switch-slider">
                                        <div class="custom-switch-labels">
                                            <span class="custom-switch-label-on">允许</span>
                                            <span class="custom-switch-label-off">禁止</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="custom-switch-description">设置该账号是否可以导出用户数据</div>
                        </div>
                        
                        <div class="permission-tip">
                            <p><i class="fa fa-info-circle"></i> 权限说明：</p>
                            <p><i class="fa fa-check"></i> 选择"允许"：该账号可删除/导出自己邀请的用户数据</p>
                            <p><i class="fa fa-check"></i> 选择"禁止"：该账号只能查看自己邀请的用户，无法删除/导出</p>
                        </div>
                        
                        <div class="permission-actions">
                            <button type="button" class="layui-btn layui-btn-primary" onclick="layer.closeAll()">
                                <i class="fa fa-times"></i> 取消
                            </button>
                            <button type="button" class="layui-btn" onclick="savePermissions()">
                                <i class="fa fa-save"></i> 保存
                            </button>
                        </div>
                    </div>
                `,
                success: function() {
                    // 绑定保存按钮事件
                    window.savePermissions = function() {
                        var canDelete = $('#can_delete_switch').prop('checked') ? 1 : 0;
                        var canExport = $('#can_export_switch').prop('checked') ? 1 : 0;
                        
                        // 发送AJAX请求更新权限
                        $.ajax({
                            url: "<?php echo url('admin/admin/changeDeletePermission'); ?>",
                            type: 'post',
                            data: {id: id, can_delete_user: canDelete, can_export_data: canExport},
                            success: function(res) {
                                if(res.code == 1) {
                                    // 使用自定义通知替代layer.msg
                                    // 根据实际修改显示不同消息
                                    var canDeleteOld = parseInt(canDeleteUser);
                                    var canExportOld = parseInt(canExportData);
                                    
                                    var message = "";
                                    if (canDeleteOld != canDelete && canExportOld != canExport) {
                                        message = canDelete == 1 ? '该账号已允许删除和导出用户' : '该账号已禁止删除和导出用户';
                                    } else if (canDeleteOld != canDelete) {
                                        message = canDelete == 1 ? '该账号已允许删除用户' : '该账号已禁止删除用户';
                                    } else if (canExportOld != canExport) {
                                        message = canExport == 1 ? '该账号已允许导出用户数据' : '该账号已禁止导出用户数据';
                                    }
                                    
                                    showStatusToast({
                                        type: 'success',
                                        title: '权限更新成功',
                                        message: message
                                    });
                                    
                                    layer.closeAll();
                                    // 刷新页面以更新状态
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    // 显示错误弹窗，而不是简单的toast
                                    layer.open({
                                        type: 1,
                                        title: false,
                                        closeBtn: 0,
                                        shadeClose: true,
                                        shade: 0.4,
                                        area: ['500px', 'auto'],
                                        skin: 'modern-dialog',
                                        content: `
                                            <div class="permission-dialog">
                                                <div class="permission-dialog-title">
                                                    <i class="fa fa-info-circle" style="color: #f59e0b;"></i> 提示信息
                                                </div>
                                                
                                                <div style="padding: 30px 20px; text-align: center;">
                                                    <div style="width: 70px; height: 70px; margin: 0 auto 20px; background-color: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fa fa-exclamation" style="font-size: 36px; color: white;"></i>
                                                    </div>
                                                    <div style="font-size: 16px; margin-bottom: 10px; color: #374151; font-weight: 500;">
                                                        表单无任何修改
                                                    </div>
                                                    <div style="font-size: 14px; color: #6b7280; line-height: 1.6;">
                                                        当前选择与原设置相同，无需提交更改。
                                                        <br>如需修改权限，请更改选项后再提交。
                                                    </div>
                                                </div>
                                                
                                                <div class="permission-actions" style="justify-content: center;">
                                                    <button type="button" class="layui-btn" onclick="layer.closeAll()">
                                                        <i class="fa fa-check"></i> 确定
                                                    </button>
                                                </div>
                                            </div>
                                        `
                                    });
                                }
                            },
                            error: function() {
                                showStatusToast({
                                    type: 'error',
                                    title: '网络错误',
                                    message: '请检查网络连接并重试'
                                });
                            }
                        });
                    };
                }
            });
        }
        
        // 打开普通管理员权限设置弹窗
        function editAdminPermissions(id, viewAllInvites) {
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shadeClose: true,
                shade: 0.4,
                area: ['500px', 'auto'],
                skin: 'modern-dialog',
                content: `
                    <div class="permission-dialog">
                        <div class="permission-dialog-title">
                            <i class="fa fa-shield"></i> 普通管理员权限设置
                        </div>
                        
                        <div class="permission-item">
                            <div class="permission-item-label">
                                <i class="fa fa-eye"></i> 数据查看权限
                            </div>
                            <div class="custom-switch-container">
                                <label class="custom-switch">
                                    <input type="checkbox" id="view_all_switch" ${viewAllInvites == 1 ? 'checked' : ''}>
                                    <div class="custom-switch-slider">
                                        <div class="custom-switch-labels">
                                            <span class="custom-switch-label-on">全部</span>
                                            <span class="custom-switch-label-off">部分</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="custom-switch-description">控制该管理员是否可以查看所有用户数据</div>
                        </div>
                        
                        <div class="permission-tip">
                            <p><i class="fa fa-info-circle"></i> 权限说明：</p>
                            <p><i class="fa fa-check"></i> 选择"全部"：该管理员可查看系统中所有用户数据</p>
                            <p><i class="fa fa-check"></i> 选择"部分"：需要分配可查看的下级用户</p>
                        </div>
                        
                        <div class="permission-actions">
                            <button type="button" class="layui-btn layui-btn-primary" onclick="layer.closeAll()">
                                <i class="fa fa-times"></i> 取消
                            </button>
                            <button type="button" class="layui-btn" onclick="saveAdminPermissions()">
                                <i class="fa fa-save"></i> 保存
                            </button>
                        </div>
                    </div>
                `,
                success: function() {
                    // layui.form.render(); // 不再需要渲染layui表单
                    
                    // 绑定保存按钮事件
                    window.saveAdminPermissions = function() {
                        var viewAll = $('#view_all_switch').prop('checked') ? 1 : 0;
                        var loadingIndex = layer.load(1, {shade: [0.2, '#000']});
                        
                        // 发送AJAX请求更新权限
                        $.ajax({
                            url: "<?php echo url('admin/admin/changeViewAllPermission'); ?>",
                            type: 'post',
                            data: {id: id, view_all_invites: viewAll},
                            success: function(res) {
                                layer.close(loadingIndex);
                                if(res.code == 1) {
                                    // 使用自定义通知替代layer.msg
                                    showStatusToast({
                                        type: 'success',
                                        title: '权限设置成功',
                                        message: viewAll == 1 ? '已允许查看全部用户数据' : '已设置为仅查看指定用户数据'
                                    });
                                    
                                    layer.closeAll('page');
                                    // 刷新页面以更新状态
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                } else {
                                    // 显示错误弹窗，而不是简单的toast
                                    layer.open({
                                        type: 1,
                                        title: false,
                                        closeBtn: 0,
                                        shadeClose: true,
                                        shade: 0.4,
                                        area: ['500px', 'auto'],
                                        skin: 'modern-dialog',
                                        content: `
                                            <div class="permission-dialog">
                                                <div class="permission-dialog-title">
                                                    <i class="fa fa-info-circle" style="color: #f59e0b;"></i> 提示信息
                                                </div>
                                                
                                                <div style="padding: 30px 20px; text-align: center;">
                                                    <div style="width: 70px; height: 70px; margin: 0 auto 20px; background-color: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                        <i class="fa fa-exclamation" style="font-size: 36px; color: white;"></i>
                                                    </div>
                                                    <div style="font-size: 16px; margin-bottom: 10px; color: #374151; font-weight: 500;">
                                                        表单无任何修改
                                                    </div>
                                                    <div style="font-size: 14px; color: #6b7280; line-height: 1.6;">
                                                        当前选择与原设置相同，无需提交更改。
                                                        <br>如需修改权限，请更改选项后再提交。
                                                    </div>
                                                </div>
                                                
                                                <div class="permission-actions" style="justify-content: center;">
                                                    <button type="button" class="layui-btn" onclick="layer.closeAll()">
                                                        <i class="fa fa-check"></i> 确定
                                                    </button>
                                                </div>
                                            </div>
                                        `
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                layer.close(loadingIndex);
                                // 显示网络错误弹窗
                                layer.open({
                                    type: 1,
                                    title: false,
                                    closeBtn: 0,
                                    shadeClose: true,
                                    shade: 0.4,
                                    area: ['500px', 'auto'],
                                    skin: 'modern-dialog',
                                    content: `
                                        <div class="permission-dialog">
                                            <div class="permission-dialog-title">
                                                <i class="fa fa-exclamation-triangle" style="color: #f59e0b;"></i> 网络错误
                                            </div>
                                            
                                            <div style="padding: 30px 20px; text-align: center;">
                                                <div style="width: 70px; height: 70px; margin: 0 auto 20px; background-color: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa fa-wifi" style="font-size: 36px; color: white;"></i>
                                                </div>
                                                <div style="font-size: 16px; margin-bottom: 10px; color: #374151; font-weight: 500;">
                                                    无法连接到服务器
                                                </div>
                                                <div style="font-size: 14px; color: #6b7280; line-height: 1.6;">
                                                    网络连接出现问题，请检查您的网络连接并重试。
                                                    <br>状态码: ${status || '未知'}, 错误信息: ${error || '未知错误'}
                                                </div>
                                            </div>
                                            
                                            <div class="permission-actions" style="justify-content: center;">
                                                <button type="button" class="layui-btn layui-btn-primary" onclick="layer.closeAll()">
                                                    <i class="fa fa-times"></i> 关闭
                                                </button>
                                                <button type="button" class="layui-btn" onclick="saveAdminPermissions()">
                                                    <i class="fa fa-refresh"></i> 重试
                                                </button>
                                            </div>
                                        </div>
                                    `
                                });
                            }
                        });
                    };
                }
            });
        }
        
        // 显示"管理员拥有全部查看权限"的提示信息
        function showViewAllMessage() {
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shadeClose: true,
                shade: 0.4,
                area: ['500px', 'auto'],
                skin: 'modern-dialog',
                content: `
                    <div class="permission-dialog">
                        <div class="permission-dialog-title">
                            <i class="fa fa-info-circle" style="color: #f59e0b;"></i> 提示信息
                        </div>
                        
                        <div style="padding: 30px 20px; text-align: center;">
                            <div style="width: 70px; height: 70px; margin: 0 auto 20px; background-color: #f59e0b; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fa fa-exclamation" style="font-size: 36px; color: white;"></i>
                            </div>
                            <div style="font-size: 16px; margin-bottom: 10px; color: #374151; font-weight: 500;">
                                表单无任何修改
                            </div>
                            <div style="font-size: 14px; color: #6b7280; line-height: 1.6;">
                                当前选择与原设置相同，无需提交更改。
                                <br>如需修改权限，请更改选项后再提交。
                            </div>
                        </div>
                        
                        <div class="permission-actions" style="justify-content: center;">
                            <button type="button" class="layui-btn" onclick="layer.closeAll()">
                                <i class="fa fa-check"></i> 确定
                            </button>
                        </div>
                    </div>
                `
            });
        }
        
        // 在当前弹窗中显示分配下级用户的对话框
        function showAssignUsersDialog(adminId) {
            // 获取用户列表
            $.ajax({
                url: "<?php echo url('admin/admin/getUsersWithInviteCodes'); ?>",
                type: 'get',
                success: function(res) {
                    if(res.code == 1) {
                        // 获取当前管理员已分配的邀请码
                        $.ajax({
                            url: "<?php echo url('admin/admin/getAssignedInvites'); ?>",
                            type: 'get',
                            data: {id: adminId},
                            success: function(assignedRes) {
                                var assignedInvites = [];
                                if(assignedRes.code == 1 && assignedRes.data) {
                                    assignedInvites = assignedRes.data.split(',');
                                }
                                
                                // 打开用户选择弹窗
                                layer.open({
                                    type: 1,
                                    title: false,
                                    closeBtn: 0,
                                    shadeClose: true,
                                    shade: 0.4,
                                    area: ['650px', '550px'],
                                    skin: 'modern-dialog',
                                    content: `
                                        <div class="user-select-dialog">
                                            <div class="permission-dialog-title">
                                                <i class="fa fa-users"></i> 选择下级用户
                                            </div>
                                            <div class="search-bar" style="margin-bottom: 15px;">
                                                <div class="layui-input-inline" style="width: 350px;">
                                                    <input type="text" id="userSearchInput" placeholder="搜索用户名或邀请码" class="layui-input">
                                                </div>
                                                <button type="button" class="layui-btn" id="userSearchBtn">
                                                    <i class="fa fa-search"></i> 搜索
                                                </button>
                                            </div>
                                            
                                            <div class="permission-tip" style="margin-bottom: 15px;">
                                                <p><i class="fa fa-info-circle"></i> 选择说明：</p>
                                                <p><i class="fa fa-check"></i> 被选中的普通账号及其邀请的用户数据将对该管理员可见</p>
                                                <p><i class="fa fa-check"></i> 未选中的普通账号所邀请的用户数据将对该管理员不可见</p>
                                            </div>
                                            
                                            <div class="layui-form" id="userSelectForm" style="max-height: 250px; overflow-y: auto;">
                                                ${generateUserList(res.data, assignedInvites)}
                                            </div>
                                            
                                            <div style="margin-top: 18px; text-align: right; font-size: 14px; color: #4b5563;">
                                                <i class="fa fa-check-circle" style="color: #10b981;"></i> 已选择: <span id="userSelectedCount" style="font-weight: 600; color: #10b981;">0</span> 个用户
                                            </div>
                                            
                                            <div class="permission-actions">
                                                <button type="button" class="layui-btn layui-btn-primary" onclick="layer.closeAll('page')">
                                                    <i class="fa fa-times"></i> 取消
                                                </button>
                                                <button type="button" class="layui-btn" id="userSelectAllBtn">
                                                    <i class="fa fa-check-square-o"></i> 全选
                                                </button>
                                                <button type="button" class="layui-btn layui-btn-warm" id="userDeselectAllBtn">
                                                    <i class="fa fa-square-o"></i> 取消全选
                                                </button>
                                                <button type="button" class="layui-btn layui-btn-normal" id="saveUserSelectBtn">
                                                    <i class="fa fa-save"></i> 保存选择
                                                </button>
                                            </div>
                                        </div>
                                    `,
                                    success: function() {
                                        layui.form.render(); // 重新渲染表单元素
                                        updateUserSelectedCount(); // 更新初始选中数量
                                        
                                        // 搜索功能
                                        $('#userSearchBtn, #userSearchInput').on('keyup click', function(e) {
                                            if(e.type === 'keyup' && e.keyCode !== 13 && this.id === 'userSearchInput') {
                                                return;
                                            }
                                            
                                            var keyword = $('#userSearchInput').val().toLowerCase();
                                            
                                            $('.user-select-item').each(function() {
                                                var text = $(this).text().toLowerCase();
                                                if(text.indexOf(keyword) > -1) {
                                                    $(this).show();
                                                } else {
                                                    $(this).hide();
                                                }
                                            });
                                        });
                                        
                                        // 全选
                                        $('#userSelectAllBtn').on('click', function() {
                                            $('.user-select-item input[type="checkbox"]').prop('checked', true);
                                            layui.form.render('checkbox');
                                            updateUserSelectedCount();
                                        });
                                        
                                        // 取消全选
                                        $('#userDeselectAllBtn').on('click', function() {
                                            $('.user-select-item input[type="checkbox"]').prop('checked', false);
                                            layui.form.render('checkbox');
                                            updateUserSelectedCount();
                                        });
                                        
                                        // 保存选择
                                        $('#saveUserSelectBtn').on('click', function() {
                                            var selectedInvites = [];
                                            $('.user-select-item input[type="checkbox"]:checked').each(function() {
                                                selectedInvites.push($(this).val());
                                            });
                                            
                                            // 发送AJAX请求保存分配的邀请码
                                            $.ajax({
                                                url: "<?php echo url('admin/admin/assignInvites'); ?>",
                                                type: 'post',
                                                data: {
                                                    id: adminId,
                                                    assigned_invites: selectedInvites.join(',')
                                                },
                                                success: function(res) {
                                                    if(res.code == 1) {
                                                        showStatusToast({
                                                            type: 'success',
                                                            title: '分配成功',
                                                            message: res.msg || '邀请码已成功分配'
                                                        });
                                                        layer.closeAll('page');
                                                        setTimeout(function() {
                                                            location.reload();
                                                        }, 1500);
                                                    } else {
                                                        showStatusToast({
                                                            type: 'error',
                                                            title: '分配失败',
                                                            message: res.msg || '邀请码分配失败'
                                                        });
                                                    }
                                                },
                                                error: function() {
                                                    showStatusToast({
                                                        type: 'error',
                                                        title: '网络错误',
                                                        message: '请检查网络连接并重试'
                                                    });
                                                }
                                            });
                                        });
                                        
                                        // 监听复选框变化
                                        layui.form.on('checkbox', function() {
                                            updateUserSelectedCount();
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        showStatusToast({
                            type: 'error',
                            title: '获取失败',
                            message: res.msg || '获取用户列表失败'
                        });
                    }
                }
            });
        }
        
        // 生成用户列表HTML
        function generateUserList(users, assignedInvites) {
            if(!users || users.length === 0) {
                return '<div style="padding: 30px; text-align: center; color: #777; background: #f9fafb; border-radius: 8px;"><i class="fa fa-info-circle" style="font-size: 20px; margin-bottom: 10px; display: block; color: #94a3b8;"></i> 暂无普通账号用户数据</div>';
            }
            
            var html = '';
            for(var i = 0; i < users.length; i++) {
                var user = users[i];
                var isChecked = assignedInvites.includes(user.invite_code) ? 'checked' : '';
                
                html += `
                    <div class="user-select-item" style="display: flex; align-items: center; padding: 12px; border-bottom: 1px solid #f0f0f0;">
                        <input type="checkbox" name="users[]" value="${user.invite_code}" title="" lay-skin="primary" ${isChecked}>
                        <div style="flex: 1; margin-left: 10px;">
                            <div style="font-weight: 600; color: #374151;">${user.nickname} <span style="font-weight: normal; color: #6b7280;">(${user.name})</span></div>
                            <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                                <span style="background: #e5e7eb; padding: 2px 6px; border-radius: 4px; font-family: monospace; color: #4b5563;">
                                    <i class="fa fa-qrcode" style="margin-right: 4px; color: #8b5cf6;"></i>${user.invite_code}
                                </span>
                            </div>
                        </div>
                        <div style="margin-left: 10px; padding: 3px 8px; border-radius: 4px; font-size: 12px; background-color: ${user.status == 1 ? '#d1e7dd' : '#f8d7da'}; color: ${user.status == 1 ? '#0f5132' : '#842029'}; font-weight: 500;">
                            <i class="fa fa-${user.status == 1 ? 'check-circle' : 'times-circle'}" style="margin-right: 3px;"></i>
                            ${user.status == 1 ? '正常' : '禁用'}
                        </div>
                    </div>
                `;
            }
            return html;
        }
        
        // 更新已选择用户数量
        function updateUserSelectedCount() {
            var count = $('.user-select-item input[type="checkbox"]:checked').length;
            $('#userSelectedCount').text(count);
        }

        // 初始化角色筛选按钮功能
        $(document).ready(function() {
            // 计算并更新各角色人数
            updateRoleCounts();
            
            // 角色筛选按钮点击事件
            $('.role-filter').on('click', function() {
                // 激活当前按钮
                $('.role-filter').removeClass('active');
                $(this).addClass('active');
                
                var roleType = $(this).data('role');
                
                // 筛选表格
                if (roleType === 'all') {
                    $('.admin-table tbody tr').show();
                } else {
                    $('.admin-table tbody tr').hide();
                    $('.admin-table tbody tr').each(function() {
                        var $userTypeEl = $(this).find('.user-type');
                        var isMatch = false;
                        
                        if (roleType === 'super_admin' && $userTypeEl.hasClass('super-admin')) {
                            isMatch = true;
                        } else if (roleType === 'admin' && $userTypeEl.hasClass('admin')) {
                            isMatch = true;
                        } else if (roleType === 'user' && $userTypeEl.hasClass('normal')) {
                            isMatch = true;
                        }
                        
                        if (isMatch) {
                            $(this).show();
                        }
                    });
                }
            });
            
            // 初始化表格按角色类型排序
            sortTableByRoleType();
        });
        
        // 计算并更新各角色人数
        function updateRoleCounts() {
            var superAdminCount = $('.admin-table .user-type.super-admin').length;
            var adminCount = $('.admin-table .user-type.admin').length;
            var userCount = $('.admin-table .user-type.normal').length;
            var totalCount = superAdminCount + adminCount + userCount;
            
            $('.role-filter[data-role="all"] .role-count').text(totalCount);
            $('.role-filter[data-role="super_admin"] .role-count').text(superAdminCount);
            $('.role-filter[data-role="admin"] .role-count').text(adminCount);
            $('.role-filter[data-role="user"] .role-count').text(userCount);
        }
        
        // 按角色类型排序表格
        function sortTableByRoleType() {
            var tbody = $('.admin-table tbody');
            var rows = tbody.find('tr').get();
            
            rows.sort(function(a, b) {
                var roleA = getRoleWeight($(a).find('.user-type'));
                var roleB = getRoleWeight($(b).find('.user-type'));
                
                // 先按角色权重排序
                if (roleA !== roleB) {
                    return roleA - roleB;
                }
                
                // 角色相同时按ID排序
                var idA = parseInt($(a).find('td:first').text());
                var idB = parseInt($(b).find('td:first').text());
                return idA - idB;
            });
            
            $.each(rows, function(index, row) {
                tbody.append(row);
            });
        }
        
        // 获取角色权重（用于排序）
        function getRoleWeight(roleEl) {
            if (roleEl.hasClass('super-admin')) return 1;
            if (roleEl.hasClass('admin')) return 2;
            return 3; // 普通账号
        }

        // 显示状态通知弹窗
        function showStatusToast(options) {
            var $toast = $('#statusToast');
            var icon = options.type === 'success' ? 'fa-check-circle' : 'fa-times-circle';
            var background = options.type === 'success' ? 
                'linear-gradient(145deg, #4CAF50, #2E7D32)' : 
                'linear-gradient(145deg, #f44336, #d32f2f)';
            
            // 设置图标
            $toast.find('.status-toast-icon i').attr('class', 'fa ' + icon);
            
            // 设置标题和消息
            $toast.find('.status-toast-title').text(options.title);
            $toast.find('.status-toast-message').text(options.message);
            
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
            }, 3000);
        }
    </script>
    
    <!-- 账号状态检查脚本 -->
    <script src="/static/admin/js/account-status-monitor.js"></script>
</body>
</html> 