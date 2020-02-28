<?php



namespace App\Http\Controllers\Auth;







use Illuminate\Foundation\Auth\AuthenticatesUsers;



use Illuminate\Support\Facades\Session;



use Illuminate\Support\Facades\Auth;



use App\Http\Controllers\Controller;



use Illuminate\Http\Request;



use App\Models\Menu;



use App\Models\Acceso;



use App\Enums\ESiNo;



use App\Enums\EPerfiles;



use App\Enums\ECargoColegio;



use App\Models\Empleado;



use Carbon\Carbon;







/**



 *



 * @tutorial Working Class



 * @author Bayron Tarazona ~bayronthz@gmail.com



 * @since 17/03/2018



 */



class LoginController extends Controller



{



    /*



     * |--------------------------------------------------------------------------



     * | Login Controller



     * |--------------------------------------------------------------------------



     * |



     * | This controller handles authenticating users for the application and



     * | redirecting them to your home screen. The controller uses a trait



     * | to conveniently provide its functionality to your applications.



     * |



     */



    use AuthenticatesUsers;







    /**



     *



     * @tutorial Where to redirect users after login.



     * @var string



     */



    protected $redirectTo = '/home';







    /**



     *



     * @tutorial Method Description: Create a new controller instance.



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {17/03/2018}



     * @return void



     */



    public function __construct()



    {



        //dd(bcrypt('secret'));



        $this->middleware('guest')->except('logout');



    }







    /**



     * The user has been authenticated.



     *



     * @param \Illuminate\Http\Request $request



     * @param mixed $user



     * @return mixed



     * @throws \Illuminate\Validation\ValidationException



     */



    protected function authenticated(Request $request, $user)



    {



        

        $empleado = Empleado::find(\Auth::user()->codempleado);
        if(blank($empleado)||\Auth::user()->perfil==2){
        
            
        }else{
            $sucursal = $empleado->sucursal()->first();
            if(blank($sucursal)){
                $this->guard()->logout();
                $request->session()->invalidate();
                Session::flash('message', 'You have been logged out!');
                $notificacion = ["message"=>'Sesion finalizada con exito', "type" => 'success'];
                return redirect('/empleado-error')->with($notificacion);
            }else if($sucursal->bloqueo == 1){
                $this->guard()->logout();
                $request->session()->invalidate();
                Session::flash('message', 'You have been logged out!');
                $notificacion = ["message"=>'Sesion finalizada con exito', "type" => 'success'];
                return redirect('/sucursal-bloqueo')->with($notificacion);
            }else if($sucursal->tipo_sucursal == 2){
                $this->guard()->logout();
                $request->session()->invalidate();
                Session::flash('message', 'You have been logged out!');
                $notificacion = ["message"=>'Sesion finalizada con exito', "type" => 'success'];
                return redirect('/logout')->with($notificacion);
            }
            session(['sucursal' => $sucursal]);
        }
        if(\Auth::user()->bloqueo==1){
            $this->guard()->logout();
            $request->session()->invalidate();
            Session::flash('message', 'You have been logged out!');
            $notificacion = ["message"=>'Sesion finalizada con exito', "type" => 'success'];
            return redirect('/empleado-bloqueo')->with($notificacion);
        }

        //se valida si el usuario esta activo
       
        //$listPerfiles = $user->perfiles;
        //Se captura la informacion del usuario que ingresa en la plataforma 
        $dataAcceso = [
            'navegador' => $request->header('User-Agent'),
            'id_usuario' => $user->id_usuario,
            'direccion_ip' => request()->ip()
        ];  
        //Se guarda en la base de dato el acceso a la plataforma
        $acceso = new Acceso($dataAcceso);
        $acceso->save();

        

        



    }



    







    /**



     * Validate the user login request.



     *



     * @param \Illuminate\Http\Request $request



     * @return void



     */



    protected function validateLogin(Request $request)



    {



        $this->validate($request, [



            $this->username() => 'required|string',



            'password' => 'required|string'



        ], [



            $this->username() . '.required' => __('auth.username_obligatorio')



        ]);



    }







    /**



     *



     * @tutorial Method Description: Get the login username to be used by the controller.



     * @author Bayron Tarazona ~bayronthz@gmail.com



     * @since {17/03/2018}



     * @return string



     */



    public function username()



    {



        return 'username';



    }











    /**



     * Log the user out of the application.



     *



     * @param  \Illuminate\Http\Request  $request



     * @return \Illuminate\Http\Response



     */



    public function logout(Request $request)



    {



        $this->guard()->logout();







        $request->session()->invalidate();



        Session::flash('message', 'You have been logged out!');



        $notificacion = ["message"=>'Sesion finalizada con exito', "type" => 'success'];



        return redirect('/')->with($notificacion);



    }



}



