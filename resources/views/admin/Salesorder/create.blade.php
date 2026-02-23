<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($salesOrder) ? route('admin.salesOrder.update', ['salesOrder' => $salesOrder->id]) : route('admin.salesOrder.store') }}"
        method="POST" id="salesOrderForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($salesOrder))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="order_date">Order Date :<span class="text-danger">*</span></label>
                    <input type="date" class="form-control w-100" name="order_date" id="order_date" maxlength="255"
                        value="{{ $salesOrder->order_date ?? '' }}" />
                    <span class="text-danger errors" id="order_dateerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="total_amount">Total Amount : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="total_amount" id="total_amount"
                        maxlength="255" value="{{ $salesOrder->total_amount ?? '' }}" />
                    <span class="text-danger errors" id="total_amounterror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sales_executive_fk">Sales Executive Name: <span class="text-danger">*</span></label>
                    <select id="sales_executive_fk" name="sales_executive_fk"
                        class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($salesexecutives as $salesexecutive)
                            <option value="{{ $salesexecutive->id }}"
                                {{ isset($salesOrder) && $salesOrder->sales_executive_fk == $salesexecutive->id ? 'selected' : '' }}>
                                {{ $salesexecutive->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="sales_executive_fkerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="store_fk">Store Name: <span class="text-danger">*</span></label>
                    <select id="store_fk" name="store_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}"
                                {{ isset($salesOrder) && $salesOrder->store_fk == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="store_fkerror"></span>
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
