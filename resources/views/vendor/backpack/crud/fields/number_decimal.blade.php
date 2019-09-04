<!-- number input -->
<div @include('crud::inc.field_wrapper_attributes') >
    <label for="{{ $field['name'] }}">{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
        	name="{{ $field['name'] }}"
            id="{{ $field['name'] }}"
            autocomplete="off"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include('crud::inc.field_attributes')
        	>
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif

    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>
@if ($crud->checkIfFieldIsFirstOfItsType($field))
    @push('crud_fields_scripts')
    <script src="{{ asset('vendor/adminlte') }}/plugins/input-mask/jquery.inputmask.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/input-mask/jquery.inputmask.numeric.extensions.js"></script>
    @endpush
@endif
@push('crud_fields_scripts')
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#{{ $field['name'] }}').inputmask({
            'alias': 'decimal',
            'groupSeparator': '.',
            'autoGroup': true,
            'digits': 2,
            'digitsOptional': false,
            'placeholder': '0',
            'prefix': 'VND ',
            'rightAlignNumerics': false
        });
    });
</script>
@endpush
