<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/topbar.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.0/css/dataTables.dataTables.css" />
    <script src="https://kit.fontawesome.com/71f1c28685.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <title>@yield('title') - Emancipación Cristiana Afro</title>
    <meta name="description"
        content="Emancipación Cristiana Afro, palabra de vida para el pueblo de tez brillante. Biblia, sólo biblia. El dedo índice en la palabra de Dios.">
    <meta name="author" content="ECCA">
    <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body id="page-top">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <div class="row">
                    <div class="col">
                        @include('layouts.topbar')
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gren-800">@yield('pageheading')</h1>
                        @if (isset($showAddButton) && $showAddButton)
                            <button type="button" class="d-none d-md-inline-block btn btn-md btn-primary shadow"
                                data-toggle="modal" data-target="#addModal">
                                @yield('addbutton')
                            </button>
                        @endif
                    </div>
                    @if (isset($showModal) && $showModal)
                        <div class="modal fade" id="addModal" tabindex="-1" role="dialog"
                            aria-labelledby="addModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addModalLabel">Crear un nuevo registro</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="@yield('formaction')" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            @yield('modalFields')
                                            <button type="submit" style="float: right"
                                                class="btn btn-success mb-4">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="container">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>{{ \Session::get('success') }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="card mb-4">
                            <div class="py-3">
                                <h4 class="m-0 font-weight-bold text-success">@yield('mainheading')</h4>
                            </div>
                            <div class="card-body">
                                @yield('datatable')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield('datatablscr')
        @include('layouts.logout')
        @include('layouts.suggestions')

       

        {{-- <script src="vendor/jquery/jquery.min.js"></script> --}}
        <script src="{{ asset('js/topbar.js') }}"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>
        <script src="https://cdn.datatables.net/2.2.0/js/dataTables.js"></script>
        {{-- <script src="{{ asset('js/spanishdt.js') }}"></script> --}}
        <footer class="sticky-footer bg-white">
            @include('admin.footer')
        </footer>
</body>

</html>
