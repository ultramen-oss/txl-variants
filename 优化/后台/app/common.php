<?php


// 应用公共文件

/**
 * 添加日志
 * @param string $title 日志标题
 * @param string $content 日志内容
 * @return bool
 */
function addlog($title = '', $content = '')
{
    // 日志记录功能暂时禁用，以避免影响主要功能
    return true;
}

/**
 * 密码加密方法
 * @param string $password 要加密的密码
 * @return string 加密后的密码
 */
function password($password)
{
    return md5($password);
}

/**
 * 生成随机字符串
 * @param int $length 随机字符串长度
 * @param bool $numeric 是否为纯数字
 * @return string 随机字符串
 */
function random($length, $numeric = false)
{
    $seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
    $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
    if($numeric) {
        $hash = '';
    } else {
        $hash = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length--;
    }
    $max = strlen($seed) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $seed[mt_rand(0, $max)];
    }
    return $hash;
}