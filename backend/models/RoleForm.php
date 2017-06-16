<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions = [];//角色权限

    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
            ['permissions', 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'description' => '描述',
            'permissions' => '权限',
        ];
    }

    //获取所有权限
    public static function getPermissionOptions()
    {
        $authManager = \Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(), 'name', 'description');
    }

    //添加角色
    public function addRole()
    {
        $authManager = \Yii::$app->authManager;
        //判断角色是否存在
        if ($authManager->getRole($this->name)) {
            $this->addError('该角色已存在');
        } else {
            $role = $authManager->createRole($this->name);
            $role->description = $this->description;
            //保存到数据表
            if ($authManager->add($role)) {
                //关联该角色的权限
                foreach ($this->permissions as $permissionName) {
                    $permission = $authManager->getPermission($permissionName);
                    if ($permission) $authManager->addChild($role, $permission);
                }
                return true;
            }
        }
        return false;
    }
    //修改角色
    public function updateRole($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        //赋值
        $role->name = $this->name;
        $role->description = $this->description;
        //检查被修改后的角色是否存在
        if ($name != $this->name && $authManager->getRole($this->name)) {
            $this->addError('该角色已存在');
        } else {
            if ($authManager->update($name, $role)) {
                //去掉所有与该角色关联的权限
                $authManager->removeChildren($role);
                //关联该角色的权限
                foreach ($this->permissions as $permissionName) {
                    $permission = $authManager->getPermission($permissionName);
                    if ($permission) $authManager->addChild($role, $permission);
                }
                return true;
            }
        }
        return false;
    }
    public function loadData(Role $role)
    {
        $this->name = $role->name;
        $this->description = $role->description;
        //权限属性赋值
        //获取该角色对应的权限
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
        //$this->permissions = ['brand/edit','brand/index'];
        foreach ($permissions as $permission) {
            $this->permissions[] = $permission->name;
        }
    }
}