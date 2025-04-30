<?php
namespace Tracking\Ocean\Tracking;

class OceanTrackingObj {

    protected $data;

    public function __construct(array $data){
        $this -> data = $data;
    }

    public function getData(): array{
        return $this -> data;
    }

    public function getPlaces(): array{
      $places = [];
      foreach ($this -> getData()['places'] ?? [] as $place) {
        $places[]=[
            'type' => (int) $place['type'] ?? 1,
            'code' => $place['code'],
            'name' => $place['name'],
            'name_cn' => $place['nameCn'] ?? null,
            'name_origin' => $place['nameOrigin'] ?? null,
            'port_time_zone' => $place['portTimeZone'] ?? null,
            'etd' => $this->normalizeDate($place['etd'] ?? null),
            'eta' => $this->normalizeDate($place['eta'] ?? null),
            'atd' => $this->normalizeDate($place['atd'] ?? null),
            'ata' => $this->normalizeDate($place['ata'] ?? null),
            'vessel' => $place['vessel'] ?? null,
            'voyage' => $place['voyage'] ?? null,
            'lat' => $place['lat'] ?? null,
            'lon' => $place['lon'] ?? null,
            'ata_ais' => $this->normalizeDate($place['ataAis'] ?? null),
            'atd_ais' => $this->normalizeDate($place['atdAis'] ?? null),
            'terminal_name_cn' => $place['terminalNameCn'] ?? null,
            'terminal_name_en' => $place['terminalNameEn'] ?? null,
            'terminal_code' => $place['terminalCode'] ?? null,
            'source' => (int) $place['source'] ?? 1
        ];
      }
      return $places;
    }

    protected function normalizeDate($value): ?string
    {
        return trim($value) === '' ? null : $value;
    }

    public function getContainers(): array
    {
        $containers = [];

        foreach ($this->getData()['containers'] ?? [] as $ctnr) {
            $statuses = [];
            foreach ($ctnr['status'] ?? [] as $status) {
                $statuses[] = [
                    'vessel' => $status['vessel'] ?? null,
                    'voyage' => $status['voyage'] ?? null,
                    'event_code' => $status['eventCode'] ?? null,
                    'event_time' => $this->normalizeDate($status['eventTime'] ?? null),
                    'is_esti' => ($status['isEsti'] ?? 'N') === 'Y',
                    'event_place' => $status['eventPlace'] ?? null,
                    'port_time_zone' => $status['portTimeZone'] ?? null,
                    'description_cn' => $status['descriptionCn'] ?? null,
                    'description' => $status['description'] ?? null,
                    'event_description_origin' => $status['eventDescriptionOrigin'] ?? null,
                    'event_place_origin' => $status['eventPlaceOrigin'] ?? null,
                    'port_code' => $status['portCode'] ?? null,
                    'terminal_code' => $status['terminalCode'] ?? null,
                    'terminal_name_cn' => $status['terminalNameCn'] ?? null,
                    'truck_no' => $status['truckNo'] ?? null,
                    'train_station_name' => $status['trainStationName'] ?? null,
                    'train_station_original_code' => $status['trainStationOriginalCode'] ?? null,
                    'train_station_std_code' => $status['trainStationStdCode'] ?? null,
                    'train_name' => $status['trainName'] ?? null,
                    'train_number' => $status['trainNumber'] ?? null,
                    'train_wagon_number' => $status['trainWagonNumber'] ?? null,
                    'container_empty_status' => $status['containerEmptyStatus'] ?? null,
                    'images' => $status['images'] ?? null,
                    'ext_params' => $status['extParams'] ?? null,
                    'remark' => $status['remark'] ?? null,
                    'source' => $status['source'] ?? null,
                    'transport_mode' => $status['transportMode'] ?? null,
                    'level' => $status['level'] ?? null,
                ];
            }

            $containers[] = [
                'container_no' => $ctnr['containerNo'] ?? null,
                'container_type' => $ctnr['containerType'] ?? null,
                'container_size' => $ctnr['containerSize'] ?? null,
                'current_status_code' => $ctnr['currentStatusCode'] ?? null,
                'current_status_time' => $this->normalizeDate($ctnr['currentStatusTime'] ?? null),
                'description_cn' => $ctnr['descriptionCn'] ?? null,
                'description' => $ctnr['description'] ?? null,
                'current_status_source' => $ctnr['currentStatusSource'] ?? null,
                'port_code' => $ctnr['portCode'] ?? null,
                'data_type' => $ctnr['dataType'] ?? [],
                'status' => $statuses,
            ];
        }

        return $containers;
    }

