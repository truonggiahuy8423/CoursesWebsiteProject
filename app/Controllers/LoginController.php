<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index(): string
    {
        return view('LoginPage');
    }

    public function login()
    {

        // Validate
        if (isset($_POST) && isset($_POST['login'])) {

            $rules = [
                'account' => [
                    'rules' => 'required|account_check',
                    'labels' => 'Tài khoản',
                    'errors' => [
                        'required' => 'Nhập tài khoản',
                        'account_check' => 'Tài khoản có tối đa 20 ký tự'
                    ]
                ],
                'password' => [
                    'rules' => 'required|password_check',
                    'labels' => 'Mật khẩu',
                    'errors' => [
                        'required' => 'Nhập mật khẩu',
                        'password_check' => 'Mật khẩu chứa từ 8-20 ký tự'
                    ]
                ],
            ];

            if (!$this->validate($rules)) {
                $data = [];
                $data['validator'] = $this->validator;
                // Failed
                return view('LoginPage', $data);
            }
        }
        // Data from $_POST method
        $account = $_POST['account'];
        $password = $_POST['password'];
        // Query
        $model = new UserModel();
        $user = $model->getUserByAccount("{$account}");
        // Login validation
        if ($user == null) { // Login failed
            $data['login_failed'] = "Tài khoản hoặc mật khẩu không đúng";
            return view('LoginPage', $data);
        } else { // Login successfully
            if ($user->id_ad != null)
                session_start();
                $_SESSION['id_user'] = 123;
                return redirect()->to("/courses");
            // else if ($user->id_giang_vien != null)
            //     return redirect()->to("teacher/home/" . urlencode(json_encode($_POST)));
            // else if ($user->id_hoc_vien != null)
            //     return redirect()->to("student/home/" . urlencode(json_encode($_POST)));
        }
    }
    public function check_session() {
        if (!isset($_SESSION['id_user'])) {
            return view('LoginPage');
        }
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        $this->check_session();
    }

}
