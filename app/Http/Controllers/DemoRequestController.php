<?php

namespace App\Http\Controllers;

use App\Http\Requests\DemoRequest;
use App\Lib\SimpleLib;
use Illuminate\Http\Request;

class DemoRequestController extends Controller
{
    //
    /** @var SimpleLib */
    protected $lib;
    public function __construct(SimpleLib $lib)
    {
        $this->lib =$lib;
    }

    public function index(){
        // demo set param when auto resolve in laravel
        //dd($this->lib->getContainer());
        return view("request.index");
    }

    public function store(DemoRequest $request){
        // pass validation rule setting in DemoRequest
        dd($request->all());

    }
}
