<div id="{{ $modalId }}" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			{{ partial('common/modal_content', array('modalHeader' => $modalHeader, 'modalBody' => $modalBody, 'modalFooter' => $modalFooter)) }}
		</div>
	</div>
</div>