<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function login()
    {
        return view('login');
    }

    public function loginProcess()
    {
        // Simulasi validasi login
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if ($email == "admin@gmail.com" && $password == "123456") {
            return redirect()->to('/verify');
        }

        return redirect()->back()->with('error', 'Email atau Password salah');
    }

    public function verify()
    {
        return view('verify');
    }

    public function verifyProcess()
    {
        $otp = $this->request->getPost('otp');

        if ($otp == "123456") {
            return redirect()->to('/')->with('success', 'Presensi berhasil!');
        }

        return redirect()->back()->with('error', 'OTP salah');
    }
}