<?php

namespace App\Support;



use Illuminate\Support\Arr;

use Illuminate\Support\Str;



/**

 *

 * @tutorial Method Description: clase de trabajo

 * @author Bayron Tarazona ~bayronthz@gmail.com

 * @since {20/07/2017}

 */

class Util extends Singleton

{



    /**

     *

     * @tutorial Method Description:

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     */

    protected static function initialize()

    {}



    /**

     *

     * @tutorial Method Description: formatea un numero

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param integer $number            

     * @param string $decimals            

     * @param string $search            

     * @param string $replace            

     * @return string

     */

    public static function formatNumber($number, $decimals = null, $search = ',', $replace = '.')

    {

        $number = ((int) $number <= 0) ? 0 : $number;

        return number_format($number, $decimals, $search, $replace);

    }



    /**

     *

     * @tutorial Method Description: valida si el valor esta nulo o vacio

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param unknown $var            

     * @return boolean

     */

    public static function isEmpty($var)

    {

        $empty = false;

        if (! Arr::accessible($var)) {

            $cadena = static::isNull($var) || Str::length(static::trim($var)) == 0 ? '' : $var;

            $cadena = static::trim($cadena);

            if (Str::length($cadena) < 1) {

                $empty = true;

            }

            unset($cadena);

        } else {

            $empty = (count($var) < 1);

        }

        unset($var);

        return $empty;

    }



    /**

     *

     * @tutorial Method Description: valida si el valor esta nulo

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param mixed $var            

     * @return boolean

     */

    public static function isNull($var)

    {

        return is_null($var);

    }



    /**

     *

     * @tutorial Method Description: elimina espacios

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param string $text            

     * @param string $charlist            

     * @return string

     */

    public static function trim($text, $charlist = null)

    {

        return trim($text, $charlist);

    }



    /**

     *

     * @tutorial Method Description: elimina string de cadena

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param unknown $str            

     * @param string $charlist            

     * @return string

     */

    public static function lTrim($str, $charlist = null)

    {

        return ltrim($str, $charlist);

    }



    /**

     *

     * @tutorial Method Description: valida si el archivo existe

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param unknown $filename            

     * @return boolean

     */

    public static function fileExists($filename)

    {

        $filename = Str::replaceWord('\\', '/', $filename);

        return file_exists($filename);

    }



    /**

     *

     * @tutorial Method Description: serializa object

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param mixed $value            

     * @return string

     */

    public static function serialize($value)

    {

        return serialize($value);

    }



    /**

     *

     * @tutorial Method Description: deserializa object

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param mixed $str            

     * @return mixed

     */

    public static function unserialize($str)

    {

        return unserialize($str);

    }

