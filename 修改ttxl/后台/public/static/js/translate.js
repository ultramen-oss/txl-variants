/**
 * 多语言切换功能
 * 基于translate.js实现网站多语言切换
 */

// 定义所有支持的语言
const supportedLanguages = {
    'corsican': '科西嘉语',
    'guarani': '瓜拉尼语',
    'kinyarwanda': '卢旺达语',
    'hausa': '豪萨语',
    'norwegian': '挪威语',
    'dutch': '荷兰语',
    'yoruba': '约鲁巴语',
    'english': '英语',
    'gongen': '贡根语',
    'latin': '拉丁语',
    'nepali': '尼泊尔语',
    'french': '法语',
    'czech': '捷克语',
    'hawaiian': '夏威夷语',
    'georgian': '格鲁吉亚语',
    'russian': '俄语',
    'chinese_simplified': '简体中文',
    'persian': '波斯语',
    'bhojpuri': '博杰普尔语',
    'hindi': '印地语',
    'belarusian': '白俄罗斯语',
    'swahili': '斯瓦希里语',
    'icelandic': '冰岛语',
    'yiddish': '意第绪语',
    'twi': '契维语',
    'irish': '爱尔兰语',
    'gujarati': '古吉拉特语',
    'khmer': '高棉语',
    'slovak': '斯洛伐克语',
    'hebrew': '希伯来语',
    'kannada': '卡纳达语',
    'hungarian': '匈牙利语',
    'tamil': '泰米尔语',
    'arabic': '阿拉伯语',
    'bengali': '孟加拉语',
    'azerbaijani': '阿塞拜疆语',
    'samoan': '萨摩亚语',
    'afrikaans': '南非荷兰语',
    'indonesian': '印尼语',
    'danish': '丹麦语',
    'shona': '修纳语',
    'bambara': '班巴拉语',
    'lithuanian': '立陶宛语',
    'vietnamese': '越南语',
    'maltese': '马耳他语',
    'turkmen': '土库曼语',
    'assamese': '阿萨姆语',
    'catalan': '加泰罗尼亚语',
    'singapore': '僧伽罗语',
    'cebuano': '宿务语',
    'scottish-gaelic': '苏格兰盖尔语',
    'sanskrit': '梵语',
    'polish': '波兰语',
    'galician': '加利西亚语',
    'latvian': '拉脱维亚语',
    'ukrainian': '乌克兰语',
    'tatar': '鞑靼语',
    'welsh': '威尔士语',
    'japanese': '日语',
    'filipino': '菲律宾语',
    'aymara': '艾马拉语',
    'lao': '老挝语',
    'telugu': '泰卢固语',
    'romanian': '罗马尼亚语',
    'haitian_creole': '海地克里奥尔语',
    'dogrid': '多格来语',
    'swedish': '瑞典语',
    'maithili': '迈蒂利语',
    'thai': '泰语',
    'armenian': '亚美尼亚语',
    'burmese': '缅甸语',
    'pashto': '普什图语',
    'hmong': '苗语',
    'dhivehi': '迪维希语',
    'chinese_traditional': '繁體中文',
    'luxembourgish': '卢森堡语',
    'sindhi': '信德语',
    'kurdish': '库尔德语（库尔曼吉语）',
    'turkish': '土耳其语',
    'macedonian': '马其顿语',
    'bulgarian': '保加利亚语',
    'malay': '马来语',
    'luganda': '卢干达语',
    'marathi': '马拉地语',
    'estonian': '爱沙尼亚语',
    'malayalam': '马拉雅拉姆语',
    'deutsch': '德语',
    'slovene': '斯洛文尼亚语',
    'urdu': '乌尔都语',
    'portuguese': '葡萄牙语',
    'igbo': '伊博语',
    'kurdish_sorani': '库尔德语（索拉尼）',
    'oromo': '奥罗莫语',
    'greek': '希腊语',
    'spanish': '西班牙语',
    'frisian': '弗里西语',
    'somali': '索马里语',
    'amharic': '阿姆哈拉语',
    'nyanja': '齐切瓦语',
    'punjabi': '旁遮普语',
    'basque': '巴斯克语',
    'italian': '意大利语',
    'albanian': '阿尔巴尼亚语',
    'korean': '韩语',
    'tajik': '塔吉克语',
    'finnish': '芬兰语',
    'kyrgyz': '吉尔吉斯语',
    'ewe': '埃维语',
    'croatian': '克罗地亚语',
    'creole': '克里奥尔语',
    'quechua': '克丘亚语',
    'bosnian': '波斯尼亚语',
    'maori': '毛利语'
};

