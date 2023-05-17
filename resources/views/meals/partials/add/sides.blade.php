<div class="side-form">
    <div class="row">

<div class="col-md-4">
    <div class="form-group">
        <label class="form-label">Name: *</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[name][]" placeholder="Name" />
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
        <input type="file" class="form-control @error('main_image') is-invalid @enderror" name="sides[main_image][]" />
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
        <input type="text" class="form-control @error('price') is-invalid @enderror" name="sides[price][]" placeholder="Price" />
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

<div class="sides-area"></div>

<button class="btn btn-primary rounded" onclick="addSide();return false;">Add New Side</button>

