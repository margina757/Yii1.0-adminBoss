<?php

/**
 * KeyGen
 *  RedisKey生成工具
 * 
 *     作者: 李晓 (kisa77.lee@gmail.com)
 * 创建时间: 2014-02-25 16:34:19
 * 修改记录: 
 * 
 * $Id$
 */

class KeyGen {

    /**
     * generateClientGameKey 
     * 
     * @param  mixed $key 
     * @return void
     */
    public static function generateClientGameKey($key) {
        return ClientGameRedis::$_prefix . $key;
    }

    /**
     * 生成用户Key
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function generateUserKey($key)
    {
        return UserRedis::$_prefix . $key;
    }

    /**
     * 生成Node Key
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function generateNodeKey($key)
    {
        return NodeRedis::$_prefix . $key;
    }
    /**
     * 生成Game Key
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function generateGameKey($key)
    {
        return GameRedis::$_prefix . $key;
    }

    /**
     * 生成Robot Key
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function generateRobotKey($key)
    {
        return RobotRedis::$_prefix . $key;
    }

    /**
     * 生成专题Key
     * @author Howe Isamu <xi4oh4o@gmail.com>
     */
    public static function generateSpecialKey($key)
    {
        return SpecialRedis::$_prefix . $key;
    }

    /**
     * generateProductKey 
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateProductKey($key) {
        return ProductRedis::$_prefix . $key;
    }

    /**
     * generateProductPriceKey 
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateProductPriceKey($key) {
        return ProductPriceRedis::$_prefix . $key;
    }
    
    /**
     * generateRefundKey 
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateRefundKey($key) {
        return RefundRedis::$_prefix . $key;
    }

    /**
     * generateOrderKey 
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateOrderKey($key) {
        return OrderRedis::$_prefix . $key;
    }

    /**
     * generateDistributorOrderKey
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateDistributorOrderKey($key) {
        return DistributorOrderRedis::$_prefix . $key;
    }

    /**
     * generateCardTypeKey
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateCardTypeKey($key) {
        return CardTypeRedis::$_prefix . $key;
    }

    /**
     * generateTagKey
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateTagKey($key) {
        return TagRedis::$_prefix . $key;
    }

    /**
     * generateMarketGiftKey
     * 
     * @param  mixed $key 
     * @return void
     * @author zhanghaolei@lonlife.net
     */
    public static function generateMarketGiftKey($key) {
        return MarketGiftRedis::$_prefix . $key;
    }

    /**
     * generateMarketGiftKey
     * 
     * @param  mixed $key 
     * @return void
     * @author qyc@lonlife.net
     */
    public static function generateRobotConversationKey() {
        return RobotConversationRedis::$_prefix;
    }

    /**
     * generateMarketGiftKey
     * 
     * @param  mixed $key 
     * @return void
     * @author qyc@lonlife.net
     */
    public static function generateRobotMessageKey($key) {
        return RobotMessageRedis::$_prefix . $key;
    }


    /**
     * __callStatic 
     * 跟generateClientGameKey一致的RedisKey生成方法
     * 
     * @param  mixed $name 
     * @param  mixed $args 
     * @return void
     */
    public static function __callStatic($name, $args) {
        $className = str_replace(array('generate', 'Key'), array('', ''), $name) . 'Redis';
        return $className::$_prefix . join("_", $args);
    }
}
