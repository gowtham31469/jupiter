<?php

namespace App\Http\Controllers\ErpUser\Jobmanager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpectrraJCContoller extends Controller
{
    public function __construct()
    {
        $this->middleware('erpuser');
   
    }
}
