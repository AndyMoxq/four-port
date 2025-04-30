<?php
namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

class OceanContainerStatus extends Model
{
    protected $table = 'fp_ocean_container_status';

    protected $fillable = [
        'fp_ocean_container_id',
        'vessel',
        'voyage',
        'event_code',
        'event_time',
        'is_esti',
        'event_place',
        'port_time_zone',
        'description_cn',
        'description',
        'event_description_origin',
        'event_place_origin',
        'port_code',
        'terminal_code',
        'terminal_name_cn',
        'truck_no',
        'train_station_name',
        'train_station_original_code',
        'train_station_std_code',
        'train_name',
        'train_number',
        'train_wagon_number',
        'container_empty_status',
        'images',
        'ext_params',
        'remark',
        'source',
        'transport_mode',
        'level',
    ];

    protected $casts = [
        'event_time' => 'datetime',
        'is_esti' => 'boolean',
        'ext_params' => 'array',
    ];

    protected $hidden = [
        'id','fp_ocean_container_id','created_at','updated_at'
    ];

    public function container()
    {
        return $this->belongsTo(OceanTrackingContainer::class, 'fp_ocean_container_id');
    }

    public function getTransportModeLabelAttribute()
    {
        $map = [
            'VESSEL' => '海运',
            'TRUCK' => '卡车',
            'RAIL'   => '火车',
        ];

        return $map[$this->transport_mode] ?? '未知';
    }
}