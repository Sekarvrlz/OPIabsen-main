<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use CodeIgniter\Controller;

class Siswa extends Controller
{

    public function tambah()
    {
        return view('tambah_siswa');
    }

    public function simpan()
    {
        $model = new SiswaModel();

        $foto = $this->request->getPost('foto');
        $namaFoto = null;

        if ($foto) {

            $image = str_replace('data:image/png;base64,', '', $foto);
            $image = base64_decode($image);

            $namaFoto = time().'.png';

            file_put_contents('uploads/'.$namaFoto,$image);
        }

        $model->save([
            'nama' => $this->request->getPost('nama'),
            'no_induk' => $this->request->getPost('no_induk'),
            'kelas' => $this->request->getPost('kelas'),
            'id_rfid' => $this->request->getPost('id_rfid'),
            'foto' => $namaFoto
        ]);

        return redirect()->to(base_url('siswa/data'));
    }


    public function data()
    {
        $model = new SiswaModel();

        $data['siswa'] = $model->findAll();

        return view('data_siswa', $data);
    }

}