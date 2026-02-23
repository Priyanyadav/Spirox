<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($BillingPDF) ? route('admin.BillingPDF.update', ['BillingPDF' => $BillingPDF->id]) : route('admin.BillingPDF.store') }}"
        method="POST" id="BillingPDFForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($BillingPDF))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="pdf_url">Pdf Url :<span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="pdf_url" id="pdf_url" maxlength="255"
                        value="{{ $BillingPDF->pdf_url ?? '' }}" />
                    <span class="text-danger errors" id="pdf_urlerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="shared_at">Shared At : <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control w-100" name="shared_at" id="shared_at"
                        value="{{ $BillingPDF->shared_at ?? '' }}" />
                    <span class="text-danger errors" id="shared_aterror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sales_order_fk">Sales Order id: <span class="text-danger">*</span></label>
                    <select id="sales_order_fk" name="sales_order_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($salesorders as $salesorder)
                            <option value="{{ $salesorder->id }}"
                                {{ isset($BillingPDF) && $BillingPDF->sales_order_fk == $salesorder->id ? 'selected' : '' }}>
                                {{ $salesorder->id }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="sales_order_fkerror"></span>
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