    public static function getMunicipios()
    {
        $municipiosSnr = [					
            '232'	 => 	'ACACIAS ',
            '150'	 => 	'AGUA DE DIOS ',
            '196'	 => 	'AGUACHICA ',
            '102'	 => 	'AGUADAS ',
            '3'	 => 	'AMALFI ',
            '351'	 => 	'AMBALEMA ',
            '4'	 => 	'ANDES ',
            '103'	 => 	'ANSERMA ',
            '8'	 => 	'APARTADO ',
            '292'	 => 	'APIA ',
            '410'	 => 	'ARAUCA ',
            '280'	 => 	'ARMENIA ',
            '352'	 => 	'ARMERO ',
            '242'	 => 	'BARBACOAS ',
            '302'	 => 	'BARICHARA ',
            '303'	 => 	'BARRANCABERMEJA ',
            '40'	 => 	'BARRANQUILLA ',
            '293'	 => 	'BELEN DE UMBRIA', 		
            ' 50C'	 => 	'BOGOTA ZONA CENTRO', 		
            ' 50N'	 => 	'BOGOTA ZONA NORTE ',		
            ' 50S'	 => 	'BOGOTA ZONA SUR ',
            '122'	 => 	'BOLIVAR (CAUCA) ',
            '300'	 => 	'BUCARAMANGA ',
            '372'	 => 	'BUENAVENTURA ',
            '373'	 => 	'BUGA ',
            '261'	 => 	'CACHIRA ',
            '354'	 => 	'CAJAMARCA ',
            '282'	 => 	'CALARCA ',
            '370'	 => 	'CALI ',
            '124'	 => 	'CALOTO ',
            '6'	 => 	'CAÑAS GORDAS ',
            '152'	 => 	'CAQUEZA ',
            '60'	 => 	'CARTAGENA ',
            '375'	 => 	'CARTAGO ',
            '15'	 => 	'CAUCASIA ',
            '143'	 => 	'CERETE ',
            '355'	 => 	'CHAPARRAL ',
            '306'	 => 	'CHARALA ',
            '192'	 => 	'CHIMICHAGUA ',
            '264'	 => 	'CHINACOTA ',
            '144'	 => 	'CHINU ',
            '72'	 => 	'CHIQUINQUIRA ',
            '154'	 => 	'CHOCONTA ',
            '222'	 => 	'CIENAGA ',
            '5'	 => 	'CIUDAD BOLIVAR (ANT) ',
            '308'	 => 	'CONCEPCION ',
            '161'	 => 	'CONTRATACION ',
            '266'	 => 	'CONVENCION ',
            '342'	 => 	'COROZAL ',
            '260'	 => 	'CUCUTA ',
            '7'	 => 	'DABEIBA ',
            '294'	 => 	'DOSQUEBRADAS ',
            '74'	 => 	'DUITAMA ',
            '224'	 => 	'EL BANCO ',
            '62'	 => 	'EL CARMEN ',
            '76'	 => 	'EL COCUY ',
            '357'	 => 	'ESPINAL ',
            '156'	 => 	'FACATATIVA ',
            '284'	 => 	'FILANDIA ',
            '420'	 => 	'FLORENCIA ',
            '10'	 => 	'FREDONIA ',
            '359'	 => 	'FRESNO ',
            '11'	 => 	'FRONTINO ',
            '225'	 => 	'FUNDACION ',
            '157'	 => 	'FUSAGASUGA ',
            '160'	 => 	'GACHETA ',
            '78'	 => 	'GARAGOA ',
            '202'	 => 	'GARZON ',
            '307'	 => 	'GIRARDOT ',
            '12'	 => 	'GIRARDOTA ',
            '162'	 => 	'GUADUAS ',
            '360'	 => 	'GUAMO ',
            '126'	 => 	'GUAPI ',
            '79'	 => 	'GUATEQUE ',
            '362'	 => 	'HONDA ',
            '350'	 => 	'IBAGUE ',
            '500'	 => 	'INIRIDA ',
            '244'	 => 	'IPIALES ',
            '184'	 => 	'ISTMINA ',
            '13'	 => 	'ITUANGO ',
            '14'	 => 	'JERICO ',
            '17'	 => 	'LA CEJA ',
            '246'	 => 	'LA CRUZ ',
            '106'	 => 	'LA DORADA ',
            '166'	 => 	'LA MESA ',
            '167'	 => 	'LA PALMA ',
            '204'	 => 	'LA PLATA ',
            '248'	 => 	'LA UNION ',
            '400'	 => 	'LETICIA ',
            '364'	 => 	'LIBANO ',
            '146'	 => 	'LORICA ',
            '64'	 => 	'MAGANGUE ',
            '212'	 => 	'MAICAO ',
            '312'	 => 	'MALAGA ',
            '100'	 => 	'MANIZALES ',
            '108'	 => 	'MANZANARES ',
            '18'	 => 	'MARINILLA', 		
            '01N'	 => 	'MEDELLIN NORTE ',
            '1'	 => 	'MEDELLIN SUR ',
            '366'	 => 	'MELGAR ',
            '82'	 => 	'MIRAFLORES ',
            '520'	 => 	'MITÚ ',
            '440'	 => 	'MOCOA ',
            '65'	 => 	'MOMPOS ',
            '83'	 => 	'MONIQUIRA ',
            '142'	 => 	'MONTELIBANO ',
            '140'	 => 	'MONTERIA ',
            '110'	 => 	'NEIRA ',
            '200'	 => 	'NEIVA ',
            '186'	 => 	'NUQUI ',
            '270'	 => 	'OCAÑA ',
            '86'	 => 	'OROCUE ',
            '170'	 => 	'PACHO ',
            '112'	 => 	'PACORA ',
            '378'	 => 	'PALMIRA ',
            '272'	 => 	'PAMPLONA ',
            '240'	 => 	'PASTO ',
            '128'	 => 	'PATIA ',
            '475'	 => 	'PAZ DE ARIPORO ',
            '114'	 => 	'PENSILVANIA ',
            '290'	 => 	'PEREIRA ',
            '314'	 => 	'PIEDECUESTA ',
            '206'	 => 	'PITALITO ',
            '226'	 => 	'PLATO ',
            '120'	 => 	'POPAYAN ',
            '315'	 => 	'PUENTE NACIONAL ',
            '442'	 => 	'PUERTO ASIS ',
            '19'	 => 	'PUERTO BERRIO ',
            '88'	 => 	'PUERTO BOYACA ',
            '540'	 => 	'PUERTO CARREÑO ',
            '234'	 => 	'PUERTO LOPEZ ',
            '130'	 => 	'PUERTO TEJADA ',
            '368'	 => 	'PURIFICACION ',
            '180'	 => 	'QUIBDO ',
            '90'	 => 	'RAMIRIQUI ',
            '210'	 => 	'RIOHACHA ',
            '20'	 => 	'RIONEGRO ',
            '115'	 => 	'RIOSUCIO ',
            '380'	 => 	'ROLDANILLO ',
            '45'	 => 	'SABANALARGA ',
            '148'	 => 	'SAHAGUN ',
            '118'	 => 	'SALAMINA ',
            '276'	 => 	'SALAZAR ',
            '250'	 => 	'SAMANIEGO ',
            '450'	 => 	'SAN ANDRES (Isla) ',
            '318'	 => 	'SAN ANDRES (STDR) ',
            '319'	 => 	'SAN GIL ',
            '480'	 => 	'SAN JOSE DEL GUAVIAR ',
            '214'	 => 	'SAN JUAN DEL CESAR ',
            '346'	 => 	'SAN MARCOS ',
            '236'	 => 	'SAN MARTIN ',
            '320'	 => 	'SAN VICENTE DE CHUCURI ',
            '425'	 => 	'SAN VICENTE DEL CAGUAN ',
            '23'	 => 	'SANTA BARBARA ',
            '24'	 => 	'SANTA FE DE ANTIOQUI ',
            '80'	 => 	'SANTA MARTA ',
            '296'	 => 	'SANTA ROSA DE CABAL ',
            '25'	 => 	'SANTA ROSA DE OSOS ',
            '92'	 => 	'SANTA ROSA DE VITERBO ',
            '132'	 => 	'SANTANDER DE QUILICHAO ',
            '26'	 => 	'SANTO DOMINGO ',
            '297'	 => 	'SANTUARIO ',
            '27'	 => 	'SEGOVIA ',
            '382'	 => 	'SEVILLA ',
            '441'	 => 	'SIBUNDOY ',
            '134'	 => 	'SILVIA ',
            '68'	 => 	'SIMITI ',
            '347'	 => 	'SINCE ',
            '340'	 => 	'SINCELEJO ',
            '228'	 => 	'SITIONU  VO ',
            '51'	 => 	'SOACHA ',
            '093'	 => 	'SOATA ',
            '94'	 => 	'SOCHA ',
            '321'	 => 	'SOCORRO ',
            '95'	 => 	'SOGAMOSO ',
            '41'	 => 	'SOLEDAD ',
            '28'	 => 	'SONSON ',
            '29'	 => 	'SOPETRAN ',
            '32'	 => 	'TAMESIS ',
            '33'	 => 	'TITIRIBI ',
            '384'	 => 	'TULUA ',
            '252'	 => 	'TUMACO ',
            '70'	 => 	'TUNJA ',
            '254'	 => 	'TUQUERRES ',
            '34'	 => 	'TURBO ',
            '172'	 => 	'UBATE ',
            '35'	 => 	'URRAO ',
            '190'	 => 	'VALLEDUPAR ',
            '324'	 => 	'VELEZ ',
            '230'	 => 	'VILLAVICENCIO ',
            '37'	 => 	'YARUMAL ',
            '38'	 => 	'YOLOMBO ',
            '470'	 => 	'YOPAL ',
            '326'	 => 	'ZAPATOCA ',
            '176'	 => 	'ZIPAQUIRA ',
            '470'	 => 	'YOPAL',
            '326'	 => 	'ZAPATOCA',
            '176'	 => 	'ZIPAQUIRA',
            ];
            return $municipiosSnr;
    }



