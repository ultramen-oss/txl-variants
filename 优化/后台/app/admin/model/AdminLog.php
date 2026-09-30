<?php

namespace app\admin\model;

use think\Model;

class AdminLog extends Model
{
    // 设置数据表名
    protected $name = 'admin_log';
    
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = false;
    
    /**
     * 关联管理员表
     */
    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id');
    }
    
    /**
     * 关联菜单表
     */
    public function menu()
    {
        return $this->belongsTo('AdminMenu', 'admin_menu_id', 'id');
    }
    
    /**
     * 获取日志列表
     * @param array $where 查询条件
     * @param int $limit 每页条数
     * @return \think\Paginator
     */
    public function getLogList($where = [], $limit = 15)
    {
        return $this->where($where)
            ->with(['admin', 'menu'])
            ->order('id', 'desc')
            ->paginate($limit);
    }
}