<?php
namespace App\Enums;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 15/04/2018
 */
class ETransicion extends AEnum
{

    /**
     *
     * @var array
     */
    protected static $items = array();

    const FADE = 'N_1';

    const DESLIZAR_UP = 'N_2';

    const DESLIZAR_DOWN = 'N_3';

    const DESLIZAR_RIGHT = 'N_4';

    const DESLIZAR_LEFT = 'N_5';

    const RAYAS = 'N_6';

    const CUADROS = 'N_7';

    /**
     *
     * @tutorial initializes the values ​​listed
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {20/09/2015}
     * @return void
     */
    protected static function values()
    {
        static::$items[static::FADE] = new EEstadoPagina(1, trans('enums.desvanecer'),'fade');
        static::$items[static::DESLIZAR_UP] = new EEstadoPagina(2, trans('enums.deslizar_up'),'slideup');
        static::$items[static::DESLIZAR_DOWN] = new EEstadoPagina(3, trans('enums.deslizar_down'),'slidedown');
        static::$items[static::DESLIZAR_RIGHT] = new EEstadoPagina(4, trans('enums.deslizar_right'),'slideright');
        static::$items[static::DESLIZAR_LEFT] = new EEstadoPagina(5, trans('enums.deslizar_left'),'slideleft');
        static::$items[static::RAYAS] = new EEstadoPagina(6, trans('enums.rayas'),'slotslide-vertical');
        static::$items[static::CUADROS] = new EEstadoPagina(7, trans('enums.cuadros'),'boxfade');
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