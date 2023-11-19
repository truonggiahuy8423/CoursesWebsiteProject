<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\UserModel;

class StudentsController extends BaseController
{
    public function index(): string
    {
        
        // Verify login status
        if (!session()->has('id_user')) {
            return redirect()->to('/');
        }
        // Query data 
        $model = new UserModel();
        $navbar_data = array();
        $main_layout_data = array();

        //left navigation chosen value
        $main_layout_data['left_nav_chosen_value'] = 3;

        if (session()->get('role') == 1) { // Admin
            $result = $model->executeCustomQuery(
                'SELECT ad.ho_ten, users.anh_dai_dien
                FROM users
                INNER JOIN ad ON users.id_ad = ad.id_ad
                WHERE users.id_user = '.session()->get("id_user"));
            $navbar_data['username'] = "{$result[0]['ho_ten']}";
            $navbar_data['role'] = 'Adminstrator';
            $navbar_data['avatar_data'] = "{$result[0]['anh_dai_dien']}";
        }
        else if (session()->get('role') == 2) { // Giang vien
 
        }
        else if (session()->get('role') == 3) { // Hoc vien
 
        }
        
        $main_layout_data['navbar'] = view('Admin\ViewCell\NavBar', $navbar_data);
        return view('Admin\ViewLayout\MainLayout', $main_layout_data);
    }
}
