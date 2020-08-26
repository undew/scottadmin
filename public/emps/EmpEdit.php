<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");

$editEmId = $_POST["editEmId"];
$editEmNo = $_POST["editEmNo"];
$editEmName = $_POST["editEmName"];
$editEmJob = $_POST["editEmJob"];
$editEmMgr = $_POST["editEmMgr"];
$editEmHiredate = $_POST["editEmHiredate"];
$editEmSal = $_POST["editEmSal"];
$editDpId = $_POST["editDpId"];

$editEmName = trim($editEmName);
$editEmJob = trim($editEmJob);

$emps = new Emp();
$emps->setId($editEmId);
$emps->setEmNo($editEmNo);
$emps->setEmName($editEmName);
$emps->setEmJob($editEmJob);
$emps->setEmMgr($editEmMgr);
$emps->setEmHiredate($editEmHiredate);
$emps->setEmSal($editEmSal);
$emps->setDpId($editDpId);

$validationMsgs = [];
try {
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $sqlSelect = "SELECT id FROM emps WHERE em_no = :em_no";
    $sqlUpdate = "UPDATE emps SET em_no = :em_no, em_name = :em_name, em_job = :em_job ,em_mgr = :em_mgr,em_hiredate = :em_hiredate,em_sal = :em_sal,dept_id = :dept_id WHERE id = :id";

    $stmt = $db->prepare($sqlSelect);
    $stmt->bindValue(":em_no", $emps->getEmNo(), PDO::PARAM_INT);
    $result = $stmt->execute();
    $idInDB = 0;
    if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $idInDB = $row["id"];
    }
    if ($idInDB > 0 && $idInDB != $editEmId) {
        $validationMsgs[] = "その部門番号はすでに使われています。別のものを指定してください。";
    }

    if (empty($validationMsgs)) {
        $stmt = $db->prepare($sqlUpdate);
        $stmt->bindValue(":em_no", $emps->getEmNo(), PDO::PARAM_INT);
        $stmt->bindValue(":em_name", $emps->getEmName(), PDO::PARAM_STR);
        $stmt->bindValue(":em_job", $emps->getEmJob(), PDO::PARAM_STR);
        $stmt->bindValue(":em_mgr", $emps->getEmMgr(), PDO::PARAM_STR);
        $stmt->bindValue(":em_hiredate", $emps->getEmHiredate(), PDO::PARAM_STR);
        $stmt->bindValue(":em_sal", $emps->getEmSal(), PDO::PARAM_STR);
        $stmt->bindValue(":dept_id", $emps->getDpId(), PDO::PARAM_STR);                
        $stmt->bindValue(":id", $emps->getId(), PDO::PARAM_INT);
        $result = $stmt->execute();
        if (!$result) {
            $_SESSION["errorMsg"] =
                "情報更新に失敗しました。もう一度はじめからやり直してください。";
        }
    } else {
        $_SESSION["emps"] = serialize($emps);
        $_SESSION["validationMsgs"] = $validationMsgs;
    }
} catch (PDOException $ex) {
    var_dump($ex);
    $_SESSION["errorMsg"] = "DB接続に失敗しました。";
} finally {
    $db = null;
}

if (isset($_SESSION["errorMsg"])) {
    header("Location: /ph34/scottadmin/public/error.php");
    exit;
} elseif (!empty($validationMsgs)) {
    header("Location: /ph34/scottadmin/public/emps/prepareEmpEdit.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>部門情報編集完了 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>部門情報編集完了</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emps/showEmpList.php">部門リスト</a></li>
            <li>部門情報編集</li>
            <li>部門情報編集完了</li>
        </ul>
    </nav>
    <section>
        以下の部門情報を更新しました。 </p>
        <dl>
            <dt>ID</dt>
            <dd><?= $emps->getId() ?></dd>
            <dt>従業員番号</dt>
            <dd><?= $emps->getEmNo() ?></dd>
            <dt>従業員名</dt>
            <dd><?= $emps->getEmName() ?></dd>
            <dt>職業</dt>
            <dd><?= $emps->getEmJob() ?></dd>
            <dt>上司番号</dt>
            <dd><?= $emps->getEmMgr() ?></dd>
            <dt>日時</dt>
            <dd><?= $emps->getEmHiredate() ?></dd>
            <dt>給与</dt>
            <dd><?= $emps->getEmSal() ?></dd>
            <dt>部門ID</dt>
            <dd><?= $emps->getDpId() ?></dd>
        </dl>
        <p>
            部門リストに<a href="/ph34/scottadmin/public/emps/showEmpList.php">戻る</a>
        </p>
    </section>
</body>

</html>