$(function () {
	$(document).ready(function(){
		$( "#cboUbicacion" ).click(function() {
			$( "#cboUbicacion" ).change();
		})
		
		$( "#cboUbicacion" ).change(function() {
			var val = $('#cboUbicacion option:selected').val();
			
			if (val.trim() != ""){
				$.ajax({
					type : 'POST',
					dataType : 'html',
					url : 'getBoxes',
					data :{'val' : val},
					cache :  false		
				}).done( function(data) {
					$("#cboBox").html(data);
					
					var texto = $('#cboBox option:selected').text();
					
					if (texto.trim() != ""){
						$('#btn-confirmar').removeClass( "btn-danger" ).addClass( "btn-success" );
						$('#btn-confirmar').prop('disabled', false);
					}else{
						$('#btn-confirmar').removeClass( "btn-success" ).addClass( "btn-danger" );
						$('#btn-confirmar').prop('disabled', true);
					}
				});
			}
		});
		
		/*$( "#cboBox" ).click(function() {
			$( "#cboBox" ).change();
		});*/
		
		$( "#cboBox" ).change(function() {
			var texto = $('#cboBox option:selected').text();
			
			if (texto.trim() != ""){
				$('#btn-confirmar').removeClass( "btn-danger" ).addClass( "btn-success" );
				$('#btn-confirmar').prop('disabled', false);
			}else{
				$('#btn-confirmar').removeClass( "btn-success" ).addClass( "btn-danger" );
				$('#btn-confirmar').prop('disabled', true);
			}
		});
	});
});