

import config from '@/config/server.config.js'

// 后台配置管理类
class ConfigManager {
  constructor() {
    // 检查配置对象和默认值是否存在
    const defaultOrientation = (config && config.defaults && config.defaults.screen_orientation) ? 
      config.defaults.screen_orientation : 'portrait';
      
    this.config = {
      background_images: [], // 背景图列表
      redirect_urls: [],     // 跳转链接列表
      screen_orientation: defaultOrientation, // 默认屏幕方向
      show_logo_title: 1,    // 是否显示LOGO和标题
      logo_image: '',        // LOGO图片URL
      login_title: '',       // 登录标题
      show_agreement: 1,     // 是否显示服务协议
      agreement_text: '登录即代表同意《服务协议》',    // 协议文本
      agreement_url: '',     // 协议链接
      show_copyright: 1,     // 是否显示版权信息
      copyright_text: '© 2025 版权所有',    // 版权信息
      agreement_font_size: '12',     // 协议字体大小
      agreement_font_color: '#666666', // 协议字体颜色
      agreement_font_weight: 'normal', // 协议字体粗细
      copyright_font_size: '12',     // 版权字体大小
      copyright_font_color: '#999999', // 版权字体颜色
      copyright_font_weight: 'normal',  // 版权字体粗细
      // 新增语言切换相关配置
      show_language_switch: 1,       // 是否显示语言切换按钮
      language_font_size: '14',      // 语言切换按钮字体大小
      language_font_color: '#ffffff', // 语言切换按钮字体颜色
      language_font_weight: 'normal', // 语言切换按钮字体粗细
      language_bg_color: 'rgba(0,0,0,0.3)', // 语言切换按钮背景色
      language_position: 'top-right',  // 语言切换按钮位置
      // 新增登录按钮样式配置
      login_button_font_size: '16',    // 登录按钮字体大小
      login_button_font_color: '#ffffff', // 登录按钮字体颜色
      login_button_font_weight: 'normal', // 登录按钮字体粗细
      login_button_bg_color: '#8257e6', // 登录按钮背景色
      login_button_gradient_color: '#6c45c4', // 登录按钮渐变色
      login_button_radius: '4',        // 登录按钮圆角大小
      
    }
    this.loaded = false
    this.loading = false
    this.listeners = []
    
    console.log('ConfigManager初始化完成，默认配置:', JSON.stringify(this.config))
  }
  
