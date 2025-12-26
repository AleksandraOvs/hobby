<?php get_header() ?>


	<?php the_post(); ?>

	<!-- Start Page Header Wrapper -->
	<div class="page-header-wrapper">
	    <div class="container">
	        <div class="row">
	            <div class="col-lg-12 text-center">
	                <div class="page-header-content">
	                    <h2><?php the_title() ?></h2>
	                    <?php woocommerce_breadcrumb() ?>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
	<!-- End Page Header Wrapper -->

		<?php the_content() ?>


<?php get_footer() ?>
