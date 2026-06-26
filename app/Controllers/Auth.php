<?php

namespace App\Controllers;

use App\Libraries\LaravelApiClient;
use App\Models\OperatorModel;

class Auth extends BaseController
{
    public function landing()
    {
        return view('landing');
    }

    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $chars   = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha = '';
        for ($i = 0; $i < 5; $i++) {
            $captcha .= $chars[random_int(0, strlen($chars) - 1)];
        }

        session()->set('captcha', $captcha);

        $captchaImage = $this->generateCaptchaImage($captcha);

        return view('login', ['captchaImage' => $captchaImage]);
    }

    public function loginProcess()
    {
        $username     = trim((string) $this->request->getPost('username'));
        $password     = (string) $this->request->getPost('password');
        $captchaInput = strtoupper(trim((string) $this->request->getPost('captcha')));

        $captchaSession = (string) session()->get('captcha');
        if ($captchaInput !== $captchaSession) {
            return redirect()->back()->withInput()->with('error', 'Captcha salah.');
        }

        $operatorModel = new OperatorModel();
        $admin         = $operatorModel->where('username', $username)->first();

        if ($admin && $this->passwordMatches($password, $admin['password'])) {
            session()->regenerate(true);
            session()->set([
                'logged_in' => true,
                'role'      => 'admin',
                'user_id'   => (int) $admin['id_admin'],
                'username'  => $admin['username'],
                'nama'      => $admin['username'],
            ]);

            return redirect()->to('/dashboard');
        }

        $client   = new LaravelApiClient();
        $guruList = $this->safeList($client->get('guru'));
        $guru     = $this->findGuruByUsername($guruList, $username);

        if ($guru && $this->passwordMatches($password, (string) ($guru['password'] ?? ''))) {
            $kelasWali   = trim((string) ($guru['kelas_wali'] ?? ''));
            $isWaliKelas = $kelasWali !== '' ? 1 : 0;

            session()->regenerate(true);
            session()->set([
                'logged_in'     => true,
                'role'          => 'guru',
                'user_id'       => (int) ($guru['id_guru'] ?? 0),
                'id_guru'       => (int) ($guru['id_guru'] ?? 0),
                'username'      => (string) ($guru['username'] ?? ''),
                'nama'          => (string) ($guru['nama'] ?? ''),
                'is_wali_kelas' => $isWaliKelas,
                'kelas_wali'    => $kelasWali,
            ]);

            return redirect()->to('/dashboard');
        }

        return redirect()->back()->withInput()->with('error', 'Username atau password tidak valid.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function generateCaptchaImage(string $text): string
    {
        $width    = 180;
        $height   = 60;
        $fontPath = FCPATH . 'fonts/captcha.ttf';

        $img = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($img, 255, 255, 255);
        imagefill($img, 0, 0, $white);

        // noise tipis aja, biar nggak mengganggu tapi tetap nyusahin OCR sederhana
        for ($i = 0; $i < 25; $i++) {
            $dot = imagecolorallocate($img, rand(210, 235), rand(210, 235), rand(210, 235));
            imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), $dot);
        }

        $x = 12;
        foreach (str_split($text) as $char) {
            $color    = imagecolorallocate($img, rand(10, 30), rand(10, 30), rand(10, 30)); // nyaris hitam
            $fontSize = rand(24, 30);
            $angle    = rand(-20, 20);
            $y        = rand(40, 48);

            imagettftext($img, $fontSize, $angle, $x, $y, $color, $fontPath, $char);
            $x += rand(26, 32);
        }

        ob_start();
        imagepng($img);
        $raw = ob_get_clean();
        imagedestroy($img);

        return 'data:image/png;base64,' . base64_encode($raw);
    }

    private function passwordMatches(string $input, ?string $stored): bool
    {
        if ($stored === null || $stored === '') {
            return false;
        }

        if (password_verify($input, $stored)) {
            return true;
        }

        return hash_equals($stored, $input);
    }

    private function safeList($response): array
    {
        if (! is_array($response)) {
            return [];
        }

        if (array_key_exists('message', $response)) {
            return [];
        }

        return array_values($response);
    }

    private function findGuruByUsername(array $guruList, string $username): ?array
    {
        if ($username === '') {
            return null;
        }

        foreach ($guruList as $guru) {
            if (! is_array($guru)) {
                continue;
            }

            if ((string) ($guru['username'] ?? '') === $username) {
                return $guru;
            }
        }

        return null;
    }

    public function refreshCaptcha()
    {
        $chars   = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $captcha = '';
        for ($i = 0; $i < 5; $i++) {
            $captcha .= $chars[random_int(0, strlen($chars) - 1)];
        }

        session()->set('captcha', $captcha);

        return $this->response->setJSON([
            'image' => $this->generateCaptchaImage($captcha),
        ]);
    }
}