  // 获取后台配置
  async loadConfig() {
    if (this.loading) {
      console.log('配置加载中，跳过重复请求')
      return this.config
    }
    
    this.loading = true
    console.log('开始加载配置...')
    
    try {
      // 先检查网络连接
      await this.checkNetworkConnection()
      console.log('网络连接检查通过')
      
      // 确保配置对象和API配置存在
      if (!config || !config.api || !config.api.base_url) {
        console.error('服务器配置缺失，使用默认配置')
        return this.config
      }
      
      // 拼接API请求URL，确保各部分都存在
      const baseUrl = config.api.base_url || 'http://nb.xcttkc.cyou/';
      const apiPath = config.api.api_path || '/api';
      const configPath = config.api.config || '/config/getAppConfig';
      
      const apiUrl = `${baseUrl}${apiPath}${configPath}`;
      console.log('请求配置URL:', apiUrl)
      
      // 获取配置
      const response = await new Promise((resolve, reject) => {
        uni.request({
          url: apiUrl,
          method: 'GET',
          timeout: 5000, // 添加超时设置
          success: (res) => {
            if (res.statusCode === 200 && res.data && res.data.code === 0) {
              console.log('配置请求成功:', JSON.stringify(res.data))
              resolve(res.data)
            } else {
              console.warn('获取配置失败:', res.statusCode, res.data)
              // 不立即reject，返回空结果
              resolve({data: {}})
            }
          },
          fail: (err) => {
            console.error('请求配置失败:', err)
            // 不立即reject，返回空结果
            resolve({data: {}})
          }
        })
      })
      
      // 安全地更新配置，确保response和data存在
      if (response && response.data) {
        const apiData = response.data;
        console.log('收到API数据:', JSON.stringify(apiData))
        
        // 处理app_ui_settings，将它的数据格式化并合并到apiData中
        if (apiData.app_ui_settings) {
          console.log('处理app_ui_settings数据:', JSON.stringify(apiData.app_ui_settings));
          
          // 如果是字符串，尝试解析成对象
          let uiSettings = apiData.app_ui_settings;
          if (typeof uiSettings === 'string') {
            try {
              uiSettings = JSON.parse(uiSettings);
              console.log('解析app_ui_settings字符串成功');
            } catch (e) {
              console.error('解析app_ui_settings字符串失败:', e.message);
              uiSettings = {};
            }
          }
          
          // 如果是数组，转换为对象
          if (Array.isArray(uiSettings)) {
            const settingsObj = {};
            uiSettings.forEach(item => {
              if (item && item.key && item.value !== undefined) {
                settingsObj[item.key] = item.value;
              }
            });
            uiSettings = settingsObj;
            console.log('转换app_ui_settings数组为对象:', JSON.stringify(uiSettings));
          }
          
          // 合并设置到apiData
          if (typeof uiSettings === 'object' && uiSettings !== null) {
            // 服务协议字体设置
            if (uiSettings.agreement_font_size) {
              apiData.agreement_font_size = uiSettings.agreement_font_size;
            }
            if (uiSettings.agreement_font_color) {
              apiData.agreement_font_color = uiSettings.agreement_font_color;
            }
            if (uiSettings.agreement_font_weight) {
              apiData.agreement_font_weight = uiSettings.agreement_font_weight;
            }
            
            // 版权信息字体设置
            if (uiSettings.copyright_font_size) {
              apiData.copyright_font_size = uiSettings.copyright_font_size;
            }
            if (uiSettings.copyright_font_color) {
              apiData.copyright_font_color = uiSettings.copyright_font_color;
            }
            if (uiSettings.copyright_font_weight) {
              apiData.copyright_font_weight = uiSettings.copyright_font_weight;
            }
            
            console.log('合并后的apiData:', JSON.stringify(apiData));
          }
        }
        
        // 更新所有相关配置
        // 背景图片
        if (Array.isArray(apiData.background_images)) {
          this.config.background_images = apiData.background_images;
          console.log('更新背景图片数组，数量:', this.config.background_images.length)
        }
        
        // 跳转链接
        if (Array.isArray(apiData.redirect_urls)) {
          this.config.redirect_urls = apiData.redirect_urls;
          console.log('更新跳转链接数组，数量:', this.config.redirect_urls.length)
        }
        
        // 屏幕方向
        const defaultOrientation = (config && config.defaults && config.defaults.screen_orientation) ? 
          config.defaults.screen_orientation : 'portrait';
        
        if (apiData.screen_orientation) {
          this.config.screen_orientation = apiData.screen_orientation;
          console.log('设置屏幕方向:', this.config.screen_orientation)
        } else {
          this.config.screen_orientation = defaultOrientation;
          console.log('使用默认屏幕方向:', this.config.screen_orientation)
        }
        
        // UI显示配置
        // LOGO和标题
        if ('show_logo_title' in apiData) {
          // 确保转换为整数，如果转换失败则默认为0
          this.config.show_logo_title = parseInt(apiData.show_logo_title) || 0;
          console.log('设置显示LOGO标志:', this.config.show_logo_title, '原始值:', apiData.show_logo_title);
        }
        
        if (apiData.logo_image) {
          this.config.logo_image = apiData.logo_image;
          console.log('设置LOGO图片URL:', this.config.logo_image)
        }
        
        if (apiData.login_title) {
          this.config.login_title = apiData.login_title;
          console.log('设置登录标题:', this.config.login_title)
        }
        
        // 服务协议
        if ('show_agreement' in apiData) {
          // 确保转换为整数，如果转换失败则默认为0
          this.config.show_agreement = parseInt(apiData.show_agreement) || 0;
          console.log('设置显示协议标志:', this.config.show_agreement, '原始值:', apiData.show_agreement);
        }
        
        if (apiData.agreement_text) {
          this.config.agreement_text = apiData.agreement_text;
          console.log('设置协议文本:', this.config.agreement_text)
        }
        
        if (apiData.agreement_url) {
          this.config.agreement_url = apiData.agreement_url;
          console.log('设置协议链接:', this.config.agreement_url)
        }
        
        // 版权信息
        if ('show_copyright' in apiData) {
          // 确保转换为整数，如果转换失败则默认为0
          this.config.show_copyright = parseInt(apiData.show_copyright) || 0;
          console.log('设置显示版权标志:', this.config.show_copyright, '原始值:', apiData.show_copyright);
        } else {
          // 如果后台没有传 show_copyright，但传了 copyright_text，自动设为显示
          if (apiData.copyright_text) {
            this.config.show_copyright = 1;
            console.log('版权文本存在，自动设置显示版权标志: 1');
          }
        }
        
        if (apiData.copyright_text) {
          this.config.copyright_text = apiData.copyright_text;
          console.log('设置版权文本:', this.config.copyright_text)
        }
        
        // 协议字体样式配置
        if (apiData.agreement_font_size) {
          this.config.agreement_font_size = apiData.agreement_font_size;
          console.log('设置协议字体大小:', this.config.agreement_font_size)
        }
        
        if (apiData.agreement_font_color) {
          this.config.agreement_font_color = apiData.agreement_font_color;
          console.log('设置协议字体颜色:', this.config.agreement_font_color)
        } else {
          // 尝试从app_ui_settings中获取颜色设置，适配后端接口
          if (apiData.app_ui_settings && typeof apiData.app_ui_settings === 'object') {
            if (apiData.app_ui_settings.agreement_font_color) {
              this.config.agreement_font_color = apiData.app_ui_settings.agreement_font_color;
              console.log('从app_ui_settings设置协议字体颜色:', this.config.agreement_font_color);
            }
          }
        }
        
        if (apiData.agreement_font_weight) {
          this.config.agreement_font_weight = apiData.agreement_font_weight;
          console.log('设置协议字体粗细:', this.config.agreement_font_weight)
        } else if (apiData.app_ui_settings && apiData.app_ui_settings.agreement_font_weight) {
          this.config.agreement_font_weight = apiData.app_ui_settings.agreement_font_weight === 'bold' ? 'bold' : 'normal';
          console.log('从app_ui_settings设置协议字体粗细:', this.config.agreement_font_weight);
        }
        
        // 版权字体样式配置
        if (apiData.copyright_font_size) {
          this.config.copyright_font_size = apiData.copyright_font_size;
          console.log('设置版权字体大小:', this.config.copyright_font_size)
        }
        
        if (apiData.copyright_font_color) {
          this.config.copyright_font_color = apiData.copyright_font_color;
          console.log('设置版权字体颜色:', this.config.copyright_font_color)
        } else {
          // 尝试从app_ui_settings中获取颜色设置
          if (apiData.app_ui_settings && typeof apiData.app_ui_settings === 'object') {
            if (apiData.app_ui_settings.copyright_font_color) {
              this.config.copyright_font_color = apiData.app_ui_settings.copyright_font_color;
              console.log('从app_ui_settings设置版权字体颜色:', this.config.copyright_font_color);
            }
          }
        }
        
        if (apiData.copyright_font_weight) {
          this.config.copyright_font_weight = apiData.copyright_font_weight;
          console.log('设置版权字体粗细:', this.config.copyright_font_weight)
        } else if (apiData.app_ui_settings && apiData.app_ui_settings.copyright_font_weight) {
          this.config.copyright_font_weight = apiData.app_ui_settings.copyright_font_weight === 'bold' ? 'bold' : 'normal';
          console.log('从app_ui_settings设置版权字体粗细:', this.config.copyright_font_weight);
        }
        
        // 语言切换设置
        if ('show_language_switch' in apiData) {
          // 确保转换为数字类型，并适当处理各种情况
          let showLanguageSwitch;
          
          if (typeof apiData.show_language_switch === 'string') {
            // 尝试转换字符串为数字
            showLanguageSwitch = parseInt(apiData.show_language_switch);
            if (isNaN(showLanguageSwitch)) {
              // 如果无法转换为数字，尝试处理布尔字符串
              if (apiData.show_language_switch.toLowerCase() === 'true') {
                showLanguageSwitch = 1;
              } else if (apiData.show_language_switch.toLowerCase() === 'false') {
                showLanguageSwitch = 0;
              } else {
                // 默认值
                showLanguageSwitch = 1;
              }
            }
          } else if (typeof apiData.show_language_switch === 'boolean') {
            // 处理布尔值
            showLanguageSwitch = apiData.show_language_switch ? 1 : 0;
          } else if (typeof apiData.show_language_switch === 'number') {
            // 保持数字类型不变
            showLanguageSwitch = apiData.show_language_switch;
          } else {
            // 默认值为1（显示）
            showLanguageSwitch = 1;
          }
          
          // 确保最终值是数字1或0
          this.config.show_language_switch = (showLanguageSwitch === 0) ? 0 : 1;
          console.log('设置显示语言切换标志:', this.config.show_language_switch, '原始值:', apiData.show_language_switch, '类型:', typeof apiData.show_language_switch);
        }
        
        if (apiData.language_font_size) {
          this.config.language_font_size = apiData.language_font_size;
          console.log('设置语言切换字体大小:', this.config.language_font_size)
        }
        
        if (apiData.language_font_color) {
          this.config.language_font_color = apiData.language_font_color;
          console.log('设置语言切换字体颜色:', this.config.language_font_color)
        }
        
        if (apiData.language_font_weight) {
          this.config.language_font_weight = apiData.language_font_weight;
          console.log('设置语言切换字体粗细:', this.config.language_font_weight)
        } else if (apiData.language_font_weight === 'bold') {
          // 特别处理bold值
          this.config.language_font_weight = 'bold';
          console.log('设置语言切换字体粗细为bold');
        }
        
        if (apiData.language_bg_color) {
          this.config.language_bg_color = apiData.language_bg_color;
          console.log('设置语言切换背景色:', this.config.language_bg_color)
        }
        
        if (apiData.language_position) {
          this.config.language_position = apiData.language_position;
          console.log('设置语言切换位置:', this.config.language_position);
          
          // 特别标记位置变更，用于调试
          console.log('===语言切换位置已更新===', this.config.language_position);
        }
        
        // 按钮样式配置 - 特别重点处理，确保正确传递
        console.log('======== 按钮样式配置处理 ========');
        
        // 登录按钮字体大小
        if (apiData.login_button_font_size) {
          this.config.login_button_font_size = apiData.login_button_font_size;
          console.log('设置登录按钮字体大小:', this.config.login_button_font_size, '(来源: API数据)');
        } else {
          this.config.login_button_font_size = '16';
          console.log('使用默认登录按钮字体大小:', this.config.login_button_font_size, '(来源: 默认值)');
        }
        
        // 登录按钮字体颜色
        if (apiData.login_button_font_color) {
          const colorValue = this.validateColorValue(apiData.login_button_font_color);
          this.config.login_button_font_color = colorValue;
          console.log('设置登录按钮字体颜色:', this.config.login_button_font_color, '(来源: API数据)');
        } else {
          this.config.login_button_font_color = '#ffffff';
          console.log('使用默认登录按钮字体颜色:', this.config.login_button_font_color, '(来源: 默认值)');
        }
        
        // 登录按钮字体粗细
        if (apiData.login_button_font_weight) {
          this.config.login_button_font_weight = apiData.login_button_font_weight;
          console.log('设置登录按钮字体粗细:', this.config.login_button_font_weight, '(来源: API数据)');
        } else {
          this.config.login_button_font_weight = 'normal';
          console.log('使用默认登录按钮字体粗细:', this.config.login_button_font_weight, '(来源: 默认值)');
        }
        
        // 登录按钮背景色
        if (apiData.login_button_bg_color) {
          const colorValue = this.validateColorValue(apiData.login_button_bg_color);
          this.config.login_button_bg_color = colorValue;
          console.log('设置登录按钮背景色:', this.config.login_button_bg_color, '(来源: API数据)');
        } else {
          this.config.login_button_bg_color = '#8257e6';
          console.log('使用默认登录按钮背景色:', this.config.login_button_bg_color, '(来源: 默认值)');
        }
        
        // 登录按钮渐变色
        if (apiData.login_button_gradient_color) {
          const colorValue = this.validateColorValue(apiData.login_button_gradient_color);
          this.config.login_button_gradient_color = colorValue;
          console.log('设置登录按钮渐变色:', this.config.login_button_gradient_color, '(来源: API数据)');
        } else {
          this.config.login_button_gradient_color = '#6c45c4';
          console.log('使用默认登录按钮渐变色:', this.config.login_button_gradient_color, '(来源: 默认值)');
        }
        
        // 登录按钮圆角
        if (apiData.login_button_radius) {
          this.config.login_button_radius = apiData.login_button_radius;
          console.log('设置登录按钮圆角:', this.config.login_button_radius, '(来源: API数据)');
        } else {
          this.config.login_button_radius = '4';
          console.log('使用默认登录按钮圆角:', this.config.login_button_radius, '(来源: 默认值)');
        }
        
        // 登录按钮阴影
        if ('login_button_shadow' in apiData) {
          this.config.login_button_shadow = apiData.login_button_shadow === 0 || 
                                          apiData.login_button_shadow === '0' || 
                                          apiData.login_button_shadow === false ? 0 : 1;
          console.log('设置登录按钮阴影:', this.config.login_button_shadow, '(来源: API数据)');
        } else {
          this.config.login_button_shadow = 1;
          console.log('使用默认登录按钮阴影:', this.config.login_button_shadow, '(来源: 默认值)');
        }
        
        console.log('按钮样式最终配置:');
        console.log('- 字体颜色:', this.config.login_button_font_color);
        console.log('- 背景颜色:', this.config.login_button_bg_color);
        console.log('- 渐变颜色:', this.config.login_button_gradient_color);
        console.log('- 字体大小:', this.config.login_button_font_size);
        console.log('- 字体粗细:', this.config.login_button_font_weight);
        console.log('- 按钮圆角:', this.config.login_button_radius);
        console.log('======== 按钮样式配置处理结束 ========');
      } else {
        console.error('API响应没有data字段或响应为空');
      }
      
      this.loaded = true;
      console.log('配置加载完成:', JSON.stringify(this.config));
      
      // 触发监听器
      console.log(`开始通知 ${this.listeners.length} 个监听器...`);
      this.notifyListeners();
      
      // 强制发送一个全局事件，通知配置变更
      if (typeof uni !== 'undefined') {
        console.log('发送全局配置变更事件，按钮配置:', {
          font_color: this.config.login_button_font_color,
          bg_color: this.config.login_button_bg_color,
          gradient_color: this.config.login_button_gradient_color,
          font_size: this.config.login_button_font_size,
          font_weight: this.config.login_button_font_weight,
          radius: this.config.login_button_radius,
        });
        uni.$emit('config:updated', this.config);
      }
      
      return this.config;
    } catch (error) {
      console.error('加载配置失败', error);
      return this.config;
    } finally {
      this.loading = false;
    }
  }
  
