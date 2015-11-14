@extends('master')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="content">
            <div class="jumbotron text-center" style="background-color:white;">
                <h1>Team Points</h1>
                <p>Week 10</p>
            </div>
            <div class="row">
                @foreach($teams as $teamName => $team)
                    <div class="team col-md-4 col-md-offset-1" style="height: 700px;">
                        <h4 class="text-center" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $teamName }}</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th class="text-right">Projected Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($team as $playerName => $points)
                                    <tr>
                                        <td>{{ $playerName }}</td>
                                        <td class="text-right">{{ is_numeric($points) ? $points : 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

