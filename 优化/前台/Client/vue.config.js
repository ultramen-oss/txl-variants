

const { defineConfig } = require('@vue/cli-service')

module.exports = defineConfig({
  transpileDependencies: true,
  configureWebpack: {
    resolve: {
      alias: {
        // 为 vue-i18n 添加特性标志替换
        'vue-i18n': 'vue-i18n/dist/vue-i18n.cjs.prod.js'
      }
    }
  }
}) 