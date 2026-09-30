<?php /*a:1:{s:61:"/www/wwwroot/154.64.230.166/app/admin/view/appv1/dingwei.html";i:1749557192;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>设备位置定位</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/static/layui/css/layui.css">
  <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" href="/static/css/admin.css">
  <!-- 引入移动端适配CSS -->
  <link rel="stylesheet" href="/app/admin/view/appv1/mobile.css" media="all">

  <!-- 高德地图API -->
  <script src="https://webapi.amap.com/maps?v=1.4.15&key=你的高德API密钥"></script>

  <!-- Leaflet 地图样式 -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <style>
    body, html, #allmap, #leafletMap {
      width: 100%;
      height: 100%;
      overflow: hidden;
      margin: 0;
      font-family: "微软雅黑";
    }
    #leafletMap {
      display: none;
    }
    .map-toggle {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 1000;
      background: white;
      padding: 8px 12px;
      border-radius: 30px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 5px;
      transition: all 0.3s;
    }
    
    .map-toggle:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .loading-container {
      position: absolute;
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
    }
    
    .loading-text {
      background: white;
      padding: 15px 20px;
      border-radius: 10px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.15);
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .loading-text i {
      color: #8257e6;
      font-size: 18px;
    }
    
    /* 定位信息卡片 */
    .location-info {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background: white;
      border-radius: 12px;
      padding: 15px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      z-index: 1000;
      width: 90%;
      max-width: 350px;
    }
    
    .location-title {
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 5px;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    .location-title i {
      color: #8257e6;
    }
    
    .location-coords {
      font-size: 12px;
      color: #666;
      display: flex;
      align-items: center;
      gap: 5px;
    }
    
    /* 移动端适配 */
    @media screen and (max-width: 768px) {
      .map-toggle {
        top: 15px;
        right: 15px;
        padding: 8px 15px;
        font-size: 13px;
      }
      
      .loading-text {
        padding: 15px 20px;
      }
      
      .location-info {
        bottom: 15px;
        padding: 12px;
        width: 85%;
      }
    }
  </style>
</head>
<body style="padding:0;">
  <div id="allmap"></div>
  <div id="leafletMap"></div>

  <div class="map-toggle" onclick="toggleMap()">
    <i class="fa fa-exchange"></i> 切换地图源
  </div>

  <div id="loading" class="loading-container">
    <div class="loading-text">
      <i class="fa fa-spinner fa-spin"></i> 正在加载地图数据...
    </div>
  </div>
  
  <div class="location-info">
    <div class="location-title">
      <i class="fa fa-mobile"></i> 设备信息
    </div>
    <div id="device-info">设备名称：<?php echo htmlentities((string) $dingweiid['clientid']); ?> <br>登录手机: <?php echo htmlentities((string) $dingweiid['name']); ?></div>
    <div class="location-coords">
      <i class="fa fa-map-marker"></i> 坐标: <?php echo htmlentities((string) $dingweiid['mapx']); ?>, <?php echo htmlentities((string) $dingweiid['mapy']); ?>
    </div>
  </div>

  <div id="map-address0" style="display:none">
    设备名称：<?php echo htmlentities((string) $dingweiid['clientid']); ?> 登录手机:<?php echo htmlentities((string) $dingweiid['name']); ?>
  </div>

<script>
  var isChina = true;
  var currentMapType = 'amap';

  var longitude = Number(<?php echo htmlentities((string) $dingweiid['mapx']); ?>);
  var latitude = Number(<?php echo htmlentities((string) $dingweiid['mapy']); ?>);
  var deviceInfo = "<?php echo htmlentities((string) $dingweiid['clientid']); ?> - <?php echo htmlentities((string) $dingweiid['name']); ?>";

  // 判断是否在中国区域
  if (longitude < 73 || longitude > 135 || latitude < 4 || latitude > 53) {
    isChina = false;
    document.getElementById('allmap').style.display = 'none';
    document.getElementById('leafletMap').style.display = 'block';
    currentMapType = 'leaflet';
  }

  // 初始化高德地图
  function initAMap() {
    var map = new AMap.Map("allmap", {
      resizeEnable: true,
      center: [longitude, latitude],
      zoom: 15
    });

    var marker = new AMap.Marker({
      position: new AMap.LngLat(longitude, latitude),
      title: deviceInfo
    });

    marker.setMap(map);

    var infoWindow = new AMap.InfoWindow({
      content: document.getElementById("map-address0").innerHTML,
      offset: new AMap.Pixel(0, -30)
    });

    marker.on('click', function () {
      infoWindow.open(map, marker.getPosition());
    });

    map.setZoomAndCenter(15, [longitude, latitude]);
    hideLoading();
  }

  // 初始化 Leaflet 地图
  function initLeafletMap() {
    var map = L.map('leafletMap').setView([latitude, longitude], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18
    }).addTo(map);

    L.marker([latitude, longitude]).addTo(map)
      .bindPopup(document.getElementById("map-address0").innerHTML)
      .openPopup();

    hideLoading();
  }

  // 地图切换功能
  function toggleMap() {
    if (currentMapType === 'amap') {
      document.getElementById('allmap').style.display = 'none';
      document.getElementById('leafletMap').style.display = 'block';
      currentMapType = 'leaflet';
      initLeafletMap();
    } else {
      document.getElementById('allmap').style.display = 'block';
      document.getElementById('leafletMap').style.display = 'none';
      currentMapType = 'amap';
      initAMap();
    }
  }

  // 隐藏加载中动画
  function hideLoading() {
    var loading = document.getElementById('loading');
    if (loading) {
      loading.style.display = 'none';
    }
  }

  // 页面加载完执行初始化
  window.onload = function () {
    if (currentMapType === 'amap') {
      initAMap();
    } else {
      initLeafletMap();
    }
  }
</script>
</body>
</html>