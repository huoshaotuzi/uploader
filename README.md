# 文件上传助手
## 安装
`composer require huoshaotuzi/uploader`
## 使用

提交表单的控制器：

```
use Huoshaotuzi\Uploader\Uploader;
use \Huoshaotuzi\Uploader\Validate\FileValidate;

// 表单文件
$file = $_FILES['file'];

// 验证器类
$validate = new FileValidate();

// 限制文件上传大小 204800 字节
$validate->maxSize = [
    'value' => 204800,
    'error_message' => '文件大小不正确',
];

// 限制文件扩展类型为部分图片
$validate->extensions = [
    'value' => ['image/png', 'image/jpg', 'image/jpeg'],
    'error_message' => '文件类型错误',
];

// 限制上传频率为每分钟 5 次
$validate->limitRate = [
    'value' => 5,
    'error_message' => '上传文件频率过快',
];

// 验证规则传给上传类
$uploader = new Uploader($validate);

// 存储到 storage 目录，并且自动生成文件名（年/月/日/随机字符串.扩展名）
$link = $uploader->save($file,'storage');

// 打印上传成功文件路径
var_dump($link);
```

save($file, $path, $fileName, $overwrite = false) 方法包括 4 个参数，作用为：

| 字段 | 值含义 |
|------|-------|
| file | 表单文件 |
| path | 保存路径 |
| fileName | 文件名称，不需要后缀名，根据文件自动获取，默认值为随机字符串 |
| overwrite| 是否允许覆盖同名文件，true 允许，false 当存在同名文件时报错 |

## 验证器类

验证器类是验证上传文件的扩展类型、文件大小和上传频率的限制器，不符合要求的文件将会报错。

验证器类的参数如下：

```
public $maxSize = [
    'value' => 0,
    'error_message' => 'file size error',
];
    
public $extensions = [
    'value' => [],
    'error_message' => 'file type error',
];
    
public $limitRate = [
    'value' => 0,
    'error_message' => 'limit rate error',
];
```

其中，error_message 表示报错提示信息。

各个字段 value 对应属性：


| 字段 | 值含义 |
|------|-------|
| maxSize | 文件大小限制，单位：字节，默认为 0 表示没有限制 |
| extensions | 允许上传文件类型，数组格式，默认为空数组|
| limitRate | 允许每分钟上传文件数量，正整数，默认为 0 表示没有限制 |

实例化 Uploader 对象时，如果不传入验证器类则视为不需要验证文件直接上传。

如果不想在每个地方都要重新写验证器规则，可以自定义一个验证器类：

```
namespace MyValidate;

use Huoshaotuzi\Uploader\Validate\FileValidate;

class ImageValidate extends FileValidate
{
    public $maxSize = [
        'value' => 204800,
        'error_message' => '图片大小不能超过2MB',
    ];

    public $extensions = [
        'value' => ['image/png', 'image/jpg', 'image/jpeg'],
        'error_message' => '只允许上传png,jpg,jpeg格式图片',
    ];

    public $limitRate = [
        'value' => 5,
        'error_message' => '每分钟只能上传5张图片',
    ];
    
    /**
     * 文件校验方法
     * 如果上面三种属性无法满足要求，
     * 则需要自定义规则则重写此方法，否则使用默认三种属性进行判断
     * @param $file
     * @throws \Exception
     */
    public function validate($file)
    {
        // 调用 Uploader 类的对象 save 方法时，将会自动调用验证器的该方法
    }
}
```

然后在实例化 Uploader 类的时候将该验证器传入构造方法：

```
use MyValidate\ImageValidate;
use Huoshaotuzi\Uploader\Uploader;

$uploader = new Uploader(new ImageValidate());
```

# 存储器类

默认使用本地存储，`Huoshaotuzi\Uploader\Disk\LocalDisk`。

可以自定义存储器：

```
<?php

use Huoshaotuzi\Uploader\Disk\DiskAbstract;

class MyDisk extends DiskAbstract
{
    // 需要实现存储方法
    public function save($file, $path, $fileName, $overwrite = false)
    {
    
    }
    
    // 需要实现删除方法
    public function delete($fileOriginal)
    {
        
    }
}
```