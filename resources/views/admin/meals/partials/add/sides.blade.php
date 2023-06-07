<div class="side-form">
    <div class="row">

<div class="col-md-4">
    <div class="form-group">
        <label class="form-label">Name: Arabic</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$counter}][ar][name]" placeholder="Name" />
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
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$counter}][en][name]" placeholder="Name" />
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
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="sides[{$counter}][fr][name]" placeholder="Name" />
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
        <input type="file" class="form-control @error('main_image') is-invalid @enderror" name="sides[{$counter}][main_image]" />
        @error('sides.*.main_image')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="col-md-4">
    <div class="form-group">
        <label class="form-label">price: *</label>
        <input type="text" class="form-control @error('price') is-invalid @enderror" name="sides[{$counter}][price]" placeholder="Price" />
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


