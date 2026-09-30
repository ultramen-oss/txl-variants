<?php /*a:1:{s:60:"/www/wwwroot/154.64.230.166/app/admin/view/appv1/mobile.html";i:1749557270;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>通讯录管理</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- 确保完全支持表情符号 -->
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="<?php echo request()->domain(); ?>/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="<?php echo request()->domain(); ?>/static/font-awesome/css/font-awesome.min.css" media="all">
  <style type="text/css">
    body { 
      background-color: #f4f6f9; 
      padding: 15px; 
      font-family: "Microsoft YaHei", sans-serif; 
    }
    
    /* 表情符号文本样式 */
    .emoji-text {
      font-family: "Segoe UI Emoji", "Apple Color Emoji", "Noto Color Emoji", sans-serif;
      word-break: break-word;
    }
    
    /* 页面标题 */
    .page-title {
      font-size: 18px;
      font-weight: 500;
      margin-bottom: 15px;
      color: #333;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    /* 搜索栏 */
    .search-bar {
      background: #fff;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 20px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }
    
    .search-bar .layui-input {
      border-radius: 4px;
      padding: 8px 12px;
      height: 36px;
    }
    
    .search-bar .layui-btn {
      background: linear-gradient(135deg, #6f42c1, #8c68c9);
      border-radius: 4px;
      height: 36px;
      line-height: 36px;
      box-shadow: 0 2px 5px rgba(111, 66, 193, 0.2);
      transition: all 0.3s;
    }
    
    .search-bar .layui-btn:hover {
      box-shadow: 0 4px 8px rgba(111, 66, 193, 0.3);
      transform: translateY(-2px);
    }
    
    /* 表格样式 */
    .layui-table {
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .layui-table thead tr th {
      background: linear-gradient(to right, #6f42c1, #8c68c9);
      color: white;
      font-weight: 500;
      text-align: center;
      padding: 15px 8px;
      font-size: 14px;
    }
    
    .layui-table tbody tr td {
      text-align: center;
      padding: 12px 8px;
      font-size: 13px;
      color: #333;
      vertical-align: middle;
    }
    
    .layui-table tbody tr:nth-child(odd) {
      background-color: rgba(0,0,0,0.02);
    }
    
    .layui-table tbody tr:hover {
      background-color: #f0f0ff;
    }
    
    /* 通讯录姓名和号码样式 */
    .contact-name {
      font-weight: 600;
      color: #333;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .contact-name i {
      color: #6f42c1;
      font-size: 16px;
    }
    
    .contact-number {
      color: #1e88e5;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .contact-row {
      display: flex;
      align-items: center;
      gap: 10px;
      justify-content: center;
    }
    
    /* 导出按钮 */
    .export-actions {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      align-items: center;
    }
    
    .export-btns {
      display: flex;
      gap: 10px;
    }
    
    .export-btn {
      background: linear-gradient(135deg, #6f42c1, #8c68c9);
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
      font-size: 13px;
      transition: all 0.3s;
      text-decoration: none;
    }
    
    .export-btn.excel {
      background: linear-gradient(135deg, #27ae60, #2ecc71);
    }
    
    .export-btn.txt {
      background: linear-gradient(135deg, #f39c12, #f1c40f);
    }
    
    .export-btn.clear {
      background: linear-gradient(135deg, #e74c3c, #c0392b);
    }
    
    .export-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
    
    .total-count {
      background: linear-gradient(135deg, #6f42c1, #8c68c9);
      color: white;
      padding: 8px 15px;
      border-radius: 50px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 2px 5px rgba(111, 66, 193, 0.3);
    }
    
    .total-count i {
      font-size: 18px;
    }
    
    .total-count span {
      font-weight: bold;
      background: rgba(255,255,255,0.25);
      padding: 3px 10px;
      border-radius: 50px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1) inset;
    }
    
    /* 空数据状态 */
    .empty-tip {
      text-align: center;
      padding: 50px 0;
      color: #999;
    }
    
    .empty-tip i {
      display: block;
      font-size: 40px;
      margin-bottom: 10px;
      color: #ddd;
    }
    
    /* 视图切换 */
    .toggle-view {
      background: #fff;
      border-radius: 50px;
      padding: 5px;
      display: inline-flex;
      margin-left: 15px;
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
    
    /* 卡片视图样式 */
    .grid-view {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 15px;
      margin-top: 20px;
    }
    
    .contact-card {
      background: #fff;
      border-radius: 8px;
      padding: 12px 15px;
      margin: 8px 0;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
      transition: all 0.3s;
      display: flex;
      flex-direction: column;
      gap: 6px;
      border-left: 3px solid #6f42c1;
      text-align: left;
    }
    
    .contact-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .contact-card .contact-name {
      font-weight: 600;
      color: #333;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .contact-card .contact-number {
      color: #1e88e5;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 6px;
    }
    
    .contact-card .time-info {
      color: #666;
      font-size: 12px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    /* 现代化弹窗样式 */
    .modern-layer {
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .modern-layer .layui-layer-title {
      background: #fff;
      border-bottom: 1px solid #f0f0f0;
      padding: 16px 24px;
      font-size: 16px;
      color: #333;
      font-weight: 500;
      height: auto;
      line-height: 1.5;
    }
    
    .modern-layer .layui-layer-content {
      padding: 24px;
      color: #666;
      font-size: 14px;
      line-height: 1.6;
    }
    
    .modern-layer .warning-icon {
      text-align: center;
      margin-bottom: 16px;
    }
    
    .modern-layer .warning-icon i {
      font-size: 48px;
      color: #ff4d4f;
      animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 1;
      }
      50% {
        transform: scale(1.1);
        opacity: 0.8;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }
    
    .modern-layer .warning-title {
      font-size: 18px;
      color: #333;
      text-align: center;
      margin-bottom: 12px;
      font-weight: 500;
    }
    
    .modern-layer .warning-desc {
      text-align: center;
      color: #666;
      margin-bottom: 24px;
    }
    
    .modern-layer .layui-layer-btn {
      padding: 16px 24px;
      text-align: right;
      background: #f9fafb;
      border-top: 1px solid #f0f0f0;
    }
    
    .modern-layer .layui-layer-btn a {
      border-radius: 6px;
      padding: 8px 16px;
      font-size: 14px;
      font-weight: 500;
      transition: all 0.3s;
      height: auto;
      line-height: 1.5;
      box-shadow: none;
    }
    
    .modern-layer .layui-layer-btn .layui-layer-btn0 {
      background: #ff4d4f;
      border-color: #ff4d4f;
      color: #fff;
    }
    
    .modern-layer .layui-layer-btn .layui-layer-btn0:hover {
      background: #ff7875;
      border-color: #ff7875;
      box-shadow: 0 4px 12px rgba(255, 77, 79, 0.2);
    }
    
    .modern-layer .layui-layer-btn .layui-layer-btn1 {
      background: #fff;
      border-color: #d9d9d9;
      color: #666;
    }
    
    .modern-layer .layui-layer-btn .layui-layer-btn1:hover {
      color: #333;
      border-color: #999;
      background: #f9f9f9;
    }
    
    /* 成功提示框样式 */
    .success-toast {
      background: rgba(255, 255, 255, 0.98) !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
      border-radius: 8px !important;
      color: #333 !important;
    }
    
    .success-toast .layui-layer-content {
      padding: 20px !important;
      text-align: center !important;
      font-size: 14px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      gap: 10px !important;
    }
    
    .success-toast i {
      font-size: 20px !important;
      color: #52c41a !important;
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
    
    /* 旧的Toast样式 - 保留一段时间用于兼容性 */
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
    }
    
    .toast.show {
      transform: translateX(0);
    }
    
    .toast-content {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      gap: 12px;
    }
    
    .toast-icon {
      width: 24px;
      height: 24px;
      flex-shrink: 0;
    }
    
    .toast-message {
      color: #fff;
      font-size: 14px;
      line-height: 1.5;
      flex-grow: 1;
    }
    
    .toast.success {
      background: linear-gradient(45deg, #28a745, #34ce57);
    }
    
    .toast.error {
      background: linear-gradient(45deg, #dc3545, #ef5462);
    }
    
    .toast.info {
      background: linear-gradient(45deg, #17a2b8, #1fc8e3);
    }
    
    /* 确认弹窗样式 */
    .confirm-modal {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .confirm-modal .layui-layer-content {
      padding: 30px 25px;
      text-align: center;
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
      padding: 8px 16px 12px;
      text-align: center;
      border-top: none;
    }
    
    .confirm-modal .layui-layer-btn a {
      border-radius: 4px;
      padding: 0 16px;
      font-size: 13px;
      font-weight: normal;
      transition: all 0.2s;
      min-width: 56px;
      margin: 0 4px;
      height: 30px;
      line-height: 30px;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn0 {
      background: #ff4d4f;
      border: none;
      color: #fff;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn0:hover {
      background: #ff7875;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn1 {
      background: #f5f5f5;
      border: none;
      color: #666;
    }
    
    .confirm-modal .layui-layer-btn .layui-layer-btn1:hover {
      background: #e8e8e8;
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
  </style>
</head>
<body>
  <div class="tplay-body-div">
    <div class="page-title">
      <i class="fa fa-address-book"></i> 通讯录管理
      <div class="toggle-view">
        <div class="view-btn active" data-view="table"><i class="fa fa-list"></i> 表格</div>
        <div class="view-btn" data-view="grid"><i class="fa fa-th"></i> 卡片</div>
      </div>
    </div>
    
    <div class="search-bar">
      <form class="layui-form" action="" method="post">
        <div style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
          <div class="layui-input-inline" style="width: 200px;">
            <input type="text" name="username" lay-verify="title" autocomplete="off" placeholder="搜索通讯录姓名" class="layui-input">
          </div>
          <div class="layui-input-inline" style="width: 200px;">
            <input type="text" name="phone" lay-verify="title" autocomplete="off" placeholder="搜索手机号码" class="layui-input">
          </div>
          <div class="layui-input-inline" style="width: 200px;">
            <input type="text" class="layui-input" id="timeRange" placeholder="上传时间范围" name="time">
          </div>
          <button class="layui-btn" lay-submit="" lay-filter="search">
            <i class="fa fa-search"></i> 搜索
          </button>
        </div>
      </form>
    </div>
    
    <div class="export-actions">
      <div class="total-count">
        <i class="fa fa-address-book-o"></i> 通讯录总数：<span><?php echo count($contacts); ?></span>
      </div>
      
      <div class="export-btns">
        <?php if(empty($list) || (($list instanceof \think\Collection || $list instanceof \think\Paginator ) && $list->isEmpty())): ?>
        <a href="javascript:;" id="clearContacts" class="export-btn clear" style="background: #cccccc !important; color: #888888; cursor: not-allowed; box-shadow: none; transform: none;">
          <i class="fa fa-trash"></i>清空记录
        </a>
        <?php else: if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_delete_user'] == 1)): ?>
        <a href="javascript:;" id="clearContacts" class="export-btn clear">
          <i class="fa fa-trash"></i>清空记录
        </a>
        <?php else: ?>
        <a href="javascript:;" class="export-btn clear" style="background: #cccccc !important; color: #888888; cursor: not-allowed; box-shadow: none; transform: none;" title="没有删除权限">
          <i class="fa fa-trash"></i>清空记录
        </a>
        <?php endif; ?>
        <?php endif; if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_export_data'] == 1)): ?>
        <a href="<?php echo url('admin/appv1/exportexcel', ['id' => $user['id']]); ?>" class="export-btn excel">
          <i class="fa fa-file-excel-o"></i> 导出Excel
        </a>
        <a href="javascript:;" id="exportTxt" class="export-btn txt">
          <i class="fa fa-file-text-o"></i> 导出TXT
        </a>
        <?php else: ?>
        <span class="export-btn excel" style="opacity: 0.5; cursor: not-allowed;" title="没有导出权限">
          <i class="fa fa-file-excel-o"></i> 导出Excel
        </span>
        <span class="export-btn txt" style="opacity: 0.5; cursor: not-allowed;" title="没有导出权限">
          <i class="fa fa-file-text-o"></i> 导出TXT
        </span>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="table-view">
      <table class="layui-table">
        <thead>
          <tr>
            <th style="width: 30%">通讯录姓名</th>
            <th style="width: 40%">通讯录号码</th>
            <th style="width: 30%">上传时间</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($contacts) || (($contacts instanceof \think\Collection || $contacts instanceof \think\Paginator ) && $contacts->isEmpty())): ?>
          <tr>
            <td colspan="3">
              <div class="empty-tip">
                <i class="fa fa-address-book"></i>
                <p>暂无通讯录数据</p>
              </div>
            </td>
          </tr>
          <?php else: if(is_array($contacts) || $contacts instanceof \think\Collection || $contacts instanceof \think\Paginator): $i = 0; $__LIST__ = $contacts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
          <tr data-id="<?php echo htmlentities((string) $vo['id']); ?>" data-name="<?php echo htmlentities((string) $vo['username']); ?>" data-phone="<?php echo htmlentities((string) $vo['umobile']); ?>" data-time="<?php echo htmlentities((string) date('Y-m-d H:i:s',!is_numeric($vo['addtime'])? strtotime($vo['addtime']) : $vo['addtime'])); ?>">
            <td>
              <span class="contact-name"><i class="fa fa-user-circle"></i> <span class="emoji-text"><?php echo htmlentities((string) $vo['username']); ?></span></span>
            </td>
            <td>
              <span class="contact-number"><i class="fa fa-phone"></i> <?php echo htmlentities((string) $vo['umobile']); ?></span>
            </td>
            <td>
              <div class="time-info">
                <i class="fa fa-clock-o"></i> <?php echo htmlentities((string) date("Y-m-d H:i:s",!is_numeric($vo['addtime'])? strtotime($vo['addtime']) : $vo['addtime'])); ?>
              </div>
            </td>
          </tr>
          <?php endforeach; endif; else: echo "" ;endif; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="grid-view" style="display:none;">
      <?php if(empty($contacts) || (($contacts instanceof \think\Collection || $contacts instanceof \think\Paginator ) && $contacts->isEmpty())): ?>
      <div class="empty-tip">
        <i class="fa fa-address-book"></i>
        <p>暂无通讯录数据</p>
      </div>
      <?php else: if(is_array($contacts) || $contacts instanceof \think\Collection || $contacts instanceof \think\Paginator): $i = 0; $__LIST__ = $contacts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
      <div class="contact-card" data-id="<?php echo htmlentities((string) $vo['id']); ?>" data-name="<?php echo htmlentities((string) $vo['username']); ?>" data-phone="<?php echo htmlentities((string) $vo['umobile']); ?>" data-time="<?php echo htmlentities((string) date('Y-m-d H:i:s',!is_numeric($vo['addtime'])? strtotime($vo['addtime']) : $vo['addtime'])); ?>">
        <div class="contact-name">
          <i class="fa fa-user-circle"></i> <span class="emoji-text"><?php echo htmlentities((string) $vo['username']); ?></span>
        </div>
        <div class="contact-number">
          <i class="fa fa-phone"></i> <?php echo htmlentities((string) $vo['umobile']); ?>
        </div>
        <div class="time-info">
          <i class="fa fa-clock-o"></i> <?php echo htmlentities((string) date("Y-m-d H:i:s",!is_numeric($vo['addtime'])? strtotime($vo['addtime']) : $vo['addtime'])); ?>
        </div>
      </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- 添加弹窗容器 -->
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

  <script src="<?php echo request()->domain(); ?>/static/layui/layui.js"></script>
  <script src="<?php echo request()->domain(); ?>/static/jquery/jquery.min.js"></script>
  <script>
    // 确保表情符号正确处理
    $(document).ready(function() {
      // 处理表情符号显示
      $('.emoji-text').each(function() {
        const text = $(this).text();
        // 保持原始文本，但确保渲染正确
        $(this).html(text);
      });
    });
    
    layui.use(['layer', 'laydate', 'form'], function(){
      var layer = layui.layer;
      var laydate = layui.laydate;
      var form = layui.form;
      
      // 日期范围选择器
      laydate.render({
        elem: '#timeRange',
        type: 'datetime',
        range: true
      });
      
      // 导出TXT功能
      $('#exportTxt').click(function(){
        var userId = getUrlParam('id');
        if(!userId) {
          layer.msg('无法确定要导出的用户ID');
          return;
        }
        
        layer.confirm('确定要导出TXT格式的通讯录数据吗？', function(index) {
          var txtContent = "通讯录姓名,通讯录号码,上传时间\n";
          $('table tbody tr').each(function(){
            var name = $(this).data('name');
            var phone = $(this).data('phone');
            var time = $(this).data('time');
            // 处理可能包含的特殊字符和表情符号
            txtContent += name + "," + phone + "," + time + "\n";
          });
          
          var blob = new Blob([txtContent], {type: 'text/plain;charset=utf-8'});
          var a = document.createElement('a');
          a.download = '通讯录数据_' + new Date().getTime() + '.txt';
          a.href = window.URL.createObjectURL(blob);
          a.click();
          
          layer.close(index);
        });
      });
      
      // 添加显示Toast的函数
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
      
      // 添加按钮状态更新函数
      function updateClearButtonState() {
        var hasContacts;
        // 根据当前视图判断是否有数据
        if($('.table-view').is(':visible')) {
          hasContacts = $('.layui-table tbody tr').length > 0 && 
                       !$('.layui-table tbody tr td .empty-tip').length;
        } else {
          hasContacts = $('.grid-view .contact-card').length > 0 && 
                       !$('.grid-view .empty-tip').length;
        }
        
        var $clearBtn = $('#clearContacts');
        if(!hasContacts) {
          $clearBtn.css({
            'background': 'linear-gradient(135deg, #e0e0e0, #d5d5d5)',
            'cursor': 'not-allowed',
            'color': '#999',
            'box-shadow': 'none'
          }).attr('disabled', 'disabled');
        } else {
          $clearBtn.css({
            'background': '',
            'cursor': '',
            'color': '',
            'box-shadow': ''
          }).removeAttr('disabled');
        }
      }
      
      // 修改清空通讯录的点击事件处理
      $('#clearContacts').click(function(){
        if($(this).attr('disabled')) {
          showToast('通讯录已经是空的了', 'info');
          return;
        }

        var userId = getUrlParam('id');
        if(!userId) {
          showToast('无法确定要操作的用户ID', 'error');
          return;
        }
        
        // 根据当前视图判断是否有数据
        var hasContacts;
        if($('.table-view').is(':visible')) {
          hasContacts = $('.layui-table tbody tr').length > 0 && 
                       !$('.layui-table tbody tr td .empty-tip').length;
        } else {
          hasContacts = $('.grid-view .contact-card').length > 0 && 
                       !$('.grid-view .empty-tip').length;
        }
        
        if(!hasContacts) {
          showToast('通讯录已经是空的了', 'info');
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
              '<div class="confirm-title">确定清空通讯录？</div>' +
              '<div class="confirm-desc">删除后数据将无法恢复</div>' +
            '</div>',
          btn: ['确定', '取消'],
          btnAlign: 'c',
          yes: function(index){
            $.ajax({
              url: "<?php echo url('admin/appv1/clearContacts'); ?>",
              type: 'POST',
              data: {id: userId},
              dataType: 'json',
              success: function(res) {
                layer.close(index);
                if(res.code == 1) {
                  showToast(res.msg || '清空成功', 'success');
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
      
      // 修改视图切换功能
      $('.view-btn').click(function(){
        var view = $(this).data('view');
        $('.view-btn').removeClass('active');
        $(this).addClass('active');
        
        if(view === 'table') {
          $('.table-view').show();
          $('.grid-view').hide();
        } else {
          $('.table-view').hide();
          $('.grid-view').show();
        }
        
        // 视图切换后更新按钮状态
        updateClearButtonState();
      });
      
      // 页面加载完成后更新按钮状态
      $(document).ready(function() {
        updateClearButtonState();
      });
      
      // 在搜索完成后也更新按钮状态
      form.on('submit(search)', function(data){
        // ... 原有的搜索逻辑 ...
        updateClearButtonState();
        return false;
      });
      
      // 获取URL参数
      function getUrlParam(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if(r != null) return decodeURI(r[2]); return null;
      }
    });
  </script>
</body>
</html>
