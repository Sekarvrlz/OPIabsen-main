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
        $height   = 56;
        $fontPath = '/usr/share/fonts/truetype/dejavu/DejaVuSansMono-Bold.ttf';

        $img = imagecreatetruecolor($width, $height);

        // Background gradient
        for ($x = 0; $x < $width; $x++) {
            $r   = (int) (220 + ($x / $width) * 30);
            $g   = (int) (225 + ($x / $width) * 20);
            $b   = (int) (235 + ($x / $width) * 15);
            $col = imagecolorallocate($img, min($r, 255), min($g, 255), min($b, 255));
            imageline($img, $x, 0, $x, $height, $col);
        }

        // Noise dots
        for ($i = 0; $i < 200; $i++) {
            $dot = imagecolorallocate($img, rand(100, 190), rand(100, 190), rand(100, 190));
            imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), $dot);
        }

        // Bezier curve noise lines
        for ($i = 0; $i < 6; $i++) {
            $lc  = imagecolorallocate($img, rand(120, 180), rand(120, 180), rand(120, 180));
            $x1  = rand(0, $width / 2);
            $y1  = rand(0, $height);
            $x2  = rand($width / 2, $width);
            $y2  = rand(0, $height);
            $cpx = rand(0, $width);
            $cpy = rand(0, $height);
            for ($t = 0; $t <= 1; $t += 0.02) {
                $bx = (int) ((1 - $t) * (1 - $t) * $x1 + 2 * (1 - $t) * $t * $cpx + $t * $t * $x2);
                $by = (int) ((1 - $t) * (1 - $t) * $y1 + 2 * (1 - $t) * $t * $cpy + $t * $t * $y2);
                imagesetpixel($img, $bx, $by, $lc);
            }
        }

        // Tiap karakter: size, angle, warna & posisi Y acak
        $x = 10;
        foreach (str_split($text) as $char) {
            $color    = imagecolorallocate($img, rand(0, 80), rand(0, 100), rand(80, 160));
            $fontSize = rand(20, 26);
            $angle    = rand(-25, 25);
            $y        = rand(38, 46);

            imagettftext($img, $fontSize, $angle, $x, $y, $color, $fontPath, $char);
            $x += rand(28, 36);
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
}