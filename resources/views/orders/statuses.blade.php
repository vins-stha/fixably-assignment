@extends('layout.layout-main')
@section('page_title', 'orders')


@section('container')
    <h1>Orders Dashboard</h1>


    <div class="main-content section__content section__content--p30 container-fluid row">
        <div class="col-lg-12">
            <div class="card">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>Total Orders</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($data as $status => $value)
                                <tr>
                                    <td>{{$status}}</td>
                                    <td>{{$value}}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>


@endsection