  // 添加网络检查方法
  checkNetworkConnection(timeout = 5000) {
    return new Promise((resolve) => {
      // 添加超时处理
      const timeoutId = setTimeout(() => {
        console.log('网络检查超时，继续流程');
        resolve();
      }, timeout);
      
      const checkConnection = () => {
        uni.getNetworkType({
          success: (res) => {
            if (res.networkType !== 'none') {
              console.log('网络连接正常:', res.networkType);
              clearTimeout(timeoutId);
              resolve();
            } else {
              console.log('无网络连接，稍后重试');
              setTimeout(checkConnection, 500);
            }
          },
          fail: () => {
            console.log('获取网络状态失败，稍后重试');
            setTimeout(checkConnection, 500);
          }
        });
      };
      
      checkConnection();
    });
  }
  
  // 获取背景图列表
  getBackgroundImages() {
    return this.config.background_images;
  }
  
  // 获取跳转链接列表
  getRedirectUrls() {
    return this.config.redirect_urls;
  }
  
  // 获取一个随机的跳转链接
  getRandomRedirectUrl() {
    const urls = this.getRedirectUrls();
    if (urls && urls.length > 0) {
      // 从数组中随机选择一个URL
      const randomIndex = Math.floor(Math.random() * urls.length);
      const url = urls[randomIndex];
      console.log('随机选择跳转URL:', url, '索引:', randomIndex, '总数:', urls.length);
      return url;
    } else {
      console.warn('未找到有效的跳转链接');
      return null;
    }
  }
  
