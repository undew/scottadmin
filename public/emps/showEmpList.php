<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/Conf.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ph34/scottadmin/classes/entity/Emp.php");

$empsList = [];
try {
    $db = new PDO(Conf::DB_DNS, Conf::DB_USERNAME, Conf::DB_PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    $sql = "SELECT * FROM emps ORDER BY em_no";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

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
        
        $empsList[$id] = $emps;
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
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="author" content="Hiroki Kondo">
    <title>従業員情報リスト | ScottAdmin Sample</title>
    <link rel="stylesheet" href="/ph34/scottadmin/public/css/main.css" type="text/css">
</head>

<body>
    <h1>従業員情報リスト</h1>
    <nav id="breadcrumbs">
        <ul>
            <li><a href="/ph34/scottadmin/public/">TOP</a></li>
            <li>従業員情報リスト</li>
        </ul>
    </nav>
    <section>
        <p>
            新規登録は<a href="/ph34/scottadmin/public/emps/goEmpAdd.php">こちら</a>から
        </p>
    </section>
    <section>
        <table>
            <thead>
                <tr>
                    <th>従業員ID</th>
                    <th>従業員番号</th>
                    <th>従業員名</th>
                    <th>職業</th>
                    <th>上司番号</th>
                    <th>給与</th>
                    <th>日時</th>
                    <th>部門ID</th>
                    <th colspan="2">操作</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (empty($empsList)) {
                ?>
                <tr>
                    <td colspan="5">該当従業員は存在しません。</td>
                </tr>
                <?php
                } else {
                    foreach ($empsList as $emps) {
                    ?>
                <tr>
                    <td><?= $emps->getId() ?></td>
                    <td><?= $emps->getEmNo() ?></td>
                    <td><?= $emps->getEmName() ?></td>
                    <td><?= $emps->getEmJob() ?></td>
                    <td><?= $emps->getEmMgr() ?></td>
                    <td><?= $emps->getEmHiredate() ?></td>
                    <td><?= $emps->getEmSal() ?></td>
                    <td><?= $emps->getDpId() ?></td>
                    <td>
                        <form action="/ph34/scottadmin/public/emps/prepareEmpEdit.php" method="post">
                            <input type="hidden" id="editEmpId<?= $emps->getId() ?>" name="editEmpId"
                                value="<?= $emps->getId() ?>">
                            <button type="submit">編集</button> </form>
                    </td>
                    <td>
                        <form action="/ph34/scottadmin/public/emps/confirmEmpDelete.php" method="post">
                            <input type="hidden" id="deleteEmpId<?= $emps->getId() ?>" name="deleteEmpId"
                                value="<?= $emps->getId() ?>">
                            <button type="submit">削除</button> </form>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </section>
</body>

</html>