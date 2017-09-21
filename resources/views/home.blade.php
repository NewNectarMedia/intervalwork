@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form action="/topic" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-sm-10">
                                <input type="text" name="topic" id="topic" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2 text-right">
                                <button type="submit" class="btn btn-default">
                                    <i class="fa fa-plus"></i> Add Topic
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Display Validation Errors -->
            @if (count($errors) > 0)
            <!-- Form Error List -->
            <div class="alert alert-danger">
                <strong>Whoops! Something went wrong!</strong>

                <br><br>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p><a href='{{url('api/ical', [$user->slug])}}' target="_blank">iCal url: </a>{{url('api/ical', [$user->slug])}}</p>

                    @if (count($schedule) > 0)
                    <div class="panel-body">
                        <table class="table table-striped">

                            <!-- Table Headings -->
                            <thead>
                                <th>Review Date</th>
                                <th>Topic</th>
                            </thead>

                            <!-- Table Body -->
                            <tbody>
                                @foreach ($schedule as $item)
                                    <tr>
                                        <td class="table-text">
                                            <div>{{date('l F j, Y', strtotime($item->when))}}</div>
                                        </td>
                                        <td class="table-text">
                                            <div>{{$item->topic->name}}</div>
                                        </td>

                                        <td>
                                            <!-- TODO: Delete Button -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                </div>

            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Send your text messages to:
                </div>
                <div class="panel-body">
                    {{getenv('TWILIO_NUMBER')}}
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Authorized phone numbers
                </div>

                <div class="panel-body">
                    @if (count($user->phones) > 0)
                    <table class="table table-striped">

                        <!-- Table Body -->
                        <tbody>
                            @foreach($user->phones as $phone)
                                <tr>
                                    <!-- Task Name -->
                                    <td class="table-text">
                                        <div>{{$phone->phone}}</div>
                                    </td>
                                    <td class="table-text">
                                        <div>{{$phone->name}}</div>
                                    </td>

                                    <td class="text-right">
                                        <form action="/phone/{{ $phone->id }}" method="POST">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <button class="btn btn-link" style="padding: 0; border: 0px;    vertical-align: baseline;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                    <!-- New Task Form -->
                    <form action="/phone" method="POST" class="">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="phone" class="col-sm-12 control-label">Phone Number<br />(format should be +13038085555)</label>

                            <div class="col-sm-12">
                                <input type="text" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name" class="col-sm-12 control-label" style="margin-top:10px;">Alias</label>

                            <div class="col-sm-12">
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <button type="submit" class="btn btn-default" style="margin-top:10px;">
                                    <i class="fa fa-plus"></i> Add Phone
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
