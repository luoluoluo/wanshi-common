## <a href="https://github.com/luoluoluo/file.wanshi.org">文件服务</a>的使用文档

### 安装

1.composer.json 增加：

```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/luoluoluo/wanshi-file-client.git"
    }
],

"require": {
    "wanshi/file-client": "dev-master"
}
```

2.config/app.php 增加(laravel)：

```
Wanshi\FileClient\FileClientServiceProvider::class,
```

3.增加config/file_client.php(laravel)：

```
return [
    'domain'    => 'http://file.wanshi.org/',
    'appid'     => env('FILE_APPID'),
    'appsecret' => env('FILE_APPSECRET'),
];
```

### 开始使用

1.上传(客户端向业务服务器请求token->客户端直接像文件服务器上传

* 获取上传token： $res = app('file_client')->token();
* 客户端带着token直接上传文件到文件服务器

```
    请求：
    url: http://file.wanshi.org/file
    method: POST
    data: file(文件)，token(上传token);

    返回：
    file_id
```

2.下载/查看
```
  获取下载/查看url：$url = app('file_client')->url(file_id, params);
  参数说明： params[size]：(缩略图 eg:200; 200x300) ，params[type]: (inline:查看； attachement:下载；)
```
3.删除
```
  $url = app('file_client')->delete(file_id); //多个file_id 用,隔开
```
