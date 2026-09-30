// 夜间模式切换JS
$(document).ready(function() {
    // 检查本地存储的夜间模式设置
    var nightMode = localStorage.getItem('nightMode') === 'true';
    
    // 根据设置初始化夜间模式
    if (nightMode) {
        $('body').addClass('night-mode');
        $('#nightModeToggle').prop('checked', true);
    }
    
    // 监听切换开关事件
    $('#nightModeToggle').change(function() {
        if ($(this).is(':checked')) {
            $('body').addClass('night-mode');
            localStorage.setItem('nightMode', 'true');
        } else {
            $('body').removeClass('night-mode');
            localStorage.setItem('nightMode', 'false');
        }
        
        // 对所有已打开的iframe应用夜间模式
        applyNightModeToIframes();
    });
    
    // 初始化时对已存在的iframe应用夜间模式
    applyNightModeToIframes();
    
    // 创建一个MutationObserver来监听新iframe的添加
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // 检查是否有新的iframe被添加
                setTimeout(applyNightModeToIframes, 500);
            }
        });
    });
    
    // 配置观察器
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});

// 对所有iframe应用夜间模式
function applyNightModeToIframes() {
    var iframes = document.querySelectorAll('iframe');
    iframes.forEach(function(iframe) {
        try {
            // 检查iframe是否已加载且可访问
            if (iframe.contentWindow && iframe.contentDocument) {
                var iframeBody = iframe.contentDocument.body;
                if (iframeBody) {
                    var nightMode = localStorage.getItem('nightMode') === 'true';
                    
                    if (nightMode) {
                        iframeBody.classList.add('night-mode');
                        
                        // 添加夜间模式样式到iframe
                        var style = iframe.contentDocument.createElement('style');
                        style.id = 'night-mode-style';
                        style.innerHTML = `
                            body.night-mode {
                                background-color: #1a1a2e !important;
                                color: #e6e6e6 !important;
                            }
                            
                            .night-mode .layui-table {
                                background-color: #1f1f3a !important;
                                color: #e6e6e6 !important;
                            }
                            
                            .night-mode .layui-table td {
                                border-color: #333355 !important;
                            }
                            
                            .night-mode .layui-table tr:hover {
                                background-color: #292952 !important;
                            }
                            
                            .night-mode input, 
                            .night-mode select, 
                            .night-mode textarea {
                                background-color: #2c2c4a !important;
                                color: #e6e6e6 !important;
                                border-color: #3e3e5e !important;
                            }
                            
                            .night-mode .layui-layer-content {
                                background-color: #1f1f3a !important;
                                color: #e6e6e6 !important;
                            }
                            
                            .night-mode .layui-btn {
                                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3) !important;
                            }
                        `;
                        
                        // 如果已存在夜间模式样式表则移除
                        var existingStyle = iframe.contentDocument.getElementById('night-mode-style');
                        if (existingStyle) {
                            existingStyle.remove();
                        }
                        
                        iframe.contentDocument.head.appendChild(style);
                    } else {
                        iframeBody.classList.remove('night-mode');
                        
                        // 移除夜间模式样式表
                        var existingStyle = iframe.contentDocument.getElementById('night-mode-style');
                        if (existingStyle) {
                            existingStyle.remove();
                        }
                    }
                }
            }
        } catch (e) {
            console.log('无法修改iframe内容', e);
        }
    });
} 