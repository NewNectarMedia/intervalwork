@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>User actions:<br />
                    INITIAL SETUP | PREFERENCES</p>

                    <p>To do:</p>
                    <ul>
                    <li>Setup tables</li>
                    <li>Create models</li>
                    <li>Integrate Twilio</li>
                    <li>Create views</li>
                    <li>Integrate Google Calendar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
