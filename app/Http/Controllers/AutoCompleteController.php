<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Str;
use app\Support\Util;

class AutoCompleteController extends Controller
{

    public function index(Request $request)
    {
        if (blank($request->control)) {
            return response()->json([
                'items' => []
            ]);
        }
        $results = $this->{$request->control}($request->search);
        return response()->json([
            'items' => $results
        ]);
    }

    /**
     *
     * @tutorial Method Description: consulta los usuarios del sistema
     * @author Bayron Tarazona ~bayronthz@gmail.com
     * @since {30/04/2018}
     * @param string $search            
     * @return multitype:multitype:NULL
     */
    public function users($search = '')
    {
        $results = array();
        $query = DB::table('usuario')->join('persona', 'persona.id_persona', '=', 'usuario.id_persona')->select('usuario.*', 'persona.numero_identificacion', 'persona.foto_perfil', DB::raw("CONCAT(persona.primer_nombre, ' ', COALESCE(persona.segundo_nombre, ''), ' ', persona.primer_apellido, ' ', COALESCE(persona.segundo_apellido, '')) AS nombre_completo"));
        if (Util::isNumeric($search)) {
            $query->orWhere('persona.numero_identificacion', $search);
            $query->orWhere('persona.id_usuario', $search);
        } else {
            $query->orWhere('usuario.username', 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(persona.primer_apellido, ' ', COALESCE(persona.segundo_apellido, ''), ' ', persona.primer_nombre, ' ', COALESCE(persona.segundo_nombre, ''))"), 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(persona.primer_nombre, ' ', persona.primer_apellido)"), 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(persona.primer_nombre, ' ', COALESCE(persona.primer_apellido, ''), ' ', persona.segundo_apellido)"), 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(persona.primer_nombre, ' ', COALESCE(persona.segundo_nombre, ''), ' ', persona.primer_apellido, ' ', COALESCE(persona.segundo_apellido, ''))"), 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(persona.primer_apellido, ' ', persona.segundo_apellido)"), 'like', '%' . $search . '%')
                ->orWhere(DB::raw("CONCAT(persona.primer_apellido, ' ', persona.primer_nombre)"), 'like', '%' . $search . '%');
        }
        $data = $query->get();
        foreach ($data as $key => $lis) {
            $filename = asset('img/users/avatar.jpg');
            if (File::exists(public_path("img/users/{$lis->foto_perfil}"))) {
                $filename = asset("img/users/{$lis->foto_perfil}");
            }
            $results[] = [
                'description' => $lis->description,
                'full_name' => $lis->fullName,
                'avatar_url' => $filename,
                'text' => $lis->username,
                'id' => $lis->id,
                
                'stargazers_count' => 6416,
                'watchers_count' => 6464,
                'forks_count' => '60',
                'width' => 60
            ];
        }
        return $results;
    }
}
