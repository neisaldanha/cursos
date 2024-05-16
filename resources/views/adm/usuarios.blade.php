@extends('layout.adm') @section('title', 'Usuários')


@section('content')



<div id="main-wrapper">
    <div class="container-fluid">
        @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible bg-danger" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <strong>OPS!</strong> Ocorreu um erro ao Deletar.
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
            <strong>Sucesso!</strong> Usuário deletado com sucesso.
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
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading clearfix">
                    <div class="form-group col-10">
                        <a href="{{url('adm/register-user',0)}}" class="btn btn-success"><i class="fa fa-user-plus"> Usuário</i>
                        </a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="panel-body">
                            <div class="table table-responsive">
                                <table id="example" class="display  table" style="width: 100%; cellspacing: 0;">
                                    <thead>
                                        <tr>
                                            <th>Imagem</th>
                                            <th>Nome</th>
                                            <th>EMAIL</th>
                                            <th>NIVEL</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <th>Imagem</th>
                                        <th>Nome</th>
                                        <th>EMAIL</th>
                                        <th>NIVEL</th>
                                        <th>Ações</th>
                                    </tfoot>
                                    <tbody>
                                        @foreach($users as $use)
                                        <tr>
                                            <th>
                                                <img src=" @if ($use->foto) {{asset('/storage/'.$use->foto)}}  @else {{asset('plugin/imagens/facisb_logo.png')}} @endif" width="64px">
                                            </th>
                                            <th>{{$use->usu_login}}</th>
                                            <td>{{$use->email}}</td>
                                            <td>@if ($use->usu_nivel == "A") Administrador @elseif($use->usu_nivel == "C") Comum @else Supervisor @endif </td>
                                            <td>
                                                <a href="{{url('adm/ver/user',$use->id)}}" title="Editar" value="" class="btn btn-primary btn-xs"><i class="fa icon-pencil"></i> </a>
                                                <a href="#" title="Visualizar" class="btn btn-info btn-xs" data-toggle="modal" data-target="#ver-{{$use->id}}"><i class="fa fa-camera"></i></a>
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#editar-{{$use->id}}">
                                                    <i class="fa  fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Modal nunca deve ficar dentro da <tr> pq não aparece no modo mobile e trava a tela -->
                                        <div class="modal fade" id="editar-{{$use->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Você vai excluir <strong> {{$use->usu_login}}</strong> ?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="{{url('adm/user/delete',$use->id)}}" title="Excluir">
                                                            <button type="button" class="btn btn-danger">Sim</button>
                                                        </a>
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Visualizar usuário -->
                                        <div class="modal fade" id="ver-{{$use->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h3 class="modal-title" id="exampleModalLongTitle" align="center"><strong> {{$use->usu_login}}</strong> </h3>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Tipo de Acesso: <strong><ins>@if($use->usu_nivel == 'A') Administrador @elseif($use->usu_nivel == "C") Comum @elseif($use->usu_nivel == "S") Supervisor @endif</ins></strong><br>
                                                        E-mail: <strong><ins>{{$use->email}}</ins></strong><br>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- Row -->
</div><!-- Main Wrapper -->


@stop
@section('css')
@stop
@section('js')

@stop
