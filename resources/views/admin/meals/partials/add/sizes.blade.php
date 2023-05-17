<div class="size-form">

    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Name: Arabic</label>
                <input type="text" class="form-control @error('abbreviation') is-invalid @enderror" name="sizes[{$counter}][ar][name]" placeholder="Abbreviation" />
                <div class="invalid-feedback">
                    Please provide a valid abbreviation.
                 </div>
                 @error('abbreviation')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                 @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label">Name: English</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sizes[{$counter}][en][name]" placeholder="Name" />
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
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="sizes[{$counter}][fr][name]" placeholder="Name" />
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
                <label class="form-label">price: *</label>
                <input type="text" class="form-control @error('price') is-invalid @enderror" name="sizes[{$counter}][price]" placeholder="Price" />
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

<div class="size-area"></div>

<button class="btn btn-primary rounded" onclick="addSize();return false;">Add New Size</button>
