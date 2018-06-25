<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MARKETPLACE::KIT</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token" ic-global-include="#csrf-token">

    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.0.0/cosmo/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">
    <link rel="stylesheet" href='https://cdnjs.cloudflare.com/ajax/libs/json-forms/1.6.3/css/brutusin-json-forms.min.css'/>

    <!-- Custom styles for this template -->
    <link href="/css/admin.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
	
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertify.js@1.0.12/dist/js/alertify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intercooler@1.2.1/dist/intercooler.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/tinymce@4.7.9/tinymce.min.js' type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/brutusin/json-forms@1.6.3/dist/js/brutusin-json-forms.min.js"></script>

<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function () {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
    })
    </script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top " style="padding: 0" >
  <a class="navbar-brand" href="{{ url('/panel') }}" style="height: 48px; padding:16px 25px 10px 15px; color: #fff; font-size: 16px; width: 210px; background: #1a2226; color: #fff;     font-size: 12px; color: #4b646f; background: #1a2226;">MARKETPLACE::KIT</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" target="_blank" href="{{ route('home') }}">Open website <i class="fa fa-external-link" aria-hidden="true"></i></a>
      </li>

    </ul>
    <ul class="navbar-nav  my-2 my-lg-0">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('panel.settings.index') }}">Settings</a>
      </li>
        <li class="nav-item"><a class="nav-link text-s" href="{{ url('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
        <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </ul>

  </div>
</nav>
    <div id="wrapper" class="toggled">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-menu">
                <li class="sidebar-brand">
                    <a href="#">
                        Start Bootstrap
                    </a>
                </li>

      <li class="{{ active(['*categories*']) }}">
    <a href="/panel/categories"><i class="fa fa-pencil-square-o"></i> Categories </a>
</li>

        <li class="{{ active(['*listings']) }}">
            <a href="/panel/listings"><i class="fa fa-tags"></i> Listings </a>
        </li>
        <li class="{{ active(['*users']) }}">
            <a href="/panel/users"><i class="fa fa-users"></i> Users </a>
        </li>
        <li class="{{ active(['*orders*']) }}">
            <a href="/panel/orders"><i class="fa fa-inbox"></i> Orders </a>
        </li>
        <li class="{{ active(['*pages*', '*menu*']) }}">
            <a href="/panel/pages"><i class="fa fa-file"></i> Content </a>
        </li>
        <li class="{{ active(['*addons*']) }}">
            <a href="/panel/addons"><i class="fa fa-puzzle-piece"></i> Addons </a>
        </li>
        <li class="{{ active(['*themes*']) }}">
            <a href="/panel/themes"><i class="fa fa-paint-brush"></i> Themes </a>
        </li>
        @if (setting('custom_homepage'))
        <li class="{{ active(['*home*']) }}">
            <a href="/panel/home"><i class="fa fa-home"></i> Homepage </a>
        </li>
        @endif
        <li class="{{ active(['*settings*', '*fields*', '*pricing-models*']) }}">
            <a href="/panel/settings"><i class="fa fa-cogs"></i> Settings </a>
        </li>

            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container" id="main">
				@yield('content')
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->


	<script src="https://cdn.jsdelivr.net/npm/alertify.js@1.0.12/dist/js/alertify.min.js"></script>

</body>

</html>
