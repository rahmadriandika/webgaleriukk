<?php
    session_start();
    if(!isset($_SESSION["login"])){
        header("Location:login.php");
    } 
    require"rianndb.php";
    $id=$_GET["id"];
    $data = query("SELECT * FROM albumfoto WHERE id=$id")[0];
    var_dump($data["foto"]);
        if(isset($_POST["ubah"])){
            $id = $_POST["fid"];
            $fotoLama=$_POST["fFoto"];
            $judul = $_POST["fJudul"];
            $deskripsi = $_POST["fDeskripsi"];
            $foto="";
            if($_FILES['fFoto']['error'] === 4){
                $foto=$fotoLama;
                
            }else{
                $namaFile = $_FILES['fFoto']['name'];
                $ukuranFile = $_FILES['fFoto']['size'];
                $error = $_FILES['fFoto']['error'];
                $tempName=$_FILES['fFoto']['tmp_name'];
                if($error === 4){
                    echo "<script>
                            alert('pilih gambar terlebih dahulu!');
                            document.location.href='tambah.php';
                            </script>";
                    return false;        
                }
                $ektensiGambarValid = ['jpg','jpeg','png'];
                $ektensiGambar = explode('.',$namaFile);
                $ektensiGambar = strtolower(end($ektensiGambar));
                if(!in_array($ektensiGambar,$ektensiGambarValid)){
                    echo"<script>
                            alert('yang anda upload bukan gambar');
                        </script>";
                    return  false;
                }
                $namaFileBaru= uniqid();
                $namaFileBaru .='.';
                $namaFileBaru .= $ektensiGambar;
                move_uploaded_file($tempName,'img/'.$namaFileBaru);
                $foto=$namaFileBaru ;
            }
            $query =  "UPDATE albumfoto SET 
                        foto ='$foto',
                        judul = '$judul',
                        deskripsi = '$deskripsi'
                        WHERE id=$id;
                        ";
            mysqli_query($koneksi,$query);
            if(mysqli_affected_rows($koneksi) >0){
                echo "
                    <script>
                        alert('data berhasil diubah!');
                        document.location.href='admin.php';
                    </script>
                ";
            }else{
                echo "
                    <script>
                        alert('data gagal ditambahkan!');
                        document.location.href='admin.php';
                    </script>
                ";
            }
        }
    

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="#" method="post" enctype="multipart/form-data">
        <input type="hidden" name="fid" value="<?= $data["id"]?>">
        <input type="hidden" name="fFoto" value="<?= $data["foto"]?>">

        <table>
            <tr>
                <td><label for="foto">Foto</label></td>
                <td>:</td>
                <td> <input type="file" name="fFoto" id="foto" /> </td>
            </tr>
            <tr>
                <td><label for="judul">Judul</label></td>
                <td>:</td>
                <td> <input type="text" name="fJudul" id="judul" value="<?= $data["judul"] ?>"/> </td>
            </tr>
            <tr>
                <td><label for="deskripsi">Deskripsi</label></td>
                <td>:</td>
                <td> 
                    <textarea name="fDeskripsi" id="deskripsi" cols="30" rows="10" "><?= $data["deskripsi"]?>
                    </textarea> 
                </td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td><button type="submit" name="ubah">Ubah Data!</button></td>
            </tr>
        </table>
    </form>
    
</body>
</html>