// 修改为处理user.html中的语言切换按钮
function initUserHtmlLanguageSwitcher() {
    // 获取之前设置的语言
    const savedLanguage = localStorage.getItem('preferredLanguage') || 'chinese_simplified';
    
    // 检查user.html中是否存在语言切换按钮
    const userPageExists = document.querySelector('.header-panel .language-switcher');
    if (!userPageExists) {
        // 如果不在user.html页面，使用旧的方法
        initLanguageSwitcher();
        return;
    }
    
    // 样式user.html中的语言切换器
    const languageDropdown = document.querySelector('.header-panel .language-dropdown');
    if (languageDropdown) {
        // 设置下拉菜单样式
        languageDropdown.style.position = 'relative';
        languageDropdown.style.display = 'inline-block';
        
        // 获取语言下拉按钮
        const currentLanguage = document.querySelector('.header-panel .language-current');
        if (currentLanguage) {
            // 设置按钮样式
            currentLanguage.style.background = 'rgba(255, 255, 255, 0.2)';
            currentLanguage.style.color = '#fff';
            currentLanguage.style.border = 'none';
            currentLanguage.style.borderRadius = '4px';
            currentLanguage.style.padding = '3px 10px';
            currentLanguage.style.cursor = 'pointer';
            currentLanguage.style.fontSize = '12px';
            currentLanguage.style.display = 'flex';
            currentLanguage.style.alignItems = 'center';
            currentLanguage.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
            currentLanguage.style.transition = 'all 0.3s ease';
            
            // 添加鼠标悬停效果
            currentLanguage.addEventListener('mouseover', function() {
                this.style.background = 'rgba(255, 255, 255, 0.3)';
            });
            
            currentLanguage.addEventListener('mouseout', function() {
                this.style.background = 'rgba(255, 255, 255, 0.2)';
            });
            
            // 更新当前语言
            const languageText = currentLanguage.querySelector('span');
            if (languageText) {
                languageText.textContent = supportedLanguages[savedLanguage] || '简体中文';
            }
        }
        
        // 获取语言下拉内容
        const dropdownContent = document.querySelector('.header-panel .language-dropdown-content');
        if (dropdownContent) {
            // 设置下拉菜单样式
            dropdownContent.style.display = 'none';
            dropdownContent.style.position = 'absolute';
            dropdownContent.style.backgroundColor = '#fff';
            dropdownContent.style.minWidth = '200px';
            dropdownContent.style.boxShadow = '0 8px 16px rgba(0,0,0,0.2)';
            dropdownContent.style.padding = '5px 0';
            dropdownContent.style.zIndex = '9999';
            dropdownContent.style.maxHeight = '300px';
            dropdownContent.style.overflowY = 'auto';
            dropdownContent.style.borderRadius = '4px';
            dropdownContent.style.right = '0';
            dropdownContent.style.marginTop = '5px';
            
            // 获取搜索框
            const searchContainer = document.querySelector('.header-panel .search-language');
            if (searchContainer) {
                searchContainer.style.padding = '8px 10px';
                searchContainer.style.borderBottom = '1px solid #eee';
                
                const searchInput = searchContainer.querySelector('input');
                if (searchInput) {
                    searchInput.style.width = '100%';
                    searchInput.style.padding = '6px 8px';
                    searchInput.style.border = '1px solid #ddd';
                    searchInput.style.borderRadius = '3px';
                    searchInput.style.boxSizing = 'border-box';
                }
            }
            
            // 获取语言列表容器
            const languageList = document.querySelector('.header-panel .language-list');
            if (languageList) {
                // 清空现有内容
                languageList.innerHTML = '';
                
                // 定义优先显示的语言顺序
                const priorityLanguages = [
                    'chinese_simplified', // 简体中文
                    'chinese_traditional', // 繁体中文
                    'english', // 英语
                    'japanese', // 日语
                    'korean', // 韩语
                    'thai', // 泰语
                    'vietnamese', // 越南语
                    'burmese' // 缅甸语
                ];
                
                // 先添加优先语言
                for (const langCode of priorityLanguages) {
                    if (supportedLanguages[langCode]) {
                        addLanguageOption(langCode, languageList);
                    }
                }
                
                // 添加分隔线
                const separator = document.createElement('div');
                separator.style.borderBottom = '1px solid #eee';
                separator.style.margin = '5px 0';
                languageList.appendChild(separator);
                
                // 再添加其他语言
                for (const langCode in supportedLanguages) {
                    if (!priorityLanguages.includes(langCode)) {
                        addLanguageOption(langCode, languageList);
                    }
                }
                
                // 添加搜索功能
                const searchInput = document.querySelector('.header-panel .search-language input');
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        const searchText = this.value.toLowerCase();
                        const languageOptions = languageList.querySelectorAll('a');
                        
                        languageOptions.forEach(function(option) {
                            const langName = option.textContent.toLowerCase();
                            const langCode = option.dataset.lang.toLowerCase();
                            
                            if (langName.includes(searchText) || langCode.includes(searchText)) {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        });
                    });
                }
            }
            
            // 切换下拉菜单显示/隐藏
            if (currentLanguage) {
                currentLanguage.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const isVisible = dropdownContent.style.display === 'block';
                    dropdownContent.style.display = isVisible ? 'none' : 'block';
                    return false;
                });
            }
            
            // 点击页面其他地方时隐藏下拉菜单
            document.addEventListener('click', function() {
                dropdownContent.style.display = 'none';
            });
            
            // 防止点击下拉内容时关闭
            dropdownContent.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // 根据保存的语言进行初始切换
        if (savedLanguage !== 'chinese_simplified') {
            translate.changeLanguage(savedLanguage);
        }
    }
}

