@extends('admin.layouts.main')

@section('content')

<div class="container">

  <div class="mt-2 card">
    <div class="card-header d-flex justify-content-between">
       <div class="header-title">
          <h4 class="card-title">Update An Offer</h4>
       </div>
    </div>
    <div class="card-body">
       <form action="{{ route('offers.update', $offer[0]->offer_id) }}" enctype="multipart/form-data" method="post" class="row g-3 needs-validation" novalidate>
          @method('PUT')
          @csrf
          {{-- @dd($categories) --}}

          <div class="col-md-12">
            <div class="form-group">
                <label class="form-label" for="exampleFormControlSelect1">Select Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" onclick="getRestaurants(this)" onchange="getRestaurants(this)" name="category_id" id="exampleFormControlSelect1">
                <option value="{{$theCategory->category_id}}" selected="" disabled="">{{$theCategory->name}}</option>
                @foreach ($categories as $category)
                    {{-- <option value="{{ $category->id }}" @if($category->id == $offer->category_id) selected @endif>{{ $category->name }}</option> --}}
                    @if ($category->id == $offer[0]->category_id)
      <option value="{{ $category->category_id }}" selected>{{ $category->name }}</option>
@else
      <option value="{{ $category->category_id  }}">{{ $category->name }}</option>
@endif
                @endforeach
                </select>
                <div class="invalid-feedback">
                    Please provide a valid name.
                    </div>
                    @error('category_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="exampleFormControlSelect1">Select Restaurant</label>
                <select class="restaurant_id form-select @error('restaurant_id') is-invalid @enderror" onclick="getMeals(this)" onchange="getMeals(this)" name="restaurant_id" id="exampleFormControlSelect1">

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


          <div class="col-md-6">
            <div class="form-group">
                <label class="form-label" for="exampleFormControlSelect1">Select Meal</label>
                <select class="meal_id form-select @error('meal_id') is-invalid @enderror" name="meal_id" id="meal_id">

                </select>
                <div class="invalid-feedback">
                    Please provide a valid name.
                 </div>
                 @error('meal_id')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                 @enderror
            </div>
          </div>

          <div class="col-md-12">
             <label for="validationCustom01" class="form-label">Name Arabic</label>
             <input type="text" name="name_ar" value="{{ $offer[0]->name }}" class="form-control @error('name_ar') is-invalid @enderror" id="validationCustom01" required>
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
            <input type="text" name="name_en" value="{{ $offer[1]->name }}" class="form-control @error('name_en') is-invalid @enderror" id="validationCustom01" required>
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
            <input type="text" name="name_fr" value="{{ $offer[2]->name }}" class="form-control @error('name_fr') is-invalid @enderror" id="validationCustom01" required>
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
            <label for="validationCustom01" class="form-label">Description Arabic</label>
            <input type="text" name="description_ar" value="{{ $offer[0]->description }}" class="form-control @error('description_ar') is-invalid @enderror" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid description.
            </div>
            @error('description_ar')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>


         <div class="col-md-12">
            <label for="validationCustom01" class="form-label">Description English</label>
            <input type="text" name="description_en" value="{{ $offer[1]->description }}" class="form-control @error('description_en') is-invalid @enderror" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid description.
            </div>
            @error('description_en')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>



         <div class="col-md-12">
            <label for="validationCustom01" class="form-label">Description French</label>
            <input type="text" name="description_fr" value="{{ $offer[2]->description }}" class="form-control @error('description_fr') is-invalid @enderror" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid description.
            </div>
            @error('description_fr')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>



         <div class="col-md-12">
            <label for="validationCustom01" class="form-label">percentage</label>
            <input type="number" name="percentage" value="{{ $offer[0]->percentage }}" class="form-control @error('percentage') is-invalid @enderror" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid percentage.
            </div>
            @error('percentage')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>
          <div class="col-md-12">
            <label class="form-label">image: *</label>
            <input type="file" class="form-control @error('main_image') is-invalid @enderror" name="main_image" />
            <img src="{{ $offer[0]->img_path }}" width="150" height="120" style="margin-top: 10px;margin-left: 50px;" alt="" srcset="">
            @error('main_image')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>
         <div class="col-md-12">
            <label for="validationCustom03" class="form-label">color</label>
            <input type="text" class="form-control @error('color') is-invalid @enderror" name="color" value="{{ $offer[0]->color }}" >
            <div class="invalid-feedback">
               Please provide a valid email.
            </div>
            @error('color')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>

         <div class="col-md-12">
            <label for="validationCustom01" class="form-label">from date</label>
            <input type="date" name="from_date" value="{{ $offer[0]->from_date }}" class="form-control @error('from_date') is-invalid @enderror" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid from_date.
            </div>
            @error('from_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
         </div>

         <div class="col-md-12">
            <label for="validationCustom01" class="form-label">to date</label>
            <input type="date" name="to_date" value="{{ $offer[0]->to_date }}" class="form-control @error('to_date') is-invalid @enderror" id="validationCustom01" required>
            <div class="invalid-feedback">
               Please provide a valid to_date.
            </div>
            @error('to_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

          <div class="col-12 mt-2">
             <button class="btn btn-primary rounded" type="submit">Submit form</button>
          </div>
       </form>
    </div>
 </div>

</div>

@endsection


@section('additional_scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js" integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>

        $(document).ready(function () {
                let itemtwo = {};
                let itemthree = {};
                itemtwo.value = '{!! $theCategory->category_id !!}';
                itemthree.value = '{!! $theRestaurant->restaurant_id !!}';
                getRestaurants(itemtwo);
                getMeals(itemthree);
        });


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

        function getRestaurants(item){
            axios.get('/admin/list_restaurants/'+item.value)
                .then((data) => {
                $('.restaurant_id').empty();
                $('.restaurant_id').append('<option value="{{$theRestaurant->restaurant_id}}">{{$theRestaurant->name}}</option>');
                for(restaurant of data.data){
                    if(restaurant.restaurant_id == {!! $theRestaurant->restaurant_id ?? 0 !!}){
                        $('.restaurant_id').append('<option value="'+restaurant.restaurant_id+'" selected>'+restaurant.name+'</option>')
                    }else{
                        $('.restaurant_id').append('<option value="'+restaurant.restaurant_id+'">'+restaurant.name+'</option>')
                    }
                }
            })
        }

        function getMeals(item){
            axios.get('/admin/list_meals/'+item.value)
                .then((data) => {
                $('.meal_id').empty();
                $('.meal_id').append('<option value="{{$theMeal->meal_id}}">{{$theMeal->name}}</option>');
                for(meal of data.data){
                    if(meal.meal_id == {!! $theMeal->meal_id ?? 0 !!}){
                        $('.meal_id').append('<option value="'+meal.meal_id+'" selected>'+meal.name+'</option>')
                    }else{
                        $('.meal_id').append('<option value="'+meal.meal_id+'">'+meal.name+'</option>')
                    }
                }
            })
        }

    </script>
@endsection
