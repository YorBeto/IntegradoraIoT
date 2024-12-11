<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateObtenerEstadisticasKidProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE DEFINER=`root`@`localhost` PROCEDURE `obtenerEstadisticasKid`(
                IN p_nombre_kid VARCHAR(50),
                IN p_apellido_kid VARCHAR(50)
            )
            BEGIN
                DECLARE v_id_kid BIGINT;

                -- Obtener el ID del niño basado en el nombre y apellido
                SELECT id_kid INTO v_id_kid
                FROM kids
                WHERE nombre = p_nombre_kid AND apellido_paterno = p_apellido_kid
                LIMIT 1;

                -- Verificar si se encontró el niño
                IF v_id_kid IS NOT NULL THEN
                    -- Obtener las estadísticas generales del niño
                    SELECT 
                        j.nombre AS nombre_juego, 
                        eg.total_tiempo_jugado, 
                        eg.numero_partidas
                    FROM 
                        estadisticas_generales eg
                    JOIN kids k ON eg.id_kid = k.id_kid
                    JOIN juegos j ON eg.id_juego = j.id_juego
                    WHERE 
                        eg.id_kid = v_id_kid;
                ELSE
                    SIGNAL SQLSTATE "45000"
                    SET MESSAGE_TEXT = "Niño no encontrado";
                END IF;
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
        DB::unprepared('DROP PROCEDURE IF EXISTS obtenerEstadisticasKid');
    }
}

