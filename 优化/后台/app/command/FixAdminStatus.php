<?php

declare (strict_types = 1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class FixAdminStatus extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('fix:admin_status')
            ->setDescription('修复所有管理员账号的状态字段');
    }

    protected function execute(Input $input, Output $output)
    {
        $output->writeln('开始修复管理员账号状态...');
        
        try {
            // 先查询所有管理员
            $admins = Db::name('admin')->select();
            $output->writeln('找到 ' . count($admins) . ' 个管理员账号');
            
            $updated = 0;
            
            foreach ($admins as $admin) {
                $needUpdate = false;
                $updateData = [];
                
                // 检查状态字段是否存在，如果不存在或非法值则设置为1（启用）
                if (!isset($admin['status']) || ($admin['status'] !== 0 && $admin['status'] !== 1)) {
                    $updateData['status'] = 1; // 默认启用
                    $needUpdate = true;
                }
                
                // 超级管理员强制状态为启用
                if ($admin['id'] == 1 && isset($admin['status']) && $admin['status'] != 1) {
                    $updateData['status'] = 1;
                    $needUpdate = true;
                }
                
                // 如果需要更新
                if ($needUpdate) {
                    Db::name('admin')->where('id', $admin['id'])->update($updateData);
                    $updated++;
                    $output->writeln('已修复管理员: ID=' . $admin['id'] . ', 用户名=' . $admin['name']);
                }
            }
            
            $output->writeln('修复完成，共更新了 ' . $updated . ' 个管理员账号');
            
            // 检查数据库字段状态
            $this->checkDatabaseFields($output);
            
            return 0;
        } catch (\Exception $e) {
            $output->writeln('<error>发生错误: ' . $e->getMessage() . '</error>');
            return 1;
        }
    }
    
    /**
     * 检查数据库字段是否存在
     */
    protected function checkDatabaseFields(Output $output)
    {
        try {
            // 检查status字段是否存在于admin表
            $columns = Db::query('SHOW COLUMNS FROM __PREFIX__admin');
            $hasStatusField = false;
            
            foreach ($columns as $column) {
                if ($column['Field'] == 'status') {
                    $hasStatusField = true;
                    $output->writeln('admin表包含status字段，类型: ' . $column['Type']);
                    break;
                }
            }
            
            if (!$hasStatusField) {
                $output->writeln('<warning>警告: admin表中不存在status字段，尝试添加该字段</warning>');
                Db::execute('ALTER TABLE __PREFIX__admin ADD COLUMN `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT "状态:0禁用,1启用" AFTER `role_type`');
                $output->writeln('<info>成功添加status字段到admin表</info>');
            }
        } catch (\Exception $e) {
            $output->writeln('<error>检查数据库字段时发生错误: ' . $e->getMessage() . '</error>');
        }
    }
}