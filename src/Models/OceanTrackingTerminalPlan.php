<?php
namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

class OceanTrackingTerminalPlan extends Model
{
    protected $table = 'fp_ocean_tracking_terminal_plans';

    protected $hidden =[
        'id','fp_ocean_tracking_id','created_at','updated_at'
    ];

    protected $fillable = [
        'fp_ocean_tracking_id',
        'port_code',
        'port_name',
        'vessel',
        'voyage',
        'terminal_name_cn',
        'terminal_code',
        'eta',
        'etd',
        'ata',
        'atd',
        'time_zone',
        'open_time',
        'close_time',
        'shipping_agent',
        'un_code',
        'imo_no',
        'call_sign',
        'carrier_code',
        'line_code',
        'port_close_date',
        'cv_close_time',
        'et_anchor',
        'etd_port',
        'at_anchor',
        'atd_port',
    ];

    protected $casts = [
        'eta' => 'datetime',
        'etd' => 'datetime',
        'ata' => 'datetime',
        'atd' => 'datetime',
        'open_time' => 'datetime',
        'close_time' => 'datetime',
        'port_close_date' => 'datetime',
        'cv_close_time' => 'datetime',
        'et_anchor' => 'datetime',
        'etd_port' => 'datetime',
        'at_anchor' => 'datetime',
        'atd_port' => 'datetime',
    ];

    public function tracking()
    {
        return $this->belongsTo(OceanTracking::class, 'fp_ocean_tracking_id');
    }
}