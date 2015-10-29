@extends('master')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="content">
            <div class="jumbotron text-center" style="background-color:white;">
                <h1>Free Agents</h1>
                <p>Week 8</p>
            </div>
            <div class="row">
                <div class="team col-md-6 col-md-offset-3">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th class="text-right">Projected Points</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($freeAgents as $freeAgent)
                            <tr>
                                <td>{{ $freeAgent['position'] }}</td>
                                <td>{{ $freeAgent['name'] }}</td>
                                <td class="text-right">{{ is_numeric($freeAgent['points']) ? $freeAgent['points'] : 0 }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

