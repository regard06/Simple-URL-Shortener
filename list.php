<!DOCTYPE html>
<html>
<head>
    <title>Shortener</title>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="list.css"/>

</head>
<body><a href=".">Go Back !</a><?php
header("Cache-Control: no-cache, must-revalidate");
$root_url = $_SERVER['REQUEST_URI'];

if (isset($_GET['userID']) && $_GET['userID'] != "") {
    include("bdd.php");
    if (isset($_GET['delete']) && $_GET['delete'] != "") {
        $req = $connexion->prepare('DELETE FROM shortener WHERE id_user= ? AND short = ?');
        $req->execute(array($_GET['userID'],$_GET['delete']));
        $req->closeCursor();
    }
    echo '
        <table>
            <tr>
                <th>Short link</th>
                <th class="center-div" style="width: 500px;">Original link</th>
                <th>Total views</th>
            </tr>';
    $list = $connexion->prepare('SELECT * FROM shortener WHERE id_user= ? ORDER BY (CASE WHEN comment IS NULL THEN 1 ELSE 0 END), date DESC;');
    $list->execute(array($_GET['userID']));

    while ($row = $list->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr><td><a href=\"./" . $row['short'] . "\" >" . $row['short'] . "</a></td>";
        echo "<td><div class=\"comment\">" . $row['comment'] . "</div><a href=\"./" . $row['short'] . "\" >" . $row['url'] . "</a></td>";
        echo "<td>" . $row['views'] . "<a href=./list.php?userID=" . $_GET['userID'] . "&delete=" . $row['short'] . "><img src=\"delete-icon.png\"></td></tr>";
    }
    $list->closeCursor();
    echo "</table>";

} else {
    echo "What are you doing here ?";
}

?>
</body>
