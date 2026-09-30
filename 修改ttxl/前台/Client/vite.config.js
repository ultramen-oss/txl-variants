

import { defineConfig } from 'vite'
import uni from '@dcloudio/vite-plugin-uni'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    uni(),
  ],
  define: {
    // 为 vue-i18n 特性标志提供明确的布尔值
    '__VUE_I18N_FULL_INSTALL__': true,
    '__VUE_I18N_LEGACY_API__': false,
    '__INTLIFY_PROD_DEVTOOLS__': false,
  },
  resolve: {
    alias: {
      'vue-i18n': 'vue-i18n/dist/vue-i18n.runtime.esm-bundler.js'
    }
  }
}) 