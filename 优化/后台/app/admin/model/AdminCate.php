<?php

namespace app\admin\model;

use think\Model;

class AdminCate extends Model
{
    // 设置数据表名
    protected $name = 'admin_cate';
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    
    /**
     * 关联管理员表
     */
    public function admin()
    {
        return $this->hasMany('Admin', 'admin_cate_id', 'id');
    }
    
    /**
     * 获取角色列表
     */
    public function getCateList()
    {
        return $this->order('id', 'asc')->select();
    }
}