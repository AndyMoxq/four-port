<?php

namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OceanTracking
 * 
 * @property string|null $subscription_id 订阅id
 * @property string|null $bill_no 提单号
 * @property string|null $sailing_id 航程Id
 * @property string|null $carrier_code 船公司代码
 * @property string|null $carrier_name 船公司名称
 * @property string|null $carrier_name_cn 船公司中文名称
 * @property \Carbon\Carbon|null $end_time 航程结束时间
 * @property array|null $first_vessel 船舶信息
 * @property array|null $data_type 数据报文类型
 *
 * @property-read array $carrier 船公司信息（包含 carrier_code, carrier_name, carrier_name_cn）
 * @property-read \Illuminate\Database\Eloquent\Collection|\Tracking\Ocean\Models\OceanTrackingPlace[] $places 发生地信息
 * @property-read \Illuminate\Database\Eloquent\Collection|\Tracking\Ocean\Models\OceanTrackingRoute[] $routes 路径信息
 * @property-read \Illuminate\Database\Eloquent\Collection|\Tracking\Ocean\Models\OceanTrackingContainer[] $containers 集装箱信息
 * @property-read \Illuminate\Database\Eloquent\Collection|\Tracking\Ocean\Models\OceanTrackingDocument[] $documents 单证集合
 * @property-read \Illuminate\Database\Eloquent\Collection|\Tracking\Ocean\Models\OceanTrackingEir[] $eir 码头堆场信息
 * @property-read \Tracking\Ocean\Models\OceanTrackingTerminalPlan|null $terminalPlan 码头船舶计划
 */
class OceanTracking extends Model {
    public $table = 'fp_ocean_trackings';

    protected $fillable = [
      'subscription_id', 'bill_no', 'sailing_id',
      'carrier_code', 'carrier_name', 'carrier_name_cn',
      'end_time', 'first_vessel', 'data_type'
    ];

    protected $casts = [
        'end_time' => 'datetime',
        'first_vessel' => 'array',
        'data_type' => 'array',
    ];

    public function getCarrierAttribute()
    {
        return [
            'carrierCode' => $this->carrier_code,
            'carrierName' => $this->carrier_name,
            'carrierNameCn' => $this->carrier_name_cn,
        ];
    }
    
    public function places(){
        return $this->hasMany(OceanTrackingPlace::class, 'fp_ocean_tracking_id');
    }
    
    public function routes(){
        return $this->hasMany(OceanTrackingRoute::class, 'fp_ocean_tracking_id');
    }
    
    public function containers(){
        return $this->hasMany(OceanTrackingContainer::class, 'fp_ocean_tracking_id');
    }

    public function documents(){
        return $this->hasMany(OceanTrackingDocument::class,'fp_ocean_tracking_id');
    }

    public function eirs(){
        return $this->hasMany(OceanTrackingEir::class,'fp_ocean_tracking_id');
    }

    public function terminalPlan()
    {
        return $this->hasOne(OceanTrackingTerminalPlan::class, 'fp_ocean_tracking_id');
    }
}