<script>
    $(document).ready(function (){
        $(document).on('click', '.application-state-label', function () {
            const applicationId = $(this).data('id');
            $('.input-application-id').val(applicationId)
        })
    })
    const updateApplicationState = (state) => {
        $('.input-application-state').val(state)
        $('.update-state-form').submit();
    };
</script>