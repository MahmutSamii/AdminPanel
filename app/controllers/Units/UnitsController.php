<?php
error_reporting(0);
include("../../../database/db.php");

if($_POST['kaydet'])
{
   $kayıt = kayıt();
   echo $kayıt;
}elseif($listele)
{
   $unit = lists();
}elseif($_GET['id'])
{
    $guncelleunit = edit();
}


function kayıt()
{
    global $db;
    
    $name = $_POST['names'];

    $ınsert = $db->prepare("INSERT INTO units SET
                          names=:names
                          ");
    $data = array(
        "names" => $name
    );
    
    $result = $ınsert->execute($data);

    if($result)
    {
        return "Kayıt İşleminiz Başarıyla Gerçekleştirilmiştir.";
    }else
    {
        return "Kayıt İşleminiz Başarısız Olmuştur.";
    }
}

function lists()
{
    global $db;

    $select = $db->prepare("SELECT * FROM units ");
    $select->execute();

    return $select->fetchAll(PDO::FETCH_OBJ);
}

function edit()
{
    global $db;

    $select = $db->prepare("SELECT * FROM units WHERE id=:id");
    $data = array(
        "id" => $_GET['id']
    );

    $select->execute($data);

    return $select->fetchAll(\PDO::FETCH_ASSOC);
}