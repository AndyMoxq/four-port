<?php
namespace Tracking\Ocean\Request;
use Tracking\Ocean\Models\OceanTrackingCarrier;
use Tracking\Ocean\Response\SubscribeResponse;

class SubscribeRequest extends BaseRequest {

    public const END_POINT = '/api/v2/subscribeOceanTracking';

    public function getEndPoint(): string{
        return self::END_POINT;
    }

    public function validate(){
        //
        $body = $this -> getBody();
        if (empty($body['carrierCode'])) {
          throw new \InvalidArgumentException('carrierCode 是必填项');
        }

        $body['carrierCode'] = strtoupper($body['carrierCode']);

        if(!OceanTrackingCarrier::where('carrier_code',$body['carrierCode'])->exists()){
            throw new \InvalidArgumentException( '暂时不支持该CarrierCode:' . $body['carrierCode']);
        }

        $carrier = OceanTrackingCarrier::where('carrier_code',$body['carrierCode'])-> first();

        if (empty($body['billNo']) && empty($body['containerNo'])) {
            throw new \InvalidArgumentException('billNo 和 containerNo 至少填写一个');
        }

        if (!empty($body['billNo']) && ! $carrier -> supportsBol()) {
            throw new \InvalidArgumentException("{$carrier->carrier_code}-{$carrier->carrier_name} 暂不支持提单号订阅");
        }

        if (!empty($body['containerNo']) && ! $carrier -> supportsContainer()) {
            throw new \InvalidArgumentException("{$carrier->carrier_code}-{$carrier->carrier_name} 暂不支持箱号订阅");
        }

        $this->setBody([
            ...$body,
            'isExport' => $body['isExport'] ?? 'E',
            'dataType' => $body['dataType'] ?? ['CARRIER'],
        ]);

        $body = $this->getBody();

        if ($this->needsPortCode($body['dataType']) && empty($body['portCode'])) {
            throw new \InvalidArgumentException('当 dataType 包含非 CARRIER 类型时，portCode 为必填项');
        }
    }

    protected function needsPortCode(array $types): bool
    {
        return !empty(array_filter($types, fn ($t) => $t !== 'CARRIER'));
    }

    public function fetch(){
        $res = $this -> doRequest();
        return SubscribeResponse::format($res);
    }

    public function setTracking(string $carrierCode,string $billNo){
        $body = [
            'carrierCode' => $carrierCode,
            'billNo' => $billNo,
            'isExport' => 'E',
            'dataType' => ['CARRIER']
        ];
        $this -> setBody($body);
        return $this;
    }
}