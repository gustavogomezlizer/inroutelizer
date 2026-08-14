<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controlador de migraciones CI3.
 *
 * Acceso permitido:
 *   - CLI siempre:  php index.php Migrate run
 *   - Web:          solo desde 127.0.0.1 (localhost)
 *
 * Métodos disponibles:
 *   run            Ejecuta todas las migraciones pendientes
 *   version        Muestra la versión actual de la BD
 *   rollback       Revierte la última migración aplicada
 *   list_all       Lista todas las migraciones y su estado
 */
class Migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->_check_access();
        $this->load->library('migration');
    }

    // Rechaza acceso web que no sea desde localhost
    private function _check_access()
    {
        if (is_cli()) return;

        $allowed = ['127.0.0.1', '::1'];
        if ( ! in_array($this->input->ip_address(), $allowed)) {
            show_error('Acceso restringido. Usa CLI en el servidor: php index.php Migrate run', 403);
        }
    }

    /** Alias de run() para acceso por defecto */
    public function index()
    {
        $this->run();
    }

    /** Ejecuta todas las migraciones pendientes hasta la última */
    public function run()
    {
        if ($this->migration->latest() === FALSE) {
            $this->_print('[ERROR] ' . $this->migration->error_string());
            return;
        }
        $this->_print('[OK] Migraciones ejecutadas. Version actual: ' . $this->migration->get_version());
    }

    /** Muestra la versión actual registrada en la BD */
    public function version()
    {
        $this->_print('Version actual de la BD: ' . $this->migration->get_version());
    }

    /** Revierte la última migración aplicada */
    public function rollback()
    {
        $migrations = $this->_get_migration_list();
        $current    = (string) $this->migration->get_version();

        if (empty($migrations) || $current === '0') {
            $this->_print('No hay migraciones que revertir (version actual: 0).');
            return;
        }

        $previous = 0;
        $keys     = array_keys($migrations);

        for ($i = count($keys) - 1; $i >= 0; $i--) {
            if ((string) $keys[$i] === $current) {
                $previous = ($i > 0) ? $keys[$i - 1] : 0;
                break;
            }
        }

        if ($this->migration->version($previous) === FALSE) {
            $this->_print('[ERROR] Rollback fallido: ' . $this->migration->error_string());
            return;
        }
        $this->_print('[OK] Rollback completado. Version actual: ' . $this->migration->get_version());
    }

    /** Lista todas las migraciones con su estado (aplicada / pendiente) */
    public function list_all()
    {
        $migrations = $this->_get_migration_list();
        $current    = (int) $this->migration->get_version();

        if (empty($migrations)) {
            $this->_print('No se encontraron archivos de migracion en ' . APPPATH . 'migrations/');
            return;
        }

        $this->_print('Migraciones disponibles (version BD actual: ' . $current . '):');
        $this->_print(str_repeat('-', 60));
        foreach ($migrations as $ver => $name) {
            $estado = ($ver <= $current) ? '[APLICADA ]' : '[PENDIENTE]';
            $cursor = ($ver === $current) ? ' <-- ACTUAL' : '';
            $this->_print("  {$estado} {$ver}_{$name}{$cursor}");
        }
    }

    // Devuelve array [version => nombre] de todos los archivos en migrations/
    private function _get_migration_list()
    {
        $result = [];
        foreach (glob(APPPATH . 'migrations/*_*.php') as $file) {
            $name = basename($file, '.php');
            if (preg_match('/^(\d+)_(.+)$/', $name, $m)) {
                $result[(int) $m[1]] = $m[2];
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
