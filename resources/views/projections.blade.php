<!DOCTYPE html>
<html>
    <head>
        <title>Fantasy Bowl: WestCoastFFL Projections</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand">West Coast FFL</a>
                </div>
            </div>
        </nav>
        <div class="container" style="margin-top: 20px;">
            <div class="content">
                <div class="jumbotron">
                    <h1>Projected Points</h1>
                    <p>Points pulled from Fantasy Sharks daily</p>
                </div>
                <div class="row">
                    @foreach($teams as $teamName => $team)
                        <div class="team col-md-6" style="height: 300px;">
                            <h3>{{ $teamName }}</h3>
                            @foreach($team as $playerName => $points)
                                {{ $playerName }} -  {{ is_numeric($points) ? $points : 0 }} <br>
                            @endforeach
                            <h4>Projected Total: {{ array_sum($team) }}</h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </body>
</html>
