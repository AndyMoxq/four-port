<?php

namespace Tracking\Ocean\Traits;

use Illuminate\Support\Facades\DB;
use Tracking\Ocean\Models\OceanTracking;
use Tracking\Ocean\Tracking\OceanTrackingObj;

trait UpdateOceanTrackingTrait
{
    /**
     * Update tracking and all associated relations in a single transaction.
     */
    public function updateOceanTracking(array $data)
    {
        $oceanTracking = new OceanTrackingObj($data);
        return DB::transaction(function () use ($oceanTracking) {
            $tracking = $this->updateMainRecord($oceanTracking->getTrackingData());
            $this->updatePlaces($tracking, $oceanTracking->getPlaces());
            $this->updateContainer($tracking, $oceanTracking->getContainers());
            $this->updateTerminaPlan($tracking, $oceanTracking->getTerminaPlan());
            $this->updateRoutes($tracking, $oceanTracking->getRoutes());
            $this->updateDocuments($tracking, $oceanTracking->getDocuments());
            $this->updateEirs($tracking, $oceanTracking->getEirs());

            return $tracking->load([
                'places',
                'routes',
                'containers.status',
                'terminalPlan',
                'documents',
                'eirs'
            ]);
        });
    }

    /**
     * Update associated EIR records for the tracking.
     */
    private function updateEirs(OceanTracking $tracking, array $eirs)
    {
        if (!empty($eirs)) {
            DB::transaction(function () use ($tracking, $eirs) {
                $tracking->eirs()->delete();
                $tracking->eirs()->createMany($eirs);
            });
        }
    }

    /**
     * Update associated document records for the tracking.
     */
    private function updateDocuments(OceanTracking $tracking, array $documents)
    {
        if (!empty($documents)) {
            DB::transaction(function () use ($tracking, $documents) {
                $tracking->documents()->delete();
                $tracking->documents()->createMany($documents);
            });
        }
    }

    /**
     * Update associated route records for the tracking.
     */
    private function updateRoutes(OceanTracking $tracking, array $routes)
    {
        if (!empty($routes)) {
            DB::transaction(function () use ($tracking, $routes) {
                $tracking->routes()->delete();
                $tracking->routes()->createMany($routes);
            });
        }
    }

    /**
     * Update or create the terminal plan record for the tracking.
     */
    private function updateTerminaPlan(OceanTracking $tracking, array $terminaPlanData)
    {
        $tracking->terminalPlan()->updateOrCreate([], $terminaPlanData);
    }

    /**
     * Update or create the main tracking record based on subscription_id and bill_no.
     */
    private function updateMainRecord(array $data): OceanTracking
    {
        return OceanTracking::updateOrCreate(
            [
                'subscription_id' => $data['subscription_id'],
                'bill_no' => $data['bill_no']
            ],
            $data
        );
    }

    /**
     * Replace all associated containers and their statuses for the tracking.
     */
    private function updateContainer(OceanTracking $tracking, array $containers)
    {
        if (!empty($containers)) {
            DB::transaction(function () use ($tracking, $containers) {
                foreach ($tracking->containers as $container) {
                    $container->status()->delete();
                }
                $tracking->containers()->delete();

                foreach ($containers as $ctnr) {
                    $container = $tracking->containers()->create($ctnr);
                    $container->status()->createMany($ctnr['status'] ?? []);
                }
            });
        }
    }

    /**
     * Replace all associated places for the tracking.
     */
    private function updatePlaces(OceanTracking $tracking, array $places)
    {
        if (!empty($places)) {
            DB::transaction(function () use ($tracking, $places) {
                $tracking->places()->delete();
                $tracking->places()->createMany($places);
            });
        }
    }

    /**
     * Update or create a tracking record from subscription data.
     */
    public function updateSubscribeData(array $data)
    {
        return OceanTracking::updateOrCreate(
            [
                'subscription_id' => $data['subscriptionId'],
                'bill_no' => $data['billNo']
            ],
            [
                'carrier_code' => $data['carrierCode'],
                'data_type' => $data['dataType'] ?? [],
            ]
        );
    }
}