    /**;

     *

     * @tutorial Method Description: retorna un select a parti de un enumerado

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param unknown $name            

     * @param unknown $value            

     * @param array $data            

     * @param array $options            

     * @param string $defaultOption            

     * @param string $defaultWord            

     * @return string

     */

    public static function selectEnum($name, $value, array $data, array $options = NULL, $defaultOption = true, $defaultWord = '')

    {

        $defaults = array(

            'name' => $name,

            'class' => 'form-control chosen-select'

        );

        if (! isset($options['id']) && isset($name)) {

            $defaults['id'] = $name;

        }

        if (Arr::accessible($options) && Arr::exists($options, 'disabled')) {

            if ($options['disabled'] == FALSE) {

                unset($options['disabled']);

            } else {

                $options['disabled'] = 'disabled';

            }

        }

        $select = '<select ' . static::setAttributes($options, $defaults) . ' >';

        if ($defaultOption) {

            $defaultWord = Util::isEmpty($defaultWord) ? trans('general.option_choose') : $defaultWord;

            $select .= '<option value="" selected>' . $defaultWord . '</option>';

        }

        foreach ($data as $d) {

            $select .= '<option value="' . $d->getId() . '" ' . ($value == $d->getId() ? 'selected="selected"' : '') . '>' . $d->getDescription() . '</option>';

        }

        $select .= '</select>';

        unset($data);

        unset($options);

        return $select;

    }



