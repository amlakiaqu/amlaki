@if ($crud->hasAccess('restore') && $entry->trashed())
    <a href="{{ Request::url().'/'.$entry->getKey() }}/restore" class="btn btn-xs btn-default" data-button-type="restore"><i class="fa fa-undo" aria-hidden="true"></i> {{ trans('backpack::crud.restore') }}</a>
@endif

@if (!isset($crud->restoreBtnFirstTime) || (isset($crud->restoreBtnFirstTime) && $crud->restoreBtnFirstTime !== false))
    @push('crud_list_scripts')
    <script>
        register_restore_button_action();
        function register_restore_button_action() {
            $("[data-button-type=restore]").unbind('click');
            // CRUD Restore
            // ask for confirmation before deleting an item
            $("[data-button-type=restore]").click(function(e) {
                e.preventDefault();
                var restore_button = $(this);
                var restore_url = $(this).attr('href');

                if (confirm("{{ trans('backpack::crud.restore_confirm') }}") == true) {
                    $.ajax({
                        url: restore_url,
                        dataType: 'json',
                        type: 'PUT',
                        success: function(result) {
                            // Show an alert with the result
                            new PNotify({
                                title: "{{ trans('backpack::crud.restore_confirmation_title') }}",
                                text: "{{ trans('backpack::crud.restore_confirmation_message') }}",
                                type: "success"
                            });
                            // delete the row from the table
                            restore_button.parentsUntil('tr').parent().remove();
                        },
                        error: function(result) {
                            // Show an alert with the result
                            new PNotify({
                                title: "{{ trans('backpack::crud.restore_confirmation_not_title') }}",
                                text: "{{ trans('backpack::crud.restore_confirmation_not_message') }}",
                                type: "warning"
                            });
                        }
                    });
                }
            });
        }
    </script>
    @endpush
@endif

@php
    $crud->restoreBtnFirstTime = false;
@endphp