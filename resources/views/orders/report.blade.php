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
                                <th>Week</th>
                                <th>Total Invoices</th>
                                <th>Total invoiced amount</th>
                                <th>Change (%)</th>

                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $diff = 0;
                                $count = 2;
                            @endphp
                            @foreach($datas as $data)
                                @php
                                    if($count > 2 && $count < 5)
                                        $diff = $datas[$count - 1]['totalSum'] - $datas[$count]['totalSum'];
                                        $pc = ($diff /  $datas[$count - 1]['totalSum']) * 100;
                                        $pc = number_format($pc, 1, '.', '');

                                @endphp
                                <tr>
                                    <td>{{$data['week']}}</td>
                                    <td>{{$data['count']}}</td>
                                    <td>{{$data['totalSum']}}</td>

                                    <td>{{$pc}} %</td>
                                </tr>
                                @php
                                $count++;
                                @endphp
                            @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>

    </div>


@endsection
