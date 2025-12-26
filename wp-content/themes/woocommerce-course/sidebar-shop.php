<!-- Start Sidebar Area Wrapper -->
<div class="col-lg-3 order-last order-lg-first mt-md-54 mt-sm-44">
	<div class="sidebar-area-wrapper">

		<form action="" method="POST" id="ajaxform">

			<?php $product_categories = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) );

				if( $product_categories ) : ?>
				<!-- Start Single Sidebar -->
				<div class="single-sidebar-wrap">
						<h3 class="sidebar-title">Категории товаров</h3>
						<div class="sidebar-body">
								<ul class="sidebar-list">
									<?php foreach( $product_categories as $product_category ) : ?>
										<li>
											<input type="checkbox" name="product_cats[]" id="product-cat-<?php echo absint( $product_category->term_id ) ?>" value="<?php echo absint( $product_category->term_id ) ?>" />
											<label for="product-cat-<?php echo absint( $product_category->term_id ) ?>"><?php echo esc_html( $product_category->name ) ?> <span>(<?php echo absint( $product_category->count ) ?>)</span></label>
										</li>
									<?php endforeach; ?>
								</ul>
						</div>
				</div>
				<!-- End Single Sidebar -->
			<?php endif; ?>



				<!-- Start Single Sidebar -->
				<div class="single-sidebar-wrap">
						<h3 class="sidebar-title">Цены</h3>
						<div class="sidebar-body">
								<div class="price-range-wrap">
										<div class="price-range" data-min="10" data-max="100"></div>
										<div class="range-slider">

												<label for="amount">Цена: </label>
												<input type="text" id="amount" value="" />
												<input type="hidden" id="min_price" name="min_price" value="<?php echo isset( $_GET[ 'min_price' ] ) ? intval( $_GET[ 'min_price' ] ) : 10 ?>" />
												<input type="hidden" id="max_price" name="max_price" value="<?php echo isset( $_GET[ 'max_price' ] ) ? intval( $_GET[ 'max_price' ] ) : 100 ?>" />
												<?php echo wc_query_string_form_fields( null, array( 'min_price', 'max_price', 'paged' ), '', true ); ?>

										</div>
								</div>
						</div>
				</div>
				<!-- End Single Sidebar -->

				<?php $product_razmers = get_terms( array( 'taxonomy' => 'pa_razmer', 'hide_empty' => false ) ); ?>

				<?php if( $product_razmers ) :

					$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

					if( !empty( $_GET[ 'min_price' ] ) ) {
						$shop_page_url = add_query_arg( 'min_price', $_GET[ 'min_price' ], $shop_page_url );
					}

					if( !empty( $_GET[ 'max_price' ] ) ) {
						$shop_page_url = add_query_arg( 'max_price', $_GET[ 'max_price' ], $shop_page_url );
					}

				?>
				<!-- Start Single Sidebar -->
				<div class="single-sidebar-wrap">
						<h3 class="sidebar-title">Размер</h3>
						<div class="sidebar-body">
								<ul class="size-list">
									<?php foreach( $product_razmers as $product_razmer ) : ?>
										<li>
											<input type="checkbox" name="razmer[]" id="razmer-<?php echo esc_attr( $product_razmer->slug ) ?>" value="<?php echo esc_attr( $product_razmer->slug ) ?>" />
											<label for="razmer-<?php echo esc_attr( $product_razmer->slug ) ?>"><?php echo esc_html( $product_razmer->name ) ?></label>
										</li>
									<?php endforeach; ?>
								</ul>
						</div>
				</div>
				<!-- End Single Sidebar -->
			<?php endif; ?>

			<input type="hidden" name="orderby" value="date" />
			<input type="hidden" name="action" value="ajaxfilter" />
			
		</form>
	</div>
</div>
<!-- End Sidebar Area Wrapper -->
