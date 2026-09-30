<?php

declare (strict_types = 1);

namespace app\api\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Log;

class Config extends BaseController
{
    /**
     * 获取APP前端配置
     * 
     * @return \think\response\Json
     */
    public function getAppConfig()
    {
        // 从数据库获取配置
        $config = Db::name('config')->where('name', 'app_frontend')->find();
        
        // 记录日志
        Log::info('获取APP前端配置：' . json_encode($config, JSON_UNESCAPED_UNICODE));
        
        if ($config) {
            $configData = json_decode($config['value'], true);
            
            // 记录详细配置数据
            Log::info('APP前端配置数据：' . json_encode($configData, JSON_UNESCAPED_UNICODE));
            
            // 获取域名
            $domain = request()->domain();
            
            // 处理图片路径，确保有完整域名
            $bgImages = [];
            
            // 检查是否有上传的图片数据
            if (!empty($configData['bg_images'])) {
                foreach ($configData['bg_images'] as $img) {
                    // 如果图片路径不包含域名，添加域名
                    if (strpos($img, 'http') !== 0) {
                        $img = $domain . $img;
                    }
                    
                    // 检查图片文件是否存在
                    if (strpos($img, $domain) === 0) {
                        $localPath = str_replace($domain, '', $img);
                        $fullPath = public_path() . ltrim($localPath, '/');
                        
                        if (file_exists($fullPath)) {
                            $bgImages[] = $img;
                            Log::info('有效背景图: ' . $img);
                        } else {
                            Log::warning('无法找到背景图文件: ' . $fullPath);
                        }
                    } else {
                        // 外部图片直接添加
                        $bgImages[] = $img;
                        Log::info('外部背景图: ' . $img);
                    }
                }
            } else {
                Log::warning('后台未配置背景图片');
            }
            
            // 添加今天日期的所有图片
            $today = date('Ymd');
            $uploadDir = public_path() . 'storage/uploads/bg_images/' . substr($today, 0, 8) . '/';
            
            if (is_dir($uploadDir)) {
                Log::info('查找当天上传目录: ' . $uploadDir);
                $files = scandir($uploadDir);
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && (strpos($file, '.jpg') !== false || strpos($file, '.png') !== false || strpos($file, '.jpeg') !== false)) {
                        $imgPath = '/storage/uploads/bg_images/' . substr($today, 0, 8) . '/' . $file;
                        $fullUrl = $domain . $imgPath;
                        
                        // 确保不重复添加
                        if (!in_array($fullUrl, $bgImages)) {
                            $bgImages[] = $fullUrl;
                            Log::info('添加当天上传图片: ' . $fullUrl);
                        }
                    }
                }
            } else {
                Log::warning('当天上传目录不存在: ' . $uploadDir);
            }
            
            // 确保返回数组
            if (!is_array($bgImages)) {
                $bgImages = [];
                Log::error('背景图处理出错，返回空数组');
            }
            
            // 记录返回的图片数量
            Log::info('返回的背景图数量：' . count($bgImages) . '，内容：' . json_encode($bgImages, JSON_UNESCAPED_UNICODE));
            
            // 处理app_ui_settings中的图片路径，确保有完整域名
            if (!empty($configData['app_ui_settings']) && !empty($configData['app_ui_settings']['logoUrl'])) {
                $logoUrl = $configData['app_ui_settings']['logoUrl'];
                if (strpos($logoUrl, 'http') !== 0) {
                    $configData['app_ui_settings']['logoUrl'] = $domain . $logoUrl;
                }
            }
            
            // 处理Logo图片路径，确保有完整域名
            if (!empty($configData['logo_image'])) {
                $logoUrl = $configData['logo_image'];
                if (strpos($logoUrl, 'http') !== 0) {
                    $configData['logo_image'] = $domain . $logoUrl;
                }
            }
            
