<?php

namespace App\Controllers;

use App\Models\GiangVienModel;
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
        if ($user == null || $user->mat_khau != $password) { // Login failed
            // echo $user->id_user;
            // echo $password;
            $data['login_failed'] = "Tài khoản hoặc mật khẩu không đúng";
            return view('LoginPage', $data);
        } else { // Login successfully
            echo "here2";
            if ($user->id_ad != null) {
                echo "here3";
                $session = session();
                $session->start();
                $session->set('id_user', $user->id_user);
                $session->set('role', 1);
                $session->set('id_role', $user->id_ad);

                //return view('LoginPage');

                //f (isset($_SESSION['id_user']))
                //echo "ok";
                return redirect()->to("/courses");
            } else if ($user->id_giang_vien != null) {
                $session = session();
                $session->start();
                $session->set('id_user', $user->id_user);
                $session->set('role', 2);
                $session->set('id_role', $user->id_giang_vien);
                //return view('LoginPage');

                //f (isset($_SESSION['id_user']))
                //echo "ok";
                return redirect()->to("/courses");
            } else if ($user->id_hoc_vien != null) {
                $session = session();
                $session->start();
                $session->set('id_user', $user->id_user);
                $session->set('role', 3);
                $session->set('id_role', $user->id_hoc_vien);
                //return view('LoginPage');

                //f (isset($_SESSION['id_user']))
                //echo "ok";
                return redirect()->to("/courses");
            }
        }
    }



    public function logout()
    {
        // Start the session
        $session = session();
        $session->remove('id_user');
        $session->remove('role');

        // Unset all session variables
        $session->destroy();
        // Redirect to the login page or handle as needed
        return redirect()->to('/');
    }
}
