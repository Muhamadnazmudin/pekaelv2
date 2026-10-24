<?php

namespace App\Controllers;

class Backup extends BaseController
{
    public function index()
    {
        $tables = $this->db->listTables();

        $data['title']  = 'Backup Database';
        $data['tables'] = $tables;

        return template('admin/backup/index', $data);
    }

    public function download()
    {
        $tables = $this->db->listTables();
        $sql = "-- PEKAEL Database Backup\n";
        $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- PHP Version: " . PHP_VERSION . "\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            // Abaikan view
            if (str_starts_with($table, 'v_')) continue;

            $createRow = $this->db->query("SHOW CREATE TABLE `$table`")->getRowArray();
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $createRow['Create Table'] . ";\n\n";

            $rows = $this->db->table($table)->get()->getResultArray();
            if (!empty($rows)) {
                $sql .= "INSERT INTO `$table` VALUES\n";
                $inserts = [];
                foreach ($rows as $r) {
                    $vals = array_map(function ($val) {
                        if ($val === null) return 'NULL';
                        return "'" . addslashes($val) . "'";
                    }, array_values($r));
                    $inserts[] = "(" . implode(', ', $vals) . ")";
                }
                $sql .= implode(",\n", $inserts) . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        $fileName = 'backup_pekael_' . date('Ymd_His') . '.sql';

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . strlen($sql));

        echo $sql;
        exit;
    }
}
