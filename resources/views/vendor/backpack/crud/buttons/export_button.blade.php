<button id="export_button" type="button" class="btn btn-primary">Xuất Excel</button>
@push('after_scripts')
<script type="text/javascript">
    jQuery('document').ready(function($){

       $('#export_button').click(function(){

           let filter_status = $('#filter_status').val();

           let filter_from = "{{ request()->filled('from_to')?  json_decode(request()->input('from_to'))->from : "" }}";
           let filter_to = "{{ request()->filled('from_to')?  json_decode(request()->input('from_to'))->to : "" }}";

           window.location.href = '{{ backpack_url('report_export') }}' +
               '?filter_status=' + filter_status + '&filter_from=' + filter_from + '&filter_to=' + filter_to;
           return false;
       });
    })
</script>
@endpush