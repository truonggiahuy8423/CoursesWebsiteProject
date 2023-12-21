<?php
namespace App\Controllers\Admin;
use App\Models\UserModel;
use App\Controllers\BaseController;
use Config\View;

class UsersController extends BaseController
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
        $main_layout_data['left_nav_chosen_value'] = 4;

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
        $main_layout_data['mainsection'] = view('Admin\ViewLayout\UsersListSectionLayout', $navbar_data);
        return view('Admin\ViewLayout\MainLayout', $main_layout_data);
    }
    public function getUsersList() {
        $data = array();
        $currentPage = $this->request->getVar("page");
        $model = new UserModel();
        $recordsPerPage = 20;
        $offset = ($currentPage - 1) * $recordsPerPage;
        
        $users = $model->executeCustomQuery(
            "(
                SELECT users.*, ad.ho_ten, ad.email
                FROM users
                INNER JOIN ad ON users.id_ad = ad.id_ad AND users.id_ad IS NOT NULL
                ORDER BY users.id_ad ASC
            )
            UNION
            (
                SELECT users.*, giang_vien.ho_ten, giang_vien.email
                FROM users
                INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien AND users.id_giang_vien IS NOT NULL
                ORDER BY users.id_giang_vien ASC
            )
            UNION
            (
                SELECT users.*, hoc_vien.ho_ten, hoc_vien.email
                FROM users
                INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien AND users.id_hoc_vien IS NOT NULL
                ORDER BY users.id_hoc_vien ASC
            ) LIMIT $recordsPerPage OFFSET $offset "
        );
    
        // Base64 encode the 'anh_dai_dien' field for each user
        foreach ($users as &$user) {
            if ($user["anh_dai_dien"] != null)
                $user["anh_dai_dien"] = base64_encode($user["anh_dai_dien"]);
        }
    
        $data["users"] = $users;
    
        $totalUsers = count($model->executeCustomQuery("(
            SELECT users.*, ad.ho_ten, ad.email
            FROM users
            INNER JOIN ad ON users.id_ad = ad.id_ad AND users.id_ad IS NOT NULL
            ORDER BY users.id_ad ASC
        )
        UNION
        (
            SELECT users.*, giang_vien.ho_ten, giang_vien.email
            FROM users
            INNER JOIN giang_vien ON users.id_giang_vien = giang_vien.id_giang_vien AND users.id_giang_vien IS NOT NULL
            ORDER BY users.id_giang_vien ASC
        )
        UNION
        (
            SELECT users.*, hoc_vien.ho_ten, hoc_vien.email
            FROM users
            INNER JOIN hoc_vien ON users.id_hoc_vien = hoc_vien.id_hoc_vien AND users.id_hoc_vien IS NOT NULL
            ORDER BY users.id_hoc_vien ASC
        )"));
    
        $data["totalUsers"] = $totalUsers;
    
        return $this->response->setJSON($data);
    }
    public function getInsertUserForm() {
        return view("Admin\ViewCell\InsertUserForm");
    }
    
}

