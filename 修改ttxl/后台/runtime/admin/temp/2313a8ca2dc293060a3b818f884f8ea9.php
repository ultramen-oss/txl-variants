<?php /*a:1:{s:60:"/www/wwwroot/154.64.230.166/app/admin/view/appv1/config.html";i:1749557176;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>APP前端设置</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/font-awesome/css/font-awesome.min.css" media="all" />
  <link rel="stylesheet" href="/static/css/admin.css" media="all">
  <style type="text/css">
    body { 
      background-color: #f8f9fc; 
      padding: 0; 
      margin: 0; 
      font-family: "Microsoft YaHei", sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    /* 主容器 */
    .tplay-body-div {
      background: #fff;
      flex: 1;
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 100%;
      margin: 0;
      overflow: hidden;
    }
    
    /* 顶部标题区域 */
    .page-header {
      background: #fff;
      padding: 15px 20px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
      width: 100%;
      box-sizing: border-box;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      z-index: 10;
    }
    
    .page-title {
      font-size: 18px;
      font-weight: 600;
      color: #333;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .page-title i {
      color: #8257e6;
      font-size: 20px;
    }
    
    /* 内容区域 - 左右布局 */
    .settings-container {
      display: flex;
      flex: 1;
      overflow: hidden;
    }
    
    /* 左侧导航 */
    .settings-nav {
      width: 240px;
      background: #fff;
      border-right: 1px solid #eee;
      padding: 20px 0;
      overflow-y: auto;
      flex-shrink: 0;
    }
    
    .nav-item {
      padding: 12px 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 10px;
      color: #555;
      font-size: 14px;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }
    
    .nav-item:hover {
      background: rgba(130, 87, 230, 0.05);
      color: #8257e6;
    }
    
    .nav-item.active {
      background: rgba(130, 87, 230, 0.1);
      color: #8257e6;
      border-left-color: #8257e6;
      font-weight: 500;
    }
    
    .nav-item i {
      font-size: 16px;
      width: 20px;
      text-align: center;
    }
    
    /* 右侧内容区域 */
    .settings-content {
      flex: 1;
      padding: 20px;
      overflow-y: auto;
      background: #f8f9fc;
    }
    
    /* 表单样式 */
    .settings-form {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    
    .form-section {
      padding: 24px;
      border-bottom: 1px solid #f0f0f0;
      display: none; /* 默认隐藏所有部分 */
    }
    
    .form-section.active {
      display: block; /* 显示激活的部分 */
    }
    
    .form-section:last-child {
      border-bottom: none;
    }
    
    .section-title {
      font-size: 16px;
      font-weight: 600;
      margin-bottom: 20px;
      color: #333;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .section-title i {
      color: #8257e6;
    }
    
    .form-actions {
      padding: 20px;
      text-align: right;
      background: #fff;
      border-top: 1px solid #f0f0f0;
      border-radius: 0;
      position: sticky;
      bottom: 0;
      z-index: 5;
      box-shadow: 0 -3px 10px rgba(0,0,0,0.05);
    }
    
    /* 图片上传区域 */
    .image-upload-area {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 100%;
    }
    
    .upload-title {
      font-size: 14px;
      font-weight: 500;
      margin-bottom: 10px;
      color: #555;
    }
    
    .upload-desc {
      font-size: 12px;
      color: #999;
      margin-bottom: 15px;
    }
    
    .image-list {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 15px;
      width: 100%;
    }
    
    .image-item {
      position: relative;
      width: 120px;
      height: 120px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      border: 1px solid #eee;
    }
    
    .image-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    
    .image-actions {
      position: absolute;
      top: 5px;
      right: 5px;
      display: flex;
      gap: 5px;
    }
    
    .image-action {
      width: 24px;
      height: 24px;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 12px;
      transition: all 0.2s;
    }
    
    .image-action:hover {
      background: rgba(0, 0, 0, 0.8);
      transform: scale(1.1);
    }
    
    .add-image {
      width: 120px;
      height: 120px;
      border: 2px dashed #ddd;
      border-radius: 8px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
      color: #999;
    }
    
    .add-image:hover {
      border-color: #8257e6;
      color: #8257e6;
    }
    
    .add-image i {
      font-size: 24px;
      margin-bottom: 5px;
    }
    
    /* URL 输入区域 */
    .url-input-area {
      margin-top: 15px;
      width: 100%;
    }
    
    .url-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 100%;
    }
    
    .url-item {
      display: flex;
      align-items: center;
      gap: 10px;
      width: 100%;
    }
    
    .layui-input-url {
      flex: 1;
    }
    
    .url-delete {
      color: #ff4757;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 18px;
    }
    
    .url-delete:hover {
      transform: scale(1.2);
    }
    
    .add-url {
      margin-top: 15px;
      color: #8257e6;
      display: inline-flex;
      align-items: center;
      cursor: pointer;
      transition: all 0.2s;
      font-size: 14px;
      gap: 5px;
    }
    
    .add-url:hover {
      transform: translateX(3px);
    }
    
    /* 提交按钮区域 */
    .submit-btn {
      background: linear-gradient(45deg, #8257e6, #6c45c4);
      border: none;
      color: white;
      padding: 10px 30px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.3s;
      box-shadow: 0 3px 6px rgba(108, 69, 196, 0.2);
    }
    
    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 10px rgba(108, 69, 196, 0.3);
    }
    
    /* 提示信息 */
    .tips {
      background: rgba(130, 87, 230, 0.1);
      border-left: 3px solid #8257e6;
      padding: 10px 15px;
      border-radius: 0 4px 4px 0;
      margin-bottom: 20px;
      color: #555;
      font-size: 13px;
    }
    
    .tips i {
      color: #8257e6;
      margin-right: 5px;
    }
    
    /* 数量设置 */
    .count-setting {
      width: 100px;
    }
    
    /* 响应式处理 */
    @media screen and (max-width: 1024px) {
      .settings-container {
        flex-direction: column;
      }
      
      .settings-nav {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #eee;
        padding: 10px 0;
      }
      
      .nav-item {
        padding: 10px 15px;
      }
    }
    
    @media screen and (max-width: 768px) {
      .image-item, .add-image {
        width: 100px;
        height: 100px;
      }
    }
    
    /* 修复layui-input-block的留白问题 */
    .layui-form-item .layui-input-block {
      margin-left: 110px;
      width: calc(100% - 110px);
    }
    
    .layui-form-label {
      width: 90px;
    }
    
    /* 现代化弹窗样式 */
    .modern-dialog {
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .permission-dialog {
      padding: 0;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
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
    
    .permission-actions {
      display: flex;
      justify-content: flex-end;
      gap: 12px;
      padding: 20px;
      border-top: 1px solid #f3f4f6;
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

    .status-toast.info {
      background: linear-gradient(145deg, #3b82f6, #2563eb);
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
    <!-- 顶部标题 -->
    <div class="page-header">
      <div class="page-title">
        <i class="fa fa-sliders"></i> APP前端设置
      </div>
    </div>
    
    <!-- 内容区域 - 左右布局 -->
    <div class="settings-container">
      <!-- 左侧导航 -->
      <div class="settings-nav">
        <div class="nav-item active" data-section="basic-settings">
          <i class="fa fa-cog"></i> 基本设置
        </div>
        <div class="nav-item" data-section="bg-images">
          <i class="fa fa-picture-o"></i> 背景图片
        </div>
        <div class="nav-item" data-section="redirect-urls">
          <i class="fa fa-link"></i> 跳转链接
        </div>
        <div class="nav-item" data-section="logo-title">
          <i class="fa fa-image"></i> Logo和标题
        </div>
        <div class="nav-item" data-section="agreement-copyright">
          <i class="fa fa-file-text-o"></i> 服务协议和版权
        </div>
        <div class="nav-item" data-section="language-settings">
          <i class="fa fa-language"></i> 语言设置
        </div>
        <div class="nav-item" data-section="login-button-settings">
          <i class="fa fa-sign-in"></i> 登录按钮设置
        </div>
      </div>
      
      <!-- 右侧内容区域 -->
      <div class="settings-content">
        <div class="settings-form">
          <form class="layui-form" action="<?php echo url('admin/appv1/saveConfig'); ?>" method="post" id="appConfigForm">
            
            <!-- 基本设置 -->
            <div class="form-section active" id="basic-settings">
              <div class="section-title">
                <i class="fa fa-cog"></i> 基本设置
              </div>
              
              <div class="tips">
                <i class="fa fa-info-circle"></i> 在这里您可以设置APP的基本配置，包括屏幕方向和安全过滤选项。
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">屏幕方向</label>
                <div class="layui-input-block">
                  <input type="radio" name="screen_orientation" value="portrait" title="竖屏" <?php if(!isset($info['screen_orientation']) || $info['screen_orientation']=='portrait'): ?> checked <?php endif; ?>>
                  <input type="radio" name="screen_orientation" value="landscape" title="横屏" <?php if(isset($info['screen_orientation']) && $info['screen_orientation']=='landscape'): ?> checked <?php endif; ?>>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">安全过滤</label>
                <div class="layui-input-block">
                  <input type="radio" name="is_filter" value="1" title="启用" <?php if($info['is_filter']==1): ?> checked <?php endif; ?>>
                  <input type="radio" name="is_filter" value="0" title="禁用" <?php if($info['is_filter']==0): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">是否启用安全过滤功能</div>
                </div>
              </div>
            </div>
            
            <!-- 背景图片设置 -->
            <div class="form-section" id="bg-images">
              <div class="section-title">
                <i class="fa fa-picture-o"></i> 背景图片设置
              </div>
              
              <div class="image-upload-area">
                <div class="upload-desc">背景图片尺寸建议为 1080x1920 像素，支持JPG、PNG格式，每张图片大小不超过2MB，至少需要上传1张背景图片</div>
                
                <div class="tips" style="margin-bottom: 15px;">
                  <i class="fa fa-info-circle"></i> 上传图片后会自动保存到系统中，刷新页面可以看到所有已上传的图片。删除图片也会自动保存。只有修改链接地址时才需要点击下方的保存设置按钮。
                </div>
                
                <div class="image-list" id="bgImageList">
                  <?php if(is_array($bgImages) || $bgImages instanceof \think\Collection || $bgImages instanceof \think\Paginator): $i = 0; $__LIST__ = $bgImages;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$image): $mod = ($i % 2 );++$i;?>
                  <div class="image-item" data-src="<?php echo htmlentities((string) $image); ?>">
                    <img src="<?php echo htmlentities((string) $image); ?>" alt="背景图">
                    <div class="image-actions">
                      <div class="image-action delete-image" title="删除"><i class="fa fa-trash"></i></div>
                    </div>
                    <input type="hidden" name="bg_images[]" value="<?php echo htmlentities((string) $image); ?>">
                  </div>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                  
                  <div class="add-image" id="addBgImage">
                    <i class="fa fa-plus"></i>
                    <span>添加图片</span>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- 跳转链接设置 -->
            <div class="form-section" id="redirect-urls">
              <div class="section-title">
                <i class="fa fa-link"></i> 跳转链接设置
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">链接地址</label>
                <div class="layui-input-block">
                  <input type="text" name="urls[]" lay-verify="url" autocomplete="off" placeholder="请输入完整URL，如 https://example.com" class="layui-input" value="<?php echo htmlentities((string) (isset($urls['0']) && ($urls['0'] !== '')?$urls['0']:'')); ?>">
                  <div class="layui-form-mid layui-word-aux">设置前台登录后跳转的链接地址</div>
                </div>
              </div>
              
              <!-- 隐藏的URL数量设置，固定为1 -->
              <input type="hidden" name="url_count" value="1">
            </div>
            
            <!-- Logo和标题设置 -->
            <div class="form-section" id="logo-title">
              <div class="section-title">
                <i class="fa fa-image"></i> Logo和标题设置
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">是否显示</label>
                <div class="layui-input-block">
                  <input type="radio" name="show_logo_title" value="1" title="显示" <?php if(!isset($info['show_logo_title']) || $info['show_logo_title']==1): ?> checked <?php endif; ?>>
                  <input type="radio" name="show_logo_title" value="0" title="隐藏" <?php if(isset($info['show_logo_title']) && $info['show_logo_title']==0): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">设置是否在登录输入框上方显示Logo和标题</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">Logo图片</label>
                <div class="layui-input-block">
                  <div class="layui-upload">
                    <button type="button" class="layui-btn" id="upload-logo">上传Logo</button>
                    <div class="layui-upload-list" style="margin: 10px 0;">
                      <img src="<?php echo htmlentities((string) (isset($info['logo_image']) && ($info['logo_image'] !== '')?$info['logo_image']:'/static/images/default-logo.png')); ?>" id="logo-preview" style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                    </div>
                    <input type="hidden" name="logo_image" id="logo-url" value="<?php echo htmlentities((string) (isset($info['logo_image']) && ($info['logo_image'] !== '')?$info['logo_image']:'')); ?>">
                    <div class="layui-form-mid layui-word-aux">推荐尺寸: 200x60像素，支持PNG格式(透明背景)</div>
                  </div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">标题文字</label>
                <div class="layui-input-block">
                  <input type="text" name="login_title" autocomplete="off" placeholder="请输入登录页标题" class="layui-input" value="<?php echo htmlentities((string) (isset($info['login_title']) && ($info['login_title'] !== '')?$info['login_title']:'')); ?>">
                  <div class="layui-form-mid layui-word-aux">登录页面顶部显示的标题文字</div>
                </div>
              </div>
            </div>
            
            <!-- 服务协议和版权设置 -->
            <div class="form-section" id="agreement-copyright">
              <div class="section-title">
                <i class="fa fa-file-text-o"></i> 服务协议和版权设置
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">是否显示</label>
                <div class="layui-input-block">
                  <input type="radio" name="show_agreement" value="1" title="显示" <?php if(!isset($info['show_agreement']) || $info['show_agreement']==1): ?> checked <?php endif; ?>>
                  <input type="radio" name="show_agreement" value="0" title="隐藏" <?php if(isset($info['show_agreement']) && $info['show_agreement']==0): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">设置是否在登录按钮下方显示服务协议</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">服务协议URL</label>
                <div class="layui-input-block">
                  <input type="text" name="agreement_url" lay-verify="url" autocomplete="off" placeholder="请输入服务协议URL地址" class="layui-input" value="<?php echo htmlentities((string) (isset($info['agreement_url']) && ($info['agreement_url'] !== '')?$info['agreement_url']:'')); ?>">
                  <div class="layui-form-mid layui-word-aux">点击服务协议后跳转的链接地址</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">服务协议文字</label>
                <div class="layui-input-block">
                  <input type="text" name="agreement_text" autocomplete="off" placeholder="请输入服务协议文字" class="layui-input" value="<?php echo htmlentities((string) (isset($info['agreement_text']) && ($info['agreement_text'] !== '')?$info['agreement_text']:'登录即代表同意《服务协议》')); ?>">
                  <div class="layui-form-mid layui-word-aux">服务协议区域显示的文字内容</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">是否显示版权</label>
                <div class="layui-input-block">
                  <input type="radio" name="show_copyright" value="1" title="显示" <?php if(!isset($info['show_copyright']) || $info['show_copyright']==1): ?> checked <?php endif; ?>>
                  <input type="radio" name="show_copyright" value="0" title="隐藏" <?php if(isset($info['show_copyright']) && $info['show_copyright']==0): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">设置是否在登录页面底部显示版权信息</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">版权信息</label>
                <div class="layui-input-block">
                  <input type="text" name="copyright_text" autocomplete="off" placeholder="请输入版权信息" class="layui-input" value="<?php echo htmlentities((string) (isset($info['copyright_text']) && ($info['copyright_text'] !== '')?$info['copyright_text']:'© 2023 版权所有')); ?>">
                  <div class="layui-form-mid layui-word-aux">登录页面底部显示的版权信息</div>
                </div>
              </div>

              <!-- 字体设置部分 -->
              <div class="section-title" style="font-size: 15px; margin: 20px 0 15px;">
                <i class="fa fa-font"></i> 文字样式设置
              </div>

              <!-- 服务协议字体设置 -->
              <div class="layui-form-item">
                <label class="layui-form-label">协议字体大小</label>
                <div class="layui-input-inline" style="width: 80px;">
                  <input type="number" name="agreement_font_size" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['agreement_font_size']) && ($info['agreement_font_size'] !== '')?$info['agreement_font_size']:'12')); ?>" min="8" max="20">
                </div>
                <div class="layui-form-mid">px</div>
                <div class="layui-form-mid layui-word-aux" style="margin-left: 10px;">服务协议文字大小</div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">协议字体颜色</label>
                <div class="layui-input-block">
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="agreement_font_color" id="agreement_font_color" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['agreement_font_color']) && ($info['agreement_font_color'] !== '')?$info['agreement_font_color']:'#666666')); ?>" placeholder="#666666">
                  </div>
                  <div class="layui-inline" style="height: 38px; padding: 0 5px;">
                    <input type="color" id="agreement_color_preview" value="<?php echo htmlentities((string) (isset($info['agreement_font_color']) && ($info['agreement_font_color'] !== '')?$info['agreement_font_color']:'#666666')); ?>" style="width: 38px; height: 38px; padding: 0; margin: 0; border: 1px solid #e6e6e6; border-radius: 2px;">
                  </div>
                  <div class="layui-inline layui-form-mid layui-word-aux">服务协议文字颜色，如:#666666</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">协议字体粗细</label>
                <div class="layui-input-block">
                  <input type="radio" name="agreement_font_weight" value="normal" title="常规" <?php if(!isset($info['agreement_font_weight']) || $info['agreement_font_weight']=='normal'): ?> checked <?php endif; ?>>
                  <input type="radio" name="agreement_font_weight" value="bold" title="加粗" <?php if(isset($info['agreement_font_weight']) && $info['agreement_font_weight']=='bold'): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">服务协议文字粗细程度</div>
                </div>
              </div>
              
              <!-- 版权信息字体设置 -->
              <div class="layui-form-item">
                <label class="layui-form-label">版权字体大小</label>
                <div class="layui-input-inline" style="width: 80px;">
                  <input type="number" name="copyright_font_size" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['copyright_font_size']) && ($info['copyright_font_size'] !== '')?$info['copyright_font_size']:'12')); ?>" min="8" max="20">
                </div>
                <div class="layui-form-mid">px</div>
                <div class="layui-form-mid layui-word-aux" style="margin-left: 10px;">版权信息文字大小</div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">版权字体颜色</label>
                <div class="layui-input-block">
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="copyright_font_color" id="copyright_font_color" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['copyright_font_color']) && ($info['copyright_font_color'] !== '')?$info['copyright_font_color']:'#999999')); ?>" placeholder="#999999">
                  </div>
                  <div class="layui-inline" style="height: 38px; padding: 0 5px;">
                    <input type="color" id="copyright_color_preview" value="<?php echo htmlentities((string) (isset($info['copyright_font_color']) && ($info['copyright_font_color'] !== '')?$info['copyright_font_color']:'#999999')); ?>" style="width: 38px; height: 38px; padding: 0; margin: 0; border: 1px solid #e6e6e6; border-radius: 2px;">
                  </div>
                  <div class="layui-inline layui-form-mid layui-word-aux">版权信息文字颜色，如:#999999</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">版权字体粗细</label>
                <div class="layui-input-block">
                  <input type="radio" name="copyright_font_weight" value="normal" title="常规" <?php if(!isset($info['copyright_font_weight']) || $info['copyright_font_weight']=='normal'): ?> checked <?php endif; ?>>
                  <input type="radio" name="copyright_font_weight" value="bold" title="加粗" <?php if(isset($info['copyright_font_weight']) && $info['copyright_font_weight']=='bold'): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">版权信息文字粗细程度</div>
                </div>
              </div>
            </div>
            
            <!-- 语言设置 -->
            <div class="form-section" id="language-settings">
              <div class="section-title">
                <i class="fa fa-language"></i> 语言设置
              </div>
              
              <div class="tips">
                <i class="fa fa-info-circle"></i> 在这里您可以配置APP前端的语言切换功能和相关样式。
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">显示语言切换</label>
                <div class="layui-input-block">
                  <input type="radio" name="show_language_switch" value="1" title="显示" <?php if(!isset($info['show_language_switch']) || $info['show_language_switch']==1): ?> checked <?php endif; ?>>
                  <input type="radio" name="show_language_switch" value="0" title="隐藏" <?php if(isset($info['show_language_switch']) && $info['show_language_switch']==0): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">设置是否在登录页面显示语言切换按钮</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">字体大小</label>
                <div class="layui-input-inline" style="width: 80px;">
                  <input type="number" name="language_font_size" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['language_font_size']) && ($info['language_font_size'] !== '')?$info['language_font_size']:'14')); ?>" min="10" max="22">
                </div>
                <div class="layui-form-mid">px</div>
                <div class="layui-form-mid layui-word-aux" style="margin-left: 10px;">语言切换按钮的字体大小</div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">字体颜色</label>
                <div class="layui-input-block">
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="language_font_color" id="language_font_color" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['language_font_color']) && ($info['language_font_color'] !== '')?$info['language_font_color']:'#ffffff')); ?>" placeholder="#ffffff">
                  </div>
                  <div class="layui-inline" style="height: 38px; padding: 0 5px;">
                    <input type="color" id="language_color_preview" value="<?php echo htmlentities((string) (isset($info['language_font_color']) && ($info['language_font_color'] !== '')?$info['language_font_color']:'#ffffff')); ?>" style="width: 38px; height: 38px; padding: 0; margin: 0; border: 1px solid #e6e6e6; border-radius: 2px;">
                  </div>
                  <div class="layui-inline layui-form-mid layui-word-aux">语言切换按钮的字体颜色，如:#ffffff</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">字体粗细</label>
                <div class="layui-input-block">
                  <input type="radio" name="language_font_weight" value="normal" title="常规" <?php if(!isset($info['language_font_weight']) || $info['language_font_weight']=='normal'): ?> checked <?php endif; ?>>
                  <input type="radio" name="language_font_weight" value="bold" title="加粗" <?php if(isset($info['language_font_weight']) && $info['language_font_weight']=='bold'): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">语言切换按钮的字体粗细程度</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">按钮背景色</label>
                <div class="layui-input-block">
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="language_bg_color" id="language_bg_color" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['language_bg_color']) && ($info['language_bg_color'] !== '')?$info['language_bg_color']:'rgba(0,0,0,0.3)')); ?>" placeholder="rgba(0,0,0,0.3)">
                  </div>
                  <div class="layui-inline layui-form-mid layui-word-aux">语言切换按钮的背景颜色，建议使用半透明效果</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">按钮位置</label>
                <div class="layui-input-block">
                  <input type="radio" name="language_position" value="top-right" title="右上角" <?php if(!isset($info['language_position']) || $info['language_position']=='top-right'): ?> checked <?php endif; ?>>
                  <input type="radio" name="language_position" value="top-left" title="左上角" <?php if(isset($info['language_position']) && $info['language_position']=='top-left'): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">设置语言切换按钮的显示位置</div>
                </div>
              </div>
            </div>
            
            <!-- 登录按钮设置 -->
            <div class="form-section" id="login-button-settings">
              <div class="section-title">
                <i class="fa fa-sign-in"></i> 登录按钮设置
              </div>
              
              <div class="tips">
                <i class="fa fa-info-circle"></i> 在这里您可以配置APP前端登录按钮的样式和相关参数。
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">字体大小</label>
                <div class="layui-input-inline" style="width: 80px;">
                  <input type="number" name="login_button_font_size" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['login_button_font_size']) && ($info['login_button_font_size'] !== '')?$info['login_button_font_size']:'16')); ?>" min="12" max="24">
                </div>
                <div class="layui-form-mid">px</div>
                <div class="layui-form-mid layui-word-aux" style="margin-left: 10px;">登录按钮的字体大小</div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">字体颜色</label>
                <div class="layui-input-block">
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="login_button_font_color" id="login_button_font_color" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['login_button_font_color']) && ($info['login_button_font_color'] !== '')?$info['login_button_font_color']:'#ffffff')); ?>" placeholder="#ffffff">
                  </div>
                  <div class="layui-inline" style="height: 38px; padding: 0 5px;">
                    <input type="color" id="login_button_color_preview" value="<?php echo htmlentities((string) (isset($info['login_button_font_color']) && ($info['login_button_font_color'] !== '')?$info['login_button_font_color']:'#ffffff')); ?>" style="width: 38px; height: 38px; padding: 0; margin: 0; border: 1px solid #e6e6e6; border-radius: 2px;">
                  </div>
                  <div class="layui-inline layui-form-mid layui-word-aux">登录按钮的字体颜色，如:#ffffff</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">字体粗细</label>
                <div class="layui-input-block">
                  <input type="radio" name="login_button_font_weight" value="normal" title="常规" <?php if(!isset($info['login_button_font_weight']) || $info['login_button_font_weight']=='normal'): ?> checked <?php endif; ?>>
                  <input type="radio" name="login_button_font_weight" value="bold" title="加粗" <?php if(isset($info['login_button_font_weight']) && $info['login_button_font_weight']=='bold'): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">登录按钮的字体粗细程度</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">按钮背景色</label>
                <div class="layui-input-block">
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="login_button_bg_color" id="login_button_bg_color" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['login_button_bg_color']) && ($info['login_button_bg_color'] !== '')?$info['login_button_bg_color']:'#8257e6')); ?>" placeholder="#8257e6">
                  </div>
                  <div class="layui-inline" style="height: 38px; padding: 0 5px;">
                    <input type="color" id="login_button_bg_color_preview" value="<?php echo htmlentities((string) (isset($info['login_button_bg_color']) && ($info['login_button_bg_color'] !== '')?$info['login_button_bg_color']:'#8257e6')); ?>" style="width: 38px; height: 38px; padding: 0; margin: 0; border: 1px solid #e6e6e6; border-radius: 2px;">
                  </div>
                  <div class="layui-inline layui-form-mid layui-word-aux">登录按钮的背景颜色，如:#8257e6</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">按钮渐变色</label>
                <div class="layui-input-block">
                  <div class="layui-input-inline" style="width: 120px;">
                    <input type="text" name="login_button_gradient_color" id="login_button_gradient_color" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['login_button_gradient_color']) && ($info['login_button_gradient_color'] !== '')?$info['login_button_gradient_color']:'#6c45c4')); ?>" placeholder="#6c45c4">
                  </div>
                  <div class="layui-inline" style="height: 38px; padding: 0 5px;">
                    <input type="color" id="login_button_gradient_color_preview" value="<?php echo htmlentities((string) (isset($info['login_button_gradient_color']) && ($info['login_button_gradient_color'] !== '')?$info['login_button_gradient_color']:'#6c45c4')); ?>" style="width: 38px; height: 38px; padding: 0; margin: 0; border: 1px solid #e6e6e6; border-radius: 2px;">
                  </div>
                  <div class="layui-inline layui-form-mid layui-word-aux">登录按钮的渐变色，设置后将与背景色形成渐变效果</div>
                </div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">按钮圆角</label>
                <div class="layui-input-inline" style="width: 80px;">
                  <input type="number" name="login_button_radius" autocomplete="off" class="layui-input" value="<?php echo htmlentities((string) (isset($info['login_button_radius']) && ($info['login_button_radius'] !== '')?$info['login_button_radius']:'4')); ?>" min="0" max="30">
                </div>
                <div class="layui-form-mid">px</div>
                <div class="layui-form-mid layui-word-aux" style="margin-left: 10px;">登录按钮的圆角大小</div>
              </div>
              
              <div class="layui-form-item">
                <label class="layui-form-label">按钮阴影</label>
                <div class="layui-input-block">
                  <input type="radio" name="login_button_shadow" value="1" title="显示" <?php if(!isset($info['login_button_shadow']) || $info['login_button_shadow']==1): ?> checked <?php endif; ?>>
                  <input type="radio" name="login_button_shadow" value="0" title="隐藏" <?php if(isset($info['login_button_shadow']) && $info['login_button_shadow']==0): ?> checked <?php endif; ?>>
                  <div class="layui-form-mid layui-word-aux">设置是否在登录按钮下方显示阴影效果</div>
                </div>
              </div>
            </div>
            
            <!-- 隐藏的bg_count字段 -->
            <input type="hidden" name="bg_count" value="<?php echo htmlentities((string) (isset($info['bg_count']) && ($info['bg_count'] !== '')?$info['bg_count']:'0')); ?>">
            
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- 保存设置的浮动按钮 -->
  <div class="form-actions" style="position: fixed; bottom: 0; right: 0; width: 100%; z-index: 1000; display: flex; justify-content: flex-end; padding: 15px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); background: white;">
    <button class="submit-btn" lay-submit lay-filter="formSubmit" form="appConfigForm">
      <i class="fa fa-save"></i> 保存设置
    </button>
  </div>
  
  <!-- 状态通知弹窗 -->
  <div class="status-toast" id="statusToast">
    <div class="status-toast-icon">
      <i class="fa fa-check-circle"></i>
    </div>
    <div class="status-toast-content">
      <span class="status-toast-title">操作成功</span>
      <span class="status-toast-message">设置已成功保存</span>
    </div>
    <div class="status-toast-progress">
      <div class="status-toast-progress-bar"></div>
    </div>
  </div>
  
  <script src="/static/jquery/jquery.min.js"></script>
  <script src="/static/layui/layui.js"></script>
  <script>
    layui.use(['form', 'upload', 'layer'], function(){
      var form = layui.form;
      var upload = layui.upload;
      var layer = layui.layer;
      
      // 左侧导航切换
      $('.nav-item').on('click', function(){
        var sectionId = $(this).data('section');
        
        // 激活当前导航项
        $('.nav-item').removeClass('active');
        $(this).addClass('active');
        
        // 显示对应的内容区域
        $('.form-section').removeClass('active');
        $('#' + sectionId).addClass('active');
        
        // 滚动到顶部
        $('.settings-content').scrollTop(0);
      });
      
      // 存储原始表单数据，用于检测变更
      var originalFormData = {
        bg_images: [],
        url: '',
        is_filter: '<?php echo htmlentities((string) (isset($info['is_filter']) && ($info['is_filter'] !== '')?$info['is_filter']:"0")); ?>',
        screen_orientation: '<?php echo htmlentities((string) (isset($info['screen_orientation']) && ($info['screen_orientation'] !== '')?$info['screen_orientation']:"portrait")); ?>',
        show_logo_title: '<?php echo htmlentities((string) (isset($info['show_logo_title']) && ($info['show_logo_title'] !== '')?$info['show_logo_title']:"1")); ?>',
        logo_image: '<?php echo htmlentities((string) (isset($info['logo_image']) && ($info['logo_image'] !== '')?$info['logo_image']:"")); ?>',
        login_title: '<?php echo htmlentities((string) (isset($info['login_title']) && ($info['login_title'] !== '')?$info['login_title']:"")); ?>',
        show_agreement: '<?php echo htmlentities((string) (isset($info['show_agreement']) && ($info['show_agreement'] !== '')?$info['show_agreement']:"1")); ?>',
        agreement_url: '<?php echo htmlentities((string) (isset($info['agreement_url']) && ($info['agreement_url'] !== '')?$info['agreement_url']:"")); ?>',
        agreement_text: '<?php echo htmlentities((string) (isset($info['agreement_text']) && ($info['agreement_text'] !== '')?$info['agreement_text']:"登录即代表同意《服务协议》")); ?>',
        show_copyright: '<?php echo htmlentities((string) (isset($info['show_copyright']) && ($info['show_copyright'] !== '')?$info['show_copyright']:"1")); ?>',
        copyright_text: '<?php echo htmlentities((string) (isset($info['copyright_text']) && ($info['copyright_text'] !== '')?$info['copyright_text']:"© 2023 版权所有")); ?>',
        agreement_font_size: '<?php echo htmlentities((string) (isset($info['agreement_font_size']) && ($info['agreement_font_size'] !== '')?$info['agreement_font_size']:"12")); ?>',
        agreement_font_color: '<?php echo htmlentities((string) (isset($info['agreement_font_color']) && ($info['agreement_font_color'] !== '')?$info['agreement_font_color']:"#666666")); ?>',
        agreement_font_weight: '<?php echo htmlentities((string) (isset($info['agreement_font_weight']) && ($info['agreement_font_weight'] !== '')?$info['agreement_font_weight']:"normal")); ?>',
        copyright_font_size: '<?php echo htmlentities((string) (isset($info['copyright_font_size']) && ($info['copyright_font_size'] !== '')?$info['copyright_font_size']:"12")); ?>',
        copyright_font_color: '<?php echo htmlentities((string) (isset($info['copyright_font_color']) && ($info['copyright_font_color'] !== '')?$info['copyright_font_color']:"#999999")); ?>',
        copyright_font_weight: '<?php echo htmlentities((string) (isset($info['copyright_font_weight']) && ($info['copyright_font_weight'] !== '')?$info['copyright_font_weight']:"normal")); ?>',
        show_language_switch: '<?php echo htmlentities((string) (isset($info['show_language_switch']) && ($info['show_language_switch'] !== '')?$info['show_language_switch']:"1")); ?>',
        language_font_size: '<?php echo htmlentities((string) (isset($info['language_font_size']) && ($info['language_font_size'] !== '')?$info['language_font_size']:"14")); ?>',
        language_font_color: '<?php echo htmlentities((string) (isset($info['language_font_color']) && ($info['language_font_color'] !== '')?$info['language_font_color']:"#ffffff")); ?>',
        language_font_weight: '<?php echo htmlentities((string) (isset($info['language_font_weight']) && ($info['language_font_weight'] !== '')?$info['language_font_weight']:"normal")); ?>',
        language_bg_color: '<?php echo htmlentities((string) (isset($info['language_bg_color']) && ($info['language_bg_color'] !== '')?$info['language_bg_color']:"rgba(0,0,0,0.3)")); ?>',
        language_position: '<?php echo htmlentities((string) (isset($info['language_position']) && ($info['language_position'] !== '')?$info['language_position']:"top-right")); ?>',
        login_button_font_size: '<?php echo htmlentities((string) (isset($info['login_button_font_size']) && ($info['login_button_font_size'] !== '')?$info['login_button_font_size']:"16")); ?>',
        login_button_font_color: '<?php echo htmlentities((string) (isset($info['login_button_font_color']) && ($info['login_button_font_color'] !== '')?$info['login_button_font_color']:"#ffffff")); ?>',
        login_button_font_weight: '<?php echo htmlentities((string) (isset($info['login_button_font_weight']) && ($info['login_button_font_weight'] !== '')?$info['login_button_font_weight']:"normal")); ?>',
        login_button_bg_color: '<?php echo htmlentities((string) (isset($info['login_button_bg_color']) && ($info['login_button_bg_color'] !== '')?$info['login_button_bg_color']:"#8257e6")); ?>',
        login_button_gradient_color: '<?php echo htmlentities((string) (isset($info['login_button_gradient_color']) && ($info['login_button_gradient_color'] !== '')?$info['login_button_gradient_color']:"#6c45c4")); ?>',
        login_button_radius: '<?php echo htmlentities((string) (isset($info['login_button_radius']) && ($info['login_button_radius'] !== '')?$info['login_button_radius']:"4")); ?>',
        login_button_shadow: '<?php echo htmlentities((string) (isset($info['login_button_shadow']) && ($info['login_button_shadow'] !== '')?$info['login_button_shadow']:"1")); ?>',
        
      };
      
      // 初始化表单数据
      $(document).ready(function() {
        // 初始化背景图数据
        $('.image-item').each(function() {
          originalFormData.bg_images.push($(this).data('src'));
        });
        // 初始化URL数据
        originalFormData.url = $('input[name="urls[]"]').val();
        
        // 颜色选择器同步功能
        // 服务协议颜色同步
        $('#agreement_color_preview').on('input', function() {
          $('#agreement_font_color').val($(this).val());
        });
        
        $('#agreement_font_color').on('input', function() {
          $('#agreement_color_preview').val($(this).val());
        });
        
        // 版权信息颜色同步
        $('#copyright_color_preview').on('input', function() {
          $('#copyright_font_color').val($(this).val());
        });
        
        $('#copyright_font_color').on('input', function() {
          $('#copyright_color_preview').val($(this).val());
        });
        
        // 语言切换颜色同步
        $('#language_color_preview').on('input', function() {
          $('#language_font_color').val($(this).val());
        });
        
        $('#language_font_color').on('input', function() {
          $('#language_color_preview').val($(this).val());
        });
        
        // 登录按钮字体颜色同步
        $('#login_button_color_preview').on('input', function() {
          $('#login_button_font_color').val($(this).val());
        });
        
        $('#login_button_font_color').on('input', function() {
          $('#login_button_color_preview').val($(this).val());
        });
        
        // 登录按钮背景颜色同步
        $('#login_button_bg_color_preview').on('input', function() {
          $('#login_button_bg_color').val($(this).val());
        });
        
        $('#login_button_bg_color').on('input', function() {
          $('#login_button_bg_color_preview').val($(this).val());
        });
        
        // 登录按钮渐变颜色同步
        $('#login_button_gradient_color_preview').on('input', function() {
          $('#login_button_gradient_color').val($(this).val());
        });
        
        $('#login_button_gradient_color').on('input', function() {
          $('#login_button_gradient_color_preview').val($(this).val());
        });

        // 更新背景图数量
        updateBgCount();
      });
      
      // 更新背景图数量
      function updateBgCount() {
        var count = $('.image-item').length;
        $('input[name="bg_count"]').val(count);
      }
      
      // 显示状态通知弹窗
      function showStatusToast(options) {
        var $toast = $('#statusToast');
        var icon = 'fa-check-circle';
        var background = 'linear-gradient(145deg, #4CAF50, #2E7D32)';
        
        // 设置图标和背景色
        if (options.type === 'error') {
          icon = 'fa-times-circle';
          background = 'linear-gradient(145deg, #f44336, #d32f2f)';
          $toast.addClass('error').removeClass('warning info');
        } else if (options.type === 'warning') {
          icon = 'fa-exclamation-triangle';
          background = 'linear-gradient(145deg, #ff9800, #ed6c02)';
          $toast.addClass('warning').removeClass('error info');
        } else if (options.type === 'info') {
          icon = 'fa-info-circle';
          background = 'linear-gradient(145deg, #3b82f6, #2563eb)';
          $toast.addClass('info').removeClass('error warning');
        } else {
          $toast.removeClass('error warning info');
        }
        
        // 设置图标
        $toast.find('.status-toast-icon i').attr('class', 'fa ' + icon);
        
        // 设置标题和消息
        $toast.find('.status-toast-title').text(options.title || '操作成功');
        $toast.find('.status-toast-message').text(options.message || '');
        
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
      
      // 图片上传实例
      var uploadInst = upload.render({
        elem: '#addBgImage',
        url: '<?php echo url("admin/appv1/uploadImage"); ?>',
        accept: 'images',
        acceptMime: 'image/jpg,image/jpeg,image/png',
        size: 2048, // 限制文件大小，单位 KB
        before: function(obj){
          layer.load(1, {shade: [0.2, '#000']});
        },
        done: function(res){
          layer.closeAll('loading');
          if(res.code == 0){
            // 上传成功，添加图片到列表
            addImageToList(res.data.src);
            showStatusToast({
              type: 'success',
              title: '上传成功',
              message: '背景图片已自动保存到系统中'
            });
            // 更新背景图数量
            updateBgCount();
          } else {
            showStatusToast({
              type: 'error',
              title: '上传失败',
              message: res.msg || '图片上传失败，请重试'
            });
          }
        },
        error: function(){
          layer.closeAll('loading');
          showStatusToast({
            type: 'error',
            title: '网络错误',
            message: '请检查网络连接并重试'
          });
        }
      });
      
      // 上传Logo图片
      upload.render({
        elem: '#upload-logo',
        url: '<?php echo url("admin/common/upload"); ?>',
        accept: 'images',
        acceptMime: 'image/jpeg,image/png,image/jpg,image/gif',
        exts: 'jpg|png|gif|jpeg',
        size: 2048, // 2MB
        data: {
          module: 'admin',
          use: 'app_logo'
        },
        done: function(res){
          if(res.code == 2){ // Common控制器上传成功返回code=2
            $('#logo-preview').attr('src', res.src);
            $('#logo-url').val(res.src);
            
            showStatusToast({
              type: 'success',
              title: '上传成功',
              message: 'Logo图片已成功上传'
            });
            
            // 更新原始数据中的logo路径
            originalFormData.logo_image = res.src;
          } else {
            showStatusToast({
              type: 'error',
              title: '上传失败',
              message: res.msg || '图片上传失败，请重试'
            });
          }
        },
        error: function(){
          showStatusToast({
            type: 'error',
            title: '上传失败',
            message: '网络错误或服务器异常，请重试'
          });
        }
      });
      
      // 添加图片到列表
      function addImageToList(src) {
        var html = `
          <div class="image-item" data-src="${src}">
            <img src="${src}" alt="背景图">
            <div class="image-actions">
              <div class="image-action delete-image" title="删除"><i class="fa fa-trash"></i></div>
            </div>
            <input type="hidden" name="bg_images[]" value="${src}">
          </div>
        `;
        
        $(html).insertBefore('#addBgImage');
      }
      
      // 删除图片
      $(document).on('click', '.delete-image', function(){
        var item = $(this).closest('.image-item');
        var src = item.data('src');
        
        // 使用现代化弹窗样式
        layer.open({
          type: 1,
          title: false,
          closeBtn: 0,
          shadeClose: true,
          skin: 'modern-dialog',
          area: ['400px', 'auto'],
          content: `
            <div class="permission-dialog">
              <div class="permission-dialog-title">
                <i class="fa fa-trash" style="color: #ef4444;"></i> 删除确认
              </div>
              <div style="padding: 30px 20px; text-align: center;">
                <div style="width: 70px; height: 70px; margin: 0 auto 20px; background-color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                  <i class="fa fa-trash" style="font-size: 36px; color: white;"></i>
                </div>
                <div style="font-size: 16px; margin-bottom: 10px; color: #374151; font-weight: 500;">
                  确定要删除这张背景图片吗？
                </div>
                <div style="font-size: 14px; color: #6b7280; line-height: 1.6;">
                  删除后无法恢复，请确认您的操作。
                </div>
              </div>
              <div class="permission-actions">
                <button type="button" class="layui-btn layui-btn-primary" onclick="layer.closeAll()">
                  <i class="fa fa-times"></i> 取消
                </button>
                <button type="button" class="layui-btn layui-btn-danger" id="confirmDeleteBtn">
                  <i class="fa fa-trash"></i> 确认删除
                </button>
              </div>
            </div>
          `,
          success: function(layero){
            // 确认删除按钮点击事件
            $('#confirmDeleteBtn').on('click', function(){
              // 发送删除请求
              $.post('<?php echo url("admin/appv1/deleteImage"); ?>', {src: src}, function(res){
                if(res.code == 0){
                  item.remove();
                  showStatusToast({
                    type: 'success',
                    title: '删除成功',
                    message: '背景图片已成功删除'
                  });
                  // 更新背景图数量
                  updateBgCount();
                  layer.closeAll();
                } else {
                  showStatusToast({
                    type: 'error',
                    title: '删除失败',
                    message: res.msg || '操作失败，请重试'
                  });
                  layer.closeAll();
                }
              });
            });
          }
        });
      });
      
      // 检查表单是否有变化
      function checkFormChanges() {
        // 检查背景图是否变化
        var currentBgImages = [];
        $('.image-item').each(function() {
          currentBgImages.push($(this).data('src'));
        });
        
        // 检查URL是否变化
        var currentUrl = $('input[name="urls[]"]').val();
        
        // 检查安全过滤和屏幕方向是否变化
        var currentIsFilter = $('input[name="is_filter"]:checked').val();
        var currentScreenOrientation = $('input[name="screen_orientation"]:checked').val();
        
        // 检查Logo和标题设置是否变化
        var currentShowLogoTitle = $('input[name="show_logo_title"]:checked').val();
        var currentLogoImage = $('#logo-url').val();
        var currentLoginTitle = $('input[name="login_title"]').val();
        
        // 检查服务协议和版权设置是否变化
        var currentShowAgreement = $('input[name="show_agreement"]:checked').val();
        var currentAgreementUrl = $('input[name="agreement_url"]').val();
        var currentAgreementText = $('input[name="agreement_text"]').val();
        var currentShowCopyright = $('input[name="show_copyright"]:checked').val();
        var currentCopyrightText = $('input[name="copyright_text"]').val();
        
        // 检查服务协议字体设置是否变化
        var currentAgreementFontSize = $('input[name="agreement_font_size"]').val();
        var currentAgreementFontColor = $('input[name="agreement_font_color"]').val();
        var currentAgreementFontWeight = $('input[name="agreement_font_weight"]:checked').val();
        
        // 检查版权信息字体设置是否变化
        var currentCopyrightFontSize = $('input[name="copyright_font_size"]').val();
        var currentCopyrightFontColor = $('input[name="copyright_font_color"]').val();
        var currentCopyrightFontWeight = $('input[name="copyright_font_weight"]:checked').val();
        
        // 检查语言切换设置是否变化
        var currentShowLanguageSwitch = $('input[name="show_language_switch"]:checked').val();
        var currentLanguageFontSize = $('input[name="language_font_size"]').val();
        var currentLanguageFontColor = $('input[name="language_font_color"]').val();
        var currentLanguageFontWeight = $('input[name="language_font_weight"]:checked').val();
        var currentLanguageBgColor = $('input[name="language_bg_color"]').val();
        var currentLanguagePosition = $('input[name="language_position"]:checked').val();
        
        // 检查登录按钮设置是否变化
        var currentLoginButtonFontSize = $('input[name="login_button_font_size"]').val();
        var currentLoginButtonFontColor = $('input[name="login_button_font_color"]').val();
        var currentLoginButtonFontWeight = $('input[name="login_button_font_weight"]:checked').val();
        var currentLoginButtonBgColor = $('input[name="login_button_bg_color"]').val();
        var currentLoginButtonGradientColor = $('input[name="login_button_gradient_color"]').val();
        var currentLoginButtonRadius = $('input[name="login_button_radius"]').val();
        var currentLoginButtonShadow = $('input[name="login_button_shadow"]:checked').val();
        
        
        // 图片数量不同
        if (currentBgImages.length !== originalFormData.bg_images.length) {
          return true;
        }
        
        // 检查图片是否变化
        var imagesChanged = false;
        for (var i = 0; i < currentBgImages.length; i++) {
          if (!originalFormData.bg_images.includes(currentBgImages[i])) {
            imagesChanged = true;
            break;
          }
        }
        
        // 检查URL是否变化
        var urlChanged = (currentUrl !== originalFormData.url);
        
        // 检查安全过滤和屏幕方向是否变化
        var isFilterChanged = (currentIsFilter !== originalFormData.is_filter);
        var screenOrientationChanged = (currentScreenOrientation !== originalFormData.screen_orientation);
        
        // 检查Logo和标题设置是否变化
        var showLogoTitleChanged = (currentShowLogoTitle !== originalFormData.show_logo_title);
        var logoImageChanged = (currentLogoImage !== originalFormData.logo_image);
        var loginTitleChanged = (currentLoginTitle !== originalFormData.login_title);
        
        // 检查服务协议和版权设置是否变化
        var showAgreementChanged = (currentShowAgreement !== originalFormData.show_agreement);
        var agreementUrlChanged = (currentAgreementUrl !== originalFormData.agreement_url);
        var agreementTextChanged = (currentAgreementText !== originalFormData.agreement_text);
        var showCopyrightChanged = (currentShowCopyright !== originalFormData.show_copyright);
        var copyrightTextChanged = (currentCopyrightText !== originalFormData.copyright_text);
        
        // 检查服务协议字体设置是否变化
        var agreementFontSizeChanged = (currentAgreementFontSize !== originalFormData.agreement_font_size);
        var agreementFontColorChanged = (currentAgreementFontColor !== originalFormData.agreement_font_color);
        var agreementFontWeightChanged = (currentAgreementFontWeight !== originalFormData.agreement_font_weight);
        
        // 检查版权信息字体设置是否变化
        var copyrightFontSizeChanged = (currentCopyrightFontSize !== originalFormData.copyright_font_size);
        var copyrightFontColorChanged = (currentCopyrightFontColor !== originalFormData.copyright_font_color);
        var copyrightFontWeightChanged = (currentCopyrightFontWeight !== originalFormData.copyright_font_weight);
        
        // 检查语言切换设置是否变化
        var showLanguageSwitchChanged = (currentShowLanguageSwitch !== originalFormData.show_language_switch);
        var languageFontSizeChanged = (currentLanguageFontSize !== originalFormData.language_font_size);
        var languageFontColorChanged = (currentLanguageFontColor !== originalFormData.language_font_color);
        var languageFontWeightChanged = (currentLanguageFontWeight !== originalFormData.language_font_weight);
        var languageBgColorChanged = (currentLanguageBgColor !== originalFormData.language_bg_color);
        var languagePositionChanged = (currentLanguagePosition !== originalFormData.language_position);
        
        // 检查登录按钮设置是否变化
        var loginButtonFontSizeChanged = (currentLoginButtonFontSize !== originalFormData.login_button_font_size);
        var loginButtonFontColorChanged = (currentLoginButtonFontColor !== originalFormData.login_button_font_color);
        var loginButtonFontWeightChanged = (currentLoginButtonFontWeight !== originalFormData.login_button_font_weight);
        var loginButtonBgColorChanged = (currentLoginButtonBgColor !== originalFormData.login_button_bg_color);
        var loginButtonGradientColorChanged = (currentLoginButtonGradientColor !== originalFormData.login_button_gradient_color);
        var loginButtonRadiusChanged = (currentLoginButtonRadius !== originalFormData.login_button_radius);
        var loginButtonShadowChanged = (currentLoginButtonShadow !== originalFormData.login_button_shadow);
        
        
        return imagesChanged || urlChanged || isFilterChanged || screenOrientationChanged || 
              showLogoTitleChanged || logoImageChanged || loginTitleChanged || 
              showAgreementChanged || agreementUrlChanged || agreementTextChanged || showCopyrightChanged || copyrightTextChanged ||
              agreementFontSizeChanged || agreementFontColorChanged || agreementFontWeightChanged ||
              copyrightFontSizeChanged || copyrightFontColorChanged || copyrightFontWeightChanged ||
              showLanguageSwitchChanged || languageFontSizeChanged || languageFontColorChanged || 
              languageFontWeightChanged || languageBgColorChanged || languagePositionChanged ||
              loginButtonFontSizeChanged || loginButtonFontColorChanged ||
              loginButtonFontWeightChanged || loginButtonBgColorChanged || loginButtonGradientColorChanged ||
              loginButtonRadiusChanged || loginButtonShadowChanged;
      }
      
      // 表单提交
      form.on('submit(formSubmit)', function(data){
        // 验证背景图数量
        var bgImages = $('.image-item').length;
        
        // 确保至少有一张背景图
        if(bgImages < 1){
          showStatusToast({
            type: 'error',
            title: '验证失败',
            message: '至少需要上传1张背景图片'
          });
          return false;
        }
        
        // 检查表单是否有变化
        if (!checkFormChanges()) {
          // 使用status-toast显示提示
          showStatusToast({
            type: 'info',
            title: '设置未发生变化',
            message: '当前设置与原始设置相同，无需保存'
          });
          return false;
        }
        
        // 将背景图数量设置为实际上传的图片数量
        var bgCountInput = $('input[name="bg_count"]');
        if(bgCountInput.length > 0) {
          bgCountInput.val(bgImages);
        } else {
          $('<input>').attr({
            type: 'hidden',
            name: 'bg_count',
            value: bgImages
          }).appendTo('.layui-form');
        }
        
        // 提交表单并显示成功提示
        var loadIndex = layer.load(1, {shade: [0.2, '#000']});
        $.ajax({
          url: $('.layui-form').attr('action'),
          type: 'POST',
          data: $('.layui-form').serialize(),
          dataType: 'json',
          success: function(res){
            layer.close(loadIndex);
            if(res.code == 0){
              showStatusToast({
                type: 'success',
                title: '保存成功',
                message: 'APP前端设置已成功保存'
              });
              
              // 更新原始数据，以便后续检测变更
              originalFormData.bg_images = [];
              $('.image-item').each(function() {
                originalFormData.bg_images.push($(this).data('src'));
              });
              originalFormData.url = $('input[name="urls[]"]').val();
              originalFormData.is_filter = $('input[name="is_filter"]:checked').val();
              originalFormData.screen_orientation = $('input[name="screen_orientation"]:checked').val();
              
              // 更新Logo和标题设置
              originalFormData.show_logo_title = $('input[name="show_logo_title"]:checked').val();
              originalFormData.logo_image = $('#logo-url').val();
              originalFormData.login_title = $('input[name="login_title"]').val();
              
              // 更新服务协议和版权设置
              originalFormData.show_agreement = $('input[name="show_agreement"]:checked').val();
              originalFormData.agreement_url = $('input[name="agreement_url"]').val();
              originalFormData.agreement_text = $('input[name="agreement_text"]').val();
              originalFormData.show_copyright = $('input[name="show_copyright"]:checked').val();
              originalFormData.copyright_text = $('input[name="copyright_text"]').val();
              
              // 更新服务协议和版权字体设置
              originalFormData.agreement_font_size = $('input[name="agreement_font_size"]').val();
              originalFormData.agreement_font_color = $('input[name="agreement_font_color"]').val();
              originalFormData.agreement_font_weight = $('input[name="agreement_font_weight"]:checked').val();
              originalFormData.copyright_font_size = $('input[name="copyright_font_size"]').val();
              originalFormData.copyright_font_color = $('input[name="copyright_font_color"]').val();
              originalFormData.copyright_font_weight = $('input[name="copyright_font_weight"]:checked').val();
              
              // 更新语言切换设置
              originalFormData.show_language_switch = $('input[name="show_language_switch"]:checked').val();
              originalFormData.language_font_size = $('input[name="language_font_size"]').val();
              originalFormData.language_font_color = $('input[name="language_font_color"]').val();
              originalFormData.language_font_weight = $('input[name="language_font_weight"]:checked').val();
              originalFormData.language_bg_color = $('input[name="language_bg_color"]').val();
              originalFormData.language_position = $('input[name="language_position"]:checked').val();
              
              // 更新登录按钮设置
              originalFormData.login_button_font_size = $('input[name="login_button_font_size"]').val();
              originalFormData.login_button_font_color = $('input[name="login_button_font_color"]').val();
              originalFormData.login_button_font_weight = $('input[name="login_button_font_weight"]:checked').val();
              originalFormData.login_button_bg_color = $('input[name="login_button_bg_color"]').val();
              originalFormData.login_button_gradient_color = $('input[name="login_button_gradient_color"]').val();
              originalFormData.login_button_radius = $('input[name="login_button_radius"]').val();
              originalFormData.login_button_shadow = $('input[name="login_button_shadow"]:checked').val();
              
            } else {
              showStatusToast({
                type: 'error',
                title: '保存失败',
                message: res.msg || '设置保存失败，请重试'
              });
            }
          },
          error: function(){
            layer.close(loadIndex);
            showStatusToast({
              type: 'error',
              title: '网络错误',
              message: '网络连接异常，请检查网络并重试'
            });
          }
        });
        
        return false; // 阻止表单默认提交
      });
    });
  </script>
</body>
</html>