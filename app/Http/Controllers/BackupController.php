<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    /**
     * Download Backup Database Lengkap (.sql)
     */
    public function downloadSql(): StreamedResponse
    {
        $dbName = config('database.connections.mysql.database', 'ponpes');
        $filename = 'Backup_Database_Ponpes_Hidayatullah_' . date('Y-m-d_His') . '.sql';

        return response()->streamDownload(function () use ($dbName) {
            $pdo = DB::connection()->getPdo();

            // Set memory limit and execution time for large databases
            ini_set('memory_limit', '512M');
            set_time_limit(300);

            $out = fopen('php://output', 'w');

            // Header SQL
            fwrite($out, "-- ==========================================================\n");
            fwrite($out, "-- PONDOK PESANTREN HIDAYATULLAH TUKSONGO\n");
            fwrite($out, "-- BACKUP DATABASE SISTEM INFORMASI MANAJEMEN PESANTREN\n");
            fwrite($out, "-- Database: `{$dbName}`\n");
            fwrite($out, "-- Tanggal Cadangan: " . date('Y-m-d H:i:s') . " WIB\n");
            fwrite($out, "-- Dibuat oleh Petugas: " . (auth()->user()->name ?? 'Super Admin') . " (" . (auth()->user()->email ?? '-') . ")\n");
            fwrite($out, "-- ==========================================================\n\n");
            fwrite($out, "SET FOREIGN_KEY_CHECKS=0;\n");
            fwrite($out, "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n");
            fwrite($out, "START TRANSACTION;\n");
            fwrite($out, "SET time_zone = \"+00:00\";\n\n");

            // Ambil semua tabel
            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            foreach ($tables as $table) {
                fwrite($out, "-- --------------------------------------------------------\n");
                fwrite($out, "-- Struktur Tabel: `{$table}`\n");
                fwrite($out, "-- --------------------------------------------------------\n");
                fwrite($out, "DROP TABLE IF EXISTS `{$table}`;\n");

                // DDL Create Table
                $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`");
                $createRow = $createStmt->fetch(\PDO::FETCH_NUM);
                fwrite($out, $createRow[1] . ";\n\n");

                // Isi Data Tabel
                $countStmt = $pdo->query("SELECT COUNT(*) FROM `{$table}`");
                $totalRows = (int) $countStmt->fetchColumn();

                if ($totalRows > 0) {
                    fwrite($out, "-- Isi Data untuk Tabel: `{$table}` ({$totalRows} baris)\n");

                    $chunkSize = 250;
                    $offset = 0;

                    while ($offset < $totalRows) {
                        $dataStmt = $pdo->query("SELECT * FROM `{$table}` LIMIT {$chunkSize} OFFSET {$offset}");
                        $rows = $dataStmt->fetchAll(\PDO::FETCH_ASSOC);

                        if (!empty($rows)) {
                            $firstRow = $rows[0];
                            $columns = array_keys($firstRow);
                            $colList = implode('`, `', $columns);

                            fwrite($out, "INSERT INTO `{$table}` (`{$colList}`) VALUES \n");

                            $valueLines = [];
                            foreach ($rows as $r) {
                                $escapedVals = [];
                                foreach ($r as $val) {
                                    if ($val === null) {
                                        $escapedVals[] = "NULL";
                                    } elseif (is_numeric($val) && !preg_match('/^0\d+/', $val)) {
                                        $escapedVals[] = $val;
                                    } else {
                                        $escapedVals[] = $pdo->quote($val);
                                    }
                                }
                                $valueLines[] = "(" . implode(", ", $escapedVals) . ")";
                            }

                            fwrite($out, implode(",\n", $valueLines) . ";\n\n");
                        }

                        $offset += $chunkSize;
                    }
                }
            }

            fwrite($out, "SET FOREIGN_KEY_CHECKS=1;\n");
            fwrite($out, "COMMIT;\n");
            fwrite($out, "-- === SELESAI PENCADANGAN DATABASE === --\n");

            fclose($out);
        }, $filename, [
            'Content-Type' => 'application/sql',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
