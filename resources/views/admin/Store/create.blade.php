<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($store) ? route('admin.store.update', ['store' => $store->id]) : route('admin.store.store') }}"
        method="POST" id="storeForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($store))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name" class="text-uppercase">Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="name" id="name" maxlength="100"
                        min="5" value="{{ $store->name ?? '' }}" />
                    <span class="text-danger errors" id="nameerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="mobile" class="text-uppercase">Mobile No : <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" name="mobile" id="mobile" maxlength="12"
                        min="10" value="{{ $store->mobile ?? '' }}" />
                    <span class="text-danger errors" id="mobileerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="address">Address: <span class="text-danger">*</span></label>
                    <textarea id="address" name="address" class="form-control" rows="4" placeholder="Enter your address here"
                        maxlength="255">{{ $store->address ?? '' }}</textarea>
                    <span class="text-danger errors" id="addresserror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="city">City : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="city" id="city" maxlength="255"
                        value="{{ $store->city ?? '' }}" />
                    <span class="text-danger errors" id="cityerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="latitude">Latitude : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="latitude" id="latitude" maxlength="255"
                        value="{{ $store->latitude ?? '' }}" />
                    <span class="text-danger errors" id="latitudeerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="longitude">longitude : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="longitude" id="longitude" maxlength="255"
                        value="{{ $store->longitude ?? '' }}" />
                    <span class="text-danger errors" id="longitudeerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sales_fk">Sales Executive Name: <span class="text-danger">*</span></label>
                    <select id="sales_fk" name="sales_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($sales as $sale)
                            <option value="{{ $sale->id }}"
                                {{ isset($store) && $store->sales_fk == $sale->id ? 'selected' : '' }}>
                                {{ $sale->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="sales_fkerror"></span>
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
<script></script>
<!--end::scripts End-->
