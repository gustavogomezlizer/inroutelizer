# Sistema de Migraciones — InRoute Lizer (CodeIgniter 3)

## Arquitectura de bases de datos

Este proyecto usa **dos capas de BD**:

| Capa | Descripción | Conexión CI3 |
|---|---|---|
| **BD Master** | `inroutem_lizer_users`. Contiene `usuarios`, `empresas`, `funciones`, `modulos`, `modulos_sub`. | `$this->db` (default) |
| **BDs por cliente** | Una BD por empresa/cliente. Contiene `clientes`, `cat_rutas`, etc. | `switch_db_dinamico($empresa)` |

> Las migraciones estándar de CI3 operan sobre la **BD Master** (`$this->db`).
> Los cambios a BDs de clientes deben hacerse con migraciones personalizadas (ver sección al final).

---

## Configuración del sistema

### Archivos involucrados

```
application/
├── config/
│   └── migration.php         ← configuración de migraciones
├── controllers/
│   └── Migrate.php           ← controlador protegido
└── migrations/
    └── YYYYMMDDHHIISS_nombre.php  ← archivos de migración
```

### Parámetros en `migration.php`

| Parámetro | Valor | Descripción |
|---|---|---|
| `migration_enabled` | `TRUE` | Habilita el sistema |
| `migration_type` | `timestamp` | Nombrado por fecha+hora |
| `migration_table` | `migrations` | Tabla de control en BD Master |
| `migration_auto_latest` | `FALSE` | No se auto-ejecuta nunca |
| `migration_path` | `APPPATH.'migrations/'` | Carpeta de archivos |

---

## 1. Cómo crear una nueva migración

### Nombre del archivo

Formato: `YYYYMMDDHHIISS_descripcion_en_snake_case.php`

```
Ejemplo: 20260814120000_add_telefono_to_clientes.php
```

**Usa la fecha y hora actual** para garantizar unicidad y orden.

### Estructura del archivo

```php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_telefono_to_clientes extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_column('clientes', [
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => TRUE,
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('clientes', 'telefono');
    }
}
```

> **Regla del nombre de clase:** `Migration_` + `ucfirst(descripcion_en_snake_case)`
> Ejemplo: archivo `..._add_telefono_to_clientes.php` → clase `Migration_Add_telefono_to_clientes`

### Operaciones disponibles en `up()` / `down()`

```php
// Crear tabla
$this->dbforge->create_table('mi_tabla', TRUE);

// Agregar columna
$this->dbforge->add_column('tabla', ['columna' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => TRUE]]);

// Modificar columna
$this->dbforge->modify_column('tabla', ['columna' => ['type' => 'INT', 'constraint' => 11]]);

// Eliminar columna
$this->dbforge->drop_column('tabla', 'columna');

// Agregar índice (usa $this->db->query() para SQL directo)
$this->db->query('ALTER TABLE tabla ADD INDEX idx_nombre (columna)');

// SQL directo cuando sea necesario
$this->db->query('ALTER TABLE tabla ADD COLUMN nueva_col INT NULL DEFAULT 0');
```

### Reglas de seguridad

- `up()` → SOLO operaciones que **agregan o modifican estructura** (no borran datos)
- `down()` → revierte exactamente lo que hizo `up()`
- **NUNCA** uses `TRUNCATE`, `DROP TABLE` ni `DELETE` en una migración
- **NUNCA** hardcodees credenciales, contraseñas ni datos sensibles
- Si `down()` requiere eliminar una columna, está permitido (es revertir, no destruir)

---

## 2. Cómo ejecutar migraciones en desarrollo (localhost)

### Opción A — Desde el navegador (localhost)

```
http://localhost/index.php/Migrate/run
```

### Opción B — Desde línea de comandos

```bash
cd D:\LIZER\InRouteLizerDesarrolloLocal
php index.php Migrate run
```

### Ver estado actual

```bash
php index.php Migrate list_all
```

Salida de ejemplo:
```
Migraciones disponibles (version BD actual: 20260813000001):
------------------------------------------------------------
  [APLICADA ] 20260813000001_add_test_column_to_modulos  <-- ACTUAL
  [PENDIENTE] 20260814120000_add_telefono_to_clientes
```

---

## 3. Cómo revisar la versión actual

```bash
php index.php Migrate version
# Version actual de la BD: 20260813000001
```

O desde el navegador (localhost):
```
http://localhost/index.php/Migrate/version
```

La versión es el timestamp de la **última migración aplicada**. `0` significa que ninguna ha sido ejecutada.

---

## 4. Cómo hacer rollback

Revierte la **última migración aplicada** (ejecuta su método `down()`):

```bash
php index.php Migrate rollback
```

> El rollback solo revierte UNA migración a la vez.
> Para revertir varias, ejecuta `rollback` múltiples veces.

**Importante:** El rollback solo es seguro si el método `down()` fue implementado correctamente.

---

## 5. Cómo probar una migración antes de hacer commit

1. Verifica el estado actual:
   ```bash
   php index.php Migrate version
   ```

2. Ejecuta la migración:
   ```bash
   php index.php Migrate run
   ```

3. Verifica en MySQL que el cambio se aplicó:
   ```sql
   DESCRIBE modulos;  -- o la tabla correspondiente
   ```

4. Prueba el rollback:
   ```bash
   php index.php Migrate rollback
   ```

