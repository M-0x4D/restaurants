@extends('admin.layouts.main')

@section('header')
    <style>
        .form-card {
            width: 90%;
            margin-left: 5%;
        }

        @media only screen and (max-width: 1200px) {
            .form-card {
                width: 100%;
                margin-left: 0 !important;
            }
        }
        </style>
@endsection

@section('content')
<div class="container">
    
    <div class="mt-2 card form-card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Create New Coupon</h4>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('coupons.store') }}" enctype="multipart/form-data" method="post"
            class="row g-3 needs-validation" novalidate>
            @csrf
            <div class="form-group">
                <label class="form-label" for="exampleFormControlSelect1">Select Restaurant</label>
                <select name="restaurant_id"  onchange="getSubTags(this)" class="form-select" id="exampleFormControlSelect1"
                    fdprocessedid="d740od">
                    <option selected="" disabled="">Select Restaurant</option>
                    @foreach ($restaurants as $restaurant)
                                <option value="{{ $restaurant->restaurant_id }}">{{ $restaurant->name }}</option>
                            @endforeach
                    
                </select>
                <div class="invalid-feedback">
                    Please provide a valid name.
                </div>
            </div>
                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Code</label>
                        <input type="text" name="code" class="form-control @error('name') is-invalid @enderror"
                            id="validationCustom01" required>
                        <div class="invalid-feedback">
                            Please provide a valid name.
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Percentage</label>
                        <input type="text" name="discount_percentage" class="form-control @error('discount_percentage') is-invalid @enderror"
                            id="validationCustom01" required>
                        <div class="invalid-feedback">
                            Please provide a valid Percentage.
                        </div>
                        @error('discount_percentage')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Available Users</label>
                        <input type="text" name="available_users" class="form-control @error('available_users') is-invalid @enderror"
                            id="validationCustom01" required>
                        <div class="invalid-feedback">
                            Please provide a valid available users.
                        </div>
                        @error('available_users')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Used Count</label>
                        <input type="text" name="used_count" class="form-control @error('used_count') is-invalid @enderror"
                            id="validationCustom01" required>
                        <div class="invalid-feedback">
                            Please provide a valid Used Count.
                        </div>
                        @error('used_count')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">from date</label>
                        <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" id="validationCustom01" required>
                        <div class="invalid-feedback">
                           Please provide a valid from_date.
                        </div>
                        @error('start_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
            
                     <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">to date</label>
                        <input type="date" name="expire_date" class="form-control @error('expire_date') is-invalid @enderror" id="validationCustom01" required>
                        <div class="invalid-feedback">
                           Please provide a valid to_date.
                        </div>
                        @error('expire_date')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                     </div>
                    <div class="col-12">
                        <button class="btn btn-primary rounded" type="submit">Submit form</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection


@section('additional_scripts')
    <script>
        $('input[type="file"]').on('change', function(e) {
            $('#upload_' + $(this).attr('rand_key')).remove();
            var rand_key = (Math.random() + 1).toString(36).substring(7);
            $(this).attr('rand_key', rand_key);
            if (e.target.files.length) {
                $(this).attr('rand_key', rand_key);
                $('<div class="col-12 py-2 px-0" id="upload_' + rand_key + '"></div>').insertAfter(this);
                $.each(e.target.files, (key, value) => {
                    $('#upload_' + rand_key).append(
                        '<div class="row d-flex m-0   btn" style="border:1px solid rgb(136 136 136 / 17%);max-width: 100%;padding: 5px;width: 220px;background: rgb(142 142 142 / 6%);margin-bottom:10px!important"><div style="max-height: 35px;overflow: hidden;display:flex;flex-flow: nowrap;" class="p-0 align-items-center">\
                            <span class="d-inline-block font-small " style="line-height: 1.2;opacity: 0.7;border-radius: 12px;overflow:hidden;width:71px">\
                                <span class="fal fa-cloud-download p-2 font-2 me-2" style="background:rgb(129 129 129 / 24%);border-radius: 12px;"></span>\
                            </span>\
                            <span style="direction: ltr;position: relative;top: -5px;height: 24px;overflow: hidden;width: 186px;" class="d-inline-block naskh font-small"> ' +
                        value.name +
                        ' </span>\
                                <span class="d-inline-block font-small px-2" style="position: relative;font-weight: bold;"> ' + (Math
                            .round(value.size / 1000000 * 100) / 100).toFixed(2) + 'M </span>\
                            </div>\
                        </div>')
                });
            }
        });
    </script>
@endsection
