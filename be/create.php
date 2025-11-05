<?php
// validasi cek method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(
        [
            'status' => 'error',
            'msg' => 'Method Salah !',
            'errors' => []
        ]
    );
    exit;
}

// Mempersiapkan datanya dan variabel $data 
header("Content-Type: application/json; charset=UTF-8");
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// validasi error
$errors = [];
if (!isset($data['nim'])) {
    $errors['nim'] = "Data NIM tidak ada";
} else {
    if ($data['nim'] == '') {
        $errors['nim'] = "Data NIM tidak boleh kosong";
    }
}

if (!isset($data['nama'])) {
    $errors['nama'] = "Data NAMA tidak ada";
} else {
    if ($data['nama'] == '') {
        $errors['nama'] = "Data NAMA tidak boleh kosong";
    }
}


if (count($errors) == 0) {
    // koneksi
    // $koneksi = mysqli_connect('localhost', 'root','', 'PBP_PAGIA');
    $koneksi = new mysqli('localhost', 'root','', '25_PBP_PAGIB_23');
    // insert
    $nim = $data['nim'];
    $nama = $data['nama'];
    $q = "INSERT INTO mahasiswa (nim, nama) VALUES('$nim','$nama')";
    // mysqli_query($koneksi, $q);
    $koneksi->query($q);
    $dataResponse = [
        'status' => 'success',
        'msg' => 'Data baru berhasil dibuat',
        'data' => [
            'id' => $koneksi->insert_id,
            'nim' => $data['nim'],
            'nama' => $data['nama']
        ]
    ];
} else {
    $dataResponse = [
        'status' => 'error',
        'msg' => 'Validasi Error',
        'errors' => $errors
    ];
    http_response_code(400);
}

echo json_encode($dataResponse);
