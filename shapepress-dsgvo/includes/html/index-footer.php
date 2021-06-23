

					<li class="clear-fix"></li>
					<li class="pagination">
						<p>
							<?php _e('Downloaded from', 'shapepress-dsgvo'); ?> <a href="[home_url]">[home_url]</a> <?php _e('on', 'shapepress-dsgvo'); ?> [date_now]
						</p>
					</li>
				</ul>
			</div>
		</main>
	</body>
	<script>
		(function($){

			$('.form-control').keyup(function(){
				var search = $(this).val().toLowerCase();

				if(search.length > 2){
					$('.row').each(function(i, ele){
						var title = $(ele).find('.grid-title').html().toLowerCase();
						var data = $(ele).find('.grid-date').html().toLowerCase();

						if(!title.includes(search) && !data.includes(search)){
							$(ele).hide();
						}else{
							$(ele).show();
						}
					});
				}else{
					$('.row').show();
				}
			});

		})(jQuery);
	</script>
</html>
