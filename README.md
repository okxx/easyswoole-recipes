easyswoole recipes
### 1.0
```php
1.提供可供http访问的接口服务
2.写几个简单的API接口，
    要求：
        要有一定的安全防御机制，
        统一入参校验、统一响应、接口统一登录态控制(哪些接口是开放式的，哪些是必须登录的)、
        接口签名控制(使用策略模式实现，算法不限)
3.以上接口中写几个demo：
    缓存处理，消息队列(基于redis即可), 以及基于连接池的mysql、redis的应用
4.基于swoole/easyswoole的简单的定时任务
5.简单的异步投递任务、并发调用和协程使用
```

### 2.0
```php
1.使用Easyswoole封装一个成熟的应用框架作为服务端，自定义应用服务组件以支撑填充服务端框架(尽量全面)
2.使用Thinkphp(版本不限)构建应用客户端
3.以电商订单系统为应用场景，实现端对端的内容管理(CURD等)和业务管理
```