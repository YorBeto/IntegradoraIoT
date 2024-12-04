<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateInsertarDatosProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE insertar_Datos(
                IN p_email VARCHAR(255),
                IN p_password VARCHAR(255),
                IN p_foto_perfil VARCHAR(255),
                IN p_nombre_persona VARCHAR(255),
                IN p_apellido_paterno VARCHAR(255),
                IN p_apellido_materno VARCHAR(255),
                IN p_fecha_nacimiento DATE,
                IN p_sexo ENUM(\'Masculino\', \'Femenino\'),
                IN p_telefono VARCHAR(20)
            )
            BEGIN
                DECLARE user_id INT;
                DECLARE rol_id INT;

                -- Insertar un nuevo usuario
                INSERT INTO users (email, password, foto_perfil, created_at, updated_at)
                VALUES (p_email, p_password, IFNULL(p_foto_perfil, NULL), NOW(), NOW());

                -- Obtener el ID del usuario insertado
                SET user_id = LAST_INSERT_ID();

                -- Insertar en la tabla personas
                INSERT INTO personas (nombre, apellido_paterno, apellido_materno, fecha_nacimiento, sexo, telefono, usuario_id, created_at, updated_at)
                VALUES (p_nombre_persona, p_apellido_paterno, p_apellido_materno, p_fecha_nacimiento, p_sexo, p_telefono, user_id, NOW(), NOW());

                -- Comprobar si el rol "usuario" existe
                SET rol_id = (SELECT id FROM roles WHERE nombre = \'usuario\' LIMIT 1);

                -- Si el rol "usuario" no existe, lo creamos
                IF rol_id IS NULL THEN
                    INSERT INTO roles (nombre, created_at, updated_at)
                    VALUES (\'usuario\', NOW(), NOW());
                    -- Obtener el ID del nuevo rol "usuario"
                    SET rol_id = LAST_INSERT_ID();
                END IF;

                -- Asociar el usuario con el rol "usuario"
                INSERT INTO roles_usuarios (usuario_id, rol_id, created_at, updated_at)
                VALUES (user_id, rol_id, NOW(), NOW());

            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS insertar_Datos');
    }
}
