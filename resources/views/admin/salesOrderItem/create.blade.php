<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($salesOrderItem) ? route('admin.salesOrderItem.update', ['salesOrderItem' => $salesOrderItem->id]) : route('admin.salesOrderItem.store') }}"
        method="POST" id="salesOrderItemForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($salesOrderItem))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="quantity">Quantity :<span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="quantity" id="quantity" maxlength="255"
                        value="{{ $salesOrderItem->quantity ?? '' }}" />
                    <span class="text-danger errors" id="quantityerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="price">Price : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="price" id="price" maxlength="255"
                        value="{{ $salesOrderItem->price ?? '' }}" />
                    <span class="text-danger errors" id="priceerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="total">Total : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="total" id="total" maxlength="255"
                        value="{{ $salesOrderItem->total ?? '' }}" />
                    <span class="text-danger errors" id="totalerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="gst">Gst :<span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="gst" id="gst" maxlength="255"
                        value="{{ $salesOrderItem->gst ?? '' }}" />
                    <span class="text-danger errors" id="gsterror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sales_order_fk">Sales Order Id: <span class="text-danger">*</span></label>
                    <select id="sales_order_fk" name="sales_order_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($salesorders as $salesorder)
                            <option value="{{ $salesorder->id }}"
                                {{ isset($salesOrderItem) && $salesOrderItem->sales_order_fk == $salesorder->id ? 'selected' : '' }}>
                                {{ $salesorder->id }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="sales_order_fkerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="product_fk">Product Name: <span class="text-danger">*</span></label>
                    <select id="product_fk" name="product_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ isset($salesOrderItem) && $salesOrderItem->product_fk == $product->id ? 'selected' : '' }}>
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
<script></script>
<!--end::scripts End-->
