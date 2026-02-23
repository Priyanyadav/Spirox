<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($inventory) ? route('admin.inventory.update', ['inventory' => $inventory->id]) : route('admin.inventory.store') }}"
        method="POST" id="inventoryForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($inventory))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="batch_no" class="text-uppercase">Batch No : <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" name="batch_no" id="batch_no"
                        value="{{ $inventory->batch_no ?? '' }}" />
                    <span class="text-danger errors" id="batch_noerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="quantity" class="text-uppercase">Quantity : <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" name="quantity" id="quantity" maxlength="12"
                        min="10" value="{{ $inventory->quantity ?? '' }}" />
                    <span class="text-danger errors" id="quantityerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="manufacture_date">Manufacture Date : <span class="text-danger">*</span></label>
                    <input type="date" class="form-control w-100" name="manufacture_date" id="manufacture_date"
                        value="{{ $inventory->manufacture_date ?? '' }}" />
                    <span class="text-danger errors" id="manufacture_dateerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="expiry_date">Expiry Date : <span class="text-danger">*</span></label>
                    <input type="date" class="form-control w-100" name="expiry_date" id="expiry_date"
                        value="{{ $inventory->expiry_date ?? '' }}" />
                    <span class="text-danger errors" id="expiry_dateerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="product_fk">Product Name: <span class="text-danger">*</span></label>
                    <select id="product_fk" name="product_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ isset($inventory) && $inventory->product_fk == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="product_fkerror"></span>
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
<script>
    $(document).ready(function() {
        $('#designation').select2();
        $('#language').select2();
    })
</script>
<!--end::scripts End-->
