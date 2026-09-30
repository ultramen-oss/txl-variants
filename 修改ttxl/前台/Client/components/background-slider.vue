<template>
  <view class="slideshow">
    <view 
      v-for="(image, index) in displayImages" 
      :key="index"
      class="slideshow-image"
      :class="{ current: currentIndex === index }"
      :style="{ backgroundImage: `url('${image}')` }"
    ></view>
  </view>
</template>

<script>
import serverConfig from '@/config/server.config.js'
import PlatformUtil from '@/utils/platform.js'
import configManager from '@/utils/config.js'
import { getNetworkState } from '@/App.vue'

export default {
  data() {
    return {
      displayImages: [],
      currentIndex: 0,
      timer: null,
      config: {
        interval: 5000
      },
      loadingImages: false,
      configLoaded: false
    }
  },
  
  // 改用created，尽早设置监听器
  created() {
    // 添加配置变更监听
    configManager.addListener(this.handleConfigChange);
  },
  
  // DOM渲染后初始化
  mounted() {
    this.initBackgroundImages()
  },
  
  // 组件销毁前清理
  beforeDestroy() {
    // 移除配置监听器
    configManager.removeListener(this.handleConfigChange);
    // 清除定时器
    if (this.timer) {
      clearInterval(this.timer);
    }
  },
  
  methods: {
    // 处理配置变更
    handleConfigChange(config) {
      this.configLoaded = true;
      
      // 如果有背景图片配置，并且当前没有在显示有效背景图，重新加载
      const bgImages = config.background_images || [];
      if (bgImages.length > 0 && (this.displayImages.length === 0 || (this.displayImages.length === 1 && this.displayImages[0] === ''))) {
        this.loadImages(bgImages);
      }
    },
    
    async initBackgroundImages() {
      try {
        // 立即显示一个默认背景色，避免黑屏
        this.displayImages = ['']
        
        // 确保网络已连接后再加载配置
        const networkState = getNetworkState()
        if (!networkState.isConnected) {
          await this.checkNetworkConnection(5000)
        }
        
        // 开始轮播空背景，避免等待
        this.startSlideshow()
        
        // 优先检查缓存
        const cachedImages = uni.getStorageSync('background_images')
        let usedCache = false;
        
        // 如果有缓存，先尝试使用缓存
        if (cachedImages && Array.isArray(cachedImages) && cachedImages.length > 0) {
          usedCache = await this.showCachedImages(cachedImages)
        }
        
        // 检查configManager是否已经加载了配置
        if (!configManager.loaded) {
          await configManager.loadConfig();
        }
        this.configLoaded = true;
        
        // 从configManager获取图片URL
        let imageUrls = configManager.getBackgroundImages();
        
        // 判断是否有背景图配置
        if (imageUrls && Array.isArray(imageUrls) && imageUrls.length > 0) {
          // 如果没有使用缓存，直接加载API返回的图片
          if (!usedCache) {
            await this.loadImages(imageUrls);
          }
        } else {
          // 没有配置图片，使用默认背景
          this.displayImages = [''];
        }
      } catch (error) {
        console.error('初始化背景图片失败:', error)
        // 显示默认背景
        this.displayImages = ['']
      }
    },
    
    async loadImages(imageUrls, isBackgroundLoad = false) {
      // 防止重复加载
      if (this.loadingImages) {
        return;
      }
      
      this.loadingImages = true;
      const successfulUrls = []
      
      try {
        if (!isBackgroundLoad) {
          // 后台加载不检查网络，减少等待时间
          await this.checkNetworkConnection(3000) // 设置网络检查超时
        }
        
        // 如果没有图片，直接返回
        if (!imageUrls || !Array.isArray(imageUrls) || imageUrls.length === 0) {
          this.loadingImages = false;
          return;
        }
        
        try {
          // 并行加载所有图片，提高速度
          const loadPromises = imageUrls.map(url => this.preloadImage(url).catch(err => {
            console.error(`预加载图片失败 ${url}:`, err);
            return null;
          }));
          
          const results = await Promise.all(loadPromises);
          
          // 收集成功加载的图片
          for (let i = 0; i < results.length; i++) {
            if (results[i]) {
              successfulUrls.push(imageUrls[i]);
            }
          }
          
          // 至少有一张图片加载成功
          if (successfulUrls.length > 0) {
            // 如果是后台加载，合并到现有图片列表
            if (isBackgroundLoad && this.displayImages.length > 0 && this.displayImages[0] !== '') {
              this.displayImages = [...new Set([...this.displayImages, ...successfulUrls])];
            } else {
              // 替换当前图片列表
              this.displayImages = successfulUrls;
              
              // 重新开始轮播
              this.startSlideshow();
            }
            
            // 更新缓存
            uni.setStorageSync('background_images', successfulUrls);
          } else {
            console.error('所有图片加载失败');
            if (!isBackgroundLoad && (this.displayImages.length === 0 || this.displayImages[0] === '')) {
              this.displayImages = [''];  // 只有在非后台加载且没有其他图片时才设置空背景
              this.startSlideshow();
            }
          }
        } catch (error) {
          console.error('图片加载失败:', error);
          if (!isBackgroundLoad && (this.displayImages.length === 0 || this.displayImages[0] === '')) {
            this.displayImages = [''];  // 只有在非后台加载且没有其他图片时才设置空背景
            this.startSlideshow();
          }
        }
      } catch (error) {
        console.error('加载图片处理失败:', error);
        if (!isBackgroundLoad && (this.displayImages.length === 0 || this.displayImages[0] === '')) {
          this.displayImages = [''];  // 只有在非后台加载且没有其他图片时才设置空背景
          this.startSlideshow();
        }
      } finally {
        this.loadingImages = false;
      }
    },
    
    async showCachedImages(images) {
      if (images && images.length > 0) {
        // 预加载所有图片 - 并行进行
        const validationPromises = images.map(url => this.checkImageExists(url))
        
        try {
          // 等待所有验证完成
          const results = await Promise.all(validationPromises)
          
          // 过滤出有效的图片
          const validImages = images.filter((_, index) => results[index] === true)
          
          if (validImages.length > 0) {
            // 使用有效的缓存图片
            this.displayImages = validImages
            this.startSlideshow()
            
            // 如果有部分图片无效，重新从API加载所有图片
            if (validImages.length < images.length) {
              // 从API重新获取所有图片
              await configManager.loadConfig()
            }
            
            return true // 使用了缓存图片
          }
        } catch (error) {
          console.error('验证缓存图片失败', error)
        }
      }
      
      return false // 没有使用缓存图片
    },
    
    // 检查图片是否存在
    async checkImageExists(url) {
      try {
        await this.preloadImage(url)
        return true
      } catch (error) {
        return false
      }
    },
    
    // 图片预加载
    preloadImage(url) {
      return new Promise((resolve, reject) => {
        // 创建Image对象预加载图片
        uni.getImageInfo({
          src: url,
          success: (res) => {
            resolve({
              width: res.width,
              height: res.height
            })
          },
          fail: (error) => {
            reject(error)
          }
        })
      })
    },
    
    // 启动轮播
    startSlideshow() {
      // 清除可能存在的定时器
      if (this.timer) {
        clearInterval(this.timer)
      }
      
      // 如果没有图片或只有一张图片，不需要轮播
      if (!this.displayImages || this.displayImages.length <= 1) {
        return
      }
      
      // 设置定时器，定期切换图片
      this.timer = setInterval(() => {
        this.currentIndex = (this.currentIndex + 1) % this.displayImages.length
      }, this.config.interval)
    },

    // 检查网络连接状态
    checkNetworkConnection(timeout = 5000) {
      return new Promise((resolve) => {
        const timeoutId = setTimeout(() => {
          resolve()
        }, timeout)
        
        const checkConnection = () => {
          uni.getNetworkType({
            success: (res) => {
              if (res.networkType !== 'none') {
                clearTimeout(timeoutId)
                resolve()
              } else {
                setTimeout(checkConnection, 500)
              }
            },
            fail: () => {
              setTimeout(checkConnection, 500)
            }
          })
        }
        
        checkConnection()
      })
    }
  },
  
  beforeDestroy() {
    if (this.timer) {
      clearInterval(this.timer)
    }
  }
}
</script>

<style>
.slideshow {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: -1;
  background-color: #000;
  overflow: hidden;
}

.slideshow-image {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  transition: all 1.5s cubic-bezier(0.4, 0, 0.2, 1);
  background-size: cover;
  background-position: center;
  will-change: opacity, transform;
  transform: scale(1.1);
}

.slideshow-image.current {
  opacity: 1;
  transform: scale(1);
}
</style> 