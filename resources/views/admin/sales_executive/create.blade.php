<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($sales_executive) ? route('admin.sales_executive.update', ['sales_executive' => $sales_executive->id]) : route('admin.sales_executive.store') }}"
        method="POST" id="sales_executiveForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($sales_executive))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name" class="text-uppercase">Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="name" id="name" maxlength="100"
                        min="5" value="{{ $sales_executive->name ?? '' }}" />
                    <span class="text-danger errors" id="nameerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="mobile" class="text-uppercase">Mobile No : <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" name="mobile" id="mobile" maxlength="12"
                        min="10" value="{{ $sales_executive->mobile ?? '' }}" />
                    <span class="text-danger errors" id="mobileerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="email">Email : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="email" id="email" maxlength="255"
                        value="{{ $sales_executive->email ?? '' }}" />
                    <span class="text-danger errors" id="emailerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="assigned_area">Assigned Area : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="assigned_area" id="assigned_area"
                        maxlength="255" value="{{ $sales_executive->assigned_area ?? '' }}" />
                    <span class="text-danger errors" id="assigned_areaerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="joining_date">Joining Date :<span class="text-danger">*</span></label>
                    <input type="date" class="form-control w-100" name="joining_date" id="joining_date"
                        maxlength="255" value="{{ $sales_executive->joining_date ?? '' }}" />
                    <span class="text-danger errors" id="joining_dateerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="password">{{ $password }} : <span class="text-danger">*</span></label>
                    <input type="password" class="form-control w-100" name="password" id="password" value="" />
                    <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password position-absolute"
                        style="right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color:black"></span>
                    {{-- <span toggle="#password-field" class="fa fa-fw fa-eye field_icon toggle-password"></span> --}}
                    <span class="text-danger errors" id="passworderror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password : <span class="text-danger">*</span></label>
                    <input type="password" class="form-control w-100" name="password_confirmation"
                        id="password_confirmation" value="" />
                    <span toggle="#password_confirmation"
                        class="fa fa-fw fa-eye field_icon confirm-password position-absolute"
                        style="right: 20px; top: 50%; transform: translateY(-50%); cursor: pointer; color:black"></span>
                    {{-- <span toggle="#password_confirmation"
                        class="fa fa-fw fa-eye field_icon confirm-password"></span> --}}
                    <span class="text-danger errors" id="password_confirmationerror"></span>
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
