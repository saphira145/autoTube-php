<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>

    <!-- Viewport mobile tag for sensible mobile support -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.11/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="/styles/jquery-ui.css">
    <!--STYLES-->
    <link rel="stylesheet" href="/styles/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" href="/styles/lightbox.min.css">
    <link rel="stylesheet" href="/styles/style.css">
    <!--STYLES END-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/dataTables.bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

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
                    <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> 
                            <span class="hidden-xs-only">Tran Binh </span> 
                            <span class="thumb-small avatar inline">
                                <!--<img src="/images/avatar.jpg" alt="Mika Sokeil" class="img-circle">-->
                            </span> <b class="caret hidden-xs-only"></b> </a> 
                        <ul class="dropdown-menu pull-right"> 
                            <li><a href="#">Settings</a></li> 
                            <li><a href="#">Profile</a></li> 
                            <li><a href="#"><span class="badge bg-danger pull-right">3</span>Notifications</a></li> 
                            <li class="divider"></li> 
                            <li><a href="docs.html">Help</a></li> <li><a href="signin.html">Logout</a></li> 
                        </ul> 
                    </li>
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