<?php /*a:1:{s:58:"/www/wwwroot/154.64.230.166/app/admin/view/appv1/user.html";i:1752850106;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>通讯录管理系统</title>
		<meta name="renderer" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<script src="/static/jquery/jquery.min.js"></script>
		<link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
		<link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css"
		media="all" />
		<link rel="stylesheet" href="/static/css/admin.css" media="all">
<link rel="stylesheet" href="/static/night-mode/night-mode.css" media="all">
		<!-- 引入移动端适配CSS文件 -->
		<link rel="stylesheet" href="/app/admin/view/appv1/mobile.css" media="all">
		<!-- 添加layui.js引用 -->
		<script src="/static/layui/layui.js"></script>
		<style type="text/css">
			/* 自然过渡基础样式 */
			body, body * {
				transition: background-color 0.3s ease, 
							color 0.3s ease, 
							border-color 0.3s ease, 
							box-shadow 0.3s ease !important;
			}
			
			/* 整体布局样式 */
			.layui-layout-admin .layui-logo {
				background-color: #222d32;
				color: #fff;
				font-weight: bold;
				font-size: 14px;
				line-height: 50px;
				text-align: center;
				box-shadow: none;
				width: 200px;
				height: 50px;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			
			/* 隐藏顶部黑色导航栏 */
			.layui-header {
				display: none !important;
			}
			
			/* 调整主内容区域，由于没有顶部导航栏，调整位置 */
			.layui-body {
				top: 0 !important;
				left: 240px !important; /* 修改：与侧边栏宽度保持一致 */
			}
			
			/* 左侧菜单也需要调整，从顶部开始 */
			.layui-side {
				top: 0 !important;
				box-shadow: none;
				width: 240px !important; /* 修改：减小侧边栏宽度 */
				transition: all 0.3s;
				overflow: hidden; /* 添加：防止内容溢出 */
			}
			
			/* 版权信息区域位置也需要调整 */
			.layui-footer {
				bottom: 0 !important;
				left: 240px !important; /* 修改：与侧边栏宽度保持一致 */
			}
			
			.layui-layout-admin .layui-logo i {
				margin-right: 6px;
				font-size: 16px;
			}
			
			/* 左侧菜单样式 */
			.layui-side-menu {
				background-color: #1e1e2d;
				width: 240px !important; /* 减小侧边栏宽度 */
				padding-top: 10px;
				padding-bottom: 20px;
				padding-left: 10px; /* 添加：左侧内边距，与黑色背景有间距 */
				padding-right: 10px; /* 添加：右侧内边距，与黑色背景有间距 */
			}
			
			/* 夜间模式开关样式 */
			.night-mode-toggle {
				display: flex;
				align-items: center;
				margin-right: 15px;
				background-color: rgba(255, 255, 255, 0.1);
				padding: 5px 10px;
				border-radius: 20px;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
				transition: background-color 0.3s ease;
			}
			
			body.night-mode .night-mode-toggle {
				background-color: rgba(0, 0, 0, 0.2);
			}
			
			.mode-label {
				font-size: 12px;
				margin: 0 5px;
				color: #666;
				transition: color 0.3s ease;
			}
			
			/* 开关样式 */
			.switch {
				position: relative;
				display: inline-block;
				width: 50px;
				height: 24px;
				margin: 0 5px;
			}
			
			.switch input {
				opacity: 0;
				width: 0;
				height: 0;
			}
			
			.slider {
				position: absolute;
				cursor: pointer;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				background-color: #ccc;
				transition: .4s;
			}
			
			.slider:before {
				position: absolute;
				content: "";
				height: 18px;
				width: 18px;
				left: 3px;
				bottom: 3px;
				background-color: white;
				transition: .4s;
				box-shadow: 0 1px 3px rgba(0,0,0,0.3);
			}
			
	input:checked + .slider {
    background: linear-gradient(135deg, #4CAF50, #81C784); /* 修改：改为绿色渐变 */
}

input:focus + .slider {
    box-shadow: 0 0 1px #4CAF50; /* 修改：改为绿色阴影 */
}

input:checked + .slider:before {
    transform: translateX(26px);
}

.slider.round {
    border-radius: 34px;
}

.slider.round:before {
    border-radius: 50%;
}

			
			/* 夜间模式主题 */
			body.night-mode {
				background-color: #1a1a2e;
				color: #e6e6e6;
			}
			
			body.night-mode .layui-body {
				background-color: #1a1a2e;
			}
			
			body.night-mode .main-content {
				background-color: #1f1f3a;
				color: #e6e6e6;
				box-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
			}
			
			body.night-mode .top-controls-bar {
				background-color: #272741;
				border-color: #333355;
			}
			
			body.night-mode .page-select {
				background-color: #2c2c4a;
				color: #e6e6e6;
				border-color: #3e3e5e;
			}
			
			body.night-mode .notice-tag {
				background-color: #272741;
				color: #e6e6e6;
			}
			
			body.night-mode .search-container input {
				background-color: #2c2c4a;
				color: #e6e6e6;
				border-color: #3e3e5e;
			}
			
			body.night-mode .user-info-container {
				background-color: #272741;
				border-color: #333355;
			}
			
			body.night-mode .user-name {
				color: #e6e6e6;
			}
			
			body.night-mode .layui-table {
				background-color: #1f1f3a;
				color: #e6e6e6;
			}
			
			body.night-mode .layui-table td {
				border-color: #333355;
			}
			
			body.night-mode .layui-table tbody tr:hover {
				background-color: #292952;
			}
			
			body.night-mode .layui-footer {
				background-color: #1f1f3a;
				color: #999;
				border-top: 1px solid #333355;
			}
			
			body.night-mode .header-panel {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
			}
			
			body.night-mode .relative-item-modal,
			body.night-mode .app-item-modal {
				background-color: #272741;
				color: #e6e6e6;
			}
			
			body.night-mode .modal-content,
			body.night-mode .remark-modal-content {
				background-color: #1f1f3a;
				color: #e6e6e6;
			}
			
			body.night-mode .modal-body,
			body.night-mode .remark-modal-body {
				background-color: #1f1f3a;
				color: #e6e6e6;
			}
			
			body.night-mode .remark-textarea {
				background-color: #2c2c4a;
				color: #e6e6e6;
				border-color: #3e3e5e;
			}
			
			body.night-mode .edit-remark {
				background-color: #272741;
				color: #e6e6e6;
			}
			
			body.night-mode .layui-layer-content {
				background-color: #1f1f3a;
				color: #e6e6e6;
			}
			
			body.night-mode .pagination > li > a,
			body.night-mode .pagination > li > span {
				background-color: #272741;
				color: #e6e6e6;
			}
			
			body.night-mode .pagination-info {
				background-color: #272741;
				color: #e6e6e6;
			}
			
			body.night-mode .stat-card {
				box-shadow: 0 3px 10px rgba(0, 0, 0, 0.25);
			}
			
			body.night-mode .mode-label {
				color: #e6e6e6;
			}
			
			body.night-mode .batch-actions {
				background-color: #1f1f3a;
				border-color: #333355;
			}
			
			.layui-nav-tree {
				background-color: transparent;
				width: 220px !important; /* 修改：适应侧边栏内边距 */
				padding: 0;
			}
			
			.layui-nav-tree .layui-nav-item {
				margin-bottom: 5px;
				border-radius: 6px; /* 添加：增加圆角 */
				overflow: hidden;
				background-color: transparent;
				border-bottom: none;
				transition: all 0.3s;
				padding: 0;
				width: 100%;
				position: relative; /* 用于阴影定位 */
			}
			
			.layui-nav-tree .layui-nav-item a {
				padding: 14px 15px;
				border-radius: 6px; /* 添加：增加圆角 */
				margin: 0;
				color: #eee !important;
				transition: all 0.3s;
				font-size: 14px;
				background-color: rgba(50, 50, 72, 0.5); /* 修改：半透明背景 */
				height: auto;
				line-height: 20px;
				overflow: hidden;
				border-left: none;
				display: flex;
				align-items: center;
				box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15); /* 修改：增强阴影效果 */
				border: none;
				width: 100%;
				box-sizing: border-box;
				text-overflow: ellipsis; /* 文本溢出时显示省略号 */
				white-space: nowrap; /* 防止文本换行 */
				position: relative; /* 用于伪元素定位 */
				z-index: 1; /* 确保内容在阴影上方 */
			}
			
			/* 添加：菜单按钮阴影遮罩效果 */
			.layui-nav-tree .layui-nav-item a:before {
				content: "";
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: linear-gradient(to bottom, rgba(255, 255, 255, 0.03), rgba(0, 0, 0, 0.1)); /* 添加：顶部到底部的渐变遮罩 */
				opacity: 0; /* 默认不显示 */
				transition: all 0.3s;
				z-index: -1;
			}
			
			.layui-nav-tree .layui-nav-item a:hover {
				background-color: #2c2c40;
				transform: translateX(2px); /* 添加：悬停时轻微右移 */
				color: #fff !important;
				border: none;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15); /* 添加：悬停时增强阴影 */
			}
			
			.layui-nav-tree .layui-nav-item a:hover:before {
				opacity: 1; /* 悬停时显示遮罩 */
			}
			
			/* 当前选中项和悬停效果 */
			.layui-nav-tree .layui-nav-item.layui-this > a,
			.layui-nav-tree .layui-nav-item.layui-nav-itemed > a {
				background-color: #2c2c40;
				color: #fff !important;
				font-weight: normal;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* 添加：选中项阴影 */
				border: none;
			}
			
			/* 添加：选中的菜单项边缘发光效果 */
			.layui-nav-tree .layui-nav-item.layui-this > a:after {
				content: "";
				position: absolute;
				left: 0;
				top: 0;
				bottom: 0;
				width: 3px;
				background: linear-gradient(to bottom, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				box-shadow: 0 0 8px rgba(255, 215, 0, 0.5); /* 修改：改为金色发光效果 */
			}
			
			.layui-nav-tree .layui-nav-child {
				background-color: #262c42;
				padding: 0;
				margin-top: 5px; /* 添加：与父菜单间隔 */
				width: 100%; /* 确保子菜单宽度正确 */
				box-shadow: inset 0 5px 5px -5px rgba(0, 0, 0, 0.3), 
				            inset 0 -5px 5px -5px rgba(0, 0, 0, 0.3); /* 内阴影效果 */
				border-radius: 6px; /* 添加：子菜单圆角 */
				overflow: hidden; /* 添加：确保圆角效果 */
			}
			
			.layui-nav-tree .layui-nav-child a {
				padding: 10px 15px 10px 35px;
				font-size: 13px;
				margin: 0;
				height: auto;
				line-height: 20px;
				background-color: transparent;
				border-radius: 0;
				color: #eee !important;
				box-shadow: none;
				width: 100%;
				box-sizing: border-box;
				white-space: nowrap; /* 防止文本换行 */
				overflow: hidden; /* 隐藏溢出内容 */
				text-overflow: ellipsis; /* 显示省略号 */
				position: relative; /* 添加：用于伪元素定位 */
				z-index: 1; /* 添加：确保内容在遮罩上方 */
				transition: all 0.3s; /* 添加：过渡效果 */
			}
			
			/* 添加：子菜单项的悬停效果 */
			.layui-nav-tree .layui-nav-child a:hover {
				background-color: rgba(44, 44, 64, 0.6);
				color: #fff !important;
				padding-left: 38px; /* 添加：悬停时轻微右移效果 */
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* 添加：轻微阴影 */
			}
			
			/* 添加：子菜单项的阴影遮罩 */
			.layui-nav-tree .layui-nav-child a:before {
				content: "";
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: linear-gradient(to right, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0));
				opacity: 0;
				transition: all 0.3s;
				z-index: -1;
			}
			
			.layui-nav-tree .layui-nav-child a:hover:before {
				opacity: 1;
			}
			
			.layui-nav-tree .layui-nav-child dd {
				width: 100%; /* 确保子菜单项宽度正确 */
				position: relative; /* 添加：用于定位 */
			}
			
			.layui-nav-tree .layui-nav-child dd.layui-this a {
				background-color: #1e1e2d;
				color: #fff !important;
				border-left: 3px solid #4CAF50; /* 修改：改为金色边框 */
				padding-left: 32px; /* 调整：考虑边框宽度 */
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2); /* 添加：选中的子菜单项阴影 */
			}
			
			.layui-nav-tree .layui-nav-item a i {
				margin-right: 10px;
				font-size: 16px;
				width: 20px;
				text-align: center;
				color: #eee;
				flex-shrink: 0; /* 防止图标缩小 */
			}
			
			.layui-nav-tree .layui-nav-child a i {
				font-size: 14px;
				color: #eee;
				flex-shrink: 0; /* 防止图标缩小 */
			}
			
			/* 主内容区域样式 */
			.layui-body {
				background-color: #f4f6f9;
				padding: 15px;
				overflow-y: auto;
				left: 240px; /* 修改：与侧边栏宽度保持一致 */
			}
			
			.main-content {
				background-color: #fff;
				border-radius: 5px;
				box-shadow: 0 1px 3px rgba(0,0,0,.1);
				padding: 15px;
				min-height: calc(100vh - 100px);
			}
			
			/* 顶部导航样式 */
			.layui-header {
				background-color: #3c8dbc;
				box-shadow: none;
				height: 50px;
			}
			
			.header-right {
				position: absolute;
				right: 20px;
				top: 0;
			}
			
			.header-right .layui-nav {
				background-color: transparent;
			}
			
			.header-right .layui-nav .layui-nav-item a {
				color: #333;
			}
			
			/* 版权信息样式 */
			.layui-footer {
				background-color: #fff;
				color: #666;
				text-align: center;
				border-top: 1px solid #eee;
				height: 44px;
				line-height: 44px;
				padding: 0;
				left: 260px;
			}
			
			/* 头部面板样式 */
			.header-panel {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				color: #fff;
				padding: 12px 15px;
				border-radius: 3px;
				margin-bottom: 15px;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			
			.header-title {
				font-size: 16px;
				font-weight: 500;
			}
			
			.header-version {
				font-size: 13px;
				display: flex;
				align-items: center;
				gap: 15px;
			}
			
			/* 语言切换器样式 */
			.language-switcher {
				display: inline-block;
				vertical-align: middle;
			}
			
			.language-dropdown {
				position: relative;
				display: inline-block;
			}
			
			.language-current {
				background: rgba(255, 255, 255, 0.2);
				color: #fff;
				border: none;
				border-radius: 4px;
				padding: 3px 10px;
				cursor: pointer;
				font-size: 12px;
				display: flex;
				align-items: center;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
				transition: all 0.3s ease;
			}
			
			.language-current:hover {
				background: rgba(255, 255, 255, 0.3);
			}
			
			.language-current i.fa-globe {
				margin-right: 5px;
			}
			
			.language-current i.fa-caret-down {
				margin-left: 5px;
				font-size: 10px;
				opacity: 0.8;
			}
			
			.language-dropdown-content {
				display: none;
				position: absolute;
				background-color: #fff;
				min-width: 200px;
				box-shadow: 0 8px 16px rgba(0,0,0,0.2);
				padding: 5px 0;
				z-index: 9999;
				max-height: 300px;
				overflow-y: auto;
				border-radius: 4px;
				right: 0;
				margin-top: 5px;
			}
			
			.search-language {
				padding: 8px 10px;
				border-bottom: 1px solid #eee;
			}
			
			.search-language input {
				width: 100%;
				padding: 6px 8px;
				border: 1px solid #ddd;
				border-radius: 3px;
				box-sizing: border-box;
			}
			
			.language-list a {
				display: block;
				padding: 8px 15px;
				text-decoration: none;
				color: #333;
				transition: background-color 0.2s;
			}
			
			.language-list a:hover {
				background-color: #f5f5f5;
			}
			
			/* 顶部控制条样式优化 */
			.top-controls-bar {
				display: flex;
				justify-content: space-between;
				align-items: center;
				background-color: #f5f5f5;
				padding: 8px 12px;
				border-radius: 5px;
				margin-bottom: 15px;
				border: 1px solid #eee;
			}
			
			.page-selector {
				display: flex;
				align-items: center;
			}
			
			.page-select {
				background: white;
				border: 1px solid #ddd;
				padding: 5px 10px;
				border-radius: 4px;
				font-size: 12px;
				cursor: pointer;
				margin-right: 15px;
				outline: none;
			}
			
			.notice-tags {
				display: flex;
				gap: 10px;
			}
			
			.notice-tag {
				background: rgba(255, 215, 0, 0.1); /* 修改：改为金色背景 */
				color: #4CAF50; /* 修改：改为金色文字 */
				padding: 4px 10px;
				border-radius: 30px;
				font-size: 12px;
				display: flex;
				align-items: center;
				gap: 5px;
			}
			
			.notice-tag i {
				color: #4CAF50; /* 修改：改为金色图标 */
			}
			
			/* 搜索状态标签样式 */
			.search-active {
				background: rgba(245, 166, 35, 0.15);
				color: #f5a623;
				font-weight: 500;
			}
			
			.search-active i {
				color: #f5a623;
			}
			
			.search-active span {
				padding: 0 3px;
				background: rgba(245, 166, 35, 0.1);
				border-radius: 3px;
				margin-left: 3px;
			}
			
			.stats-badges {
				display: flex;
				align-items: center;
				justify-content: flex-end;
				flex: 1;
				gap: 15px;
			}
			
			.search-container {
				position: relative;
				display: flex;
				align-items: center;
				width: 300px;
				box-sizing: border-box;
			}
			
			.search-container * {
				box-sizing: border-box;
			}
			
			.search-container input {
				width: 100%;
				padding: 8px 15px;
				border: 1px solid #ddd;
				border-radius: 30px;
				font-size: 12px;
				outline: none;
				transition: all 0.3s;
				padding-right: 65px;
				box-sizing: border-box;
				height: 32px;
				line-height: normal;
			}
			
			.search-container input:focus {
				border-color: #4CAF50; /* 修改：改为金色边框 */
				box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1); /* 修改：改为金色阴影 */
			}
			
			.search-container button {
				position: absolute;
				right: 4px; /* 调整到搜索框内部右侧 */
				top: 3px;
				width: 28px;
				height: 28px;
				border-radius: 50%;
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				color: white;
				border: none;
				display: flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
			}
			
			.search-container button i {
				font-size: 12px;
				display: flex;
				align-items: center;
				justify-content: center;
				width: 100%;
				height: 100%;
				margin: 0;
				padding: 0;
			}
			
			.search-container button:hover {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
			}
			
			/* 添加清除搜索按钮样式 */
			.clear-search {
				position: absolute;
				right: 36px;
				top: 50%;
				transform: translateY(-50%);
				background: #f0f0f0;
				color: #666;
				border: none;
				width: 24px;
				height: 24px;
				min-width: 24px;
				min-height: 24px;
				line-height: 0;
				border-radius: 50%;
				display: none; /* 默认隐藏 */
				align-items: center;
				justify-content: center;
				cursor: pointer;
				transition: all 0.3s;
				text-decoration: none;
				z-index: 2;
				font-size: 0;
				overflow: hidden;
			}
			
			.clear-search i {
				font-size: 12px;
				display: flex;
				align-items: center;
				justify-content: center;
				width: 100%;
				height: 100%;
			}
			
			/* 当搜索框有内容时显示清除按钮 */
			.search-container input:not([value=""]) ~ .clear-search,
			.search-container input:focus ~ .clear-search {
				display: flex;
			}
			
			.clear-search:hover {
				background: #e0e0e0;
				color: #333;
			}
			
			/* 表格样式优化 */
			.layui-table {
				margin-top: 15px;
				box-shadow: 0 1px 3px rgba(0,0,0,.1);
				border-radius: 10px;
				overflow: hidden;
			}
			
			.layui-table thead tr th {
				background: linear-gradient(to right, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				color: white;
				font-weight: 500;
				text-align: center;
				font-size: 13px;
				padding: 12px 8px;
				letter-spacing: 1px;
			}
			
			.layui-table tbody tr td {
				text-align: center;
				vertical-align: middle;
				padding: 15px 10px !important;
				font-size: 12px;
				border-bottom: 1px solid #f0f0f0;
			}
			
			.layui-table tbody tr:hover {
				background-color: #fffbf0; /* 修改：改为金色背景 */
			}
			
			/* 按钮样式 */
			.operation-btn {
				margin: 3px;
				border-radius: 6px;
				font-size: 12px;
				transition: all 0.3s ease;
				padding: 6px 12px;
				font-weight: 500;
				box-shadow: 0 2px 4px rgba(0,0,0,0.1);
				letter-spacing: 0.5px;
				border: none;
			}
			
			.operation-btn:hover {
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(0,0,0,0.15);
			}
			
			.btn-blue {
				background: linear-gradient(135deg, #1e90ff, #70a1ff);
			}
			
			.btn-green {
				background: linear-gradient(135deg, #2dce89, #4cd3a5);
			}
			
			.btn-purple {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
			}
			
			.btn-orange {
				background: linear-gradient(135deg, #f5a623, #ff9500);
			}
			
			.btn-red {
				background: linear-gradient(135deg, #f5365c, #ff6b81);
			}
			
			/* 搜索表单样式 */
			.search-form {
				background-color: #f8f9fa;
				padding: 15px;
				border-radius: 5px;
				margin-bottom: 15px;
			}
			
			.search-input {
				border-radius: 4px;
			}
			
			/* 操作按钮组容器样式 */
			.operation-menu .layui-btn-group {
				display: flex;
				flex-wrap: wrap;
				justify-content: center;
			}
			
			/* 时间地址样式 */
			.time-address {
				display: flex;
				flex-direction: column;
				gap: 8px;
			}
			
			.date-time {
				display: flex;
				flex-direction: column;
				gap: 4px;
			}
			
			.date, .time {
				display: flex;
				align-items: center;
				gap: 6px;
				font-size: 12px;
				color: #444;
			}
			
			.date i, .time i {
				color: #4CAF50; /* 修改：改为金色 */
				width: 14px;
				font-size: 12px;
			}
			
			.location {
				display: flex;
				flex-direction: column;
				gap: 4px;
			}
			
			.ip-address {
				display: flex;
				align-items: center;
				gap: 6px;
				font-size: 12px;
				color: #444;
			}
			
			.ip-address i {
				color: #4CAF50; /* 修改：改为金色 */
				width: 14px;
				font-size: 12px;
			}
			
			.address-text {
				font-size: 12px;
				color: #666;
				padding-left: 20px;
			}
			
			.detailed-location {
				font-size: 12px;
				color: #0066cc;
				padding-left: 20px;
			}
			
			.detailed-location i {
				color: #0066cc;
				width: 14px;
				font-size: 12px;
				margin-right: 4px;
			}
			
			/* 箭头图标样式 */
			.layui-nav .layui-nav-mored,
			.layui-nav-itemed>a .layui-nav-more {
				border-color: transparent transparent #fff;
				transform: rotate(180deg);
			}
			
			.layui-nav .layui-nav-more {
				display: inline-block;
				position: absolute;
				right: 15px;
				top: 50%;
				margin-top: -2px;
				width: 0;
				height: 0;
				border-style: solid;
				border-width: 5px 5px 0;
				border-color: #fff transparent transparent;
				transition: all .3s;
			}
			
			/* 重新定义子菜单样式 */
			.layui-nav-tree .layui-nav-child dd {
				padding: 0;
				position: relative;
				background-color: #28283a;
			}
			
			.layui-nav-itemed>.layui-nav-child {
				display: block;
				padding: 0;
				background-color: #262c42 !important;
				border-radius: 0;
				margin-top: 0;
				width: 100%;
			}
			
			/* 特殊菜单项样式 */
			.layui-nav-tree .layui-nav-item.special-menu a {
				color: #ffffff !important;
				transition: all 0.3s ease;
				background: linear-gradient(to right, #ff9800, #ffb74d); /* 修改：使用渐变背景 */
				border: none;
				font-weight: 600;
				box-shadow: 0 2px 8px rgba(255, 152, 0, 0.3); /* 添加：外阴影效果 */
				width: 100%;
				position: relative; /* 添加：用于伪元素定位 */
				overflow: hidden; /* 添加：防止伪元素溢出 */
				z-index: 1; /* 添加：确保内容在遮罩上方 */
			}
			
			/* 添加：特殊菜单阴影遮罩效果 */
			.layui-nav-tree .layui-nav-item.special-menu a:before {
				content: "";
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15)); /* 添加：顶部到底部的渐变遮罩 */
				z-index: -1;
			}
			
			.layui-nav-tree .layui-nav-item.special-menu a:hover {
				color: #ffffff !important;
				transform: translateY(-1px); /* 修改：轻微上移效果 */
				background: linear-gradient(to right, #ffb74d, #ffa726); /* 修改：使用亮色渐变 */
				border: none;
				box-shadow: 0 4px 10px rgba(255, 152, 0, 0.4); /* 修改：增强阴影效果 */
			}
			
			.layui-nav-tree .layui-nav-item.purple-menu a {
				color: #ffffff !important;
				transition: all 0.3s ease;
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：使用金色渐变 */
				border: none;
				font-weight: 600;
				box-shadow: 0 2px 8px rgba(255, 215, 0, 0.3); /* 修改：金色阴影效果 */
				width: 100%;
				position: relative; /* 添加：用于伪元素定位 */
				overflow: hidden; /* 添加：防止伪元素溢出 */
				z-index: 1; /* 添加：确保内容在遮罩上方 */
			}
			
			/* 添加：金色菜单阴影遮罩效果 */
			.layui-nav-tree .layui-nav-item.purple-menu a:before {
				content: "";
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15)); /* 添加：顶部到底部的渐变遮罩 */
				z-index: -1;
			}
			
			.layui-nav-tree .layui-nav-item.purple-menu a:hover {
				color: #ffffff !important;
				transform: translateY(-1px); /* 修改：轻微上移效果 */
				background: linear-gradient(to right, #4CAF50, #fff176); /* 修改：使用亮金色渐变 */
				border: none;
				box-shadow: 0 4px 10px rgba(255, 215, 0, 0.4); /* 修改：增强金色阴影效果 */
			}
			
			/* 动画效果重置 */
			.layui-nav-tree .layui-nav-item a:hover span {
				transform: none;
			}
			
			.layui-nav-tree .layui-nav-item a:hover i {
				transform: none;
			}
			
			.layui-nav-tree .layui-nav-item.layui-this > a i,
			.layui-nav-tree .layui-nav-item.layui-nav-itemed > a i {
				transform: none;
				color: #eee;
			}
			
			.layui-nav-tree .layui-nav-item.special-menu a:hover i,
			.layui-nav-tree .layui-nav-item.purple-menu a:hover i {
				transform: none;
			}
			
			/* 去除顶部标识 */
			.layui-side-menu:before {
				display: none;
			}
			
			/* 优化功能按钮样式 */
			.function-btn {
				width: 100%;
				border: none;
				border-radius: 4px;
				padding: 6px 0;
				font-size: 12px;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 5px;
				transition: all 0.3s;
				margin-bottom: 5px;
			}
			
			.function-btn.purple {
				background: #4CAF50; /* 修改：改为金色 */
				color: white;
				box-shadow: 0 1px 3px rgba(255, 215, 0, 0.3); /* 修改：金色阴影 */
			}
			
			.function-btn.purple:hover {
				background: #4CAF50; /* 修改：改为亮金色 */
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(255, 215, 0, 0.4); /* 修改：金色阴影 */
			}
			
			.function-btn.blue {
				background: #2196f3;
				color: white;
				box-shadow: 0 1px 3px rgba(33, 150, 243, 0.3);
			}
			
			.function-btn.blue:hover {
				background: #1976d2;
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(33, 150, 243, 0.4);
			}
			
			.function-btn.green {
				background: linear-gradient(135deg, #4cd3a5, #6bdfb8);
			}
			
			.function-btn.red {
				background: linear-gradient(135deg, #f5365c, #ff6b81);
			}
			
			.function-btn i {
				font-size: 14px;
				width: 16px;
				text-align: center;
				margin-right: 4px;
			}
			
			/* 信息样式优化 */
			.info-list {
				display: flex;
				flex-direction: column;
				gap: 8px;
			}
			
			.info-item {
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			.info-item i {
				color: #4CAF50; /* 修改：改为金色 */
				width: 16px;
				text-align: center;
				font-size: 14px;
			}
			
			.info-value {
				font-weight: bold;
			}
			
			.count-value {
				font-weight: bold;
				color: #4CAF50; /* 修改：改为金色 */
				margin: 0 3px;
			}
			
			.date-time-info {
				display: flex;
				flex-direction: column;
				gap: 8px;
			}
			
			.date-info, .time-info, .ip-info {
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			.date-info i, .time-info i, .ip-info i {
				color: #4CAF50; /* 修改：改为金色 */
				width: 16px;
				text-align: center;
				font-size: 14px;
			}
			
			.remark-info {
				display: flex;
				align-items: center;
				gap: 8px;
				margin-top: 8px;
				color: #ff9800;
			}
			
			.remark-info i {
				color: #4CAF50; /* 修改：改为金色 */
				width: 16px;
				text-align: center;
				font-size: 14px;
			}
			
			/* 搜索过滤区域样式 */
			.filter-section {
				display: flex;
				flex-wrap: wrap;
				gap: 12px;
				margin-bottom: 20px;
				background: linear-gradient(to right, #fffbf0, #fff8e1); /* 修改：使用金色渐变背景 */
				padding: 15px;
				border-radius: 10px;
				box-shadow: 0 2px 6px rgba(255, 215, 0, 0.08); /* 修改：金色阴影 */
				border: 1px solid rgba(255, 215, 0, 0.1); /* 修改：金色边框 */
			}
			
			.filter-item {
				flex: 1;
				min-width: 200px;
			}
			
			.filter-input {
				width: 100%;
				height: 38px;
				padding: 8px 15px 8px 35px;
				border: 1px solid #e0e0f0;
				border-radius: 6px;
				font-size: 13px;
				transition: all 0.3s;
				background-color: #fff;
				position: relative;
			}
			
			.filter-item {
				position: relative;
			}
			
			.filter-item i {
				position: absolute;
				left: 12px;
				top: 12px;
				color: #4CAF50; /* 修改：改为金色 */
				z-index: 1;
			}
			
			.filter-input:focus {
				border-color: #4CAF50; /* 修改：改为金色边框 */
				box-shadow: 0 0 6px rgba(255, 215, 0, 0.2); /* 修改：改为金色阴影 */
				outline: none;
			}
			
			.search-btn {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				color: white;
				border: none;
				height: 38px;
				padding: 0 20px;
				border-radius: 6px;
				cursor: pointer;
				font-size: 13px;
				transition: all 0.3s;
				width: 100%;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 8px;
			}
			
			.search-btn:hover {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(255, 215, 0, 0.2); /* 修改：改为金色阴影 */
			}
			
			/* 统计卡片区域 */
			.stats-cards {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
				gap: 15px;
				margin-bottom: 20px;
			}
			
			.stat-card {
				padding: 15px;
				border-radius: 10px;
				color: white;
				display: flex;
				flex-direction: column;
				box-shadow: 0 3px 8px rgba(0,0,0,0.12);
				transition: all 0.3s ease;
				min-height: 90px;
				position: relative;
				overflow: hidden;
			}
			
			.stat-card:hover {
				transform: translateY(-3px);
				box-shadow: 0 5px 15px rgba(0,0,0,0.15);
			}
			
			.stat-card .stat-icon {
				position: absolute;
				right: 15px;
				bottom: 10px;
				font-size: 48px;
				opacity: 0.2;
			}
			
			.stat-card .stat-title {
				font-size: 16px;
				font-weight: 500;
				margin-bottom: 15px;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			.stat-card .stat-title i {
				font-size: 18px;
			}
			
			.stat-card .stat-value {
				font-size: 22px;
				font-weight: bold;
				letter-spacing: 0.5px;
			}
			
			.stat-card .stat-badge {
				background: rgba(255, 255, 255, 0.25);
				padding: 2px 10px;
				border-radius: 20px;
				font-size: 14px;
				margin-left: 8px;
				backdrop-filter: blur(5px);
			}
			
			.stat-card.blue {
				background: linear-gradient(135deg, #1e90ff, #70a1ff);
			}
			
			.stat-card.green {
				background: linear-gradient(135deg, #2dce89, #4cd3a5);
			}
			
			.stat-card.purple {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
			}
			
			.stat-card.orange {
				background: linear-gradient(135deg, #f5a623, #ff9500);
			}
			
			/* 批量操作区域 */
			.batch-actions {
				margin-bottom: 15px;
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding: 15px;
				background: #fff;
				border-radius: 8px;
				box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			}
			
			.action-left {
				display: flex;
				align-items: center;
				gap: 10px;
				flex-wrap: wrap;
			}
			
			.batch-btn {
				padding: 8px 15px;
				border: none;
				border-radius: 6px;
				font-size: 14px;
				font-weight: 500;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 6px;
				transition: all 0.3s ease;
				box-shadow: 0 2px 5px rgba(0,0,0,0.1);
			}
			
			.batch-btn:hover {
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(0,0,0,0.15);
			}
			
			.batch-btn:active {
				transform: translateY(0);
				box-shadow: 0 1px 3px rgba(0,0,0,0.1);
			}
			
			.batch-btn.btn-danger {
				background: linear-gradient(to right, #ff5858, #f09819);
				color: white;
			}
			
			.batch-btn.btn-primary {
				background: linear-gradient(to right, #4a00e0, #8e2de2);
				color: white;
			}
			
			.batch-btn.btn-success {
				background: linear-gradient(to right, #11998e, #38ef7d);
				color: white;
			}
			
			.selected-count {
				font-size: 14px;
				color: #555;
				margin-left: 10px;
				padding: 6px 12px;
				background: #f5f5f5;
				border-radius: 20px;
				font-weight: 500;
				box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
			}
			
			.notice-tag {
				padding: 8px 15px;
				background: #f8f9fa;
				border-radius: 6px;
				font-size: 14px;
				display: flex;
				align-items: center;
				gap: 8px;
				color: #555;
				box-shadow: 0 1px 3px rgba(0,0,0,0.05);
			}
			
			/* 提示弹窗样式现代化 */
			.notification-toast {
				position: fixed;
				top: 20px;
				right: 20px;
				max-width: 350px;
				padding: 16px 20px;
				border-radius: 8px;
				color: white;
				font-weight: 500;
				z-index: 9999;
				display: flex;
				align-items: center;
				box-shadow: 0 4px 12px rgba(0,0,0,0.15);
				animation: slide-in 0.4s ease;
			}
			
			@keyframes slide-in {
				from { transform: translateX(100%); opacity: 0; }
				to { transform: translateX(0); opacity: 1; }
			}
			
			.notification-toast.success {
				background: linear-gradient(to right, #06beb6, #48b1bf);
			}
			
			.notification-toast.error {
				background: linear-gradient(to right, #ff5858, #f09819);
			}
			
			.notification-toast i {
				margin-right: 10px;
				font-size: 18px;
			}
			
			/* 手机号码颜色调整 */
			.phone-number {
				color: #1e90ff;
				font-weight: 500;
			}
			
			.invitation-code {
				color: #ff9800;
				font-weight: 500;
			}
			
			.ip-address-value {
				color: #2dce89;
				font-weight: 500;
			}
			
			/* 顶部显示页数及用户总数样式 */
			.top-controls-bar {
				display: flex;
				justify-content: space-between;
				margin-bottom: 15px;
				align-items: center;
			}
			
			.page-selector {
				display: flex;
				align-items: center;
				gap: 15px;
			}
			
			.page-select {
				height: 34px;
				border: 1px solid #d2d2d2;
				border-radius: 4px;
				padding: 0 10px;
				background-color: #fff;
				font-size: 13px;
				color: #333;
				cursor: pointer;
			}
			
			.page-select:focus {
				border-color: #4CAF50; /* 修改：改为金色边框 */
				outline: none;
			}
			
			.notice-tags {
				display: flex;
				align-items: center;
				gap: 10px;
			}
			
			.stats-badges {
				display: flex;
				align-items: center;
				justify-content: flex-end;
				flex: 1;
				gap: 10px;
			}
			
			.user-count {
				display: flex;
				align-items: center;
				gap: 5px;
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				padding: 6px 12px;
				border-radius: 20px;
				color: white;
				font-size: 13px;
			}
			
			.user-count i {
				font-size: 14px;
			}
			
			.user-count-number {
				background: rgba(255, 255, 255, 0.3);
				padding: 2px 8px;
				border-radius: 10px;
				font-weight: bold;
			}
			
			.search-container {
				position: relative;
			}
			
			.search-container input {
				height: 32px;
				width: 200px;
				border: 1px solid #d2d2d2;
				border-radius: 17px;
				padding: 0 35px 0 15px; /* 右侧留出足够空间放置搜索图标 */
				font-size: 13px;
			}
			
			.search-container input:focus {
				border-color: #4CAF50; /* 修改：改为金色边框 */
				box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.1); /* 修改：改为金色阴影 */
			}
			
			.search-container button {
				position: absolute;
				right: 4px; /* 调整到搜索框内部 */
				top: 2px;
				width: 28px;
				height: 28px;
				border-radius: 50%;
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				color: white;
				border: none;
				display: flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
			}
			
			.search-container button i {
				font-size: 12px;
				display: flex;
				align-items: center;
				justify-content: center;
				width: 100%;
				height: 100%;
				margin: 0;
				padding: 0;
			}
			
			.search-container button:hover {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
			}
			
			/* 弹窗内容样式 */
			.popup-header {
				background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
				color: #fff;
				padding: 12px 20px;
				display: flex;
				justify-content: space-between;
				align-items: center;
				border-top-left-radius: 10px;
				border-top-right-radius: 10px;
			}
			
			.popup-header h3 {
				margin: 0;
				font-size: 16px;
				font-weight: 500;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			.popup-header .total-count {
				background: rgba(255, 255, 255, 0.2);
				padding: 4px 10px;
				border-radius: 20px;
				font-size: 12px;
			}
			
			.popup-actions {
				display: flex;
				gap: 10px;
				margin: 15px 0;
				padding: 0 20px;
			}
			
			.popup-export-btn {
				background: linear-gradient(135deg, #2dce89, #4cd3a5);
				color: white;
				border: none;
				padding: 6px 15px;
				border-radius: 4px;
				cursor: pointer;
				font-size: 12px;
				display: flex;
				align-items: center;
				gap: 5px;
			}
			
			.popup-content {
				padding: 0 20px 20px;
			}
			
			.popup-table {
				width: 100%;
				border-collapse: collapse;
			}
			
			.popup-table th {
				background: #f5f5f5;
				padding: 10px;
				text-align: left;
				font-weight: 500;
				font-size: 13px;
				color: #333;
				border-bottom: 1px solid #eee;
			}
			
			.popup-table td {
				padding: 10px;
				border-bottom: 1px solid #f0f0f0;
				font-size: 13px;
			}
			
			.popup-table tr:hover {
				background-color: #fffbf0; /* 修改：改为金色背景 */
			}
			
			.popup-pagination {
				display: flex;
				justify-content: center;
				margin-top: 20px;
				gap: 5px;
			}
			
			.popup-page-btn {
				width: 30px;
				height: 30px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				background: #f5f5f5;
				cursor: pointer;
				font-size: 12px;
				transition: all 0.2s;
			}
			
			.popup-page-btn:hover {
				background: #e0e0e0;
			}
			
			.popup-page-btn.active {
				background: #4CAF50; /* 修改：改为金色 */
				color: white;
			}
			
			/* 图片网格样式 */
			.image-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
				gap: 15px;
				padding: 20px;
			}
			
			.image-item {
				border-radius: 8px;
				overflow: hidden;
				box-shadow: 0 2px 8px rgba(0,0,0,0.1);
				cursor: pointer;
				transition: all 0.3s;
			}
			
			.image-item:hover {
				transform: translateY(-5px);
				box-shadow: 0 5px 15px rgba(0,0,0,0.15);
			}
			
			.image-item img {
				width: 100%;
				height: 200px;
				object-fit: cover;
				display: block;
			}
			
			/* 弹窗样式优化 */
			.layui-layer-title {
				background: linear-gradient(135deg, #4CAF50, #4CAF50) !important; /* 修改：改为金色渐变 */
				color: #fff !important;
				font-weight: 500 !important;
				border: none !important;
				height: 50px !important;
				line-height: 50px !important;
				padding-left: 20px !important;
				font-size: 16px !important;
				border-radius: 10px 10px 0 0 !important;
			}
			
			.layui-layer-title i {
				margin-right: 8px;
				font-size: 18px;
			}
			
			.layui-layer-rim {
				border: none !important;
				border-radius: 10px !important;
				box-shadow: 0 0 15px rgba(0,0,0,0.15) !important;
			}
			
			.layui-layer-setwin {
				top: 17px !important;
				right: 17px !important;
			}
			
			.layui-layer-setwin a {
				color: rgba(255,255,255,0.8) !important;
				font-size: 14px !important;
				font-weight: normal !important;
				text-decoration: none !important;
			}
			
			.layui-layer-setwin a:hover {
				color: #fff !important;
			}
			
			.layui-layer-iframe iframe {
				border-radius: 0 0 10px 10px;
			}
			
			/* 已安装App列显示样式 */
			.app-list-container {
			  display: flex;
			  flex-direction: column;
			  gap: 5px;
			}
			
			.app-list {
			  display: flex;
			  flex-direction: column;
			  gap: 7px;
			}
			
			.app-item {
			  display: flex;
			  align-items: center;
			  gap: 5px;
			  font-size: 13px;
			}
			
			.app-item i {
			  width: 16px;
			  text-align: center;
			  font-size: 14px;
			}
			
			.bank-app {
			  color: #ff4757;
			  font-weight: 600;
			}
			
			.app-item .fa-cog {
			  color: #2684ff;
			}
			
			.expand-apps-btn {
			  display: inline-flex !important;
			  align-items: center !important;
			  gap: 3px !important;
			  padding: 1px 0 !important;
			  background: transparent !important;
			  border: none !important;
			  text-decoration: none !important;
			  cursor: pointer !important;
			  font-size: 12px !important;
			  color: #4CAF50 !important; /* 修改：改为金色 */
			}
			
			.expand-apps-btn i {
			  font-size: 10px !important;
			  color: #4CAF50 !important; /* 修改：改为金色 */
			}
			
			.expand-apps-btn:hover {
			  opacity: 0.8;
			  text-decoration: none !important;
			}
			
			/* 功能操作按钮样式 */
			.function-btn {
			  padding: 6px 8px;
			  border-radius: 4px;
			  border: none;
			  cursor: pointer;
			  display: flex;
			  align-items: center;
			  gap: 5px;
			  font-size: 12px;
			  transition: all 0.3s;
			  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			  color: white;
			  width: 100%;
			}
			
			.function-btn i {
			  font-size: 14px;
			}
			
			.function-btn.purple {
			  background: linear-gradient(135deg, #4CAF50, #4CAF50); /* 修改：改为金色渐变 */
			}
			
			.function-btn.blue {
			  background: linear-gradient(135deg, #1e90ff, #70a1ff);
			}
			
			.function-btn.green {
			  background: linear-gradient(135deg, #4cd3a5, #6bdfb8);
			}
			
			.function-btn.orange {
			  background: linear-gradient(135deg, #ff9800, #ffb74d);
			}
			
			.function-btn.red {
			  background: linear-gradient(135deg, #f44336, #ef5350);
			}
			
			.function-btn:hover {
			  transform: translateY(-2px);
			  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
			}
			
			/* 增加行间距 */
			.layui-table tbody tr td {
			  padding: 15px 10px !important;
			  vertical-align: top;
			}
			
			/* 拉黑IP按钮样式 */
			.blacklist-ip-btn {
			  background: #ff6363;
			  color: white;
			  border: none;
			  padding: 2px 6px;
			  border-radius: 3px;
			  cursor: pointer;
			  font-size: 12px;
			  display: inline-flex;
			  align-items: center;
			  gap: 3px;
			  margin-left: 5px;
			  transition: all 0.3s;
			  font-weight: 400;
			}
			
			.blacklist-ip-btn:hover {
			  background: #ff4242;
			}
			
			.blacklist-ip-btn i {
			  font-size: 12px;
			}
			
			/* 备注信息样式 */
			.remark-info {
			  display: flex;
			  align-items: center;
			  gap: 5px;
			  margin-top: 8px;
			  background: rgba(0,0,0,0.03);
			  padding: 6px 8px;
			  border-radius: 4px;
			  cursor: pointer;
			  transition: all 0.3s;
			}
			
			.remark-info:hover {
			  background: rgba(0,0,0,0.05);
			}
			
			.remark-info i {
			  color: #4CAF50; /* 修改：改为金色 */
			  width: 16px;
			  text-align: center;
			  font-size: 14px;
			}
			
			.remark-info span {
			  font-size: 12px;
			  color: #666;
			}
			
			.info-list {
			  display: flex;
			  flex-direction: column;
			  gap: 8px;
			}
			
			.info-item {
			  display: flex;
			  align-items: center;
			  gap: 5px;
			}
			
			/* 弹窗提示样式 */
			.data-loading-popup {
				background: #fff;
				border-radius: 10px;
				box-shadow: 0 0 20px rgba(0,0,0,0.2);
				width: 300px;
				overflow: hidden;
			}
			
			.data-loading-header {
				background: #f44336;
				color: white;
				padding: 10px 15px;
				font-size: 16px;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			
			.data-loading-header .close {
				cursor: pointer;
				font-size: 18px;
			}
			
			.data-loading-content {
				padding: 20px;
				text-align: center;
			}
			
			.data-loading-icon {
				color: #f5a623;
				font-size: 36px;
				margin-bottom: 15px;
			}
			
			.data-loading-message {
				font-size: 14px;
				color: #333;
				margin-bottom: 20px;
			}
			
			.data-loading-actions {
				display: flex;
				justify-content: center;
				gap: 10px;
			}
			
			.confirm-btn {
				background: #f44336;
				color: white;
				border: none;
				padding: 8px 20px;
				border-radius: 4px;
				cursor: pointer;
				transition: all 0.3s;
			}
			
			.confirm-btn:hover {
				background: #e53935;
			}
			
			.cancel-btn {
				background: #9e9e9e;
				color: white;
				border: none;
				padding: 8px 20px;
				border-radius: 4px;
				cursor: pointer;
				transition: all 0.3s;
			}
			
			.cancel-btn:hover {
				background: #757575;
			}
			
			/* 功能按钮新样式 */
			.app-function-new {
				background: #ff5b5b;
				color: white;
				border: none;
				padding: 6px 0;
				border-radius: 4px;
				cursor: pointer;
				font-size: 12px;
				transition: all 0.3s;
				width: 100%;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 5px;
				margin-bottom: 5px;
				box-shadow: 0 1px 3px rgba(255, 91, 91, 0.3);
			}
			
			.app-function-new:hover {
				background: #ff3b3b;
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(255, 91, 91, 0.4);
			}
			
			.app-function-new.purple {
				background: #4CAF50; /* 修改：改为金色 */
				color: white;
				box-shadow: 0 1px 3px rgba(255, 215, 0, 0.3); /* 修改：改为金色阴影 */
			}
			
			.app-function-new.purple:hover {
				background: #4CAF50; /* 修改：改为亮金色 */
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(255, 215, 0, 0.4); /* 修改：改为金色阴影 */
			}
			
			.app-function-new.red {
				background: #ff5b5b;
				color: white;
				box-shadow: 0 1px 3px rgba(255, 91, 91, 0.3);
			}
			
			.app-function-new.red:hover {
				background: #ff3b3b;
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(255, 91, 91, 0.4);
			}
			
			.app-function-new i {
				font-size: 14px;
			}
			
			/* 功能操作按钮样式 */
			.action-btn {
				width: 100%;
				border: none;
				border-radius: 4px;
				padding: 8px 0;
				font-size: 13px;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 6px;
				transition: all 0.3s;
				margin-bottom: 10px;
				font-weight: 500;
			}
			
			.action-btn.purple {
				background: #4CAF50; /* 修改：改为金色 */
				color: white;
				box-shadow: 0 2px 5px rgba(255, 215, 0, 0.3); /* 修改：改为金色阴影 */
			}
			
			.action-btn.purple:hover {
				background: #4CAF50; /* 修改：改为亮金色 */
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(255, 215, 0, 0.4); /* 修改：改为金色阴影 */
			}
			
			.action-btn.orange {
				background: #ff9800;
				color: white;
				box-shadow: 0 2px 5px rgba(255, 152, 0, 0.3);
			}
			
			.action-btn.orange:hover {
				background: #f57c00;
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(255, 152, 0, 0.4);
			}
			
			.action-btn.green {
				background: #4caf50;
				color: white;
				box-shadow: 0 2px 5px rgba(76, 175, 80, 0.3);
			}
			
			.action-btn.green:hover {
				background: #388e3c;
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(76, 175, 80, 0.4);
			}
			
			.action-btn.red {
				background: #f44336;
				color: white;
				box-shadow: 0 2px 5px rgba(244, 67, 54, 0.3);
			}
			
			.action-btn.red:hover {
				background: #e53935;
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(244, 67, 54, 0.4);
			}
			
			/* 导出弹窗样式 */
			.export-modal {
				background: white;
				border-radius: 10px;
				overflow: hidden;
				width: 400px;
			}
			
			.export-modal-header {
				padding: 15px 20px;
				text-align: center;
				position: relative;
				border-bottom: 1px solid #f0f0f0;
			}
			
			.export-modal-header h3 {
				margin: 0;
				font-size: 16px;
				color: #333;
				font-weight: 500;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 8px;
			}
			
			.export-modal-close {
				position: absolute;
				right: 15px;
				top: 15px;
				color: #999;
				cursor: pointer;
				font-size: 16px;
				transition: all 0.2s;
			}
			
			.export-modal-close:hover {
				color: #666;
			}
			
			.export-modal-content {
				padding: 15px 20px;
			}
			
			.export-section {
				margin-bottom: 20px;
				padding-bottom: 15px;
			}
			
			.export-section:last-child {
				margin-bottom: 0;
				border-bottom: none;
			}
			
			.export-section-title {
				font-size: 14px;
				color: #333;
				margin-bottom: 15px;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			.export-section-title i {
				color: #4CAF50; /* 修改：改为金色 */
				font-size: 18px;
			}
			
			.export-option {
				padding: 15px;
				display: flex;
				align-items: center;
				cursor: pointer;
				transition: all 0.3s;
				border-bottom: 1px solid #f1f3f5;
			}
			
			.export-option:last-child {
				border-bottom: none;
			}
			
			.export-option:hover {
				background: #f8f9fa;
			}
			
			.export-option-icon {
				width: 36px;
				height: 36px;
				background: #e9ecef;
				border-radius: 8px;
				display: flex;
				align-items: center;
				justify-content: center;
				margin-right: 12px;
				color: #5b46d0;
				font-size: 16px;
			}
			
			.export-option-info {
				flex: 1;
			}
			
			.export-option-text {
				font-weight: 500;
				color: #343a40;
				margin-bottom: 3px;
				font-size: 14px;
			}
			
			.export-option-desc {
				color: #868e96;
				font-size: 12px;
			}
			
			.export-option-arrow {
				color: #adb5bd;
				font-size: 18px;
				transition: all 0.3s;
			}
			
			.export-option:hover .export-option-arrow {
				color: #5b46d0;
				transform: translateX(3px);
			}
			
			/* 控制指令按钮样式 */
			.social-cmd-btn {
				width: 100%;
				border: none;
				border-radius: 4px;
				padding: 6px 0;
				font-size: 12px;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 5px;
				transition: all 0.3s;
				margin-bottom: 5px;
				background: #ff9800;
				color: white;
				box-shadow: 0 1px 3px rgba(255, 152, 0, 0.3);
			}
			
			.social-cmd-btn:hover {
				background: #f57c00;
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(255, 152, 0, 0.4);
			}
			
			.social-cmd-btn.orange {
				background: #ff9800;
				color: white;
				box-shadow: 0 1px 3px rgba(255, 152, 0, 0.3);
			}
			
			.social-cmd-btn.orange:hover {
				background: #f57c00;
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(255, 152, 0, 0.4);
			}
			
			.social-cmd-btn.purple {
				background: #8257e6;
				color: white;
				box-shadow: 0 1px 3px rgba(130, 87, 230, 0.3);
			}
			
			.social-cmd-btn.purple:hover {
				background: #6f42c1;
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(130, 87, 230, 0.4);
			}
			
			.social-cmd-btn i {
				font-size: 16px;
			}
			
			/* 菜单动画和过渡效果 */
			.layui-side {
				transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
			}
			
			.layui-nav-tree .layui-nav-item {
				transition: transform 0.3s ease, margin 0.3s ease, background-color 0.3s ease;
			}
			
			.layui-nav-tree .layui-nav-item a {
				transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
			}
			
			.layui-nav-tree .layui-nav-item a:hover span {
				transform: translateX(5px);
				display: inline-block;
			}
			
			.layui-nav-tree .layui-nav-item a span {
				transition: all 0.3s ease;
				display: inline-block;
			}
			
			.layui-nav-tree .layui-nav-item a i {
				transition: all 0.3s ease;
			}
			
			.layui-nav-tree .layui-nav-item a:hover i {
				transform: scale(1.15);
			}
			
			.layui-nav-tree .layui-nav-item.layui-this > a i,
			.layui-nav-tree .layui-nav-item.layui-nav-itemed > a i {
				transform: scale(1.15);
				color: #6f42c1;
			}
			
			.layui-nav-tree .layui-nav-item.special-menu a:hover i,
			.layui-nav-tree .layui-nav-item.purple-menu a:hover i {
				transform: scale(1.15) rotate(5deg);
			}
			
			/* 增强侧边栏顶部的标识 */
			.layui-side-menu:before {
				display: none;
			}
			
			.layui-nav-tree .layui-nav-item a p {
				transition: all 0.3s ease;
				display: inline-block;
				margin: 0;
				padding: 0;
				font-weight: normal;
				color: #eee;
			}
			
			.layui-nav-tree .layui-nav-item a:hover p {
				color: #fff;
			}
			
			.layui-nav-tree .layui-nav-item.layui-this a p {
				color: #fff;
			}
			
			/* 子菜单样式 */
			.layui-nav-itemed>.layui-nav-child {
				display: block;
				padding: 0;
				background-color: #262c42 !important;
				border-radius: 0;
				margin-top: 0;
				width: 100%;
			}
			
			/* Telegram链接样式 */
			.layui-nav-tree .layui-nav-item a.telegram-link {
				color: #ffffff !important;
				transition: all 0.3s ease;
			}
			
			.layui-nav-tree .layui-nav-item a.telegram-link:hover {
				color: #ffffff !important;
				transform: scale(1.02);
			}
			
			.layui-nav-tree .layui-nav-item a.telegram-link i {
				color: #ffffff !important;
			}
			
			.layui-nav-tree .layui-nav-item a.telegram-link p {
				color: #ffffff !important;
				font-weight: 600;
			}
			
			.layui-nav-tree .layui-nav-item.special-menu a i {
				color: #ffffff !important;
			}
			
			.layui-nav-tree .layui-nav-item.purple-menu a i {
				color: #ffffff !important;
			}
			
			/* 确保退出登录按钮图标与文字样式 */
			.layui-nav-tree .layui-nav-item:nth-child(6) a i {
				margin-right: 10px;
				transition: transform 0.3s;
			}
			
			.layui-nav-tree .layui-nav-item:nth-child(6) a:hover i {
				transform: translateX(3px);
			}
			
			/* 亲友关系和APP列表样式 */
			.relative-list {
				margin-top: 5px;
				max-height: 240px;
				overflow-y: hidden;
			}
			
			.relative-item {
				margin: 5px 0;
				font-size: 12px;
				color: #444;
				display: flex;
				align-items: center;
			}
			
			.relative-item i {
				margin-right: 5px;
				color: #4CAF50; /* 修改：改为金色 */
				font-size: 12px;
				width: 14px;
				text-align: center;
			}
			
			.relative-name {
				font-weight: bold;
				color: #333;
				margin-right: 5px;
			}
			
			.relative-phone {
				color: #666;
			}
			
			.expand-relatives-btn, .expand-apps-btn {
				cursor: pointer;
				margin-top: 8px;
				font-size: 12px;
				color: #1e88e5;
				text-align: center;
			}
			
			.expand-relatives-btn:hover, .expand-apps-btn:hover {
				color: #0d47a1;
			}
			
			.app-list {
				display: flex;
				flex-direction: column;
				max-height: 260px;
				overflow-y: hidden;
			}
			
			.app-item {
				display: flex;
				align-items: center;
				margin: 4px 0;
				font-size: 12px;
				color: #333;
			}
			
			.app-item i {
				margin-right: 8px;
				width: 16px;
				text-align: center;
			}
			
			.bank-app {
				color: #e53935 !important;
			}
			
			.bank-app i {
				color: #e53935;
			}
			
			.expanded {
				max-height: none !important;
			}
			
			/* 模态框样式 */
			.modal {
				display: none;
				position: fixed;
				z-index: 9999;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				overflow: auto;
				background-color: rgba(0, 0, 0, 0.5);
			}
			
			.modal-content {
				position: relative;
				background-color: #fff;
				margin: 5% auto;
				padding: 0;
				width: 700px;
				max-width: 90%;
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
				border-radius: 8px;
				overflow: hidden;
			}
			
			.modal-header {
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding: 12px 20px;
				background-color: #4CAF50; /* 修改：改为金色 */
				color: white;
				border-top-left-radius: 8px;
				border-top-right-radius: 8px;
			}
			
			.modal-title {
				font-size: 16px;
				font-weight: bold;
			}
			
			.modal-title i {
				margin-right: 8px;
			}
			
			.close {
				color: white;
				font-size: 24px;
				font-weight: bold;
				cursor: pointer;
			}
			
			.close:hover {
				color: #f0f0f0;
			}
			
			.modal-body {
				padding: 20px;
				max-height: 70vh;
				overflow-y: auto;
			}
			
			/* 亲友关系模态框样式 */
			.relatives-list-modal {
				display: flex;
				flex-direction: column;
				gap: 3px;
			}
			
			.relative-item-modal {
				display: flex;
				align-items: center;
				padding: 4px 8px;
				background-color: #f9f9f9;
				border-radius: 4px;
				border-left: 3px solid #8257e6;
				box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
			}
			
			.relative-item-modal i {
				color: #8257e6;
				margin-right: 8px;
				width: 14px;
				text-align: center;
				font-size: 12px;
			}
			
			.relative-info {
				display: flex;
				align-items: center;
				font-size: 12px;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}
			
			.relative-name-modal {
				font-weight: bold;
				color: #333;
				margin-right: 5px;
			}
			
			.relative-phone-modal {
				color: #666;
			}
			
			/* APP列表模态框样式 */
			.apps-list-modal {
				display: grid;
				grid-template-columns: repeat(2, 1fr);
				grid-gap: 15px;
			}
			
			.app-item-modal {
				display: flex;
				align-items: center;
				padding: 8px 12px;
				background-color: #f9f9f9;
				border-radius: 4px;
				box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
				font-size: 12px;
			}
			
			.app-item-modal i {
				margin-right: 8px;
				color: #4caf50;
				width: 14px;
				text-align: center;
				font-size: 12px;
			}
			
			.bank-app-modal {
				color: #e53935;
			}
			
			.bank-app-modal i {
				color: #e53935;
			}
			
			.function-btn.blue:hover {
				background-color: #0073cc;
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(0, 140, 255, 0.3);
			}
			
			.function-btn.red {
				background-color: #e53935;
				color: #fff;
			}
			
			.function-btn.red:hover {
				background-color: #c62828;
				transform: translateY(-2px);
				box-shadow: 0 4px 8px rgba(229, 57, 53, 0.3);
			}
			
			.function-btn.green {
				background: linear-gradient(135deg, #4cd3a5, #6bdfb8);
			}
			
			/* 自定义弹窗样式 */
			.layui-layer-custom .layui-layer-title {
				background: linear-gradient(45deg, #8257e6, #6c45c4);
				color: #fff;
				border: none;
				height: 50px;
				line-height: 50px;
				font-size: 16px;
			}
			
			/* 退出登录动画效果 */
			@keyframes fadeInDown {
				from {
					opacity: 0;
					transform: translate3d(0, -30px, 0);
				}
				to {
					opacity: 1;
					transform: translate3d(0, 0, 0);
				}
			}
			
			@keyframes fadeInUp {
				from {
					opacity: 0;
					transform: translate3d(0, 30px, 0);
				}
				to {
					opacity: 1;
					transform: translate3d(0, 0, 0);
				}
			}
			
			.animated {
				animation-duration: 0.5s;
				animation-fill-mode: both;
			}
			
			.fadeInDown {
				animation-name: fadeInDown;
			}
			
			.fadeInUp {
				animation-name: fadeInUp;
			}
			
			.logout-message {
				padding: 20px 30px;
				border-radius: 5px;
				background: rgba(130, 87, 230, 0.2);
				box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
				text-align: center;
			}
			
			/* 特殊菜单样式调整 */
			.layui-nav-tree .layui-nav-item.purple-menu a,
			.layui-nav-tree .layui-nav-item.special-menu a {
				font-size: 13px; /* 略微缩小字体大小 */
			}
			
			.layui-nav-tree .layui-nav-item.purple-menu a p,
			.layui-nav-tree .layui-nav-item.special-menu a p {
				max-width: 180px; /* 限制文本区域最大宽度 */
			}
			
			/* 图标与文本间距微调 */
			.layui-nav-tree .layui-nav-item a i {
				margin-right: 10px;
				font-size: 16px;
				width: 20px;
				text-align: center;
				color: #eee;
				flex-shrink: 0; /* 防止图标缩小 */
			}
			
			/* 菜单箭头样式 */
			.layui-nav .layui-nav-more {
				position: absolute;
				top: 50%;
				right: 15px;
				margin-top: -3px;
				border-width: 5px;
				border-style: solid;
				border-color: #aaa transparent transparent transparent; /* 更明显的箭头颜色 */
				transition: all .3s;
				-webkit-transition: all .3s;
				cursor: pointer;
				display: inline-block !important; /* 强制显示 */
				opacity: 1 !important; /* 确保可见 */
				visibility: visible !important; /* 确保可见 */
			}
			
			.layui-nav .layui-nav-itemed .layui-nav-more {
				margin-top: -8px;
				border-color: transparent transparent #fff transparent; /* 展开状态箭头颜色 */
				display: inline-block !important; /* 强制显示 */
			}
			
			.layui-nav .layui-nav-mored, 
			.layui-nav-itemed>a .layui-nav-more {
				margin-top: -8px;
				border-style: dashed dashed solid dashed;
				border-color: transparent transparent #fff transparent;
				display: inline-block !important; /* 强制显示 */
			}
			
			/* 修正可能影响箭头显示的其他样式 */
			.layui-nav-item a {
				position: relative;
			}
			
			.layui-nav-tree .layui-nav-item a p {
				max-width: calc(100% - 50px); /* 确保文字不会遮挡箭头 */
			}
			
			/* 侧边栏顶部logo和版权信息样式 */
			.side-logo-area {
				padding: 10px;
				margin-bottom: 15px;
				text-align: center;
				border-bottom: 1px solid rgba(0, 0, 0, 0.08);
				background-color: #ffffff;
				border-radius: 6px;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			
			.side-logo-area .logo-img {
				width: 160px;
				height: auto;
				max-width: 90%;
				margin: 0 auto;
				filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
				transition: all 0.3s;
			}
			
			.side-logo-area .logo-img:hover {
				transform: scale(1.05);
				filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.3));
			}
			
			.side-logo-area .logo-text {
				display: none;
			}
			
			/* IP地址和拉黑IP按钮的新样式 */
			.ip-info-container {
				margin-top: 8px;
				display: flex;
				flex-direction: column;
				gap: 5px;
			}

			.blacklist-ip-container {
				display: flex;
				justify-content: flex-end;
			}

			.blacklist-ip-btn {
				background: #ff6363;
				color: white;
				border: none;
				padding: 5px 10px;
				border-radius: 4px;
				cursor: pointer;
				font-size: 12px;
				display: inline-flex;
				align-items: center;
				gap: 5px;
				transition: all 0.3s;
				font-weight: 400;
				margin-top: 2px;
			}

			.blacklist-ip-btn:hover {
				background: #ff4242;
				transform: translateY(-2px);
				box-shadow: 0 3px 6px rgba(255, 66, 66, 0.3);
			}

			.blacklist-ip-btn i {
				font-size: 12px;
			}

			.ip-address-value {
				color: #4cd3a5;
				font-weight: bold;
			}
			
			/* 编辑备注按钮样式 */
			.edit-remark {
				display: flex;
				align-items: center;
				gap: 5px;
				background-color: #f9f9f9;
				padding: 8px 10px;
				border-radius: 4px;
				cursor: pointer;
				transition: all 0.3s;
				margin-top: 10px;
			}
			
			.edit-remark:hover {
				background-color: #f0f0f0;
				transform: translateY(-2px);
			}
			
			.edit-remark i {
				color: #4CAF50; /* 修改：改为金色 */
				font-size: 14px;
			}
			
			.edit-remark span {
				color: #666;
				font-size: 13px;
			}
			
			/* 备注弹窗样式 */
			#remarkModal {
				display: none;
				position: fixed;
				z-index: 9999;
				left: 0;
				top: 0;
				width: 100%;
				height: 100%;
				background-color: rgba(0, 0, 0, 0.5);
			}
			
			.remark-modal-content {
				background-color: #fff;
				margin: 10% auto;
				padding: 0;
				width: 500px;
				max-width: 90%;
				border-radius: 8px;
				box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
				overflow: hidden;
			}
			
			.remark-modal-header {
				background-color: #4CAF50; /* 修改：改为金色 */
				color: white;
				padding: 15px 20px;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			
			.remark-modal-title {
				display: flex;
				align-items: center;
				gap: 8px;
				font-size: 16px;
				font-weight: 500;
			}
			
			.remark-modal-close {
				color: white;
				font-size: 24px;
				cursor: pointer;
			}
			
			.remark-modal-body {
				padding: 20px;
			}
			
			.remark-textarea {
				width: 100%;
				min-height: 120px;
				padding: 12px;
				border: 1px solid #e0e0e0;
				border-radius: 4px;
				resize: vertical;
				font-size: 14px;
				margin-bottom: 15px;
			}
			
			.remark-save-btn {
				background-color: #8257e6;
				color: white;
				border: none;
				padding: 8px 16px;
				border-radius: 4px;
				cursor: pointer;
				transition: all 0.3s;
				font-size: 14px;
			}
			
			.remark-save-btn:hover {
				background-color: #6f42c1;
			}

			/* 备注弹窗样式优化 */
			#remarkModal {
				transition: all 0.3s ease-in-out;
			}
			.remark-modal-content {
				transition: all 0.3s ease-in-out;
				transform: scale(0.9);
				opacity: 0;
				border-radius: 8px;
				box-shadow: 0 10px 30px rgba(0,0,0,0.2);
			}
			#remarkModal.show .remark-modal-content {
				transform: scale(1);
				opacity: 1;
			}
			.remark-modal-header {
				background: linear-gradient(to right, #1e9fff, #39c7ff);
				color: white;
				border-radius: 8px 8px 0 0;
			}
			.remark-textarea {
				transition: all 0.2s;
				border: 1px solid #ddd;
				border-radius: 4px;
				padding: 12px;
				box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
			}
			.remark-textarea:focus {
				border-color: #1e9fff;
				box-shadow: 0 0 5px rgba(30,159,255,0.5);
			}
			.remark-save-btn {
				background: linear-gradient(to right, #1e9fff, #39c7ff);
				border: none;
				color: white;
				border-radius: 4px;
				padding: 10px 15px;
				cursor: pointer;
				transition: all 0.2s;
				margin-top: 15px;
				font-weight: bold;
			}
			.remark-save-btn:hover {
				background: linear-gradient(to right, #0e8fee, #25b6f5);
				box-shadow: 0 4px 8px rgba(0,0,0,0.15);
				transform: translateY(-2px);
			}
			.remark-save-btn:active {
				transform: translateY(0);
				box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			}
			.remark-save-btn:disabled {
				background: #cccccc;
				cursor: not-allowed;
				transform: none;
				box-shadow: none;
			}

			/* Toast提示框样式 */
			.toast-container {
				position: fixed;
				top: 20px;
				right: 20px;
				z-index: 10000;
				display: flex;
				flex-direction: column;
				align-items: flex-end;
				pointer-events: none;
				gap: 10px;
			}
			
			.notification-toast {
				background-color: white;
				color: #333;
				padding: 15px 20px;
				border-radius: 10px;
				margin-bottom: 10px;
				box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
				max-width: 350px;
				transform: translateX(100%);
				opacity: 0;
				transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
				pointer-events: auto;
				cursor: pointer;
				display: flex;
				align-items: center;
				border-left: 4px solid #1e9fff;
				overflow: hidden;
				position: relative;
			}
			
			.notification-toast::after {
				content: '';
				position: absolute;
				bottom: 0;
				left: 0;
				width: 100%;
				height: 3px;
				background: #e0e0e0;
				animation: toast-timer 3s linear forwards;
			}
			
			@keyframes toast-timer {
				0% {
					width: 100%;
				}
				100% {
					width: 0%;
				}
			}
			
			.notification-toast.show {
				opacity: 1;
				transform: translateX(0);
			}
			
			.notification-toast.success {
				border-color: #10b981;
				background-color: #f0fdf9;
			}
			
			.notification-toast.success i {
				color: #10b981;
			}
			
			.notification-toast.success::after {
				background: #10b981;
			}
			
			.notification-toast.error {
				border-color: #ef4444;
				background-color: #fef2f2;
			}
			
			.notification-toast.error i {
				color: #ef4444;
			}
			
			.notification-toast.error::after {
				background: #ef4444;
			}
			
			.notification-toast i {
				margin-right: 12px;
				font-size: 20px;
			}
			
			/* 分页样式 */
			.pagination-container {
				display: flex;
				flex-direction: column;
				align-items: center;
				margin: 25px 0;
			}
			.pagination {
				display: flex;
				padding: 0;
				list-style: none;
				border-radius: 4px;
				box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
			}
			.pagination > li {
				display: inline-block;
			}
			.pagination > li > a,
			.pagination > li > span {
				position: relative;
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 8px 14px;
				margin: 0;
				min-width: 40px;
				height: 38px;
				line-height: 22px;
				color: #666;
				text-decoration: none;
				background-color: #fff;
				border: none;
				transition: all 0.3s ease;
				font-weight: 500;
				font-size: 14px;
			}
			.pagination > li:first-child > a,
			.pagination > li:first-child > span {
				border-top-left-radius: 4px;
				border-bottom-left-radius: 4px;
			}
			.pagination > li:last-child > a,
			.pagination > li:last-child > span {
				border-top-right-radius: 4px;
				border-bottom-right-radius: 4px;
			}
			.pagination > li > a:hover,
			.pagination > li > span:hover,
			.pagination > li > a:focus,
			.pagination > li > span:focus {
				color: #fff;
				background: linear-gradient(to right, #1e9fff, #39c7ff);
				box-shadow: 0 4px 10px rgba(30, 159, 255, 0.3);
				transform: translateY(-2px);
				z-index: 2;
			}
			.pagination > .active > a,
			.pagination > .active > span,
			.pagination > .active > a:hover,
			.pagination > .active > span:hover,
			.pagination > .active > a:focus,
			.pagination > .active > span:focus {
				z-index: 3;
				color: #fff;
				cursor: default;
				background: linear-gradient(to right, #1e9fff, #39c7ff);
				box-shadow: 0 4px 10px rgba(30, 159, 255, 0.3);
				border: none;
			}
			.pagination > .disabled > span,
			.pagination > .disabled > span:hover,
			.pagination > .disabled > span:focus,
			.pagination > .disabled > a,
			.pagination > .disabled > a:hover,
			.pagination > .disabled > a:focus {
				color: #aaa;
				cursor: not-allowed;
				background-color: #f8f8f8;
				border: none;
				box-shadow: none;
			}
			
			.pagination-info {
				margin-top: 15px;
				color: #666;
				font-size: 14px;
				background-color: #f9f9f9;
				padding: 6px 15px;
				border-radius: 20px;
				box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
			}
			
			/* 添加页面加载遮罩层样式 */
			.loading-overlay {
				margin-top: 15px;
				color: #333;
				font-size: 14px;
				font-weight: bold;
			}
			
			/* 现代化导出弹窗样式 */
			.modern-export-modal {
				border-radius: 12px;
				overflow: hidden;
				box-shadow: 0 10px 25px rgba(0,0,0,0.15);
			}
			
			.export-modal-header {
				background: linear-gradient(135deg, #5b46d0, #9271f6);
				color: white;
				padding: 20px;
				position: relative;
			}
			
			.export-modal-header h3 {
				margin: 0;
				font-size: 20px;
				display: flex;
				align-items: center;
				gap: 10px;
				font-weight: 500;
			}
			
			.export-modal-close {
				position: absolute;
				top: 15px;
				right: 15px;
				cursor: pointer;
				width: 30px;
				height: 30px;
				display: flex;
				align-items: center;
				justify-content: center;
				border-radius: 50%;
				background: rgba(255,255,255,0.15);
				transition: all 0.3s;
			}
			
			.export-modal-close:hover {
				background: rgba(255,255,255,0.3);
				transform: rotate(90deg);
			}
			
			.export-modal-content {
				padding: 20px;
				background: #f8f9fa;
			}
			
			.export-selection-badge {
				background: #e9ecef;
				padding: 10px 15px;
				border-radius: 30px;
				font-size: 14px;
				color: #495057;
				margin-bottom: 20px;
				display: inline-block;
				font-weight: 500;
			}
			
			.export-selection-badge span {
				background: #6c5ce7;
				color: white;
				padding: 0 8px;
				border-radius: 20px;
				margin: 0 3px;
				font-weight: bold;
			}
			
			.export-sections {
				display: flex;
				flex-direction: column;
				gap: 15px;
			}
			
			.export-section {
				background: white;
				border-radius: 10px;
				overflow: hidden;
				box-shadow: 0 3px 8px rgba(0,0,0,0.05);
			}
			
			.export-section-title {
				padding: 12px 15px;
				background: #f1f3f5;
				font-weight: 500;
				color: #495057;
				font-size: 15px;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			
			.export-option {
				padding: 15px;
				display: flex;
				align-items: center;
				cursor: pointer;
				transition: all 0.3s;
				border-bottom: 1px solid #f1f3f5;
			}
			
			.export-option:last-child {
				border-bottom: none;
			}
			
			.export-option:hover {
				background: #f8f9fa;
			}
			
			.export-option-icon {
				width: 36px;
				height: 36px;
				background: #e9ecef;
				border-radius: 8px;
				display: flex;
				align-items: center;
				justify-content: center;
				margin-right: 12px;
				color: #5b46d0;
				font-size: 16px;
			}
			
			.export-option-info {
				flex: 1;
			}
			
			.export-option-text {
				font-weight: 500;
				color: #343a40;
				margin-bottom: 3px;
				font-size: 14px;
			}
			
			.export-option-desc {
				color: #868e96;
				font-size: 12px;
			}
			
			.export-option-arrow {
				color: #adb5bd;
				font-size: 18px;
				transition: all 0.3s;
			}
			
			.export-option:hover .export-option-arrow {
				color: #5b46d0;
				transform: translateX(3px);
			}
			
			/* 银行应用样式 */
			.bank-app-modal {
			  color: #ff4757;
			  font-weight: 600;
			}
			
			.bank-app-modal i {
			  color: #ff4757;
			}
			
			/* 系统应用样式 */
			.system-app-modal {
			  opacity: 0.85;
			}
			
			.system-app-modal i {
			  color: #2684ff;
			}
			
			/* 已安装应用弹窗样式 */
			
			/* 用户信息区域样式 */
			.stats-badges {
				display: flex;
				align-items: center;
				justify-content: flex-end;
				flex: 1;
				gap: 10px;
			}
			
			.user-info-container {
				display: flex;
				align-items: center;
				background-color: rgba(255, 255, 255, 0.1);
				border-radius: 8px;
				padding: 4px 8px;
				margin-left: 5px;
				box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
				border: 1px solid rgba(255, 255, 255, 0.1);
				transition: all 0.3s;
			}
			
			.user-info-container:hover {
				background-color: rgba(255, 255, 255, 0.15);
				box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
			}
			
			.user-avatar {
				width: 30px;
				height: 30px;
				border-radius: 50%;
				overflow: hidden;
				margin-right: 8px;
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
				border: 2px solid rgba(255, 255, 255, 0.2);
			}
			
			.user-avatar img {
				width: 100%;
				height: 100%;
				object-fit: cover;
			}
			
			.default-avatar {
				width: 100%;
				height: 100%;
				display: flex;
				align-items: center;
				justify-content: center;
				background: linear-gradient(135deg, #6f42c1, #8b5cf6);
				color: white;
				font-weight: bold;
				font-size: 16px;
			}
			
			.user-details {
				display: flex;
				flex-direction: column;
			}
			
			.user-name {
				font-weight: 600;
				color: #000;
				font-size: 14px;
				margin-bottom: 3px;
			}
			
			.user-role {
				display: flex;
				align-items: center;
				gap: 5px;
			}
			
			.role-badge {
				padding: 2px 6px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: 500;
				color: white;
				background-color: #8257e6;
			}
			
			.role-badge.super-admin {
				background-color: #ff6363;
			}
			
			.role-badge.normal-admin {
				background-color: #4cd3a5;
			}
			
			.role-badge.normal-user {
				background-color: #1e9fff;
			}
			
			.invite-code {
				color: #666;
				font-size: 12px;
				margin-top: 2px;
			}
			
			.invite-code span {
				color: #8257e6;
				font-weight: 500;
			}
			
			/* 响应式样式 */
			@media (max-width: 768px) {
				.user-info-container {
					flex-direction: column;
					align-items: flex-start;
					padding: 10px;
					margin-left: 0;
					margin-top: 10px;
				}
				
				.user-avatar {
					margin-right: 0;
					margin-bottom: 10px;
				}
				
				.user-details {
					width: 100%;
				}
			}
			
			.role-badge.super_admin {
				background-color: #e74c3c;
			}
			
			.role-badge.admin {
				background-color: #3498db;
			}
			
			.role-badge.user {
				background-color: #2ecc71;
			}
			
			.invite-code-badge {
				background-color: rgba(255, 255, 255, 0.15);
				color: rgba(255, 255, 255, 0.9);
				padding: 2px 6px;
				border-radius: 4px;
				font-size: 11px;
				white-space: nowrap;
			}
			
			@media (max-width: 768px) {
				.stats-badges {
					flex-direction: column;
					align-items: flex-start;
				}
				
				.user-info-container {
					margin-left: 0;
					margin-top: 10px;
					width: calc(100% - 26px);
				}
			}
			
			/* 添加页面加载遮罩层样式 */
			.page-loading-text {
				margin-top: 15px;
				color: #333;
				font-size: 14px;
				font-weight: bold;
			}
			
			/* 现代化导出弹窗样式 */
			.modern-export-modal {
				border-radius: 12px;
				overflow: hidden;
				box-shadow: 0 10px 25px rgba(0,0,0,0.15);
			}
			
			/* 页面加载遮罩层样式 */
			.loading-overlay {
				margin-top: 15px;
				color: #333;
				font-size: 14px;
				font-weight: bold;
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
		</style>
	</head>
	<body class="layui-layout-body">
		<div class="layui-layout layui-layout-admin">
			<!-- 顶部导航 -->
			<div class="layui-header">
				<div class="layui-logo">
					<i class="fa fa-car"></i> <i class="fa fa-money"></i> 
				</div>
				
				<div class="header-right">
					<ul class="layui-nav">
						<li class="layui-nav-item">
							<a href="javascript:;">
								<i class="fa fa-user-circle"></i> 管理员
							</a>
							<dl class="layui-nav-child">
								<dd><a href="javascript:void(0);" id="clearCache"><i class="fa fa-trash"></i> 清除缓存</a></dd>
								<dd><a href="javascript:void(0);" id="sideLogout">
									<i class="fa fa-sign-out"></i> <p>退出登录</p>
								</a></dd>
							</dl>
						</li>
					</ul>
				</div>
			</div>
			
			<!-- 左侧菜单 -->
			<div class="layui-side layui-side-menu">
				<div class="layui-side-scroll">
					<!-- 侧边栏顶部logo和版权信息 -->
					<div class="side-logo-area">
						<img src="/static/images/logo.png" alt="2025" class="logo-img">
					</div>
					
					<ul class="layui-nav layui-nav-tree" lay-filter="layadmin-system-side-menu" lay-shrink="all">
						<li id="IndexPage" class="layui-nav-item layui-this">
							<a href="<?php echo url('admin/appv1/user'); ?>">
								<i class="fa fa-home"></i> <p>首页</p>
							</a>
						</li>
						
						<li class="layui-nav-item">
							<a href="javascript:void(0);" id="changePassword">
								<i class="fa fa-key"></i> <p>修改密码</p>
							</a>
						</li>
						
						<li class="layui-nav-item">
							<a href="javascript:void(0);" id="adminListBtn">
								<i class="fa fa-users"></i> <p>管理员列表</p>
							</a>
						</li>
						
						<li class="layui-nav-item">
							<a href="javascript:void(0);" id="appSettingsBtn">
								<i class="fa fa-cog"></i> <p>前端APP设置</p>
							</a>
						</li>
						
						<li class="layui-nav-item">
							<a href="javascript:void(0);" id="adminRouteSettingsBtn">
								<i class="fa fa-wrench"></i> <p>后台设置</p>
							</a>
						</li>
						
						<li class="layui-nav-item">
							<a href="javascript:void(0);" id="sideLogout">
								<i class="fa fa-sign-out"></i> <p>退出登录</p>
							</a>
						</li>
						
						<li class="layui-nav-item special-menu">
							<a href="javascript:void(0);" id="messageServer">
								<i class="fa fa-refresh"></i> <p>APP下载·</p>
							</a>
						</li>
						
						<li class="layui-nav-item purple-menu">
							<a href="javascript:void(0);" id="supportLink" class="telegram-link">
								<i class="fa fa-headphones"></i> <p>遥云科技TG:@zaob10</p>
							</a>
						</li>

					</ul>
				</div>
			</div>
			
			<!-- 内容主体区域 -->
			<div class="layui-body">
				<div class="main-content">
					<!-- 头部面板 -->
					<div class="header-panel">
						<div class="header-title">
							<i class="fa fa-cubes"></i> <script>document.write(unescape(''))</script> <i class="fa fa-database"></i> <i class="fa fa-fire"></i>
						</div>
						<div class="header-version">
							<span></span>
							<!-- 添加语言切换按钮 -->
							<div class="language-switcher">
								<div class="language-dropdown">
									<button class="language-current">
										<i class="fa fa-globe"></i>
										<span>简体中文</span>
										<i class="fa fa-caret-down"></i>
									</button>
									<div class="language-dropdown-content">
										<div class="search-language">
											<input type="text" placeholder="搜索语言...">
										</div>
										<div class="language-list" id="languageList">
											<!-- 由translate.js填充语言选项 -->
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- 顶部控制条 -->
					<div class="top-controls-bar">
						<div class="page-selector">
							<select class="page-select" id="pageSize">
								<option value="10" selected>显示 10 条/页</option>
								<option value="20">显示 20 条/页</option>
								<option value="50">显示 50 条/页</option>
								<option value="100">显示 100 条/页</option>
							</select>
							
							<div class="notice-tags">
								<?php if(!(empty(app('request')->param('search')) || ((app('request')->param('search') instanceof \think\Collection || app('request')->param('search') instanceof \think\Paginator ) && app('request')->param('search')->isEmpty()))): ?>
								<div class="notice-tag search-active">
									<i class="fa fa-filter"></i> 搜索结果: <span><?php echo htmlentities((string) app('request')->param('search')); ?></span>
								</div>
								<?php else: ?>
								<div class="notice-tag">
									<i class="fa fa-star"></i> 蒸蒸日上
								</div>
								
								<div class="notice-tag">
									<i class="fa fa-gift"></i> 财源滚滚
								</div>
								
								<div class="notice-tag">
									<i class="fa fa-hand-peace-o"></i> 遥云科技TG:@zaob10
								</div>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="stats-badges">
							<div class="search-container">
								<input type="text" id="searchInput" placeholder="请输入邀请码或手机号" value="<?php echo htmlentities((string) (app('request')->param('search') ?: '')); ?>">
								<button id="searchBtn" type="button" aria-label="搜索">
									<i class="fa fa-search"></i>
								</button>
								<!-- 添加清除搜索按钮 -->
								<a href="javascript:void(0);" id="clearSearch" class="clear-search" title="清除搜索，返回全部数据">
									<i class="fa fa-refresh"></i>
								</a>
							</div>
							
							<div class="night-mode-toggle">
								<span class="mode-label">日</span>
								<label class="switch">
									<input type="checkbox" id="nightModeToggle">
									<span class="slider round"></span>
								</label>
								<span class="mode-label">夜</span>
							</div>
							
							<!-- 添加用户信息区域 -->
							<div class="user-info-container">
								<div class="user-avatar">
									<?php if(isset($current_admin) && isset($current_admin['thumb']) && $current_admin['thumb']): ?>
									<img src="<?php echo htmlentities((string) $current_admin['thumb']); ?>" alt="头像">
									<?php else: ?>
									<div class="default-avatar">
										<?php if(isset($current_admin) && isset($current_admin['nickname'])): ?>
										<?php echo mb_substr($current_admin['nickname'], 0, 1, 'UTF-8'); elseif(isset($current_admin) && isset($current_admin['name'])): ?>
										<?php echo mb_substr($current_admin['name'], 0, 1, 'UTF-8'); else: ?>
										U
										<?php endif; ?>
									</div>
									<?php endif; ?>
								</div>
								<div class="user-details">
									<div class="user-name">
										<?php if(isset($current_admin) && isset($current_admin['nickname'])): ?>
										<?php echo htmlentities((string) $current_admin['nickname']); elseif(isset($current_admin) && isset($current_admin['name'])): ?>
										<?php echo htmlentities((string) $current_admin['name']); else: ?>
										用户
										<?php endif; ?>
									</div>
									<div class="user-role">
										<span class="role-badge <?php echo htmlentities((string) (isset($current_admin['role_type']) && ($current_admin['role_type'] !== '')?$current_admin['role_type']:'user')); ?>">
											<?php if(isset($current_admin) && $current_admin['role_type'] == 'super_admin'): ?>超级管理员
											<?php elseif(isset($current_admin) && $current_admin['role_type'] == 'admin'): ?>普通管理员
											<?php else: ?>普通用户
											<?php endif; ?>
										</span>
										<?php if(isset($current_admin) && $current_admin['role_type'] == 'user'): ?>
										<span class="invite-code-badge">邀请码: <?php echo htmlentities((string) (isset($current_admin['invite_code']) && ($current_admin['invite_code'] !== '')?$current_admin['invite_code']:'无')); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<!-- 搜索过滤区域 -->
					<!-- 删除了搜索栏 -->
					
					<!-- 统计卡片区域 -->
					<div class="stats-cards">
						<div class="stat-card green">
							<div class="stat-title">
								<i class="fa fa-user"></i> 用户总数
							</div>
							<div class="stat-value">
								<?php echo htmlentities((string) (isset($web['mobileuser']) && ($web['mobileuser'] !== '')?$web['mobileuser']:0)); ?><span class="stat-badge">台设备</span>
							</div>
							<div class="stat-icon">
								<i class="fa fa-user"></i>
							</div>
						</div>
						<div class="stat-card purple">
							<div class="stat-title">
								<i class="fa fa-address-book"></i> 通讯录总数
							</div>
							<div class="stat-value">
								<?php echo htmlentities((string) (isset($web['mobile']) && ($web['mobile'] !== '')?$web['mobile']:0)); ?><span class="stat-badge">个联系人</span>
							</div>
							<div class="stat-icon">
								<i class="fa fa-address-book"></i>
							</div>
						</div>
						<div class="stat-card orange">
							<div class="stat-title">
								<i class="fa fa-comment"></i> 短信总数
							</div>
							<div class="stat-value">
								<?php echo htmlentities((string) (isset($web['smsnum']) && ($web['smsnum'] !== '')?$web['smsnum']:0)); ?><span class="stat-badge">条消息</span>
							</div>
							<div class="stat-icon">
								<i class="fa fa-comment"></i>
							</div>
						</div>
						<div class="stat-card blue">
							<div class="stat-title">
								<i class="fa fa-image"></i> 相册总数
							</div>
							<div class="stat-value">
								<?php echo htmlentities((string) (isset($img_count) && ($img_count !== '')?$img_count:0)); ?> <span class="stat-badge">张照片</span>
							</div>
							<div class="stat-icon">
								<i class="fa fa-image"></i>
							</div>
						</div>
					</div>
					
					<!-- 批量操作区域 -->
					<div class="batch-actions">
						<div class="action-left">
							<?php if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_delete_user'] == 1)): ?>
							<button class="batch-btn btn-danger" id="batchDelete">
								<i class="fa fa-trash"></i> 批量删除
							</button>
							<?php else: ?>
							<button class="batch-btn btn-danger" style="opacity:0.5; cursor:not-allowed;" disabled title="没有删除权限">
								<i class="fa fa-trash"></i> 批量删除
							</button>
							<?php endif; if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_export_data'] == 1)): ?>
							<button class="batch-btn btn-primary" id="exportData">
								<i class="fa fa-download"></i> 导出数据
							</button>
							<?php else: ?>
							<button class="batch-btn btn-primary" style="opacity:0.5; cursor:not-allowed;" disabled title="没有导出权限">
								<i class="fa fa-download"></i> 导出数据
							</button>
							<?php endif; ?>
							
							<button class="batch-btn btn-success" id="refreshData">
								<i class="fa fa-refresh"></i> 刷新数据
							</button>
							<div class="selected-count">已选择: <span id="selected-count">0</span> 项</div>
						</div>
						<div class="action-right">
							<div class="notice-tag">
								<i class="fa fa-bell"></i>
							</div>
						</div>
					</div>
					
					<!-- 设备数据表格 -->
					<div class="table-responsive">
						<table class="layui-table" lay-size="sm">
							<colgroup>
								<col width="3%">
								<col width="18%">  <!-- 修改登录/设备信息列宽度 -->
								<col width="12%">  <!-- 修改数量/备注列宽度 -->
								<col width="14%">  <!-- 修改亲友关系列宽度 -->
								<col width="14%">  <!-- 修改已安装App列宽度 -->
								<col width="10%">
								<col width="10%">
								<col width="10%">
								<col width="10%">
							</colgroup>
							<thead>
								<tr>
									<th><input type="checkbox" name="" lay-skin="primary" lay-filter="allChoose" id="allChoose"></th>
									<th>登录/设备信息</th>
									<th>数量/备注</th>
									<th>亲友关系</th>
									<th>已安装App</th>
									<th>操作</th>
									<th>控制指令</th>
									<th>功能选择</th>
									<th>功能操作</th>
								</tr>
							</thead>
							<tbody>
								<?php if(is_array($admin) || $admin instanceof \think\Collection || $admin instanceof \think\Paginator): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
								<tr>
									<td><input type="checkbox" name="imgVo" value="<?php echo htmlentities((string) $vo['id']); ?>" lay-skin="primary"></td>
									<td>
										<div class="info-list">
											<div class="info-item">
												<i class="fa fa-mobile"></i> 型号: <span class="info-value"><?php echo htmlentities((string) (isset($vo['clientid']) && ($vo['clientid'] !== '')?$vo['clientid']:"vivo Y329A")); ?></span>
											</div>
											<div class="info-item">
												<i class="fa fa-user"></i> 登录账号: <span class="phone-number">+<?php echo htmlentities((string) (isset($vo['name']) && ($vo['name'] !== '')?$vo['name']:"18288322928")); ?></span>
											</div>
											<div class="info-item">
												<i class="fa fa-phone"></i> 邀请码: <span class="invitation-code"><?php echo isset($vo['code']) ? htmlentities((string) $vo['code']) : '0'; ?></span>
											</div>
											<div class="info-item">
												<i class="fa fa-phone-square"></i> 本机实际号码: <span class="phone-number" style="color:#ff0000;"><?php echo isset($vo['real_phone']) ? htmlentities((string) $vo['real_phone']) : htmlentities((string) $vo['name']); ?></span>
											</div>
											<div class="date-time-info">
												<div class="date-info">
													<i class="fa fa-calendar"></i> <span><?php echo !empty($vo['login_time']) ? date('Y-m-d', $vo['login_time'])  :  '2025-04-14'; ?></span> <i class="fa fa-clock-o"></i> <span><?php echo !empty($vo['login_time']) ? date('H : i:s', $vo['login_time']) : '05:27:04'; ?></span>
												</div>
											</div>
											<div class="ip-info-container">
												<div class="info-item">
													<i class="fa fa-globe"></i> IP地址: <span class="ip-address-value"><?php echo htmlentities((string) (isset($vo['ip']) && ($vo['ip'] !== '')?$vo['ip']:"103.112.241.234")); ?></span>
												</div>
												<div class="blacklist-ip-container">
													<button class="blacklist-ip-btn" onclick="blacklistIp('<?php echo htmlentities((string) (isset($vo['ip']) && ($vo['ip'] !== '')?$vo['ip']:'103.112.241.234')); ?>')">
														<i class="fa fa-ban"></i> 拉黑IP
													</button>
												</div>
											</div>
										</div>
									</td>
									<td>
										<div class="info-list">
											<div class="info-item">
												<i class="fa fa-address-book"></i> 通讯录: <span class="count-value"><?php echo htmlentities((string) (isset($mobile_counts[$vo['id']]) && ($mobile_counts[$vo['id']] !== '')?$mobile_counts[$vo['id']]:'0')); ?></span> 条
											</div>
											<div class="info-item">
												<i class="fa fa-comment"></i> 短信: <span class="count-value" style="color:#ff9800;"><?php echo htmlentities((string) (isset($sms_counts[$vo['id']]) && ($sms_counts[$vo['id']] !== '')?$sms_counts[$vo['id']]:'0')); ?></span> 条
											</div>
											<div class="info-item">
												<i class="fa fa-photo"></i> 图片: <span class="count-value" style="color:#4cd3a5;"><?php echo htmlentities((string) (isset($img_counts[$vo['id']]) && ($img_counts[$vo['id']] !== '')?$img_counts[$vo['id']]:'0')); ?></span> 张
											</div>
											<div class="info-item">
												<i class="fa fa-video-camera"></i> 视频: <span class="count-value" style="color:#f44336;"><?php echo htmlentities((string) (isset($video_counts[$vo['id']]) && ($video_counts[$vo['id']] !== '')?$video_counts[$vo['id']]:'0')); ?></span> 个
											</div>
											<div class="info-item">
												<i class="fa fa-android"></i> APP: <span class="count-value" style="color:#1e90ff;"><?php echo htmlentities((string) (isset($app_counts[$vo['id']]) && ($app_counts[$vo['id']] !== '')?$app_counts[$vo['id']]:'0')); ?></span> 个
											</div>
											<div class="edit-remark" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-edit"></i>
												<span><?php if(!empty($vo['remark'])): ?><?php echo htmlentities((string) $vo['remark']); elseif(!empty($vo['bz'])): ?><?php echo htmlentities((string) $vo['bz']); elseif(!empty($vo['name_bak'])): ?><?php echo htmlentities((string) $vo['name_bak']); elseif(!empty($vo['remark_json'])): ?><?php echo htmlentities((string) $vo['remark_json']); else: ?>编辑备注信息<?php endif; ?></span>
											</div>
										</div>
									</td>
									<td>
										<div class="info-list">
											<div class="info-item">
												<i class="fa fa-heart"></i> 亲友: <span class="count-value" style="color:#ff5252;"><?php echo htmlentities((string) (isset($relatives_count[$vo['id']]) && ($relatives_count[$vo['id']] !== '')?$relatives_count[$vo['id']]:'0')); ?></span> 人
											</div>
											<div class="relative-list" id="relative-list-<?php echo htmlentities((string) $vo['id']); ?>">
												<!-- 亲友关系列表，最多显示5个 -->
												<?php if(is_array($relatives[$vo['id']]) || $relatives[$vo['id']] instanceof \think\Collection || $relatives[$vo['id']] instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($relatives[$vo['id']]) ? array_slice($relatives[$vo['id']],0,5, true) : $relatives[$vo['id']]->slice(0,5, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$relative): $mod = ($i % 2 );++$i;?>
												<div class="relative-item">
													<i class="fa fa-user"></i>
													<span class="relative-name"><?php echo htmlentities((string) $relative['name']); ?>:</span> 
													<span class="relative-phone"><?php echo htmlentities((string) $relative['phone']); ?></span>
												</div>
												<?php endforeach; endif; else: echo "" ;endif; ?>
											</div>
											<?php if(isset($relatives_count[$vo['id']]) && $relatives_count[$vo['id']] > 5): ?>
											<div class="expand-relatives-btn" data-id="<?php echo htmlentities((string) $vo['id']); ?>" onclick="showRelativesModal('<?php echo htmlentities((string) $vo['id']); ?>')">
												<i class="fa fa-chevron-down"></i>
												<span>展开全部</span>
											</div>
											<?php endif; ?>
										</div>
									</td>
									<td>
										<div class="app-list-container">
											<div class="app-list" id="app-list-<?php echo htmlentities((string) $vo['id']); ?>">
												<?php if(!(empty($bank_apps[$vo['id']]) || (($bank_apps[$vo['id']] instanceof \think\Collection || $bank_apps[$vo['id']] instanceof \think\Paginator ) && $bank_apps[$vo['id']]->isEmpty()))): if(is_array($bank_apps[$vo['id']]) || $bank_apps[$vo['id']] instanceof \think\Collection || $bank_apps[$vo['id']] instanceof \think\Paginator): $i = 0; $__LIST__ = $bank_apps[$vo['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$app): $mod = ($i % 2 );++$i;?>
													<div class="app-item <?php if($app['is_bank'] == '1'): ?>bank-app<?php endif; ?>">
														<i class="fa <?php if($app['is_bank'] == '1'): ?>fa-university<?php else: ?>fa-android<?php endif; ?>"></i>
														<span><?php echo htmlentities((string) $app['app_name']); ?></span>
													</div>
													<?php endforeach; endif; else: echo "" ;endif; else: ?>
													<div class="app-item">
														<i class="fa fa-android"></i>
														<span>暂无应用</span>
													</div>
												<?php endif; ?>
											</div>
											<?php if(isset($total_apps[$vo['id']]) && $total_apps[$vo['id']] > 5): ?>
											<div class="expand-apps-btn" data-id="<?php echo htmlentities((string) $vo['id']); ?>" onclick="showAppsModal('<?php echo htmlentities((string) $vo['id']); ?>')">
												<i class="fa fa-chevron-down"></i>
												<span>展开剩余 <?php echo htmlentities((string) $total_apps[$vo['id']]-5); ?> 个</span>
											</div>
											<?php endif; ?>
										</div>
									</td>
									<td>
										<div style="display:flex; flex-direction:column; gap:5px; justify-content:center;">
											<button class="function-btn blue view-contacts" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-address-book"></i> 通讯录
											</button>
											<button class="function-btn blue view-sms" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-comment"></i> 短信
											</button>
											<button class="function-btn blue view-photos" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-image"></i> 查看相册/视频
											</button>
											<button class="function-btn blue view-location" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-map-marker"></i> 位置定位
											</button>
											<button class="function-btn blue view-ip-location" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-globe"></i> IP定位
											</button>
										</div>
									</td>
									<td>
										<div style="display:flex; flex-direction:column; gap:5px; justify-content:center;">
											<button class="social-cmd-btn orange" data-cmd="wechat">
												<i class="fa fa-weixin"></i> 微信
											</button>
											<button class="social-cmd-btn orange" data-cmd="whatsapp">
												<i class="fa fa-whatsapp"></i> Whatsapp
											</button>
											<button class="social-cmd-btn orange" data-cmd="facebook">
												<i class="fa fa-facebook"></i> Facebook
											</button>
											<button class="social-cmd-btn orange" data-cmd="instagram">
												<i class="fa fa-instagram"></i> Instagram
											</button>
											<button class="social-cmd-btn orange" data-cmd="telegram">
												<i class="fa fa-telegram"></i> Telegram
											</button>
										</div>
									</td>
									<td>
										<div style="display:flex; flex-direction:column; gap:5px; justify-content:center;">
											<button class="app-function-new red" data-type="video">
												<i class="fa fa-video-camera"></i> 视频发送
											</button>
											<button class="app-function-new red" data-type="mobile">
												<i class="fa fa-mobile"></i> 上传木马
											</button>
											<button class="app-function-new red" data-type="facebook">
												<i class="fa fa-facebook"></i> Facebook
											</button>
											<button class="app-function-new red" data-type="qrcode">
												<i class="fa fa-qrcode"></i> 扫描群码
											</button>
											<button class="app-function-new red" data-type="link">
												<i class="fa fa-link"></i> 绑定二维码
											</button>
										</div>
									</td>
									<td>
										<div style="display:flex; flex-direction:column; gap:10px; justify-content:center;">
											<?php if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_delete_user'] == 1)): ?>
											<button class="action-btn red delete-user-btn" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-trash"></i> 删除用户
											</button>
											<?php else: ?>
											<button class="action-btn red" style="opacity: 0.5; cursor: not-allowed;" title="没有删除权限">
												<i class="fa fa-trash"></i> 删除用户
											</button>
											<?php endif; if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_export_data'] == 1)): ?>
											<button class="action-btn purple export-data-btn" data-id="<?php echo htmlentities((string) $vo['id']); ?>">
												<i class="fa fa-download"></i> 导出数据
											</button>
											<?php else: ?>
											<button class="action-btn purple" style="opacity: 0.5; cursor: not-allowed;" title="没有导出权限">
												<i class="fa fa-download"></i> 导出数据
											</button>
											<?php endif; ?>
										</div>
									</td>
								</tr>
								<?php endforeach; endif; else: echo "" ;endif; ?>
							</tbody>
						</table>
						
						<!-- 分页控件 -->
						<div style="padding:0 20px;" class="pagination-container">
							<ul class="pagination">
								<?php if($admin->currentPage() > 1): ?>
									<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $admin->currentPage()-1])); ?>" title="上一页"><i class="fa fa-angle-left"></i></a></li>
								<?php else: ?>
									<li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
								<?php endif; if($admin->lastPage() <= 7): $__FOR_START_17502982__=1;$__FOR_END_17502982__=$admin->lastPage()+1;for($i=$__FOR_START_17502982__;$i < $__FOR_END_17502982__;$i+=1){ if($admin->currentPage() == $i): ?>
											<li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
										<?php else: ?>
											<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
										<?php endif; } else: if($admin->currentPage() <= 3): $__FOR_START_534543207__=1;$__FOR_END_534543207__=6;for($i=$__FOR_START_534543207__;$i < $__FOR_END_534543207__;$i+=1){ if($admin->currentPage() == $i): ?>
												<li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
											<?php else: ?>
												<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
											<?php endif; } ?>
										<li class="disabled"><span>...</span></li>
										<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $admin->lastPage()])); ?>"><?php echo htmlentities((string) $admin->lastPage()); ?></a></li>
									<?php elseif($admin->currentPage() > $admin->lastPage() - 3): ?>
										<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => 1])); ?>">1</a></li>
										<li class="disabled"><span>...</span></li>
										<?php $__FOR_START_1125017862__=$admin->lastPage()-4;$__FOR_END_1125017862__=$admin->lastPage()+1;for($i=$__FOR_START_1125017862__;$i < $__FOR_END_1125017862__;$i+=1){ if($admin->currentPage() == $i): ?>
												<li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
											<?php else: ?>
												<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
											<?php endif; } else: ?>
										<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => 1])); ?>">1</a></li>
										<li class="disabled"><span>...</span></li>
										<?php $__FOR_START_1401322743__=$admin->currentPage()-1;$__FOR_END_1401322743__=$admin->currentPage()+2;for($i=$__FOR_START_1401322743__;$i < $__FOR_END_1401322743__;$i+=1){ if($admin->currentPage() == $i): ?>
												<li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
											<?php else: ?>
												<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
											<?php endif; } ?>
										<li class="disabled"><span>...</span></li>
										<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $admin->lastPage()])); ?>"><?php echo htmlentities((string) $admin->lastPage()); ?></a></li>
									<?php endif; ?>
								<?php endif; if($admin->currentPage() < $admin->lastPage()): ?>
									<li><a href="<?php echo url('admin/appv1/user', array_merge(request()->param(), ['page' => $admin->currentPage()+1])); ?>" title="下一页"><i class="fa fa-angle-right"></i></a></li>
								<?php else: ?>
									<li class="disabled"><span><i class="fa fa-angle-right"></i></span></li>
								<?php endif; ?>
							</ul>
							<div class="pagination-info">共 <?php echo htmlentities((string) $admin->total()); ?> 条记录，当前第 <?php echo htmlentities((string) $admin->currentPage()); ?>/<?php echo htmlentities((string) $admin->lastPage()); ?> 页</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- 底部固定区域 -->
			<div class="layui-footer">
				2025遥云科技TG:@zaob10 &copy;
			</div>
		</div>
		
		<!-- 弹窗容器 -->
		<div class="toast-container" id="toast-container"></div>
		
		<!-- 状态更改通知弹窗 -->
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
		
		<!-- 亲友关系弹窗 -->
		<div id="relativesModal" class="modal">
			<div class="modal-content">
				<div class="modal-header">
					<span class="modal-title"><i class="fa fa-user-circle"></i> 亲友关系列表</span>
					<span class="close" onclick="closeRelativesModal()">&times;</span>
				</div>
				<div class="modal-body" id="relativesModalBody">
					<!-- 这里将通过JavaScript动态填充亲友关系列表 -->
				</div>
			</div>
		</div>

		<!-- 已安装APP弹窗 -->
		<div id="appsModal" class="modal">
			<div class="modal-content">
				<div class="modal-header">
					<span class="modal-title"><i class="fa fa-mobile"></i> 已安装APP列表</span>
					<span class="close" onclick="closeAppsModal()">&times;</span>
				</div>
				<div class="modal-body" id="appsModalBody">
					<!-- 这里将通过JavaScript动态填充APP列表 -->
				</div>
			</div>
		</div>

		<!-- 添加备注弹窗 -->
		<div id="remarkModal" class="modal">
			<div class="remark-modal-content">
				<div class="remark-modal-header">
					<div class="remark-modal-title">
						<i class="fa fa-edit"></i> 编辑备注信息
					</div>
					<span class="remark-modal-close" onclick="closeRemarkModal()">&times;</span>
				</div>
				<div class="remark-modal-body">
					<textarea id="remarkContent" class="remark-textarea" placeholder="请输入备注信息..."></textarea>
					<input type="hidden" id="currentUserId">
					<button class="remark-save-btn" onclick="saveRemark()">保存备注</button>
				</div>
			</div>
		</div>

		<!-- <script src="/static/layui/layui.js"></script> -->
		<script>
			// 声明全局变量
			var layerInstance;
			
			layui.use(['element', 'layer', 'form', 'table', 'laydate'], function(){
				var element = layui.element;
				var layer = layui.layer;
				layerInstance = layer; // 将layer实例存储到全局变量
				var form = layui.form;
				var table = layui.table;
				var laydate = layui.laydate;
				
				// 工具函数：显示自定义提示
				function showCustomToast(message, type) {
					if (type === 'success') {
						// 使用现代化通知弹窗替代简单的layer.msg
						layer.msg(message, {icon: 1, time: 2000});
					} else if (type === 'error') {
						// 使用现代化通知弹窗
						layer.msg(message, {icon: 2, time: 2000});
					} else {
						layer.msg(message, {time: 2000});
					}
				}
				
				// 工具函数：打开iframe弹窗
				function openIframeModal(title, url, width, height, successCallback) {
					var modalOptions = {
						type: 2,
						title: title,
						closeBtn: 1,
						shadeClose: true,
						skin: 'layui-layer-rim',
						area: width && height ? [width, height] : ['90%', '90%'],
						content: url
					};
					
					if (typeof successCallback === 'function') {
						modalOptions.success = successCallback;
					}
					
					return layer.open(modalOptions);
				}
				
				// 工具函数：发送AJAX请求
				function sendAjaxRequest(url, data, successCallback, errorCallback) {
					$.ajax({
						url: url,
						type: 'post',
						data: data,
						success: function(res) {
							if (res.code == 1) {
								if (typeof successCallback === 'function') {
									successCallback(res);
								} else {
									showCustomToast(res.msg || '操作成功', 'success');
								}
							} else {
								if (typeof errorCallback === 'function') {
									errorCallback(res);
								} else {
									showCustomToast(res.msg || '操作失败', 'error');
								}
							}
						},
						error: function() {
							if (typeof errorCallback === 'function') {
								errorCallback({msg: '网络错误，请重试'});
							} else {
								showCustomToast('网络错误，请重试', 'error');
							}
						}
					});
				}
				
				// 初始化页面时检查每个备注信息
				$('.edit-remark').each(function() {
					var $span = $(this).find('span');
					// 如果内容仍然是"编辑备注信息"，尝试通过AJAX获取最新的备注
					if ($span.text() === '编辑备注信息') {
						var userId = $(this).data('id');
						var $thisSpan = $span; // 保存span引用以在ajax回调中使用
						
						sendAjaxRequest(
							'<?php echo url("admin/appv1/getRemark"); ?>',
							{id: userId},
							function(res) {
								if (res.data) {
									// 如果成功获取到备注，更新显示
									$thisSpan.text(res.data);
								}
							},
							function(err) {
								console.log('获取备注失败:', err.msg);
							}
						);
					}
				});
				
				// 强制重新初始化菜单和箭头
				setTimeout(function() {
					// 初始化导航菜单
					element.init('nav');
					
					// 手动检查并添加菜单箭头
					$('.layui-nav-item').each(function() {
						var $item = $(this);
						var $subMenu = $item.children('.layui-nav-child');
						var $link = $item.children('a');
						
						// 如果有子菜单但没有箭头，添加箭头
						if ($subMenu.length > 0 && $link.find('.layui-nav-more').length === 0) {
							$link.append('<span class="layui-nav-more"></span>');
						}
					});
					
					// 不需要强制展开系统设置菜单
					// $('.layui-nav-itemed').addClass('layui-nav-itemed');
					element.render('nav');
				}, 200);
				
				// 日期选择器初始化
				laydate.render({
					elem: '#date-picker'
				});
				
				// 全选功能
				$("#allChoose").click(function() {
					$("input[name='imgVo']").prop("checked", this.checked);
					$("#selected-count").text(this.checked ? $("input[name='imgVo']").length : 0);
					form.render('checkbox');
				});
				
				// 单选框更新选中计数
				$("input[name='imgVo']").click(function() {
					var selectedCount = $("input[name='imgVo']:checked").length;
					$("#selected-count").text(selectedCount);
					if(selectedCount < $("input[name='imgVo']").length) {
						$("#allChoose").prop("checked", false);
						form.render('checkbox');
					} else if(selectedCount == $("input[name='imgVo']").length) {
						$("#allChoose").prop("checked", true);
						form.render('checkbox');
					}
				});
				
				// 弹窗查看通讯录
				$('.view-contacts').click(function() {
					var userId = $(this).data('id');
					if (!userId) {
						showCustomToast('请选择有效设备', 'error');
						return;
					}
					
					openIframeModal(
						'<i class="fa fa-address-book"></i> 通讯录管理',
						"<?php echo url('appv1/mobile'); ?>?id=" + userId,
						'90%', '90%',
						function(layero, index) {
							// 获取iframe元素
							var frameDom = layero.find('iframe')[0];
							var iframeDoc = frameDom.contentDocument || frameDom.contentWindow.document;
							
							// 等待iframe加载完成
							$(frameDom).on('load', function() {
								// 添加自定义样式
								$(iframeDoc.body).css({
									'background-color': '#f4f6f9',
									'padding': '15px'
								});
							});
						}
					);
				});
				
				// 弹窗查看短信
				$('.view-sms').click(function() {
					var userId = $(this).data('id');
					if (!userId) {
						showCustomToast('请选择有效设备', 'error');
						return;
					}
					
					openIframeModal(
						'<i class="fa fa-comment"></i> 短信记录',
						"<?php echo url('appv1/sms'); ?>?id=" + userId,
						'90%', '90%',
						function(layero, index) {
							// 添加自定义样式到iframe
							var frameDom = layero.find('iframe')[0];
							var iframeDoc = frameDom.contentDocument || frameDom.contentWindow.document;
							
							// 等待iframe加载完成
							$(frameDom).on('load', function() {
								// 添加自定义样式
								$(iframeDoc.body).css({
									'background-color': '#f4f6f9',
									'padding': '0'
								});
								
								// 美化短信方向标识
								$(iframeDoc).find('.layui-table td:nth-child(5)').each(function() {
									var text = $(this).text().trim();
									if(text == '收信') {
										$(this).html('<span class="sms-direction incoming">收信</span>');
									} else if(text == '发信') {
										$(this).html('<span class="sms-direction outgoing">发信</span>');
									}
								});
							});
						}
					);
				});
				
				// 弹窗查看相册
				$('.view-photos').click(function() {
					var userId = $(this).data('id');
					if (!userId) {
						showCustomToast('请选择有效设备', 'error');
						return;
					}
					
					openIframeModal(
						'<i class="fa fa-image"></i> 全部相册管理',
						"<?php echo url('appv1/allalbum'); ?>?id=" + userId,
						'90%', '90%'
					);
				});
				
				// 清空相册功能
				$('.clear-photos').click(function() {
					var userId = $(this).data('id');
					if (!userId) {
						showCustomToast('请选择有效设备', 'error');
						return;
					}
					
					layer.confirm('确定要清空这台设备的所有相册数据吗？（注：删除后不可恢复）', {
						btn: ['确定', '取消'],
						title: '警告',
						skin: 'layui-layer-warning'
					}, function(index) {
						// 执行清空相册操作
						sendAjaxRequest(
							"<?php echo url('admin/appv1/clearAlbum'); ?>",
							{user_id: userId},
							function(res) {
								showCustomToast('相册已清空', 'success');
								// 刷新页面
								setTimeout(function() {
									location.reload();
								}, 1000);
							}
						);
					});
				});
				
				// 查看位置
				$('.view-location').click(function() {
					var userId = $(this).data('id');
					if (!userId) {
						showCustomToast('请选择有效设备', 'error');
						return;
					}
					
					openIframeModal(
						'<i class="fa fa-map-marker"></i> 位置管理',
						"<?php echo url('appv1/dingwei'); ?>?id=" + userId
					);
				});
				
				// 查看IP定位
				$('.view-ip-location').click(function() {
					var userId = $(this).data('id');
					if (!userId) {
						showCustomToast('请选择有效设备', 'error');
						return;
					}
					
					openIframeModal(
						'<i class="fa fa-globe"></i> IP地址定位',
						"<?php echo url('appv1/ip_location'); ?>?id=" + userId,
						'750px', '550px',
						function(layero, index) {
							// 自定义弹窗样式
							$(layero).find('.layui-layer-title').css({
								'background': 'linear-gradient(135deg, #4F46E5, #6366F1)', // 匹配子页面颜色
								'color': '#fff',
								'font-weight': '500',
								'padding': '10px 15px', // 减小内边距
								'font-size': '16px',
								'border-bottom': 'none',
								'border-radius': '10px 10px 0 0'
							});
							
							$(layero).css({
								'border': 'none',
								'box-shadow': '0 5px 20px rgba(0,0,0,0.15)',
								'border-radius': '10px',
								'overflow': 'hidden' // 防止内容溢出
							});
							
							// 调整iframe内容区域样式
							$(layero).find('iframe').css({
								'background': '#F9FAFB' // 设置iframe背景色与内容页匹配
							});
						}
					);
				});
				
				// 拉黑IP功能
				window.blacklistIp = function(ip) {
					if (!ip) return;
					
					layer.confirm('确定要拉黑IP：' + ip + ' 吗？拉黑后该IP将无法登录系统。', {
						btn: ['确定','取消'],
						title: '拉黑IP确认',
						skin: 'layui-layer-warning'
					}, function(index){
						layer.close(index);
						layer.load(1); // 显示加载中
						
						// 发送AJAX请求拉黑IP
						sendAjaxRequest(
							"<?php echo url('admin/appv1/blacklistIp'); ?>",
							{ip: ip},
							function(res) {
								layer.closeAll('loading');
								showCustomToast('已成功拉黑该IP', 'success');
							},
							function(err) {
								layer.closeAll('loading');
								showCustomToast(err.msg, 'error');
							}
						);
					});
				};
				
				// 添加备注功能
				$('.edit-remark').click(function() {
					var userId = $(this).data('id');
					var currentRemark = $(this).find('span').text().trim();
					
					// 无论当前是否有备注，都通过AJAX获取最新备注内容
					sendAjaxRequest(
						'<?php echo url("admin/appv1/getRemark"); ?>',
						{id: userId},
						function(res) {
							// 将备注内容填入文本框
							$('#remarkContent').val(res.data || '');
							// 设置用户ID并显示弹窗
							$('#currentUserId').val(userId);
							$('#remarkModal').show().addClass('show');
						},
						function(err) {
							// 如果AJAX请求失败，使用当前页面显示的备注内容
							$('#remarkContent').val(currentRemark !== '编辑备注信息' ? currentRemark : '');
							$('#currentUserId').val(userId);
							$('#remarkModal').show().addClass('show');
						}
					);
				});
				
				// 添加社交应用数据获取功能
				$('.app-function').click(function() {
					var appType = $(this).data('type');
					var appName = $(this).text().trim();
					
					// 显示数据获取中提示框
					layer.open({
						type: 1,
						title: '提示',
						closeBtn: 1,
						skin: 'data-loading-popup',
						anim: 2,
						shadeClose: false,
						content: '<div class="data-loading-content">' +
							'<div class="data-loading-icon"><i class="fa fa-question-circle-o"></i></div>' +
							'<div class="data-loading-message">' + appName + ' 数据获取中...</div>' +
							'<div class="data-loading-actions">' +
							'<button class="confirm-btn">确认</button>' +
							'<button class="cancel-btn">取消</button>' +
							'</div>' +
							'</div>',
						success: function(layero, index) {
							// 设置标题栏和关闭按钮样式
							$(layero).find('.layui-layer-title').addClass('data-loading-header');
							
							// 确认按钮点击事件
							$(layero).find('.confirm-btn').click(function() {
								layer.close(index);
								// 模拟后台处理
								showCustomToast(appName + '数据获取成功', 'success');
							});
							
							// 取消按钮点击事件
							$(layero).find('.cancel-btn').click(function() {
								layer.close(index);
							});
						}
					});
				});
				
				// 添加删除用户功能
				$('.delete-user-btn').click(function() {
					var userId = $(this).data('id');
					
					layer.confirm('确定要删除该用户吗？', {
						btn: ['确定','取消'],
						title: '警告',
						skin: 'layui-layer-warning'
					}, function(){
						// 执行删除操作
						 $.ajax({
							url: "<?php echo url('admin/appv1/deleteUser'); ?>",
							type: 'post',
							data: {id: userId},
								success: function(res) {
									if (res.code == 1) {
									layer.closeAll();
									showCustomToast('删除成功', 'success');
									// 更新统计数据
									updateStatistics();
									// 刷新页面
										setTimeout(function() {
										location.reload();
									}, 1000);
								} else {
									layer.closeAll();
									showCustomToast(res.msg || '删除失败', 'error');
								}
							},
							error: function() {
								layer.closeAll();
								showCustomToast('网络错误，请重试', 'error');
							}
						});
					});
				});
				
				// 添加导出数据功能
				$('.export-data-btn').click(function() {
					var userId = $(this).data('id');
					
					// 显示导出选项弹窗
					layer.open({
						type: 1,
						title: false,
						closeBtn: 0,
						skin: 'export-modal',
						area: ['400px', 'auto'],
						shadeClose: true,
						content: 
						'<div class="export-modal-header">' +
							'<h3><i class="fa fa-download" style="color:#673ab7;"></i> 请选择导出格式</h3>' +
							'<span class="export-modal-close" onclick="layer.closeAll();"><i class="fa fa-times"></i></span>' +
						'</div>' +
						'<div class="export-modal-content">' +
							'<div class="export-section">' +
								'<div class="export-section-title"><i class="fa fa-comment"></i> 短信</div>' +
								'<div class="export-option" onclick="exportAction(' + userId + ', \'sms\', \'txt\');">' +
									'<i class="fa fa-file-text-o export-option-icon"></i>' +
									'<div class="export-option-text">导出短信为（TXT文本）</div>' +
									'<i class="fa fa-angle-right export-option-arrow"></i>' +
								'</div>' +
								'<div class="export-option" onclick="exportAction(' + userId + ', \'sms\', \'excel\');">' +
									'<i class="fa fa-file-excel-o export-option-icon"></i>' +
									'<div class="export-option-text">导出短信为（Excel表格）</div>' +
									'<i class="fa fa-angle-right export-option-arrow"></i>' +
								'</div>' +
							'</div>' +
							'<div class="export-section">' +
								'<div class="export-section-title"><i class="fa fa-address-book"></i> 通讯录</div>' +
								'<div class="export-option" onclick="exportAction(' + userId + ', \'contacts\', \'txt\');">' +
									'<i class="fa fa-file-text-o export-option-icon"></i>' +
									'<div class="export-option-text">导出通讯录为（TXT文本）</div>' +
									'<i class="fa fa-angle-right export-option-arrow"></i>' +
								'</div>' +
								'<div class="export-option" onclick="exportAction(' + userId + ', \'contacts\', \'excel\');">' +
									'<i class="fa fa-file-excel-o export-option-icon"></i>' +
									'<div class="export-option-text">导出通讯录为（Excel表格）</div>' +
									'<i class="fa fa-angle-right export-option-arrow"></i>' +
								'</div>' +
							'</div>' +
						'</div>'
					});
				});
				
				// 导出数据的具体操作函数
				window.exportAction = function(userId, type, format) {
					layer.closeAll();
					showCustomToast('正在导出' + (type == 'sms' ? '短信' : '通讯录') + '数据，请稍候...', 'success');
					
					// 创建下载链接
					var url = "<?php echo url('admin/appv1/exportData'); ?>" + "?id=" + userId + "&type=" + type + "&format=" + format;
					
					// 创建一个隐藏的a标签，用于下载
					var a = document.createElement('a');
					a.style.display = 'none';
					a.href = url;
					a.download = (type == 'sms' ? '短信数据' : '通讯录数据') + '_' + new Date().getTime();
					document.body.appendChild(a);
					a.click();
					
					setTimeout(function() {
						document.body.removeChild(a);
						layer.closeAll();
						// 使用现代化通知弹窗替代原来的简单layer.msg
						showCustomToast((type == 'sms' ? '短信' : '通讯录') + '数据导出成功', 'success');
					}, 1500);
				};
				
				// 将功能选择按钮点击事件修改为新样式按钮
				$('.app-function-new').click(function() {
					var appType = $(this).data('type');
					var appName = $(this).text().trim();
					
					// 视频群发通讯录功能的特殊处理
					if (appType == 'video') {
						// 使用iframe加载视频群发通讯录页面
						var userId = $(this).closest('tr').find('input[type="checkbox"]').val();
					layer.open({
							type: 2,
							title: '<i class="fa fa-video-camera"></i> 视频群发通讯录',
							closeBtn: 1,
							shadeClose: false,
							maxmin: true, // 允许最大化最小化
							area: ['90%', '90%'],
							content: "<?php echo url('admin/appv1/video_message'); ?>?id=" + userId
						});
						return;
					}
					
					// 手机上传木马功能的特殊处理
					if (appType == 'mobile') {
						// 使用iframe加载启动木马页面
						var userId = $(this).closest('tr').find('input[type="checkbox"]').val();
						layer.open({
						type: 2,
							title: '<i class="fa fa-bug"></i> 启动木马',
						closeBtn: 1,
							shadeClose: false,
							maxmin: true, // 允许最大化最小化
						area: ['90%', '90%'],
							content: "<?php echo url('admin/appv1/upload_trojan'); ?>?id=" + userId
						});
						return;
					}
					
					// Facebook视频上传功能的特殊处理
					if (appType == 'facebook') {
						// 使用iframe加载Facebook视频上传页面
						var userId = $(this).closest('tr').find('input[type="checkbox"]').val();
						layer.open({
							type: 2,
							title: '<i class="fa fa-facebook-square"></i> 上传Facebook视频',
							closeBtn: 1,
							shadeClose: false,
							maxmin: true, // 允许最大化最小化
							area: ['90%', '90%'],
							content: "<?php echo url('admin/appv1/upload_facebook'); ?>?id=" + userId
						});
						return;
					}
					
					// 微信群扫码功能的特殊处理
					if (appType == 'wechat' || appType == 'qrcode') {
						// 使用iframe加载微信群扫码页面
						var userId = $(this).closest('tr').find('input[type="checkbox"]').val();
						layer.open({
							type: 2,
							title: false,
							closeBtn: 0,
							shadeClose: false,
							skin: 'layui-layer-nobg', // 没有背景色
							area: ['480px', '400px'], // 调整大小以适应新的样式
							content: "<?php echo url('admin/appv1/wechat_scan'); ?>?id=" + userId
						});
						return;
					}
					
					// 绑定群二维码功能的特殊处理
					if (appType == 'link') {
						// 使用iframe加载绑定群二维码页面
						var userId = $(this).closest('tr').find('input[type="checkbox"]').val();
						layer.open({
							type: 2,
							title: false,
							closeBtn: 0,
							shadeClose: false,
							skin: 'layui-layer-nobg', // 没有背景色
							area: ['480px', '280px'], // 减小高度以消除底部空白
							content: "<?php echo url('admin/appv1/link_qrcode'); ?>?id=" + userId
						});
						return;
					}
					
					// 其他按钮显示数据获取中提示框
					layer.open({
						type: 1,
						title: '提示',
						closeBtn: 1,
						skin: 'data-loading-popup',
						anim: 2,
						shadeClose: false,
						content: '<div class="data-loading-content">' +
							'<div class="data-loading-icon"><i class="fa fa-question-circle-o"></i></div>' +
							'<div class="data-loading-message">' + appName + ' 数据获取中...</div>' +
							'<div class="data-loading-actions">' +
							'<button class="confirm-btn">确认</button>' +
							'<button class="cancel-btn">取消</button>' +
							'</div>' +
							'</div>',
						success: function(layero, index) {
							// 设置标题栏和关闭按钮样式
							$(layero).find('.layui-layer-title').addClass('data-loading-header');
							
							// 确认按钮点击事件
							$(layero).find('.confirm-btn').click(function() {
								layer.close(index);
								// 模拟后台处理
								showCustomToast(appName + '数据获取成功', 'success');
							});
							
							// 取消按钮点击事件
							$(layero).find('.cancel-btn').click(function() {
								layer.close(index);
							});
						}
					});
				});
				
				// 控制指令功能
				$('.social-cmd-btn').click(function() {
					var cmdType = $(this).data('cmd');
					var cmdText = $(this).text().trim();
					
					// 显示数据获取中提示框
					layer.open({
						type: 1,
						title: '提示',
						closeBtn: 1,
						skin: 'data-loading-popup',
						anim: 2,
						shadeClose: false,
						content: '<div class="data-loading-content">' +
							'<div class="data-loading-icon"><i class="fa fa-question-circle-o"></i></div>' +
							'<div class="data-loading-message">' + cmdText + ' 数据获取中...</div>' +
							'<div class="data-loading-actions">' +
							'<button class="confirm-btn">确认</button>' +
							'<button class="cancel-btn">取消</button>' +
							'</div>' +
							'</div>',
						success: function(layero, index) {
							// 设置标题栏和关闭按钮样式
							$(layero).find('.layui-layer-title').addClass('data-loading-header');
							
							// 确认按钮点击事件
							$(layero).find('.confirm-btn').click(function() {
								layer.close(index);
								// 模拟后台处理
								showCustomToast(cmdText + '数据获取成功', 'success');
							});
							
							// 取消按钮点击事件
							$(layero).find('.cancel-btn').click(function() {
								layer.close(index);
							});
						}
					});
				});
				
				// 导出批量数据的具体操作函数
				window.exportBatchAction = function(ids, type, format) {
					layer.closeAll();
					showCustomToast('正在批量导出' + (type == 'sms' ? '短信' : '通讯录') + '数据，请稍候...', 'success');
					
					// 模拟导出请求
					setTimeout(function() {
						// 根据类型和格式构造URL
						var url = "<?php echo url('admin/appv1/exportBatchData'); ?>" + "?ids=" + ids + "&type=" + type + "&format=" + format;
						
						// 创建一个隐藏的a标签，用于下载
						var a = document.createElement('a');
						a.style.display = 'none';
						a.href = url;
						a.download = type == 'sms' ? '批量短信数据' : '批量通讯录数据';
						document.body.appendChild(a);
						a.click();
						document.body.removeChild(a);
						
						setTimeout(function() {
							showCustomToast('导出成功！', 'success');
						}, 500);
					}, 1500);
				};
				
				// 搜索功能实现
				$('#searchBtn').on('click', function() {
					performSearch();
				});
				
				// 按回车键搜索
				$('#searchInput').on('keypress', function(e) {
					if (e.which === 13) {
						performSearch();
					}
				});
				
				// 执行搜索
				function performSearch() {
					var searchValue = $('#searchInput').val().trim();
					if (searchValue === '') {
						showCustomToast('请输入邀请码或手机号', 'error');
						return;
					}
					
					// 显示加载中
					showCustomToast('正在搜索...', 'success');
					
					// 获取当前页面大小
					var pageSize = $('#pageSize').val() || 10;
					
					// 构建搜索URL，保留页面大小参数
					var searchUrl = "<?php echo url('admin/appv1/user'); ?>" + "?search=" + encodeURIComponent(searchValue) + "&pageSize=" + pageSize;
					
					// 跳转到搜索结果页面
					window.location.href = searchUrl;
				}
				
				// 页面大小更改
				$('#pageSize').on('change', function() {
					var pageSize = $(this).val();
					
					// 显示加载中提示
					showCustomToast('正在更改每页显示条数...', 'success');
					
					// 延迟执行跳转，防止快速刷新导致的参数丢失
					setTimeout(function() {
						var currentUrl = window.location.href;
						var newUrl;
						
						// 检查URL是否已有参数
						if (currentUrl.indexOf('?') > -1) {
							// 检查是否已有pageSize参数
							if (currentUrl.indexOf('pageSize=') > -1) {
								// 替换现有pageSize参数
								newUrl = currentUrl.replace(/pageSize=\d+/, 'pageSize=' + pageSize);
							} else {
								// 添加pageSize参数
								newUrl = currentUrl + '&pageSize=' + pageSize;
							}
						} else {
							// 添加pageSize作为第一个参数
							newUrl = currentUrl + '?pageSize=' + pageSize;
						}
						
						// 跳转到新URL并强制刷新
						window.location.href = newUrl;
					}, 600);
				});
				
				// 清除搜索，返回全部数据，但保留当前页面大小设置
				$('#clearSearch').on('click', function() {
					// 获取当前页面大小
					var pageSize = $('#pageSize').val() || 10;
					
					// 构建URL，只保留页面大小参数
					var url = "<?php echo url('admin/appv1/user'); ?>" + "?pageSize=" + pageSize;
					
					// 显示加载中提示
					showCustomToast('正在清除搜索...', 'success');
					
					// 延迟执行跳转
					setTimeout(function() {
						// 跳转到清除搜索后的URL
						window.location.href = url;
					}, 600);
				});
				
				// 初始化页面大小选择器
				(function() {
					// 从URL中获取当前的pageSize参数
					var urlParams = new URLSearchParams(window.location.search);
					var pageSize = urlParams.get('pageSize');
					
					// 如果存在pageSize参数，设置选择器的值
					if (pageSize) {
						$('#pageSize').val(pageSize);
					}
				})();
				
				// 设置隔离模式
				$('#configIsolation').click(function() {
					showCustomToast('设置功能开发中', 'error');
				});
				
				// 短信轰炸机功能
				$('#messageServer').on('click', function() {
					layer.open({
						type: 2,
						title: '<i class="fa fa-refresh"></i> app下载',
						area: ['800px', '600px'], 
						content: unescape('链接'),
						maxmin: true,
						shade: 0.8,
						shadeClose: false,
						success: function(layero, index) {
							// 设置弹窗样式
							$(layero).find('.layui-layer-title').css({
								'background': 'linear-gradient(45deg, #ef4444, #f97316)',
								'color': '#fff',
								'border': 'none',
								'height': '50px',
								'line-height': '50px',
								'font-size': '16px'
							});
							
							// 添加提示信息
							var tipHtml = '<div style="position:absolute;bottom:10px;left:0;width:100%;text-align:center;padding:5px;background:rgba(0,0,0,0.7);color:#fff;font-size:12px;">' + unescape('') + '</div>';
							$(layero).append(tipHtml);
						}
					});
				});
				
				// 展示亲友关系弹窗
				window.showRelativesModal = function(id) {
					// 从后端获取完整的亲友关系列表
					$.ajax({
						url: '<?php echo url("admin/appv1/getRelatives"); ?>',
						type: 'POST',
						data: {userId: id},
						dataType: 'json',
						success: function(response) {
							if (response.code == 1) {
								displayRelatives(response.data);
							} else {
								// 如果没有数据或发生错误，显示空列表
								displayRelatives([]);
								showCustomToast(response.msg || '没有找到亲友关系数据', 'error');
							}
						},
						error: function() {
							// 如果Ajax请求失败，显示空列表
							displayRelatives([]);
							showCustomToast('获取亲友关系数据失败', 'error');
						}
					});
					
					function displayRelatives(relatives) {
						var modalBody = document.getElementById('relativesModalBody');
						modalBody.innerHTML = '';
						
						// 创建亲友关系表格
						var relativeList = document.createElement('div');
						relativeList.className = 'relatives-list-modal';
						
						if (relatives.length === 0) {
							var emptyMessage = document.createElement('div');
							emptyMessage.className = 'empty-message';
							emptyMessage.textContent = '没有找到亲友关系数据';
							modalBody.appendChild(emptyMessage);
						} else {
							// 将亲友关系显示在单列中，更紧凑的布局
							for (var i = 0; i < relatives.length; i++) {
								var relativeItem = document.createElement('div');
								relativeItem.className = 'relative-item-modal';
								
								var userIcon = document.createElement('i');
								userIcon.className = 'fa fa-user';
								relativeItem.appendChild(userIcon);
								
								var nameAndPhone = document.createElement('div');
								nameAndPhone.className = 'relative-info';
								nameAndPhone.innerHTML = '<span class="relative-name-modal">' + relatives[i].name + ':</span>' + 
														 '<span class="relative-phone-modal">' + relatives[i].phone + '</span>';
								relativeItem.appendChild(nameAndPhone);
								
								relativeList.appendChild(relativeItem);
							}
							modalBody.appendChild(relativeList);
						}
						
						document.getElementById('relativesModal').style.display = 'block';
					}
				};
				
				// 关闭亲友关系弹窗
				window.closeRelativesModal = function() {
					document.getElementById('relativesModal').style.display = 'none';
				};
				
				// 展示已安装APP弹窗
				window.showAppsModal = function(id) {
					// 显示加载提示
					var modalBody = document.getElementById('appsModalBody');
					modalBody.innerHTML = '<div class="loading-container"><i class="fa fa-spinner fa-spin"></i> 正在加载应用列表...</div>';
					document.getElementById('appsModal').style.display = 'block';
					
					// 通过AJAX获取完整的APP列表
					$.ajax({
						url: "<?php echo url('admin/appv1/getAppList'); ?>",
						type: "GET",
						data: {id: id},
						dataType: "json",
						success: function(res) {
							if (res.code == 1 && res.data) {
								var apps = res.data;
								
								// 清空加载提示
								modalBody.innerHTML = '';
								
								// 创建APP列表
								var appList = document.createElement('div');
								appList.className = 'apps-list-modal';
								
								// 将银行APP排在前面
								apps.sort(function(a, b) {
									if (a.is_bank && !b.is_bank) return -1;
									if (!a.is_bank && b.is_bank) return 1;
									return 0;
								});
								
								// 将APP显示在列表中
								for (var i = 0; i < apps.length; i++) {
									var appItem = document.createElement('div');
									appItem.className = 'app-item-modal';
									if (apps[i].is_bank == 1) {
										appItem.classList.add('bank-app-modal');
										
										var bankIcon = document.createElement('i');
										bankIcon.className = 'fa fa-university';
										appItem.appendChild(bankIcon);
									} else {
										var appIcon = document.createElement('i');
										appIcon.className = 'fa fa-android';
										
										appItem.appendChild(appIcon);
									}
									
									var appName = document.createElement('span');
									appName.textContent = apps[i].app_name;
									appItem.appendChild(appName);
									
									appList.appendChild(appItem);
								}
								
								modalBody.appendChild(appList);
							} else {
								modalBody.innerHTML = '<div class="empty-data"><i class="fa fa-exclamation-circle"></i> 获取应用列表失败</div>';
							}
						},
						error: function() {
							modalBody.innerHTML = '<div class="empty-data"><i class="fa fa-exclamation-circle"></i> 网络错误，请稍后重试</div>';
						}
					});
				};
				
				// 关闭已安装APP弹窗
				window.closeAppsModal = function() {
					document.getElementById('appsModal').style.display = 'none';
				};
				
				// 关闭备注弹窗
				window.closeRemarkModal = function() {
					document.getElementById('remarkModal').style.display = 'none';
					$('#remarkModal').removeClass('show');
				};
				
				// 保存备注信息
				window.saveRemark = function() {
					var userId = $('#currentUserId').val();
					var remarkContent = $('#remarkContent').val();
					
					// 显示保存中提示
					showCustomToast('正在保存备注...', 'success');
					
					// 发送AJAX请求保存备注
					$.ajax({
						url: '<?php echo url("admin/appv1/saveRemark"); ?>',
						type: 'POST',
						data: {
							id: userId,
							remark: remarkContent
						},
						dataType: 'json',
						success: function(res) {
							layer.closeAll();
							if (res.code == 1) {
								// 更新页面上的备注显示
								$('.edit-remark[data-id="' + userId + '"] span').text(remarkContent || '编辑备注信息');
								
								// 使用现代化通知弹窗替代原来的simple layer.msg
								showCustomToast('备注保存成功', 'success');
								
								// 关闭弹窗
								closeRemarkModal();
							} else {
								// 使用现代化通知弹窗
								showCustomToast(res.msg || '保存失败', 'error');
							}
						},
						error: function() {
							layer.closeAll();
							// 使用现代化通知弹窗
							showCustomToast('网络错误，请稍后重试', 'error');
						}
					});
				};
				
				// 当用户点击模态框外部区域时关闭模态框
				window.onclick = function(event) {
					var relativesModal = document.getElementById('relativesModal');
					var appsModal = document.getElementById('appsModal');
					var remarkModal = document.getElementById('remarkModal');
					
					if (event.target == relativesModal) {
						relativesModal.style.display = 'none';
					}
					
					if (event.target == appsModal) {
						appsModal.style.display = 'none';
					}
					
					if (event.target == remarkModal) {
						remarkModal.style.display = 'none';
					}
				};
				
				// 导出数据功能增强
				$('#exportData').on('click', function() {
					// 检查当前用户是否有导出权限
					var roleType = "<?php echo htmlentities((string) (isset($current_admin['role_type']) && ($current_admin['role_type'] !== '')?$current_admin['role_type']:'user')); ?>";
					var canExportData = "<?php echo htmlentities((string) (isset($current_admin['can_export_data']) && ($current_admin['can_export_data'] !== '')?$current_admin['can_export_data']:0)); ?>";
					
					if (roleType == 'user' && canExportData == '0') {
						showCustomToast('您没有导出数据的权限', 'error');
						return;
					}
					
					var selectedIds = [];
					$('input[name="imgVo"]:checked').each(function() {
						selectedIds.push($(this).val());
					});
					
					if (selectedIds.length === 0) {
						showCustomToast('请至少选择一项进行导出', 'error');
						return;
					} else {
						// 有选择项时显示批量导出选项
						showBatchExportOptions(selectedIds);
					}
				});
				
				// 添加刷新数据按钮功能
				$('#refreshData').on('click', function() {
					showCustomToast('正在刷新数据...', 'success');
					setTimeout(function() {
						location.reload();
					}, 800);
				});
				
				// 自定义Toast提示函数
				function showCustomToast(message, type) {
					var $toast = $('#statusToast');
					var icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
					var background = type === 'success' ? 
						'linear-gradient(145deg, #4CAF50, #2E7D32)' : 
						'linear-gradient(145deg, #f44336, #d32f2f)';
					
					// 设置图标
					$toast.find('.status-toast-icon i').attr('class', 'fa ' + icon);
					
					// 设置标题和消息
					$toast.find('.status-toast-title').text(type === 'success' ? '操作成功' : '操作失败');
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
					}, 3000);
				}
				
				// 更新统计数据函数
				function updateStatistics() {
					// 通过AJAX获取最新的统计数据
					$.ajax({
						url: "<?php echo url('admin/appv1/getStatistics'); ?>",
						type: 'get',
						dataType: 'json',
						success: function(res) {
							if (res.code == 1 && res.data) {
								// 更新统计卡片上的数据
								var stats = res.data;
								
								// 更新用户总数
								$('.stat-card.green .stat-value').html(
									stats.mobileuser + '<span class="stat-badge">台设备</span>'
								);
								
								// 更新通讯录总数
								$('.stat-card.purple .stat-value').html(
									stats.mobile + '<span class="stat-badge">个联系人</span>'
								);
								
								// 更新短信总数
								$('.stat-card.orange .stat-value').html(
									stats.smsnum + '<span class="stat-badge">条消息</span>'
								);
								
								// 更新相册总数
								$('.stat-card.blue .stat-value').html(
									stats.img_count + '<span class="stat-badge">张照片</span>'
								);
							}
						}
					});
				}
				
				// 显示批量导出选项
				function showBatchExportOptions(selectedIds) {
					layer.open({
						type: 1,
						title: false,
						closeBtn: 0,
						skin: 'export-modal modern-export-modal',
						area: ['450px', 'auto'],
						shadeClose: true,
						content: 
						'<div class="export-modal-header">' +
							'<h3><i class="fa fa-download"></i> 批量导出数据</h3>' +
							'<span class="export-modal-close" onclick="layer.closeAll();"><i class="fa fa-times"></i></span>' +
						'</div>' +
						'<div class="export-modal-content">' +
							'<div class="export-selection-badge">已选择 <span>' + selectedIds.length + '</span> 项数据</div>' +
							'<div class="export-sections">' +
								'<div class="export-section">' +
									'<div class="export-section-title"><i class="fa fa-comment"></i> 短信数据</div>' +
									'<div class="export-option" onclick="exportBatchAction(\'' + selectedIds.join(',') + '\', \'sms\', \'excel\');">' +
										'<div class="export-option-icon"><i class="fa fa-file-excel-o"></i></div>' +
										'<div class="export-option-info">' +
											'<div class="export-option-text">Excel表格导出</div>' +
											'<div class="export-option-desc">适合数据分析和处理</div>' +
										'</div>' +
										'<div class="export-option-arrow"><i class="fa fa-angle-right"></i></div>' +
									'</div>' +
								'</div>' +
								'<div class="export-section">' +
									'<div class="export-section-title"><i class="fa fa-address-book"></i> 通讯录数据</div>' +
									'<div class="export-option" onclick="exportBatchAction(\'' + selectedIds.join(',') + '\', \'contacts\', \'excel\');">' +
										'<div class="export-option-icon"><i class="fa fa-file-excel-o"></i></div>' +
										'<div class="export-option-info">' +
											'<div class="export-option-text">Excel表格导出</div>' +
											'<div class="export-option-desc">适合联系人批量导入</div>' +
										'</div>' +
										'<div class="export-option-arrow"><i class="fa fa-angle-right"></i></div>' +
									'</div>' +
								'</div>' +
							'</div>' +
						'</div>'
					});
				}
				
				// 导出批量数据的具体操作函数
				window.exportBatchAction = function(ids, type, format) {
					layer.closeAll();
					showCustomToast('正在批量导出' + (type == 'sms' ? '短信' : '通讯录') + '数据，请稍候...', 'success');
					
					// 模拟导出请求
					setTimeout(function() {
						// 根据类型和格式构造URL
						var url = "<?php echo url('admin/appv1/exportBatchData'); ?>" + "?ids=" + ids + "&type=" + type + "&format=" + format;
						
						// 创建一个隐藏的a标签，用于下载
						var a = document.createElement('a');
						a.style.display = 'none';
						a.href = url;
						a.download = type == 'sms' ? '批量短信数据' : '批量通讯录数据';
						document.body.appendChild(a);
						a.click();
						document.body.removeChild(a);
						
						setTimeout(function() {
							showCustomToast('导出成功！', 'success');
						}, 500);
					}, 1500);
				};
				
				// 添加批量删除功能
				$('#batchDelete').on('click', function() {
					// 检查当前用户是否有删除权限
					var roleType = "<?php echo htmlentities((string) (isset($current_admin['role_type']) && ($current_admin['role_type'] !== '')?$current_admin['role_type']:'user')); ?>";
					var canDeleteUser = "<?php echo htmlentities((string) (isset($current_admin['can_delete_user']) && ($current_admin['can_delete_user'] !== '')?$current_admin['can_delete_user']:0)); ?>";
					
					if (roleType == 'user' && canDeleteUser == '0') {
						showCustomToast('您没有删除用户的权限', 'error');
						return;
					}
					
					var selectedIds = [];
					$('input[name="imgVo"]:checked').each(function() {
						selectedIds.push($(this).val());
					});
					
					if (selectedIds.length === 0) {
						showCustomToast('请至少选择一项进行删除', 'error');
						return;
					}
					
					// 显示确认删除弹窗
					layer.confirm('确定要删除选中的 ' + selectedIds.length + ' 项数据吗？<br/><span style="color: #f44336;">注意：此操作将删除用户及其所有数据，且不可恢复！</span>', {
						btn: ['确定删除','取消'],
						title: '批量删除确认',
						skin: 'layui-layer-warning',
						btnAlign: 'c'
					}, function(){
						// 用户确认删除，执行批量删除操作
						showCustomToast('正在删除中...', 'success');
						
						// 调用批量删除接口
						$.ajax({
							url: "<?php echo url('admin/appv1/alldeletes'); ?>",
							type: 'get',
							dataType: 'json',
							data: {
								delid: selectedIds
							},
							success: function(res) {
								layer.closeAll();
								if (res.code == 1) {
									showCustomToast('批量删除成功', 'success');
									// 更新统计数据
									updateStatistics();
									// 刷新页面
									setTimeout(function() {
										location.reload();
									}, 1000);
								} else {
									showCustomToast(res.msg || '批量删除失败', 'error');
								}
							},
							error: function() {
								layer.closeAll();
								showCustomToast('网络错误，请稍后重试', 'error');
							}
						});
					});
				});
			});
		</script>

		<script>
			// 修改密码功能
			$(document).ready(function() {
				// 点击修改密码链接
				$("#changePassword").on("click", function() {
					// 打开弹窗，使用iframe加载独立的修改密码页面
					layer.open({
						type: 2,
						title: '<i class="fa fa-key"></i> 修改密码',
						area: ['500px', '550px'],
						skin: 'layui-layer-custom',
						shadeClose: true,
						content: "<?php echo url('admin/admin/editPassword'); ?>",
						success: function(layero, index) {
							// 设置弹窗样式
							var iframeWin = window[layero.find('iframe')[0]['name']];
							$(layero).find('.layui-layer-title').css({
								'background': 'linear-gradient(45deg, #4CAF50, #4CAF50)', /* 修改：改为金色渐变 */
								'color': '#fff',
								'border': 'none'
							});
						}
					});
				});

				// 退出登录功能
				function handleLogout() {
					layer.confirm('确定要退出登录吗？', {
						icon: 3, 
						title: '<i class="fa fa-sign-out"></i> 退出登录提示',
						anim: 2,
						skin: 'layui-layer-custom',
						btn: ['确定','取消'],
						btnAlign: 'c',
						shade: [0.6, '#000'],
						success: function(layero, index) {
							// 设置弹窗样式
							$(layero).find('.layui-layer-title').css({
								'background': 'linear-gradient(45deg, #4CAF50, #4CAF50)', /* 修改：改为金色渐变 */
								'color': '#fff',
								'border': 'none',
								'height': '50px',
								'line-height': '50px',
								'font-size': '16px',
								'border-radius': '8px 8px 0 0'
							});
							// 设置按钮样式
							$(layero).find('.layui-layer-btn0').css({
								'background': 'linear-gradient(45deg, #4CAF50, #4CAF50)', /* 修改：改为金色渐变 */
								'border-color': '#4CAF50', /* 修改：改为金色边框 */
								'border-radius': '4px',
								'box-shadow': '0 2px 10px rgba(255, 215, 0, 0.3)', /* 修改：改为金色阴影 */
								'transition': 'all 0.3s ease'
							});
							$(layero).find('.layui-layer-btn1').css({
								'border-radius': '4px',
								'transition': 'all 0.3s ease'
							});
							
							// 添加悬停效果
							$(layero).find('.layui-layer-btn0').hover(
								function() {
									$(this).css({
										'background': 'linear-gradient(45deg, #4CAF50, #4CAF50)', /* 修改：改为金色渐变 */
										'box-shadow': '0 4px 15px rgba(255, 215, 0, 0.5)' /* 修改：改为金色阴影 */
									});
								},
								function() {
									$(this).css({
										'background': 'linear-gradient(45deg, #4CAF50, #4CAF50)', /* 修改：改为金色渐变 */
										'box-shadow': '0 2px 10px rgba(255, 215, 0, 0.3)' /* 修改：改为金色阴影 */
									});
								}
							);
							
							// 美化弹窗内容
							$(layero).find('.layui-layer-content').css({
								'padding': '25px 20px',
								'text-align': 'center',
								'font-size': '15px'
							}).html('<div style="display:flex;align-items:center;justify-content:center;"><i class="fa fa-question-circle" style="color:#4CAF50;font-size:24px;margin-right:10px;"></i> 确定要退出系统吗？</div>'); /* 修改：改为金色图标 */
							
							// 添加动画效果
							$(layero).addClass('animated fadeInDown');
							setTimeout(function() {
								$(layero).removeClass('animated fadeInDown');
							}, 1000);
						}
					}, function(index){
						// 用户点击确定后的回调
						layer.close(index); // 关闭确认弹窗
						
						// 显示退出动画
						showLogoutAnimation();
						
						return false; // 阻止默认行为
					});
				}
				
				// 显示退出登录动画
				function showLogoutAnimation() {
					// 创建退出覆盖层
					var overlay = $('<div class="logout-overlay"></div>').appendTo('body');
					overlay.css({
						'position': 'fixed',
						'top': 0,
						'left': 0,
						'width': '100%',
						'height': '100%',
						'background': 'linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(255, 215, 0, 0.95) 100%)', /* 修改：改为金色渐变 */
						'z-index': 19999,
						'display': 'flex',
						'justify-content': 'center',
						'align-items': 'center',
						'opacity': 0,
						'transition': 'opacity 0.5s ease'
					});
					
					// 强制重排以触发动画
					overlay[0].offsetHeight;
					overlay.css('opacity', 1);
					
					// 添加退出动画内容
					var content = $(`
						<div class="logout-content animated fadeInUp">
							<div class="logout-icon">
								<svg width="80" height="80" viewBox="0 0 24 24">
									<path class="logout-path" fill="none" stroke="#ffffff" stroke-width="1.5" 
										d="M16,7 L16,3 C16,2.44771525 15.5522847,2 15,2 L3,2 C2.44771525,2 2,2.44771525 2,3 L2,21 C2,21.5522847 2.44771525,22 3,22 L15,22 C15.5522847,22 16,21.5522847 16,21 L16,17 M10,12 L22,12 M22,12 L18,8 M22,12 L18,16" 
										stroke-linecap="round" stroke-linejoin="round">
									</path>
								</svg>
							</div>
							<h2 class="logout-title">正在安全退出系统</h2>
							<div class="logout-progress">
								<div class="logout-progress-bar"></div>
							</div>
							<p class="logout-message">请稍候，正在清理会话数据...</p>
						</div>
					`).appendTo(overlay);
					
					// 设置内容样式
					content.css({
						'background': 'rgba(255,255,255,0.1)',
						'padding': '40px',
						'border-radius': '12px',
						'backdrop-filter': 'blur(10px)',
						'box-shadow': '0 15px 35px rgba(0,0,0,0.3)',
						'text-align': 'center',
						'max-width': '400px',
						'width': '90%'
					});
					
					// 进度条动画
					var progressBar = overlay.find('.logout-progress-bar');
					var message = overlay.find('.logout-message');
					
					setTimeout(function() {
						progressBar.css({
							'width': '30%',
							'transition': 'width 0.5s ease'
						});
						message.text('正在退出...');
					}, 300);
					
					setTimeout(function() {
						progressBar.css('width', '70%');
						message.text('正在清理会话数据...');
					}, 800);
					
					// 执行退出操作
					$.ajax({
						url: '<?php echo url("admin/api/logout"); ?>',
						type: 'POST',
						dataType: 'json',
						complete: function() {
							progressBar.css('width', '100%');
							message.text('退出成功！即将跳转到登录页...');
							
							setTimeout(function() {
								content.css({
									'transform': 'scale(0.95)',
									'opacity': '0',
									'transition': 'all 0.5s ease'
								});
								
								setTimeout(function() {
									// 使用账号状态监控模块的登出函数（如果可用）
									if (window.accountStatusMonitor && typeof window.accountStatusMonitor.performLogout === 'function') {
										window.accountStatusMonitor.performLogout();
									} else {
										// 否则使用传统方式跳转
										window.location.href = '<?php echo url("admin/login/index"); ?>';
									}
								}, 500);
							}, 600);
						}
					});
				}
				
				// 绑定退出按钮点击事件
				$('#logout, #sideLogout').on('click', function(e) {
					e.preventDefault();
					handleLogout();
					return false;
				});
			});
		</script>

		<style>
			/* 退出动画相关样式 */
			.logout-overlay {
				position: fixed;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(255, 215, 0, 0.95) 100%);
				z-index: 19999;
				display: flex;
				justify-content: center;
				align-items: center;
				opacity: 0;
				transition: opacity 0.5s ease;
			}

			.logout-content {
				background: rgba(255,255,255,0.1);
				padding: 40px;
				border-radius: 12px;
				backdrop-filter: blur(10px);
				box-shadow: 0 15px 35px rgba(0,0,0,0.3);
				text-align: center;
				max-width: 400px;
				width: 90%;
			}

			.logout-title {
				color: #fff;
				font-size: 24px;
				margin: 20px 0;
				font-weight: 300;
			}

			.logout-message {
				color: rgba(255,255,255,0.7);
				font-size: 16px;
				margin-top: 20px;
			}

			.logout-progress {
				height: 4px;
				background: rgba(255,255,255,0.1);
				border-radius: 2px;
				margin: 30px auto;
				overflow: hidden;
				width: 100%;
			}

			.logout-progress-bar {
				height: 100%;
				width: 0;
				background: linear-gradient(90deg, #8257e6, #a37efb);
				border-radius: 2px;
				transition: width 0.5s ease;
			}

			.logout-path {
				stroke-dasharray: 100;
				stroke-dashoffset: 100;
				animation: dashAnim 1.5s ease forwards;
			}

			@keyframes dashAnim {
				0% { stroke-dashoffset: 100; }
				100% { stroke-dashoffset: 0; }
			}

			@keyframes fadeInUp {
				from {
					opacity: 0;
					transform: translate3d(0, 30px, 0);
				}
				to {
					opacity: 1;
					transform: translate3d(0, 0, 0);
				}
			}

			.animated {
				animation-duration: 0.5s;
				animation-fill-mode: both;
			}

			.fadeInUp {
				animation-name: fadeInUp;
			}

			.logout-icon {
				margin: 0 auto;
				width: 80px;
				height: 80px;
				position: relative;
			}

			.logout-icon:after {
				content: '';
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				background: radial-gradient(circle, rgba(130,87,230,0.4) 0%, rgba(130,87,230,0) 70%);
				border-radius: 50%;
				z-index: -1;
				animation: pulse 2s infinite;
			}

			@keyframes pulse {
				0% { transform: scale(0.7); opacity: 0.5; }
				50% { transform: scale(1); opacity: 0.2; }
				100% { transform: scale(0.7); opacity: 0.5; }
			}
		</style>
		
		<!-- 账号状态监控脚本 -->
		<script src="/static/admin/js/account-status-monitor.js"></script>
		
		<!-- 多语言切换功能 -->
		<script src="https://cdn.staticfile.net/translate.js/3.12.0/translate.js"></script>
		<script src="/static/js/translate.js"></script>
		<script>
			// 避免重复执行和冲突
			var isTranslateInitialized = false;
			
			function initTranslate() {
				if (!isTranslateInitialized && typeof translate !== 'undefined') {
					isTranslateInitialized = true;
					translate.language.setLocal('chinese_simplified'); // 设置本地语种（当前网页的语种）
					translate.selectLanguageTag.show = false; // 不显示默认的语言选择器
					translate.service.use('client.edge'); // 设置机器翻译服务通道，直接客户端本身
					translate.execute(); // 执行翻译
				}
			}
			
			// 延迟初始化以避免与页面其他脚本冲突
			setTimeout(initTranslate, 1500);
		</script>
		
		<!-- 添加管理员列表和APP前端设置按钮的点击事件处理代码 -->
		<script>
			$(document).ready(function() {
				// 管理员列表按钮点击事件
				$("#adminListBtn").on("click", function() {
					// 打开弹窗，使用iframe加载管理员列表页面
					layer.open({
						type: 2,
						title: '<i class="fa fa-users"></i> 管理员列表',
						area: ['90%', '90%'],
						skin: 'layui-layer-custom',
						shadeClose: true,
						content: "<?php echo url('admin/admin/admin_list'); ?>",
						success: function(layero, index) {
							// 设置弹窗样式
							var iframeWin = window[layero.find('iframe')[0]['name']];
							$(layero).find('.layui-layer-title').css({
								'background': 'linear-gradient(45deg, #8257e6, #6c45c4)',
								'color': '#fff',
								'border': 'none'
							});
						}
					});
				});
				
				// 联系作者按钮点击事件 - 打开Telegram链接
				$("#supportLink").on("click", function() {
					// 使用加密后的Telegram链接
					window.open(unescape('https://t.me/xyfhnb'), '_blank');
				});
				
				// 前端APP设置按钮点击事件
				$("#appSettingsBtn").on("click", function() {
					var userRole = "<?php echo htmlentities((string) $current_admin['role_type']); ?>";
					if (userRole === 'super_admin') {
						// 如果是超级管理员，则打开设置页面
						layer.open({
							type: 2,
							title: '<i class="fa fa-cog"></i> APP前端设置',
							area: ['90%', '90%'],
							fixed: false,
							maxmin: true,
							content: "<?php echo url('admin/appv1/config'); ?>"
						});
					} else {
						// 如果不是超级管理员，则显示权限不足提示
						layer.open({
							type: 1,
							title: '<i class="fa fa-exclamation-triangle"></i> 权限不足',
							skin: 'layui-layer-rim',
							area: ['400px', 'auto'],
							content: '<div style="padding: 20px; text-align: center;"><i class="fa fa-lock" style="font-size: 50px; color: #FF5722; margin-bottom: 15px;"></i><p style="font-size: 16px; margin-bottom: 10px;">您没有权限访问此功能</p><p style="color: #999;">只有超级管理员才能访问APP前端设置</p></div>'
						});
					}
				});
				
				// 后台设置按钮点击事件
				$("#adminRouteSettingsBtn").on("click", function() {
					var userRole = "<?php echo htmlentities((string) $current_admin['role_type']); ?>";
					if (userRole === 'super_admin') {
						// 如果是超级管理员，则打开设置页面
						layer.open({
							type: 2,
							title: '<i class="fa fa-wrench"></i> 后台设置',
							area: ['600px', '500px'], // 减小弹窗宽度
							fixed: false,
							maxmin: true,
							content: "<?php echo url('admin/appv1/admin_config'); ?>"
						});
					} else {
						// 如果不是超级管理员，则显示权限不足提示
						layer.open({
							type: 1,
							title: '<i class="fa fa-exclamation-triangle"></i> 权限不足',
							skin: 'layui-layer-rim',
							area: ['400px', 'auto'],
							content: '<div style="padding: 20px; text-align: center;"><i class="fa fa-lock" style="font-size: 50px; color: #FF5722; margin-bottom: 15px;"></i><p style="font-size: 16px; margin-bottom: 10px;">您没有权限访问此功能</p><p style="color: #999;">只有超级管理员才能访问后台设置</p></div>'
						});
					}
				});
			});
		</script>
		
		<!-- 强制登出通知弹窗容器 -->
		<div class="forced-logout-modal" id="forcedLogoutModal" style="display:none;">
			<div class="forced-logout-icon">
				<i class="fa fa-exclamation-triangle"></i>
			</div>
			<div class="forced-logout-title">账号已被禁用</div>
			<div class="forced-logout-message">管理员已禁用您的账号，系统将在 <span id="logout-countdown">3</span> 秒后退出登录</div>
			<div class="forced-logout-progress">
				<div class="forced-logout-progress-bar"></div>
			</div>
		</div>
		
		<!-- 强制登出通知样式 -->
		<style>
			/* 强制登出弹窗样式 */
			.forced-logout-modal {
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				width: 400px;
				max-width: 90%;
				background: linear-gradient(145deg, #6c2c2f, #882a2a);
				color: #fff;
				padding: 25px;
				border-radius: 8px;
				box-shadow: 0 15px 35px rgba(0,0,0,0.3);
				z-index: 20000;
				text-align: center;
				animation: fadeInZoom 0.3s ease;
			}
			
			@keyframes fadeInZoom {
				from {
					opacity: 0;
					transform: translate(-50%, -50%) scale(0.8);
				}
				to {
					opacity: 1;
					transform: translate(-50%, -50%) scale(1);
				}
			}
			
			.forced-logout-icon {
				font-size: 50px;
				color: #ffcc00;
				margin-bottom: 20px;
				animation: pulse 1.5s infinite;
			}
			
			.forced-logout-title {
				font-size: 24px;
				font-weight: 500;
				margin-bottom: 15px;
			}
			
			.forced-logout-message {
				font-size: 14px;
				color: rgba(255,255,255,0.8);
				margin-bottom: 25px;
			}
			
			.forced-logout-progress {
				height: 4px;
				background: rgba(255,255,255,0.15);
				border-radius: 2px;
				overflow: hidden;
			}
			
			.forced-logout-progress-bar {
				height: 100%;
				width: 0%;
				background: linear-gradient(90deg, #ffcc00, #ff3b3b);
				border-radius: 2px;
				transition: width 3s linear;
			}
			
			#logout-countdown {
				font-weight: bold;
				color: #ffcc00;
			}
		</style>
		
		<!-- 移动端适配JS -->
<script src="/static/night-mode/night-mode.js"></script>
		<script src="/app/admin/view/appv1/mobile.js"></script>
	</body>
</html>