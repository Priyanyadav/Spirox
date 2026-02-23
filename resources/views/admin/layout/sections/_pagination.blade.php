<!--begin::pagination start-->
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap float-right">
            <div class="d-flex flex-wrap py-2">
                {!! $data->links() !!}
            </div>
        </div>
    </div>
<!--end::pagination End-->
<!--begin::scripts start-->
        <script type="text/javascript">
            $(document).ready(function() {
                $(".pagination li .page-link").addClass('btn btn-icon btn-sm border-0 btn-hover-primary mr-2 my-1');
                $(".pagination li").each(function() {
                    if ($(this).attr("aria-label") == "« Previous") {
                        $(this).find('span').html(`<i class="ki ki-bold-arrow-back icon-xs"></i>`);
                    } else if ($(this).attr("aria-label") == "Next »") {
                        $(this).find('span').html(`<i class="ki ki-bold-arrow-next icon-xs"></i>`);
                    }
                });
                $(".pagination li a").each(function() {
                    if ($(this).attr("aria-label") == "« Previous") {
                        $(this).html(`<i class="ki ki-bold-arrow-back icon-xs"></i>`);
                    } else if ($(this).attr("aria-label") == "Next »") {
                        $(this).html(`<i class="ki ki-bold-arrow-next icon-xs"></i>`);
                    }
                });
            });
        </script>
<!--end::scripts End-->
