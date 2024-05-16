<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\users;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Session;
use Validator;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function login()
     {
 
         return view('auth.login');
     }
 
     function logout()
     {
         if (session()->has('LoggedUser')) {
             session()->pull('LoggedUser');
             return redirect('/auth/login');
         }
     }
 
     public function check(Request $request)
     {
 
         //Validate requests
         $request->validate([
             'email' => 'required',
             'password' => 'required|min:5|max:12'
         ]);
         // Verifica se o usuário passado no login, exite no banco de dados
         $userInfo = users::where('email', '=', $request->email)
             //->orWhere('CPF', '=', $request->email)
             ->where('usu_status', '=', 'A')
             ->first(); //toSql();
         $qtdUsers = DB::table('users')->where('usu_status', 'A')->count();
 
         if (!$userInfo) {
             return back()->with('fail', 'Opss...Você não está cadastrado!');
         } else {
 
             //check password
             if (Hash::check($request->password, $userInfo->usu_senha)) {
                 $request->session()->put('LoggedUser', $userInfo->id);
                 $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
                 $userLogado = users::where('id', '=', session('LoggedUser'))->first();
                 //dd($user->USU_LOGIN);
                 $nivel =  Arr::pluck($data, 'usu_nivel')[0];
                 return redirect('/adm/home')->with([
                     'logado' => $userLogado, //Usuário Logado
                     'qtdeUsers' => $qtdUsers, //Qtde de usuários ativos
                 ]);
             } else {
                 return back()->with('fail', 'Senha incorreta');
             }
         }
     }
 
     function dashboard()
     {
 
         $ano = date('Y');
         $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
         $qtdCursos = DB::table('cursos')->count();
         $userLogado = users::where('id', '=', session('LoggedUser'))->first();
         $qtdUsers = DB::table('users')->where('usu_status', 'A')->count();
         $id = Arr::pluck($data, 'id')[0];
         $nivel = Arr::pluck($data, 'usu_nivel')[0];
         $cursos = DB::table('cursos')->get();
         $foto = Arr::pluck($data, 'foto');
         return view('adm.home')->with([
             'data' => Arr::pluck($data, 'USU_LOGIN'),
             'iduser' => Arr::pluck($data, 'id'),
             'idpessoa' => Arr::pluck($data, 'ID_PESSOA'),
             'imagem' => $foto,
             'cursos' => $cursos,
             'logado' => $userLogado,
             'qtdeUsers' => $qtdUsers,
             'qtdeCursos'=>$qtdCursos,
         ]);
     }
 
     public function index()
     {
         $users = DB::table('users')
             ->where('usu_status', 'A')
             ->get();
 
         $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
         $foto = Arr::pluck($data, 'foto');
         $userLogado = users::where('id', '=', session('LoggedUser'))->first();
         $qtdUsers = DB::table('users')->where('usu_status', 'A')->count();
         //dd(($foto));
         $nivel =  Arr::pluck($data, 'usu_nivel')[0];
         if ($nivel != 'A') {
             //abort(403,"Acesso não autorizado");
             //session()->flash('error2', true);
             Session::flash('error2', true);
             return back()->with([
                 'qtdeUsers' => $qtdUsers,  // Qtde de usuários ativos
                 'logado' => $userLogado,   // Usuário Logado
                 'data' => Arr::pluck($data, 'usu_login'),
                 'iduser' => Arr::pluck($data, 'id'),
                 'imagem' => $foto,
                 'nivel' => $nivel,
 
             ]);
         } else {
 
             //dd($users);
             return view('adm/usuarios')->with([
                 'users' => $users, // todos os usuários
                 'qtdeUsers' => $qtdUsers, // Qtde Usuários ativos
                 'logado' => $userLogado, //usuário logado
                 'data' => Arr::pluck($data, 'usu_login'),
                 'iduser' => Arr::pluck($data, 'id'),
                 'imagem' => $foto,
                 'nivel' => $nivel,
             ]);
         }
         //'data'=>Arr::pluck($data,'nome')
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         //
     }
 
     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
        
        

         $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
         $userLogado = users::where('id', '=', session('LoggedUser'))->first();
         $qtdUsers = DB::table('users')->where('usu_status', 'A')->count();
         $id = Arr::pluck($data, 'id')[0];
         $foto = Arr::pluck($data, 'foto')[0];
         $nivel =  Arr::pluck($data, 'usu_nivel')[0];
        
         // Não deixa alterar se não for usuário Administrador
         /*
         if ($userLogado->USU_NIVEL != 'A' ) {
             //abort(403,"Acesso não autorizado");
             Session::flash('error2', true);
             return back()->with([
                 'logado'=> $userLogado,//Usuário logado',
                 'qtdeUsers'=>$qtdUsers, //Qtde Usuários ativos
                 'data' => Arr::pluck($data, 'USU_LOGIN'),
                 'iduser' => Arr::pluck($data, 'id'),
                 'imagem' => $foto,
                 'nivel' => $nivel,
 
             ]);
         } else {*/
         if ($request->id == 0) {

             $input = $request->all();
 
             $id = $request->pessoa;
             $rules = [
                 'name' => 'required',
                 'email' => 'required',
                 'password' => 'required|min:5|max:12',
                 'tipo' => 'required',
                 'arquivo' => 'required',
 
             ];
 
             $nomes = [
                 'name' => 'Nome',
                 'email' => 'E-mail',
                 'password' => 'Senha',
                 'tipo' => 'Tipo Acesso',
                 'arquivo' => 'Imagem',
             ];
 
             $messages = [];
 
             $validator = Validator::make($input, $rules, $messages);
             $validator->setAttributeNames($nomes);
 
             if ($validator->fails()) {
                 Session::flash('error', true);
                 return redirect()
                     ->back()
                     ->withErrors($validator)
                     ->withInput();
             }
 
             //Insert data into database
             $user = new users;
 
             $user->email = $request->email;
             $user->usu_nivel = $request->tipo;
             $user->usu_login = $request->name;
             $user->senha = $request->password;
             $user->usu_status = "A";
             $user->usu_data_cad = date('Y-m-d H:i:s');
             $user->usu_data_update = date('Y-m-d H:i:s');
 
             $dataArq = date('Y-m-d H:i:s');
 
             // Define o valor default para a variável que contém o nome da imagem
             $nameFile = null;
             //$arq = file_get_contents($_FILES['arquivo']['tmp_name']);
             // Verifica se informou o arquivo e se é válido
             if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
 
                 // Define um aleatório para o arquivo baseado no timestamps atual
                 $name = time();
 
                 // Recupera a extensão do arquivo
                 $extension = $request->arquivo->extension();
 
                 // Define finalmente o nome
                 $nameFile = "{$name}.{$extension}";
 
                 // Faz o upload:
                 $destino = public_path('storage');
                 //dd($destino);
                 $upload = $request->arquivo->move($destino, $nameFile);
                 $imagem = $request->arquivo;
                 $user->foto = $nameFile;
 
                 // Verifica se NÃO deu certo o upload (Redireciona de volta)
                 if (!$upload) {
                     return redirect()
                         ->back()
                         ->with('error', 'Falha ao fazer upload')
                         ->withInput();
                 }
             }
 
 
             $user->usu_senha = Hash::make($request->password);
             $save = $user->save();
             //$save2 = $pessoausuario->update();
             $userLogado = users::where('id', '=', session('LoggedUser'))->first();
             $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
             $nivel =  Arr::pluck($data, 'usu_nivel')[0];
             //$dpto = DB::table('tab_departamentos')->select('ID_DPTO','DESCRICAO')->get();
             if ($save) {
                 Session::flash('success', true);
                 return back()->with([
                     'logado' => $userLogado, //'Usuário logado',
                     'qtdeUsers' => $qtdUsers, // Qtde Usuários ativos
                     'data' => Arr::pluck($data, 'usu_login'),
                     'iduser' => Arr::pluck($data, 'id'),
                     'imagem' => $foto,
                     'nivel' => $nivel,
 
                 ]);
             } else {
                 Session::flash('error', true);
                 return back()->with('fail', 'Opss..., Algo deu errado');
             }
         } else {
 
             //Validate requests
             $input = $request->all();
             $id = $request->id;
             $user = users::findOrFail($id);
 
             $rules = [
                 'name' => 'required',
                 //'email'=>'required|EMAIL|unique:users',
                 'password' => 'required|min:5|max:12',
                 //'tipo' => 'required',
             ];
 
 
             $nomes = [
                 'name' => 'Nome',
                 'email' => 'email',
                 'password' => 'Senha',
                 //'tipo' => 'Tipo Acesso',
             ];
 
             $messages = [];
 
             $validator = Validator::make($input, $rules, $messages);
             $validator->setAttributeNames($nomes);
 
             if ($validator->fails()) {
                 Session::flash('error', true);
                 return redirect()
                     ->back()
                     ->withErrors($validator)
                     ->withInput();
             }
 
             if (!$request->tipo) {
                 $tipo = $user->usu_nivel;
             } else {
                 $tipo = $request->tipo;
             }
             // dd( $tipo);
 
             //Insert data into database
 
            
             $user->usu_nivel = $tipo;
             $user->usu_login = $request->name;
             $user->senha = $request->password;
             $user->email = $request->email;
             $user->usu_data_update = date('Y-m-d H:i:s');
 
             $dataArq = date('Y-m-d H:i:s');
 
             // Define o valor default para a variável que contém o nome da imagem
             $nameFile = null;
             //$arq = file_get_contents($_FILES['arquivo']['tmp_name']);
             // Verifica se informou o arquivo e se é válido
             if ($request->hasFile('arquivo') && $request->file('arquivo')->isValid()) {
 
                 // Define um aleatório para o arquivo baseado no timestamps atual
                 $name = time();
 
                 // Recupera a extensão do arquivo
                 $extension = $request->arquivo->extension();
 
                 // Define finalmente o nome
                 $nameFile = "{$name}.{$extension}";
 
                 // Faz o upload:
                 $destino = public_path('storage');
                 //dd($destino);
                 $upload = $request->arquivo->move($destino, $nameFile);
                 $imagem = $request->arquivo;
                 $user->foto = $nameFile;
                 if ($imagem) {
                     $user->foto = $nameFile;
                 } else {
                     $user->foto = $user->foto;
                 }
 
                 // Verifica se NÃO deu certo o upload (Redireciona de volta)
                 if (!$upload) {
                     return redirect()
                         ->back()
                         ->with('fail', 'Falha ao fazer upload');
                 }
             }
 
             $user->usu_senha = Hash::make($request->password);
             $save = $user->update();
             $userLogado = users::where('id', '=', session('LoggedUser'))->first();
             $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
             $nivel =  Arr::pluck($data, 'usu_nivel')[0];
             if ($save) {
                 Session::flash('success', true);
                 return back()->with([
                     'logado' => $userLogado, // 'Usuário logado',
                     'qtdeUsers' => $qtdUsers, // Qtde Usuários ativos
                     'data' => Arr::pluck($data, 'usu_login'),
                     'iduser' => Arr::pluck($data, 'id'),
                     'imagem' => $foto,
                     'nivel' => $nivel,
                     
                 ]);
             } else {
                 Session::flash('error', true);
                 return back()->with('fail', 'Opss..., Algo deu errado');
             }
         }
         //}
 
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
         $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
         $qtdUsers = DB::table('users')->where('usu_status', 'A')->count();
         $foto = Arr::pluck($data, 'foto');
         $userLogado = users::where('id', '=', session('LoggedUser'))->first();
         $nivel =  Arr::pluck($data, 'usu_nivel')[0];
 
 
         if ($id == 0) {
 
             $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
             $nivel =  Arr::pluck($data, 'usu_nivel')[0];
             //$user = users::findOrFail($id);
             return view('adm.register-user')->with([
                 'logado' => $userLogado, // Usuário logado
                 'qtdeUsers' => $qtdUsers, // Qtde Usuarios ativos
                 'data' => Arr::pluck($data, 'usu_login'),
                 'iduser' => Arr::pluck($data, 'id'),
                 'imagem' => Arr::pluck($data, 'FOTO'),
                 'nivel' => $nivel,
                 
             ]);
         } else {
 
             $user = users::findOrFail($id);
             //dd($user->USU_NIVEL);
             $userLogado = users::where('id', '=', session('LoggedUser'))->first();
             $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
             $nivel =  Arr::pluck($data, 'usu_nivel')[0];
              
             return view('adm.register-user')->with([
                 'logado' => $userLogado, //Usuário Logado
                 'qtdeUsers' => $qtdUsers, //Qtde Usuários ativos
                 'user' => $user, // todos usuários
                 'data' => Arr::pluck($data, 'usu_login'),
                 'iduser' => Arr::pluck($data, 'id'),
                 'imagem' => Arr::pluck($data, 'foto'),
                 'nivel' => $user->usu_nivel,
                 
             ]);
         }
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
         //
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request, $id)
     {
         //
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
         $qtdUsers = DB::table('users')->where('usu_status', 'A')->count();
         $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
         $userLogado = users::where('id', '=', session('LoggedUser'))->first();
 
         $nivel =  Arr::pluck($data, 'usu_nivel')[0];
         if ($nivel != 'A') {
             //abort(403,"Acesso não autorizado");
             Session::flash('error2', true);
             return back()->with([
                 'logado' => $userLogado, // Usuário Logado
                 'qtdeUsers' => $qtdUsers, // Qtde Usuários ativos
             ]);
         } else {
             $user = users::findOrFail($id);
             //exclui imagem da pasta
             /* $imagem = $user->avatar;
             Storage::disk('public')->delete($imagem);
             */
             $user->usu_status = 'I';
             $user->usu_data_update = date('Y-m-d H:i:s');
             $inativado = $user->update();
             $data = ['LoggedUserInfo' => users::where('id', '=', session('LoggedUser'))->first()];
             if ($inativado) {
                 Session::flash('success', true);
                 return back()->with([
                     'logado' => $userLogado, // Usuário Logado
                     'qtdeUsers' => $qtdUsers, // Qtde Usuários ativos
                 ]);
             } else {
                 Session::flash('error', true);
                 return back()->with('fail', 'Opss..., Algo deu errado');
             }
         }
     }
}
