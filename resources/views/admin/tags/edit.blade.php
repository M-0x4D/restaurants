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
    {{-- @dd($restaurants) --}}
    {{-- @dd($tag[0]->restaurant_id) --}}

    <div class="card-body">
       <form action="{{ route('tags.update', $tag[0]->tag_id) }}" method="post" class="row g-3 needs-validation" novalidate>
          @method('PUT')
          @csrf

          <div class="col-md-12">
            <div class="form-group">
                <label class="form-label" for="exampleFormControlSelect1">Select Restaurant</label>
                <select class="form-select @error('restaurant_id') is-invalid @enderror" name="restaurant_id" id="exampleFormControlSelect1">
                <option selected="" disabled="">Select the restaurant</option>
                @foreach ($restaurants as $restaurant)
                    {{-- <option value="{{ $restaurant->restaurant_id }}" @if($tag[0]->restaurant_id == $restaurant->restaurant_id) selected @endif>{{ $restaurant->name }}</option> --}}
                    @if ($tag[0]->restaurant_id == $restaurant->restaurant_id)
      <option value="{{ $restaurant->restaurant_id }}" selected>{{ $restaurant->name }}</option>
@else
      <option value="{{ $restaurant->restaurant_id  }}">{{ $restaurant->name }}</option>
@endif

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
             <label for="validationCustom01" class="form-label">Name Arabic</label>
             <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" value="{{ $tag[0]->name }}" id="validationCustom01" required>
             <div class="invalid-feedback">
                Please provide a valid name.
             </div>
             @error('name_ar')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
             @enderror
          </div>

          <div class="col-md-12">
            <label for="validationCustom01" class="form-label">Name English</label>
            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ $tag[1]->name }}" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid name.
            </div>
            @error('name_en')
               <span class="invalid-feedback" role="alert">
                   <strong>{{ $message }}</strong>
               </span>
            @enderror
         </div>

         <div class="col-md-12">
            <label for="validationCustom01" class="form-label">Name French</label>
            <input type="text" name="name_fr" class="form-control @error('name_fr') is-invalid @enderror" value="{{ $tag[2]->name }}" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid name.
            </div>
            @error('name_fr')
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
