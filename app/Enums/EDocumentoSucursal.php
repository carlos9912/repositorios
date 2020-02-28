<?php

namespace App\Enums;

// Camara de comercio (inferior a 3 dias)
// Rut
// Cedula Representante
// Cedula Deudor
// Certificado Centrales de riesgo (Datacredito)
// Extractos bancarios (ultimos 3 meses)
// Certificado Libertad y tradicion (Deudor Solidario)
// Copia del contrato de arrendamiento local (si no es propio)
// Certificado Cuenta Bancaria Representante 
// Pagare
// Contrato de colaboracion empresarial
// Carta de instrucciones

/**

 *

 * @tutorial Working Class

 * @author Bayron Tarazona ~bayronthz@gmail.com

 * @since 15/04/2018

 */

class EDocumentoSucursal extends AEnum

{




    /**

     *

     * @var array

     */

    protected static $items = array();



    const CAMARA_COMERCIO = 'N_1';
    const RUT = 'N_2';
    const CEDULA_REPRESENTANTE = 'N_3';
    const CEDULA_DEUDOR = 'N_4';
    const CERTIFICADO_DATACREDITO = 'N_5';
    const EXTRACTOS = 'N_6';
    const CTL = 'N_7';
    const CONTRATO_ARRENDAMIENTO = 'N_8';
    const PAGARE = 'N_9';
    const CONTRATO = 'N_10';
    const CARTA_INSTRUCCIONES = 'N_11';




    /**

     *

     * @tutorial initializes the values ​​listed

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {20/09/2015}

     * @return void

     */

    protected static function values()

    {
        static::$items[static::CAMARA_COMERCIO] = new EIcono(1, 'Camara de comercio');
        static::$items[static::RUT] = new EIcono(2, 'Rut');
        static::$items[static::CEDULA_REPRESENTANTE] = new EIcono(3, 'Cedula Representante');
        static::$items[static::CEDULA_DEUDOR] = new EIcono(4, 'Cedula Deudor');
        static::$items[static::CERTIFICADO_DATACREDITO] = new EIcono(5, 'Certificado Datacredito');
        static::$items[static::EXTRACTOS] = new EIcono(6, 'Extractos');
        static::$items[static::CTL] = new EIcono(7, 'CTL');
        static::$items[static::CONTRATO_ARRENDAMIENTO] = new EIcono(8, 'Contrato Arrendamiento');
        static::$items[static::PAGARE] = new EIcono(9, 'Pagaré');
        static::$items[static::CONTRATO] = new EIcono(10, 'Contrato colaboración');
        static::$items[static::CARTA_INSTRUCCIONES] = new EIcono(11, 'Carta de instrucciones');
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