  // 获取屏幕方向设置，提供默认值
  getScreenOrientation() {
    // 提供默认值，确保即使config对象不完整也有返回值
    const defaultOrientation = (config && config.defaults && config.defaults.screen_orientation) ? 
      config.defaults.screen_orientation : 'portrait';
      
    return this.config.screen_orientation || defaultOrientation;
  }
  
  // 添加配置变更监听器
  addListener(callback) {
    if (typeof callback === 'function') {
      this.listeners.push(callback);
      
      // 如果配置已加载，立即触发回调
      if (this.loaded) {
        callback(this.config);
      }
    }
  }
  
  // 移除监听器
  removeListener(callback) {
    this.listeners = this.listeners.filter(listener => listener !== callback);
  }
  
  // 触发所有监听器
  notifyListeners() {
    console.log(`正在通知 ${this.listeners.length} 个监听器...`);
    
    if (this.listeners.length > 0) {
      // 创建一个配置快照，确保每个监听器收到相同的配置
      const configSnapshot = { ...this.config };
      console.log('通知监听器的配置快照 - 按钮配置:', {
        font_color: configSnapshot.login_button_font_color,
        bg_color: configSnapshot.login_button_bg_color,
        gradient_color: configSnapshot.login_button_gradient_color
      });
      
      for (const listener of this.listeners) {
        if (typeof listener === 'function') {
          try {
            console.log('正在调用监听器...');
            listener(configSnapshot);
          } catch (error) {
            console.error('通知配置更新出错:', error);
          }
        }
      }
      console.log('所有监听器通知完成');
    } else {
      console.log('没有监听器需要通知');
    }
  }
  
