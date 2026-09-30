<template>
  <view class="webview-container">
    <web-view :src="url"></web-view>
  </view>
</template>

<script>
export default {
  data() {
    return {
      url: '',
      title: '服务协议'
    }
  },
  onLoad(options) {
    if (options.url) {
      this.url = decodeURIComponent(options.url);
    }
    if (options.title) {
      this.title = decodeURIComponent(options.title);
      // 设置导航栏标题
      uni.setNavigationBarTitle({
        title: this.title
      });
    }
  },
  onReady() {
    // 动态设置导航栏
    uni.setNavigationBarColor({
      frontColor: '#ffffff',
      backgroundColor: '#ff6600',
      animation: {
        duration: 300,
        timingFunc: 'easeIn'
      }
    });
  }
}
</script>

<style>
.webview-container {
  width: 100%;
  height: 100vh;
}
</style> 