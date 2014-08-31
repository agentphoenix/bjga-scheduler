<div id="applyCredit" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title">Apply User Credit</h3>
			</div>

			<div class="modal-body">
				<p id="success" class="alert alert-success hide">User credit code has been successfully applied to your account!</p>

				<p id="error" class="alert alert-danger hide"></p>

				<p id="applyIntro">If you have a 12-digit user credit code, you can apply it to your account by typing in the code and clicking on Apply.</p>

				<form id="applyCreditForm">
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								{{ Form::text('code', null, array('class' => 'form-control input-lg', 'placeholder' => "Enter user credit code")) }}
							</div>
						</div>
					</div>
					
					<div class="visible-md visible-lg">
						{{ Form::submit("Apply", array('id' => 'applyCreditBtn', 'class' => 'btn btn-lg btn-primary')) }}
					</div>
					<div class="visible-xs visible-sm">
						{{ Form::submit("Apply", array('id' => 'applyCreditBtn', 'class' => 'btn btn-lg btn-block btn-primary')) }}
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$('#applyCreditBtn').on('click', function(e)
	{
		e.preventDefault();

		$.ajax({
			beforeSend: function()
			{
				$('#success').addClass('hide');
				$('#error').addClass('hide');
			},
			type: "POST",
			url: "{{ route('credit.apply') }}",
			data: $('#applyCreditForm').serialize(),
			dataType: "json",
			success: function(data)
			{
				if (data.code == 1)
				{
					$('#applyCreditForm').addClass('hide');
					$('#success').removeClass('hide');
					$('#applyIntro').addClass('hide');
				}
				else
				{
					$('#error').html(data.message).removeClass('hide');
					$('[name="code"]').val('');
				}
			},
			error: function(data)
			{
				$('#error').html(data.message).removeClass('hide');
				$('[name="code"]').val('');
			}
		});
	});
</script>