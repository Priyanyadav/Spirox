<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($admin) ? route('admin.admin.update', ['admin' => $admin->id]) : route('admin.admin.store') }}"
        method="POST" id="adminForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($admin))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name" class="text-uppercase">Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="name" id="name" maxlength="100"
                        min="5" value="{{ $admin->name ?? '' }}" />
                    <span class="text-danger errors" id="nameerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="mobile" class="text-uppercase">Mobile No : <span class="text-danger">*</span></label>
                    <input type="number" class="form-control w-100" name="mobile" id="mobile" maxlength="12"
                        min="10" value="{{ $admin->mobile ?? '' }}" />
                    <span class="text-danger errors" id="mobileerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="email">Email : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="email" id="email" maxlength="255"
                        value="{{ $admin->email ?? '' }}" />
                    <span class="text-danger errors" id="emailerror"></span>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label for="joining_date">Joining Date : <span class="text-danger">*</span></label>
                    <input type="date" class="form-control w-100" name="joining_date" id="joining_date"
                        value="{{ $admin->joining_date ?? '' }}" />
                    <span class="text-danger errors" id="joining_dateerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="role">Role : <span class="text-danger">*</span></label>
                    <select name="role" id="role" class="form-control w-100">
                        <option value="">--Select Option--</option>

                        <option value="Admin"
                            @if (!empty($admin)) {{ $admin->role == 'Admin' ? 'selected' : '' }} @endif>
                            Admin
                        </option>

                        <option value="Manager"
                            @if (!empty($admin)) {{ $admin->role == 'Manager' ? 'selected' : '' }} @endif>
                            Manager
                        </option>

                        <option value="Salesman"
                            @if (!empty($admin)) {{ $admin->role == 'Salesman' ? 'selected' : '' }} @endif>
                            Salesman
                        </option>

                    </select>
                    <span class="text-danger errors" id="roleerror"></span>
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
<script>
    $(document).ready(function() {
        $('#designation').select2();
        $('#language').select2();
    })
</script>
<!--end::scripts End-->
