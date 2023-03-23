
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Chart Of Account | Testing</title>
    <link rel="icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon"/>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    {{-- css select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
        #toast-container .toast-close-button{
            padding: 0.375rem 0.75rem !important;
            color: white !important;
            background-color: #dc3545 !important;
            font-size: 0.9rem !important;
            font-weight: 400 !important;
            line-height: 1.6 !important;
            opacity: 1 !important; 
        }
        #toast-container .toast-close-button:hover{
            filter:drop-shadow(.1rem .1rem .3rem #e23) !important;

        }
        ul > li .nav-link{
            color: aliceblue;
        }
    </style>
</head>