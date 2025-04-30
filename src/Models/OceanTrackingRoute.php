<?php

namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

class OceanTrackingRoute extends Model {

    public $table = 'fp_ocean_tracking_routes';

    protected $hidden =[
        'id','fp_ocean_tracking_id','created_at','updated_at'
    ];
    protected $fillable = [
        'fp_ocean_tracking_id',
        'route',
        'pol_code',
        'pol_name',
        'pol_name_origin',
        'pol_zone',
        'pod_code',
        'pod_name',
        'pod_name_origin',
        'pod_zone',
        'transport_mode',
        'vessel',
        'voyage',
        'pol_etd',
        'pol_atd',
        'pod_eta',
        'pod_ata',
        'pol_lat',
        'pol_lon',
        'pod_lat',
        'pod_lon',
        'pol_atd_ais',
        'pod_ata_ais',
        'transport_state',
    ];

    protected $casts = [
        'pol_etd' => 'datetime',
        'pol_atd' => 'datetime',
        'pod_eta' => 'datetime',
        'pod_ata' => 'datetime',
        'pol_atd_ais' => 'datetime',
        'pod_ata_ais' => 'datetime',
        'pol_lat' => 'decimal:6',
        'pol_lon' => 'decimal:6',
        'pod_lat' => 'decimal:6',
        'pod_lon' => 'decimal:6',
    ];

    public function tracking()
    {
        return $this->belongsTo(OceanTracking::class, 'fp_ocean_tracking_id');
    }

    public function transportMode(): string{
        $map = [
            'VESSEL' => '海运',
            'TRUCK' => '卡车',
            'RAIL'   => '火车',
        ];

        return $map[$this->transport_mode] ?? '未知';
    }

    public function transportState(): string{
        $map = [
            'NOT_START' => '未开始',
            'IN_TRACKING' => '运输中',
            'HAS_DONE'=>'已结束'
        ];

        return $map[$this->transport_state] ?? '未知';
    }
}