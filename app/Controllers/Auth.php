<?php

namespace App\Controllers;

use App\Models\OperatorModel;
use CodeIgniter\Controller;

class Auth extends Controller
{

    public function landing()
    {
        return view('landing');
    }

    public function login()
    {
        $captcha = rand(1000,9999);
        session()->set('captcha',$captcha);

        return view('login',['captcha'=>$captcha]);
    }

    public function loginProcess()
{
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');
    $captchaInput = $this->request->getPost('captcha');

    $captchaSession = session()->get('captcha');

    // cek captcha
    if($captchaInput != $captchaSession){
        return redirect()->back()->with('error','Captcha salah');
    }

    $model = new OperatorModel();

    $user = $model->where('username',$username)->first();

    if($user){

        // cek password langsung
        if($password == $user['password']){

            session()->set([
                'id_admin' => $user['id_admin'],
                'username' => $user['username'],
                'logged_in' => true
            ]);

            return redirect()->to('/dashboard');

        }else{
            return redirect()->back()->with('error','Password salah');
        }

    }else{
        return redirect()->back()->with('error','Username tidak ditemukan');
    }
}

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

}