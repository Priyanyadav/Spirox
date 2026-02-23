<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($tester) ? route('admin.tester.update', ['tester' => $tester->id]) : route('admin.tester.store') }}"
        method="POST" id="testerForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($tester))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name">Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="name" id="name"
                        value="{{ $tester->name ?? '' }}" />
                    <span class="text-danger errors" id="nameerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="email">Email : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="email" id="email"
                        value="{{ $tester->email ?? '' }}" />
                    <span class="text-danger errors" id="emailerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="date">Date Of Birth : <span class="text-danger">*</span></label>
                    <input type="date" class="form-control w-100" name="date" id="date"
                        value="{{ $tester->dob ?? '' }}" />
                    <span class="text-danger errors" id="dateerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="designation">Designation : <span class="text-danger">*</span></label>
                    <select name="designation" id="designation" class="form-control w-100">
                        <option value="">--Select Option--</option>
                        <option value="0"
                            @if (!empty($tester)) {{ $tester->designation == '0' ? 'selected' : '' }} @endif>
                            Training
                        </option>
                        <option value="1"
                            @if (!empty($tester)) {{ $tester->designation == '1' ? 'selected' : '' }} @endif>
                            Senior Tester
                        </option>
                        <option value="2"
                            @if (!empty($tester)) {{ $tester->designation == '2' ? 'selected' : '' }} @endif>
                            Junior Tester</option>
                    </select>
                    <span class="text-danger errors" id="designationerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="mobile">Mobile No : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="mobile" id="mobile"
                        value="{{ $tester->phone ?? '' }}" />
                    <span class="text-danger errors" id="mobileerror"></span>
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
                    {{-- <span toggle="#password_confirmation" class="fa fa-fw fa-eye field_icon confirm-password"></span> --}}
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
    });
</script>
<!--end::scripts End-->