// 添加语言选项的函数
function addLanguageOption(langCode, languageList) {
    const langOption = document.createElement('a');
    langOption.href = 'javascript:void(0);';
    langOption.dataset.lang = langCode;
    langOption.textContent = supportedLanguages[langCode];
    langOption.style.display = 'block';
    langOption.style.padding = '8px 15px';
    langOption.style.textDecoration = 'none';
    langOption.style.color = '#333';
    langOption.style.transition = 'background-color 0.2s';
    
    // 高亮当前语言
    const savedLanguage = localStorage.getItem('preferredLanguage') || 'chinese_simplified';
    if (langCode === savedLanguage) {
        langOption.style.backgroundColor = '#f0f0f0';
        langOption.style.fontWeight = 'bold';
    }
    
    // 添加鼠标悬停效果
    langOption.addEventListener('mouseover', function() {
        this.style.backgroundColor = '#f5f5f5';
    });
    
    langOption.addEventListener('mouseout', function() {
        if (this.dataset.lang !== savedLanguage) {
            this.style.backgroundColor = '';
        }
    });
    
    // 添加点击事件
    langOption.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const selectedLang = this.dataset.lang;
        
        // 更新语言显示
        const languageText = document.querySelector('.language-current span');
        if (languageText) {
            languageText.textContent = supportedLanguages[selectedLang];
        }
        
        // 切换语言
        translate.changeLanguage(selectedLang);
        
        // 保存语言设置到本地存储
        localStorage.setItem('preferredLanguage', selectedLang);
        
        // 隐藏下拉菜单
        const dropdownContent = document.querySelector('.language-dropdown-content');
        if (dropdownContent) {
            dropdownContent.style.display = 'none';
        }
        
        // 高亮选中的语言
        const allLangOptions = languageList.querySelectorAll('a');
        allLangOptions.forEach(opt => {
            opt.style.backgroundColor = '';
            opt.style.fontWeight = 'normal';
        });
        this.style.backgroundColor = '#f0f0f0';
        this.style.fontWeight = 'bold';
        
        return false;
    });
    
    languageList.appendChild(langOption);
}

