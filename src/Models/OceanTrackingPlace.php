<?php

namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

class OceanTrackingPlace extends Model {

    public $table = 'fp_ocean_tracking_places';

    public $timestamps = false;

    protected $hidden = [
        'id','fp_ocean_tracking_id'
    ];

    protected $fillable = [
        'fp_ocean_tracking_id',
        'type',
        'code',
        'name',
        'name_cn',
        'name_origin',
        'port_time_zone',
        'etd',
        'eta',
        'atd',
        'ata',
        'vessel',
        'voyage',
        'lat',
        'lon',
        'ata_ais',
        'atd_ais',
        'terminal_name_cn',
        'terminal_name_en',
        'terminal_code',
        'source',
    ];

    protected $casts = [
        'etd' => 'datetime',
        'eta' => 'datetime',
        'atd' => 'datetime',
        'ata' => 'datetime',
        'ata_ais' => 'datetime',
        'atd_ais' => 'datetime',
        'lat' => 'decimal:6',
        'lon' => 'decimal:6',
    ];

    public function tracking(){
        return $this -> belongsTo(OceanTracking::class);
    }

    public function getType(): string {
        $map = [
            1 => '接货地',
            2 => '起始港',
            3 => '中转港',
            4 => '目的港',
            5 => '交货地',
            10 => '未知',
        ];

        return $map[$this->type] ?? '未知';
    }

    public function getSource(): string {
        $map = [
            0 => '港区',
            1 => '船司',
            5 => '码头船期',
            10 => '码头堆场',
            11 => '港前多式联运-铁路',
            12 => '港前多式联运-卡车',
            13 => '港前多式联运-水路',
            14 => '港前多式联运-驳船',
            15 => '港内作业',
        ];

        return $map[$this->source] ?? '未知';
    }
}