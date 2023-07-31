		<!-- Footer -->
		<footer id="footer">
			<div class="copyright">
					<?php print " &copy; AOTECH合創數位科技, ".$lang->line("index.browser_suggested_size").": 1280 * 900為佳<br> "; ?>
					<?php if(false && !isset($_SESSION['admin_user']['sn']) && !isset($_SESSION['user']['sn'])) { ?>
						<a href="admin_login.php"><?php Echo $lang->line("index.admin_login"); ?></a>.
					<?php } ?>
			</div>
		</footer>		
		<!-- </section> -->
		<!-- Scripts -->
		<!-- <script src="assets/js/jquery.min.js"></script> -->
		<script src="assets/js/jquery.scrolly.min.js"></script>
		<script src="assets/js/skel.min.js"></script>
		<script src="assets/js/util.js"></script>
		<script src="assets/js/main.js"></script>
	</body>
	
	
	<script>	
	// -- 20200226
	if($('#main').height() > 760) {
		
		$('#footer').css({'position' : 'static'});
	}
	</script>
	
</html>