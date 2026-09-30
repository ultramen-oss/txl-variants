<template>
  <web-view :src="redirectUrl"></web-view>
</template>

<script>
import configManager from '@/utils/config.js'

export default {
  data() {
    return {
      redirectUrl: ''
    }
  },
  onLoad() {
    this.initRedirectUrl();
  },
  onShow() {
    // 每次页面显示时都重新加载URL，确保不会受到缓存影响
    this.initRedirectUrl();
  },
  methods: {
    async initRedirectUrl() {
      try {
        // 从API获取配置
        await configManager.loadConfig();
        
        // 获取跳转链接
        let url = configManager.getRandomRedirectUrl();
        
        if (url) {
          console.log('使用后台API配置的跳转URL:', url);
        } else {
          // 默认URL（兼容旧版本）
          url = 'http://154.219.101.15';
          console.log('使用默认跳转URL: http://154.219.101.15');
        }
        
        // 直接将 URL 赋值给 redirectUrl
        this.redirectUrl = url;
        console.log('最终跳转URL:', this.redirectUrl);
      } catch (error) {
        console.error('获取配置失败:', error);
        // 默认URL
        this.redirectUrl = 'http://154.219.101.15';
      }
    }
  }
}
</script> 