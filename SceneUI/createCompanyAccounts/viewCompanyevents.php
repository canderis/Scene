<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/inc/php/pdo_mysql_helper.php");

$dbc = new pdo_mysql_helper($_SERVER['DOCUMENT_ROOT'] . "/inc/php/credentials.php");


$query = "SELECT title, description, address, type FROM company_events";
$dbc->query($query, null);
$data = $dbc->fetch_all_assoc();

echo '<h1>';
echo "Company Events";
echo '</h1>';

echo '<table border="2">';
echo '<td>';
echo "Title";
echo '</td>';
echo '<td>';
echo "Description";
echo '</td>';
echo '<td>';
echo "Address";
echo '</td>';
echo '<td>';
echo "Type";
echo '</td>';
echo '<tr>';

echo '</tr>';
foreach($data as $key=>$row){
  //echo json_encode($row);
    echo '<tr>';
    //$row = explode(' ',$row);
    foreach($row as $cell){
        echo '<td>';
        echo $cell;
        echo '</td>';
    }
    echo '</tr>';
}
echo '</table>';

echo '<br />';

echo '<a href="createCompanyEvent.html">';
echo "Create Company Event";
echo '</a>';

?>
