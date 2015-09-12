@extends('master')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="content">
            <div class="jumbotron text-center" style="background-color:white;">
                <h1>Draft Kings</h1>
                <p>Points updated from Fantasy Sharks twice daily</p>
            </div>
            <div class="row">
                <div class="team col-md-6 col-md-offset-3">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Salary</th>
                            <th class="text-right">Projected Points</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($players as $player)
                            <tr>
                                <td>{{ $player['position'] }}</td>
                                <td>{{ $player['name'] }}</td>
                                <td class="text-right">{{ is_numeric($player['salary']) ? $player['salary'] : 0 }}</td>
                                <td class="text-right">{{ is_numeric($player['points']) ? $player['points'] : 0 }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

