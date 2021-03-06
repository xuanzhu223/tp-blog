<?php
// 回复信息配置
return [
    'code' => [
        'SUCCESS'                                   => 0,
        'FAIL'                                      => -1,
        'ERR_CODE_INTERFACE_ERROR'                  => -10000,
        'ERR_CODE_PARAM_ERROR'                      => -10001,
        'ERR_CODE_REGISTER'                         => -10002,
        'ERR_CODE_LOGIN'                            => -10003,
        'ERR_CODE_LOGIN_OVERDUE'                    => -10004,
        'ERR_CODE_GET_CAPTCHA'                      => -10005,
        'ERR_CODE_SEND_CAPTCHA'                     => -10006,
        'ERR_CODE_BIND_EMAIL'                       => -10007,
        'ERR_CODE_GET_USER_INFO'                    => -10008,
        'ERR_CODE_ES'                               => -10009,
        'ERR_CODE_INIT_ARTICLE'                     => -10010,
        'ERR_CODE_GET_INDEX_DATA'                   => -10011,
        'ERR_CODE_GET_ARTICLE'                      => -10012,
        'ERR_CODE_DEL_ARTICLE'                      => -10013,
        'ERR_CODE_GET_CATEGORY'                     => -10014,
        'ERR_CODE_CREATE_CATEGORY'                  => -10015,
        'ERR_CODE_DEL_CATEGORY'                     => -10016,
        'ERR_CODE_GET_CATEGORY_INFO'                => -10017,
        'ERR_CODE_EDIT_USER_INFO'                   => -10018,
        'ERR_CODE_GET_ARTICLE_DETAIL'               => -10019,
        'ERR_CODE_SAVE_ARTICLE'                     => -10020,
    ],
    'info' => [
        0            => 'success',
        -1           => '失败',
        -10000       => '接口请求错误',
        -10001       => '参数错误',
        -10002       => '注册错误',
        -10003       => '登录错误',
        -10004       => '登录过期，请重新登录',
        -10005       => '获取验证码错误',
        -10006       => '发送验证码错误',
        -10007       => '绑定邮箱失败',
        -10008       => '获取用户信息失败',
        -10009       => '全文检索错误',
        -10010       => '初始化文章失败',
        -10011       => '获取统计数据失败',
        -10012       => '获取文章列表失败',
        -10013       => '删除文章失败',
        -10014       => '获取分类列表失败',
        -10015       => '创建分类失败',
        -10016       => '删除分类失败',
        -10017       => '获取分类信息失败',
        -10018       => '修改用户信息失败',
        -10019       => '获取文章详情失败',
        -10020       => '保存文章失败',
    ]
];
