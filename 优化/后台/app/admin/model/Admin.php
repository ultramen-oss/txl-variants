<?php
namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    // 设置数据表名
    protected $name = 'admin';
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    // 用户类型常量
    const TYPE_SUPER_ADMIN = 'super_admin'; // 超级管理员
    const TYPE_ADMIN = 'admin';             // 普通管理员
    const TYPE_USER = 'user';               // 普通账号
    
    /**
     * 获取管理员信息
     * @param int $id 管理员ID
     * @return array|null 管理员信息
     */
    public function getAdminInfo($id)
    {
        return $this->where('id', $id)->find();
    }
    
    /**
     * 检查管理员类型
     * @param int $admin_id 管理员ID
     * @return string 管理员类型
     */
    public function getAdminType($admin_id)
    {
        $admin = $this->where('id', $admin_id)->find();
        if (!$admin) {
            return self::TYPE_USER; // 默认为普通账号
        }
        
        // 检查是否为超级管理员(admin)，ID为1或用户名为admin
        if ($admin_id == 1 || $admin['name'] == 'admin') {
            return self::TYPE_SUPER_ADMIN;
        }
        
        // 检查角色类型，如果是管理员角色(admin_cate_id为1或2)就是普通管理员
        if ($admin['admin_cate_id'] == 1 || $admin['admin_cate_id'] == 2) {
            return self::TYPE_ADMIN;
        }
        
        // 默认为普通账号
        return self::TYPE_USER;
    }
    
    /**
     * 检查管理员权限
     * @param int $admin_id 管理员ID
     * @param int $admin_cate_id 管理员角色ID
     * @param string $module 模块名
     * @param string $controller 控制器名
     * @param string $action 方法名
     * @return bool 是否有权限
     */
    public function checkPermissions($admin_id, $admin_cate_id, $module, $controller, $action)
    {
        // 超级管理员免检权限
        if ($admin_cate_id == 1 || $this->getAdminType($admin_id) == self::TYPE_SUPER_ADMIN) {
            return true;
        }
        
        // 查询管理员分类信息
        $permissions = \think\facade\Db::name('admin_cate')
                ->where('id', $admin_cate_id)
                ->value('permissions');
                
        // 将权限ID转换为数组
        $permissions = explode(',', $permissions);
        
        // 根据权限ID获取对应的URL
        $menu_urls = \think\facade\Db::name('admin_menu')
                ->where('id', 'in', $permissions)
                ->select();
                
        // 检查当前访问的URL是否在权限列表中
        foreach ($menu_urls as $p) {
            if (strtolower($p['module']) == strtolower($module) && 
                strtolower($p['controller']) == strtolower($controller) && 
                strtolower($p['function']) == strtolower($action)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 检查是否可以访问指定的用户数据
     * @param int $admin_id 管理员ID
     * @param string $invite_code 用户邀请码
     * @return bool 是否有权限查看
     */
    public function canAccessUserData($admin_id, $invite_code = null)
    {
        $admin_type = $this->getAdminType($admin_id);
        
        // 超级管理员和普通管理员可以查看所有用户数据
        if ($admin_type == self::TYPE_SUPER_ADMIN || $admin_type == self::TYPE_ADMIN) {
            return true;
        }
        
        // 普通账号只能查看通过自己邀请码邀请的用户数据
        $admin = $this->where('id', $admin_id)->find();
        if (!$admin) {
            return false;
        }
        
        // 如果未提供邀请码，则检查是否为自己的邀请码
        if ($invite_code === null) {
            return true; // 仅检查自己的信息
        }
        
        // 检查邀请码是否匹配
        return $admin['invite_code'] == $invite_code;
    }
    
    /**
     * 确保管理员数据中包含角色类型字段
     * @param array $admin 管理员数据
     * @return array 处理后的管理员数据
     */
    public function ensureRoleType($admin)
    {
        if (!isset($admin['role_type']) || empty($admin['role_type'])) {
            if ($admin['id'] == 1 || $admin['name'] == 'admin') {
                $admin['role_type'] = self::TYPE_SUPER_ADMIN;
            } else if ($admin['admin_cate_id'] == 1 || $admin['admin_cate_id'] == 2) {
                $admin['role_type'] = self::TYPE_ADMIN;
            } else {
                $admin['role_type'] = self::TYPE_USER;
            }
        }
        return $admin;
    }
}