    /**

     *

     * @tutorial Method Description: valida los atributos

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param array $attributes            

     * @param array $defaults            

     * @return string

     */

    protected static function setAttributes($attributes, $defaults)

    {

        if (Arr::accessible($attributes)) {

            foreach ($defaults as $key => $val) {

                $var = Arr::exists($attributes, $key) && ! Arr::accessible($attributes[$key]) ? $attributes[$key] : NULL;

                if (! static::isEmpty($var)) {
;
                    $defaults[$key] = $attributes[$key];

                    unset($attributes[$key]);

                }

            }

            

            if (count($attributes) > 0) {

                $defaults = array_merge($defaults, $attributes);

            }

        }

        $att = '';

        foreach ($defaults as $key => $val) {

            if ($key == 'value') {

                $val = static::setValidateAttributes($val, $defaults['name']);

            }

            $att .= $key . '="' . $val . '" ';

        }

        unset($attributes);

        unset($defaults);

        return $att;

    }



    /**

     *

     * @tutorial Method Description: valida los atributos

     * @author Bayron Tarazona ~bayronthz@gmail.com

     * @since {15/04/2018}

     * @param string $str            

     * @param string $campo            

     * @return Ambigous <string, unknown, mixed>|string|mixed

     */

    protected static function setValidateAttributes($str = '', $campo = '')

    {

        static $campoValido = array();

        if (Arr::accessible($str)) {

            foreach ($str as $key => $val) {

                $str[$key] = static::setValidateAttributes($val);

            }

            return $str;

        }

        if ($str === '') {

            return '';

        }

        if (array_key_exists($campo, $campoValido) && ! empty($campoValido[$campo])) {

            return $str;

        }

        $str = htmlSpecialChars($str);

        $str = str_replace(array(

            "'",

            '"'

        ), array(

            "&#39;",

            "&quot;"

        ), $str);

        if ($campo != '') {

            $campoValido[$campo] = $campo;

        }

        unset($campoValido);

        unset($campo);

        return $str;
;
    }



    public static function arrayMap($callback, $array1)

    {

        return array_map($callback, $array1);

    }



    



    public static function rand($min = 0, $max = 0)

    {

        return rand($min, $max);

    }



    public static function time()

    {

        return microtime_float();

    }



    public static function uniqid($prefix = null, $more_entropy = null)

    {

        return uniqid($prefix, $more_entropy);

    }



    /**

     *

     * @tutorial Metodo Descripcion:

     * @author Eminson Mendoza || emimaster16@gmail.com

     * @since 20/11/2016

     * @return SessionDto

     */

    public static function userSessionDto()

