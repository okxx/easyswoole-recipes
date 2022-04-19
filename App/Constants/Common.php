<?php
namespace App\Constants;

class Common {

    const HEADER_ALLOW_HEADERS                      = 'Content-Type, Authorization, X-Requested-With, token, admin-user-token,rrs';

    // MESSAGES
    const SUCCESS                                   = 'successful.';
    const PARAM_EXCEPTION                           = "参数异常, %s";
    const USER_NOTFOUND                             = '用户不存在';
    const USER_SAVE_FAIL                            = '用户保存失败';
    const USER_EXISTS                               = '用户已存在';
    const USER_PASSWORD_INCORRECT                   = '账户密码不正确';
    const CODE_GENERATE_EXISTS                      = '验证码不能重复生成';
    const CODE_NOT_EXISTS                           = '验证码不存在';
    const CODE_NOT_EQUAL                            = '验证码不正确';
    const ROUTE_THROTTLING_DENIED                   = '访问过于频繁，请稍后重试。';
    const TOKEN_IS_INVALID                          = 'TOKEN无效。';
    const TOKEN_HAS_EXPIRED                         = 'TOKEN已过期。';
    const TOKEN_NOT_FOUND                           = 'TOKEN不存在。';
    const TOKEN_VERIFICATION_FAILED                 = 'TOKEN验证不通过。';
    const SIGNATURE_VERIFICATION_FAILED             = '签名验证不通过。';
    const GOODS_CREATE_FAIL                         = '创建商品失败。';
    const GOODS_EDIT_FAIL                           = '编辑商品失败。';
    const GOODS_DELETE_FAIL                         = '删除商品失败。';
    const GOODS_NOT_FOUND                           = '商品不存在。';
    const ORDER_CREATE_FAIL                         = '订单创建失败。';
    const ORDER_NOT_PAID                            = '订单未支付，请先支付。';
    const ORDER_CALLBACK_FAIL                       = '订单回调失败。';
    const ORDER_NOT_FOUND                           = '订单不存在。';
    const COUPON_NOT_FOUND                          = '优惠券不存在。';
    const COUPON_ARE_USED                           = '优惠券被使用。';

    // EXPIRE
    const EXPIRE_HOUR = 3600;

}