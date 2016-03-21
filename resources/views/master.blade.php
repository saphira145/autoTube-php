<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>

    <!-- Viewport mobile tag for sensible mobile support -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/styles/font-awesome.min.css">
    <link rel="stylesheet" href="/styles/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="/styles/jquery-ui.css">
    <!--STYLES-->
    <link rel="stylesheet" href="/styles/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/styles/lightbox.min.css">
    <link rel="stylesheet" href="/styles/style.css">
    <!--STYLES END-->

    <script src="/js/dependencies/jquery.min.js"></script>
    <!--<script src="/js/dependencies/jquery.dataTables.min.js"></script>-->
    <script type="text/javascript" src="https://cdn.datatables.net/t/dt/dt-1.10.11/datatables.min.js"></script>
    <script src="/js/dependencies/bootstrap.min.js"></script>
    <script src="/js/dependencies/dataTables.bootstrap.min.js"></script>
    <script src="/js/dependencies/jquery-ui.min.js"></script>

    <script src="/js/dependencies/moment.min.js"></script>
    <script src="/js/dependencies/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/dependencies/mustache.min.js"></script>
    <script src="/js/dependencies/videoLightning.js"></script>
    <script src="/js/dependencies/jquery.ui.widget.js"></script>
    <script src="/js/dependencies/jquery.iframe-transport.js"></script>
    <script src="/js/dependencies/jquery.fileupload.js"></script>
    <script src="/js/dependencies/jquery.form.min.js"></script>
   
</head>

<body>
    <!-- Static navbar -->
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">MENU</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Video</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>    
                <ul class="nav navbar-nav navbar-right">
                    @if (Session::has('user'))
                        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                                <span class="hidden-xs-only">{{Session::get('user')->email}}</span> 
                                <span class="thumb-small avatar inline">
                                    <!--<img src="/images/avatar.jpg" alt="Mika Sokeil" class="img-circle">-->
                                </span> <b class="caret hidden-xs-only"></b> </a> 
                            <ul class="dropdown-menu pull-right"> 
                                <li><a href="#">Settings</a></li> 
                                <li><a href="#">Profile</a></li> 
                                <li><a href="#"><span class="badge bg-danger pull-right">3</span>Notifications</a></li> 
                                <li class="divider"></li> 
                                <li><a href="docs.html">Help</a></li> <li><a href="/auth/logout">Logout</a></li> 
                            </ul> 
                        </li>
                    @else 
                    <li><a href="/auth"><i class="fa fa-sign-in"></i> Login</a></li> 
                    @endif
                </ul>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>
    
    <div class="container-fluid">
        <!-- Main component for a primary marketing message or call to action -->
        <div class="jumbotron">
            @yield('content')
        </div>
    </div>
    
    <script src="/js/video.js"></script>
</body>

</html>

<script type="text/javascript">

$(document).ready(function () {
    // Active main menu
    var url = window.location;
      $('.navbar-auto a').filter(function() {
          return this.href == url || url.href.indexOf(this.href) == 0;
      }).addClass('active').parent().addClass('active');
  });
</script>