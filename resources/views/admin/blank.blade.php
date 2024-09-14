@extends('admin.layout.master')
@extends('admin.asset.scripts')
@push('plugin-styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
        <div>
            <h4 class="mb-3 mb-md-0">Welcome toa Dashboard</h4>
        </div>
    </div>
    <h1>My Form</h1>
    <form id="my-form" action="/your-action-here">
        <div class="fields"></div>

        <p>
            <button type="button" class="add-fields">
                Add fields
            </button>

            <button type="submit">
                Send form
            </button>
        </p>
    </form>
@endsection
<script type="text/x-templates" id="fields-templates">
    <p class="input-fields">
        <input name="title[]" placeholder="title">
        <input name="description[]" placeholder="description">
        <button type="button" class="remove-fields">
            Remove these fields
        </button>
    </p>
</script>


<script src="{{ asset('assets/js/jquery2.min.js') }}"></script>
<script>
    alert("sdfsf");
    $(function() {
        var FIELDS_TEMPLATE = $('#fields-templates').text();
        var $form = $('#my-form');
        var $fields = $form.find('.fields');

        $form.on('click', '.add-fields', function() {
            $fields.prepend($(FIELDS_TEMPLATE));
        });

        $form.on('click', '.remove-fields', function(event) {
            $(event.target).closest('.input-fields').remove();
        });
    });
</script>
