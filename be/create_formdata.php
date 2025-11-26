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
        if(!preg_match('/^[1-9][0-9]{2}$/', $_POST['nim'])){
            $errors['nim'] = "Format NIM salah, harus angka semua minimal dan max 10 digit dan angka awal tidak boleh 0";
        }
    }
}

$anyPhoto = false;
$namaPhoto = '';
if (isset($_FILES['photo'])) {

    // User memilih file
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $fileName = $_FILES['photo']['name']; //namaaslifile.JPEG, docx
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); // hasilnya jadi jpeg

        if (!in_array($fileExt, $allowed)) {
            $errors['photo'] = "File harus jpg, jpeg atau png";
        } else {
            $anyPhoto = true; // photo valid, siap disave
            $namaPhoto = md5(date('dmyhis')) . "." . $fileExt; // fjsadlfjiajflsdjflsadkjfsad.jpeg
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

if ($anyPhoto) {
    move_uploaded_file($_FILES['photo']['tmp_name'], 'img/' . $namaPhoto);
}
$q = "INSERT INTO mhs(nama, nim, photo) VALUES ('$nama', '$nim', " . ($namaPhoto ? "'$namaPhoto'" : "NULL") . ")";


$koneksi->query($q);
$id = $koneksi->insert_id;


echo json_encode([
    'status' => "ok",
    'msg' => "Proses berhasil",
    'data' => [
        'id' => $id,
        'nama' => $nama,
        'nim' => $nim,
        'photo' => $namaPhoto
    ]
]);


