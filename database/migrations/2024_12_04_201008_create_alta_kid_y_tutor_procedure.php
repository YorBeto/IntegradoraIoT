<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateAltaKidYTutorProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE PROCEDURE altaKidYtutor(
                IN p_nombre_kid VARCHAR(50),
                IN p_apellido_paterno_kid VARCHAR(50),
                IN p_fecha_nacimiento_kid DATE,
                IN p_sexo_kid ENUM(\'Masculino\',\'Femenino\'),
                IN p_foto_perfil_kid VARCHAR(255),
                IN p_id_persona BIGINT
            )
            BEGIN
                DECLARE v_id_tutor BIGINT;

                -- Iniciar transacción
                START TRANSACTION;

                -- Verificar si ya existe un tutor para esta persona
                SELECT id_tutor INTO v_id_tutor
                FROM tutores
                WHERE id_persona = p_id_persona
                LIMIT 1;

                -- Si no existe un tutor, insertamos uno nuevo
                IF v_id_tutor IS NULL THEN
                    INSERT INTO tutores (id_persona, created_at, updated_at)
                    VALUES (p_id_persona, NOW(), NOW());

                    -- Obtener el ID del tutor insertado
                    SET v_id_tutor = LAST_INSERT_ID();
                END IF;

                -- Insertar el niño en la tabla kids
                INSERT INTO kids (nombre, apellido_paterno, sexo, fecha_nacimiento, foto_perfil, id_tutor, created_at, updated_at)
                VALUES (p_nombre_kid, p_apellido_paterno_kid, p_sexo_kid, p_fecha_nacimiento_kid, p_foto_perfil_kid, v_id_tutor, NOW(), NOW());

                -- Confirmar la transacción
                COMMIT;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS altaKidYtutor');
    }
}
