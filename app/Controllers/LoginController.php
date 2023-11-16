<?php

namespace App\Controllers;

class LoginController extends BaseController
{
    public function index(): string
    {

        return view('index');
    }

    public function login($i) {
        return redirect()->to("/Home/index");
    }
}
