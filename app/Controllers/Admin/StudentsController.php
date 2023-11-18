<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
class StudentsController extends BaseController
{
    public function courses($data): string
    {
        // redirect to
        return view('Admin');
    }
}
