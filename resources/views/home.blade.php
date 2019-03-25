@extends('layouts.app')

@section('content')
    <div class="title m-b-md">
        Agent matcher
    </div>

    <div>
        <form method="POST" action="/match">
            {{csrf_field()}}

            <label for="agent1_zip_code">Agent 1</label>
            <input name="agent1_zip_code" type="text" placeholder="ZIP Code" required>

            <label for="agent2_zip_code">Agent 2</label>
            <input name="agent2_zip_code" type="text" placeholder="ZIP Code" required>

            <button type="submit">Match</button>
        </form>
        <div class="error">
            @if ($errors->any())
                <p>{{$errors}}</p>
            @endif
        </div>
    </div>
    <div>
        <p>There are {{count($contacts)}} registered contacts</p>
    </div>
@endsection