    public function getTerminaPlan(): array
    {
        $data = $this->getData()['terminalPlan'] ?? null;
        if (!$data) {
            return [];
        }
        return [
            'port_code' => $data['portCode'] ?? null,
            'port_name' => $data['portName'] ?? null,
            'vessel' => $data['vessel'] ?? null,
            'voyage' => $data['voyage'] ?? null,
            'terminal_name_cn' => $data['terminalNameCn'] ?? null,
            'terminal_code' => $data['terminalCode'] ?? null,
            'eta' => $this->normalizeDate($data['eta'] ?? null),
            'etd' => $this->normalizeDate($data['etd'] ?? null),
            'ata' => $this->normalizeDate($data['ata'] ?? null),
            'atd' => $this->normalizeDate($data['atd'] ?? null),
            'time_zone' => $data['timeZone'] ?? null,
            'open_time' => $this->normalizeDate($data['openTime'] ?? null),
            'close_time' => $this->normalizeDate($data['closeTime'] ?? null),
            'shipping_agent' => $data['shippingAgent'] ?? null,
            'un_code' => $data['unCode'] ?? null,
            'imo_no' => $data['imoNo'] ?? null,
            'call_sign' => $data['callSign'] ?? null,
            'carrier_code' => $data['carrierCode'] ?? null,
            'line_code' => $data['lineCode'] ?? null,
            'port_close_date' => $this->normalizeDate($data['portCloseDate'] ?? null),
            'cv_close_time' => $this->normalizeDate($data['cvCloseTime'] ?? null),
            'et_anchor' => $this->normalizeDate($data['etAnchor'] ?? null),
            'etd_port' => $this->normalizeDate($data['etdPort'] ?? null),
            'at_anchor' => $this->normalizeDate($data['atAnchor'] ?? null),
            'atd_port' => $this->normalizeDate($data['atdPort'] ?? null),
        ];
    }

    public function getRoutes(): array
    {
        $routes = [];

        foreach ($this->getData()['routes'] ?? [] as $item) {
            $routes[] = [
                'route' => (int) $item['route'] ?? null,
                'pol_code' => $item['polCode'] ?? null,
                'pol_name' => $item['polName'] ?? null,
                'pol_name_origin' => $item['polNameOrigin'] ?? null,
                'pol_zone' => $item['polZone'] ?? null,
                'pod_code' => $item['podCode'] ?? null,
                'pod_name' => $item['podName'] ?? null,
                'pod_name_origin' => $item['podNameOrigin'] ?? null,
                'pod_zone' => $item['podZone'] ?? null,
                'transport_mode' => $item['transportMode'] ?? null,
                'vessel' => $item['vessel'] ?? null,
                'voyage' => $item['voyage'] ?? null,
                'pol_etd' => $this->normalizeDate($item['polEtd'] ?? null),
                'pol_atd' => $this->normalizeDate($item['polAtd'] ?? null),
                'pod_eta' => $this->normalizeDate($item['podEta'] ?? null),
                'pod_ata' => $this->normalizeDate($item['podAta'] ?? null),
                'pol_lat' => $item['polLat'] ?? null,
                'pol_lon' => $item['polLon'] ?? null,
                'pod_lat' => $item['podLat'] ?? null,
                'pod_lon' => $item['podLon'] ?? null,
                'pol_atd_ais' => $this->normalizeDate($item['polAtdAis'] ?? null),
                'pod_ata_ais' => $this->normalizeDate($item['podAtaAis'] ?? null),
                'transport_state' => $item['transportState'] ?? null,
            ];
        }

        return $routes;
    }

    public function getCarrierName(): string{
        return $this -> getData()['carrier']['carrierName'] ?? '';
    }

    public function getCarrierCode(): string{
        return $this -> getData()['carrier']['carrierCode'] ?? '';
    }

    public function getCarrierNameCn(): string{
        return $this -> getData()['carrier']['carrierNameCn'] ?? '';
    }

