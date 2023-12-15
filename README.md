# swoole test

```
1.项目拉下后先 composer install 没有引入任何组件，纯粹是为了swoole ide自动提示；
2.HTTP测试 直接运行 php http_server.php 即可，浏览器便可访问：http://127.0.0.1:9501/index/index?a=2
3.TCP服务测试 直接运行 php server.php 即可，这时就可以使用 telnet/netcat 工具连接服务器。
telnet 127.0.0.1 9501
hello
Server: hello
```