<?php
namespace App\Enums;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 15/04/2018
 */
class EAcceso extends AEnum
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
        static::$items[static::Si] = new EAcceso(1, trans('enums.acceso_total'));
        static::$items[static::No] = new EAcceso(2, trans('enums.sin_acceso'));
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
     * @tutorial Method Description: search for EAcceso index
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
     * @tutorial get result of the EAcceso values
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {20/09/2015}
     * @param string $search            
     * @return Ambigous <\app\enums\EAcceso, EAcceso>
     */
    public static function result($search)
    {
        if (blank(static::$items)) {
            static::values();
        }
        $result = new EAcceso(NULL, NULL);
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
     * @tutorial get data values EAcceso listed
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