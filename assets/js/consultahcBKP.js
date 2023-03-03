$('document').ready(function(){
	$("#antecedentes-table").tablesorter({
		sortList : [[0,0]],
		widgets: ["filter"],
		widgetOptions : {
			filter_childRows : false,
			filter_childByColumn : false,
			filter_childWithSibs : true,
			filter_columnFilters : true,
			filter_columnAnyMatch: true,
			filter_cellFilter : '',
			filter_cssFilter : '', // or []
			filter_defaultFilter : {},
			filter_excludeFilter : {},
			filter_external : '',
			filter_filteredRow : 'filtered',
			filter_formatter : null,
			filter_functions : null,
			filter_hideEmpty : true,
			/*filter_hideFilters : true,*/
			filter_ignoreCase : true,
			filter_liveSearch : true,
			filter_matchType : { 'input': 'exact', 'select': 'exact' },
			filter_onlyAvail : 'filter-onlyAvail',
			filter_placeholder : { search : '', select : '' },
			filter_resetOnEsc : true,
			filter_saveFilters : true,
			filter_searchDelay : 300,
			filter_searchFiltered: true,
			filter_selectSource  : null,
			filter_serversideFiltering : false,
			filter_startsWith : false,
			filter_useParsedData : false,
			filter_defaultAttrib : 'data-value',
			filter_selectSourceSeparator : '|'
		}
	});
	
	$('body').on('click','#btn-buscar',function(event){		
		event.preventDefault();
		var hc = $("#paciente-hc").val();	
		var nombre = $("#paciente-nombre").val().toUpperCase();	
		var dni = $("#paciente-dni").val();	
		
		$.ajax({
			type : 'POST',
			dataType : 'json',
			url : 'buscarPaciente',
			data :{
				'hc' : hc,
				'nombre' : nombre,
				'dni' : dni
			},
			cache :  false		
		}).done(function(data) {	
			$('#resPaciente tbody').empty();
			$('#antecedentes-table tbody').empty();
			if(data["pacientes"] != null){
				var html = "";
				$.each(data["pacientes"] , function(i, item) { 
					html = '<tr data-hc="' + item[2] + '"><td>' + item[2] + '</td><td>' + item[0] + '</td><td>' + item[1] + '</td></tr>';
					$('#resPaciente tbody').append(html);
				}); 				
			}				
		});
	});
	
	$('body').on('click','#resPaciente tbody tr',function(){		
		$("#resPaciente tbody tr").css( "font-weight", "" );
		$(this).css( "font-weight", "bold" );
		var hc = $(this).data('hc');
		cargarAntecedentes(hc);
	});
	
	$('body').on('click','#btn-cerrar-antecedente',function(){		
		$('#visorAnt .widget-header h3').html("");
		$('#visorAnt .widget-content').html("");
		$('#visorAnt').hide();
	});
});

