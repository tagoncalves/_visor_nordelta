<table class="table table-striped table-bordered" id="solicitudes">
	<thead>
		<tr>
			<th>Est</th>
			<th style="white-space: nowrap;">Fecha<span class="fa fa-fw fa-sort"></span></th>
			<th style="white-space: nowrap;">Protocolo<span class="fa fa-fw fa-sort"></span></th>
			<th style="white-space: nowrap;" data-placeholder="Seleccione profesional">Solicitante<span class="fa fa-fw fa-sort"></span></th>
			<th style="white-space: nowrap;" data-placeholder="Seleccione sector">Sector<span class="fa fa-fw fa-sort"></span></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody id="solicitudesBody">	
		<?php if (!empty($solicitudes)): ?>
			<?php foreach ($solicitudes as $item): ?>
				<tr class="abrirSolicitud" data-id="<?= $item->sector.$item->protocolo ?>">
					<td><span class="icono icon-circle <?= ($item->Estado == 'Final') ? 'iconFinal' : (($item->Estado == 'Parcial') ? 'iconParcial' : 'iconPendiente') ?> "></span></td>
					<td><?= $item->Fecha ?></td>
					<td><?= $item->protocolo ?></td>
					<td><?= $item->Solicitante ?></td>
					<td><?= ($item->sector == '89') ? 'Lab. Gral.' : 'Microb.' ?></td>
					<td class="tdOption">
						<?php if (!empty($item->archivo)): ?>
							<a href="#" class="openPDF" data-url="<?=base_url('estudios/getArchivoEstudio/'.$item->archivo) ?>"><span class="icon-file"></span></a>
						<?php endif; ?>
						<? if($item->Estado == 'Final' || $item->Estado == 'Parcial') : ?>
							<a href="#" class="abrirResultado" data-id="<?= $item->sector.$item->protocolo ?>" data-estado="<?= $item->Estado ?>"><span class="icon-search"></span></a>
						<? endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>	
		<?php if (!empty($solPasaje)): ?>
			<?php foreach ($solPasaje as $item): ?>
				<tr class="abrirSolicitud" data-id="<?= $item->sector.$item->protocolo ?>">
					<td><span class="icono icon-circle <?= ($item->Estado == 'Final') ? 'iconFinal' : (($item->Estado == 'Parcial') ? 'iconParcial' : 'iconPendiente') ?> "></span></td>
					<td><?= $item->Fecha ?></td>
					<td><?= $item->protocolo ?></td>
					<td><?= $item->Solicitante ?></td>
					<td><?= ($item->sector == '89') ? 'Lab. Gral.' : 'Microb.' ?></td>
					<td class="tdOption">
						<?php if (!empty($item->archivo)): ?>
							<a href="#" class="openPDF" data-url="<?=base_url('estudios/getArchivoEstudio/'.$item->archivo) ?>"><span class="icon-file"></span></a>
						<?php endif; ?>
						<? if($item->Estado == 'Final' || $item->Estado == 'Parcial') : ?>
							<a href="#" class="abrirResultado" data-id="<?=$item->sector.$item->protocolo ?>" data-estado="<?= $item->Estado ?>"><span class="icon-search"></span></a>
						<? endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>				
		<?php if (!empty($estudios->ESTUDIOS)): ?>
			<?php foreach ($estudios->ESTUDIOS as $item): ?>
				<tr class="abrirSolicitud">
					<td><span class="icono-xl iconFinal icon-file"></span></td>
					<td><?= $item[0] ?></td>
					<td><?= $item[3] ?></td>
					<td><?= $item[5] ?></td>
					<td><?= $item[4] ?></td>
					<td class="tdOption">
						<a href="#" class="abrirInforme" data-id="<?= $item[3] ?>" data-sector="<?= $item[4] ?>"><span class="icon-search"></span></a>
					</td>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>                             
</table>