    {

        return static::unserialize(Session::getData('sessionDto'));

    }



    public static function setUserSessionDto($sessionDto)

    {

        Session::setData('sessionDto', static::serialize($sessionDto));

    }



    public static function isObject($var)

    {

        return ! static::isNull($var) && is_object($var) && ! Arr::accessible($var);

    }



    public static function classExists($class_name, $autoload = true)

    {

        return class_exists($class_name, $autoload);

    }



    public static function isNumeric($var)

    {

        return is_numeric($var);

    }



    public static function round($val, $precision = null, $mode = null)

    {

        return round($val, $precision, $mode);

    }



    public static function year()

    {

        return date('Y');

    }



    public static function fecha($timestamp = NULL, $format = 'Y-m-d H:i:s')

    {

        if (! static::isNumeric($timestamp)) {

            $timestamp = static::fechaNumero($timestamp);

        }

        return date($format, $timestamp);

    }



    public static function fechaNumero($fecha)

    {

        return strtotime($fecha);

    }



    public static function fechaActual($horas = FALSE)

    {

        $horas = ($horas) ? ' H:i:s' : NULL;

        return date("Y-m-d$horas");

    }



    public static function mkTime($hour = NULL, $minute = NULL, $second = NULL, $month = NULL, $day = NULL, $year = NULL)

    {

        return mktime($hour, $minute, $second, $month, $day, $year);

    }



    /**

     *

     * @tutorial Method Description:

     * @author Rodolfo Perez Gomez -- pipo6280@gmail.com

     * @since {16/01/2017}

     * @param unknown $dateC            

     * @param unknown $options            

     * @return string

     */

    public static function sumarFecha($dateC, $options = array())

    {

        $defaultC = [

            'Y' => 0,

            'm' => 0,

            'd' => 0,

            'H' => 0,

            'i' => 0,

            's' => 0,

            'format' => 'Y-m-d H:i:s'

        ];

        $dateC = static::fecha(static::fechaNumero($dateC));

        $options = array_merge($defaultC, $options);

        list ($fechaC, $horaC) = Arr::explode($dateC, ' ');

        list ($anoC, $mesC, $diaC) = Arr::explode($fechaC, '-');

        list ($horaC, $minutosC, $segundosC) = Arr::explode($horaC, ':');

        

        $mktimeC = static::mkTime(($horaC + $options['H']), ($minutosC + $options['i']), ($segundosC + $options['s']), ($mesC + $options['m']), ($diaC + $options['d']), ($anoC + $options['Y']));

        return static::fecha($mktimeC, $options['format']);

    }



    public static function restarFecha($date, $options = array())

    {

        $defaultC = array(

            "A" => 0,

            "m" => 0,

            "d" => 0,

            "H" => 0,

            "i" => 0,

            "s" => 0,

            'format' => 'Y-m-d'

        );

        $options = array_merge($defaultC, $options);

        list ($fecha, $hora) = Arr::explode($date, ' ');

        list ($A, $m, $d) = Arr::explode($fecha, '-');

        list ($H, $i, $s) = Arr::explode($hora, ':');

        $mktime = static::mktime($H - $options['H'], $i - $options['i'], $s - $options['s'], $m - $options['m'], $d - $options['d'], $A - $options['A']);

        return static::fecha($mktime, $options['format']);

    }



    /**

     *

     * @tutorial Method Description: retorna una array de los meses del año

     * ;

     * @since {21/12/2015}

     * @return multitype:string

     */

    public static function meses()

    {

        return array(

            1 => 'Enero',

            2 => 'Febrero',

            3 => 'Marzo',

            4 => 'Abril',

            5 => 'Mayo',

            6 => 'Junio',

            7 => 'Julio',

            8 => 'Agosto',

            9 => 'Septiembre',

            10 => 'Octubre',

            11 => 'Noviembre',

            12 => 'Diciembre'

        );

    }



    public static function mes($mes)

    {

        $meses = static::meses();

        $numero = (int) $mes;

        return $meses[$numero];

    }



    /**

     *

     * @tutorial Method Description: metodo que trasforma la fecha string a datetime

     * @author Rodolfo Perez - pipo6280@gmail.com

     * @since {21/11/2015}

     * @param unknown $fecha            

     * @return Ambigous <unknown, \DateTime>

     */

