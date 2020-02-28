<?php
use Illuminate\Database\Seeder;

/**
 *
 * @tutorial Working Class
 * @author Eminson Mendoza ~~ ;
 * @since {19/06/2018}
 */
class PermisosSeeder extends Seeder
{

    /**
     *
     * @tutorial Method Description: Run the database seeds.
     * @author Eminson Mendoza ~~ ;
     * @since {19/06/2018}
     * @return void
     */
    public function run()
    {
        factory(App\Models\Permiso::class, 100)->create();
    }
}
