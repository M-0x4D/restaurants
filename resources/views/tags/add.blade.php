@extends('admin.layouts.main')

@section('content')

<div class="container">
    <div class="row">

  <div class="mt-2 card">
    <div class="card-header d-flex justify-content-between">
       <div class="header-title">
          <h4 class="card-title">Create New Tag</h4>
       </div>
    </div>
    <div class="card-body">
       <form action="{{ route('tags.store') }}" method="post" class="row g-3 needs-validation" novalidate>
          @csrf

          <div class="col-md-12">
            <div class="form-group">
                <label class="form-label" for="exampleFormControlSelect1">Select Restaurant</label>
                <select class="form-select @error('restaurant_id') is-invalid @enderror" name="restaurant_id" id="exampleFormControlSelect1">
                <option >Select the restaurant</option>
                @foreach ($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
                @endforeach
                </select>
                <div class="invalid-feedback">
                    Please provide a valid name.
                 </div>
                 @error('restaurant_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                 @enderror
            </div>
        </div>

           <div class="col-md-12">
               <div class="form-group">
                   <label class="form-label" for="exampleFormControlSelect1">Select Tags</label>
                   <select class="form-select @error('parent_id') is-invalid @enderror" name="parent_id" id="exampleFormControlSelect1">
                       <option selected value="">Select the Tags</option>
                       @foreach ($tag as $item)
                           <option value="{{ $item->id }}">{{ $item->name }}</option>
                       @endforeach
                   </select>
                   <div class="invalid-feedback">
                       Please provide a valid name.
                   </div>
                   @error('parent_id')
                   <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                   @enderror
               </div>
           </div>

          <div class="col-md-12">
             <label for="validationCustom01" class="form-label">name</label>
             <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="validationCustom01" required>
             <div class="invalid-feedback">
                Please provide a valid name.
             </div>
             @error('name')
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
</div>

@endsection
