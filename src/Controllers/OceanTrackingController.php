<?php
namespace Tracking\Ocean\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Tracking\Ocean\Traits\UpdateOceanTrackingTrait;

class OceanTrackingController extends Controller {
    use UpdateOceanTrackingTrait;
    public function update(Request $request){
        $request -> validate([
            'subscriptionId'=>'required',
            'billNo' => 'required'
        ]);
        try {
            $tracking = $this -> updateOceanTracking($request -> all());
            
        } catch (\Throwable $th) {
            Log::error("OceanTracking update failed", [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);
            return response()->json([
                'code' => 500,
                'message' => 'Update failed',
                'error' => $th->getMessage()
            ], 500);
        }
        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $tracking ?? []
        ],200);
        
    }
}