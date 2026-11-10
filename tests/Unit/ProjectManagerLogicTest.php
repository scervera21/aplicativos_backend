<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Actividad;

class ProjectManagerLogicTest extends TestCase
{
    /**
     * Prueba que el mutator de Actividad fije completado=true y estado=CULMINADO en 100.
     */
    public function test_actividad_100_percent_sets_completado_and_culminado(): void
    {
        $actividad = new Actividad();
        $actividad->porcentaje = 100;

        $this->assertEquals(100, $actividad->porcentaje);
        $this->assertTrue($actividad->completado);
        $this->assertEquals('CULMINADO', $actividad->estado);
        $this->assertEquals(now()->format('Y-m-d'), $actividad->fecha_culminacion);
    }

    /**
     * Prueba que el mutator revierta completado=false y estado=PENDIENTE si baja de 100.
     */
    public function test_actividad_less_than_100_reverts_culminado(): void
    {
        $actividad = new Actividad();
        $actividad->porcentaje = 100;

        $this->assertEquals('CULMINADO', $actividad->estado);
        $this->assertTrue($actividad->completado);

        // Bajar porcentaje a 60
        $actividad->porcentaje = 60;

        $this->assertEquals(60, $actividad->porcentaje);
        $this->assertFalse($actividad->completado);
        $this->assertEquals('PENDIENTE', $actividad->estado);
        $this->assertNull($actividad->fecha_culminacion);
    }

    /**
     * Prueba que el mutator restrinja el porcentaje estrictamente entre 0 y 100.
     */
    public function test_actividad_restricts_porcentaje_to_0_100(): void
    {
        $actividad = new Actividad();

        $actividad->porcentaje = -15;
        $this->assertEquals(0, $actividad->porcentaje);

        $actividad->porcentaje = 150;
        $this->assertEquals(100, $actividad->porcentaje);
    }
}