    public function getDocuments(): array
    {
        $documents = [];

        foreach ($this->getData()['documents'] ?? [] as $item) {
            $documents[] = [
                'container_owner' => $item['containerOwner'] ?? null,
                'container_no' => $item['containerNo'] ?? null,
                'ctn_tare_weight' => $item['ctnTareWeight'] ?? null,
                'ctn_gross_weight' => $item['ctnGrossWeight'] ?? null,
                'dangerous_goods_grade' => $item['dangerousGoodsGrade'] ?? null,
                'un_code' => $item['unCode'] ?? null,
                'container_type' => $item['containerType'] ?? null,
                'container_size' => $item['containerSize'] ?? null,
                'come_out_mode' => $item['comeOutMode'] ?? null,
                'approach_mode' => $item['approachMode'] ?? null,
                'declaration_no' => $item['declarationNo'] ?? null,
                'container_height' => $item['containerHeight'] ?? null,
                'pre_record_time' => $this->normalizeDate($item['preRecordTime'] ?? null),
                'report_send_time' => $this->normalizeDate($item['reportSendTime'] ?? null),
                'danger_flag' => $item['dangerFlag'] ?? null,
                'carrier_operate' => $item['carrierOperate'] ?? null,
                'temperature' => $item['temperature'] ?? null,
                'vgm_weight' => $item['vgmWeight'] ?? null,
                'vgm_edi_time' => $item['vgmEdiTime'] ?? null,
                'vgm_details_info' => $item['vgmDetailsInfo'] ?? null,
                'seal_number' => $item['sealNumber'] ?? null,
                'pol_code' => $item['polCode'] ?? null,
                'pod_code' => $item['podCode'] ?? null,
                'bills' => $item['bills'] ?? null,
            ];
        }

        return $documents;
    }

    public function getEirs(): array
    {
        $eirs = [];

        foreach ($this->getData()['eir'] ?? [] as $item) {
            $eirs[] = [
                'container_no' => $item['containerNo'] ?? null,
                'vessel' => $item['vessel'] ?? null,
                'voyage' => $item['voyage'] ?? null,
                'un_code' => $item['unCode'] ?? null,
                'transport' => $item['transport'] ?? null,
                'discharge_port_code' => $item['dischargePortCode'] ?? null,
                'trade_nw' => $item['tradeNW'] ?? null,
                'destination_port' => $item['destinationPort'] ?? null,
                'forwarder_code' => $item['forwarderCode'] ?? null,
                'cp_code' => $item['cpCode'] ?? null,
                'container_owner' => $item['containerOwner'] ?? null,
                'size_type' => $item['sizeType'] ?? null,
                'sequence' => $item['sequence'] ?? null,
                'weight' => $item['weight'] ?? null,
                'ctnnet_weight' => $item['ctnnetWeight'] ?? null,
                'shiping_mark' => $item['shipingMark'] ?? null,
                'part_name' => $item['partName'] ?? null,
                'seal_no' => $item['sealNo'] ?? null,
                'yard_name' => $item['yardName'] ?? null,
                'tk_validity' => $this->normalizeDate($item['tkValidity'] ?? null),
                'outdoor_status' => $this->normalizeDate($item['outdoorStatus'] ?? null),
                'begin_time' => $this->normalizeDate($item['beginTime'] ?? null),
                'end_time' => $this->normalizeDate($item['endTime'] ?? null),
                'custom_release' => $item['customRelease'] ?? null,
                'book_time' => $this->normalizeDate($item['bookTime'] ?? null),
                'tk_truck_no' => $item['tkTruckNo'] ?? null,
                'jz_truck_no' => $item['jzTruckNo'] ?? null,
                'tz_truck_no' => $item['tzTruckNo'] ?? null,
                'tk_license' => $item['tkLicense'] ?? null,
                'jz_license' => $item['jzLicense'] ?? null,
            ];
        }

        return $eirs;
    }

    public function getTrackingData(): array{
        $data = $this ->getData();
        return [
            'subscription_id' => $data['subscriptionId'],
            'bill_no' => $data['billNo'],
            'sailing_id' => $data['sailingId'] ?? null,
            'carrier_code' => $this -> getCarrierCode(),
            'carrier_name' => $this -> getCarrierName(),
            'carrier_name_cn' => $this -> getCarrierNameCn(),
            'end_time' => $data['endTime'] ?? null,
            'first_vessel' => $data['firstVessel'] ?? [],
            'data_type' => $data['dataType'] ?? []
        ];
    }

}