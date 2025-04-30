<?php

namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

class OceanTrackingContainer extends Model {
    public $table = 'fp_ocean_containers';

    protected $hidden = [
        'id','fp_ocean_tracking_id','created_at','updated_at'
    ];

    protected $fillable = [
        'fp_ocean_tracking_id',
        'container_no',
        'container_type',
        'container_size',
        'current_status_code',
        'current_status_time',
        'description_cn',
        'description',
        'current_status_source',
        'port_code',
        'data_type',
    ];

    protected $casts = [
        'current_status_time' => 'datetime',
        'data_type' => 'array'
    ];


    public function tracking()
    {
        return $this->belongsTo(OceanTracking::class, 'fp_ocean_tracking_id');
    }

    public function status()
    {
        return $this->hasMany(OceanContainerStatus::class, 'fp_ocean_container_id');
    }

    protected static function booted()
    {
        static::addGlobalScope('status', function ($query) {
            $query->with('status');
        });
    }

}