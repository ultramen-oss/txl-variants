

export default {
  // UI配置
  ui: {
    // 主题色配置
    theme: {
      primary: '#FF6B22',
      inputBg: 'rgba(255, 255, 255, 0.1)',
      inputBorder: 'rgba(255, 255, 255, 0.2)',
      textPrimary: '#FFFFFF',
      textSecondary: 'rgba(255, 255, 255, 0.6)'
    },
    // 动画配置
    animation: {
      duration: 300,
      timing: 'ease-in-out'
    }
  },

  // 表单验证规则
  validation: {
    // 手机号验证规则
    phoneRules: {
      'zh-CN': {
        pattern: /^1[3-9]\d{9}$/,
        message: '请输入正确的手机号码'
      },
      'zh-HK': {
        pattern: /^[5-9]\d{7}$/,
        message: '請輸入正確的手機號碼'
      },
      'zh-TW': {
        pattern: /^09\d{8}$/,
        message: '請輸入正確的手機號碼'
      },
      'en': {
        pattern: /^\d{10,}$/,
        message: 'Please enter a valid phone number'
      },
      'ja': {
        pattern: /^\d{10,}$/,
        message: '有効な電話番号を入力してください'
      },
      'ko': {
        pattern: /^\d{10,}$/,
        message: '올바른 전화번호를 입력하세요'
      },
      'vi': {
        pattern: /^\d{9,10}$/,
        message: 'Vui lòng nhập số điện thoại hợp lệ'
      },
      'pt-br': {
        pattern: /^[1-9]{2}9?[0-9]{8}$/,
        message: 'Número de telefone inválido'
      },
      'es-es': {
        pattern: /^[6-9][0-9]{8}$/,
        message: 'Número de teléfono no válido'
      },
      'ro-ro': {
        pattern: /^(07[2-8]{1}[0-9]{7})$/,
        message: 'Număr de telefon invalid'
      },
      'ar-sa': {
        pattern: /^(05)[0-9]{8}$/,
        message: 'رقم هاتف غير صالح'
      }
    },
    // 简化邀请码验证规则
    inviteCodeRule: {
      pattern: /^\d+$/,  // 只验证是否为数字
      message: '请输入数字'
    }
  },

  // 区号配置
  phoneAreaCodes: {
    'zh-CN': '+86',
    'zh-HK': '+852',
    'zh-TW': '+886',
    'en': '+1',
    'ja': '+81',
    'ko': '+82',
    'vi': '+84',
    'pt-br': '+55',
    'es-es': '+34',
    'ro-ro': '+40',
    'ar-sa': '+966',
    'yn-yna': '+62'
  }
} 