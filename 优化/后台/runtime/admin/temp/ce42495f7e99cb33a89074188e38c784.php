<?php /*a:1:{s:57:"/www/wwwroot/154.64.230.166/app/admin/view/appv1/sms.html";i:1749557372;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>短信记录</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/static/layui/css/layui.css"  media="all">
  <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css" media="all" />
  <link rel="stylesheet" href="/static/admin/css/admin.css"  media="all">
  <!-- 引入移动端适配CSS -->
  <link rel="stylesheet" href="/app/admin/view/appv1/mobile.css" media="all">
  <style type="text/css">
    body { 
      background-color: #fff; 
      padding: 0; 
      margin: 0; 
      font-family: 'Helvetica Neue', Helvetica, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', Arial, sans-serif;
    }
    
    .tplay-body-div {
      background: #fff;
      padding: 0;
      margin: 0;
    }

    .top-stats {
      background: #fff;
      border-bottom: 1px solid #f0f0f0;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .stats-left {
      display: flex;
      align-items: center;
      color: #666;
      font-size: 13px;
      background: #f8f8f8;
      padding: 8px 16px;
      border-radius: 20px;
      box-shadow: 0 2px 6px rgba(133, 87, 230, 0.1);
      transition: all 0.3s ease;
    }

    .stats-left:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(133, 87, 230, 0.15);
    }

    .stats-left .fa {
      color: #8557e6;
      margin-right: 8px;
      font-size: 16px;
    }

    .stats-right {
      display: flex;
      gap: 10px;
    }

    .export-btn {
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 13px;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      color: white;
      text-decoration: none;
    }
    
    .export-btn i {
      margin-right: 5px;
    }
    
    #clearSms {
      background: linear-gradient(135deg, #e74c3c, #c0392b) !important;
      color: white !important;
    }

    #exportTxt {
      background: linear-gradient(135deg, #f39c12, #f1c40f) !important;
    }

    button[onclick="exportExcel()"] {
      background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
    }

    .export-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }

    /* 按钮禁用状态样式 */
    .export-btn[disabled] {
      background: linear-gradient(135deg, #e0e0e0, #d5d5d5) !important;
      cursor: not-allowed !important;
      color: #999 !important;
      box-shadow: none !important;
      transform: none !important;
      opacity: 0.7 !important;
      pointer-events: none !important;
    }

    .action-btn.clear[disabled] {
      background: #f5f5f5 !important;
      color: #999 !important;
      cursor: not-allowed !important;
      border: 1px solid #d9d9d9 !important;
      transform: none !important;
      box-shadow: none !important;
    }
    
    /* 改进按钮的禁用样式 */
    #clearSms[disabled] {
      background: linear-gradient(135deg, #e0e0e0, #d5d5d5) !important;
      cursor: not-allowed !important;
      color: #999 !important;
      box-shadow: none !important;
      transform: none !important;
      opacity: 0.7 !important;
    }

    /* Toast样式修改 */
    .toast.success {
      background: linear-gradient(45deg, #27ae60, #2ecc71) !important;
    }

    .toast.error {
      background: linear-gradient(45deg, #e74c3c, #c0392b) !important;
    }

    .toast.info {
      background: linear-gradient(45deg, #3498db, #2980b9) !important;
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

    .layui-table {
      margin: 0;
      font-family: 'Helvetica Neue', Helvetica, 'PingFang SC', 'Hiragino Sans GB', 'Microsoft YaHei', Arial, sans-serif;
    }

    .layui-table thead tr th {
      background: #8557e6;
      color: #fff;
      font-weight: 500;
      font-size: 13px;
      padding: 12px 20px;
      text-align: left;
      border: none;
    }

    .layui-table tbody tr td {
      padding: 12px 20px;
      text-align: left;
      font-size: 13px;
      color: #666;
      border-bottom: 1px solid #f0f0f0;
      word-break: break-all;
      line-height: 1.6;
    }

    .layui-table tbody tr td:nth-child(2) {
      font-weight: 600;
      color: #333;
    }

    .layui-table tbody tr:hover {
      background-color: #f9f9f9;
    }

    .layui-table[lay-skin=line] {
      border: none;
    }

    .empty-tip {
      text-align: center;
      padding: 40px 0;
      color: #999;
    }

    .empty-tip i {
      font-size: 32px;
      margin-bottom: 10px;
      display: block;
      color: #d9d9d9;
    }

    .empty-tip p {
      font-size: 13px;
      margin: 0;
    }

    .page-div {
      padding: 20px;
      text-align: center;
    }

    .layui-laypage a, .layui-laypage span {
      background-color: #f4f4f4;
      color: #666;
      margin: 0 2px;
      font-size: 13px;
      border-radius: 20px;
      min-width: 30px;
      height: 30px;
      line-height: 30px;
    }

    .layui-laypage .layui-laypage-curr .layui-laypage-em {
      background-color: #8557e6;
      border-radius: 20px;
    }

    .layui-laypage input:focus, 
    .layui-laypage select:focus {
      border-color: #8557e6!important;
    }
    
    /* 增强分页样式 */
    .layui-laypage {
      margin: 10px 0;
      display: inline-block;
    }
    
    .layui-laypage .layui-laypage-count {
      padding: 0 10px;
      margin-right: 10px;
      color: #666;
      font-size: 13px;
    }
    
    .layui-laypage .layui-laypage-skip {
      margin-left: 10px;
    }
    
    .layui-laypage .layui-laypage-skip input {
      height: 26px;
      line-height: 26px;
      width: 40px;
      margin: 0 5px;
      padding: 0 5px;
      border-radius: 3px;
      border: 1px solid #e2e2e2;
    }
    
    .layui-laypage .layui-laypage-limits {
      margin-right: 10px;
    }
    
    .layui-laypage .layui-laypage-limits select {
      height: 28px;
      line-height: 28px;
      border-radius: 3px;
      border: 1px solid #e2e2e2;
      padding: 0 5px;
    }
    
    .layui-laypage button, 
    .layui-laypage input, 
    .layui-laypage select {
      border-radius: 3px;
    }
    
    .layui-laypage .layui-laypage-btn {
      border-radius: 20px;
      background-color: #8557e6;
      color: #fff;
      font-size: 13px;
      padding: 0 12px;
      height: 30px;
      line-height: 30px;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .layui-laypage .layui-laypage-btn:hover {
      opacity: 0.9;
      box-shadow: 0 2px 5px rgba(133, 87, 230, 0.2);
    }

    /* Toast提示框样式 */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 999999;
      max-height: 100vh;
      overflow-y: hidden;
    }
    
    /* Toast 样式 */
    .toast {
      background: rgba(0, 0, 0, 0.8);
      border-radius: 8px;
      padding: 0;
      margin-bottom: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transform: translateX(120%);
      transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      min-width: 250px;
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

    /* 移动端适配样式 */
    @media screen and (max-width: 768px) {
      .top-stats {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
        padding: 10px 15px;
      }
      
      .stats-left {
        width: 100%;
        justify-content: center;
      }
      
      .stats-right {
        width: 100%;
      }
      
      .export-btns {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        width: 100%;
      }
      
      .export-btn {
        flex: 1;
        min-width: 100px;
        text-align: center;
        justify-content: center;
      }
      
      .layui-table {
        margin: 0;
      }
      
      .layui-table thead tr th {
        padding: 10px;
        white-space: nowrap;
      }
      
      .layui-table tbody tr td {
        padding: 10px;
        word-break: break-word;
      }
      
      /* 创建表格的水平滚动容器 */
      .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }
      
      /* 确保表格在容器内可以完整显示 */
      .table-container .layui-table {
        min-width: 600px;
        width: 100%;
      }
      
      /* 分页容器优化 */
      .page-div {
        padding: 15px 10px;
      }
      
      /* 表格内容区域优化 */
      .layui-table tbody tr td:nth-child(3) {
        max-width: 200px;
      }
      
      /* 移动端分页样式优化 */
      .layui-laypage a, 
      .layui-laypage span {
        margin: 0 2px;
        min-width: 28px;
        height: 28px;
        line-height: 28px;
        font-size: 12px;
      }
      
      .layui-laypage .layui-laypage-count,
      .layui-laypage .layui-laypage-limits {
        display: none;
      }
      
      .layui-laypage .layui-laypage-skip input {
        width: 30px;
        margin: 0 2px;
      }
      
      .layui-laypage .layui-laypage-skip {
        margin-left: 5px;
      }
    }
    
    /* 小型手机屏幕的额外优化 */
    @media screen and (max-width: 480px) {
      .export-btn {
        font-size: 12px;
        padding: 6px 10px;
      }
      
      .export-btn i {
        margin-right: 2px;
      }
      
      .layui-table thead tr th,
      .layui-table tbody tr td {
        padding: 8px 5px;
        font-size: 12px;
      }
      
      .empty-tip i {
        font-size: 24px;
      }
      
      .empty-tip p {
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
    <div class="top-stats">
      <div class="stats-left">
        <i class="fa fa-comments"></i>
        共 <?php if(!(empty($info) || (($info instanceof \think\Collection || $info instanceof \think\Paginator ) && $info->isEmpty()))): ?><?php echo htmlentities((string) $info->total()); else: ?>0<?php endif; ?> 条记录
      </div>
      <div class="stats-right">
        <div class="export-btns">
          <?php if(empty($info) || (($info instanceof \think\Collection || $info instanceof \think\Paginator ) && $info->isEmpty())): ?>
            <button class="export-btn" id="clearSms" disabled="disabled" style="background: #cccccc !important; color: #888888; cursor: not-allowed; box-shadow: none; transform: none;">
              <i class="fa fa-trash"></i>清空记录
            </button>
          <?php else: if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_delete_user'] == 1)): ?>
            <button class="export-btn" id="clearSms">
              <i class="fa fa-trash"></i>清空记录
            </button>
            <?php else: ?>
            <button class="export-btn" style="background: #cccccc !important; color: #888888; cursor: not-allowed; box-shadow: none; transform: none;" title="没有删除权限">
              <i class="fa fa-trash"></i>清空记录
            </button>
            <?php endif; ?>
          <?php endif; if($current_admin['role_type'] == 'super_admin' || $current_admin['role_type'] == 'admin' || ($current_admin['role_type'] == 'user' && $current_admin['can_export_data'] == 1)): ?>
          <button class="export-btn" onclick="exportTxt()" id="exportTxt">
            <i class="fa fa-file-text-o"></i>导出TXT
          </button>
          <button class="export-btn" onclick="exportExcel()">
            <i class="fa fa-file-excel-o"></i>导出Excel
          </button>
          <?php else: ?>
          <button class="export-btn" style="background: #cccccc !important; color: #888888; cursor: not-allowed; box-shadow: none; transform: none;" title="没有导出权限">
            <i class="fa fa-file-text-o"></i>导出TXT
          </button>
          <button class="export-btn" style="background: #cccccc !important; color: #888888; cursor: not-allowed; box-shadow: none; transform: none;" title="没有导出权限">
            <i class="fa fa-file-excel-o"></i>导出Excel
          </button>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="table-container">
      <table class="layui-table" lay-skin="line">
        <thead>
          <tr>
            <th width="180">时间</th>
            <th width="150">号码</th>
            <th>内容</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($info) || (($info instanceof \think\Collection || $info instanceof \think\Paginator ) && $info->isEmpty())): ?>
          <tr>
            <td colspan="3">
              <div class="empty-tip">
                <i class="fa fa-folder-open-o"></i>
                <p>暂无短信记录</p>
              </div>
            </td>
          </tr>
          <?php else: if(is_array($info) || $info instanceof \think\Collection || $info instanceof \think\Paginator): $i = 0; $__LIST__ = $info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
          <tr>
            <td><?php echo htmlentities((string) date("Y-m-d H:i:s",!is_numeric($vo['smstime'])? strtotime($vo['smstime']) : $vo['smstime'])); ?></td>
            <td><?php echo htmlentities((string) $vo['smstel']); ?></td>
            <td><?php echo htmlentities((string) $vo['smscontent']); ?></td>
          </tr>
          <?php endforeach; endif; else: echo "" ;endif; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?php if(!(empty($info) || (($info instanceof \think\Collection || $info instanceof \think\Paginator ) && $info->isEmpty()))): ?>
    <div class="page-div">
      <div class="custom-pagination">
        <?php 
        // 获取当前的分页对象
        $page = $info;
        
        // 获取请求参数中的用户ID
        $userId = request()->param('id/d', 0);
        
        // 当前页码
        $currentPage = $page->currentPage();
        // 最后页码
        $lastPage = $page->lastPage();
        // 每页显示的记录数
        $listRows = $page->listRows();
        // 总记录数
        $total = $page->total();
        
        // 生成url的基础函数
        $makeUrl = function($page) use ($userId) {
            $params = ['page' => $page];
            if($userId) {
                $params['id'] = $userId;
            }
            return url('admin/appv1/sms', $params);
        };
        
        // 开始构建分页HTML
        echo '<div class="layui-box layui-laypage layui-laypage-default">';
        
        // 显示总记录数
        echo '<span class="layui-laypage-count">共 '.$total.' 条</span>';
        
        // 首页和上一页
        if($currentPage > 1) {
            echo '<a href="'.$makeUrl(1).'" class="layui-laypage-first" title="首页">首页</a>';
            echo '<a href="'.$makeUrl($currentPage-1).'" class="layui-laypage-prev" title="上一页">&lt;</a>';
        } else {
            echo '<span class="layui-laypage-first" title="首页">首页</span>';
            echo '<span class="layui-laypage-prev" title="上一页">&lt;</span>';
        }
        
        // 页码按钮
        $showPages = 5; // 显示的页码数量
        $startPage = max(1, $currentPage - floor($showPages / 2));
        $endPage = min($lastPage, $startPage + $showPages - 1);
        
        // 调整startPage以确保显示的页码数量
        $startPage = max(1, $endPage - $showPages + 1);
        
        // 页码
        for($i = $startPage; $i <= $endPage; $i++) {
            if($i == $currentPage) {
                echo '<span class="layui-laypage-curr"><em class="layui-laypage-em"></em><em>'.$i.'</em></span>';
            } else {
                echo '<a href="'.$makeUrl($i).'">'.$i.'</a>';
            }
        }
        
        // 下一页和尾页
        if($currentPage < $lastPage) {
            echo '<a href="'.$makeUrl($currentPage+1).'" class="layui-laypage-next" title="下一页">&gt;</a>';
            echo '<a href="'.$makeUrl($lastPage).'" class="layui-laypage-last" title="尾页">尾页</a>';
        } else {
            echo '<span class="layui-laypage-next" title="下一页">&gt;</span>';
            echo '<span class="layui-laypage-last" title="尾页">尾页</span>';
        }
        
        // 跳转到指定页
        echo '<span class="layui-laypage-skip">到第<input type="text" min="1" value="'.$currentPage.'" class="layui-input">页
        <button type="button" class="layui-laypage-btn">确定</button></span>';
        
        echo '</div>';
         ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <script src="/static/layui/layui.js"></script>
  <script src="/static/jquery/jquery.min.js"></script>
  <script>
    layui.use(['layer'], function(){
      var layer = layui.layer;
      
      // 修复分页跳转按钮
      $(document).ready(function() {
        updateClearButtonState();
        
        // 获取用户ID
        var userId = getUrlParam('id');
        
        // 绑定跳转按钮点击事件
        $('.layui-laypage-btn').on('click', function() {
          var input = $(this).prev('input');
          var page = input.val();
          if (page && !isNaN(page)) {
            var url = window.location.pathname + '?page=' + page;
            if (userId) {
              url += '&id=' + userId;
            }
            window.location.href = url;
          }
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
      
      // 更新清空按钮状态
      function updateClearButtonState() {
        // 检查是否有短信记录 (不包含空提示的情况)
        var hasSms = $('.layui-table tbody tr').length > 0 && 
                    $('.layui-table tbody tr td .empty-tip').length === 0;
        
        var $clearBtn = $('#clearSms');
        if(!hasSms) {
          $clearBtn.attr('disabled', 'disabled');
        } else {
          $clearBtn.removeAttr('disabled');
        }
      }
      
      // 清空短信
      $('#clearSms').click(function(){
        if($(this).attr('disabled')) {
          showToast('短信记录已经是空的了', 'info');
          return;
        }

        var userId = getUrlParam('id');
        if(!userId) {
          showToast('无法确定要操作的用户ID', 'error');
          return;
        }
        
        var hasSms = $('.layui-table tbody tr').length > 0 && 
                    !$('.layui-table tbody tr td .empty-tip').length;
        
        if(!hasSms) {
          showToast('短信记录已经是空的了', 'info');
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
              '<div class="confirm-title">确定清空短信？</div>' +
              '<div class="confirm-desc">删除后数据将无法恢复</div>' +
            '</div>',
          btn: ['确定', '取消'],
          btnAlign: 'c',
          yes: function(index){
            $.ajax({
              url: '/admin/appv1/clearSms',
              type: 'post',
              data: {id: userId},
              dataType: 'json',
              success: function(res){
                layer.close(index);
                if(res.code == 1){
                  showToast('清空成功', 'success');
                  setTimeout(function(){
                    location.reload();
                  }, 1500);
                }else{
                  showToast(res.msg || '清空失败', 'error');
                }
              },
              error: function(){
                layer.close(index);
                showToast('服务器错误，请稍后重试', 'error');
              }
            });
          }
        });
      });
    });

    // 导出TXT
    function exportTxt() {
      var userId = getUrlParam('id');
      if(!userId) {
        layer.msg('无法确定要导出的用户ID');
        return;
      }
      
      var content = "时间,号码,内容\n";
      $('table tbody tr').each(function(){
        var time = $(this).find('td:eq(0)').text();
        var phone = $(this).find('td:eq(1)').text();
        var sms = $(this).find('td:eq(2)').text();
        content += time + "," + phone + "," + sms + "\n";
      });
      
      var blob = new Blob([content], {type: 'text/plain'});
      var a = document.createElement('a');
      a.download = '短信记录_' + new Date().getTime() + '.txt';
      a.href = window.URL.createObjectURL(blob);
      a.click();
    }

    // 导出Excel
    function exportExcel() {
      var userId = getUrlParam('id');
      if(!userId) {
        layer.msg('无法确定要导出的用户ID');
        return;
      }
      window.location.href = "<?php echo url('admin/appv1/smsexcel'); ?>?id=" + userId;
    }

    // 获取URL参数
    function getUrlParam(name) {
      var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
      var r = window.location.search.substr(1).match(reg);
      if(r != null) return decodeURI(r[2]); return null;
    }
  </script>
</body>
</html> 