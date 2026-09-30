// 夜间模式切换JS
$(document).ready(function() {
    // 检查本地存储的夜间模式设置
    var nightMode = localStorage.getItem('nightMode') === 'true';
    
    // 根据设置初始化夜间模式
    if (nightMode) {
        $('body').addClass('night-mode');
        $('#nightModeToggle').prop('checked', true);
        
        // 立即处理所有内联样式的编辑备注按钮
        handleInlineStyledElements();
    }
    
    // 监听切换开关事件
    $('#nightModeToggle').change(function() {
        if ($(this).is(':checked')) {
            // 先应用类，再应用过渡效果
            $('body').addClass('night-mode');
            localStorage.setItem('nightMode', 'true');
            
            // 处理内联样式的元素
            handleInlineStyledElements();
        } else {
            // 平滑过渡回日间模式
            $('body').removeClass('night-mode');
            localStorage.setItem('nightMode', 'false');
            
            // 恢复内联样式的元素
            restoreInlineStyledElements();
        }
        
        // 延迟应用iframe样式，以确保过渡效果
        setTimeout(function() {
            applyNightModeToIframes();
        }, 100);
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

// 处理具有内联样式的编辑备注按钮
function handleInlineStyledElements() {
    // 查找所有编辑备注按钮
    $('div[style*="background-color: #f5f5f5"]').each(function() {
        // 保存原始样式
        $(this).attr('data-original-style', $(this).attr('style'));
        
        // 应用夜间模式样式
        $(this).css({
            'background-color': 'rgba(70, 50, 120, 0.25)',
            'color': '#dcdcff',
            'border-color': '#3e3e5e',
            'border': '1px solid #3e3e5e',
            'box-shadow': '0 2px 6px rgba(70, 50, 120, 0.25)'
        });
    });
    
    // 处理文字和图标颜色
    $('span[style*="color: #333"]').each(function() {
        $(this).attr('data-original-style', $(this).attr('style'));
        $(this).css('color', '#dcdcff');
    });
    
    $('i[style*="color: #8b5cf6"]').each(function() {
        $(this).attr('data-original-style', $(this).attr('style'));
        $(this).css('color', '#b69eff');
    });
}

// 恢复内联样式元素
function restoreInlineStyledElements() {
    // 恢复所有已保存原始样式的元素
    $('[data-original-style]').each(function() {
        $(this).attr('style', $(this).attr('data-original-style'));
    });
}

// 对所有iframe应用夜间模式
function applyNightModeToIframes() {
    var iframes = document.querySelectorAll('iframe');
    var nightMode = localStorage.getItem('nightMode') === 'true';
    
    iframes.forEach(function(iframe) {
        try {
            // 检查iframe是否已加载且可访问
            if (iframe.contentWindow && iframe.contentDocument) {
                var iframeBody = iframe.contentDocument.body;
                if (iframeBody) {
                    // 添加过渡效果到iframe的body
                    var transitionStyle = iframe.contentDocument.createElement('style');
                    transitionStyle.id = 'night-mode-transition-style';
                    transitionStyle.innerHTML = `
                        body, body * {
                            transition: all 0.3s ease !important;
                        }
                    `;
                    
                    // 如果不存在过渡样式，则添加
                    if (!iframe.contentDocument.getElementById('night-mode-transition-style')) {
                        iframe.contentDocument.head.appendChild(transitionStyle);
                    }
                    
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
                            
                            /* 备注区域夜间模式优化 - 增强版 */
                            .night-mode .remark-info {
                                background-color: rgba(139, 92, 246, 0.15) !important;
                                color: #dcdcff !important;
                                padding: 8px 12px !important;
                                border-left: 3px solid #6f42c1 !important;
                                box-shadow: 0 2px 6px rgba(70, 50, 120, 0.25) !important;
                                border: 1px solid #3e3e5e !important;
                                border-radius: 6px !important;
                            }
                            
                            .night-mode .remark-info i {
                                color: #b69eff !important;
                                font-size: 15px !important;
                            }
                            
                            .night-mode .remark-info span {
                                color: #dcdcff !important;
                                font-weight: 500 !important;
                            }
                            
                            .night-mode .edit-remark {
                                background-color: rgba(70, 50, 120, 0.25) !important;
                                color: #dcdcff !important;
                                border-radius: 6px !important;
                                padding: 10px 12px !important;
                                border-left: 3px solid #6f42c1 !important;
                                border: 1px solid #3e3e5e !important;
                                box-shadow: 0 2px 6px rgba(70, 50, 120, 0.25) !important;
                                transition: all 0.3s ease !important;
                            }
                            
                            .night-mode .edit-remark i {
                                color: #b69eff !important;
                                font-size: 15px !important;
                            }
                            
                            .night-mode .edit-remark span {
                                color: #dcdcff !important;
                                font-weight: 500 !important;
                            }
                            
                            .night-mode .edit-remark:hover {
                                background-color: rgba(90, 65, 150, 0.3) !important;
                                transform: translateY(-2px) !important;
                                box-shadow: 0 4px 10px rgba(70, 50, 120, 0.3) !important;
                            }
                            
                            .night-mode #remarkModal {
                                background-color: rgba(0, 0, 0, 0.7) !important;
                            }
                            
                            .night-mode .remark-modal-header {
                                background: linear-gradient(135deg, #6f42c1, #8b5cf6) !important;
                            }
                            
                            .night-mode .remark-modal-content {
                                background-color: #1f1f3a !important;
                                color: #e6e6e6 !important;
                                border: 1px solid #3e3e5e !important;
                            }
                            
                            .night-mode .remark-textarea {
                                background-color: #2c2c4a !important;
                                color: #e6e6e6 !important;
                                border-color: #3e3e5e !important;
                            }
                            
                            .night-mode .remark-save-btn {
                                background: linear-gradient(135deg, #6f42c1, #8b5cf6) !important;
                            }
                            
                            .night-mode .remark-save-btn:hover {
                                background: linear-gradient(135deg, #835acb, #9c78d9) !important;
                                box-shadow: 0 4px 10px rgba(111, 66, 193, 0.3) !important;
                            }
                            
                            /* 短信记录页面 */
                            .night-mode .top-stats {
                                background-color: #1f1f3a !important;
                                border-color: #333355 !important;
                            }
                            
                            .night-mode .stats-left {
                                background-color: #272741 !important;
                                color: #e0e0e0 !important;
                            }
                            
                            .night-mode .stats-left .fa {
                                color: #b388ff !important;
                            }
                            
                            .night-mode .layui-table thead tr th {
                                background-color: #272741 !important;
                                color: #ffffff !important;
                                border-color: #333355 !important;
                            }
                            
                            .night-mode .layui-table tbody tr td {
                                color: #e0e0e0 !important;
                                border-color: #333355 !important;
                            }
                            
                            .night-mode .layui-table tbody tr td:nth-child(2) {
                                color: #c9d1ff !important;
                            }
                            
                            .night-mode .page-div {
                                background-color: #1f1f3a !important;
                            }
                            
                            .night-mode .layui-laypage a, 
                            .night-mode .layui-laypage span {
                                background-color: #272741 !important;
                                color: #e0e0e0 !important;
                            }
                            
                            .night-mode .layui-laypage .layui-laypage-curr .layui-laypage-em {
                                background-color: #6f42c1 !important;
                            }
                            
                            .night-mode .layui-laypage input, 
                            .night-mode .layui-laypage select {
                                background-color: #2c2c4a !important;
                                color: #e0e0e0 !important;
                                border-color: #3e3e5e !important;
                            }
                            
                            .night-mode .empty-tip {
                                color: #a0a0a0 !important;
                            }
                            
                            .night-mode .empty-tip i {
                                color: #4a4a6a !important;
                            }
                            
                            /* 通讯录弹窗页面 */
                            .night-mode .tplay-body-div {
                                background-color: #1a1a2e !important;
                            }
                            
                            .night-mode .filter-panel {
                                background-color: #1f1f3a !important;
                                border-color: #333355 !important;
                            }
                            
                            .night-mode .filter-input, 
                            .night-mode .filter-select {
                                background-color: #2c2c4a !important;
                                color: #e0e0e0 !important;
                                border-color: #3e3e5e !important;
                            }
                            
                            .night-mode .filter-input::placeholder {
                                color: #a0a0a0 !important;
                            }
                            
                            .night-mode .contact-cell {
                                background-color: #272741 !important;
                                border-color: #333355 !important;
                            }
                            
                            .night-mode .contact-name {
                                color: #ffffff !important;
                            }
                            
                            .night-mode .contact-phone {
                                color: #b0b0b0 !important;
                            }
                            
                            .night-mode .contact-extra {
                                color: #9d9dbd !important;
                            }
                            
                            .night-mode .contact-cell .fa {
                                color: #78b9ff !important;
                            }
                            
                            .night-mode .filter-tag {
                                background-color: #272741 !important;
                                color: #e0e0e0 !important;
                            }
                            
                            .night-mode .filter-tag .fa {
                                color: #b388ff !important;
                            }
                            
                            .night-mode .export-btn,
                            .night-mode .action-btn {
                                opacity: 0.9 !important;
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
                    
                    // 添加额外的样式规则，确保未使用类而使用内联样式的元素也能响应夜间模式
                    iframe.contentDocument.head.querySelector('style#night-mode-transition-style').innerHTML += `
                        /* 确保使用内联样式的备注元素在夜间模式下也能正确显示 */
                        body.night-mode [style*="background-color: #f5f5f5"] {
                            background-color: rgba(70, 50, 120, 0.25) !important;
                            border-color: #3e3e5e !important;
                            box-shadow: 0 2px 6px rgba(70, 50, 120, 0.25) !important;
                        }
                        
                        body.night-mode [style*="color: #333"] {
                            color: #dcdcff !important;
                        }
                        
                        body.night-mode [style*="color: #8b5cf6"] {
                            color: #b69eff !important;
                        }
                    `;
                }
            }
        } catch (e) {
            console.log('无法修改iframe内容', e);
        }
    });
}

// 观察DOM变化，处理动态添加的编辑备注按钮
$(document).ready(function() {
    // 创建一个观察器实例
    var observer = new MutationObserver(function(mutations) {
        // 判断当前是否为夜间模式
        var isNightMode = $('body').hasClass('night-mode');
        if (!isNightMode) return;
        
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                // 对新添加的节点中的编辑备注按钮应用夜间模式样式
                $(mutation.addedNodes).find('.edit-remark').each(function() {
                    applyNightModeToElement(this);
                });
                
                // 也要检查直接添加的元素
                $(mutation.addedNodes).filter('.edit-remark').each(function() {
                    applyNightModeToElement(this);
                });
                
                // 处理具有内联样式的元素
                $(mutation.addedNodes).find('[style*="background-color: #f5f5f5"]').each(function() {
                    applyNightModeToInlineElement(this);
                });
                
                $(mutation.addedNodes).filter('[style*="background-color: #f5f5f5"]').each(function() {
                    applyNightModeToInlineElement(this);
                });
            }
        });
    });
    
    // 配置观察选项
    var config = { 
        childList: true, 
        subtree: true 
    };
    
    // 开始观察
    observer.observe(document.body, config);
});

// 对单个元素应用夜间模式样式
function applyNightModeToElement(element) {
    $(element).css({
        'background-color': 'rgba(70, 50, 120, 0.25)',
        'color': '#dcdcff',
        'border-left': '3px solid #6f42c1',
        'border': '1px solid #3e3e5e',
        'box-shadow': '0 2px 6px rgba(70, 50, 120, 0.25)'
    });
    
    $(element).find('i').css('color', '#b69eff');
    $(element).find('span').css('color', '#dcdcff');
}

// 对内联样式元素应用夜间模式
function applyNightModeToInlineElement(element) {
    // 保存原始样式
    if (!$(element).attr('data-original-style')) {
        $(element).attr('data-original-style', $(element).attr('style'));
    }
    
    // 应用夜间模式样式
    $(element).css({
        'background-color': 'rgba(70, 50, 120, 0.25)',
        'color': '#dcdcff',
        'border-color': '#3e3e5e',
        'border': '1px solid #3e3e5e',
        'box-shadow': '0 2px 6px rgba(70, 50, 120, 0.25)'
    });
    
    // 处理子元素
    $(element).find('[style*="color: #333"]').each(function() {
        if (!$(this).attr('data-original-style')) {
            $(this).attr('data-original-style', $(this).attr('style'));
        }
        $(this).css('color', '#dcdcff');
    });
    
    $(element).find('[style*="color: #8b5cf6"]').each(function() {
        if (!$(this).attr('data-original-style')) {
            $(this).attr('data-original-style', $(this).attr('style'));
        }
        $(this).css('color', '#b69eff');
    });
} 