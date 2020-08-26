<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");

$emps = new Emp();
$validationMsgs = null;

if (isset($_POST["editEmpId"])) {
    $editEmpId = $_POST["editEmpId"];
    try {
        $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        $sql = "SELECT * FROM emps WHERE id = :id";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(":id", $editEmpId, PDO::PARAM_INT);
        $result = $stmt->execute();
        
        if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
            $id = $row["id"];
            $emNo = $row["em_no"];
            $emName = $row["em_name"];
            $emJob = $row["em_job"];
            $emMgr = $row["em_mgr"];
            $emHiredate = $row["em_hiredate"];
            $emSal = $row["em_sal"];
            $dpId = $row["dept_id"];

            $emps = new Emp();
            $emps->setId($id);
            $emps->setEmNo($emNo);
            $emps->setEmName($emName);
            $emps->setEmJob($emJob);
            $emps->setEmMgr($emMgr);
            $emps->setEmHiredate($emHiredate);
            $emps->setEmSal($emSal);
            $emps->setDpId($dpId);

        } else {
            $_SESSION["errorMsg"] = "部門情報の取得に失敗しました。";
        }
    } catch (PDOException $ex) {
        var_dump($ex);
        $_SESSION["errorMsg"] = "DB接続に失敗しました。";
    } finally {
        $db = null;
    }
    if (isset($_SESSION["errorMsg"])) {
        header("Jobation: /ph34/scottadmin/public/error.php");
        exit;
    }
} else {
    if (isset($_SESSION["emps"])) {
        $emps = $_SESSION["emps"];
        $emps = unserialize($emps);
        unset($_SESSION["emps"]);
    }
    if (isset($_SESSION["validationMsgs"])) {
        $validationMsgs = $_SESSION["validationMsgs"];
        unset($_SESSION["validationMsgs"]);
    }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>部門情報編集 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>部門情報編集</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/public/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emps/showEmpList.php">部門リスト</a></li>
            <li>部門情報編集</li>
        </ul>
    </nav>
    <?php if (!is_null($validationMsgs)) { ?>
    <section id="errorMsg">
        <p>以下のメッセージをご確認ください。</p>
        <ul>
            <?php
                foreach ($validationMsgs as $msg) {
                ?>
            <li><?= $msg ?></li>
            <?php
                }
                ?>
        </ul>
    </section>
    <?php
    }
    ?>
    <section>
        <p>情報を入力し、更新ボタンをクリックしてください。</p>
        <form action="/ph34/scottadmin/public/emps/EmpEdit.php" method="post" class="box">
            従業員ID:&nbsp;<?= $emps->getId() ?><br>
            <input type="hidden" name="editEmId" value="<?= $emps->getId() ?>">
            <label for="editEmNo">従業員番号：&nbsp;<span class="required">必須</span>
            <input type="number" min="1000" max="9999" id="editEmNo" name="editEmNo" value="<?= $emps->getEmNo() ?>" required>
            </label><br>
        <label for="editEmName">従業員名：&nbsp;<span class="required">必須</span>
        <input type="text" id="editEmName" name="editEmName" value="<?= $emps->getEmName() ?>" required>
        </label><br>
    <label for="editEmJob">職業：&nbsp;<span class="required">必須</span>
    <input type="text" id="editEmJob" name="editEmJob" value="<?= $emps->getEmJob() ?>" required>
    </label><br>
    <label for="editEmMgr">上司番号：&nbsp;<span class="required">必須</span>
    <input type="text" id="editEmMgr" name="editEmMgr" value="<?= $emps->getEmMgr() ?>" required>
    </label><br>
    <label for="editEmHiredate">日時：&nbsp;<span class="required">必須</span>
    <input type="text" id="editEmHiredate" name="editEmHiredate" value="<?= $emps->getEmHiredate() ?>" required>
    </label><br>
    <label for="editEmSal">給与：&nbsp;<span class="required">必須</span>
    <input type="text" id="editEmSal" name="editEmSal" value="<?= $emps->getEmSal() ?>" required>
    </label><br>
    <label for="editDpId">部門ID：&nbsp;<span class="required">必須</span>
    <input type="text" id="editDpId" name="editDpId" value="<?= $emps->getDpId() ?>" required>
    </label><br>

    
    
    
    <button type="submit">更新</button>
    </form>
    </section>
</body>

</html