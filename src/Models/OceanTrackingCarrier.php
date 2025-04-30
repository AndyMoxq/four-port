<?php

namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $carrier_code 船公司代码
 * @property string $carrier_name 船公司名称
 * @property bool $bol_method 是否支持提单订阅
 * @property bool $container_method 是否支持箱号订阅
 */
class OceanTrackingCarrier extends Model {

    public $table = 'fp_ocean_tracking_carriers';

    public $timestamps = false;

    protected $fillable = [
      'carrier_code','carrier_name','bol_method','container_method'
    ];

    protected $casts = [
        'bol_method' => 'boolean',
        'container_method' => 'boolean'
    ];

    public function supportsBol(): bool{
        return $this -> bol_method;
    }

    public function supportsContainer(): bool{
        return $this -> container_method;
    }
}