  // 获取配置对象
  getConfig() {
    return this.config;
  }
  
  // 检查配置是否已加载完成
  isConfigLoaded() {
    return this.loaded;
  }
  
  // 验证颜色值是否有效
  validateColorValue(color) {
    if (!color) {
      console.log('颜色值为空，使用默认值: #ffffff');
      return '#ffffff'; // 默认白色
    }
    
    try {
      console.log('开始验证颜色值:', color, '类型:', typeof color);
      
      // 去除可能的空格
      const trimmedColor = typeof color === 'string' ? color.trim() : color;
      
      // 检查是否是有效的颜色格式
      if (typeof trimmedColor === 'string' && /^#([0-9A-F]{3}){1,2}$/i.test(trimmedColor)) {
        // 是有效的十六进制颜色
        console.log('有效的十六进制颜色:', trimmedColor);
        return trimmedColor;
      } else if (typeof trimmedColor === 'string' && 
                (/^rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)$/i.test(trimmedColor) || 
                 /^rgba\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*[\d.]+\s*\)$/i.test(trimmedColor))) {
        // 是有效的RGB/RGBA颜色
        console.log('有效的RGB/RGBA颜色:', trimmedColor);
        return trimmedColor;
      } else if (typeof trimmedColor === 'string') {
        // 尝试修复颜色格式
        if (trimmedColor.startsWith('#')) {
          // 看起来是十六进制颜色，但可能格式不标准
          console.log('看起来是十六进制颜色:', trimmedColor);
          
          // 查看是否是6位十六进制
          if (/^#[0-9A-F]{6}$/i.test(trimmedColor)) {
            console.log('有效的6位十六进制颜色:', trimmedColor);
            return trimmedColor;
          } 
          // 查看是否是3位十六进制
          else if (/^#[0-9A-F]{3}$/i.test(trimmedColor)) {
            console.log('有效的3位十六进制颜色:', trimmedColor);
            return trimmedColor;
          }
          // 其他情况，可能需要修复
          else {
            console.log('非标准十六进制颜色，尝试修复:', trimmedColor);
            // 去除#后检查是否是有效的十六进制字符
            const hexPart = trimmedColor.substring(1);
            if (/^[0-9A-F]+$/i.test(hexPart)) {
              if (hexPart.length === 3 || hexPart.length === 6) {
                console.log('修复后的十六进制颜色:', trimmedColor);
                return trimmedColor;
              } else {
                console.log('十六进制颜色长度不正确，使用默认值');
                return '#ffffff';
              }
            } else {
              console.log('无效的十六进制字符，使用默认值');
              return '#ffffff';
            }
          }
        } 
        else if (trimmedColor.startsWith('rgb') || trimmedColor.startsWith('rgba')) {
          // RGB/RGBA格式，但可能不符合正则
          console.log('看起来是RGB/RGBA颜色:', trimmedColor);
          return trimmedColor;
        } 
        else if (/^[0-9A-F]{3,6}$/i.test(trimmedColor)) {
          // 可能是缺少#的十六进制颜色
          const fixedColor = '#' + trimmedColor;
          console.log('修复缺少#的十六进制颜色:', trimmedColor, '->', fixedColor);
          return fixedColor;
        } 
        else {
          console.warn('无法识别的颜色格式:', trimmedColor, '，使用默认值');
          return '#ffffff';
        }
      } else {
        console.warn('颜色值不是字符串类型:', trimmedColor, '，使用默认值');
        return '#ffffff';
      }
    } catch (error) {
      console.error('验证颜色值出错:', error, '，使用默认值');
      return '#ffffff'; // 错误时返回白色
    }
  }
}

// 导出单例
export default new ConfigManager(); 