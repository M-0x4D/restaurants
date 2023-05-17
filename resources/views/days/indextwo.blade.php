@extends('admin.layouts.main')


@section('content')


<div class="card-transparent bg-transparent mb-0">
    <div class="card-header border-0  ">
       <div class="d-flex justify-content-center align-items-center">
            <h3>Daya</h3>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="btn btn-outline-primary"><a href="{{ route('days.indextwo') }}">Show In Datatable</a></div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="btn btn-outline-primary"><a href="{{ route('days.create') }}">Add New Day</a></div>
        </div>



       </div>
    </div>
    <div class="card-body">

        <div class="container">


            <div class="row">

            @foreach ($days as $day)

            <div class="col-md-2" role="group" aria-label="{{ $loop->iteration }} / 8">
                <div class="card category-menu" data-iq-gsap="onStart" data-iq-opacity="0" data-iq-position-y="-40" data-iq-duration=".6" data-iq-delay=".6" data-iq-trigger="scroll" data-iq-ease="none" style="transform: translate(0px, 0px); opacity: 1;">
                   <div class="card-body">
                      <div class="text-center iq-menu-category">
                         <h6 class="heading-title fw-bolder pb-4">{{ $day->name }}</h6>
                         <div class="category-icon pt-4">
                            <div class="btn btn-primary"><a class="text-white" href="{{ route('days.edit', $day->id) }}">Edit</a></div>
                         </div>
                      </div>
                   </div>
                </div>
             </div>

             @endforeach

            </div>

        </div>

    </div>


 @endsection
