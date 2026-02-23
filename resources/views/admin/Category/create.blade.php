<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($category) ? route('admin.category.update', ['category' => $category->id]) : route('admin.category.store') }}"
        method="POST" id="categoryForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($category))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="name" class="text-uppercase">Name : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="name" id="name" maxlength="100"
                        min="5" value="{{ $category->name ?? '' }}" />
                    <span class="text-danger errors" id="nameerror"></span>
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
<!--end::scripts End-->
