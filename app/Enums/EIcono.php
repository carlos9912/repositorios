<?php
namespace App\Enums;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 15/04/2018
 */
class EIcono extends AEnum
{

    /**
     *
     * @var array
     */
    protected static $items = array();

    const Si = 'N_1';

    const No = 'N_2';

    /**
     *
     * @tutorial initializes the values ​​listed
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {20/09/2015}
     * @return void
     */
    protected static function values()
    {
        static::$items[static::Si] = new EIcono(1, trans('enums.imagen'));
        static::$items[static::No] = new EIcono(2, trans('enums.icono'));
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
     * @tutorial Method Description: search for EIcono index
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
     * @tutorial get result of the EIcono values
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {20/09/2015}
     * @param string $search            
     * @return Ambigous <\app\enums\EIcono, EIcono>
     */
    public static function result($search)
    {
        if (blank(static::$items)) {
            static::values();
        }
        $result = new EIcono(NULL, NULL);
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
     * @tutorial get data values EIcono listed
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