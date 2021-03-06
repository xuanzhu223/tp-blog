<?php
namespace app\admin\controller;

use app\BaseController;
use think\Request;
use app\util\Token;
use app\admin\service\Auth;
use app\admin\model\User as UserModel;
use app\admin\model\Login as LoginModel;
use app\admin\model\Category as CategoryModel;
use app\admin\validate\LoginValidate;
use think\exception\ValidateException;
use \PHPMailer\PHPMailer;
use think\facade\Log;

class Login extends BaseController
{
    /**
     * @var \think\Request Request实例
     */
    protected $request;
    protected $param;

    /**
     * 构造方法
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request)
    {
		$this->request = $request;
		$this->header = $request->header();
		$this->param = $this->request->param();
    }

    public function login()
    {
        try {
            Log::info('走到 login');
            validate(LoginValidate::class)->scene('login')->check($this->param);
            $user = (new UserModel)->getUserInfoByAccount($this->param['account']);
            if (empty($user)) {
                return ajaxReturn(ERR_CODE_LOGIN,'用户不存在');
            }
            if(Auth::getMd5($this->param['password']) != $user['password']) {
                $pa1 = Auth::getMd5($this->param['password']);
                return ajaxReturn(ERR_CODE_LOGIN,'密码不正确');
            }
            $userToken = Token::createToken($user['id']);
            LoginModel::update(['rememberToken' =>  $userToken], ['id' => $user['id']]);
        } catch (ValidateException $e) {
            Log::info('ValidateException');
            return ajaxReturn(ERR_CODE_LOGIN,$e->getError());
        } catch (\DataNotFoundException $e){
            Log::info('DataNotFoundException');
            return ajaxReturn(ERR_CODE_LOGIN,$e->getError());
        }
            Log::info('SUCCESS');
        return ajaxReturn(SUCCESS,'',['token'=>Token::createToken($user['id'])]);
    }

    public function register()
    {
        try {
            validate(LoginValidate::class)->check($this->param);
            $this->param['password'] = Auth::getMd5($this->param['password']);
            $this->param['account'] = $this->param['account'];
            $this->param['nickname'] = '未命名用户|'.time();
            unset($this->param['confirmPassword']);
            $userInfo = LoginModel::create($this->param);
            list($dealRet, $response) = (new CategoryModel)->createDefaultCategory($userInfo->id);
            if (!$dealRet) {
                return ajaxReturn(ERR_CODE_REGISTER, $response);
            }
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_REGISTER,$e->getError());
        }
        return ajaxReturn(SUCCESS);
    }

    public function sendEmailCpatcha()
    {
        try {
            validate(LoginValidate::class)->scene('captcha')->check($this->param);
            $emailConfig = config('email');
            $captcha = substr(str_shuffle($emailConfig['captcha_character']),rand(0, strlen($emailConfig['captcha_character']) - 1),$emailConfig['captcha_length']);
            $toEmail = $this->param['email'];
            $emailCaptcha = time().'_'.$captcha;
            sendEamil($toEmail, $captcha);
            LoginModel::saveEmailCaptcha($this->param['account'], $emailCaptcha);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_GET_CAPTCHA,$e->getError());
        } catch (Exception $e) {  //其他错误
            return ajaxReturn(ERR_CODE_SEND_CAPTCHA,$e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }

    /*
    * 找回密码
    */
    public function retrievePassword()
    {
        try {
            validate(LoginValidate::class)->scene('retrieve')->check($this->param);
            $userInfo = LoginModel::where('account', $this->param['account'])->find();
            $captchaInfo = explode('_' ,$userInfo->emailCaptcha);
            $captcha_exp = config('email.captcha_exp');
            // 验证有效期
            if ((time()-(int)$captchaInfo[0]) > $captcha_exp){
                return ajaxReturn(ERR_CODE_BIND_EMAIL,'验证码过期');
            }
            if ($this->param['captcha'] != $captchaInfo[1]){
                return ajaxReturn(ERR_CODE_BIND_EMAIL,'验证码错误');
            }
            LoginModel::update(['emailCaptcha' => '', 'email' => $this->param['email'], 'id' => $userInfo->id]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return ajaxReturn(ERR_CODE_BIND_EMAIL,$e->getError());
        } catch (\Exception $e) {  //其他错误
            return ajaxReturn(ERR_CODE_BIND_EMAIL,$e->getMessage());
        }
        return ajaxReturn(SUCCESS);
    }
}
