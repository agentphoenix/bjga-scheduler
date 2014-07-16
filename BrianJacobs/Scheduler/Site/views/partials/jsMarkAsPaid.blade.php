<script>

	$(document).on('click', '.js-markAsPaid', function(e)
	{
		e.preventDefault();

		var button = $(this);

		$.ajax({
			url: "{{ URL::route('ajax.markAsPaid') }}",
			type: "POST",
			data: { appt: $(this).data('appt') },
			success: function(data)
			{
				button.fadeOut('normal', function()
				{
					$(this).remove();
				});
			}
		});
	});

</script>