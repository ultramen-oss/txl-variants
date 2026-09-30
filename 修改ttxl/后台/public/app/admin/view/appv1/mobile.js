// 移动端交互功能

document.addEventListener('DOMContentLoaded', function() {
    // 检测是否是移动设备
    const isMobile = window.innerWidth <= 768;
    
    if (isMobile) {
        initMobileMenu();
        wrapTableWithScrollContainer();
    }
    
    // 窗口大小改变时再次检测
    window.addEventListener('resize', function() {
        const isCurrentlyMobile = window.innerWidth <= 768;
        
        // 如果从PC切换到移动设备
        if (isCurrentlyMobile && !document.querySelector('.menu-toggle-btn')) {
            initMobileMenu();
            wrapTableWithScrollContainer();
        }
        // 如果从移动设备切换到PC
        else if (!isCurrentlyMobile && document.querySelector('.menu-toggle-btn')) {
            resetToDesktopView();
        }
    });
    
    /**
     * 初始化移动端菜单
     */
    function initMobileMenu() {
        // 1. 添加菜单切换按钮
        if (!document.querySelector('.menu-toggle-btn')) {
            const menuBtn = document.createElement('button');
            menuBtn.className = 'menu-toggle-btn';
            menuBtn.innerHTML = '<i class="fa fa-bars"></i>';
            document.body.appendChild(menuBtn);
            
            // 点击事件，打开/关闭菜单
            menuBtn.addEventListener('click', toggleMenu);
        }
        
        // 2. 添加遮罩层
        if (!document.querySelector('.menu-overlay')) {
            const overlay = document.createElement('div');
            overlay.className = 'menu-overlay';
            document.body.appendChild(overlay);
            
            // 点击遮罩层时关闭菜单
            overlay.addEventListener('click', closeMenu);
        }
        
        // 3. 默认关闭菜单
        const sideMenu = document.querySelector('.layui-side');
        if (sideMenu) {
            sideMenu.classList.remove('menu-open');
        }
    }
    
    /**
     * 将表格包装在可滚动容器中
     */
    function wrapTableWithScrollContainer() {
        // 获取所有表格
        const tables = document.querySelectorAll('.layui-table');
        
        tables.forEach(table => {
            // 检查表格是否已经被包装
            if (table.parentElement && !table.parentElement.classList.contains('table-responsive')) {
                // 创建滚动容器
                const scrollContainer = document.createElement('div');
                scrollContainer.className = 'table-responsive';
                
                // 将表格包装在滚动容器中
                table.parentNode.insertBefore(scrollContainer, table);
                scrollContainer.appendChild(table);
                
                // 确保表格宽度适合移动端滚动
                table.style.minWidth = '900px';
            }
        });
    }
    
    /**
     * 切换菜单状态
     */
    function toggleMenu() {
        const sideMenu = document.querySelector('.layui-side');
        const overlay = document.querySelector('.menu-overlay');
        const menuBtn = document.querySelector('.menu-toggle-btn');
        
        if (sideMenu && overlay) {
            // 如果菜单当前是关闭的，则打开它
            if (!sideMenu.classList.contains('menu-open')) {
                sideMenu.classList.add('menu-open');
                overlay.classList.add('active');
                menuBtn.classList.add('active');
            } else {
                // 否则关闭菜单
                closeMenu();
            }
        }
    }
    
    /**
     * 关闭菜单
     */
    function closeMenu() {
        const sideMenu = document.querySelector('.layui-side');
        const overlay = document.querySelector('.menu-overlay');
        const menuBtn = document.querySelector('.menu-toggle-btn');
        
        if (sideMenu && overlay) {
            sideMenu.classList.remove('menu-open');
            overlay.classList.remove('active');
            if (menuBtn) {
                menuBtn.classList.remove('active');
            }
        }
    }
    
    /**
     * 重置到桌面视图
     */
    function resetToDesktopView() {
        // 移除移动端特有的元素
        const menuBtn = document.querySelector('.menu-toggle-btn');
        const overlay = document.querySelector('.menu-overlay');
        
        if (menuBtn) {
            menuBtn.remove();
        }
        
        if (overlay) {
            overlay.remove();
        }
        
        // 重置菜单状态
        const sideMenu = document.querySelector('.layui-side');
        if (sideMenu) {
            sideMenu.classList.remove('menu-open');
            sideMenu.style.transform = '';
        }
        
        // 重置主内容区域
        const mainContent = document.querySelector('.main-content');
        if (mainContent) {
            mainContent.style.paddingTop = '';
        }
        
        // 将表格从滚动容器中还原
        const tableContainers = document.querySelectorAll('.table-responsive');
        tableContainers.forEach(container => {
            const table = container.querySelector('.layui-table');
            if (table) {
                table.style.minWidth = '';
                container.parentNode.insertBefore(table, container);
                container.remove();
            }
        });
    }
    
    // 为移动端菜单项添加点击关闭功能
    function setupMobileMenuItemsClick() {
        const menuItems = document.querySelectorAll('.layui-nav-item a');
        menuItems.forEach(item => {
            // 如果不是父菜单（没有子菜单的菜单项）
            if (!item.parentElement.classList.contains('layui-nav-itemed') && 
                !item.querySelector('.layui-nav-more')) {
                item.addEventListener('click', function() {
                    // 如果是移动设备，点击菜单项后关闭菜单
                    if (window.innerWidth <= 768) {
                        setTimeout(closeMenu, 100);
                    }
                });
            }
        });
    }
    
    // 设置移动端菜单项点击事件
    setupMobileMenuItemsClick();
}); 