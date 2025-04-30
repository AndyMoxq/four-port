# Ocean Tracking Laravel Package

Ocean Tracking 是一个轻量级的 Laravel 扩展包，用于集成外部 API 并跟踪国际海运物流状态。适用于需要追踪提单号、箱号、多段运输信息的跨境业务系统。

## 📦 安装 Installation

> 适配 Laravel 8.x 及以上版本

推荐使用本地路径方式安装：

```bash
composer require 4port/ocean-tracking
```

## ⚙️ 配置 Configuration

发布配置文件到主项目：

```bash
php artisan vendor:publish --tag=ocean-tracking-config
```

在 `config/ocean-tracking.php` 中配置：

```php
return [
    // 使用 .env 中的配置自动初始化
    'baseUrl' => env('OCEAN_TRACKING_BASE_URL', 'https://api.example.com'),
    'app_id' => env('OCEAN_TRACKING_COMPANYID','0000'),
    'secret' => env('OCEAN_TRACKING_SECRET','YOUR-SECRET'),
];
```

## 🧬 数据库迁移 Migration

执行包内迁移文件：

```bash
php artisan migrate
```

如需发布迁移文件自行修改：

```bash
php artisan vendor:publish --tag=ocean-tracking-migrations
```

## 🚀 使用方式 Usage

> 所有请求类均提供 `fetch()` 方法作为统一入口，内部完成参数校验、请求发送及响应解析处理。

### 📝 示例一：注册追踪信息

```php
use Tracking\Ocean\Request\SubscribeRequest;
use Tracking\Ocean\Traits\UpdateOceanTrackingTrait;

try {
    $request = new SubscribeRequest;

    $body = [
        'carrierCode' => 'HMM',
        'billNo' => 'NBOZ0Y012345',
        'portCode' => 'CNNGB',
        'dataType' => [
            "PORT",
            "CARRIER"
        ]
    ];

    $res = $request->setBody($body)->fetch();

    // 或者简易订阅（只获取海运数据）
    // $res = $request -> setTracking('HMM','NBOZ0Y012345')->fetch();

    // 调试响应数据
    // var_dump($res->getData());

    // 使用 UpdateOceanTrackingTrait 将响应数据写入数据库
    $tracking = $this->updateSubscribeData($res->getData());

    dump($tracking->toArray());
    
} catch (\Throwable $th) {
    var_dump($th->getMessage());
}
```
> 该示例演示如何注册一个新的追踪信息，向外部接口订阅物流状态更新，并将返回的数据保存到本地数据库。

### 📦 示例二：获取追踪信息

```php
use Tracking\Ocean\Request\GetTrackingRequest;
use Tracking\Ocean\Models\OceanTracking;

try {
    $subscriptionId = OceanTracking::firstOrFail()->subscription_id;

    $request = new GetTrackingRequest();
    $request->setSubscriptionId($subscriptionId);
    $res = $request->fetch();

    // 自动更新主表、航程、箱信息及状态节点
    $tk = $this->updateOceanTracking($res->getData());

    dump($tk->toArray());
} catch (\Throwable $th) {
    $this->error($th->getMessage());
}
```
> 该示例展示如何根据订阅 ID 拉取最新的追踪信息，并同步更新本地数据库中的相关记录。

### 🔁 示例三：自动同步追踪数据（Webhook）

你可以创建一个 webhook 接口用于接收来自平台的箱动态推送，并自动保存更新：

```php
use Tracking\Ocean\Traits\UpdateOceanTrackingTrait;

$this->updateOceanTracking($res->getData());
```

平台推送示例：

```bash
POST https://your-domain.com/api/ocean-tracking
```

将自动更新主表、航程、箱信息及状态节点，无需额外处理。

> 该示例说明如何通过 webhook 接收平台主动推送的物流状态变更，实现自动同步和持久化，极大简化数据维护工作。

## 📡 API 对接 API Integration

本包默认支持以下功能：

- 提单号和箱号订阅及追踪；
- 支持列表 https://your-domain.com/api/ocean-tracking-carriers
- 响应字段与接口文档保持一致；
- 可通过 HTTP 客户端重写 `generateToken()` 等方法自定义接入方式；

接口文档地址：

[查看接口文档](https://tracking.4portun.com/#/application/detail?activeId=1&categoryIndex=1&subIndex=0&apiIndex=0)

## 📚 License

MIT © 2024 Andy / 4portun
