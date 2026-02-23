<!--begin::form start-->
<fieldset>
    <form
        action="{{ !empty($livelocationtracking) ? route('admin.livelocationtracking.update', ['livelocationtracking' => $livelocationtracking->id]) : route('admin.livelocationtracking.store') }}"
        method="POST" id="livelocationtrackingForm" class="form-horizontal" enctype="multipart/form-data">
        @if (!empty($livelocationtracking))
            @method('PUT')
        @endif
        @csrf
        <input type="hidden" class="form-control id" name="id" id="id">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="latitude">Latitude :<span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="latitude" id="latitude" maxlength="255"
                        value="{{ $livelocationtracking->latitude ?? '' }}" />
                    <span class="text-danger errors" id="latitudeerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="longitude">Longitude : <span class="text-danger">*</span></label>
                    <input type="text" class="form-control w-100" name="longitude" id="longitude"
                        value="{{ $livelocationtracking->longitude ?? '' }}" />
                    <span class="text-danger errors" id="longitudeerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="location_time">Location Time : <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control w-100" name="location_time" id="location_time"
                        value="{{ $livelocationtracking->location_time ?? '' }}" />
                    <span class="text-danger errors" id="location_timeerror"></span>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="sales_exec_id">Sales Executive Name: <span class="text-danger">*</span></label>
                    <select id="sales_exec_id" name="sales_exec_id" class="form-control w-100 dropdown-toggle">
                        <option value="">--- Select Option ---</option>
                        @foreach ($salesexecutives as $salesexecutive)
                            <option value="{{ $salesexecutive->id }}"
                                {{ isset($livelocationtracking) && $livelocationtracking->sales_exec_id == $salesexecutive->id ? 'selected' : '' }}>
                                {{ $salesexecutive->id }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-danger errors" id="sales_exec_iderror"></span>
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
