<?php
namespace app\util;

use \Firebase\JWT\JWT;
use app\admin\model\Login as LoginModel;
use think\facade\Log;

class Token
{
    /**
     * 生成token
     */
    public static function createToken($userId)
    {
        $time = time();
        $token = array(
            "iss" => config('app.token_iss'),  //签发组织
            "aud" => config('app.token_aud'),  //签发作者
            "iat" => $time,
            "nbf" => $time,
            "exp" => $time + config('app.token_expire_time'),
            "data"=> [
                'id' => $userId
            ]
        );
        $jwt = JWT::encode($token, config('app.token_key'));
        return $jwt;
    }

    /**
     * 校验token
     */
    public static function checkToken($jwt,$alg = array('HS256'))
    {
        $key = config('app.token_key');
        try {
            JWT::$leeway = 60;//当前时间减去60，把时间留点余地
            $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
            $userInfo = (array)$decoded;
            $userData = (array)$userInfo['data'];
            $userId = $userData['id'];
            $userToken = self::createToken($userId);
            // 更新用户的token
            LoginModel::update(['rememberToken' => $userToken], ['id' => $userId]);
        } catch(\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            return [false, $e->getMessage()];
        }catch(\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            return [false, $e->getMessage()];
        }catch(\Firebase\JWT\ExpiredException $e) {  // token过期
            return [false, $e->getMessage()];
        }catch(Exception $e) {  //其他错误
            return [false, $e->getMessage()];
        }
        return [true, $userId];
    }

    /**
     * 校验token
     */
    public static function getTokenInfo($jwt)
    {
        $key = config('app.token_key');
        $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
        $userInfo = (array)$decoded;
        $userInfo = $userInfo['data'];
        if (is_object($userInfo)) {
            $info = [];
            foreach ($userInfo as $key => $value) {
                $info[$key] = $value;
            }
        }
        return $info['id'];
    }
}