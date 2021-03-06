<?php
namespace app\admin\validate;

use think\Validate;

class LoginValidate extends Validate
{
    protected $rule = [
        'account' => 'require|unique:users|length:4,18',
        'password' => 'require|length:4,18',
        'confirmPassword' => 'require|length:4,18|confirm:password',
    ];
    //定义提示信息
    protected $message = [
        'account.require' => '账号不能为空',
        'account.length' => '账号长度应为4~18字符',
        'account.unique' => '账号已存在',
        'password.require' => '密码不能为空',
        'password.length' => '密码长度应为4~18字符',
        'confirmPassword.require' => '确认密码不能为空',
        'confirmPassword.length' => '确认密码应为4~18字符',
        'confirmPassword.confirm' => '两次输入的密码不匹配',
        'email.require' => '邮箱不能为空',
        'email.email' => '邮箱格式不正确',
    ];

    // login 验证场景定义
    public function sceneLogin()
    {
        return $this->only(['account','password'])
            ->remove('account', 'unique')
            ->remove('password', 'length');
    }

    // 发送验证码 验证场景定义
    public function sceneCaptcha()
    {
        return $this->only(['account','email'])
            ->remove('account', 'unique')
            ->append('email', 'require|email');
    }

    // 验证验证码 验证场景定义
    public function sceneRetrieve()
    {
        return $this->only(['account','captcha','email'])
            ->remove('account', 'unique')
            ->append('email', 'require|email')
            ->append('captcha', 'require');
    }
}