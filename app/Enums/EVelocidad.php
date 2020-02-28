<?php
namespace App\Enums;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 15/04/2018
 */
class EVelocidad extends AEnum
{

    /**
     *
     * @var array
     */
    protected static $items = array();

    const RAPIDO = 'N_1';

    const NORMAL = 'N_2';

    const LENTO = 'N_3';

    const MUY_LENTO = 'N_4';

    /**
     *
     * @tutorial initializes the values ​​listed
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {20/09/2015}
     * @return void
     */
    protected static function values()
    {
        static::$items[static::RAPIDO] = new EEstadoPagina(1, trans('enums.rapido'),'2000');
        static::$items[static::NORMAL] = new EEstadoPagina(2, trans('enums.normal'),'3000');
        static::$items[static::LENTO] = new EEstadoPagina(3, trans('enums.lento'),'4000');
        static::$items[static::MUY_LENTO] = new EEstadoPagina(4, trans('enums.muy_lento'),'5000');
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