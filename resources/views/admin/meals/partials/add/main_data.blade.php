<div class="col-md-6">
    <div class="form-group">
        <label class="form-label" for="exampleFormControlSelect1">Select Tag</label>
        <select onchange="getSubTags(this.value)" class="form-select" id="exampleFormControlSelect1">
        <option selected="" disabled="">Select the tag</option>
        @foreach ($tags as $tag)
            <option value="{{ $tag->tag_id }}">{{ $tag->name }}</option>
        @endforeach
        </select>
        <div class="invalid-feedback">
            Please provide a valid name.
         </div>
         @error('tag_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
         @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label" for="exampleFormControlSelect1">Select Sub Tag</label>
        <select class="tag_id form-select @error('tag_id') is-invalid @enderror" name="tag_id" id="exampleFormControlSelect1">
        <option selected="" disabled="">Select the sub tag</option>
        </select>
        <div class="invalid-feedback">
            Please provide a valid name.
         </div>
         @error('tag_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
         @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Name: Arabic</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name_ar" placeholder="Name: Arabic" />
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
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Name: English</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name_en" placeholder="Name: English" />
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
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Name: French</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name_fr" placeholder="Name: French" />
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
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Description: Arabic</label>
        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description_ar" placeholder="Description: Arabic" />
        <div class="invalid-feedback">
            Please provide a valid name.
         </div>
        @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Description: English</label>
        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description_en" placeholder="Description: English" />
        <div class="invalid-feedback">
            Please provide a valid name.
         </div>
        @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">Description: English</label>
        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description_fr" placeholder="Description: English" />
        <div class="invalid-feedback">
            Please provide a valid name.
         </div>
        @error('description')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">price: *</label>
        <input type="text" class="form-control @error('price') is-invalid @enderror" name="price" placeholder="Price" />
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
<div class="col-md-6">
    <div class="form-group">
        <label class="form-label">image: *</label>
        <input type="file" class="form-control @error('main_image') is-invalid @enderror" name="main_image" />
        @error('main_image')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
