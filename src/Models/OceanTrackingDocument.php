<?php

namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

class OceanTrackingDocument extends Model
{
    protected $table = 'fp_ocean_tracking_documents';

    protected $hidden =[
        'id','fp_ocean_tracking_id','created_at','updated_at'
    ];

    protected $fillable = [
        'fp_ocean_tracking_id',
        'container_owner',
        'container_no',
        'ctn_tare_weight',
        'ctn_gross_weight',
        'dangerous_goods_grade',
        'un_code',
        'container_type',
        'container_size',
        'come_out_mode',
        'approach_mode',
        'declaration_no',
        'container_height',
        'pre_record_time',
        'report_send_time',
        'danger_flag',
        'carrier_operate',
        'temperature',
        'vgm_weight',
        'vgm_edi_time',
        'vgm_details_info',
        'seal_number',
        'pol_code',
        'pod_code',
        'bills',
    ];

    protected $casts = [
        'pre_record_time' => 'datetime',
        'report_send_time' => 'datetime',
        'vgm_edi_time' => 'datetime',
        'bills' => 'array',
        'vgm_details_info' => 'array',
    ];

    public function tracking()
    {
        return $this->belongsTo(OceanTracking::class, 'fp_ocean_tracking_id');
    }
}