    final public static function toDatetime($fecha)

    {

        $dateTime = $fecha;

        if ($fecha != NULL && ! ($fecha instanceof \DateTime)) {

            $dateTime = new \DateTime($fecha);

        }

        return $dateTime;

    }



    public static function formatDate($date, $format)

    {

        if ($date == '0000-00-00' or $date == '0000-00-00 00:00:00' or $date == '') {

            return '';

        } else {

            $date = static::fecha(static::fechaNumero($date));

            list ($fecha, $hora) = Arr::explode($date, ' ');

            list ($year, $month, $day) = Arr::explode($fecha, "-");

            switch ($format) {

                case EDateFormat::index(EDateFormat::MES_DIA_ANO)->getId():

                    list ($year, $month, $day) = Arr::explode($fecha, "-");

                    return static::mes($month) . " " . $day . " de " . $year;

                    break;

                case EDateFormat::index(EDateFormat::MES_DIA_ANO_HORA)->getId():

                    list ($year, $month, $day) = Arr::explode($fecha, "-");

                    $horaTxt = ($hora != '00:00:00' && $hora != '') ? ' Hora: ' . static::fecha(static::fechaNumero($date), 'h:i a') : '';

                    return static::mes($month) . " " . $day . " de " . $year . $horaTxt;

                    break;

                case EDateFormat::index(EDateFormat::MES_DIA_ANO_HORA_SLASH)->getId():

                    list ($year, $month, $day) = Arr::explode($fecha, "-");

                    return $day . "/" . $month . "/" . $year . ' ' . $hora;

                    break;

                case EDateFormat::index(EDateFormat::FORMAT_ABBREVIATED)->getId():

                    list ($year, $month, $day) = Arr::explode($fecha, "-");

                    return static::shortMonthName($month) . " " . $day . " de " . $year;

                    break;

                case EDateFormat::index(EDateFormat::SOLO_FECHA)->getId(): // retornar solo fecha

                    return $fecha;

                    break;

                case EDateFormat::index(EDateFormat::SOLO_ANO)->getId(): // retornar solo año

                    return $year;

                    break;

                case EDateFormat::index(EDateFormat::SOLO_MES)->getId(): // retornar solo mes

                    return static::mes($month);

                    break;

                case EDateFormat::index(EDateFormat::SOLO_DIA)->getId(): // retornar solo dia

                    return $day;

                    break;

                case EDateFormat::index(EDateFormat::DIA_MES_ANO_LETTER)->getId():

                    list ($year, $month, $day) = Arr::explode($fecha, "-");

                    return $day . " de " . static::mes($month) . " de " . $year;

                    break;

                case EDateFormat::index(EDateFormat::MES_DIA_ANO)->getId(): // Retorna fecha completa con dia de la semana

                    return static::dias(static::formatDay($fecha)) . ', ' . $day . " de " . static::mes($month) . " de " . $year;

                    break;

                case EDateFormat::index(EDateFormat::DIA_MES)->getId():

                    list ($year, $month, $day) = Arr::explode($fecha, "-");

                    return $day . " de " . static::mes($month);

                    break;

                case 11: // Retorna fecha completa con dia de la semana - abreviado

                    return static::dias(static::formatDay($fecha), false) . ', ' . $day . " " . static::mes($month) . " del " . $year;

                    break;

                case 12: // retornar fecha y hora

                    return $date;

                    break;

                case 13: // Solo hora

                    return $hora;

                    break;

                case 14:

                    list ($year, $month, $day) = Arr::explode($fecha, "-");

                    return $year . "-" . $month . "-" . $day;

                    break;

                case 15:

                    return static::shortMonthName($month) . " / " . $year;

                    break;

            }

        }

    }



    /**

     *

     * @tutorial Method Description:

     * ;

     * @since {21/12/2015}

     * @param \DateTime $fecha            

     * @param number $tipo            

     * @return mixed

     */

    public static function formatDay($fecha, $tipo = 0)

    {

        $date = Arr::explode($fecha, '-');

        return jddayofweek(cal_to_jd(CAL_GREGORIAN, $date[1], $date[2], $date[0]), $tipo);

    }



