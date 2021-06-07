@extends('layout.layout-main')
@section('page_title', 'Orders Management')


@section('container')
    <h1>Orders Management</h1>

    <div class="main-content section__content section__content--p30 container-fluid row">
        <div class="col-lg-12">
            <div class="card">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Type</th>
                                <th>Manufacturer</th>
                                <th>Brand</th>
                                <th>Technician</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                            $count = 0;
                            @endphp
                            @foreach($orders as $order)
                                <tr>
                                    <td><a href="{{url('orders')}}/{{$order['id']}}">{{$order['id']}}</a></td>
                                    <td>{{$order['deviceType']}}</td>
                                    <td>{{$order['deviceManufacturer']}}</td>
                                    <td>{{$order['deviceBrand']}}</td>
                                    <td>{{$order['technician']}}</td>
                                    <td>{{$order['status']}}</td>
                                </tr>
                            @endforeach
                            @php
                             $count++;
                            @endphp

                            </tbody>
                        </table>

                    </div>
                    @if ($total_pages > 1)
                        @if($startPage > 1)
                            <a style="margin-left: 10px" href="{{url('orders?page=')}}{{1}}">&laquo;</a>
                        @endif
                        @for($i = $startPage; $i <= $lastPage; $i++)
                            <div class="pagination">
                                    <span style="list-style: none; display: inline-block" class="actions">

                                        <a style="margin-left: 10px" href="{{url('orders?page=')}}{{$i}}">{{$i}}</a>
                                        @if($i >= $lastPage)
                                            <a style="margin-left: 10px" href="{{url('orders?page=')}}{{++$i}}">&raquo;</a>
                                        @endif
                                    </span>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>
        </div>

    </div>


@endsection
