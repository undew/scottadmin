<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");
$deleteEmpId = $_POST["deleteEmpId"];

try {
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $sql = "SELECT * FROM emps WHERE id = :id";

    $stmt = $db->prepare($sql);
    $stmt->bindValue(":id", $deleteEmpId, PDO::PARAM_INT);
    $result = $stmt->execute();
    if ($result && $row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $id = $row["id"];
        $emNo = $row["em_no"];
        $emName = $row["em_name"];
        $emJob = $row["em_job"];
        $emMgr = $row["em_mgr"];
        $emHiredate = $row["em_hiredate"];
        $emSal = $row["em_sal"];
        $emDpId = $row["dept_id"];
        
        $emps = new Emp();
        $emps->setId($id);
        $emps->setEmNo($emNo);
        $emps->setEmName($emName);
        $emps->setEmJob($emJob);
        $emps->setEmMgr($emMgr);
        $emps->setEmHiredate($emHiredate);
        $emps->setEmSal($emSal);
        $emps->setDpId($emDpId);

    } else {
        $_SESSION["errorMsg"] = "部門情報の取得に失敗しました。";
    }
} catch (PDOException $ex) {
    $_SESSION["errorMsg"] = "DB接続に失敗しました。";
} finally {
    $db = null;
}
if (isset($_SESSION["errorMsg"])) {
    header("Jobation: /ph34/scottadmin/public/error.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Shinzo SAITO">
    <title>部門情報削除 | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>部門情報削除</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/public/">TOP</a></li>
            <li><a href="/ph34/scottadmin/public/emps/showEmpList.php">部門リスト</a></li>
            <li>部門情報削除確認</li>
        </ul>
    </nav>
    <section>
        <p>
            以下の部門情報を削除します。<br>
            よろしければ、削除ボタンをクリックしてください。 </p>
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
            <dt>日時番号</dt>
            <dd><?= $emps->getEmHiredate() ?></dd>
            <dt>給与</dt>
            <dd><?= $emps->getEmSal() ?></dd>
            <dt>部門ID</dt>
            <dd><?= $emps->getDpId() ?></dd>
        </dl>
        <form action="/ph34/scottadmin/public/emps/EmpDelete.php" method="post">
            <input type="hidden" id="deleteEmpId" name="deleteEmpId" value="<?= $emps->getId() ?>">
            <button type="submit">削除</button> </form>
    </section>
</body>

</html>