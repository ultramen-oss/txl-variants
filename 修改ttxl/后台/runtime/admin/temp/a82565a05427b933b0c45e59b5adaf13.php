<?php /*a:1:{s:62:"/www/wwwroot/lsht999.top/app/admin/view/appv1/ip_location.html";i:1749558386;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>IP地址定位</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css" media="all" />
  <link rel="stylesheet" href="/static/css/admin.css" media="all">
  <!-- 引入移动端适配CSS -->
  <link rel="stylesheet" href="/app/admin/view/appv1/mobile.css" media="all">
  <!-- 引入jQuery -->
  <script src="https://cdn.bootcdn.net/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <style type="text/css">
    :root {
      --primary-color: #4F46E5;
      --primary-light: #6366F1;
      --secondary-color: #F43F5E;
      --success-color: #10B981;
      --light-color: #F9FAFB;
      --dark-color: #1F2937;
      --gray-color: #6B7280;
      --light-gray: #E5E7EB;
    }
    
    body, html {
      width: 100%;
      height: 100%;
      margin: 0;
      padding: 0;
      background-color: #F9FAFB;
      font-family: "SF Pro Display", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
      color: var(--dark-color);
      overflow-x: hidden;
    }
    
    .container {
      max-width: 740px;
      margin: 0 auto;
      padding: 0 0.5rem;
    }
    
    .header {
      background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
      color: white;
      padding: 0.75rem 1rem;
      border-radius: 0.75rem;
      margin-bottom: 0.75rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 5px 15px -5px rgba(79, 70, 229, 0.2);
    }
    
    .header-title {
      font-size: 1.1rem;
      font-weight: 600;
      display: flex;
      align-items: center;
    }
    
    .header-title i {
      margin-right: 0.5rem;
      font-size: 1.25rem;
    }
    
    .ip-badge {
      background: rgba(255,255,255,0.15);
      padding: 0.35rem 0.75rem;
      border-radius: 1.5rem;
      font-size: 0.9rem;
      font-weight: 500;
      backdrop-filter: blur(4px);
      -webkit-backdrop-filter: blur(4px);
      box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
    }
    
    .info-cards {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 0.75rem;
      margin-bottom: 0.75rem;
    }
    
    .info-card {
      background: white;
      border-radius: 0.75rem;
      padding: 0.875rem;
      box-shadow: 0 5px 10px -3px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      border: 1px solid rgba(229, 231, 235, 0.5);
    }
    
    .info-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 12px -5px rgba(0, 0, 0, 0.1);
    }
    
    .card-title {
      font-size: 1rem;
      font-weight: 600;
      margin-bottom: 0.625rem;
      padding-bottom: 0.5rem;
      border-bottom: 1px solid var(--light-gray);
      color: var(--dark-color);
      display: flex;
      align-items: center;
    }
    
    .card-title i {
      margin-right: 0.5rem;
      color: var(--primary-color);
      font-size: 1.1rem;
    }
    
    .card-item {
      display: flex;
      margin-bottom: 0.375rem;
      align-items: center;
    }
    
    .card-item:last-child {
      margin-bottom: 0;
    }
    
    .item-label {
      width: 75px;
      color: var(--gray-color);
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .item-value {
      flex: 1;
      color: var(--dark-color);
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    .warn {
      color: var(--secondary-color);
    }
    
    .success {
      color: var(--success-color);
    }
    
    .action-btns {
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      margin-top: 0.75rem;
      justify-content: center;
    }
    
    .btn {
      padding: 0.45rem 0.75rem;
      border-radius: 0.5rem;
      border: none;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: 500;
      display: flex;
      align-items: center;
      transition: all 0.3s;
      box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
    }
    
    .btn i {
      margin-right: 0.5rem;
      font-size: 1rem;
    }
    
    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 12px -3px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
      background: var(--primary-color);
      color: white;
    }
    
    .btn-primary:hover {
      background: var(--primary-light);
    }
    
    .btn-danger {
      background: var(--secondary-color);
      color: white;
    }
    
    .btn-danger:hover {
      background: #E11D48;
    }
    
    .btn-success {
      background: var(--success-color);
      color: white;
    }
    
    .btn-success:hover {
      background: #059669;
    }
    
    .loading-container {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255,255,255,0.9);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 1001;
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
    }
    
    .loading-text {
      background: white;
      padding: 1.25rem 1.5rem;
      border-radius: 1rem;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      font-size: 1rem;
      border: 1px solid var(--light-gray);
    }
    
    .loading-text i {
      margin-right: 0.75rem;
      font-size: 1.25rem;
      color: var(--primary-color);
    }
    
    .animate-pulse {
      animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.5;
      }
    }
    
    @media screen and (max-width: 768px) {
      .container {
        margin: 10px auto;
        padding: 0 10px;
      }
      
      .header {
        padding: 12px 15px;
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      
      .ip-badge {
        align-self: flex-start;
        margin-top: 5px;
      }
      
      .info-cards {
        grid-template-columns: 1fr;
        gap: 12px;
      }
      
      .card-title {
        font-size: 14px;
      }
      
      .item-label {
        width: 70px;
        font-size: 12px;
      }
      
      .item-value {
        font-size: 12px;
      }
      
      .action-btns {
        flex-direction: column;
        align-items: stretch;
        margin-top: 15px;
      }
      
      .btn {
        width: 100%;
        justify-content: center;
        padding: 10px;
        margin-bottom: 8px;
      }
      
      .loading-container {
        padding: 0 15px;
      }
      
      .loading-text {
        width: 85%;
        max-width: 300px;
        font-size: 14px;
        flex-direction: column;
        padding: 20px 15px;
        text-align: center;
        gap: 15px;
      }
      
      .loading-text i {
        font-size: 24px;
      }
    }
    
    /* 小型手机屏幕的额外优化 */
    @media screen and (max-width: 480px) {
      .container {
        margin: 5px auto;
      }
      
      .header {
        border-radius: 10px;
        padding: 10px;
      }
      
      .info-card {
        padding: 10px;
        border-radius: 10px;
      }
      
      .btn {
        font-size: 13px;
      }
      
      .btn i {
        font-size: 16px;
      }
    }
    
    .ip-info {
      margin-bottom: 20px;
      padding: 15px;
      background: #fff;
      border-radius: 5px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .ip-row {
      margin-bottom: 10px;
      display: flex;
      align-items: flex-start;
    }
    .ip-label {
      width: 80px;
      color: #666;
      font-weight: bold;
    }
    .ip-value {
      flex: 1;
      word-break: break-all;
    }
    .warn {
      color: #ff6b6b;
      font-weight: bold;
    }
    .action-btn {
      margin-top: 5px;
      margin-right: 10px;
    }
    /* 加载中提示样式 */
    .loading-container {
      text-align: center;
      padding: 20px;
      background: rgba(255,255,255,0.8);
      border-radius: 5px;
      margin-bottom: 20px;
      display: none;
    }
    .loading-text {
      color: #666;
      font-size: 16px;
    }
    /* 地图容器样式 */
    .map-container {
      margin-bottom: 20px;
      height: 400px;
      border-radius: 5px;
      overflow: hidden;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      display: none;
    }
    #map {
      width: 100%;
      height: 100%;
    }
    .map-error {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
      background: #f8f9fa;
      color: #dc3545;
      font-size: 16px;
    }
    .map-error i {
      margin-right: 8px;
    }
    /* 按钮样式美化 */
    .btn-refresh {
      background-color: #17a2b8;
      border-color: #17a2b8;
    }
    .btn-refresh:hover {
      background-color: #138496;
      border-color: #117a8b;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="header-title">
        <i class="fa fa-globe"></i> IP地址定位
      </div>
      <div class="ip-badge"><?php echo htmlentities((string) (isset($ip) && ($ip !== '')?$ip:"")); ?></div>
    </div>
    
    <div class="info-cards">
      <div class="info-card">
        <div class="card-title">
          <i class="fa fa-info-circle"></i> 基本信息
        </div>
        <div class="card-item">
          <div class="item-label">关联设备:</div>
          <div class="item-value"><?php echo htmlentities((string) (isset($user_info['clientid']) && ($user_info['clientid'] !== '')?$user_info['clientid']:"")); ?></div>
        </div>
        <div class="card-item">
          <div class="item-label">关联账号:</div>
          <div class="item-value"><?php echo htmlentities((string) (isset($user_info['name']) && ($user_info['name'] !== '')?$user_info['name']:"")); ?></div>
        </div>
        <div class="card-item">
          <div class="item-label">IP地址:</div>
          <div class="item-value" id="ip-address"><?php echo htmlentities((string) (isset($ip) && ($ip !== '')?$ip:"")); ?></div>
        </div>
        <div class="card-item">
          <div class="item-label">ISP:</div>
          <div class="item-value" id="ip-isp">
            <span class="animate-pulse">加载中...</span>
          </div>
        </div>
      </div>
      
      <div class="info-card">
        <div class="card-title">
          <i class="fa fa-map-marker"></i> 位置信息
        </div>
        <div class="card-item">
          <div class="item-label">国家/地区:</div>
          <div class="item-value" id="ip-country">
            <span class="animate-pulse">加载中...</span>
          </div>
        </div>
        <div class="card-item">
          <div class="item-label">省份:</div>
          <div class="item-value" id="ip-province">
            <span class="animate-pulse">加载中...</span>
          </div>
        </div>
        <div class="card-item">
          <div class="item-label">城市:</div>
          <div class="item-value" id="ip-city">
            <span class="animate-pulse">加载中...</span>
          </div>
        </div>
        <div class="card-item">
          <div class="item-label">经纬度:</div>
          <div class="item-value" id="ip-coords">
            <span class="animate-pulse">加载中...</span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="action-btns">
      <button class="btn btn-primary" onclick="window.open('https://www.geolocation.com/?ip=<?php echo htmlentities((string) (isset($ip) && ($ip !== '')?$ip:'')); ?>', '_blank')">
        <i class="fa fa-map-marker"></i> Geolocation查询
      </button>
      <button class="btn btn-danger" onclick="blacklistIp('<?php echo htmlentities((string) (isset($ip) && ($ip !== '')?$ip:'')); ?>')">
        <i class="fa fa-ban"></i> 拉黑此IP
      </button>
      <button class="btn btn-success" onclick="refreshInfo()">
        <i class="fa fa-refresh"></i> 刷新信息
      </button>
    </div>
  </div>
  
  <div id="loading" class="loading-container">
    <div class="loading-text">
      <i class="fa fa-spinner fa-spin"></i> 正在获取IP位置信息...
    </div>
  </div>
  
  <script>
    $(document).ready(function() {
      // 初始化获取IP信息，直接使用外部API
      getIpInfo(true);
    });
    
    // 刷新信息功能
    function refreshInfo() {
      $('#loading').show();
      getIpInfo(true); // 强制使用外部API
    }
    
    // 获取IP信息
    function getIpInfo(forceExternalApi) {
      var ip = "<?php echo htmlentities((string) (isset($ip) && ($ip !== '')?$ip:'')); ?>";
      if (!ip) {
        hideLoading();
        alert('未提供IP地址');
        return;
      }
      
      // 显示加载提示
      $('#loading').show();
      
      // 如果强制使用外部API，直接使用ipinfo.io
      if (forceExternalApi) {
        useIpInfo(ip);
        return;
      }
      
      // 清除所有之前的数据源提示
      $('.data-source-info').remove();
      
      // 调用后端API获取IP信息
      $.ajax({
        url: '<?php echo url("admin/appv1/getIpInfo"); ?>',
        type: 'GET',
        dataType: 'json',
        data: {ip: ip},
        success: function(res) {
          if (res.code == 1 && res.data) {
            var data = res.data;
            // 更新位置信息
            $('#ip-country').html(data.country || '未知');
            $('#ip-province').html(data.province || '未知');
            $('#ip-city').html(data.city || '未知');
            $('#ip-coords').html(data.lng && data.lat ? data.lng + ', ' + data.lat : '未知');
            $('#ip-isp').html(data.isp || '未知');
            
            // 标记VPN或代理
            if (data.is_vpn) {
              $('#ip-country').append(' <span class="warn">(可能为VPN/代理)</span>');
            }
            
            // 显示警告，提示用户结果可能不准确
            var warningHtml = '<div class="data-source-info" style="margin-top: 10px;"><span class="warn"><i class="fa fa-exclamation-triangle"></i> 注意：IP地理位置数据可能不准确，建议使用多个来源验证。</span></div>';
            $('#ip-coords').after(warningHtml);
            
            // 隐藏加载提示
            hideLoading();
          } else {
            // 使用ipinfo.io
            useIpInfo(ip);
          }
        },
        error: function() {
          // 使用ipinfo.io
          useIpInfo(ip);
        }
      });
    }
    
    // ipinfo.io查询
    function useIpInfo(ip) {
      console.log("使用ipinfo.io查询IP信息");
      
      // 显示正在使用备用API的提示
      $('#ip-country').html('<span class="animate-pulse">使用ipinfo.io查询中...</span>');
      $('#ip-province').html('<span class="animate-pulse">使用ipinfo.io查询中...</span>');
      $('#ip-city').html('<span class="animate-pulse">使用ipinfo.io查询中...</span>');
      $('#ip-coords').html('<span class="animate-pulse">使用ipinfo.io查询中...</span>');
      $('#ip-isp').html('<span class="animate-pulse">使用ipinfo.io查询中...</span>');
      
      // 清除所有之前的数据源提示
      $('.data-source-info').remove();
      
      // 使用ipinfo.io
      $.ajax({
        url: 'https://ipinfo.io/' + ip + '/json',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
          if (data && data.country) {
            $('#ip-country').html(data.country || '未知');
            $('#ip-province').html(data.region || '未知');
            $('#ip-city').html(data.city || '未知');
            $('#ip-coords').html(data.loc || '未知');
            $('#ip-isp').html(data.org || '未知');
            
            // 显示警告，提示用户使用了外部数据源
            var warningHtml = '<div class="data-source-info" style="margin-top: 10px;"><span class="warn"><i class="fa fa-info-circle"></i> 数据源：ipinfo.io</span></div>';
            $('#ip-coords').after(warningHtml);
          } else {
            setDefaultValues();
          }
          
          hideLoading();
        },
        error: function() {
          setDefaultValues();
          hideLoading();
        }
      });
    }
    
    // 设置默认值
    function setDefaultValues() {
      $('#ip-country').html('<span class="warn">获取失败</span>');
      $('#ip-province').html('<span class="warn">获取失败</span>');
      $('#ip-city').html('<span class="warn">获取失败</span>');
      $('#ip-coords').html('<span class="warn">获取失败</span>');
      $('#ip-isp').html('<span class="warn">获取失败</span>');
      
      // 清除所有之前的数据源提示
      $('.data-source-info').remove();
      
      // 显示建议手动查询的提示
      var suggestionHtml = '<div class="data-source-info" style="margin-top: 10px;"><span class="warn"><i class="fa fa-exclamation-circle"></i> API查询失败，请使用上方的"Geolocation查询"按钮手动查询。</span></div>';
      $('#ip-coords').after(suggestionHtml);
    }
    
    // 隐藏加载中提示
    function hideLoading() {
      $('#loading').hide();
    }
    
    // 拉黑IP功能
    function blacklistIp(ip) {
      if (!ip) {
        alert('IP地址为空，无法拉黑');
        return;
      }
      
      if (confirm('确定要拉黑此IP: ' + ip + ' 吗？')) {
        // 显示加载中
        $('#loading').show();
        $('.loading-text').html('<i class="fa fa-spinner fa-spin"></i> 正在拉黑IP...');
        
        // 发送AJAX请求拉黑IP
        $.ajax({
          url: '<?php echo url("admin/appv1/blacklistIp"); ?>',
          type: 'POST',
          data: {ip: ip},
          dataType: 'json',
          success: function(res) {
            if (res.code == 1) {
              alert('IP已成功拉黑！');
            } else {
              alert(res.msg || '拉黑IP失败');
            }
            $('#loading').hide();
          },
          error: function() {
            alert('网络错误，请重试');
            $('#loading').hide();
          }
        });
      }
    }
  </script>
</body>
</html> 