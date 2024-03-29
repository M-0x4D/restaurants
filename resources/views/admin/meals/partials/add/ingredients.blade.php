<div class="ingredient-form">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">Name: Arabic</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="ingredients[{$counter}][ar][name]"
                    placeholder="Name: Arabic" />
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
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="ingredients[{$counter}][en][name]"
                    placeholder="Name: English" />
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
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="ingredients[{$counter}][fr][name]"
                    placeholder="Name: French" />
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
                <label class="form-label">image: *</label>
                <input type="file" class="form-control @error('main_image') is-invalid @enderror"
                    name="ingredients[{$counter}][main_image]" />
                @error('ingredients.*.main_image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

    </div>

</div>

<div class="ingredient-area"></div>

<!-- addIngredient()-->

<button class="btn btn-primary rounded" onclick="addIngredient();return false;">Add New Ingredient</button>
