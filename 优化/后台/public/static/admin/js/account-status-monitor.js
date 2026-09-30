/**
 * 账号状态监控模块
 * 1. 负责检测账号是否被禁用，如果被禁用则强制登出
 * 2. 检查用户长时间无操作，自动退出登录
 * 3. 提供实时监测机制，确保账号状态变更能及时反映
 * 4. 包含用户角色检测功能，确保正确识别超级管理员
 * 
 * @version 1.2.0
 * @lastupdate 202505031045
 */
(function() {
    // 配置参数
    const CONFIG = {
        // 常规检查间隔 (毫秒)
        CHECK_INTERVAL: 5000,
        
        // 实时检查间隔 (毫秒)
        REALTIME_CHECK_INTERVAL: 2000,
        
        // 最大不活动时间（默认20分钟，可从服务端配置获取）
        MAX_INACTIVITY_TIME: 1200000,
        
        // 定时检查间隔（5秒检查一次无操作状态）
        INACTIVITY_CHECK_INTERVAL: 5000,
        
        // 强制检查间隔（30秒检查一次账号状态）
        FORCE_CHECK_INTERVAL: 30000,
        
        // 调试模式
        DEBUG: false
    };
    
    // 存储状态的key
    const STORAGE_KEYS = {
        ACCOUNT_CHECK_KEY: 'account_last_check_time',
        INACTIVE_NOTICE_KEY: 'inactive_notice_shown',
        DISABLED_NOTICE_KEY: 'disabled_notice_shown',
        LAST_ACTIVITY_KEY: 'last_activity_time',
        USER_INFO_KEY: 'user_info',
        LAST_ACTIVITY_KEY_LOCAL: 'last_activity_time_local', // 新增: 本地存储的活动时间
        SESSION_TIMEOUT_KEY: 'session_timeout_config_202505031045' // 修改: 使用新的缓存键名，避免使用旧缓存
    };
    
    // 从服务器获取超时配置
    function loadTimeoutConfig() {
        try {
            // 清除所有可能的旧缓存
            localStorage.removeItem('session_timeout_config');
            localStorage.removeItem('session_timeout_config_v1');
            localStorage.removeItem('session_timeout_config_v2');
            logDebug("清除所有可能的旧缓存配置");
            
            // 首先尝试从本地存储获取
            const cachedConfig = localStorage.getItem(STORAGE_KEYS.SESSION_TIMEOUT_KEY);
            if (cachedConfig) {
                try {
                    const config = JSON.parse(cachedConfig);
                    if (config && config.timeout && config.timestamp) {
                        // 检查配置是否在10秒内的，这里只保留极短时间的缓存，确保每次刷新都重新获取
                        if (Date.now() - config.timestamp < 10000) { // 10秒
                            // 确保timeout是数字类型
                            const timeout = parseInt(config.timeout);
                            if (!isNaN(timeout) && timeout > 0) {
                                CONFIG.MAX_INACTIVITY_TIME = timeout * 60 * 1000; // 转换为毫秒
                                logDebug("从本地缓存加载超时配置: " + timeout + "分钟 (" + CONFIG.MAX_INACTIVITY_TIME + "ms)");
                                
                                // 在页面显示实际应用的超时设置
                                showTimeoutInfo(timeout);
                                return;
                            }
                        }
                    }
                } catch (e) {
                    // 解析错误，忽略缓存
                    logDebug("解析缓存的超时配置错误: " + e.message);
                }
            }
            
            // 向服务器请求配置
            logDebug("正在从服务器获取超时配置...");
            $.ajax({
                url: '/admin/appv1/getRouteSettings',
                type: 'POST',
                dataType: 'json',
                cache: false,
                async: false, // 同步请求，确保在初始化前获取配置
                success: function(res) {
                    logDebug("收到服务器超时配置响应: " + JSON.stringify(res));
                    if (res.code === 1 && res.data) {
                        const routeSettings = res.data;
                        logDebug("服务器返回的配置: " + JSON.stringify(routeSettings));
                        
                        if (routeSettings.session_timeout) {
                            // 确保转换为数字类型
                            const timeout = parseInt(routeSettings.session_timeout);
                            logDebug("解析超时时间: " + routeSettings.session_timeout + " -> " + timeout);
                            
                            if (!isNaN(timeout) && timeout > 0 && timeout <= 180) {
                                // 更新配置
                                CONFIG.MAX_INACTIVITY_TIME = timeout * 60 * 1000; // 转换为毫秒
                                
                                // 显示超时信息
                                showTimeoutInfo(timeout);
                                
                                // 缓存配置
                                try {
                                    localStorage.setItem(STORAGE_KEYS.SESSION_TIMEOUT_KEY, JSON.stringify({
                                        timeout: timeout,
                                        timestamp: Date.now()
                                    }));
                                } catch (e) {
                                    logDebug("缓存超时配置失败: " + e.message);
                                }
                                
                                logDebug("从服务器加载超时配置: " + timeout + "分钟 (" + CONFIG.MAX_INACTIVITY_TIME + "ms)");
                            } else {
                                logDebug("服务器返回的超时时间无效: " + timeout + "，使用默认值");
                            }
                        } else {
                            logDebug("服务器返回的配置中没有session_timeout字段，使用默认值");
                        }
                    } else {
                        logDebug("服务器响应错误或格式错误: " + JSON.stringify(res));
                    }
                },
                error: function(xhr, status, error) {
                    logDebug("获取超时配置失败: " + status + " - " + error + "，使用默认值: " + (CONFIG.MAX_INACTIVITY_TIME/60000) + "分钟");
                }
            });
        } catch (e) {
            logDebug("加载超时配置出错: " + e.message);
        }
    }
    
    // 缓存DOM元素和状态变量
    const cache = {
        modal: null,
        timers: {
            inactivity: null,
            polling: null,
            realtime: null,
            forceCheck: null
        },
        ws: null,
        statusChecking: false
    };
    
    /* ===== 全局活动监控 ===== */
    // 存储上一次活动时间的全局变量
    let lastActivityTime = Date.now();
    let inactivityTimer = null;
    let inactivityCheckInterval = null;
    
    // 记录活动的函数
    function recordActivity() {
        lastActivityTime = Date.now();
        
        try {
            // 同时保存到localStorage和sessionStorage
            localStorage.setItem(STORAGE_KEYS.LAST_ACTIVITY_KEY_LOCAL, lastActivityTime.toString());
            sessionStorage.setItem(STORAGE_KEYS.LAST_ACTIVITY_KEY, lastActivityTime.toString());
            
            // 触发一个自定义事件，让其他标签页知道有活动发生
            if (typeof window.CustomEvent === 'function') {
                const activityEvent = new CustomEvent('user_activity_recorded', { 
                    detail: { time: lastActivityTime } 
                });
                window.dispatchEvent(activityEvent);
            }
        } catch (e) {
            // 如果存储操作失败，记录日志但不中断流程
            logDebug('记录活动时间失败: ' + e.message);
        }
    }
    
    // 重置无操作计时器
    function resetInactivityTimer() {
        if (inactivityTimer) {
            clearTimeout(inactivityTimer);
        }
        
        inactivityTimer = setTimeout(() => {
            const currentTime = Date.now();
            const inactiveTime = currentTime - lastActivityTime;
            
            if (inactiveTime >= CONFIG.MAX_INACTIVITY_TIME && !isLoginPage()) {
                showTimeoutNotice();
            }
        }, CONFIG.MAX_INACTIVITY_TIME);
    }
    
    // 显示超时通知，并强制加载登录页面
    function showTimeoutNotice() {
        // 如果已经显示了弹窗，不再重复显示
        if (document.getElementById('timeout-modal')) {
            return;
        }
        
        // 先设置退出标记，即使UI显示失败，后续检查也会发现用户需要退出
        try {
            localStorage.setItem('force_logout_required', 'timeout');
            localStorage.setItem('force_logout_time', Date.now().toString());
        } catch (e) {
            // 忽略错误
        }
        
        // 获取当前超时设置（分钟）
        const timeoutMinutes = Math.round(CONFIG.MAX_INACTIVITY_TIME / 60000);
        
        // 创建超时弹窗
        const timeoutModal = document.createElement('div');
        timeoutModal.id = 'timeout-modal';
        timeoutModal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999999;
        `;
        
        const modalContent = document.createElement('div');
        modalContent.style.cssText = `
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        `;
        
        // 倒计时秒数
        const countdownSeconds = 3;
        
        modalContent.innerHTML = `
            <h2 style="margin-top: 0; color: #f5a623; font-size: 22px;">
                <i class="fa fa-clock-o" style="margin-right: 10px;"></i>会话已过期
            </h2>
            <p style="font-size: 16px; margin: 15px 0;">您已超过${timeoutMinutes}分钟未操作，系统将在 <span id="countdown-number" style="font-weight:bold; color:#ff5722;">${countdownSeconds}</span> 秒后安全退出。</p>
            <div style="height: 6px; background-color: #f0f0f0; border-radius: 3px; margin: 20px 0; overflow: hidden;">
                <div id="countdown-progress" style="height: 100%; width: 100%; background: linear-gradient(to right, #f5a623, #ffb74d); transform: translateX(-100%); transition: transform ${countdownSeconds}s linear;"></div>
            </div>
            <div id="forced-logout-message" style="color: #ff5722; font-weight: bold; margin-top: 15px;">准备安全退出系统...</div>
        `;
        
        try {
            timeoutModal.appendChild(modalContent);
            document.body.appendChild(timeoutModal);
            
            // 触发回流
            void timeoutModal.offsetWidth;
            
            // 启动进度条
            const progressBar = document.getElementById('countdown-progress');
            if (progressBar) {
                progressBar.style.transform = 'translateX(0)';
            }
        } catch (e) {
            // 如果DOM操作失败，直接执行登出
            performLogout();
            return;
        }
        
        // 开始倒计时
        let countdown = countdownSeconds;
        const countdownEl = document.getElementById('countdown-number');
        const messageEl = document.getElementById('forced-logout-message');
        
        // 倒计时定时器
        const countdownInterval = setInterval(() => {
            countdown--;
            
            if (countdownEl) {
                countdownEl.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                
                if (messageEl) {
                    messageEl.textContent = '正在退出系统...';
                }
                
                // 移除弹窗，避免干扰后续逻辑
                try {
                    if (timeoutModal && timeoutModal.parentNode) {
                        timeoutModal.parentNode.removeChild(timeoutModal);
                    }
                } catch (e) {
                    // 忽略错误
                }
                
                // 执行统一的登出逻辑
                performLogout();
            }
        }, 1000);
    }
    
    // 执行登出操作 - 直接在顶层文档中加载登录页面
    function performLogout() {
        logDebug("执行强制登出操作");
        
        try {
            // 清除所有会话状态
            sessionStorage.clear();
            localStorage.clear();
            
            // 强制清除PHP会话
            document.cookie = "auto_logout=1; path=/; max-age=60";
            document.cookie = "PHPSESSID=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            
            // 清除可能的其他会话cookie
            var cookies = document.cookie.split("; ");
            for (var i = 0; i < cookies.length; i++) {
                var cookieName = cookies[i].split("=")[0];
                document.cookie = cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            }
            
            // 在后台发送登出请求
            const logoutXhr = new XMLHttpRequest();
            logoutXhr.open('POST', '/admin/login/logout', true);
            logoutXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            logoutXhr.send('t=' + Date.now());
            
            // 这里不立即跳转，而是由showTimeoutNotice控制跳转时机
            // 如果是其他地方调用此函数，则正常执行跳转
            if (!document.getElementById('timeout-modal')) {
                // 构建特殊的登录URL
                const loginUrl = '/admin/login/index?'
                    + 'auto_logout=1'
                    + '&clear=1'
                    + '&force=1'
                    + '&cause=timeout'
                    + '&cache_bust=' + Math.random()
                    + '&t=' + Date.now();
                
                // 直接加载登录页面，替换当前页面
                try {
                    top.location.href = loginUrl;
                } catch (err) {
                    logDebug("top.location.href失败: " + err.message);
                    try {
                        window.location.replace(loginUrl);
                    } catch (err2) {
                        logDebug("window.location.replace失败: " + err2.message);
                        window.location.href = loginUrl;
                    }
                }
                
                // 如果replace失败，使用普通跳转
                setTimeout(function() {
                    if (!isLoginPage()) {
                        logDebug("尝试普通跳转");
                        window.location.href = loginUrl;
                    }
                }, 100);
                
                // 最后尝试硬刷新
                setTimeout(function() {
                    if (!isLoginPage()) {
                        logDebug("尝试硬刷新");
                        document.cookie = "FORCE_LOGOUT=1; path=/;";
                        window.location.href = loginUrl + '&hard=1';
                        window.location.reload(true);
                    }
                }, 300);
                
                // 终极保障措施：创建一个表单并提交到登录页面
                setTimeout(function() {
                    if (!isLoginPage()) {
                        logDebug("使用表单提交到登录页面");
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/login/index';
                        form.style.display = 'none';
                        
                        const autoLogoutInput = document.createElement('input');
                        autoLogoutInput.type = 'hidden';
                        autoLogoutInput.name = 'auto_logout';
                        autoLogoutInput.value = '1';
                        form.appendChild(autoLogoutInput);
                        
                        const timeInput = document.createElement('input');
                        timeInput.type = 'hidden';
                        timeInput.name = 't';
                        timeInput.value = Date.now();
                        form.appendChild(timeInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                }, 500);
            }
        } catch (e) {
            logDebug("强制登出失败: " + e.message);
            // 最后手段
            window.location.href = '/admin/login/index?failed=1&t=' + Date.now();
        }
    }
    
    // 开始活动监控
    function startActivityMonitoring() {
        logDebug("启动活动监控");
        
        // 调试输出当前设置的超时时间
        logDebug("当前超时时间设置: " + (CONFIG.MAX_INACTIVITY_TIME / 60000) + "分钟 (" + CONFIG.MAX_INACTIVITY_TIME + "ms)");
        
        // 检查是否需要强制登出（处理页面刷新的情况）
        try {
            const forceLogoutRequired = localStorage.getItem('force_logout_required');
            const forceLogoutTime = parseInt(localStorage.getItem('force_logout_time') || '0');
            
            // 如果存在强制登出标记，且时间在30分钟内，则执行登出
            if (forceLogoutRequired && 
                forceLogoutTime > 0 && 
                (Date.now() - forceLogoutTime) < 1800000) { // 30分钟
                
                logDebug("检测到未完成的强制登出操作，继续执行");
                
                // 根据登出原因执行相应操作
                if (forceLogoutRequired === 'timeout') {
                    showTimeoutNotice();
                    return; // 不继续初始化活动监控
                } else if (forceLogoutRequired === 'disabled') {
                    showDisabledNotice();
                    return; // 不继续初始化活动监控
                } else {
                    // 其他未知原因，直接执行登出
                    performLogout();
                    return;
                }
            } else if (forceLogoutRequired) {
                // 如果标记存在但已过期，清除它
                localStorage.removeItem('force_logout_required');
                localStorage.removeItem('force_logout_time');
            }
        } catch (e) {
            // 忽略存储错误
            logDebug("检查强制登出标记时出错: " + e.message);
        }
        
        // 初始化活动时间
        recordActivity();
        
        // 设置定时器
        resetInactivityTimer();
        
        // 设置定期检查间隔
        if (inactivityCheckInterval) {
            clearInterval(inactivityCheckInterval);
        }
        
        inactivityCheckInterval = setInterval(() => {
            if (!isLoginPage()) {
                const currentTime = Date.now();
                const lastActivityFromStorage = parseInt(localStorage.getItem(STORAGE_KEYS.LAST_ACTIVITY_KEY_LOCAL) || lastActivityTime.toString());
                const inactiveTime = currentTime - lastActivityFromStorage;
                
                logDebug(`定期检查: 当前时间 ${new Date(currentTime).toLocaleTimeString()}, 上次活动 ${new Date(lastActivityFromStorage).toLocaleTimeString()}, 无操作时间 ${inactiveTime}ms, 超时阈值 ${CONFIG.MAX_INACTIVITY_TIME}ms`);
                
                if (inactiveTime >= CONFIG.MAX_INACTIVITY_TIME) {
                    logDebug("定期检查检测到超时: " + inactiveTime + "ms");
                    showTimeoutNotice();
                }
            }
        }, CONFIG.INACTIVITY_CHECK_INTERVAL);
        
        // 监听用户活动事件
        const activityEvents = [
            'mousedown', 'mousemove', 'keydown', 'scroll', 
            'touchstart', 'click', 'keypress', 'focus'
        ];
        
        activityEvents.forEach(eventName => {
            document.addEventListener(eventName, () => {
                recordActivity();
                resetInactivityTimer();
            }, { passive: true });
        });
    }
    
    /* ===== 工具函数 ===== */
    
    // 记录调试信息
    function logDebug(message) {
        if (CONFIG.DEBUG && console && console.log) {
            console.log("[账户状态监控] " + message);
        }
    }
    
    // 在页面上显示当前超时设置信息
    function showTimeoutInfo(timeout) {
        // 生产环境不显示超时信息
        if (!CONFIG.DEBUG) {
            return;
        }
        
        try {
            // 创建或更新页面上的提示元素
            let infoElement = document.getElementById('timeout-info');
            if (!infoElement) {
                infoElement = document.createElement('div');
                infoElement.id = 'timeout-info';
                infoElement.style.cssText = 'position:fixed; bottom:10px; right:10px; background:rgba(0,0,0,0.7); color:white; padding:10px; border-radius:5px; font-size:12px; z-index:9999;';
                document.body.appendChild(infoElement);
            }
            infoElement.innerHTML = `当前已应用的超时时间: <b>${timeout}</b> 分钟`;
            
            // 10秒后自动隐藏
            setTimeout(() => {
                if (infoElement && infoElement.parentNode) {
                    infoElement.parentNode.removeChild(infoElement);
                }
            }, 10000);
        } catch (e) {
            // 忽略显示错误
        }
    }
    
    // 检查当前是否在登录页面
    function isLoginPage() {
        // 首先检查是否已显示会话过期弹窗（解决无限循环问题）
        if (document.querySelector('.layui-layer-dialog') && 
            document.querySelector('.layui-layer-dialog').textContent.includes('会话已过期')) {
            logDebug("检测到会话过期弹窗，认为用户已登出");
            return true;
        }
        
        // 更加严格的登录页面检测
        if (window.location.pathname.indexOf('/login') > -1 || 
            window.location.pathname.indexOf('/uxwnet') > -1 ||
            document.title.toLowerCase().indexOf('登录') > -1 ||
            $('form[action*="login"]').length > 0 ||
            $('form#login-form').length > 0 ||  // 检查登录表单ID
            $('#captcha-input').length > 0 ||  // 检查验证码输入框
            $('.company-title:contains("通讯后台")').length > 0 ||  // 检查登录页特有标题
            document.getElementById('login-form') != null) {  // 检查是否存在登录表单元素
            
            sessionStorage.clear(); // 在登录页面时清除所有状态
            
            // 在登录页面时移除可能存在的弹窗
            try {
                const modal = document.getElementById('timeout-modal');
                if (modal && modal.parentNode) {
                    modal.parentNode.removeChild(modal);
                }
            } catch(e) {
                // 忽略错误
            }
            
            return true;
        }
        return false;
    }
    
    // 获取当前用户ID
    function getCurrentUserId() {
        return window.current_admin_id || 
               (document.querySelector('[data-admin-id]')?.getAttribute('data-admin-id')) || 
               '';
    }
    
    // 获取CSRF令牌
    function getCsrfToken() {
        const tokenEl = document.querySelector('meta[name="csrf-token"]');
        return tokenEl ? tokenEl.getAttribute('content') : '';
    }
    
    /* ===== 用户角色检测功能 ===== */
    
    // 初始化用户角色数据 (整合自原user-role-detector.js)
    function initUserRoleData() {
        // 如果已经设置了全局变量，无需再处理
        if (window.current_admin_role !== undefined) {
            return;
        }
        
        // 尝试从元数据中读取
        const roleMeta = document.querySelector('meta[name="user-role"]');
        if (roleMeta) {
            const role = roleMeta.getAttribute('content') || 'user';
            window.current_admin_role = role;
            return;
        }
        
        // 如果页面上没有角色信息，尝试从会话存储中获取
        const userInfoStr = sessionStorage.getItem(STORAGE_KEYS.USER_INFO_KEY);
        if (userInfoStr) {
            try {
                const userInfo = JSON.parse(userInfoStr);
                if (userInfo && userInfo.role_type) {
                    window.current_admin_role = userInfo.role_type;
                    window.current_admin_id = userInfo.id;
                    return;
                }
            } catch (e) {
                logDebug('解析用户信息失败: ' + e.message);
            }
        }
        
        // 如果没有找到角色信息，默认设置为普通用户
        window.current_admin_role = 'user';
    }
    
    // 在页面上添加用户角色元数据标签 (整合自原user-role-detector.js)
    function addUserRoleMeta() {
        if (!document.querySelector('meta[name="user-role"]') && window.current_admin_role) {
            const meta = document.createElement('meta');
            meta.name = 'user-role';
            meta.content = window.current_admin_role;
            document.head.appendChild(meta);
        }
    }
    
    // 检查当前用户是否是超级管理员
    function isSuperAdmin() {
        // 确保用户角色数据已初始化
        initUserRoleData();
        
        // 快速检查全局变量(最常见的方式)
        if (window.current_admin_role === 'super_admin') {
            return true;
        }
        
        // 检查meta标签
        const roleMeta = document.querySelector('meta[name="user-role"]');
        if (roleMeta && roleMeta.getAttribute('content') === 'super_admin') {
            return true;
        }
        
        // 检查当前用户是否是ID为1的用户（超级管理员）
        if (window.current_admin_id === '1' || window.current_admin_id === 1) {
            return true;
        }
        
        return false;
    }
    
    /* ===== 会话存储操作 ===== */
    
    // 获取最后检查时间
    function getLastCheckTime() {
        const lastTime = sessionStorage.getItem(STORAGE_KEYS.ACCOUNT_CHECK_KEY);
        return lastTime ? parseInt(lastTime) : 0;
    }
    
    // 更新最后检查时间
    function updateLastCheckTime() {
        sessionStorage.setItem(STORAGE_KEYS.ACCOUNT_CHECK_KEY, Date.now().toString());
    }
    
    // 检查是否已经显示过通知
    function hasShownDisabledNotice() {
        return sessionStorage.getItem(STORAGE_KEYS.DISABLED_NOTICE_KEY) === 'true';
    }
    
    function hasShownInactiveNotice() {
        return sessionStorage.getItem(STORAGE_KEYS.INACTIVE_NOTICE_KEY) === 'true';
    }
    
    // 标记已经显示了通知
    function markDisabledNoticeShown() {
        sessionStorage.setItem(STORAGE_KEYS.DISABLED_NOTICE_KEY, 'true');
    }
    
    function markInactiveNoticeShown() {
        sessionStorage.setItem(STORAGE_KEYS.INACTIVE_NOTICE_KEY, 'true');
    }
    
    // 检查当前是否在禁用账号操作
    function isDisablingOtherUser() {
        // 检查URL路径中是否包含管理员管理或者用户管理相关路径
        if (window.location.pathname.indexOf('/admin/admin/') > -1 ||
            window.location.pathname.indexOf('/admin/user/') > -1 ||
            window.location.pathname.indexOf('/admin/system/user') > -1) {
            
            // 检查URL是否包含禁用/启用操作
            const currentUrl = window.location.href.toLowerCase();
            if (currentUrl.indexOf('disable') > -1 ||
                currentUrl.indexOf('enable') > -1 ||
                currentUrl.indexOf('status') > -1) {
                return true;
            }
            
            // 检查最近的XHR请求
            // 由于无法直接跟踪所有XHR，我们使用一个标记
            return !!window.recentlyDisabledUser;
        }
        
        return false;
    }
    
    // 标记刚刚执行了禁用操作
    function markDisablingOperation() {
        window.recentlyDisabledUser = true;
        setTimeout(() => {
            window.recentlyDisabledUser = false;
        }, 5000); // 5秒后清除标记
    }
    
    // 检查账号状态的Ajax请求
    function fetchAccountStatus(callback) {
        // 如果已经在进行检查，不重复发起请求
        if (cache.statusChecking) {
            return;
        }
        
        // 如果是管理员正在禁用其他账户，跳过检查
        if (isSuperAdmin() && isDisablingOtherUser()) {
            if (typeof callback === 'function') {
                callback(false); // 不触发禁用通知
            }
            return;
        }
        
        cache.statusChecking = true;
        
        // 获取用户ID和Token信息
        const userId = getCurrentUserId();
        const csrfToken = getCsrfToken();
        
        // 发送AJAX请求检查账号状态
        $.ajax({
            url: '/admin/api/checkStatus',
            type: 'post',
            dataType: 'json',
            cache: false,  // 禁止缓存
            data: {
                userId: userId,
                timestamp: Date.now(),  // 添加时间戳避免缓存
                _token: csrfToken
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache'
            },
            success: function(res) {
                updateLastCheckTime();
                cache.statusChecking = false;
                
                // 增加更多的条件判断
                if (res.code === 0 && res.disabled === true) {
                    logDebug("检测到账号已被禁用");
                    if (typeof callback === 'function') {
                        callback(true); // 账号已禁用
                    }
                } else if (res.code === 0 && res.status === 'disabled') {
                    logDebug("检测到账号已被禁用(状态字段)");
                    if (typeof callback === 'function') {
                        callback(true); // 账号已禁用
                    }
                } else if (res.code === 401 || res.code === 403) {
                    logDebug("检测到账号无权限或需要登录");
                    if (typeof callback === 'function') {
                        callback(true); // 认为账号有问题，强制退出
                    }
                } else {
                    logDebug("账号状态正常");
                    if (typeof callback === 'function') {
                        callback(false); // 账号正常
                    }
                }
            },
            error: function(xhr) {
                cache.statusChecking = false;
                
                // 检查特定的HTTP状态码
                if (xhr.status === 401 || xhr.status === 403) {
                    logDebug("服务器返回未授权状态");
                    if (typeof callback === 'function') {
                        callback(true); // 认为账号有问题，强制退出
                    }
                } else {
                    if (typeof callback === 'function') {
                        callback(false); // 其他错误
                    }
                }
            }
        });
    }
    
    // 重定向到登录页
    function redirectToLogin() {
        // 确保清除所有会话存储，避免未来登录时保留旧状态
        sessionStorage.clear();
        localStorage.removeItem(STORAGE_KEYS.LAST_ACTIVITY_KEY_LOCAL);
        
        // 重定向到登录页并添加清除参数
        window.location.href = '/admin/login/index?clear=1&t=' + Date.now();
    }
    
    // 显示禁用通知
    function showDisabledNotice() {
        if (hasShownDisabledNotice() || isSuperAdmin() || isDisablingOtherUser()) {
            return false;
        }
        
        // 检查是否已经在登录页面，如果是则不显示禁用通知
        if (isLoginPage()) {
            return false;
        }
        
        // 设置强制登出标记
        try {
            localStorage.setItem('force_logout_required', 'disabled');
            localStorage.setItem('force_logout_time', Date.now().toString());
        } catch (e) {
            // 忽略存储错误
        }
        
        logDebug("显示账号禁用通知");
        markDisabledNoticeShown();
        
        // 如果已经显示了弹窗，不再重复显示
        if (document.getElementById('timeout-modal')) {
            return false;
        }
        
        // 创建超时弹窗
        const timeoutModal = document.createElement('div');
        timeoutModal.id = 'timeout-modal';
        timeoutModal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999999;
        `;
        
        const modalContent = document.createElement('div');
        modalContent.style.cssText = `
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        `;
        
        // 倒计时秒数
        const countdownSeconds = 5;
        
        modalContent.innerHTML = `
            <h2 style="margin-top: 0; color: #ff5722; font-size: 22px;">
                <i class="fa fa-exclamation-triangle" style="margin-right: 10px;"></i>账号已禁用
            </h2>
            <p style="font-size: 16px; margin: 15px 0;">您的账号已被管理员禁用，系统将在 <span id="countdown-number" style="font-weight:bold; color:#ff5722;">${countdownSeconds}</span> 秒后安全退出。</p>
            <div style="height: 6px; background-color: #f0f0f0; border-radius: 3px; margin: 20px 0; overflow: hidden;">
                <div id="countdown-progress" style="height: 100%; width: 100%; background: linear-gradient(to right, #ff5722, #ff9800); transform: translateX(-100%); transition: transform ${countdownSeconds}s linear;"></div>
            </div>
            <div id="forced-logout-message" style="color: #ff5722; font-weight: bold; margin-top: 15px;">准备安全退出系统...</div>
        `;
        
        timeoutModal.appendChild(modalContent);
        document.body.appendChild(timeoutModal);
        
        // 触发回流
        void timeoutModal.offsetWidth;
        
        // 启动进度条
        const progressBar = document.getElementById('countdown-progress');
        if (progressBar) {
            progressBar.style.transform = 'translateX(0)';
        }
        
        // 开始倒计时
        let countdown = countdownSeconds;
        const countdownEl = document.getElementById('countdown-number');
        const messageEl = document.getElementById('forced-logout-message');
        
        // 倒计时定时器
        const countdownInterval = setInterval(() => {
            countdown--;
            
            if (countdownEl) {
                countdownEl.textContent = countdown;
            }
            
            if (countdown <= 0) {
                clearInterval(countdownInterval);
                
                if (messageEl) {
                    messageEl.textContent = '正在退出系统...';
                }
                
                // 删除弹窗避免后续重复显示
                if (timeoutModal && timeoutModal.parentNode) {
                    try {
                        timeoutModal.parentNode.removeChild(timeoutModal);
                    } catch (e) {
                        // 忽略可能的删除错误
                    }
                }
                
                // 清除所有会话状态
                try {
                    // 清除会话存储
                    sessionStorage.clear();
                    localStorage.clear();
                    
                    // 清除所有cookie
                    const cookies = document.cookie.split(";");
                    for (let i = 0; i < cookies.length; i++) {
                        const cookie = cookies[i];
                        const eqPos = cookie.indexOf("=");
                        const name = eqPos > -1 ? cookie.substr(0, eqPos).trim() : cookie.trim();
                        document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
                    }
                    
                    // 设置登出标记
                    document.cookie = "auto_logout=1; path=/; max-age=60";
                } catch (e) {
                    logDebug("清除会话状态失败: " + e.message);
                }
                
                // 创建隐藏的iframe预加载登录页面
                const hiddenFrame = document.createElement('iframe');
                hiddenFrame.style.display = 'none';
                hiddenFrame.id = 'login-frame';
                hiddenFrame.src = '/admin/login/index?auto_logout=1&clear=1&t=' + Date.now();
                document.body.appendChild(hiddenFrame);
                
                // 发送登出请求
                try {
                    const logoutXhr = new XMLHttpRequest();
                    logoutXhr.open('POST', '/admin/login/logout', true);
                    logoutXhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    logoutXhr.send('t=' + Date.now());
                } catch (e) {
                    // 忽略请求错误
                }
                
                // 构建特殊的登录URL
                const loginUrl = '/admin/login/index?'
                    + 'auto_logout=1'
                    + '&clear=1'
                    + '&force=1'
                    + '&cache_bust=' + Math.random()
                    + '&t=' + Date.now();
                
                // 尝试顶层窗口直接跳转
                try {
                    window.top.location.href = loginUrl;
                    return; // 如果成功跳转，直接返回
                } catch (err) {
                    // 忽略错误，继续尝试其他方法
                }
                
                // 尝试普通跳转
                try {
                    window.location.replace(loginUrl);
                } catch (err) {
                    window.location.href = loginUrl;
                }
                
                // 最后的保障措施，1秒后再次尝试
                setTimeout(function() {
                    if (!isLoginPage()) {
                        // 试图移除所有可能阻止跳转的页面内容
                        try {
                            document.body.innerHTML = '<p style="text-align:center;margin-top:100px;">正在跳转到登录页面...</p>';
                        } catch(e) {
                            // 忽略错误
                        }
                        
                        // 使用硬刷新
                        try {
                            window.location.href = '/admin/login/index?force=1&t=' + Date.now();
                            window.location.reload(true);
                        } catch(e) {
                            // 忽略错误
                        }
                    }
                }, 1000);
            }
        }, 1000);
        
        return true;
    }
    
    // 显示无操作超时通知
    function showInactivityNotice() {
        logDebug("显示长时间未操作通知");
        
        // 无论如何都清除标记，确保每次都能显示通知
        sessionStorage.removeItem(STORAGE_KEYS.INACTIVE_NOTICE_KEY);
        
        if (isSuperAdmin()) {
            logDebug("超级管理员无需显示通知");
            return false;
        }
        
        markInactiveNoticeShown();
        
        // 尝试调用窗口的显示函数
        if (typeof window.showForcedLogoutModal === 'function') {
            window.showForcedLogoutModal('inactivity');
            return true;
        }
        
        // 创建并显示弹窗
        return createAndShowModal({
            title: '<i class="fa fa-clock-o" style="margin-right:8px;"></i>会话已过期',
            message: '您已超过20分钟未操作，系统将在 <span id="inactivity-countdown" style="font-weight:bold;color:#f5a623;">3</span> 秒后退出登录',
            titleColor: '#f5a623',
            progressColor: 'linear-gradient(to right, #f5a623, #f5a623)',
            countdownId: 'inactivity-countdown'
        });
    }
    
    // 创建并显示通用弹窗 (简化版本，减少DOM操作)
    function createAndShowModal(options) {
        // 如果已经创建过modal元素，则重用它
        let modal = cache.modal;
        
        if (!modal) {
            // 创建弹窗容器
            modal = document.createElement('div');
            modal.id = 'account-status-modal';
            modal.style.cssText = 'position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.75); display:flex; justify-content:center; align-items:center; z-index:9999999;';
            
            // 创建弹窗内容容器
            const modalContent = document.createElement('div');
            modalContent.className = 'logout-message animated fadeInDown';
            modalContent.style.cssText = 'padding:25px; background:#fff; border-radius:10px; box-shadow:0 10px 25px rgba(0,0,0,0.5); text-align:center; width:400px; max-width:90%;';
            
            // 创建标题元素
            const titleEl = document.createElement('h3');
            titleEl.id = 'modal-title';
            titleEl.style.cssText = 'margin-top:0; font-size:20px; margin-bottom:15px;';
            
            // 创建消息元素
            const messageEl = document.createElement('p');
            messageEl.id = 'modal-message';
            messageEl.style.cssText = 'margin-bottom:20px; color:#333; font-size:16px; line-height:1.5;';
            
            // 创建进度条容器
            const progressContainer = document.createElement('div');
            progressContainer.style.cssText = 'height:8px; background:#f0f0f0; border-radius:4px; overflow:hidden; margin-bottom:15px;';
            
            // 创建进度条
            const progressBar = document.createElement('div');
            progressBar.id = 'modal-progress-bar';
            progressBar.className = 'status-progress-bar';
            progressBar.style.cssText = 'height:100%; width:0%; transition:width 3s linear;';
            
            // 组装弹窗
            progressContainer.appendChild(progressBar);
            modalContent.appendChild(titleEl);
            modalContent.appendChild(messageEl);
            modalContent.appendChild(progressContainer);
            modal.appendChild(modalContent);
            
            // 添加样式
            if (!document.getElementById('account-status-styles')) {
                const style = document.createElement('style');
                style.id = 'account-status-styles';
                style.textContent = `
                    @keyframes fadeInDown {
                        from { opacity: 0; transform: translate3d(0, -30px, 0); }
                        to { opacity: 1; transform: translate3d(0, 0, 0); }
                    }
                    .animated { animation-duration: 0.5s; animation-fill-mode: both; }
                    .fadeInDown { animation-name: fadeInDown; }
                `;
                document.head.appendChild(style);
            }
            
            // 将弹窗添加到页面
            document.body.appendChild(modal);
            
            // 缓存弹窗元素
            cache.modal = modal;
        }
        
        // 更新弹窗内容
        const titleEl = modal.querySelector('#modal-title');
        const messageEl = modal.querySelector('#modal-message');
        const progressBar = modal.querySelector('#modal-progress-bar');
        
        // 设置标题和颜色
        titleEl.innerHTML = options.title;
        titleEl.style.color = options.titleColor;
        
        // 设置消息内容
        messageEl.innerHTML = options.message;
        
        // 设置进度条颜色
        progressBar.style.background = options.progressColor;
        progressBar.style.width = '0%';
        
        // 显示弹窗
        modal.style.display = 'flex';
        document.body.classList.add('modal-active');
        
        // 启动进度条动画
        setTimeout(() => {
            progressBar.style.width = '100%';
        }, 50);
        
        // 倒计时逻辑
        let countdownEl = document.getElementById(options.countdownId);
        if (!countdownEl && messageEl) {
            // 如果找不到倒计时元素，找到第一个span元素
            const spans = messageEl.querySelectorAll('span');
            if (spans.length > 0) {
                countdownEl = spans[0];
            }
        }
        
        if (countdownEl) {
            let countdown = 3;
            const timer = setInterval(() => {
                countdown--;
                countdownEl.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(timer);
                    redirectToLogin();
                }
            }, 1000);
        } else {
            // 找不到倒计时元素，延迟3秒后重定向
            setTimeout(redirectToLogin, 3000);
        }
        
        return true;
    }
    
    // 检查账号是否被禁用
    function checkAccountStatus() {
        // 跳过不需要检查的情况
        if (isSuperAdmin() || hasShownDisabledNotice()) {
            return false;
        }
        
        const now = Date.now();
        const lastCheck = getLastCheckTime();
        
        // 距离上次检查还不到检查间隔时间，则跳过
        if (now - lastCheck < CONFIG.CHECK_INTERVAL) {
            return false;
        }
        
        // 发送请求检查账号状态
        fetchAccountStatus(isDisabled => {
            if (isDisabled) {
                showDisabledNotice();
            }
        });
    }
    
    // 实时检查账号状态（无间隔限制）
    function checkRealTimeAccountStatus() {
        // 跳过不需要检查的情况
        if (isSuperAdmin() || hasShownDisabledNotice()) {
            return false;
        }
        
        // 发送请求检查账号状态
        fetchAccountStatus(isDisabled => {
            if (isDisabled) {
                showDisabledNotice();
            }
        });
    }
    
    // 初始化WebSocket连接(如果支持)
    function initWebSocketCheck() {
        // 跳过不需要检查的情况
        if (isSuperAdmin()) {
            logDebug("超级管理员不启用WebSocket监控");
            return null;
        }
        
        // 检查浏览器是否支持WebSocket
        if (typeof WebSocket === 'undefined') {
            logDebug("浏览器不支持WebSocket，使用轮询检查");
            return null;
        }
        
        try {
            // 获取当前用户ID
            const userId = getCurrentUserId();
            if (!userId) {
                logDebug("无法获取用户ID，使用轮询检查");
                return null;
            }
            
            // 创建WebSocket连接
            const wsProtocol = window.location.protocol === 'https:' ? 'wss://' : 'ws://';
            const wsUrl = wsProtocol + window.location.host + '/admin/ws/status?userId=' + userId;
            
            const ws = new WebSocket(wsUrl);
            
            ws.onopen = function() {
                logDebug("WebSocket连接已建立，开始实时监控账号状态");
            };
            
            ws.onmessage = function(event) {
                try {
                    const data = JSON.parse(event.data);
                    if (data.type === 'status_check' && data.disabled === true) {
                        logDebug("WebSocket接收到账号禁用通知");
                        showDisabledNotice();
                    }
                } catch (e) {
                    logDebug("WebSocket消息解析错误: " + e.message);
                }
            };
            
            ws.onerror = function() {
                logDebug("WebSocket连接错误，切换到轮询模式");
                cache.ws = null;
                startPolling();
            };
            
            ws.onclose = function() {
                logDebug("WebSocket连接已关闭，切换到轮询模式");
                cache.ws = null;
                startPolling();
            };
            
            // 缓存WebSocket连接
            cache.ws = ws;
            
            // 添加页面卸载时关闭WebSocket
            window.addEventListener('beforeunload', function() {
                if (cache.ws) {
                    cache.ws.close();
                    cache.ws = null;
                }
            });
            
            return ws;
        } catch (e) {
            logDebug("WebSocket初始化错误: " + e.message);
            return null;
        }
    }
    
    // 启动轮询检查
    function startPolling() {
        // 如果是超级管理员，不启动轮询
        if (isSuperAdmin()) {
            return;
        }
        
        // 清除可能已经存在的定时器
        clearInterval(cache.timers.polling);
        clearInterval(cache.timers.realtime);
        clearInterval(cache.timers.forceCheck);
        
        // 首次执行检查
        checkAccountStatus();
        
        // 常规轮询
        cache.timers.polling = setInterval(function() {
            if (!hasShownDisabledNotice()) {
                checkAccountStatus();
            } else {
                // 如果已经显示了禁用通知，清除定时器
                clearInterval(cache.timers.polling);
            }
        }, CONFIG.CHECK_INTERVAL);
        
        // 实时快速轮询 (首次立即执行)
        checkRealTimeAccountStatus();
        cache.timers.realtime = setInterval(function() {
            if (!hasShownDisabledNotice()) {
                checkRealTimeAccountStatus();
            } else {
                // 如果已经显示了禁用通知，清除定时器
                clearInterval(cache.timers.realtime);
            }
        }, CONFIG.REALTIME_CHECK_INTERVAL);
        
        // 强制检查定时器 - 无论如何每30秒必定检查一次
        cache.timers.forceCheck = setInterval(function() {
            if (!hasShownDisabledNotice()) {
                // 跳过缓存直接检查
                forceCheckAccountStatus();
            } else {
                clearInterval(cache.timers.forceCheck);
            }
        }, CONFIG.FORCE_CHECK_INTERVAL);
    }
    
    // 停止所有定时器和监控
    function stopAllMonitors() {
        // 清除所有定时器
        clearTimeout(cache.timers.inactivity);
        clearInterval(cache.timers.polling);
        clearInterval(cache.timers.realtime);
        clearInterval(cache.timers.forceCheck);
        
        // 关闭WebSocket连接
        if (cache.ws) {
            cache.ws.close();
            cache.ws = null;
        }
    }
    
    // 清理资源，避免内存泄漏
    function cleanup() {
        stopAllMonitors();
        
        // 清除DOM引用
        cache.modal = null;
    }
    
    // 强制检查账号状态，跳过缓存直接发送请求
    function forceCheckAccountStatus() {
        // 跳过超级管理员检查
        if (isSuperAdmin()) {
            return false;
        }
        
        // 如果当前是在执行禁用用户操作，也跳过
        if (isDisablingOtherUser()) {
            return false;
        }
        
        // 直接发送请求
        $.ajax({
            url: '/admin/api/checkStatus',
            type: 'post',
            dataType: 'json',
            cache: false,
            data: {
                userId: getCurrentUserId(),
                timestamp: Date.now(),
                forceCheck: true,
                _token: getCsrfToken()
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            },
            success: function(res) {
                if ((res.code === 0 && res.disabled === true) || 
                    (res.code === 0 && res.status === 'disabled') ||
                    res.code === 401 || res.code === 403) {
                    
                    logDebug("强制检查发现账号已被禁用");
                    showDisabledNotice();
                }
            },
            error: function(xhr) {
                if (xhr.status === 401 || xhr.status === 403) {
                    logDebug("强制检查返回未授权状态");
                    showDisabledNotice();
                }
            }
        });
        
        return true;
    }
    
    // 初始化函数
    function init() {
        if (isLoginPage()) {
            // 如果在登录页面，清理存储并不执行监控
            cleanup();
            return;
        }
        
        // 加载超时配置
        loadTimeoutConfig();
        
        // 初始化用户角色数据
        initUserRoleData();
        
        // 检查当前用户是否是超级管理员，如果是则添加标记
        if (isSuperAdmin()) {
            addUserRoleMeta();
        }
        
        // 启动活动监控
        startActivityMonitoring();
        
        // 添加多标签页活动同步监听
        window.addEventListener('storage', function(e) {
            // 处理配置变更
            if (e.key === STORAGE_KEYS.SESSION_TIMEOUT_KEY) {
                try {
                    const newConfig = JSON.parse(e.newValue);
                    if (newConfig && newConfig.timeout) {
                        const newTimeout = parseInt(newConfig.timeout) * 60 * 1000;
                        // 如果超时设置发生变化，更新配置
                        if (CONFIG.MAX_INACTIVITY_TIME !== newTimeout) {
                            CONFIG.MAX_INACTIVITY_TIME = newTimeout;
                            logDebug("检测到超时配置变更: " + CONFIG.MAX_INACTIVITY_TIME + "ms");
                            // 重置计时器
                            resetInactivityTimer();
                        }
                    }
                } catch (ex) {
                    // 忽略解析错误
                }
            }
            
            if (e.key === STORAGE_KEYS.LAST_ACTIVITY_KEY_LOCAL) {
                // 从其他标签页更新活动时间
                const newTime = parseInt(e.newValue || lastActivityTime.toString());
                if (!isNaN(newTime)) {
                    // 只有当新时间比当前记录的时间更新时才更新
                    if (newTime > lastActivityTime) {
                        lastActivityTime = newTime;
                        resetInactivityTimer();
                    }
                }
            }
        });
        
        // 添加自定义事件监听
        window.addEventListener('user_activity_recorded', function(e) {
            if (e.detail && e.detail.time) {
                lastActivityTime = Math.max(lastActivityTime, e.detail.time);
                resetInactivityTimer();
            }
        });
        
        // 启动WebSocket检查（如果支持）
        initWebSocketCheck();
        
        // 如果WebSocket不可用或初始化失败，回退到轮询检查
        if (!cache.ws) {
            startPolling();
        }
        
        // 定期强制检查账号状态
        cache.timers.forceCheck = setInterval(forceCheckAccountStatus, CONFIG.FORCE_CHECK_INTERVAL);
        
        // 立即执行一次强制检查
        setTimeout(forceCheckAccountStatus, 1000);
        
        // 监听页面卸载事件，清理资源
        window.addEventListener('beforeunload', stopAllMonitors);
        
        logDebug("账号状态监控已初始化");
    }
    
    // 在窗口加载完成后自动初始化
    if (document.readyState === 'complete') {
        init();
    } else {
        window.addEventListener('load', init);
    }
    
    // 公开API，允许其他代码调用
    window.accountStatusMonitor = {
        // 核心功能
        checkStatus: checkAccountStatus,
        forceCheck: forceCheckAccountStatus,
        
        // 会话管理
        performLogout: performLogout,
        showTimeoutNotice: showTimeoutNotice,
        showDisabledNotice: showDisabledNotice,
        
        // 用户角色检查
        isSuperAdmin: isSuperAdmin,
        
        // 工具函数
        isLoginPage: isLoginPage,
        
        // 重新初始化函数
        reinit: init
    };
})(); 
// 最后修改时间: 2025-05-03
// 强制刷新缓存: 20250503104420
// 强制刷新缓存: 20250503104934
// 最后更新时间: 2025-05-03 10:51:48
