<?php

namespace App\Controllers;

class ProfileController extends BaseController
{
    public function index($role)
    {
        if ($role == "true") {
            // giang vien
            $id_giang_vien = $_GET['id'];
            return view('ProfilePage');
        } else {
            // hoc vien
            $id_hoc_vien = $_GET['id'];
            return view('ProfilePage');
        }
    }
}