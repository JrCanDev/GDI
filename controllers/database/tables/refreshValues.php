<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root . '/vendor/autoload.php';
require_once $root . '/lib/project.lib.php';
$db = require $root . '/lib/pdo.php';

$tableName = GETPOST('tableName');
$columnMetadata = GETPOST('columnMetadata');
$listFK = GETPOST('listFK');
$sort = GETPOSTISSET('sort') ? GETPOST('sort') : null;

try {
  $query = "SELECT * ";
  $query .= "FROM $tableName ";
  if (!is_null($sort)) {
    $query .= "ORDER BY " . $sort[0] . " " . $sort[1];
  }
  $statement = $db->query($query);
  $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  $statement->closeCursor();

  $table_values = '';
  $i = 1;
  foreach ($result as $row) {
    $value_list = [];
    $table_values .= "<tr id='tr-$tableName-$i'>";
    foreach ($columnMetadata as $column) {
      $column_name = $column['name'];
      $column_type = $column['type'];
      $column_value = $row[$column_name];
      $value_list[$column_name] = $column_value;

      $table_values .= "<td class='w3-border' id='". $column['name'] . "'>" . validateTypeInbound($column_value, $column_type) . "</td>";
    }
    $table_values .= "<td class='w3-blue-grey w3-border-blue-grey' style='width: 5%'><button id='modify-value-$tableName-$i' class='clickable w3-light-green' onClick=\"modifyValue('$tableName', " . sanitize(json_encode($columnMetadata)) . ", $i, " . sanitize(json_encode($value_list)) . ")\"><i class='material-icons' style='padding-top: 5px;'>&#xe254;</i></button></td>";
    $table_values .= "<td class='w3-blue-grey w3-border-blue-grey' style='width: 5%'><button id='delete-value-$tableName-$i' class='clickable w3-red' onClick=\"deleteValue('$tableName', " . sanitize(json_encode($columnMetadata)) . ", " . sanitize(json_encode($listFK)) . ", $i, " . sanitize(json_encode($value_list)) . ")\"><i class='material-icons' style='padding-top: 5px;'>&#xe92b;</i></button></td>";
    $table_values .= "</tr>";
    $i += 1;
  }
  $result = null;

  echo $table_values;
} catch (Throwable $e) {
  die(json_encode(['error' => 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile()]));
}
$db = null;