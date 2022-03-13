# 基于 illuminate/validate，在 laravel 框架外使用的 validate

## 安装

`composer require mouyong/validate`

## 文件翻译创建

```php
./webman plugin:install mouyong/validate

or

php ./vendor/mouyong/validate/src/scripts/install.php
```

### 移除翻译文件

```php
./webman plugin:uninstall mouyong/validate

or

php ./vendor/mouyong/validate/src/scripts/uninstall.php
```

## 基础用法

```php
$data = [
    'name'  => 'Tinywan',
    'age'  => 24,
    'email' => 'Tinywan@163.com'
];


/** @var \Illuminate\Contracts\Validation\Validator|\Illuminate\Contracts\Validation\Factory $validator */
$validator = validate($data, [
    'name'  => 'require|max:25',
    'age'   => 'require|number|between:1,120',
    'email' => 'require|email'
], [
    'name.require' => '名称必须',
    'name.max'     => '名称最多不能超过25个字符',
    'age.require'   => '年龄必须是数字',
    'age.number'   => '年龄必须是数字',
    'age.between'  => '年龄只能在1-120之间',
    'email.require'        => '邮箱必须是数字',
    'email.email'        => '邮箱格式错误'
])

$validator->validate();
```

更多验证规则请阅读：http://laravel.com/docs/9.x/validation#available-validation-rules

## 参考

https://medium.com/@jeffochoa/using-the-illuminate-validation-validator-class-outside-laravel-6b2b0c07d3a4
