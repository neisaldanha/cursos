@extends('layout.adm')

@section('title', 'Cadatro-Usuário')

@section('content_header')

@stop

@section('content')
<!-- Main content -->

<div id="main-wrapper">
    <div class="row">
        <form method="post" action="{{url('adm/store')}}" enctype="multipart/form-data">
            <div class="panel-heading clearfix">

            </div>
            <div class="col-md-4">
                <!-- Profile Image -->
                <div class="panel panel-white">
                    <div class="panel-body">
                        <div id="profile-container" class="text-center">
                            <a href="#"><img id="profileImage" class="profile-user-img img-fluid img-circle" src=" @if (isset($user->foto)) {{asset('/storage/'.$user->foto)}}  @else {{asset('/imagens/user_default.png')}} @endif" width="268" height="268" alt="User"></a>

                        </div>
                        <div class="text-center">
                            <span><i class="fa fa-pencil"> Clique na imagem para Edita-la</i></span>
                        </div>
                        <input id="imageUpload" type="file" style="display: none;" value="@if (isset($user->foto)) {{asset('/storage/'.$user->foto)}}  @else {{asset('/imagens/user_default.png')}} @endif" name="arquivo" placeholder="Photo" capture>
                        <h3 class="profile-username text-center">{{isset($user) ? $user->usu_login  :'NOVO NOME'}}</h3>
                        <p class="text-muted text-center">@if(isset($user)) @if($nivel == 'A') Administrador @elseif($nivel == "C") Comum @elseif($nivel == "S") Supervisor @endif @endif</p>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-8">
                <div class="panel panel-white">
                    <div class="panel-heading clearfix">
                        <h4 class="panel-title">{{isset($user) ? 'Editando '.$user->usu_login : 'Cadastrar Novo Usuário'}}</h4>
                    </div>
                    <div class="panel-body">

                        <div class="container-fluid">
                            @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible bg-danger" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>OPS!</strong> Ocorreu um erro ao salvar.
                                @if (count($errors) > 0)
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            @endif
                            @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible bg-success" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <strong>Sucesso!</strong> Registro salvo com sucesso.
                                @if (count($errors) > 0)
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            @endif

                            @csrf
                        </div>
                        <input type="hidden" hidden class="form-control" placeholder="id" id="id" value="{{isset($user) ? $user->id  : 0}}" name="id">
                        <div class="form-group row">
                            <div class="col-sm-8">
                                <label for="select1">Nome</label>
                                @if($logado->usu_nivel == 'A')
                                    <input type="text" value="{{isset($user) ? $user->usu_login  :''}}" class="form-control" id="name" name="name" placeholder="Nome Completo">
                                @else
                                    <input type="text" readonly value="{{isset($user) ? $user->usu_login  :''}}" class="form-control" id="name" name="name" placeholder="Nome Completo">
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <label for="select1">Email</label>
                                <input type="email" value="{{isset($user) ? $user->email :''}}" class="form-control" id="email" name="email" placeholder="seuemail@provedor.com">
                            </div><br>
                            @if($logado->usu_nivel != 'A')
                                <div hidden class="col-sm-6">
                            @elseif($logado->usu_nivel == 'A')
                            <div class="col-sm-6">
                                    <label for="select1">Tipo de acesso</label>
                                    <select class="form-control select2" name="tipo" id="selects">
                                        @if (isset($user) && $user->usu_nivel == "A")
                                        <option selected="true" value="{{$user->usu_nivel}}">Administrador</option>
                                        <option value="S">Supervisor</option>
                                        <option value="C">Comum</option>
                                        @elseif(isset($user) && $user->usu_nivel == "C")
                                        <option selected="true" value="{{$user->usu_nivel}}">Comum</option>
                                        <option value="A">Administrador</option>
                                        <option value="S">Supervisor</option>
                                        @elseif(isset($user) && $user->usu_nivel == "S")
                                        <option selected="true" value="{{$user->usu_nivel}}">Supervisor</option>
                                        <option value="A">Administrador</option>
                                        <option value="C">Comum</option>
                                        @else
                                        <option value="">Selecione</option>
                                        <option value="A">Administrador</option>
                                        <option value="S">Supervisor</option>
                                        <option value="C">Comum</option>
                                        @endif
                                    </select>
                                </div>
                            @endif
                            <div class="col-sm-6">
                                <label for="select1">Senha</label>
                                <input type="password" value="{{isset($user) ? $user->senha :''}}" class="form-control" id="password" name="password" placeholder="*********">
                            </div>
                        </div>
                        <div class="form-group">
                            <div  class=" col-sm-10">
                                <button type="submit" class="btn btn-danger">{{isset($user) ? 'Atualizar': 'Cadastar'}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div> <!-- /.row -->
</div>

@stop

@section('css')

@stop

@section('js')


@stop
