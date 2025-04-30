<?php
namespace Tracking\Ocean\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
abstract class BaseRequest {
    protected $baseUrl;

    protected $appId;

    private $secret;

    protected $headers=[];

    protected $body=[];

    public function __construct(){
        $this -> appId = config('ocean-tracking.app_id','appId');
        $this -> baseUrl = config('ocean-tracking.baseUrl','https://prod-api.4portun.com/openapi/gateway');
        $this -> secret = config('ocean-tracking.secret','SECRET');
    }

    protected function getCacheKey(): string
    {
        return 'FOUR-PORT-TOKEN-' . $this->getAppId();
    }

    public function getAppId(): string{
        return $this -> appId;
    }

    public function setAppId(string $appId): static{
        $this -> appId = $appId;
        Cache::forget($this->getCacheKey());
        return $this;
    }

    public function getBaseUrl(): string{
        return $this -> baseUrl;
    }

    public function setBaseUrl(string $baseUrl): static{
        $this -> baseUrl = $baseUrl;
        return $this;
    }

    public function setSecret(string $secret): static{
        $this -> secret = $secret;
        Cache::forget($this->getCacheKey());
        return $this;
    }

    public function setBody(array $body): static{
        $this -> body = $body;
        return $this;
    }

    public function getBody(): array{
        return $this -> body;
    }


    protected function doRequest()
    {
        $this->validate();
        $headers = [
            'Content-Type' => 'application/json',
            'appId' => $this->getAppId(),
            'Authorization' => Cache::get($this->getCacheKey()) ?: $this->generateToken()
        ];
        $body = $this->getBody();
        $url = $this->getBaseUrl() . $this->getEndPoint();
        $res = Http::withHeaders($headers)->post($url, $body);
        return $res -> json();
    }

    private function generateToken(): string
    {
        $url = config('ocean-tracking.authUrl','https://prod-api.4portun.com/openapi/auth/token');
        $res = Http::post($url, [
            'appId' => $this->getAppId(),
            'secret' => $this->secret,
        ]);

        if ($res->successful()) {
            if ($res->json('code') != 200) {
                throw new \Exception($res->json('message') ?? '未知错误', 1);
            }
            $token = $res->json('data');
            Cache::put($this->getCacheKey(),$token,now()->addHours(24));
            return $token;
        }

        throw new \Exception('请求失败', 1);
    }

    abstract public function validate();
    abstract public function getEndPoint() :string;
    abstract public function fetch();

}