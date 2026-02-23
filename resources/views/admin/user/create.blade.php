<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($user) ? route('admin.user.update', ['user' => $user->id]) : route('admin.user.store') }}"
        method="POST" id="userForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($user))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name" class="text-uppercase">Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="name" id="name" maxlength="100" min="5"
                        value="{{ $users->name ?? '' }}" />
                    <span class="text-danger errors" id="nameerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="email" class="text-uppercase">Email Id : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="email" id="email"
                        value="{{ $users->email ?? '' }}" />
                    <span class="text-danger errors" id="emailerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="password" class="text-uppercase">Password : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="password" id="password"
                        value="" />
                    <span class="text-danger errors" id="passworderror"></span>
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
</script>
<!--end::scripts End-->
