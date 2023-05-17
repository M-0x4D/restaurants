@extends('admin.layouts.main')

@section('content')
    <div class="content-inner mt-5 py-0">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">

                    <form method="POST" action="{{ route('termsAndConditions.store') }}">
                        @csrf
                        <div class="feature-form">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Name Arabic </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="data[{$counter}][ar][name]" placeholder="Name Arabic" />
                                        <div class="invalid-feedback">
                                            Please provide a valid name.
                                        </div>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Name English</label>
                                        <input type="text" class="form-control @error('value') is-invalid @enderror"
                                            name="data[{$counter}][en][name]" placeholder="Name english" />
                                        <div class="invalid-feedback">
                                            Please provide a valid value.
                                        </div>
                                        @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Name French</label>
                                        <input type="text" class="form-control @error('value') is-invalid @enderror"
                                            name="data[{$counter}][fr][name]" placeholder="Name French" />
                                        <div class="invalid-feedback">
                                            Please provide a valid value.
                                        </div>
                                        @error('value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Description Arabic</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="data[{$counter}][ar][description]" placeholder="Description Arabic" />
                                            <div class="invalid-feedback">
                                                Please provide a valid name.
                                            </div>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Description English</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="data[{$counter}][en][description]"
                                                placeholder="Description English" />
                                            <div class="invalid-feedback">
                                                Please provide a valid name.
                                            </div>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Description French</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="data[{$counter}][fr][description]" placeholder="Description French" />
                                            <div class="invalid-feedback">
                                                Please provide a valid name.
                                            </div>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>


                            </div>

                        </div>


                        <div class="features-area"></div>

                        <button class="btn btn-primary rounded" onclick="addTerm();return false;">Add New
                            Term</button>
                        <div></div>

                        <button class="btn btn-primary rounded" type="submit">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection


    @section('additional_scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.1.3/axios.min.js"
            integrity="sha512-0qU9M9jfqPw6FKkPafM3gy2CBAvUWnYVOfNPDYKVuRTel1PrciTj+a9P3loJB+j0QmN2Y0JYQmkBBS8W+mbezg=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            var counter = 0;
            var formHTML = $('.feature-form').html()
            $('.feature-form').html(
                formHTML.replaceAll('{$counter}', 0)
            )

            function getSubTags(item) {
                axios.get('/list_subtags/' + item.value)
                    .then((data) => {
                        $('.tag_id').empty();
                        $('.tag_id').append('<option value="">Choose Sub Tag  ...</option>');
                        for (subtag of data.data) {
                            $('.tag_id').append('<option value="' + subtag.id + '">' + subtag.name + '</option>')
                        }
                    })
            }

            function addTerm() {
                counter += 1;
                $('.features-area').html(formHTML.replaceAll('{$counter}', counter))
            }

            function addIngredient() {
                featureForm = $('.ingredient-form').clone()

                $('.ingredient-area').html(featureForm)
            }

            function addSize() {
                featureForm = $('.size-form').clone()

                $('.size-area').html(featureForm)
            }

            function addOption() {
                featureForm = $('.option-form').clone()

                $('.options-area').html(featureForm)
            }

            function addDrink() {
                featureForm = $('.drink-form').clone()

                $('.drinks-area').html(featureForm)
            }

            function addSide() {
                featureForm = $('.side-form').clone()

                $('.sides-area').html(featureForm)
            }
        </script>
    @endsection
