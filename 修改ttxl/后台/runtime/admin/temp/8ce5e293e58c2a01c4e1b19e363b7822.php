<?php /*a:1:{s:59:"/www/wwwroot/lsht999.top/app/admin/view/appv1/allalbum.html";i:1749557162;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>全部相册</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css" media="all" />
  <link rel="stylesheet" href="/static/css/admin.css" media="all">
  <!-- 引入移动端适配CSS -->
  <link rel="stylesheet" href="/app/admin/view/appv1/mobile.css" media="all">
  <style type="text/css">
    body {
      background-color: #f4f6f9;
      padding: 0;
      margin: 0;
      font-family: "Microsoft YaHei", sans-serif;
    }
    
    .tplay-body-div {
      padding: 0;
      background: #fff;
      border-radius: 0;
      overflow: hidden;
      box-shadow: none;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    
    /* 页面标题和切换按钮 */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px;
      border-bottom: 1px solid #eee;
    }
    
    .page-title {
      font-size: 18px;
      color: #333;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .toggle-view {
      background: #fff;
      border-radius: 50px;
      padding: 5px;
      display: inline-flex;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .view-btn {
      padding: 5px 12px;
      border-radius: 50px;
      cursor: pointer;
      font-size: 13px;
      color: #666;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .view-btn.active {
      background: linear-gradient(135deg, #6f42c1, #8c68c9);
      color: white;
      box-shadow: 0 2px 5px rgba(111, 66, 193, 0.2);
    }
    
    /* 图片网格布局 */
    .image-grid-container {
      flex: 1;
      overflow-y: auto;
      padding: 15px;
    }
    
    .image-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 15px;
    }
    
    .image-item {
      border-radius: 4px;
      overflow: hidden;
      box-shadow: 0 1px 4px rgba(0,0,0,0.1);
      background: #fff;
      transition: all 0.3s;
      position: relative;
      aspect-ratio: 1/1;
    }
    
    .image-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    
    .image-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    
    .image-item video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    
    .video-indicator {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(0,0,0,0.5);
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    
    .image-type {
      position: absolute;
      top: 10px;
      right: 10px;
      padding: 2px 8px;
      font-size: 12px;
      border-radius: 4px;
      color: white;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    
    .image-type.ios {
      background-color: rgba(0, 122, 255, 0.7);
    }
    
    .image-type.normal {
      background-color: rgba(50, 205, 50, 0.7);
    }
    
    .empty-album {
      text-align: center;
      padding: 50px 0;
      color: #888;
      grid-column: 1 / -1;
    }
    
    .empty-album i {
      font-size: 50px;
      color: #ddd;
      margin-bottom: 15px;
      display: block;
    }
    
    /* 分页区域 */
    .pagination-area {
      padding: 10px 15px;
      background: #f9f9f9;
      border-top: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .total-count {
      background: rgba(130, 87, 230, 0.1);
      color: #8257e6;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 13px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .actions {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .clear-btn {
      background: #ff5252;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 13px;
      transition: all 0.3s;
      box-shadow: 0 2px 5px rgba(255, 82, 82, 0.2);
    }
    
    .clear-btn:hover {
      background: #ff3636;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(255, 82, 82, 0.3);
    }
    
    /* 添加禁用按钮样式 */
    .clear-btn[disabled] {
      background: #ccc;
      color: #888;
      cursor: not-allowed;
      box-shadow: none;
      pointer-events: none;
    }
    
    .clear-btn[disabled]:hover {
      background: #ccc;
      transform: none;
      box-shadow: none;
    }

    /* 确认弹窗样式 */
    .confirm-modal .layui-layer-content {
      padding: 30px 25px;
      text-align: center;
    }
    
    .confirm-icon {
      font-size: 48px;
      color: #ff5252;
      margin-bottom: 20px;
    }
    
    .confirm-title {
      font-size: 18px;
      color: #333;
      margin-bottom: 10px;
      font-weight: 500;
    }
    
    .confirm-desc {
      font-size: 14px;
      color: #888;
      margin-bottom: 20px;
    }
    
    /* 图片查看器 - 改进的灯箱效果 */
    .image-viewer {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.9);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    
    .image-viewer img {
      max-width: 90%;
      max-height: 90%;
      object-fit: contain;
    }
    
    .image-viewer-close {
      position: absolute;
      top: 20px;
      right: 20px;
      color: #fff;
      font-size: 30px;
      cursor: pointer;
    }
    
    .image-viewer-prev {
      position: absolute;
      top: 50%;
      left: 20px;
      transform: translateY(-50%);
      color: #fff;
      font-size: 30px;
      cursor: pointer;
    }
    
    .image-viewer-next {
      position: absolute;
      top: 50%;
      right: 20px;
      transform: translateY(-50%);
      color: #fff;
      font-size: 30px;
      cursor: pointer;
    }
    
    .image-viewer-content {
      position: relative;
      width: 80%;
      height: 80%;
    }
    
    .image-viewer-img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    
    .image-viewer-video {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }
    
    .image-viewer-caption {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background-color: rgba(0, 0, 0, 0.5);
      color: #fff;
      padding: 5px 10px;
      border-radius: 0 0 4px 4px;
    }
    
    .image-viewer-count {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: rgba(0, 0, 0, 0.5);
      color: #fff;
      padding: 2px 5px;
      border-radius: 4px;
    }
    
    /* Toast 容器样式 */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      display: flex;
      flex-direction: column;
      gap: 10px;
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
    
    .toast {
      min-width: 250px;
      transform: translateX(120%);
      opacity: 0;
      transition: all 0.35s cubic-bezier(0.21, 1.02, 0.73, 1);
      padding: 12px 16px;
      border-radius: 4px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      display: flex;
      align-items: center;
      cursor: pointer;
      overflow: hidden;
      position: relative;
    }
    
    .toast.show {
      transform: translateX(0);
      opacity: 1;
    }
    
    .toast-icon {
      width: 20px;
      height: 20px;
      margin-right: 8px;
      flex-shrink: 0;
    }
    
    .toast-message {
      color: white;
      font-size: 14px;
      flex-grow: 1;
    }
    
    .toast.success {
      background: linear-gradient(45deg, #52c41a, #73d13d);
    }
    
    .toast.error {
      background: linear-gradient(45deg, #dc3545, #ef5462);
    }
    
    .toast.info {
      background: linear-gradient(45deg, #17a2b8, #1fc8e3);
    }
    
    /* 确认弹窗样式优化 */
    .confirm-modal {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      overflow: hidden !important;
    }
    
    .confirm-modal .layui-layer-content {
      padding: 16px 16px 8px !important;
      text-align: center;
      height: auto !important;
      min-height: auto !important;
      max-height: none !important;
      overflow: visible !important;
    }
    
    .confirm-icon {
      width: 36px;
      height: 36px;
      margin: 0 auto 10px;
      background: rgba(255, 77, 79, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .confirm-icon i {
      font-size: 18px;
      color: #ff4d4f;
    }
    
    .confirm-title {
      font-size: 15px;
      color: #333;
      margin-bottom: 4px;
      font-weight: 500;
      line-height: 1.3;
    }
    
    .confirm-desc {
      color: #666;
      font-size: 12px;
      line-height: 1.3;
      margin: 0 auto;
      max-width: 220px;
    }
    
    .confirm-modal .layui-layer-btn {
      padding: 8px 16px 12px !important;
      text-align: center;
      border-top: none;
    }
    
    .confirm-modal .layui-layer-btn a {
      border-radius: 4px;
      padding: 0 16px !important;
      font-size: 13px;
      font-weight: normal;
      transition: all 0.2s;
      min-width: 56px;
      margin: 0 4px;
      height: 30px !important;
      line-height: 30px !important;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn0 {
      background: #ff4d4f !important;
      border: none !important;
      color: #fff !important;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn0:hover {
      background: #ff7875 !important;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn1 {
      background: #f5f5f5 !important;
      border: none !important;
      color: #666 !important;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn1:hover {
      background: #e8e8e8 !important;
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

    /* 操作按钮样式 */
    .action-buttons {
      display: flex;
      gap: 10px;
      margin-left: 15px;
    }
    
    .select-btn, .unselect-btn {
      background: #fff;
      border: 1px solid #8257e6;
      color: #8257e6;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
      transition: all 0.3s;
    }
    
    .select-btn:hover, .unselect-btn:hover {
      background: rgba(130, 87, 230, 0.1);
      transform: translateY(-2px);
    }
    
    .clear-btn.disabled {
      background: #ccc !important;
      color: #888 !important;
      cursor: not-allowed !important;
      box-shadow: none !important;
      transform: none !important;
      pointer-events: none;
    }

    /* 移动端适配样式 */
    @media screen and (max-width: 768px) {
      /* 页面标题栏响应式布局 */
      .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 10px;
        background: linear-gradient(135deg, #6f42c1, #8c68c9);
        border-radius: 0;
        margin: 0;
        color: white;
      }
      
      .page-title {
        width: 100%;
        justify-content: space-between;
        color: white;
      }
      
      .page-title i {
        color: white !important;
      }
      
      .action-buttons {
        margin-left: 0;
        width: 100%;
        flex-wrap: wrap;
        gap: 10px;
      }
      
      .select-btn, .unselect-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 8px 12px;
        border-radius: 20px;
        transition: all 0.3s;
      }
      
      .select-btn:hover, .unselect-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-2px);
      }
      
      .clear-btn {
        background: rgba(255, 82, 82, 0.9);
        border: none;
        box-shadow: 0 3px 6px rgba(255, 82, 82, 0.2);
        border-radius: 20px;
      }
      
      /* 移动端图片网格布局 */
      .image-grid-container {
        padding: 10px;
      }
      
      .image-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
      }
      
      .image-item {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }
      
      .image-item:active {
        transform: scale(0.95);
      }
      
      .image-item img, .image-item video {
        border-radius: 12px;
      }
      
      .video-indicator {
        top: auto;
        bottom: 10px;
        left: 10px;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(4px);
        border-radius: 20px;
        padding: 5px 10px;
      }
      
      .image-type {
        top: auto;
        bottom: 10px;
        right: 10px;
        border-radius: 20px;
        padding: 5px 10px;
        backdrop-filter: blur(4px);
      }
      
      /* 切换视图按钮优化 */
      .toggle-view {
        width: 100%;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 25px;
        padding: 3px;
      }
      
      .view-btn {
        border-radius: 25px;
        padding: 8px 12px;
        font-size: 13px;
      }
      
      .view-btn.active {
        background: white;
        color: #6f42c1;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
      }
      
      /* 分页区域调整 */
      .pagination-area {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 15px 10px;
        background: #f9f9fc;
      }
      
      .total-count {
        width: 100%;
        background: white;
        border-radius: 20px;
        padding: 10px 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      }
      
      .actions {
        width: 100%;
      }
      
      .pagination-container {
        width: 100%;
      }
      
      .pagination {
        display: flex;
        overflow-x: auto;
        padding-bottom: 5px;
        justify-content: center;
      }
      
      .pagination > li > a, 
      .pagination > li > span {
        border-radius: 20px;
        margin: 0 2px;
        min-width: 36px;
        height: 36px;
      }
      
      .pagination-info {
        text-align: center;
        font-size: 13px;
        background: white;
        border-radius: 20px;
        padding: 8px 15px;
      }
      
      /* 图片查看器优化 */
      .image-viewer {
        background-color: rgba(0, 0, 0, 0.95);
      }
      
      .image-viewer-content {
        width: 95%;
        height: 70%;
      }
      
      .image-viewer-close {
        top: 15px;
        right: 15px;
        background: rgba(0, 0, 0, 0.5);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .image-viewer-prev,
      .image-viewer-next {
        padding: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .image-viewer-caption {
        background-color: rgba(0, 0, 0, 0.7);
        padding: 10px;
        border-radius: 20px;
        width: auto;
        max-width: 150px;
        text-align: center;
        left: 50%;
        transform: translateX(-50%);
        backdrop-filter: blur(4px);
      }
      
      .image-viewer-count {
        top: 15px;
        left: 15px;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 5px 10px;
        border-radius: 20px;
        backdrop-filter: blur(4px);
      }
      
      /* 状态提示优化 */
      .status-toast {
        border-radius: 15px;
      }
      
      .toast {
        border-radius: 15px;
      }
    }
    
    /* 小型手机屏幕适配 */
    @media screen and (max-width: 480px) {
      .image-grid {
        grid-template-columns: repeat(1, 1fr);
        gap: 15px;
      }
      
      .image-item {
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      }
      
      .image-item img, .image-item video {
        border-radius: 16px;
      }
      
      .select-btn, .unselect-btn, .clear-btn {
        padding: 8px 12px;
        font-size: 12px;
        flex: 1;
        text-align: center;
        border-radius: 25px;
      }
      
      .action-buttons {
        flex-direction: row;
        gap: 8px;
      }
      
      .toggle-view {
        width: 100%;
        justify-content: space-between;
      }
      
      .view-btn {
        flex: 1;
        justify-content: center;
        font-size: 12px;
      }
      
      .pagination > li > a, 
      .pagination > li > span {
        min-width: 32px;
        height: 32px;
        line-height: 32px;
        font-size: 12px;
      }
    }
  </style>
</head>
<body>
  <!-- 添加Toast容器 -->
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
  
  <div class="tplay-body-div">
    <!-- 顶部标题栏和切换按钮 -->
    <div class="page-header">
      <div class="page-title">
        <i class="fa fa-image" style="color: #8257e6;"></i> 全部相册
        <div class="action-buttons">
          <button id="selectAllBtn" class="select-btn" type="button">全选</button>
          <button id="unselectAllBtn" class="unselect-btn" type="button">取消全选</button>
          <?php if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_delete_user'] == 1)): ?>
          <button id="clearAlbum" class="clear-btn" type="button" style="margin-left: 10px;">
            <i class="fa fa-trash"></i> 清空所有图片
          </button>
          <?php else: ?>
          <button class="clear-btn disabled" type="button" style="margin-left: 10px; background: #ccc; color: #888; cursor: not-allowed;" title="没有删除权限">
            <i class="fa fa-trash"></i> 清空所有图片
          </button>
          <?php endif; ?>
        </div>
      </div>
      <div class="toggle-view">
        <div class="view-btn active" data-type="all"><i class="fa fa-th"></i> 全部</div>
        <div class="view-btn" data-type="image"><i class="fa fa-image"></i> 图片</div>
        <div class="view-btn" data-type="video"><i class="fa fa-video-camera"></i> 视频</div>
      </div>
    </div>
    
    <!-- 图片网格 -->
    <div class="image-grid-container">
      <div class="image-grid">
        <?php if(empty($imgs) || (($imgs instanceof \think\Collection || $imgs instanceof \think\Paginator ) && $imgs->isEmpty())): ?>
          <div class="empty-album">
            <i class="fa fa-image"></i>
            <p>暂无相册内容</p>
          </div>
        <?php else: if(is_array($imgs) || $imgs instanceof \think\Collection || $imgs instanceof \think\Paginator): $i = 0; $__LIST__ = $imgs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;
              $url = strtolower($img['image_url']);
              $isVideo = stripos($url, '.mp4') !== false || 
                         stripos($url, '.mov') !== false || 
                         stripos($url, '.m4v') !== false || 
                         stripos($url, '.3gp') !== false || 
                         stripos($url, '.avi') !== false;
             if($isVideo): ?>
              <div class="image-item video-item">
                <video src="<?php echo htmlentities((string) $img['image_url']); ?>" controls>
                  您的浏览器不支持视频播放
                </video>
                <div class="video-indicator">
                  <i class="fa fa-play"></i> 视频
                </div>
                <div class="image-type <?php if($img['type'] == 'ios'): ?>ios<?php else: ?>normal<?php endif; ?>"><?php if($img['type'] == 'ios'): ?>iOS<?php else: ?>Android<?php endif; ?></div>
              </div>
            <?php else: ?>
              <div class="image-item image-item">
                <img src="<?php echo htmlentities((string) $img['image_url']); ?>" alt="相册图片">
                <div class="image-type <?php if($img['type'] == 'ios'): ?>ios<?php else: ?>normal<?php endif; ?>"><?php if($img['type'] == 'ios'): ?>iOS<?php else: ?>Android<?php endif; ?></div>
              </div>
            <?php endif; ?>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- 分页 -->
    <div class="pagination-area">
      <div class="total-count">
        <i class="fa fa-picture-o"></i> 总计: <b><?php echo htmlentities((string) $total_count); ?></b> 项 (图片: <?php echo htmlentities((string) $image_count); ?>, 视频: <?php echo htmlentities((string) $video_count); ?>)
      </div>
      <div class="actions">
        <div class="pagination-container">
          <ul class="pagination">
            <?php if($current_page > 1): ?>
              <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $current_page-1])); ?>" title="上一页"><i class="fa fa-angle-left"></i></a></li>
            <?php else: ?>
              <li class="disabled"><span><i class="fa fa-angle-left"></i></span></li>
            <?php endif; if($total_pages <= 7): $__FOR_START_688733095__=1;$__FOR_END_688733095__=$total_pages+1;for($i=$__FOR_START_688733095__;$i < $__FOR_END_688733095__;$i+=1){ if($current_page == $i): ?>
                  <li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
                <?php else: ?>
                  <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
                <?php endif; } else: if($current_page <= 3): $__FOR_START_1089833179__=1;$__FOR_END_1089833179__=6;for($i=$__FOR_START_1089833179__;$i < $__FOR_END_1089833179__;$i+=1){ if($current_page == $i): ?>
                    <li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
                  <?php else: ?>
                    <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
                  <?php endif; } ?>
                <li class="disabled"><span>...</span></li>
                <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $total_pages])); ?>"><?php echo htmlentities((string) $total_pages); ?></a></li>
              <?php elseif($current_page > $total_pages - 3): ?>
                <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => 1])); ?>">1</a></li>
                <li class="disabled"><span>...</span></li>
                <?php $__FOR_START_1586912520__=$total_pages-4;$__FOR_END_1586912520__=$total_pages+1;for($i=$__FOR_START_1586912520__;$i < $__FOR_END_1586912520__;$i+=1){ if($current_page == $i): ?>
                    <li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
                  <?php else: ?>
                    <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
                  <?php endif; } else: ?>
                <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => 1])); ?>">1</a></li>
                <li class="disabled"><span>...</span></li>
                <?php $__FOR_START_366813464__=$current_page-1;$__FOR_END_366813464__=$current_page+2;for($i=$__FOR_START_366813464__;$i < $__FOR_END_366813464__;$i+=1){ if($current_page == $i): ?>
                    <li class="active"><span><?php echo htmlentities((string) $i); ?></span></li>
                  <?php else: ?>
                    <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $i])); ?>"><?php echo htmlentities((string) $i); ?></a></li>
                  <?php endif; } ?>
                <li class="disabled"><span>...</span></li>
                <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $total_pages])); ?>"><?php echo htmlentities((string) $total_pages); ?></a></li>
              <?php endif; ?>
            <?php endif; if($current_page < $total_pages): ?>
              <li><a href="<?php echo url('admin/appv1/allalbum', array_merge(request()->param(), ['page' => $current_page+1])); ?>" title="下一页"><i class="fa fa-angle-right"></i></a></li>
            <?php else: ?>
              <li class="disabled"><span><i class="fa fa-angle-right"></i></span></li>
            <?php endif; ?>
          </ul>
          <div class="pagination-info">共 <?php echo htmlentities((string) $total_count); ?> 项媒体（图片: <?php echo htmlentities((string) $image_count); ?>, 视频: <?php echo htmlentities((string) $video_count); ?>），当前第 <?php echo htmlentities((string) $current_page); ?>/<?php echo htmlentities((string) $total_pages); ?> 页</div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- 图片查看器 - 改进的灯箱效果 -->
  <div class="image-viewer">
    <div class="image-viewer-close"><i class="fa fa-times"></i></div>
    <div class="image-viewer-prev"><i class="fa fa-chevron-left"></i></div>
    <div class="image-viewer-next"><i class="fa fa-chevron-right"></i></div>
    <div class="image-viewer-content">
      <img src="" alt="大图预览" class="image-viewer-img">
      <video src="" controls class="image-viewer-video" style="display:none;"></video>
    </div>
    <div class="image-viewer-caption"></div>
    <div class="image-viewer-count"></div>
  </div>

  <script src="/static/layui/layui.js"></script>
  <script src="/static/jquery/jquery.min.js"></script>
  <script>
    layui.use(['layer'], function(){
      var layer = layui.layer;
      
      // 确保DOM完全加载后执行按钮状态更新
      $(document).ready(function() {
        updateClearButtonState();
        setupImageViewer();
        setupTouchEvents(); // 添加触摸事件支持
        
        // 移除视频项默认的隐藏样式，确保所有媒体项都可见
        $('.video-item').removeAttr('style');
        
        // 检查URL参数是否指定显示类型
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('show') === 'video') {
          // 模拟点击视频按钮
          $('.view-btn[data-type="video"]').click();
        } else if (urlParams.get('show') === 'image') {
          // 模拟点击图片按钮
          $('.view-btn[data-type="image"]').click();
        } else {
          // 默认显示所有媒体项
          $('.view-btn[data-type="all"]').click();
        }
      });
      
      // 设置移动端触摸事件
      function setupTouchEvents() {
        var startX, startY;
        var threshold = 50; // 滑动阈值
        
        // 图片查看器触摸事件
        $('.image-viewer').on('touchstart', function(e) {
          startX = e.originalEvent.touches[0].clientX;
          startY = e.originalEvent.touches[0].clientY;
        });
        
        $('.image-viewer').on('touchmove', function(e) {
          if (!startX || !startY) return;
          
          var moveX = e.originalEvent.touches[0].clientX;
          var moveY = e.originalEvent.touches[0].clientY;
          
          // 计算水平滑动距离
          var diffX = startX - moveX;
          var diffY = startY - moveY;
          
          // 如果水平滑动距离大于垂直滑动距离且超过阈值
          if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > threshold) {
            e.preventDefault(); // 阻止默认行为
            
            // 重置起始位置，避免连续触发
            startX = null;
            startY = null;
            
            if (diffX > 0) {
              // 向左滑 - 下一个
              if (currentIndex < mediaItems.length - 1) {
                showMediaItem(currentIndex + 1);
              }
            } else {
              // 向右滑 - 上一个
              if (currentIndex > 0) {
                showMediaItem(currentIndex - 1);
              }
            }
          }
        });
        
        // 双击图片关闭查看器
        $('.image-viewer-content').on('dblclick', function() {
          $('.image-viewer').hide();
          document.querySelector('.image-viewer-video').pause();
        });
      }
      
      // 更新媒体计数
      function updateMediaCount() {
        var totalImages = $('.image-item:not(.video-item)').length;
        var totalVideos = $('.video-item').length;
        var totalCount = totalImages + totalVideos;
        
        // 更新总计显示
        $('.total-count').html('<i class="fa fa-picture-o"></i> 总计: <b>' + totalCount + '</b> 项 (图片: ' + totalImages + ', 视频: ' + totalVideos + ')');
        
        // 更新分页信息
        $('.pagination-info').text('共 ' + totalCount + ' 项媒体，当前第 <?php echo htmlentities((string) $current_page); ?>/<?php echo htmlentities((string) $total_pages); ?> 页');
      }
      
      // 设置图片查看器（灯箱效果）
      function setupImageViewer() {
        // 存储所有图片和视频
        var mediaItems = [];
        var currentIndex = 0;
        
        // 收集所有媒体项
        function collectMediaItems() {
          mediaItems = [];
          // 只收集当前可见的媒体项
          $('.image-item:visible').each(function(index) {
            var isVideo = $(this).hasClass('video-item');
            var src = isVideo ? 
              $(this).find('video').attr('src') : 
              $(this).find('img').attr('src');
            var type = $(this).find('.image-type').text();
            
            mediaItems.push({
              index: index,
              src: src,
              isVideo: isVideo,
              type: type
            });
          });
          
          return mediaItems;
        }
        
        // 图片点击处理
        $('.image-item img').on('click', function(){
          collectMediaItems();
          var $item = $(this).closest('.image-item');
          var index = $('.image-item:visible').index($item);
          showMediaItem(index);
        });
        
        // 视频点击处理
        $('.video-item').on('click', function(){
          collectMediaItems();
          var index = $('.image-item:visible').index(this);
          showMediaItem(index);
        });
        
        // 显示媒体项
        function showMediaItem(index) {
          if (index < 0 || index >= mediaItems.length) {
            return;
          }
          
          currentIndex = index;
          var item = mediaItems[index];
          
          // 隐藏所有媒体，然后显示当前项
          $('.image-viewer-img, .image-viewer-video').hide();
          
          if (item.isVideo) {
            $('.image-viewer-video').attr('src', item.src).show();
            // 自动播放视频
            document.querySelector('.image-viewer-video').play();
          } else {
            $('.image-viewer-img').attr('src', item.src).show();
          }
          
          // 更新计数器
          $('.image-viewer-count').text((index + 1) + ' / ' + mediaItems.length);
          // 更新标题
          $('.image-viewer-caption').text(item.type);
          
          // 显示查看器
          $('.image-viewer').css('display', 'flex');
        }
        
        // 上一个媒体
        $('.image-viewer-prev').on('click', function(e){
          e.stopPropagation();
          if (currentIndex > 0) {
            showMediaItem(currentIndex - 1);
          }
        });
        
        // 下一个媒体
        $('.image-viewer-next').on('click', function(e){
          e.stopPropagation();
          if (currentIndex < mediaItems.length - 1) {
            showMediaItem(currentIndex + 1);
          }
        });
        
        // 关闭查看器
        $('.image-viewer-close, .image-viewer').on('click', function(){
          $('.image-viewer').hide();
          // 停止视频播放
          document.querySelector('.image-viewer-video').pause();
        });
        
        // 阻止冒泡
        $('.image-viewer-content').on('click', function(e){
          e.stopPropagation();
        });
        
        // 键盘导航
        $(document).keydown(function(e){
          if (!$('.image-viewer').is(':visible')) {
            return;
          }
          
          switch(e.which) {
            case 37: // 左箭头
              if (currentIndex > 0) {
                showMediaItem(currentIndex - 1);
              }
              break;
              
            case 39: // 右箭头
              if (currentIndex < mediaItems.length - 1) {
                showMediaItem(currentIndex + 1);
              }
              break;
              
            case 27: // ESC
              $('.image-viewer').hide();
              document.querySelector('.image-viewer-video').pause();
              break;
              
            default: 
              return;
          }
          e.preventDefault();
        });
      }
      
      // 更新清空按钮状态
      function updateClearButtonState() {
        var hasImages = $('.image-grid .image-item').length > 0 && 
                       !$('.image-grid .empty-album').length;
        
        var $clearBtn = $('#clearAlbum');
        if(!hasImages) {
          $clearBtn.attr('disabled', 'disabled');
          $clearBtn.addClass('disabled');
        } else {
          $clearBtn.removeAttr('disabled');
          $clearBtn.removeClass('disabled');
        }
      }
      
      // 视图切换功能
      $('.view-btn').click(function() {
        var type = $(this).data('type');
        $('.view-btn').removeClass('active');
        $(this).addClass('active');
        
        if(type === 'video') {
          $('.image-item:not(.video-item)').hide();
          $('.video-item').show();
        } else if(type === 'image') {
          $('.image-item:not(.video-item)').show();
          $('.video-item').hide();
        } else if(type === 'all') {
          $('.image-item').show();
        }
        
        // 更新URL以反映当前视图，不刷新页面
        var currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('show', type);
        window.history.pushState({}, '', currentUrl);
      });
      
      // 清空相册功能
      $('#clearAlbum').on('click', function() {
        // 如果按钮被禁用，则不执行操作
        if($(this).is('[disabled]')) {
          showToast('相册已经是空的了', 'info');
          return;
        }

        var userId = getUrlParam('id');
        if (!userId) {
          showToast('无法确定要操作的用户ID', 'error');
          return;
        }
        
        var hasImages = $('.image-grid .image-item').length > 0 && 
                       !$('.image-grid .empty-album').length;
        
        if(!hasImages) {
          showToast('相册已经是空的了', 'info');
          return;
        }
        
        layer.open({
          type: 1,
          title: false,
          closeBtn: false,
          skin: 'confirm-modal',
          area: ['280px', 'auto'],
          maxHeight: 'none',
          scrollbar: false,
          resize: false,
          move: false,
          content: 
            '<div class="layui-layer-content">' +
              '<div class="confirm-icon">' +
                '<i class="fa fa-exclamation-circle"></i>' +
              '</div>' +
              '<div class="confirm-title">确定清空相册？</div>' +
              '<div class="confirm-desc">删除后数据将无法恢复</div>' +
            '</div>',
          btn: ['确定', '取消'],
          btnAlign: 'c',
          yes: function(index){
            $.ajax({
              url: '<?php echo url("admin/appv1/clearAlbum"); ?>',
              type: 'POST',
              data: {
                user_id: userId
              },
              success: function(res) {
                layer.close(index);
                if(res.code == 1) {
                  showToast('清空成功', 'success');
                  setTimeout(function() {
                    location.reload();
                  }, 1500);
                } else {
                  showToast(res.msg || '操作失败', 'error');
                }
              },
              error: function() {
                layer.close(index);
                showToast('网络错误，请重试', 'error');
              }
            });
          }
        });
      });
      
      // 获取URL参数
      function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]); return null;
      }
      
      // 添加显示Toast的函数
      function showToast(message, type = 'info', duration = 3000) {
        // 使用status-toast样式
        const $toast = $('#statusToast');
        let icon = 'fa-check-circle';
        let background = 'linear-gradient(145deg, #4CAF50, #2E7D32)';
        let title = '操作成功';
        
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
    });

    // 确保页面加载后执行
    document.addEventListener('DOMContentLoaded', function() {
      // 检查URL参数是否指定显示视频
      const urlParams = new URLSearchParams(window.location.search);
      if (urlParams.get('show') === 'video') {
        // 模拟点击视频按钮
        document.querySelector('.view-btn[data-type="video"]').click();
      } else if (urlParams.get('show') === 'all') {
        // 显示所有媒体
        document.querySelector('.view-btn[data-type="all"]').click();
      }
      
      // 手动添加日志以检查是否有视频元素
      console.log('视频元素数量:', document.querySelectorAll('.video-item').length);
    });
  </script>
</body>
</html> 