function cargarAntecedentes(hc){
	$('.abrirAntecedente').unbind('click');
	$('.abrirAdjunto').unbind('click');
	$('.abrirInforme').unbind('click');
	
	$.ajax({
		type : 'POST',
		dataType : 'json',
		url : 'cargarAntecedentesPac',
		data :{'hc' : hc},
		cache :  false		
	}).done( function(data) {	
		$('#antecedentes-table tbody').empty();
		$('.abrirAntecedente').unbind('click',abrirAntecedente);
		$('.abrirAdjunto').unbind('click',abrirAdjunto);
		if(data["antecedentes"] != null){
			var html = "";
			$.each(data["antecedentes"] , function(i, item) { 
				html = '<tr class="abrirAntecedente" data-ingreso="' + item[5] + '" data-titulo="' + item[0] + ' - ' + item[4] + ' - ' + item[3] + '" ><td>' + item[0] + '</td><td>' + item[4] + '</td><td>' + item[2] + ' - ' + item[3] + '</td></tr>';
				$('#antecedentes-table tbody').append(html);
			}); 
		}
		if(data["archivos"] != null){
			var html = "";
			$.each(data["archivos"], function(i, item) {
				if(item["archivo"] != "Thumbs.db"){
					html = '<tr class="abrirAntecedente" data-archivo="' + item["archivo"] + '" data-hc="' + hc + '"><td>' + item["fecha"] + '</td><td>' + item["archivo"] + '</td><td>Estudios Complementarios</td></tr>';
					$('#antecedentes-table tbody').append(html);
				}
			}); 
		}
		if(data["adjuntos"] != null){
			var html = "";
			$.each(data["adjuntos"], function(i, item) {
				if(item["archivo"] != "Thumbs.db"){
					html = '<tr class="abrirAdjunto" data-archivo="/' + hc + "/" + item["archivo"] + '"><td>' + item["fecha"] + '</td><td>' + item["archivo"] + '</td><td>Documentos Adjuntos</td></tr>';
					$('#antecedentes-table tbody').append(html);
				}
			}); 
		}
		
		$.ajax({
			type : 'POST',
			dataType : 'json',
			url : 'getInformesPac',
			data :{'hc' : hc},
			cache :  false		
		}).done( function(data) {
			
			if(data["ESTUDIOS"] != null){
				var html = "";
				$.each(data["ESTUDIOS"], function(i, item) {
					var fec = item[0].split("/");
					html = '<tr class="abrirInforme" data-id=' + item[3] + ' data-sec=' + item[4] + ' ><td>' + fec[2]+"-"+fec[1]+"-"+fec[0] + '</td><td>' + item[5] + '</td><td>' + item[1] + '</td></tr>';
					$('#antecedentes-table tbody').append(html);
				}); 
			}
			
			
			$('.abrirAntecedente').bind('click',abrirAntecedente);
			$('.abrirAdjunto').bind('click',abrirAdjunto);
			$('.abrirInforme').bind('click',abrirInforme);
			$('#antecedentes-table').trigger('update');
			$('#antecedentes-table').trigger('filterReset');
			$('#frameAntecedentes').show();
		});		
	});
}

function abrirInforme(){
	var id = $(this).data('id');
	var sector = $(this).data('sec');
	
	$('#antecedentes-table').block({ message: 'Cargando...' });
	$("#myModal .modal-content .modal-body").html('');
	
	$.ajax({
		url: "/_hcdigital_desarrollo/estudios/getInforme/",
		method: "POST",
		dataType: "html",
		data: {
			'id' : id,
			'sector' : sector
		}
	}).done(function(html){
		$('#visorAnt').hide("slow");
		$('#visorAnt .widget-content').html(html);

		$('#visorAnt').show("slow");
		$('#antecedentes-table').unblock();
	});
}

function abrirAntecedente(){
	$('#antecedentes-table').block({ message: 'Cargando...' });
	
	$(".abrirAntecedente").css( "font-weight", "" );
	$(this).css( "font-weight", "bold" );
	
	var ingreso = $(this).data('ingreso');
	var archivo = $(this).data('archivo');
	var hc = $(this).data('hc');
	var titulo = $(this).data('titulo');
	 
	$("#myModal .modal-content .modal-body").html('');
	
	if(ingreso != null){
		$.ajax({
			type : 'POST',
			dataType : 'html',
			url : 'cargarDiagnostico/' + ingreso,
			cache :  false			
		}).done(function(html){
			$('#visorAnt .widget-header h3').html(titulo);
			$('#visorAnt .widget-content').html(html);			
			$('#antecedentes-table').unblock();
			$('#visorAnt').show();
		});
	}else{		
		$.ajax({
			type : 'POST',
			dataType : 'html',
			url : 'PDF/',
			data: {
				'hc' 		: hc,
				'archivo' 	: archivo
			},
			cache :  false			
		}).done(function(html){	
			$('#visorAnt .widget-header h3').html(titulo);
			$('#visorAnt .widget-content').html(html);			
			$('#antecedentes-table').unblock();
			$('#visorAnt').show();
		});
	}
}

function abrirAdjunto(){
    window.open("uploads" + $(this).data("archivo"), '_blank');
}