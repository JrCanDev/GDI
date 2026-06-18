<?php
$root = $_SERVER['DOCUMENT_ROOT'];
include_once $root . '/vendor/autoload.php';
require_once $root . '/lib/project.lib.php';
require_once $root . '/lib/security.lib.php';
require_once $root . '/inc/log.php'; // variable globale $LOGFILE

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// On vérifie si l'utilisateur connecté possède les privilèges d'accès à la base de données.
if (!authClass::checkPriviledgeDatabase($_SESSION['user']['nom_util'])) {
    die(json_encode(['error' => 'Accès refusé']));
}

$tableName = 'logs';
$columnMetadata = GETPOST('columnMetadata');
$sort = GETPOSTISSET('sort') ? GETPOST('sort') : null;

// Lit un fichier à l'envers ligne par ligne
if (!function_exists('read_file_backwards')) {
    function read_file_backwards($file_path, $line_callback) {
        if (!file_exists($file_path) || !is_readable($file_path)) {
            return false;
        }
        
        $file = fopen($file_path, 'rb');
        if (!$file) {
            return false;
        }  

        $chunk_size = 8192;
        fseek($file, 0, SEEK_END);
        $pos = ftell($file);
        $buffer = '';

        while ($pos > 0) {
            $read_len = min($pos, $chunk_size);
            $pos -= $read_len;
            fseek($file, $pos, SEEK_SET);
            
            $chunk = fread($file, $read_len);
            $buffer = $chunk . $buffer;

            $lines = explode("\n", $buffer);
            $buffer = array_shift($lines);

            for ($i = count($lines) - 1; $i >= 0; $i--) {
                $line = trim($lines[$i]);
                if ($line !== '') {
                    if ($line_callback($line) === false) {
                        fclose($file);
                        return true;
                    }
                }
            }
        }

        if ($buffer !== '') {
            $line = trim($buffer);
            if ($line !== '') {
                $line_callback($line);
            }
        }

        fclose($file);
        return true;
    }
}

try {
  $result = [];
  if (file_exists($LOGFILE)) {
    read_file_backwards($LOGFILE, function($line) use (&$result) {
      if (preg_match('/^\[([^\]]+)\]\s+\[([^\]]+)\]\s+\[([^\]]+)\]:\s+(.*)$/', $line, $matches)) {
        $result[] = [
          'date' => $matches[1],
          'domain' => $matches[2],
          'username' => $matches[3],
          'message' => $matches[4]
        ];
      }
    });
  }

  // Tri des logs si demandé
  if (!is_null($sort) && isset($sort[0]) && isset($sort[1])) {
    $sort_col = $sort[0];
    $sort_dir = strtoupper($sort[1]) === 'ASC' ? 1 : -1;

    usort($result, function($a, $b) use ($sort_col, $sort_dir) {
      if ($sort_col === 'date') {
        $da = DateTime::createFromFormat('d-m-Y H:i:s', $a['date']);
        $db = DateTime::createFromFormat('d-m-Y H:i:s', $b['date']);
        if ($da && $db) {
          return ($da <=> $db) * $sort_dir;
        }
      }
      return strcasecmp($a[$sort_col], $b[$sort_col]) * $sort_dir;
    });
  }

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

      $table_values .= "<td class='w3-border' id='". $column['name'] . "-$i'>";
      if ($column_name === 'domain') {
        $badgeColor = 'w3-gray';
        switch ($column_value) {
          case 'login': $badgeColor = 'w3-green'; break;
          case 'logout': $badgeColor = 'w3-blue'; break;
          case 'addDatabase': case 'addToken': $badgeColor = 'w3-teal'; break;
          case 'removeDatabase': case 'removeToken': $badgeColor = 'w3-orange w3-text-white'; break;
          case 'updateDatabase': case 'resetPassword': case 'updatePassword': $badgeColor = 'w3-indigo'; break;
          case 'error': $badgeColor = 'w3-red'; break;
        }
        $table_values .= "<span class='w3-tag w3-round $badgeColor' style='font-size: 11px; font-weight: bold;'>" . sanitize($column_value) . "</span>";
      } elseif ($column_name === 'username') {
        $table_values .= "<strong>" . sanitize($column_value) . "</strong>";
      } elseif ($column_name === 'message') {
        $table_values .= "<span style='font-family: monospace; font-size: 13px;'>" . sanitize($column_value) . "</span>";
      } else {
        $table_values .= sanitize($column_value);
      }
      $table_values .= "</td>";
    }
    $table_values .= "</tr>";
    $i += 1;
  }
  $result = null;

  echo $table_values;
} catch (Throwable $e) {
  die(json_encode(['error' => 'Erreur: ' . $e->getMessage() . ' ligne -> ' . $e->getLine() . ' File - ' . $e->getFile()]));
}
