<?php



    namespace App\Http\Controllers;
    use App\Models\Perfil;
    use App\Models\Pagina;
    use App\Enums\ESiNo;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Http\Request;
    use App\Notifications\Notificacion;
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Facades\DB;
    use Curl;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {

        

        if(\Auth::user()->email==1){



            return redirect()->route('productos.venta');



        }else{


            return view('home');



        }

;

        



    }







    



    /**



     *



     * @tutorial Method Description: Show the application dashboard.



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function transacciones()



    {


        $listCategorias = [];
        foreach(DB::table('categorias AS a')->where('estado','=',1)->get() as $categoria){
            $listCategorias[$categoria->id_categoria] = $categoria->nombre;
        }
        return view('transacciones',[
            'categorias' => $listCategorias
        ]);



    }
    
    
    public function transaccionesNew()



    {



        return view('transacciones2');



    }

    public function empleadoError()
    {
        return view('errores.empleado-sucursal');
    }







    /**



     *



     * @tutorial Method Description: Show the application dashboard.



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function galeriaWizard()



    {



        return view('principal.galeria-wizard');



    }











    /**



     *



     * @tutorial Method Description: Show the application dashboard.



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {15/03/2018}



     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory



     */



    public function galeriaPrincipal()



    {



        return view('principal.galeria-principal');



    }



    











    /**



     * @tutorial Method Description: Redirecciona la notificacion seleccionada a la accion correspondiente



     * ;



     * @since {17 sep. 2018}



     * @param unknown $noti



     * @return \Illuminate\Http\RedirectResponse



     */



    public function notificacion($noti){



        $user = \Auth::user();



        $notification = $user->notifications()->where('id',$noti)->first();



        if ($notification)



        {



            $notification->markAsRead();



            return redirect()->route($notification->data['route'], ([



                $notification->data['origen'] => $notification->data[$notification->data['origen']]



            ]));



            



        }



    }



}