            // 返回完整配置数据
            return json([
                'code' => 0,
                'msg' => '获取成功',
                'data' => [
                    // 只返回数据库中配置的背景图，不自动添加当天的图片
                    'background_images' => $configData['bg_images'] ? array_map(function($img) use ($domain) {
                        return strpos($img, 'http') === 0 ? $img : $domain . $img;
                    }, $configData['bg_images']) : [],
                    'redirect_urls' => $configData['urls'] ?? [],
                    // 保留原有格式的app_ui_settings，用于兼容旧版本
                    'app_ui_settings' => $configData['app_ui_settings'] ?? [],
                    // 添加新格式的配置项
                    'screen_orientation' => $configData['screen_orientation'] ?? 'portrait',
                    'is_filter' => $configData['is_filter'] ?? 0,
                    'show_logo_title' => $configData['show_logo_title'] ?? 0,
                    'logo_image' => $configData['logo_image'] ?? '',
                    'login_title' => $configData['login_title'] ?? '',
                    'show_agreement' => $configData['show_agreement'] ?? 1,
                    'agreement_url' => $configData['agreement_url'] ?? '',
                    'agreement_text' => $configData['agreement_text'] ?? '登录即代表同意《服务协议》',
                    'show_copyright' => $configData['show_copyright'] ?? 0,
                    'copyright_text' => $configData['copyright_text'] ?? '© 2023 版权所有',
                    // 字体样式设置
                    'agreement_font_size' => $configData['agreement_font_size'] ?? '12',
                    'agreement_font_color' => $configData['agreement_font_color'] ?? '#666666',
                    'agreement_font_weight' => $configData['agreement_font_weight'] ?? 'normal',
                    'copyright_font_size' => $configData['copyright_font_size'] ?? '12',
                    'copyright_font_color' => $configData['copyright_font_color'] ?? '#999999',
                    'copyright_font_weight' => $configData['copyright_font_weight'] ?? 'normal',
                    // 语言切换设置
                    'show_language_switch' => $configData['show_language_switch'] ?? 1,
                    'language_font_size' => $configData['language_font_size'] ?? '14',
                    'language_font_color' => $configData['language_font_color'] ?? '#ffffff',
                    'language_font_weight' => $configData['language_font_weight'] ?? 'normal',
                    'language_bg_color' => $configData['language_bg_color'] ?? 'rgba(0,0,0,0.3)',
                    'language_position' => $configData['language_position'] ?? 'top-right',
                    // 添加登录按钮相关配置
                    'login_button_font_size' => $configData['login_button_font_size'] ?? '16',
                    'login_button_font_color' => $configData['login_button_font_color'] ?? '#ffffff',
                    'login_button_font_weight' => $configData['login_button_font_weight'] ?? 'normal',
                    'login_button_bg_color' => $configData['login_button_bg_color'] ?? '#8257e6',
                    'login_button_gradient_color' => $configData['login_button_gradient_color'] ?? '#6c45c4',
                    'login_button_radius' => $configData['login_button_radius'] ?? '4',
                    'login_button_shadow' => $configData['login_button_shadow'] ?? 1,
                    'login_button_hover_effect' => $configData['login_button_hover_effect'] ?? 1
                ]
            ]);
        }
        
        return json([
            'code' => 1, 
            'msg' => '配置未找到',
            'data' => [
                'background_images' => [],
                'redirect_urls' => [],
                'app_ui_settings' => [],
                'screen_orientation' => 'portrait',
                'is_filter' => 0,
                'show_logo_title' => 0,
                'logo_image' => '',
                'login_title' => '',
                'show_agreement' => 1,
                'agreement_url' => '',
                'agreement_text' => '登录即代表同意《服务协议》',
                'show_copyright' => 0,
                'copyright_text' => '© 2023 版权所有',
                // 字体样式设置
                'agreement_font_size' => '12',
                'agreement_font_color' => '#666666',
                'agreement_font_weight' => 'normal',
                'copyright_font_size' => '12',
                'copyright_font_color' => '#999999',
                'copyright_font_weight' => 'normal',
                // 语言切换设置
                'show_language_switch' => 1,
                'language_font_size' => '14',
                'language_font_color' => '#ffffff',
                'language_font_weight' => 'normal',
                'language_bg_color' => 'rgba(0,0,0,0.3)',
                'language_position' => 'top-right',
                // 添加登录按钮相关默认配置
                'login_button_font_size' => '16',
                'login_button_font_color' => '#ffffff',
                'login_button_font_weight' => 'normal',
                'login_button_bg_color' => '#8257e6',
                'login_button_gradient_color' => '#6c45c4',
                'login_button_radius' => '4',
                'login_button_shadow' => 1,
                'login_button_hover_effect' => 1
            ]
        ]);
    }
}