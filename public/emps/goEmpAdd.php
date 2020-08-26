<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");

$emps = new Emp();

if (isset($_SESSION["emps"])) {
    $emps = $_SESSION["emps"];
    $emps = unserialize($emps);
    unset($_SESSION["emps"]);
}
$validationMsgs = null;
if (isset($_SESSION["validationMsgs"])) {
    $validationMsgs = $_SESSION["validationMsgs"];
    unset($_SESSION["validationMsgs"]);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Hiroki Kondo">
    <title>従業員情報追加 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>従業員情報追加</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/public/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emps/showEmpList.php">従業員リスト</a></li>
            <li>従業員情報追加</li>
        </ul>
    </nav>
    <?php if (!is_null($validationMsgs)) { ?>
    <section id="errorMsg">
        <p>以下のメッセージをご確認ください。</p>
        <ul>
            <?php foreach ($validationMsgs as $msg) { ?>
            <li><?= $msg ?></li>
            <?php } ?>
            <?php }
            ?>
        </ul>
    </section>
    <section>
        <p>
            情報を入力し、登録ボタンをクリックしてください。 </p>
        <form action="/ph34/scottadmin/public/emps/EmpAdd.php" method="post" class="box">
            <label for="addEmNo">
                従業員番号&nbsp;<span class="required">必須</span>
                <input type="number" min="1000" max="9999" id="addEmNo" name="addEmNo"
                    value="<?= $emps->getEmNo() ?>" required> </label><br>
            <label for="addEmName">
                従業員名&nbsp;<span class="required">必須</span>
                <input type="text" id="addEmName" name="addEmName" value="<?= $emps->getEmName() ?>" required>
            </label><br>
            <label for="addEmJob"> 職業&nbsp;<span class="required">必須</span>
                <input type="text" id="addEmJob" name="addEmJob" value="<?= $emps->getEmJob() ?>" required>
            </label><br>
            <label for="addEmMgr"> 上司番号&nbsp;<span class="required">必須</span>
                <input type="text" id="addEmMgr" name="addEmMgr" value="<?= $emps->getEmMgr() ?>" required>
            </label><br>
            <label for="addEmHiredate"> 日時&nbsp;<span class="required">必須</span>
                <input type="text" id="addEmHiredate" name="addEmHiredate" value="<?= $emps->getEmHiredate() ?>" required>
            </label><br>
            <label for="addEmSal"> 給与&nbsp;<span class="required">必須</span>
                <input type="text" id="addEmSal" name="addEmSal" value="<?= $emps->getEmSal() ?>" required>
            </label><br>
            <label for="addDpId"> 部門ID&nbsp;<span class="required">必須</span>
                <input type="text" id="addDpId" name="addDpId" value="<?= $emps->getDpId() ?>" required>
            </label><br>
            
            <button type="submit">登録</button> </form>
    </section>
</body>

</html>