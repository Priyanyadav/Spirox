<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($salesExe) ? route('admin.salesExe.update', ['salesExe' => $salesExe->id]) : route('admin.salesExe.store') }}"
        method="POST" id="salesExeForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($salesExe))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sale_amount">Sales Amount :<span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="sale_amount" id="sale_amount" maxlength="255"
                        value="{{ $salesExe->sale_amount ?? '' }}" />
                    <span class="text-danger errors" id="sale_amounterror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sale_date">Sale Date : <span class="text-danger">*</span></label>
                    <input type="date" class="form-control w-100" name="sale_date" id="sale_date"
                        value="{{ $salesExe->sale_date ?? '' }}" />
                    <span class="text-danger errors" id="sale_dateerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sales_exec_id">Sales Executive Name: <span class="text-danger">*</span></label>
                    <select id="sales_exec_id" name="sales_exec_id" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($salesexecutives as $salesexecutive)
                            <option value="{{ $salesexecutive->id }}"
                                {{ isset($salesExe) && $salesExe->sales_exec_id == $salesexecutive->id ? 'selected' : '' }}>
                                {{ $salesexecutive->id }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="sales_exec_iderror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sales_order_fk">Sales Order id: <span class="text-danger">*</span></label>
                    <select id="sales_order_fk" name="sales_order_fk" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($salesorders as $salesorder)
                            <option value="{{ $salesorder->id }}"
                                {{ isset($salesExe) && $salesExe->sales_order_fk == $salesorder->id ? 'selected' : '' }}>
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
