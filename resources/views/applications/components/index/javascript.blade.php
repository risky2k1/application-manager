<script>
    $(document).ready(function (){
        $(document).on('click', '.application-state-label', function () {
            const applicationId = $(this).data('id');
            const applicationUpdateStateUrl = $(this).data('url');
            $('.input-application-id').val(applicationId)
            $('.update-state-form').attr('action', applicationUpdateStateUrl);
        })
    })
    const updateApplicationState = (state) => {
        $('.input-application-state').val(state)
        $('.update-state-form').submit();
    };
</script>