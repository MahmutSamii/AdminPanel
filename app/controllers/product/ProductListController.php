<?php
error_reporting(0);
include("../../../database/db.php");
if($_GET['silinecekid'])
{
  $deleted = deleted();
  echo $deleted;
}

if ($_POST['ekle']) {
    $product = product();
    echo $product;
}elseif($listele)
{
    $produc = liste();
}elseif($_GET['id'])
{
  $guncelle = edit();
}elseif($_POST['Edit'])
{
    $update = update();
    echo $update;
}


function product()
{
    global $db;
    $name = $_POST['names'];
    $piece = $_POST['piece'];

    $insert = $db->prepare("INSERT INTO product SET
                           names=:names,
                           piece=:piece
                          ");

    $data = array(
        "names" => $name,
        "piece" => $piece
    );

    $result = $insert->execute($data);

    if ($result) {
        return "İşleminiz Başarıyla Gerçekleştirilmiştir.";
    } else {
        return "İşleminiz Başarısız Olmuştur.";
    }
}

function liste()
{ 
    global $db;
    $select = $db->prepare("SELECT * FROM product");
    $select->execute();
    
    return $select->fetchAll(PDO::FETCH_OBJ);
}


function deleted()
{
    global $db;
    $sil = $_GET['silinecekid'];
 
    if($sil)
    {
        $delete = $db->prepare("DELETE FROM product WHERE id=:id");
        $delete->bindParam(":id" , $sil, PDO::PARAM_INT);
        $sil = $delete->execute();
        return "<div style='margin-left: 500px;'>Silme İşleminiz Başarıyla Gerçekleştirilmiştir.</div>";
    }else
    {
        return "<div style='margin-left: 500px;'>Silme İşleminiz Başarısız Olmuştur.</div>";
    }
    

}

function edit()
{
    global $db;

    $select = $db->prepare("SELECT * FROM product WHERE id=:id");
    $data = array(
        "id" => $_GET['id'],
    );

    $select->execute($data);

    return $select->fetchAll(\PDO::FETCH_ASSOC);
}

function update()
{
    global $db;
    
    $names = $_POST['names'];
    $piece = $_POST['piece'];

    $updates = $db->prepare("UPDATE product SET
                           names=:names,
                           piece=:piece  
                          ");
    $data = array(
        "names" => $names,
        "piece" => $piece
    );
    
    $result = $updates->execute($data);

    if($result)
    {
        return "Güncelleme İşleminiz Başarıyla Gerçekleştirilmiştir.";
    }else{
        return "Güncelleme İşleminiz Başarısız Olmuştur.";
    }
}