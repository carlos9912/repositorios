<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use App\Enums\ESiNo;

use Illuminate\Support\Arr;



/**

 *

 * @tutorial Working Class

 * ;

 * @since {28/05/2018}

 */

class Menu extends Model

{



    /**

     *

     * @var string

     */

    protected $table = 'menus';



    /**

     *

     * @var string

     */

    protected $primaryKey = 'id_menu';



    /**

     *

     * @var boolean

     */

    public $timestamps = false;



    /**

     *

     * @var array

     */

    protected $fillable = [

        'nombre',// character varying(60) NOT NULL, -- Nombre

        'url',// character varying(120) NOT NULL, -- Ruta del menú

        'id_menu_padre',// integer, -- Id menu padre

        'class_icon',// character varying(60) DEFAULT NULL::character varying, -- Nombre de la clase

        'target',// character varying(15) NOT NULL DEFAULT '1'::character varying, -- ETarget(1: self; 2: _blank;)

        'orden',// smallint, -- Orden del menú

        'activo',// smallint NOT NULL DEFAULT (1)::smallint, -- ESiNo(1: Si; 2: No)

        'imagen',// smallint NOT NULL DEFAULT (2)::smallint, -- ESiNo(1: Si; 2: No)

        'id_usuario_registra',// integer NOT NULL, -- Id usuario registra

        'fecha_registro',// timestamp(0) without time zone NOT NULL, -- Fecha de registro

        'id_usuario_modifica',// integer, -- Id usuario modifica

        'fecha_modificacion',// timestamp(0) without time zone DEFAULT NULL::timestamp without time zone, -- Fecha de modificación

        'color',// character varying(255),

    ];



    protected $casts = [

        'activo' => 'boolean'

    ];



    /**

     *

     * @tutorial Method Description: obtiene el menu padre

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo

     */

    public function menuPadre()

    {

        return $this->belongsTo('App\Models\Menu', 'id_menu_padre');

    }



    /**

     *

     * @tutorial Method Description: obtiene los menus hijos

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\HasOne

     */

    public function menus()

    {

        return $this->hasMany('App\Models\Menu', 'id_menu_padre');

    }



    /**

     *

     * @tutorial Method Description: obtiene las paginas asociadas al menu

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\HasOne

     */

    public function paginas()

    {

        return $this->hasMany('App\Models\Pagina', 'id_menu');

    }



    /**

     *

     * @tutorial Method Description: obtiene los menus hijos

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\HasOne

     */

    public static function listMenusTotal()

    {

        $principal = ESiNo::index(ESiNo::Si)->getId();

        $listMenus = DB::table('menus AS men_p')

        ->join('menus AS men', 'men.id_menu_padre', '=', 'men_p.id_menu')

        ->leftjoin('paginas AS pag', function ($join) use ($principal) {

            $join->on('pag.id_menu', '=', 'men.id_menu')

                ->where('pag.principal', '=', $principal);

        })

        ->orderBy('men_p.id_menu')->orderBy('men.orden');

        return $listMenus->select('men_p.id_menu AS id_menu_padre', 'men_p.nombre AS menu_padre', 'pag.id_pagina', 'men.*')->get();

    }





    /**

     *

     * @tutorial Method Description: obtiene los menus hijos que no tienen mas hijos

     * @author Bayron Tarazona ~ bayronthz@gmail.com

     * @since {11/10/2018}

     * @return \Illuminate\Database\Eloquent\Relations\HasOne

     */

    public static function listMenusHijos(){

        $listMenus = Menu::listMenusTotal();

        $listaHijos = [];

        foreach ($listMenus as $key => $menu) {

            if($menu->activo==ESiNo::index(ESiNo::Si)->getId()){

                $listaHijos[$menu->id_menu_padre][$menu->id_menu] = $menu;

            }

        }

        $listMenus = [];

        foreach ($listaHijos[1] as $menuNv1){

            if (Arr::exists($listaHijos,$menuNv1->id_menu)){

                foreach ($listaHijos[$menuNv1->id_menu] as $menuNv2){

                    if(Arr::exists($listaHijos,$menuNv2->id_menu)){

                        foreach ($listaHijos[$menuNv2->id_menu] as $menuNv3){

                            $listMenus[$menuNv3->id_menu]=$menuNv3->nombre;

                        }

                    }else{

                        $listMenus[$menuNv2->id_menu]=$menuNv2->nombre;

                    }

                }

            }else{

                $listMenus[$menuNv1->id_menu]=$menuNv1->nombre;

            }

        }

        return $listMenus;

    }

}

