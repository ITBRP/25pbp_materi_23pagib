<?php 
header("Content-Type: application/json; charset=UTF-8");
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    http_response_code(405);
    $res = [
        'status' => 'error',
        'msg' => 'Method salah !'
    ];
    echo json_encode($res);
    exit();
}

// block jika method nya benar
$errors = [];
if(!isset($_POST['nama'])){
    $errors['nama'] = "Nama wajib dikirim";
}else{
    if($_POST['nama']==''){
        $errors['nama'] = "Nama tidak boleh kosong";
    }
}
if(!isset($_POST['nim'])){
    $errors['nim'] = "NIM wajib dikirim";
}else{
    if($_POST['nim']==''){
        $errors['nim'] = "NIM tidak boleh kosong";
    }else{
        if(!preg_match('/^[1-9][0-9]{9}$/', $_POST['nim'])){
            $errors['nim'] = "Format NIM salah, harus angka semua minimal dan max 10 digit dan angka awal tidak boleh 0";
        }
    }
}

if(count($errors)>0){
    http_response_code(400);
    $res = [
        'status' => 'error',
        'msg' => 'Data error',
        'errors' => $errors
    ];
    echo json_encode($res);
    exit();
}

// insert ke db
$koneksi = new mysqli('localhost', 'root','', '4PAGIB');
$nama = $_POST['nama'];
$nim = $_POST['nim'];
$q = "INSERT INTO mhs(nama, nim) VALUES('$nama','$nim')";
$koneksi->query($q);
$id = $koneksi->insert_id;

echo json_encode([
    'status' => "ok",
    'msg' => "Proses berhasil",
    'data' => [
        'id' => $id,
        'nama' => $nama,
        'nim' => $nim
    ]
]);


