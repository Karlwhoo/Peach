<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    {{-- Metarial Icon --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0"/>

    {{-- fontawsome link --}}
    <link rel="stylesheet" href="{{asset('css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/regular.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/solid.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/brands.min.css')}}">

    
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    
    <!-- Latest compiled JavaScript -->
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>



    {{-- Bootsrap icon list --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">

    {{-- custom css --}}
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}">
    <link href="/css/main.css" rel="stylesheet" media="all">
    
  
    {{-- data table --}}
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
   
    <!-- Sweet Alert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vendor CSS-->
    <link href="/vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="/vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('hotel1.ico') }}" type="image/x-icon">

    <!-- In the head section -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Add Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light py-2">
             <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">                           
               
                <li class="nav-item">
                   
                    <div class="navbar-search-block">
                        <form class="form-inline">
                        <div class="input-group input-group-lg">
                            <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                            
                            </div>
                        </div>
                        </form>
                    </div>
                </li>
               <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        @if(Auth::user()->Photo)
                            <img src="{{ asset('storage/uploads/'.Auth::user()->Photo) }}" class="user-image img-circle elevation-2" alt="User Photo" style="width: 35px; height: 35px; object-fit: cover;">
                        @else
                            <img src="{{asset('img/profile.png')}}" class="user-image img-circle elevation-2" alt="User Photo" style="width: 35px; height: 35px; object-fit: cover;">
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right mt-3" style="border-radius: 0.5rem">
                        <li class="text-center py-1">
                            <a href="profile/show" class="text-navy">
                                <i class="fa-solid fa-user mr-3 text-navy"></i>Profile
                            </a>
                        </li>
                        <li class="text-center py-1 pb-3">
                            <a href="#" class="text-navy" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket mr-3 text-navy"></i>LogOut
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div> 


        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.sidebar')
        
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <section class="content">
                @yield('content')
            </section>
        </div>

        <!-- Main Footer -->
       <!--  <footer class="main-footer custom__main__footer">
            <div class="float-right d-none d-sm-block">
                <b>Hand-crafted & Made with  </b>
                <i class="bi bi-heart text-danger fs-5"></i>
            </div>
            <strong>Copyright &copy; Creative Software Tim</strong> All rights
            Reserved.
        </footer> -->
    </div>
   
    <script src="{{ mix('js/app.js') }}" defer></script>
    {{-- custom js --}}
    <script src="{{ URL::asset('/js/custom.js') }}"></script>
    <!-- Vendor JS-->
    <script src="/vendor/select2/select2.min.js"></script>
    <script src="/vendor/datepicker/moment.min.js"></script>
    <script src="/vendor/datepicker/daterangepicker.js"></script>
    <!-- Main JS-->
    <script src="/js/global.js"></script>

    <!-- Add Bootstrap 5 JS just before closing body tag -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <!-- Place these scripts just before the closing body tag -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    <!-- Your custom scripts -->
    @stack('scripts')

    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Fix Bootstrap modal issues
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });
    </script>

    <!-- Before closing body tag -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/custom-js/paymentSetting.js') }}"></script>
</body>
</html>
