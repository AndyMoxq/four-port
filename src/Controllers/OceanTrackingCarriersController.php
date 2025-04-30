<?php

namespace Tracking\Ocean\Controllers;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Tracking\Ocean\Models\OceanTrackingCarrier;

class OceanTrackingCarriersController extends Controller {
    public function index(){
        return OceanTrackingCarrier::all();
    }
}