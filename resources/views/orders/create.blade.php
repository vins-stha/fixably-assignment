@extends('.layout/layout-main')
@section('page_title', 'Add Order')

@section('container')
    <h1>Order Management</h1>
    <div class="row">
        <div class="col-lg-12 card-body">
            <div class="card-title">
                <h3 class="text-center title-2">Create Order</h3>
            </div>
            <hr>
            @if(session()->has('message'))
                <div class="sufee-alert alert with-close alert-success alert-dismissible ">
                    <span class=""> {{session('message')}}</span>
                </div>
            @endif

            <form action="{{route('createOrders')}}" method="post">
                <div class="form-group">
                    @csrf
                    <label for="cc-brand" class="control-label mb-1">Manufacturer</label>
                    <input name="manufacturer" type="text" class="form-control" aria-required="true" aria-invalid="false">

                </div>

                <div class="form-group">
                    <label for="cc-brand" class="control-label mb-1">Device Type </label>
                    <input name="device" type="text" class="form-control" aria-required="false" aria-invalid="false">

                </div>

                <div class="form-group">
                    <label for="cc-brand" class="control-label mb-1">Device Brand </label>
                    <input name="brand" type="text" class="form-control" aria-required="false" aria-invalid="false">

                </div>
                <div class="form-group">
                    <label for="cc-brand" class="control-label mb-1">Description </label>
                    <textarea name="description" rows ="7" cols="30" type="text" class="form-control" aria-required="false" aria-invalid="false">
                    </textarea>
                </div>

                <div>
                    <button id="payment-button" type="submit" class="btn btn-lg btn-info btn-block">
                        <span id="payment-button-amount">Create Order</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
