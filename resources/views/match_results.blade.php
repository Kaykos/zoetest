@extends('layouts.app')

@section('content')
    <div class="subtitle m-b-md">
        Match results
    </div>
    <div>
        <table>
            <thead>
            <tr>
                <th>Agent ID</th>
                <th>Contact Name</th>
                <th>ZIP Code</th>
            </tr>
            </thead>
            <tbody>
            @foreach($results as $result)
                <tr>
                    <td>{{$result['agentId']}}</td>
                    <td>{{$result['contactName']}}</td>
                    <td>{{$result['zipcode']}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <br>
    <a href="/">Return</a>
@endsection
