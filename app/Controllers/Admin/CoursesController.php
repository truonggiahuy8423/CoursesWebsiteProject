<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Controllers\LoginController;

class CoursesController extends BaseController
{
    public function index(): string
    {
        (new LoginController())->check_session();
        $data['navbar'] = view('Admin\ViewCell\NavBar');
        // redirect to
        return view('Admin\ViewLayout\MainLayout', $data);
    }



}
