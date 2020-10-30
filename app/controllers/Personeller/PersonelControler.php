<?php
error_reporting(0);
include("../../../database/db.php");
if ($_GET['silinecekid']) {
    $deleted = deleted();
    echo $deleted;
}

if (isset($_POST['kaydet'])) {
    $response = store();
    echo $response;
} elseif ($listele) {
    $personel = liste();
} elseif ($_GET['id']) {
    $edits = edit();
} elseif ($_POST['guncelle']) {
    $cretated = create();
    echo $cretated;
}

function store()
{
    global $db;

    $name = $_POST['names'];
    $surname = $_POST['surname'];
    $identityNo = $_POST['identity_no'];
    $isMarried = $_POST['is_married'];
    $job = $_POST['job'];
    $salary = $_POST['salary'];
    $age = $_POST['age'];
    $childirens = $_POST['childirens'];
    $birthDate = $_POST['birth_date'];
    $createdAt = $_POST['created_at'];

    $insert = $db->prepare("INSERT INTO personnels SET
                         names=:names,
                         surname=:surname,
                         identity_no=:identity_no,
                         is_married=:is_married,
                         job=:job,
                         salary=:salary,
                         age=:age,
                         childirens=:childirens,
                         birth_date=:birth_date,
                         created_at=:created_at");

    $data = array(
        "names" => $name,
        "surname" => $surname,
        "identity_no" => $identityNo,
        "is_married" => intval($isMarried),
        "job" => $job,
        "salary" => intval($salary),
        "age" => intval($age),
        "childirens" => intval($childirens),
        "birth_date" => date($birthDate),
        "created_at" => date($createdAt)
    );

    $result = $insert->execute($data);

    if ($result) {
        return "İşleminiz Başarıyla Gerçekleştirilmiştir.";
    } else {
        return "işleminiz Başarısız Olmuştur.";
    }
}

function liste()
{
    global $db;

    $liste = $db->prepare("SELECT * FROM personnels");
    $liste->execute();

    return $liste->fetchAll(PDO::FETCH_OBJ);
}

function edit()
{
    global $db;

    $select = $db->prepare("SELECT * FROM personnels WHERE id=:id");

    $data = array(
        "id" => $_GET['id'],
    );

    $select->execute($data);

    return $select->fetchAll(\PDO::FETCH_ASSOC);
}

function create()
{
    global $db;

    $name = $_POST['names'];
    $surname = $_POST['surname'];
    $identityNo = $_POST['identity_no'];
    $isMarried = $_POST['is_married'];
    $job = $_POST['job'];
    $salary = $_POST['salary'];
    $age = $_POST['age'];
    $childirens = $_POST['childirens'];
    $birthDate = $_POST['birth_date'];
    $createdAt = $_POST['created_at'];

    $update = $db->prepare("UPDATE personnels SET
                         names=:names,
                         surname=:surname,
                         identity_no=:identity_no,
                         is_married=:is_married,
                         job=:job,
                         salary=:salary,
                         age=:age,
                         childirens=:childirens,
                         birth_date=:birth_date,
                         created_at=:created_at
                         ");
    $data = array(
        "names" => $name,
        "surname" => $surname,
        "identity_no" => $identityNo,
        "is_married" => $isMarried,
        "job" => $job,
        "salary" => $salary,
        "age" => $age,
        "childirens" => $childirens,
        "birth_date" => $birthDate,
        "created_at" => $createdAt
    );

    $results = $update->execute($data);

    if ($results) {
        return "Güncelleme İşleminiz Başarıyla Gerçekleştirilmiştir.";
    } else {
        return "Güncelleme İşleminiz Başarısız Olmuştur";
    }
}

function deleted()
{
    global $db;
    $sil = $_GET['silinecekid'];
    if ($sil) {
        $delete = $db->prepare("DELETE FROM personnels WHERE id=:id");
        $delete->bindParam(":id", $sil, PDO::PARAM_INT);
        $sil = $delete->execute();
        return "<div style='margin-left: 500px;'>Silme İşleminiz Başarıyla Gerçekleştirilmiştir.</div>";
    } else {
        return "<div style='margin-left: 500px;'>Silme İşleminiz Başarısız Olmuştur.</div>";
    }
}


