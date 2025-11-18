<?php
namespace Tracking\Ocean;
use Tracking\Ocean\Request\SubscribeRequest;
use Tracking\Ocean\Response\SubscribeResponse;
use Tracking\Ocean\Request\GetTrackingRequest;
use Tracking\Ocean\Response\GetTrackingResponse;
class OceanTracking{
    /**
     * 订阅海运跟踪
     * @param string $carrierCode
     * @param string $billNo
     * @return SubscribeResponse
     */
    public static function subscribe(string $carrierCode,string $billNo): SubscribeResponse{
        $request = new SubscribeRequest();
        $request -> setTracking($carrierCode,$billNo);
        return $request->fetch();
    }

    /**
     * 获取追踪信息
     * @param string $subscriptionId
     * @return GetTrackingResponse
     */
    public static function getTracking(string $subscriptionId): GetTrackingResponse{
        $request = new GetTrackingRequest();
        $request->setSubscriptionId($subscriptionId);
        return $request->fetch();
    }
}
