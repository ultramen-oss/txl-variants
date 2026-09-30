

import config from '@/config/server.config.js'

class PlatformUtil {
    static isIOS() {
        // #ifdef APP-PLUS
        return plus.os.name.toLowerCase() === 'ios';
        // #endif
        return false;
    }

    static isAndroid() {
        // #ifdef APP-PLUS
        return plus.os.name.toLowerCase() === 'android';
        // #endif
        return false;
    }

    static getPlatformConfig() {
        if (this.isIOS()) {
            return config.ios_api;
        } else if (this.isAndroid()) {
            return config.android_api;
        }
        return config.api; // 默认配置
    }
}

export default PlatformUtil; 