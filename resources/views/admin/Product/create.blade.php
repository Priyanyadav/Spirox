<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($product) ? route('admin.product.update', ['product' => $product->id]) : route('admin.product.store') }}"
        method="POST" id="productForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($product))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="image">Product Image : <span class="text-danger">*</span></label>

                    <div id="image-preview" style="display:flex; flex-wrap:wrap; gap:10px; cursor:pointer;">

                        @if (isset($product) && $product->image)
                            @php
                                $images = json_decode($product->image, true);
                            @endphp

                            @if (is_array($images))
                                @foreach ($images as $img)
                                    <img src="{{ $img }}"
                                        style="width:80px;height:80px;border-radius:8px;object-fit:cover;">
                                @endforeach
                            @endif
                        @else
                            <span style="color:#999;">Click to upload image</span>
                        @endif

                    </div>

                    <input type="file" class="form-control d-none" name="image[]" id="image" multiple>

                    <span class="text-danger errors" id="imageerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="category_fk">Category Name : <span class="text-danger">*</span></label>
                    <select id="category_fk" name="category_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($categorys as $category)
                            <option value="{{ $category->id }}"
                                {{ isset($product) && $product->category_fk == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="category_fkerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name" class="text-uppercase">Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="name" id="name" maxlength="100"
                        min="5" value="{{ $product->name ?? '' }}" />
                    <span class="text-danger errors" id="nameerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="weight">Weight : <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" name="weight" id="weight" maxlength="255"
                        value="{{ $product->weight ?? '' }}" />
                    <span class="text-danger errors" id="weighterror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="price">Price : <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" name="price" id="price" maxlength="255"
                        value="{{ $product->price ?? '' }}" />
                    <span class="text-danger errors" id="priceerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="variation_gram">Variation Gram: <span class="text-danger">*</span></label>
                    <select name="variation_gram" id="variation_gram" class="form-control w-100">
                        <option value="">--Select Option--</option>
                        <option value="100g"
                            @if (!empty($product)) {{ $product->variation_gram == '100g' ? 'selected' : '' }} @endif>
                            100g</option>
                        <option value="200g"
                            @if (!empty($product)) {{ $product->variation_gram == '200g' ? 'selected' : '' }} @endif>
                            200g</option>
                        <option value="500g"
                            @if (!empty($product)) {{ $product->variation_gram == '500g' ? 'selected' : '' }} @endif>
                            500g</option>
                        <option value="1kg"
                            @if (!empty($product)) {{ $product->variation_gram == '1kg' ? 'selected' : '' }} @endif>
                            1kg</option>
                    </select>
                    <span class="text-danger errors" id="variation_gramerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="gst">Gst : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="gst" id="gst" maxlength="255"
                        value="{{ $product->gst ?? '' }}" />
                    <span class="text-danger errors" id="gsterror"></span>
                </div>
            </div>
            <div class="col-md-12 text-right">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
</fieldset>
<!--end::form End-->

<!--begin::scripts start-->
<!--end::scripts End-->
