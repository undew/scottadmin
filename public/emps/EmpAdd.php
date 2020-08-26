<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");

$addEmNo = $_POST["addEmNo"];
$addEmName = $_POST["addEmName"];
$addEmJob = $_POST["addEmJob"];
$addEmMgr = $_POST["addEmMgr"];
$addEmHiredate = $_POST["addEmHiredate"];
$addEmSal = $_POST["addEmSal"];
$addDpId = $_POST["addDpId"];
$addEmName = trim($addEmName);
$addEmJob = trim($addEmJob);


$emps = new Emp();
$emps->setEmNo($addEmNo);
$emps->setEmName($addEmName);
$emps->setEmJob($addEmJob);
$emps->setEmMgr($addEmMgr);
$emps->setEmHiredate($addEmHiredate);
$emps->setEmSal($addEmSal);
$emps->setDpId($addDpId);

$validationMsgs = [];
try {
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $sqlSelect = "SELECT COUNT(*) count FROM emps WHERE em_no = :em_no";
    $sqlInsert = "INSERT INTO emps (em_no, em_name, em_job,em_mgr,em_hiredate,em_sal,dept_id) VALUES (:em_no, :em_name,
 :em_job,:em_mgr,:em_hiredate,:em_sal,:dept_id)";

    $stmt = $db->prepare($sqlSelect);
    $stmt->bindValue(":em_no", $addEmNo, PDO::PARAM_INT);
    $result = $stmt->execute();
    $count = 1;
    if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $count = $row["count"];
    }
    if ($count > 0) {
        $validationMsgs[] = "その従業員番号はすでに使われています。別のものを指定してください。";
    }

    if (empty($validationMsgs)) {
        $stmt = $db->prepare($sqlInsert);
        $stmt->bindValue(":em_no", $emps->getEmNo(), PDO::PARAM_INT);
        $stmt->bindValue(":em_name", $emps->getEmName(), PDO::PARAM_STR);
        $stmt->bindValue(":em_job", $emps->getEmJob(), PDO::PARAM_STR);
        $stmt->bindvalue(":em_mgr", $emps->getEmMgr(), PDO::PARAM_STR);
        $stmt->bindvalue(":em_hiredate", $emps->getEmHiredate(), PDO::PARAM_STR);
        $stmt->bindvalue(":em_sal", $emps->getEmSal(), PDO::PARAM_STR);
        $stmt->bindvalue(":dept_id", $emps->getDpId(), PDO::PARAM_STR);
        
        $result = $stmt->execute();
        if ($result) {
            $emId = $db->lastInsertId();
        } else {
            $_SESSION["errorMsg"] =
                "情報登録に失敗しました。もう一度はじめからやり直してください。";
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
    header("Location: /ph34/scottadmin/public/emps/goEmpAdd.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>従業員情報追加完了 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>従業員情報追加完了</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emps/showEmpList.php">従業員リスト</a></li>
            <li>従業員情報追加</li>
            <li>従業員情報追加完了</li>
        </ul>
    </nav>
    <section>
        <p> </p>
        以下の従業員情報を登録しました。
        <dl>
            <dt>ID(自動生成)</dt>
            <dd><?= $emId ?></dd>
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

        </dl>
        <p>
            従業員リストに<a href="/ph34/scottadmin/public/emps/showEmpList.php">戻る</a>
        </p>
    </section>
</body>

</html>