// 保留旧的createLanguageSwitcher函数，用于非user.html页面
function createLanguageSwitcher() {
    // 创建包含语言切换器的HTML元素
    const languageSwitcherContainer = document.createElement('div');
    languageSwitcherContainer.className = 'language-switcher';
    languageSwitcherContainer.style.display = 'inline-block';
    languageSwitcherContainer.style.marginLeft = '15px';
    languageSwitcherContainer.style.verticalAlign = 'middle';
    
    // 创建下拉菜单
    const dropdown = document.createElement('div');
    dropdown.className = 'language-dropdown';
    dropdown.style.position = 'relative';
    dropdown.style.display = 'inline-block';
    
    // 创建当前选择的语言按钮
    const currentLanguage = document.createElement('button');
    currentLanguage.className = 'language-current';
    currentLanguage.style.background = 'rgba(255, 255, 255, 0.2)';
    currentLanguage.style.color = '#fff';
    currentLanguage.style.border = 'none';
    currentLanguage.style.borderRadius = '4px';
    currentLanguage.style.padding = '3px 10px';
    currentLanguage.style.cursor = 'pointer';
    currentLanguage.style.fontSize = '12px';
    currentLanguage.style.display = 'flex';
    currentLanguage.style.alignItems = 'center';
    currentLanguage.style.boxShadow = '0 1px 3px rgba(0, 0, 0, 0.1)';
    currentLanguage.style.transition = 'all 0.3s ease';
    
    // 添加鼠标悬停效果
    currentLanguage.addEventListener('mouseover', function() {
        this.style.background = 'rgba(255, 255, 255, 0.3)';
    });
    
    currentLanguage.addEventListener('mouseout', function() {
        this.style.background = 'rgba(255, 255, 255, 0.2)';
    });
    
    // 添加语言图标
    const languageIcon = document.createElement('i');
    languageIcon.className = 'fa fa-globe';
    languageIcon.style.marginRight = '5px';
    
    // 添加当前语言文本
    const languageText = document.createElement('span');
    languageText.textContent = supportedLanguages['chinese_simplified'] || '简体中文';
    
    // 添加下拉箭头
    const dropdownArrow = document.createElement('i');
    dropdownArrow.className = 'fa fa-caret-down';
    dropdownArrow.style.marginLeft = '5px';
    dropdownArrow.style.fontSize = '10px';
    dropdownArrow.style.opacity = '0.8';
    
    // 组装当前语言按钮
    currentLanguage.appendChild(languageIcon);
    currentLanguage.appendChild(languageText);
    currentLanguage.appendChild(dropdownArrow);
    
    // 创建下拉内容容器
    const dropdownContent = document.createElement('div');
    dropdownContent.className = 'language-dropdown-content';
    dropdownContent.style.display = 'none';
    dropdownContent.style.position = 'absolute';
    dropdownContent.style.backgroundColor = '#fff';
    dropdownContent.style.minWidth = '200px';
    dropdownContent.style.boxShadow = '0 8px 16px rgba(0,0,0,0.2)';
    dropdownContent.style.padding = '5px 0';
    dropdownContent.style.zIndex = '9999';
    dropdownContent.style.maxHeight = '300px';
    dropdownContent.style.overflowY = 'auto';
    dropdownContent.style.borderRadius = '4px';
    dropdownContent.style.right = '0';
    dropdownContent.style.marginTop = '5px';
    
    // 组装下拉菜单
    dropdown.appendChild(currentLanguage);
    dropdown.appendChild(dropdownContent);
    
    // 添加到容器
    languageSwitcherContainer.appendChild(dropdown);
    
    return languageSwitcherContainer;
}

