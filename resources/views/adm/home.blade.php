@extends('layout.adm') @section('title', 'Dashboard')

@section('content_header') @stop @section('content')


    <div class="">
        <div id="main-wrapper">
            <div class="page-title">
                <h3>Dashboard</h3>
                <div class="page-breadcrumb">
                    <ol class="breadcrumb">
                        <li><a href="{{url('adm/home')}}">Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel info-box panel-white">
                        <div class="panel-body">
                            <div class="info-box-stats">
                                <p class="counter">{{$qtdeCursos}}</p>
                                <span class="info-box-title">Total Cursos</span>
                            </div>
                            <div class="info-box-icon">
                                <i class="icon-check"></i>
                            </div>
                            <div class="info-box-progress">
                                <div class="progress progress-xs progress-squared bs-n">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- Row -->
        </div><!-- Main Wrapper -->
    </div><!-- Page Inner -->


        <!-- Novo
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
        <script>
          document.getElementById('btn').onclick = function() {
              window.print();
            };
        </script>




    @stop @section('css') @stop @section('js') @stop
