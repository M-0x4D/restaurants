@extends('admin.layouts.main')

@section('content')


<div class="content-inner mt-5 py-0">
    <div class="row">
      <div class="col-sm-12">
         <div class="card">
            <div class="card-header d-flex justify-content-between pb-0 border-0 row">
                    <h4 class="card-title col-md-6">Addons</h4>
                    <div class="btn btn-outline-primary col-md-3"><a href="{{ route('addons.create') }}">Add New Addons</a></div>
                    <div class="btn btn-outline-primary col-md-3"><a href="{{ route('addons.index') }}">Show Blocks</a></div>
            </div>
            <div class="card-body">
               <div class="table-responsive">
                    {!! $dataTable->table() !!}
               </div>
            </div>
         </div>
      </div>
    </div>
</div>



@endsection


@section('additional_scripts')
    {!! $dataTable->scripts() !!}
@endsection
