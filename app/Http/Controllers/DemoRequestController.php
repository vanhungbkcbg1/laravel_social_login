<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemoRequest;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    //

    public function index(){
        return view("request.index");
    }

    public function store(DemoRequest $request){
        // pass validation rule setting in DemoRequest
        dd($request->all());

    }
}
