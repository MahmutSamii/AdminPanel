<?php
error_reporting(0);
include("../../../database/db.php");
if ($_POST['kayitol']) {
    $register = register();
    echo $register;
} elseif ($_POST['submit']) {
    $login = login();
    echo $login;
}

function register()
{
    global $db;
    $username = $_POST['username'];
    $password = $_POST['password1'];
    $retypePassword = $_POST['retype_password1'];

    if ($password == $retypePassword) {
        $insert = $db->prepare("INSERT INTO user SET
                          username=:username,
                          password1=:password1,
                          retype_password1=:retype_password1
                         ");
        $data = array(
            "username" => $username,
            "password1" => $password,
            "retype_password1" => $retypePassword
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
        header("Location:../../../resources/views/personeller/create.php");
    } else {
        return "Kullanıcı adınız veya şifreniz Hatalıdır.";
    }
}
