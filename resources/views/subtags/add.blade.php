@extends('admin.layouts.main')

@section('content')

<div class="container">
    <div class="row">


  <div class="mt-2 card">
    <div class="card-header d-flex justify-content-between">
       <div class="header-title">
          <h4 class="card-title">Create New SubTag</h4>
       </div>
    </div>
    <div class="card-body">
       <form action="{{ route('subtags.store', $tag->tag_id) }}" method="post" class="row g-3 needs-validation" novalidate>
          @csrf

          <div class="col-md-12">
             <label for="validationCustom01" class="form-label">Name Arabic</label>
             <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" id="validationCustom01" required>
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
            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" id="validationCustom01" required>
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
            <input type="text" name="name_fr" class="form-control @error('name_fr') is-invalid @enderror" id="validationCustom01" required>
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
