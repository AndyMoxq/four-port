<?php
namespace Tracking\Ocean\Models;

use Illuminate\Database\Eloquent\Model;

class OceanTrackingEir extends Model
{
    protected $table = 'fp_ocean_tracking_eirs';

    protected $fillable = [
        'fp_ocean_tracking_id',
        'container_no',
        'vessel',
        'voyage',
        'un_code',
        'transport',
        'discharge_port_code',
        'trade_nw',
        'destination_port',
        'forwarder_code',
        'cp_code',
        'container_owner',
        'size_type',
        'sequence',
        'weight',
        'ctnnet_weight',
        'shiping_mark',
        'part_name',
        'seal_no',
        'yard_name',
        'tk_validity',
        'outdoor_status',
        'begin_time',
        'end_time',
        'custom_release',
        'book_time',
        'tk_truck_no',
        'jz_truck_no',
        'tz_truck_no',
        'tk_license',
        'jz_license',
    ];

    protected $casts = [
        'tk_validity' => 'datetime',
        'outdoor_status' => 'datetime',
        'begin_time' => 'datetime',
        'end_time' => 'datetime',
        'book_time' => 'datetime',
    ];

    /**
     * Get the tracking record that owns the EIR.
     */
    public function tracking()
    {
        return $this->belongsTo(OceanTracking::class, 'fp_ocean_tracking_id');
    }
}

/* Schema::create('fp_ocean_tracking_eirs',function (Blueprint $table){
    $table->id();
    $table->foreignId('fp_ocean_tracking_id')->constrained('fp_ocean_trackings')->onDelete('cascade');
    $table->string('container_no')->nullable()->comment('箱号');
    $table->string('vessel')->nullable()->comment('船名');
    $table->string('voyage')->nullable()->comment('航次');
    $table->string('un_code')->nullable()->comment('UN代码');
    $table->string('transport')->nullable()->comment('中转港');
    $table->string('discharge_port_code')->nullable()->comment('卸货港');
    $table->string('trade_nw')->nullable()->comment('内外贸');
    $table->string('destination_port')->nullable()->comment('目的港');
    $table->string('forwarder_code')->nullable()->comment('货代结算代码');
    $table->string('cp_code')->nullable()->comment('码头');
    $table->string('container_owner')->nullable()->comment('箱主');
    $table->string('size_type')->nullable()->comment('箱型尺寸');
    $table->string('sequence')->nullable()->comment('提箱序列号');
    $table->string('weight')->nullable()->comment('皮重');
    $table->string('ctnnet_weight')->nullable()->comment('货毛重');
    $table->string('shiping_mark')->nullable()->comment('唛头');
    $table->string('part_name')->nullable()->comment('品名');
    $table->string('seal_no')->nullable()->comment('铅封号');
    $table->string('yard_name')->nullable()->comment('提箱码头/堆场');
    $table->dateTime('tk_validity')->nullable()->comment('提箱有效期');
    $table->dateTime('outdoor_status')->nullable()->comment('提空状态');
    $table->dateTime('begin_time')->nullable()->comment('进港预约开始时间');
    $table->dateTime('end_time')->nullable()->comment('进港预约结束时间');
    $table->string('custom_release')->nullable()->comment('海关放行标记');
    $table->dateTime('book_time')->nullable()->comment('提重预约时间');
    $table->string('tk_truck_no')->nullable()->comment('提空作业号');
    $table->string('jz_truck_no')->nullable()->comment('进重作业号');
    $table->string('tz_truck_no')->nullable()->comment('提重作业号');
    $table->string('tk_license')->nullable()->comment('提空车牌号');
    $table->string('jz_license')->nullable()->comment('进重车牌号');
    $table->timestamps();
}); */