    /**

     *

     * @tutorial Method Description:

     * ;

     * @since {21/12/2015}

     * @param string $mes            

     * @return string

     */

    public static function shortMonthName($mes)

    {

        switch ($mes) {

            case 1:

                return 'Ene';

                break;

            case 2:

                return 'Feb';

                break;

            case 3:

                return 'Mar';

                break;

            case 4:

                return 'Abr';

                break;

            case 5:

                return 'May';

                break;

            case 6:

                return 'Jun';

                break;

            case 7:

                return 'Jul';

                break;

            case 8:

                return 'Ago';

                break;

            case 9:

                return 'Sep';

                break;

            case 10:

                return 'Oct';

                break;

            case 11:

                return 'Nov';

                break;

            case 12:

                return 'Dic';

                break;

        }

    }



    public static function dias($indice = '', $abr = false)

    {

        if ($abr) {

            $nombre = array(

                0 => 'Dom',

                1 => 'Lun',

                2 => 'Mar',

                3 => 'Mie',

                4 => 'Jue',

                5 => 'Vie',

                6 => 'Sab'

            );

        } else {

            $nombre = array(

                0 => 'Domingo',

                1 => 'Lunes',

                2 => 'Martes',

                3 => 'Miercoles',

                4 => 'Jueves',

                5 => 'Viernes',

                6 => 'Sabado'

            );

        }

        if ($indice === '') {

            return $nombre;

        } else {

            return $nombre[$indice];

        }

    }



    public static function horaAM()

    {

        $return = array();

        for ($i = 7; $i <= 12; $i ++) {

            $i = ($i < 10) ? '0' . $i : $i;

            for ($j = 0; $j < 60; $j += 15) {

                $hora = ($j == 0) ? ($i . ":" . '00') : ($i . ":" . $j);

                $listHora[] = $hora;

                if ($i == 12) {

                    break;

                }

            }

        }

        $return['horaReal'] = $listHora;

        $return['horaVer'] = $listHora;

        unset($i, $j, $hora, $listHora);

        return $return;

    }



    public static function horasPM()

    {

        $return = array();

        for ($i = 2; $i <= 7; $i ++) {

            $i = ($i < 10) ? '0' . $i : $i;

            for ($j = 0; $j < 60; $j += 15) {

                $hora = ($j == 0) ? $i . ":" . '00' : $i . ":" . $j;

                $listHora[] = $hora;

                $horaReal = ($j == 0) ? ($i + 12) . ":" . '00' : ($i + 12) . ":" . $j;

                $listHoraReal[] = $horaReal;

                if ($i == 7) {

                    break;

                }

            }

        }

        $return['horaReal'] = $listHoraReal;

        $return['horaVer'] = $listHora;

        unset($i, $j, $hora, $listHora, $horaReal, $listHoraReal);

        return $return;

    }



    public static function fechaCita($fecha, $hora)

    {

        return ($fecha . ' ' . $hora . ':00');

    }



    public static function comboAnos($anoInicial, $anoFinal)

    {

        $result = array();

        for ($i = $anoFinal; $i >= $anoInicial; $i --) {

            $obj = new EnumGeneric($i, $i);

            $result[] = $obj;

        }

        return $result;

    }



    public static function trimText($texto, $limite = 100)

    {

        $texto = trim($texto);

        $texto = strip_tags(nl2br($texto));

        $tamano = strlen($texto);

        $resultado = '';

        if ($tamano <= $limite) {

            return $texto;

        } else {

            $texto = substr($texto, 0, $limite);

            $palabras = explode(' ', $texto);

            $resultado = implode(' ', $palabras);

            $resultado .= '...';

        }

        return $resultado;

    }



    public static function cutText($cadena, $limite, $corte = " ", $pad = "...", $html = false)

    {

        if (strlen($cadena) <= $limite)

            return $cadena;

        if (false !== ($breakpoint = strpos($cadena, $corte, $limite))) {

            if ($breakpoint < strlen($cadena) - 1) {

                if ($html) {

                    $cadena = substr($cadena, 0, $breakpoint) . $pad;

                } else {

                    $cadena = substr(strip_tags(html_entity_decode(nl2br($cadena))), 0, $breakpoint) . $pad;

                }

            }

        }

        return $cadena;

    }

}