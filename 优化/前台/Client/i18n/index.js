

import { createI18n } from 'vue-i18n'
import messages from '@/locale/index.js'

const i18n = createI18n({
    locale: uni.getStorageSync('language') || 'zh-CN', // 默认语言
    fallbackLocale: 'zh-CN',  // 回退语言（如果当前语言翻译缺失，使用这个语言）
    messages,                  // 导入的语言包
    legacy: false,            // 使用 Composition API 模式，适用于 Vue 3
    globalInjection: true,    // 全局注入 $t 方法，可以直接在模板中使用
    missingWarn: false,       // 关闭缺失翻译警告
    fallbackWarn: false       // 关闭回退警告
})

export default i18n 