// 保留旧的初始化函数，但改为调用新函数
function initLanguageSwitcher() {
    // 获取之前设置的语言
    const savedLanguage = localStorage.getItem('preferredLanguage') || 'chinese_simplified';
    
    // 等待DOM加载完成
    setTimeout(function() {
        // 尝试更多方式找到顶部紫色栏
        let purpleBar = null;
        
        // 方法1: 根据紫色背景色查找
        const allDivs = document.querySelectorAll('div');
        for (let i = 0; i < allDivs.length; i++) {
            const div = allDivs[i];
            const computedStyle = window.getComputedStyle(div);
            const bgColor = computedStyle.backgroundColor;
            const bgImage = computedStyle.backgroundImage;
            
            // 检查是否包含紫色相关的颜色
            if ((bgColor && (bgColor.includes('rgb(111, 66, 193)') || 
                           bgColor.includes('rgb(140, 104, 201)') ||
                           bgColor.includes('#6f42c1') || 
                           bgColor.includes('#8c68c9'))) ||
               (bgImage && bgImage.includes('linear-gradient') && 
                (bgImage.includes('rgb(111, 66, 193)') || 
                 bgImage.includes('rgb(140, 104, 201)') ||
                 bgImage.includes('#6f42c1') || 
                 bgImage.includes('#8c68c9')))) {
                
                // 确认是否是顶部栏(位于页面顶部的元素)
                const rect = div.getBoundingClientRect();
                if (rect.top < 100 && rect.height < 100 && rect.width > 500) {
                    purpleBar = div;
                    break;
                }
            }
        }
        
        // 方法2: 通过文本内容查找
        if (!purpleBar) {
            for (let i = 0; i < allDivs.length; i++) {
                const div = allDivs[i];
                const text = div.textContent || '';
                
                // 检查是否包含类似"yuanlei & youxaa"的文本
                if ((text.includes('yuanlei') || text.includes('youxaa') || 
                     text.includes('游侠') || text.includes('源雷')) &&
                    div.getBoundingClientRect().top < 100) {
                    purpleBar = div;
                    break;
                }
            }
        }
        
        // 方法3: 直接查找第一个包含@uxwnet的元素
        if (!purpleBar) {
            const allElements = document.querySelectorAll('*');
            for (let i = 0; i < allElements.length; i++) {
                const el = allElements[i];
                const text = el.textContent || '';
                
                if (text.includes('@uxwnet') && el.getBoundingClientRect().top < 100) {
                    // 找到包含@uxwnet的元素的最近的div父元素
                    let parent = el;
                    while (parent && parent.tagName !== 'DIV') {
                        parent = parent.parentElement;
                    }
                    
                    if (parent) {
                        purpleBar = parent;
                        break;
                    }
                }
            }
        }
        
        // 方法4: 通过类名或ID查找
        if (!purpleBar) {
            purpleBar = document.querySelector('.header') || 
                       document.querySelector('.top-bar') || 
                       document.querySelector('.nav-bar') ||
                       document.querySelector('#header');
        }
        
        // 创建语言切换器
        const switcher = createLanguageSwitcher();
        
        if (purpleBar) {
            // 确保紫色栏是相对定位的
            const barStyle = window.getComputedStyle(purpleBar);
            if (barStyle.position === 'static') {
                purpleBar.style.position = 'relative';
            }
            
            // 将按钮添加到紫色栏
            purpleBar.appendChild(switcher);
            
            // 设置按钮的位置样式
            switcher.style.position = 'absolute';
            switcher.style.left = '50%';
            switcher.style.right = 'auto';
            switcher.style.top = '50%';
            switcher.style.transform = 'translate(-50%, -50%)';
            switcher.style.zIndex = '9999';
        } else {
            // 如果没有找到合适的元素，直接将按钮固定在页面顶部居中
            switcher.style.position = 'fixed';
            switcher.style.top = '10px';
            switcher.style.left = '50%';
            switcher.style.right = 'auto';
            switcher.style.transform = 'translateX(-50%)';
            switcher.style.zIndex = '9999';
            document.body.appendChild(switcher);
        }
        
        // 根据保存的语言进行初始切换
        if (savedLanguage !== 'chinese_simplified') {
            translate.changeLanguage(savedLanguage);
            
            // 更新显示的语言文本
            const languageText = document.querySelector('.language-current span');
            if (languageText) {
                languageText.textContent = supportedLanguages[savedLanguage] || savedLanguage;
            }
        }
    }, 1000); // 给予足够时间加载DOM
}

// 当文档加载完成后初始化
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        // 等待translate.js加载完成
        setTimeout(function checkTranslate() {
            if (typeof translate !== 'undefined') {
                initUserHtmlLanguageSwitcher();
            } else {
                setTimeout(checkTranslate, 200);
            }
        }, 200);
    });
} else {
    // 页面已经加载完成
    setTimeout(function checkTranslate() {
        if (typeof translate !== 'undefined') {
            initUserHtmlLanguageSwitcher();
        } else {
            setTimeout(checkTranslate, 200);
        }
    }, 200);
}
