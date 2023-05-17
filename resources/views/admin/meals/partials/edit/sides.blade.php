{{-- @foreach ($meal->sides as $side)

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">Name: Arabic</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$side}][ar][name]" value="{{ $side->name }}" placeholder="Name" />
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
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">Name: English</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$side}][en][name]" value="{{ $side->name }}" placeholder="Name" />
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
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">Name: French</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$side}][fr][name]" value="{{ $side->name }}" placeholder="Name" />
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
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">image: *</label>
                <input type="file" class="form-control @error('main_image') is-invalid @enderror" name="sides[{$side}][main_image]" />
                <img src="{{ $side->img_path }}" width="100" style="margin: 20px;" alt="" srcset="">
                @error('main_image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="form-label">price: *</label>
                <input type="text" class="form-control @error('price') is-invalid @enderror" name="sides[{$side}][price]" value="{{ $side->price }}" placeholder="Price" />
                <div class="invalid-feedback">
                    Please provide a valid value.
                </div>
                @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-3 mt-5 text-center">
            <div class="btn btn-success rounded"><a href="{{ route('sides.delete', $side->id) }}" class="text-white">Remove</a></div>
        </div>

    </div>

@endforeach --}}



<div class="side-form" style="width: 98% !important;">

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Name: Arabic</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$side}][ar][name]" placeholder="Name" />
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
                <label class="form-label">Name: English</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$side}][en][name]" placeholder="Name" />
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
                <label class="form-label">Name: French</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$side}][fr][name]" placeholder="Name" />
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
                <label class="form-label">image: *</label>
                <input type="file" class="form-control @error('main_image') is-invalid @enderror" name="sides[{$side}][main_image]" />
                @error('main_image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">price: *</label>
                <input type="text" class="form-control @error('price') is-invalid @enderror" name="sides[{$side}][price]" placeholder="Price" />
                <div class="invalid-feedback">
                    Please provide a valid value.
                </div>
                @error('price')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>

</div>

<div class="side-area"></div>

<button class="btn btn-primary rounded col-md-3" style="margin: 13px 36%;" onclick="addSide();return false;">Add New Side</button>
