<?php
error_reporting(0);
include("../../../database/db.php");

if ($_POST['kayitol']) {
    $register = register();
    echo $register;
} elseif ($_POST['submit']) {
    $login = login();
    echo $login;
} elseif ($listeleAuth) {
    $users = listele();
} elseif ($_GET['id']) {
    $get = gelenEleman();
} elseif ($_POST['guncelle']) {
    $created = created();
    echo $created;
} elseif ($_POST['logout']) {
    logout();
}


function loginConrtoler()
{
    session_start();
    if (!$_SESSION['user_id']) {
        header('location:../auth/login.php');
    }
}

//register sayfası üzerinde bulunan kayıt ol butonuna tıklanınca etkileşime girecek olan kodlar
function register()
{
    global $db;
    $username = $_POST['username'];
    $password = $_POST['password1'];
    $retypePassword = $_POST['retype_password1'];

    if (strlen($password) < 6) {
        return "Şifre Uzunluğu En az 6 Karakter Olmak Zorunda";
    }

    if(!isset($username) or !isset($password)){
         return 'Kullanıcı Adı Veya Şifre Boş Olamaz.';
    }

    if ($password == $retypePassword) {
        $insert = $db->prepare("INSERT INTO user SET
                          username=:username,
                          password1=:password1
                         ");
        $data = array(
            "username" => $username,
            "password1" => $password
        );


        $result = $insert->execute($data);

        if ($result) {
            return "Kayıt İşleminiz Başarıyla Gerçekleştirilmiştir.";
        } else {
            return "Kayıt İşleminiz Geçersiz Olmuştur.";
        }
    } else {
        return "Girmiş Olduğunuz Şifreler Birbiriyle Uyuşmamaktadır.";
    }
}


//login tarafında bulunan giriş yap butonuna tıklanınca etkileşime girecek olan kodlar
function login()
{
    global $db;

    $username = $_POST['username'];
    $password = $_POST['password1'];

    $user = $db->prepare("SELECT * FROM user WHERE username=:username and password1=:password1");

    $data = array(
        "username" => $username,
        "password1" => $password
    );

    $user->execute($data);
    $results = $user->fetchAll(\PDO::FETCH_ASSOC);


    if (isset($results[0]['username'])) {
        session_start();
        $_SESSION['user_id'] = $results[0]['id'];
        header("Location:../../../resources/views/personeller/create.php");
    } else {
        return "Kullanıcı adınız veya şifreniz Hatalıdır.";
    }
}

//index.php sayfasında istenilen elemanları çekip sayfa üzerinde göstermek için kullandığım kod bloğum
function listele()
{
    global $db;

    $liste = $db->prepare("SELECT * FROM user");
    $liste->execute();

    return $liste->fetchAll(\PDO::FETCH_OBJ);
}

//edit.php sayfasında güncellenmek istenilen elemanları çekip sayfa üzerinde göstermek için kullandığım kod bloğum
function gelenEleman()
{
    global $db;

    $select = $db->prepare("SELECT * FROM user WHERE id=:id");

    $data = array(
        "id" => $_GET['id'],
    );

    $select->execute($data);

    return $select->fetchAll(\PDO::FETCH_ASSOC);
}

//edit.php sayfasında bulunan form sayfası ile elimde bulunan kullanıcıyı güncelleyebiliceğim kod bloğum
function created()
{
    global $db;

    $username = $_POST['username'];
    $password = $_POST['password1'];
    $retypePassword = $_POST['retype_password1'];

    $update = $db->prepare("UPDATE user SET
                        username=:username,
                        password1=:password1,
                        retype_password1=:retype_password1
                        ");
    $data = array(
        "username" => $username,
        "password1" => $password,
        "retype_password1" => $retypePassword
    );

    $result = $update->execute($data);

    if ($result) {
        return "Güncelleme İşleminiz Başarıyla Gerçekleştirilmiştir.";
    } else {
        return "Güncelleme İşlemi Esnasında Bir Hata Oluştu";
    }
}

function logout()
{
    session_start();
    session_destroy();

    header('location:../../../resources/views/auth/login.php');
}
