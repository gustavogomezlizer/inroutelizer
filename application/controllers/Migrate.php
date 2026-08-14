<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de migraciones CI3.
 *
 * Acceso: CLI siempre | Web solo desde 127.0.0.1 (localhost).
 *
 * Métodos:
 *   run       → ejecuta todas las migraciones pendientes
 *   version   → muestra la versión actual de la BD
 *   rollback  → revierte la última migración aplicada
 *   list_all  → lista todas las migraciones con su estado
 *
 * Uso CLI:
 *   php index.php Migrate run
 *   php index.php Migrate version
 *   php index.php Migrate rollback
 *   php index.php Migrate list_all
 */
class Migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->_check_access();
        $this->load->library('migration');
    }

    private function _check_access()
    {
        if (is_cli()) return;

        $allowed = ['127.0.0.1', '::1'];
        if ( ! in_array($this->input->ip_address(), $allowed)) {
            show_error('Acceso restringido. Solo CLI o localhost. Usa: php index.php Migrate run', 403);
        }
    }

    public function index()
    {
        $this->run();
    }

    /** Ejecuta todas las migraciones pendientes */
    public function run()
    {
        $before = $this->_current_version();
        $result = $this->migration->latest();

        if ($result === FALSE) {
            $this->_print('[ERROR] ' . $this->migration->error_string());
            return;
        }

        $after = $this->_current_version();

        if ($before === $after) {
            $this->_print('[OK] No hay migraciones pendientes. Version actual: ' . $after);
        } else {
            $this->_print('[OK] Migraciones ejecutadas.');
            $this->_print('     Version anterior : ' . $before);
            $this->_print('     Version actual   : ' . $after);
        }
    }

    /** Muestra la versión actual registrada en la BD */
    public function version()
    {
        $this->_print('Version actual de la BD: ' . $this->_current_version());
    }

    /** Revierte la última migración aplicada (ejecuta down()) */
    public function rollback()
    {
        $migrations = $this->_get_migration_list();
        $current    = (string) $this->_current_version();

        if (empty($migrations) || $current === '0') {
            $this->_print('No hay migraciones que revertir (version actual: 0).');
            return;
        }

        // Busca la versión inmediatamente anterior a la actual
        $previous = 0;
        $keys     = array_keys($migrations);
        for ($i = count($keys) - 1; $i >= 0; $i--) {
            if ((string) $keys[$i] === $current) {
                $previous = ($i > 0) ? $keys[$i - 1] : 0;
                break;
            }
        }

        $this->_print('Revirtiendo desde version ' . $current . ' hacia ' . $previous . ' ...');

        $result = $this->migration->version($previous);

        if ($result === FALSE) {
            $this->_print('[ERROR] Rollback fallido: ' . $this->migration->error_string());
            return;
        }
        $this->_print('[OK] Rollback completado. Version actual: ' . $this->_current_version());
    }

    /** Lista todas las migraciones con estado aplicada/pendiente */
    public function list_all()
    {
        $migrations = $this->_get_migration_list();
        $current    = (string) $this->_current_version();

        if (empty($migrations)) {
            $this->_print('No se encontraron archivos de migracion en:');
            $this->_print('  ' . APPPATH . 'migrations/');
            return;
        }

        $this->_print('Migraciones (version BD actual: ' . $current . '):');
        $this->_print(str_repeat('-', 65));
        foreach ($migrations as $ver => $name) {
            $aplicada = ($current !== '0' && (string) $ver <= $current);
            $estado   = $aplicada ? '[APLICADA ]' : '[PENDIENTE]';
            $cursor   = ((string) $ver === $current) ? ' <-- ACTUAL' : '';
            $this->_print("  {$estado} {$ver}_{$name}{$cursor}");
        }
    }

    // Lee la version actual directamente de la tabla migrations (BIGINT)
    private function _current_version()
    {
        $row = $this->db->select('version')->get('migrations')->row();
        return $row ? (string) $row->version : '0';
    }

    // Devuelve array [timestamp => nombre] ordenado ascendente
    private function _get_migration_list()
    {
        $result = [];
        foreach (glob(APPPATH . 'migrations/*_*.php') as $file) {
            $name = basename($file, '.php');
            if (preg_match('/^(\d{14})_(.+)$/', $name, $m)) {
                $result[$m[1]] = $m[2];
            }
        }
        ksort($result);
        return $result;
    }

    private function _print($msg)
    {
        echo is_cli()
            ? $msg . PHP_EOL
            : '<pre style="font-family:monospace;font-size:14px;">' . htmlspecialchars($msg) . '</pre>';
    }
}
