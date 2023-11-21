<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
class Home extends BaseController
{
    public function index($data): string
    {
       
        return view('Test1');
    }
    public function index1(): string
    {
        echo $_GET['id'];
        return view('Test1');
    }
    public function index2(): string
    {
       
        return view('Test2');
    }
}