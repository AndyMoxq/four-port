<?php
namespace Tracking\Ocean\Request;
use Tracking\Ocean\Response\GetTrackingResponse;

class GetTrackingRequest extends BaseRequest {
    public const END_POINT = '/api/v2/getOceanTracking';

    public function getEndPoint(): string{
        return self::END_POINT;
    }

    public function setSubscriptionId($subscriptionId){
        $body['subscriptionId'] = $subscriptionId;
        $this -> setBody($body);
    }

    public function validate(){
        $body = $this -> getBody();
        if(empty($body['subscriptionId'])){
            throw new \Exception("subscriptionId is required", 1);
        }
    }
    
    public function fetch(){
        return GetTrackingResponse::format($this -> doRequest());
    }
}