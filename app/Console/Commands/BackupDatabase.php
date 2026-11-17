<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDO;
use Symfony\Component\Process\Process;
use Throwable;

class BackupDatabase extends Command
{
    protected $signature = 'app:backup-database {--bin= : Directory containing mysqldump and mysql}';

    protected $description = 'Back up MySQL and verify restoration in an isolated temporary database';

    public function handle(): int
    {
        $connection = DB::connection();
        if ($connection->getDriverName() !== 'mysql') {
            $this->error('Este comando requiere MySQL.');

            return self::FAILURE;
        }

        $config = $connection->getConfig();
        $pdo = $connection->getPdo();
        $temporaryDatabase = 'backup_verify_'.strtolower(Str::random(20));
        $directory = storage_path('app/private/backups');
        if (! is_dir($directory)) {
            mkdir($directory, 0700, true);
        }
        $path = $directory.'/menuatumedida-'.now()->format('Ymd-His').'-'.Str::random(6).'.sql';
        $binaryDirectory = rtrim((string) $this->option('bin'), '/\\');
        $binary = fn (string $name): string => $binaryDirectory === '' ? $name : $binaryDirectory.DIRECTORY_SEPARATOR.$name.(PHP_OS_FAMILY === 'Windows' ? '.exe' : '');
        $arguments = ['--host='.$config['host'], '--port='.$config['port'], '--user='.$config['username']];
        $environment = ['MYSQL_PWD' => (string) $config['password']];
        $createdTemporaryDatabase = false;
        $verificationServer = null;
        $verificationPdo = null;
        $stage = 'lectura original';

        try {
            $before = $this->fingerprints($pdo, $config['database']);
            $stage = 'mysqldump';
            $dump = new Process([$binary('mysqldump'), ...$arguments, '--single-transaction', '--skip-lock-tables', '--no-tablespaces', '--hex-blob', '--routines', '--events', '--result-file='.$path, $config['database']], null, $environment);
            $dump->setTimeout(120)->run();
            if (! $dump->isSuccessful() || ! is_file($path) || filesize($path) === 0) {
                throw new \RuntimeException('Dump failed');
            }

            $stage = 'inicialización del servidor de verificación aislado';
            $verificationDirectory = $directory.'/'.$temporaryDatabase;
            mkdir($verificationDirectory, 0700);
            $initialize = new Process([$binary('mysqld'), '--no-defaults', '--initialize-insecure', '--datadir='.$verificationDirectory]);
            $initialize->setTimeout(120)->run();
            if (! $initialize->isSuccessful()) {
                throw new \RuntimeException('Initialization failed');
            }
            $port = random_int(20000, 45000);
            $verificationServer = new Process([$binary('mysqld'), '--no-defaults', '--datadir='.$verificationDirectory, '--port='.$port, '--bind-address=127.0.0.1', '--mysqlx=OFF', '--skip-log-bin']);
            $verificationServer->setTimeout(null)->start();
            for ($attempt = 0; $attempt < 60; $attempt++) {
                try {
                    $verificationPdo = new PDO('mysql:host=127.0.0.1;port='.$port, 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                    break;
                } catch (Throwable) {
                    usleep(500000);
                }
            }
            if (! $verificationPdo) {
                throw new \RuntimeException('Verification server unavailable');
            }
            $verificationPdo->exec('CREATE DATABASE `'.$temporaryDatabase.'`');
            $createdTemporaryDatabase = true;
            $stage = 'restauración y comparación';
            $restore = new Process([$binary('mysql'), '--host=127.0.0.1', '--port='.$port, '--user=root', '--database='.$temporaryDatabase], null, ['MYSQL_PWD' => '']);
            $input = fopen($path, 'rb');
            try {
                $restore->setInput($input)->setTimeout(120)->run();
            } finally {
                fclose($input);
            }
            if (! $restore->isSuccessful()) {
                throw new \RuntimeException('Restoration verification failed');
            }
            $stage = 'comparación del contenido restaurado';
            $restored = $this->fingerprints($verificationPdo, $temporaryDatabase);
            if ($before !== $restored) {
                foreach ($before as $table => $fingerprint) {
                    if ($fingerprint !== ($restored[$table] ?? null)) {
                        $this->line('Diferencia en '.$table.': estructura '.(($fingerprint['schema'] === ($restored[$table]['schema'] ?? null)) ? 'igual' : 'distinta').', filas '.(($fingerprint['rows'] === ($restored[$table]['rows'] ?? null)) ? 'iguales' : 'distintas'));
                    }
                }
                throw new \RuntimeException('Restored content differs');
            }
            $stage = 'comparación del contenido original';
            if ($before !== $this->fingerprints($pdo, $config['database'])) {
                throw new \RuntimeException('Original content changed');
            }

            $this->info('Respaldo restaurado y verificado: '.$path);
            foreach (['users', 'platos', 'categorias'] as $table) {
                $this->line($table.': '.$connection->table($table)->count());
            }
            $this->line('Administradores: '.$connection->table('users')->where('es_admin', true)->count());

            return self::SUCCESS;
        } catch (Throwable) {
            $this->error('No se pudo verificar el respaldo en: '.$stage.'. No se modificó la base original. Revisá permisos y ejecutables MySQL.');

            return self::FAILURE;
        } finally {
            if ($createdTemporaryDatabase) {
                $verificationPdo->exec('DROP DATABASE `'.$temporaryDatabase.'`');
            }
            $verificationServer?->stop();
        }
    }

    /** @return array<string, array{schema: string, rows: array<int, string>}> */
    private function fingerprints(PDO $pdo, string $database): array
    {
        $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
        $tables = $pdo->query('SHOW FULL TABLES FROM `'.$database.'` WHERE Table_type = "BASE TABLE"')->fetchAll(PDO::FETCH_COLUMN);
        sort($tables);
        $fingerprints = [];
        foreach ($tables as $table) {
            $qualifiedTable = '`'.str_replace('`', '``', $database).'`.`'.str_replace('`', '``', $table).'`';
            $schema = $pdo->query('SHOW CREATE TABLE '.$qualifiedTable)->fetch(PDO::FETCH_NUM)[1];
            $schema = preg_replace('/ CHARACTER SET [a-zA-Z0-9_]+ COLLATE /', ' COLLATE ', $schema);
            $rows = $pdo->query('SELECT * FROM '.$qualifiedTable)->fetchAll(PDO::FETCH_ASSOC);
            $rowFingerprints = array_map(fn (array $row): string => hash('sha256', serialize($row)), $rows);
            sort($rowFingerprints);
            $fingerprints[$table] = ['schema' => $schema, 'rows' => $rowFingerprints];
        }

        return $fingerprints;
    }
}
