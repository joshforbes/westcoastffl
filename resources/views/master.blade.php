<!DOCTYPE html>
<html>
    <head>
        <title>Fantasy Bowl: WestCoastFFL Projections</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">West Coast FFL</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="{{ isActiveRoute('index') }}"><a href="{{ route('index') }}">Projected</a></li>
                        <li class="{{ isActiveRoute('team') }}"><a href="{{ route('team') }}">Team</a></li>
                        <li class="{{ isActiveRoute('free-agent') }}"><a href="{{ route('free-agent') }}">Free Agent</a></li>
                        <li class="{{ isActiveRoute('dfs') }}"><a href="{{ route('dfs') }}">DFS</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </nav>
        @yield('content')
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </body>
</html>