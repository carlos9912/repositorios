<?php

namespace App\Enums;



/**

 *

 * @tutorial Working Class

 * @author Bayron Tarazona ~bayronthz@gmail.com

 * @since 15/04/2018

 */

class ETipoMovimiento extends AEnum

{



    /**

     *

     * @var array

     */

    protected static $items = array();



    const ENVIO_GIRO = 'N_1';



    const RETIRO_GIRO = 'N_2';



    const GIRO_BANCO = 'N_3';



    const RECARGA = 'N_4';



    const PAGO = 'N_5';



    const OTRO = 'N_6';

    const SOAT = 'N_7';



    /**

     *

     * @tutorial initializes the values listed

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {20/09/2015}

     * @return void

     */

    protected static function values()

    {

        static::$items[static::ENVIO_GIRO] = new ETipoMovimiento(1, '<i class="fas fa-arrow-alt-circle-up text-warning"> </i> Envío','fade');

        static::$items[static::RETIRO_GIRO] = new ETipoMovimiento(2, '<i class="fas fa-arrow-alt-circle-down text-success"> </i> Retiro','slideup');

        static::$items[static::GIRO_BANCO] = new ETipoMovimiento(3, '<i class="fas fa-arrow-alt-circle-right text-primary"> </i> Giro bancarío','slidedown');

        static::$items[static::RECARGA] = new ETipoMovimiento(4, '<i class="fas fa-arrow-alt-circle-left text-inverse"> </i> Recarga','slideright');

        static::$items[static::PAGO] = new ETipoMovimiento(5, '<i class="fas fa-arrow-alt-circle-left text-inverse"> </i>Pago','slideleft');

        static::$items[static::OTRO] = new ETipoMovimiento(6, '<i class="fas fa-arrow-alt-circle-left text-inverse"> </i>Otro','slideleft');

        static::$items[static::SOAT] = new ETipoMovimiento(7, '<i class="fas fa-arrow-alt-circle-left text-inverse"> </i>SOAT','slideleft');


    }



    /**

     *

     * @tutorial Method Description: returns list of data for selects

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {13/05/2018}

     * @return multitype:NULL

     */

    public static function items()

    {

        if (blank(static::$items)) {

            static::values();

        }

        $items = [];

        foreach (static::$items as $item) {

            $items[$item->getId()] = $item->getDescription();

        }

        return $items;

    }



    /**

     *

     * @tutorial Method Description: search for EEstadoPagina index

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {23/07/2017}

     * @param string $search            

     * @return AEnum

     */

    public static function index($search)

    {

        if (blank(static::$items)) {

            static::values();

        }

        return static::$items[$search];

    }



    /**

     *

     * @tutorial get result of the EEstadoPagina values

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {20/09/2015}

     * @param string $search            

     * @return Ambigous <\app\enums\EEstadoPagina, EEstadoPagina>

     */

    public static function result($search)

    {

        if (blank(static::$items)) {

            static::values();

        }

        $result = new EEstadoPagina(NULL, NULL);

        foreach (static::$items as $item) {

            if ($item->getId() == $search) {

                $result = $item;

                break;

            }

        }

        return $result;

    }



    /**

     *

     * @tutorial get data values EEstadoPagina listed

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {20/09/2015}

     * @return array

     */

    public static function data()

    {

        if (blank(static::$items)) {

            static::values();

        }

        return static::$items;

    }

}