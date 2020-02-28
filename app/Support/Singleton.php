<?php
namespace app\Support;

/**
 *
 * @tutorial Working Class
 * @author Bayron Tarazona ~bayronthz@gmail.com
 * @since 15/04/2018
 */
abstract class Singleton
{

    /**
     *
     * @var array
     */
    private static $_instances = array();

    /**
     *
     * @tutorial Method Description: constructor class
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {15/04/2018}
     * @throws \Exception
     */
    public function __construct()
    {
        if (array_key_exists(get_called_class(), self::$_instances)) {
            throw new \Exception('Already one instance of ' . get_called_class());
        }
        static::initialize();
    }

    /**
     *
     * @tutorial Method Description: load instancias
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {15/04/2018}
     * @return multitype:
     */
    final public static function instance()
    {
        $class = get_called_class();
        if (! array_key_exists($class, self::$_instances)) {
            self::$_instances[$class] = new $class();
        }
        return self::$_instances[$class];
    }

    /**
     *
     * @tutorial Method Description: clona una instancia
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {15/04/2018}
     * @throws \Exception
     */
    final public function __clone()
    {
        throw new \Exception('Class type ' . get_called_class() . ' cannot be cloned');
    }
}