5. Verifica que el rollback lo revirtió correctamente.

6. Si todo funciona, vuelve a aplicar la migración y haz commit:
   ```bash
   php index.php Migrate run
   git add application/migrations/
   git commit -m "feat: agrega columna X a tabla Y"
   ```

---

## 6. Cómo subir migraciones a GitHub

Las migraciones se versionan en Git como cualquier archivo PHP:

```bash
# Después de crear el archivo de migración
git add application/migrations/20260814120000_add_telefono_to_clientes.php
git commit -m "db: agrega columna telefono a clientes"
git push origin main
```

> Nunca incluyas credenciales en los archivos de migración.
> Nunca ignores la carpeta `application/migrations/` en `.gitignore`.

---

## 7. Cómo actualizar el proyecto en cPanel

cPanel descarga automáticamente el código cuando haces `git push` (vía `.cpanel.yml`).

Pasos:
1. Haz `git push origin main` desde tu máquina local.
2. cPanel detecta el push y copia los archivos a `public_html/` automáticamente.
3. Las migraciones llegan al servidor **sin ejecutarse** (esto es intencional).
4. Luego ejecutas las migraciones manualmente (ver sección 8).

---

## 8. Cómo ejecutar migraciones en producción de forma SEGURA

### Requisitos previos
- [ ] Tener backup de la BD de producción
- [ ] Haber probado la migración en desarrollo
- [ ] Confirmar que el rollback funciona en desarrollo

### Pasos

1. **Conectarse al servidor** vía SSH (Terminal de cPanel):
   ```bash
   cd /home/inroutem/public_html
   ```

2. **Revisar migraciones pendientes:**
   ```bash
   php index.php Migrate list_all
   ```

3. **Ejecutar migraciones:**
   ```bash
   php index.php Migrate run
   ```

4. **Verificar:**
   ```bash
   php index.php Migrate version
   ```

> El controlador `Migrate` solo acepta acceso CLI o desde 127.0.0.1.
> Desde el navegador de producción siempre recibirás un error 403.

---

## 9. Qué hacer si una migración falla

### Error en `up()` (al aplicar)

1. Lee el mensaje de error:
   ```bash
   php index.php Migrate run
   # [ERROR] ...mensaje de error...
   ```

2. Corrige el error en el archivo de migración.

3. Si la migración quedó parcialmente aplicada, ejecuta el rollback:
   ```bash
   php index.php Migrate rollback
   ```

4. Corrige el archivo y vuelve a ejecutar:
   ```bash
   php index.php Migrate run
   ```

### Error en `down()` (al revertir)

1. Verifica manualmente en MySQL qué estado quedó la tabla.
2. Si el rollback no puede completarse automáticamente, aplica el SQL de reversión manual:
   ```sql
   -- Ejemplo: revertir manualmente una columna agregada
   ALTER TABLE modulos DROP COLUMN migration_test;
   ```
3. Luego actualiza la tabla `migrations` manualmente:
   ```sql
   -- Establece la versión anterior manualmente
   UPDATE migrations SET version = 0 WHERE id = 1;
   ```

### En producción: si algo sale muy mal

```bash
# 1. Obtén backup inmediato
mysqldump -u usuario -p nombre_bd > backup_$(date +%Y%m%d_%H%M%S).sql

# 2. Intenta rollback
php index.php Migrate rollback

# 3. Si el rollback falla, restaura el backup
mysql -u usuario -p nombre_bd < backup_YYYYMMDD_HHMMSS.sql
```

---

## Migraciones para BDs de clientes

Para modificar una BD específica de un cliente, puedes usar una migración personalizada con `switch_db_dinamico`:

```php
public function up()
{
    // Obtener la conexión del cliente con idCliente = 1
    $config = switch_db_dinamico(1, 0);
    $db_cliente = $this->load->database($config, TRUE);

    $db_cliente->query('ALTER TABLE cat_rutas ADD COLUMN migration_test VARCHAR(100) NULL');
}

public function down()
{
    $config = switch_db_dinamico(1, 0);
    $db_cliente = $this->load->database($config, TRUE);

    $db_cliente->query('ALTER TABLE cat_rutas DROP COLUMN migration_test');
}
```

> **Nota:** Para aplicar un cambio a TODOS los clientes, itera sobre la tabla `empresas`
> y ejecuta la migración para cada `idCliente`.

---

## Flujo completo Desarrollo → GitHub → cPanel → MySQL

```
[Desarrollo Local]
  1. Modifica código
  2. Crea migración:  application/migrations/YYYYMMDDHHIISS_nombre.php
  3. Prueba:          php index.php Migrate run
  4. Prueba rollback: php index.php Migrate rollback
  5. Vuelve a aplicar: php index.php Migrate run
  6. Commit:          git add . && git commit -m "db: descripcion"
  7. Push:            git push origin main
         ↓
[GitHub]
  Almacena el código con la nueva migración
         ↓
[cPanel — automático vía .cpanel.yml]
  Copia el código a public_html/
  (las migraciones NO se ejecutan solas)
         ↓
[Tú — manual vía SSH en cPanel Terminal]
  cd /home/inroutem/public_html
  php index.php Migrate list_all   ← verifica qué hay pendiente
  php index.php Migrate run        ← aplica migraciones
         ↓
[MySQL Producción]
  Estructura actualizada sin perder datos
```
