<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fp_ocean_trackings', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_id')->index();
            $table->string('bill_no')->index();
            $table->string('sailing_id')->nullable();
            $table->string('carrier_code')->nullable();
            $table->string('carrier_name')->nullable();
            $table->string('carrier_name_cn')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->text('first_vessel')->nullable();
            $table->text('data_type')->nullable();
            $table->timestamps();
        });

        Schema::create('fp_ocean_tracking_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fp_ocean_tracking_id')->constrained('fp_ocean_trackings')->onDelete('cascade');
            $table->integer('type')->nullable()->comment('发生地类型: 1接货地; 2起始港; 3中转港; 4目的港; 5交货地; 10未知');
            $table->string('code',5)->nullable()->comment('地点五字码');
            $table->string('name')->nullable()->comment('地点英文名');
            $table->string('name_cn')->nullable()->comment('地点中文名');
            $table->string('name_origin')->nullable()->comment('地点原始值');
            $table->string('port_time_zone',5)->nullable()->comment('时区');
            $table->dateTime('etd')->nullable()->comment('预计离开时间');
            $table->dateTime('eta')->nullable()->comment('预计到达时间');
            $table->dateTime('atd')->nullable()->comment('实际离开时间');
            $table->dateTime('ata')->nullable()->comment('实际到达时间');
            $table->string('vessel')->nullable()->comment('船名');
            $table->string('voyage')->nullable()->comment('航次');
            $table->decimal('lat', 10, 6)->nullable()->comment('纬度');
            $table->decimal('lon', 10, 6)->nullable()->comment('经度');
            $table->dateTime('ata_ais')->nullable()->comment('AIS实际到港时间');
            $table->dateTime('atd_ais')->nullable()->comment('AIS实际离港时间');
            $table->string('terminal_name_cn')->nullable()->comment('码头中文名');
            $table->string('terminal_name_en')->nullable()->comment('码头英文名');
            $table->string('terminal_code')->nullable()->comment('码头代码');
            $table->integer('source')->nullable()->comment('数据来源'); //0港区; 1船司; 5码头船期; 10码头堆场; 11/12/13/14港前多式联运; 15港内作业
        });

        Schema::create('fp_ocean_tracking_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fp_ocean_tracking_id')->constrained('fp_ocean_trackings')->onDelete('cascade');
            $table->integer('route')->nullable()->comment('第几段');
            $table->string('pol_code',10)->nullable()->comment('起始地的港口五字码');
            $table->string('pol_name')->nullable()->comment('起始地的港口名称');
            $table->string('pol_name_origin')->nullable()->comment('起始地的港口名称原始值');
            $table->string('pol_zone')->nullable()->comment('起始地的时区');
            $table->string('pod_code',10)->nullable()->comment('目的地的港口五字码');
            $table->string('pod_name')->nullable()->comment('目的地的港口名称');
            $table->string('pod_name_origin')->nullable()->comment('目的地的港口名称原始值');
            $table->string('pod_zone')->nullable()->comment('目的地的时区');
            $table->string('transport_mode')->nullable()->comment('运输方式');//: VESSEL 海运;  TRUCK 卡车;  RAIL 火车;
            $table->string('vessel')->nullable()->comment('船名');
            $table->string('voyage')->nullable()->comment('航次');
            $table->dateTime('pol_etd')->nullable()->comment('起始地的预计离港时间');
            $table->dateTime('pol_atd')->nullable()->comment('起始地的实际离港时间');
            $table->dateTime('pod_eta')->nullable()->comment('目的地的预计到港时间');
            $table->dateTime('pod_ata')->nullable()->comment('目的地的实际到港时间');
            $table->decimal('pol_lat', 10, 6)->nullable()->comment('起始地的纬度');
            $table->decimal('pol_lon', 10, 6)->nullable()->comment('起始地的经度');
            $table->decimal('pod_lat', 10, 6)->nullable()->comment('目的地的纬度');
            $table->decimal('pod_lon', 10, 6)->nullable()->comment('目的地的经度');
            $table->dateTime('pol_atd_ais')->nullable()->comment('起始地的AIS离港时间');
            $table->dateTime('pod_ata_ais')->nullable()->comment('目的地的AIS到港时间');
            $table->string('transport_state')->nullable()->comment('运输状态'); //  NOT_START未开始; IN_TRACKING运输中; HAS_DONE已结束;
            $table->timestamps();
        });

        Schema::create('fp_ocean_containers',function (Blueprint $table){
            $table->id();
            $table->foreignId('fp_ocean_tracking_id')->constrained('fp_ocean_trackings')->onDelete('cascade');
            $table->string('container_no',15)->nullable()->comment('箱号');
            $table->string('container_type',5)->nullable()->comment('箱型');
            $table->string('container_size',5)->nullable()->comment('箱尺寸');
            $table->string('current_status_code',10)->nullable()->comment('当前状态代码');
            $table->dateTime('current_status_time')->nullable()->comment('当前状态时间');
            $table->string('description_cn')->nullable()->comment('当前状态中文描述');
            $table->string('description')->nullable()->comment('当前状态英文描述');
            $table->integer('current_status_source')->nullable()->comment('当前状态数据来源');
            $table->string('port_code',10)->nullable()->comment('当前状态发生地五字码');
            $table->text('data_type')->nullable()->comment('箱粒度');
            $table->timestamps();
        });

        Schema::create('fp_ocean_container_status',function (Blueprint $table){
            $table->id();
            $table->foreignId('fp_ocean_container_id')->constrained('fp_ocean_containers')->onDelete('cascade');
            $table->string('vessel')->nullable()->comment('船名');
            $table->string('voyage')->nullable()->comment('航次');
            $table->string('event_code')->nullable()->comment('节点状态代码');
            $table->dateTime('event_time')->nullable()->comment('节点状态时间');
            $table->boolean('is_esti')->nullable()->comment('是否是预计');
            $table->string('event_place')->nullable()->comment('发生地');
            $table->string('port_time_zone')->nullable()->comment('发生地所在时区');
            $table->string('description_cn')->nullable()->comment('状态中文描述');
            $table->string('description')->nullable()->comment('状态英文描述');
            $table->string('event_description_origin')->nullable()->comment('状态原始值');
            $table->string('event_place_origin')->nullable()->comment('发生地原始值');
            $table->string('port_code')->nullable()->comment('港口五字码');
            $table->string('terminal_code')->nullable()->comment('码头代码');
            $table->string('terminal_name_cn')->nullable()->comment('码头名称');
            $table->string('truck_no')->nullable()->comment('卡车车牌号');
            $table->string('train_station_name')->nullable()->comment('火车站点中文名称');
            $table->string('train_station_original_code')->nullable()->comment('火车站点原始代码');
            $table->string('train_station_std_code')->nullable()->comment('火车站点标准代码');
            $table->string('train_name')->nullable()->comment('火车名称');
            $table->string('train_number')->nullable()->comment('火车车次');
            $table->string('train_wagon_number')->nullable()->comment('火车车皮号');
            $table->string('container_empty_status')->nullable()->comment('箱储状态');
            $table->text('images')->nullable()->comment('图片');
            $table->text('ext_params')->nullable()->comment('扩展字段');
            $table->text('remark')->nullable()->comment('备注');
            $table->string('source')->nullable()->comment('数据来源');
            $table->string('transport_mode')->nullable()->comment('运输方式');
            $table->string('level')->nullable()->comment('重要度');
            $table->timestamps();
        });

        Schema::create('fp_ocean_tracking_documents',function (Blueprint $table){
            $table->id();
            $table->foreignId('fp_ocean_tracking_id')->constrained('fp_ocean_trackings')->onDelete('cascade');
            $table->string('container_owner')->nullable()->comment('箱主代码');
            $table->string('container_no')->nullable()->comment('箱号');
            $table->string('ctn_tare_weight')->nullable()->comment('箱皮重');
            $table->string('ctn_gross_weight')->nullable()->comment('箱总重');
            $table->string('dangerous_goods_grade')->nullable()->comment('危险品等级');
            $table->string('un_code')->nullable()->comment('联合国编号');
            $table->string('container_type')->nullable()->comment('箱型');
            $table->string('container_size')->nullable()->comment('尺寸');
            $table->string('come_out_mode')->nullable()->comment('出场方式');
            $table->string('approach_mode')->nullable()->comment('进场方式');
            $table->string('declaration_no')->nullable()->comment('报关单号');
            $table->string('container_height')->nullable()->comment('箱高度');
            $table->dateTime('pre_record_time')->nullable()->comment('箱预录时间');
            $table->dateTime('report_send_time')->nullable()->comment('运抵报文发送时间');
            $table->string('danger_flag')->nullable()->comment('危险品标识');
            $table->string('carrier_operate')->nullable()->comment('船公司操作');
            $table->string('temperature')->nullable()->comment('冷藏箱温度');
            $table->string('vgm_weight')->nullable()->comment('VGM船公司报文重量');
            $table->string('vgm_edi_time')->nullable()->comment('VGM报文时间');
            $table->text('vgm_details_info')->nullable()->comment('VGM详细信息');
            $table->string('seal_number')->nullable()->comment('铅封号');
            $table->string('pol_code')->nullable()->comment('起运港代码');
            $table->string('pod_code')->nullable()->comment('卸运港代码');
            $table->text('bills')->nullable()->comment('提单信息');
            $table->timestamps();
        });

        Schema::create('fp_ocean_tracking_eirs',function (Blueprint $table){
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
        });

        Schema::create('fp_ocean_tracking_terminal_plans',function (Blueprint $table){
            $table->id();
            $table->foreignId('fp_ocean_tracking_id')->constrained('fp_ocean_trackings')->onDelete('cascade');
            $table->string('port_code')->nullable()->comment('港口代码');
            $table->string('port_name')->nullable()->comment('港口名称');
            $table->string('vessel')->nullable()->comment('船名');
            $table->string('voyage')->nullable()->comment('航次');
            $table->string('terminal_name_cn')->nullable()->comment('码头中文名');
            $table->string('terminal_code')->nullable()->comment('码头代码');
            $table->dateTime('eta')->nullable()->comment('预计靠泊时间');
            $table->dateTime('etd')->nullable()->comment('预计离泊时间');
            $table->dateTime('ata')->nullable()->comment('实际靠泊时间');
            $table->dateTime('atd')->nullable()->comment('实际离泊时间');
            $table->string('time_zone')->nullable()->comment('地点所在时区');
            $table->dateTime('open_time')->nullable()->comment('开港时间');
            $table->dateTime('close_time')->nullable()->comment('截港时间');
            $table->string('shipping_agent')->nullable()->comment('船代理简称');
            $table->string('un_code')->nullable()->comment('UN代码');
            $table->string('imo_no')->nullable()->comment('IMO呼号');
            $table->string('call_sign')->nullable()->comment('呼号');
            $table->string('carrier_code')->nullable()->comment('船公司代码');
            $table->string('line_code')->nullable()->comment('航线代码');
            $table->dateTime('port_close_date')->nullable()->comment('截单时间');
            $table->dateTime('cv_close_time')->nullable()->comment('截关时间');
            $table->dateTime('et_anchor')->nullable()->comment('预计抵港时间');
            $table->dateTime('etd_port')->nullable()->comment('预计离港时间');
            $table->dateTime('at_anchor')->nullable()->comment('实际抵港时间');
            $table->dateTime('atd_port')->nullable()->comment('实际离港时间');
            $table->timestamps();
        });

        Schema::create('fp_ocean_tracking_carriers',function (Blueprint $table){
            $table->id();
            $table->string('carrier_code');
            $table->string('carrier_name');
            $table->boolean('bol_method')->default(true);
            $table->boolean('container_method')->default(true);
        });
        
        DB::table('fp_ocean_tracking_carriers')->insert([
            ['carrier_code' => 'ACI', 'carrier_name' => '亚利安莎', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'ACL', 'carrier_name' => '大西洋', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'ANL', 'carrier_name' => '澳航', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'ATL', 'carrier_name' => '安通', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'BAL', 'carrier_name' => '博亚海运', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'CCNI', 'carrier_name' => '南美智利', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'CKL', 'carrier_name' => '天敬海运', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'CMA', 'carrier_name' => '达飞', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'CNC', 'carrier_name' => '正利', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'COSCO', 'carrier_name' => '中远海', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'CUL', 'carrier_name' => '中联', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'DYS', 'carrier_name' => '东暎', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'EMC', 'carrier_name' => '长荣海运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'ESL', 'carrier_name' => '阿联酋航运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'GSL', 'carrier_name' => '金星轮船', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'HAL', 'carrier_name' => '兴亚', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'HASCO', 'carrier_name' => '海华', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'HBS', 'carrier_name' => '汉堡南美', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'HMM', 'carrier_name' => '现代', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'HPL', 'carrier_name' => '赫伯罗特', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'IAL', 'carrier_name' => '运达', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'JINJIANG', 'carrier_name' => '锦江', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'KKC', 'carrier_name' => '神原汽船', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'KMTC', 'carrier_name' => '高丽', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'KWE', 'carrier_name' => '近铁', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'MARI', 'carrier_name' => '玛丽亚那', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'MATS', 'carrier_name' => '美森轮船', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'MCC', 'carrier_name' => '穆勒航运(SEALAND)', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'MSC', 'carrier_name' => '地中海航运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'MSH', 'carrier_name' => '民生轮船', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'MSK', 'carrier_name' => '马士基', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'NGPL', 'carrier_name' => '太古船务', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'NOSCO', 'carrier_name' => '宁波远洋', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'NSS', 'carrier_name' => '南星海运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'ONE', 'carrier_name' => '海洋网联', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'OOCL', 'carrier_name' => '东方海外', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'PCL', 'carrier_name' => '泛洲海运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'PIL', 'carrier_name' => '太平', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'RCL', 'carrier_name' => '宏海箱运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'SAF', 'carrier_name' => '南非轮船', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'SINO', 'carrier_name' => '中外运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'SINOKOR', 'carrier_name' => '长锦', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'SITC', 'carrier_name' => '新海丰', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'SLS', 'carrier_name' => '海领船务', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'SML', 'carrier_name' => '森罗', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'SSL', 'carrier_name' => '萨姆达拉', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'STX', 'carrier_name' => '世腾船务', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'TARROS', 'carrier_name' => '塔罗斯', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'TJFH', 'carrier_name' => '致远航运', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'TRAWIND', 'carrier_name' => '信风船务', 'bol_method' => true, 'container_method' => false],
            ['carrier_code' => 'TSL', 'carrier_name' => '德祥', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'WHL', 'carrier_name' => '万海', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'YML', 'carrier_name' => '阳明', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'ZIM', 'carrier_name' => '以星', 'bol_method' => true, 'container_method' => true],
            ['carrier_code' => 'ZSH', 'carrier_name' => '中谷', 'bol_method' => true, 'container_method' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('fp_ocean_tracking_carriers');
        Schema::dropIfExists('fp_ocean_tracking_terminal_plans');
        Schema::dropIfExists('fp_ocean_tracking_eirs');
        Schema::dropIfExists('fp_ocean_container_status');
        Schema::dropIfExists('fp_ocean_containers');
        Schema::dropIfExists('fp_ocean_tracking_documents');
        Schema::dropIfExists('fp_ocean_tracking_routes');
        Schema::dropIfExists('fp_ocean_tracking_places');
        Schema::dropIfExists('fp_ocean_trackings');
    }
};