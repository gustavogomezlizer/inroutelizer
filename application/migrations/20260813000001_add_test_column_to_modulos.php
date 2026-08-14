<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migración de prueba: agrega columna migration_test a la tabla modulos.
 *
 * Tabla objetivo : modulos (BD Master - inroutem_lizer_users)
 * Riesgo         : NINGUNO - columna nullable, sin valor por defecto obligatorio.
 * Reversible     : SI - down() elimina únicamente esta columna.
 */
class Migration_Add_test_column_to_modulos extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_column('modulos', [
            'migration_test' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => TRUE,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('modulos', 'migration_test');
    }
}
