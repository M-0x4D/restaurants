@extends('admin.layouts.main')


@section('content')


<div class="card-transparent bg-transparent mb-0">
    <div class="card-header border-0  ">
       <div class="d-flex justify-content-center align-items-center">
            <h3>Week Hours</h3>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="btn btn-outline-primary"><a href="{{ route('week_hours.indextwo') }}">Show In Datatable</a></div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <div class="btn btn-outline-primary"><a href="{{ route('week_hours.create') }}">Add New Week Hours</a></div>
        </div>



       </div>
    </div>
    <div class="card-body">

        <div class="container">


            <div class="row">
{{-- @dd($week_hours) --}}
            @foreach ($week_hours as $week_hour)

            <div class="col-md-2" role="group" aria-label="{{ $loop->iteration }} / 8">
                <div class="card category-menu" data-iq-gsap="onStart" data-iq-opacity="0" data-iq-position-y="-40" data-iq-duration=".6" data-iq-delay=".6" data-iq-trigger="scroll" data-iq-ease="none" style="transform: translate(0px, 0px); opacity: 1;">
                   <div class="card-body">
                      <div class="text-center iq-menu-category">
                         <h6 class="heading-title fw-bolder pb-4">{{ @$week_hour->name }}</h6>
                         <p class="text-black">{{ @$week_hour->name }}</p>
                         <p class="text-black">{{ $week_hour->from }}</p>
                         <p class="text-black">{{ $week_hour->to }}</p>

                         <div class="category-icon pt-4">
                            <div class="btn btn-primary"><a class="text-white" href="{{ route('week_hours.edit', $week_hour->id) }}">Edit</a></div>
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
