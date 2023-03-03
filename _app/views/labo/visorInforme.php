<? if (!empty($informe->INFORME)) : ?>
	<div class="sticky-wrapper">
		<div class="widget">
			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Datos</h3>
			</div>
			<div class="widget-content">
				<div class="form-inline">
					<label for="informe-fecha">
						Fecha
						<br>
						<input type="text" class="input-small" id="informe-fecha" name="informe-fecha" value="<?=$informe->INFORME[0] ?>" readonly />
					</label>
					
					<label for="informe-Protocolo">
						Protocolo
						<br>
						<input type="text" class="input-small" id="informe-Protocolo" name="informe-Protocolo" value="<?=$informe->INFORME[1] ?>" readonly />
					</label>
				</div>
				<div class="form-inline">
					<label for="informe-HC">
						HC
						<br>
						<input type="text" class="input-small" id="informe-HC" name="informe-HC" value="<?=$informe->INFORME[2] ?>" readonly />
					</label>
					
					<label for="informe-Paciente">
						Paciente
						<br>
						<input type="text" class="input" id="informe-Paciente" name="informe-Paciente" value="<?=$informe->INFORME[3] ?>" readonly />
					</label>
					
					<label for="informe-Prepaga">
						Prepaga
						<br>
						<input type="text" class="input" id="informe-Prepaga" name="informe-Prepaga" value="<?=$informe->INFORME[6] ?>" readonly />
					</label>
				</div>
			</div><!-- /.panel-body -->
		</div><!-- /.widget -->
	</div><!-- /.panel -->
	
	<div class="sticky-wrapper">
		<div class="widget">
			<div class="widget-header">
				<i class="icon-list-alt"></i>
				<h3>Informe</h3>
			</div>
			<div class="widget-content">
				<div class="visorInformes">
					<?=$informe->INFORME[7] ?>
				</div>
			</div><!-- /.panel-body -->
		</div><!-- /.widget -->
	</div><!-- /.panel -->
	
	<?php if (!empty($informe->LINKS)): ?>
		<table class="table table-striped table-bordered" id="informesWeb">
			<thead>
				<tr>
					<th>Codigo</th>
					<th>Informe</th>
				</tr>
			</thead>
			<tbody id="InformesWebBody">	
				<?php foreach ($informe->LINKS as $item): ?>
					<tr class="abrirSolicitud" data-url="">
						<td><?= $item[0] ?></td>
						<td><a target="_blank" href="<?= $item[1] ?>"><button type="submit" class="btn btn-success">Visualizar</button></a></td>
					</tr>
				<?php endforeach; ?>
			</tbody>                             
		</table>
	<?php endif; ?>	
<? else : ?>
	<p class="bg-danger">Se produjo un error al cargar el informe, Por favor intente nuevamente mas tarde.</p>
<? endif; ?>