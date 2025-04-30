<?php
namespace Tracking\Ocean\Response;

class SubscribeResponse extends BaseResponse {
    public function validate(){
        if ($this -> getCode() !== 200) {
            $this -> setMessage("【" . $this -> getCode() . '】, ' . $this -> getBody()['message'] ?? "Unknow Message");
            throw new \Exception($this -> getMessage(), 1);
            
        }
    }

    public function getSubscriptionId(){
        return $this -> getData()['subscriptionId'];
    }
}