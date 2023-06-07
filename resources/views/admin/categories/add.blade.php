@extends('admin.layouts.main')

@section('header')

<!--<link rel="stylesheet" media="screen" type="text/css" href="css/colorpicker.css" />-->

    <style>
        .form-card{
            width: 90%;
            margin-left: 5%;
        }

        @media only screen and (max-width: 1200px) {
            .form-card{
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
          <h4 class="card-title">Create New Category</h4>
       </div>
    </div>
    <div class="card-body">
       <form action="{{ route('categories.store') }}" enctype="multipart/form-data" method="post" class="row g-3 needs-validation" novalidate>
          @csrf
          <div class="col-md-12">
             <label for="validationCustom01" class="form-label">Name Ar</label>
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
             <label for="validationCustom01" class="form-label">Name En</label>
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
             <label for="validationCustom01" class="form-label">Name Fr</label>
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
          <div class="col-md-12">
            <label class="form-label">image: *</label>
            <input type="file" class="form-control @error('main_image') is-invalid @enderror" name="main_image" />
            @error('main_image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>
         <div class="col-md-12">
            <label for="validationCustom03" class="form-label">color</label>
            
            
             <div class="d-flex align-items-center">
                
              <input id="colorx" style="width: 2rem; height:2rem " type="color" id="favcolor" name="favcolor" value=""><br><br>
            <input type="text" class="form-control @error('color') is-invalid @enderror" name="color" value="">
                
            </div>

            <div class="invalid-feedback">
               Please provide a valid color.
            </div>
            @error('color')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>
         <div class="col-md-12">
            <label for="validationCustom03" class="form-label">Border color</label>
            
            <div class="d-flex align-items-center">
                
              <input id="bordercolorx" style="width: 2rem; height:2rem " type="color" id="favcolor" name="favcolor" value="#ff0000"><br><br>
            <input type="text" class="form-control @error('border_color') is-invalid @enderror" name="border_color" value="">
                
            </div>
            
            
            <div class="invalid-feedback">
               Please provide a valid Border Color.
            </div>
            @error('border_color')
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
        $('input[type="file"]').on('change',function(e){
            $('#upload_'+$(this).attr('rand_key')).remove();
            var rand_key = (Math.random() + 1).toString(36).substring(7);
            $(this).attr('rand_key',rand_key);
            if(e.target.files.length){
                $(this).attr('rand_key',rand_key);
                $('<div class="col-12 py-2 px-0" id="upload_'+rand_key+'"></div>').insertAfter(this);
                $.each(e.target.files,(key,value)=>{
                    $('#upload_'+rand_key).append('<div class="row d-flex m-0   btn" style="border:1px solid rgb(136 136 136 / 17%);max-width: 100%;padding: 5px;width: 220px;background: rgb(142 142 142 / 6%);margin-bottom:10px!important"><div style="max-height: 35px;overflow: hidden;display:flex;flex-flow: nowrap;" class="p-0 align-items-center">\
                        <span class="d-inline-block font-small " style="line-height: 1.2;opacity: 0.7;border-radius: 12px;overflow:hidden;width:71px">\
                            <span class="fal fa-cloud-download p-2 font-2 me-2" style="background:rgb(129 129 129 / 24%);border-radius: 12px;"></span>\
                        </span>\
                        <span style="direction: ltr;position: relative;top: -5px;height: 24px;overflow: hidden;width: 186px;" class="d-inline-block naskh font-small"> '+value.name+' </span>\
                            <span class="d-inline-block font-small px-2" style="position: relative;font-weight: bold;"> '+(Math.round(value.size/1000000 * 100) / 100).toFixed(2)+'M </span>\
                        </div>\
                    </div>')});
            }
        });
        
        
const colorElement = document.getElementById('colorx');
const  colorVal = document.getElementsByName('color')

// console.log(colorElement.value)

colorElement.addEventListener('click' , (val) => {
    // colorVal.value = val
// console.log(val.target.value)
})

    </script>
@endsection
