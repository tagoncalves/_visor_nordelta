		<!-- <footer class="Site-footer">
			<div class="footer">
				<div class="footer-inner">
					<div class="container">
						<div class="row">
							<div class="span12"> 
								&copy; <?=date("Y")?> <a href="http://www.cm-nordelta.com.ar">Sistemas Centro M&eacute;dico Nordelta</a>.
							</div></span12 
						</div> 
					</div> 
				</div>  
			</div> 
		</footer> --> 
		
		<script type="text/javascript" src="<?= base_url('assets/js/jquery-1.11.3.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/jquery.forms.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/excanvas.min.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/jquery.timer.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/jquery.sticky.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/bootstrap.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/bootstrap-datepicker.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/jquery.tablesorter.min.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/jquery.tablesorter.widgets.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/jquery.blockUI.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/on-off-switch.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/on-off-switch-onload.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/script.js') ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/sweetalert.min.js') ?>"></script>		
		<script type="text/javascript" src="<?= base_url('assets/js/jquery.postitall.js') ?>"></script>		
		<?php if($page == 'turnos') : ?>
			<script type="text/javascript" src="<?= base_url('assets/js/full-calendar/moment.min.js') ?>"></script>
			<script type="text/javascript" src="<?= base_url('assets/js/full-calendar/fullcalendar.js') ?>"></script>
			<script type="text/javascript" src="<?= base_url('assets/js/full-calendar/es.js') ?>"></script>
			<script type="text/javascript" src="<?= base_url('assets/js/turnos.js') ?>"></script>
		<?php elseif($page == 'agenda') : ?>
			<script type="text/javascript" src="<?= base_url('assets/js/agenda.js') ?>"></script>
			<script type="text/javascript" src="<?= base_url('assets/js/upload.js') ?>"></script>
		<?php elseif($page == 'consultahc') : ?>
			<script type="text/javascript" src="<?= base_url('assets/js/consultahc.js') ?>"></script>
		<?php elseif($page == 'login') : ?>
			<script type="text/javascript" src="<?= base_url('assets/js/login.js') ?>"></script>
		<?php endif; ?>
		
	</body>
</html>
