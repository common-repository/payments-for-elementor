<div class="wrap">

	<h1><?php _e( 'Payments for Elementor Settings', 'payments-for-elementor' ); ?></h1>

	<?php if ( isset( $_REQUEST['settings-updated'] ) ) : ?>

		<?php add_settings_error( '', 'updated', __( 'Settings updated!' ), 'updated' ); ?>

	<?php endif; ?>
	
	<?php settings_errors(); ?>

	<style>
		.connect-button {
		    display: inline-block;
		    margin-bottom: 1px;
		    background-image: -webkit-gradient(linear,left top,left bottom,from(#28a0e5),to(#015e94));
		    background-image: linear-gradient(#28a0e5,#015e94);
		    -webkit-font-smoothing: antialiased;
		    border: 0;
		    padding: 1px;
		    height: 32px;
		    text-decoration: none;
		    border-radius: 4px;
		    -webkit-box-shadow: 0 1px 0 rgba(0,0,0,.2);
		    box-shadow: 0 1px 0 rgba(0,0,0,.2);
		    cursor: pointer;
		    -moz-user-select: none;
		    -webkit-user-select: none;
		    -ms-user-select: none;
		    user-select: none;
		    text-decoration: none!important;
		}

		.connect-button span {
			display: block;
			position: relative;
			padding: 0 12px;
			height: 30px;
			background: #1275ff;
			background-image: -webkit-gradient(linear,left top,left bottom,from(#7dc5ee),color-stop(85%,#008cdd),to(#30a2e4));
			background-image: linear-gradient(#7dc5ee,#008cdd 85%,#30a2e4);
			font-size: 15px;
			line-height: 30px;
			color: #fff;
			font-weight: 700;
			font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
			text-shadow: 0 -1px 0 rgba(0,0,0,.2);
			-webkit-box-shadow: inset 0 1px 0 hsla(0,0%,100%,.25);
			box-shadow: inset 0 1px 0 hsla(0,0%,100%,.25);
			border-radius: 3px;
			padding-left: 44px;
		}

		.connect-button span:before {
			background-image: url("data:image/png;base64,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");
			content: "";
			display: block;
			position: absolute;
			left: 11px;
			top: 50%;
			width: 23px;
			height: 24px;
			margin-top: -12px;
			background-repeat: no-repeat;
			background-size: 23px 24px;
		}

		@media only screen and (-webkit-min-device-pixel-ratio: 1.5), not all, not all { 
			.connect-button span:before {
				background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAC4AAAAwCAYAAABuZUjcAAAKRGlDQ1BJQ0MgUHJvZmlsZQAASA2dlndUFNcXx9/MbC+0XZYiZem9twWkLr1IlSYKy+4CS1nWZRewN0QFIoqICFYkKGLAaCgSK6JYCAgW7AEJIkoMRhEVlczGHPX3Oyf5/U7eH3c+8333nnfn3vvOGQAoASECYQ6sAEC2UCKO9PdmxsUnMPG9AAZEgAM2AHC4uaLQKL9ogK5AXzYzF3WS8V8LAuD1LYBaAK5bBIQzmX/p/+9DkSsSSwCAwtEAOx4/l4tyIcpZ+RKRTJ9EmZ6SKWMYI2MxmiDKqjJO+8Tmf/p8Yk8Z87KFPNRHlrOIl82TcRfKG/OkfJSREJSL8gT8fJRvoKyfJc0WoPwGZXo2n5MLAIYi0yV8bjrK1ihTxNGRbJTnAkCgpH3FKV+xhF+A5gkAO0e0RCxIS5cwjbkmTBtnZxYzgJ+fxZdILMI53EyOmMdk52SLOMIlAHz6ZlkUUJLVlokW2dHG2dHRwtYSLf/n9Y+bn73+GWS9/eTxMuLPnkGMni/al9gvWk4tAKwptDZbvmgpOwFoWw+A6t0vmv4+AOQLAWjt++p7GLJ5SZdIRC5WVvn5+ZYCPtdSVtDP6386fPb8e/jqPEvZeZ9rx/Thp3KkWRKmrKjcnKwcqZiZK+Jw+UyL/x7ifx34VVpf5WEeyU/li/lC9KgYdMoEwjS03UKeQCLIETIFwr/r8L8M+yoHGX6aaxRodR8BPckSKPTRAfJrD8DQyABJ3IPuQJ/7FkKMAbKbF6s99mnuUUb3/7T/YeAy9BXOFaQxZTI7MprJlYrzZIzeCZnBAhKQB3SgBrSAHjAGFsAWOAFX4Al8QRAIA9EgHiwCXJAOsoEY5IPlYA0oAiVgC9gOqsFeUAcaQBM4BtrASXAOXARXwTVwE9wDQ2AUPAOT4DWYgSAID1EhGqQGaUMGkBlkC7Egd8gXCoEioXgoGUqDhJAUWg6tg0qgcqga2g81QN9DJ6Bz0GWoH7oDDUPj0O/QOxiBKTAd1oQNYSuYBXvBwXA0vBBOgxfDS+FCeDNcBdfCR+BW+Bx8Fb4JD8HP4CkEIGSEgeggFggLYSNhSAKSioiRlUgxUonUIk1IB9KNXEeGkAnkLQaHoWGYGAuMKyYAMx/DxSzGrMSUYqoxhzCtmC7MdcwwZhLzEUvFamDNsC7YQGwcNg2bjy3CVmLrsS3YC9ib2FHsaxwOx8AZ4ZxwAbh4XAZuGa4UtxvXjDuL68eN4KbweLwa3gzvhg/Dc/ASfBF+J/4I/gx+AD+Kf0MgE7QJtgQ/QgJBSFhLqCQcJpwmDBDGCDNEBaIB0YUYRuQRlxDLiHXEDmIfcZQ4Q1IkGZHcSNGkDNIaUhWpiXSBdJ/0kkwm65KdyRFkAXk1uYp8lHyJPEx+S1GimFLYlESKlLKZcpBylnKH8pJKpRpSPakJVAl1M7WBep76kPpGjiZnKRcox5NbJVcj1yo3IPdcnihvIO8lv0h+qXyl/HH5PvkJBaKCoQJbgaOwUqFG4YTCoMKUIk3RRjFMMVuxVPGw4mXFJ0p4JUMlXyWeUqHSAaXzSiM0hKZHY9O4tHW0OtoF2igdRzeiB9Iz6CX07+i99EllJWV75RjlAuUa5VPKQwyEYcgIZGQxyhjHGLcY71Q0VbxU+CqbVJpUBlSmVeeoeqryVYtVm1Vvqr5TY6r5qmWqbVVrU3ugjlE3VY9Qz1ffo35BfWIOfY7rHO6c4jnH5tzVgDVMNSI1lmkc0OjRmNLU0vTXFGnu1DyvOaHF0PLUytCq0DqtNa5N03bXFmhXaJ/RfspUZnoxs5hVzC7mpI6GToCOVGe/Tq/OjK6R7nzdtbrNug/0SHosvVS9Cr1OvUl9bf1Q/eX6jfp3DYgGLIN0gx0G3QbThkaGsYYbDNsMnxipGgUaLTVqNLpvTDX2MF5sXGt8wwRnwjLJNNltcs0UNnUwTTetMe0zg80czQRmu836zbHmzuZC81rzQQuKhZdFnkWjxbAlwzLEcq1lm+VzK32rBKutVt1WH60drLOs66zv2SjZBNmstemw+d3W1JZrW2N7w45q52e3yq7d7oW9mT3ffo/9bQeaQ6jDBodOhw+OTo5ixybHcSd9p2SnXU6DLDornFXKuuSMdfZ2XuV80vmti6OLxOWYy2+uFq6Zroddn8w1msufWzd3xE3XjeO2323Ineme7L7PfchDx4PjUevxyFPPk+dZ7znmZeKV4XXE67m3tbfYu8V7mu3CXsE+64P4+PsU+/T6KvnO9632fein65fm1+g36e/gv8z/bAA2IDhga8BgoGYgN7AhcDLIKWhFUFcwJTgquDr4UYhpiDikIxQODQrdFnp/nsE84by2MBAWGLYt7EG4Ufji8B8jcBHhETURjyNtIpdHdkfRopKiDke9jvaOLou+N994vnR+Z4x8TGJMQ8x0rE9seexQnFXcirir8erxgvj2BHxCTEJ9wtQC3wXbF4wmOiQWJd5aaLSwYOHlReqLshadSpJP4iQdT8YmxyYfTn7PCePUcqZSAlN2pUxy2dwd3Gc8T14Fb5zvxi/nj6W6pZanPklzS9uWNp7ukV6ZPiFgC6oFLzICMvZmTGeGZR7MnM2KzWrOJmQnZ58QKgkzhV05WjkFOf0iM1GRaGixy+LtiyfFweL6XCh3YW67hI7+TPVIjaXrpcN57nk1eW/yY/KPFygWCAt6lpgu2bRkbKnf0m+XYZZxl3Uu11m+ZvnwCq8V+1dCK1NWdq7SW1W4anS1/+pDa0hrMtf8tNZ6bfnaV+ti13UUahauLhxZ77++sUiuSFw0uMF1w96NmI2Cjb2b7Dbt3PSxmFd8pcS6pLLkfSm39Mo3Nt9UfTO7OXVzb5lj2Z4tuC3CLbe2emw9VK5YvrR8ZFvottYKZkVxxavtSdsvV9pX7t1B2iHdMVQVUtW+U3/nlp3vq9Orb9Z41zTv0ti1adf0bt7ugT2ee5r2au4t2ftun2Df7f3++1trDWsrD+AO5B14XBdT1/0t69uGevX6kvoPB4UHhw5FHupqcGpoOKxxuKwRbpQ2jh9JPHLtO5/v2pssmvY3M5pLjoKj0qNPv0/+/tax4GOdx1nHm34w+GFXC62luBVqXdI62ZbeNtQe395/IuhEZ4drR8uPlj8ePKlzsuaU8qmy06TThadnzyw9M3VWdHbiXNq5kc6kznvn487f6Iro6r0QfOHSRb+L57u9us9ccrt08rLL5RNXWFfarjpebe1x6Gn5yeGnll7H3tY+p772a87XOvrn9p8e8Bg4d93n+sUbgTeu3px3s//W/Fu3BxMHh27zbj+5k3Xnxd28uzP3Vt/H3i9+oPCg8qHGw9qfTX5uHnIcOjXsM9zzKOrRvRHuyLNfcn95P1r4mPq4ckx7rOGJ7ZOT437j154ueDr6TPRsZqLoV8Vfdz03fv7Db56/9UzGTY6+EL+Y/b30pdrLg6/sX3VOhU89fJ39ema6+I3am0NvWW+738W+G5vJf49/X/XB5EPHx+CP92ezZ2f/AAOY8/wRDtFgAAAIbklEQVRoBdVZa5BURxU+fZ9z57mzs7PvF4i7srAQSCifMVDERC0jYlzUlJalKeGPlCnL/NEfywpWacoiVZRVJIYfGjGUu5bxj5qHFSAYyQOBEsJzYSHDvnd2dp535j66PX1vNgsULDPs1cr2Vs+9e7v79NfnnnP663MJYwwWYxEWI2iOedEClxabxgkBwjEvOuA9PQOOlSw64JMr4vK8GidYYMcOES4tVSEAAZ8FAUqon1GiAJEEEG0CjFB8cTaxZUMAo1gEqQA0UABprAjPbrUwXnkesgqKP8CBk5vDIenrE+BKmwI+MawA1MbCkdV10cBDflXuVmSxQRbFkCAQZ9U2ZTaONyxKcyXDHjMs83ImV3rz6njmDRPMUZB80zAJOuvvsflkXpTP7DrWyeXcYCqk75AEieawrEoty1vrvlcV0ja3VQdb1rVUQVd9EFqqNIj5ZfDJooPBsCnohq2ldDMynC42XZnW7z09lu25lMxDMl34y0gyvTsBwyewc84Z4MEPpWIzF/MBcLLtNzJISmxZU+PmWETbtqGzfvVja5uguyF02+kCIEJUk6Ex4oMV9XP9ZnQT/nZ24it7XrtoJ5LZ7SjAM+Bg2+0ckAOcbBkQIaZFVzY1bGurjezYfn87PNQZ5+13ZaQRXMzH26Lg8ymfUokQdAR59INOc53GQ6q/Jiiua6oJ7+h9uAPua47cHeLrwHEmQRmTGLHV6x4v+JYwWsOFCGRDn6RKem1rPPrkN9Y0uqAXLN4VwCgjYGEE8rBgMAjwKsF9S9WgLa9qjYcf+Po9jXdlGrfC5Wj8Vg0Lf+ZENAFmpGB9TWTLhmUxUD1UDg/gtudRnK+a4RtkgqQyO+RT5LVrmiLgJcN19gcGNojUWriS5yRQm7pcBTc/vyCKdW1RrWwzOTiYhGf+dRUmcgZosgDVfgWaMCS2V2tO+OzG0MiVjdUwiFiYm9a7O4kJAoZEooV9H4T0O0ofODkKr5+6+nY6V3heVZQpv6ZWaz55qSJJnXjtUBW5pT7k8xeK5u+B0PQdBVbQgTLq9HbQYthyNVSmTT6A/nB0aGpF0K99+trY1F7TNI9PZGXkKUVRtYjGZCIOV1dHR4Ynz8FSLV8BrjK6uiAlpLcmco1ipmgpAaU8rfesboCuumBg31uJbx6+qH0uX9D/em0i85xFhaslKZKA8/82RtYDhd/1MkCuBnjxrLgKB0EQSb5oWO+9O1bZrsy3+Kc3dcH+b99b07NuyXe6P9r8z/am+C9lkuqCjo4qGGkQES76qJcuz/2GOlUoFuVsQS+98frlaSeq8Gkqqctrg7Dz853wwrfugUfXtj3W3tJ8oCletRUEXy1SCSSYHhdu41gFqILcZCrzwkvnJmE0U3JtHefiL7eS2l7th11f7IQ9j65aVh+r+nlzbd2TELJrHPLmIXZX3wyBX8MTQMm8PJ0u9Pe9chGQYy9omvXouHu/thJqI+Ef1sZDm0AMBmfPiQsSPDuY2zhWwSH5ISU5Pjm98x9nRo7+7JVBB3wl5nJz35Vo/z/esBQUVf2+QlkD9Aw42/Ts3Au7ushdAhQ5UzJoOjE+OrV9/1tDR7cNnIax7N2bDX9nm1bUQXdz9Rp/MLwRoqAtDOzcaO7rvDrAWW8vhcatWVNjF6cmJre9embkz1947h3YfXgIUgVzblQldxgFH0ZOr/qULwM15k4Zlci4Vd9ZU5ltY71oObHBnBFQBidmUk8kEsOP7Hntwqsb974NfS8PAh7LKoo23Hw+2R4FQcSzKlDPgFOEyf8kx3HW94kQ7xJgRRdAJG7CyIWxgiXNUN0+k5nJLN83k3n8D8eHN3+1ux5+8uBHIKiWt1G1Rn3IJkiUCcQzU3G0h9qWHMeJdoSrwtr9dl6I6DNjFwRRyxiKnStSqkPJPsGSmZ+mp1P9z2dzOy3Klj31yMdmX9S8V75APEsomMZwT9fz9i6vkW9AvEgQyqrBQM2Dq9rrD0gCgXfHA0jpjIRm2Zcw+3CR2tZl27SnMZFSZ1lWcRwZITeDckresAEXaoKwwBh7/WQubgTOQj5BVjdv7KiBJz7bztMNcHIk03JiONNyfiK/ntv2VMHAMx6BjpoA/Gj9Emdjul7W7e6TeQNDK9WJLRm361P5c1drEmAaymaYoXpfjZoiOk7FHWuh5dxEHmzLHiXM9oyTz9FawRZw65f5yyzXBMpd0JGhFKB5nSwRMVvumDv2cxm4m1f5X4AuWhRePDUOtqEPQJVVGfWcBz1ahmPlTlxzqaJLquYZU1HTvjcTMD6dOULM0n+g5nKposHzdWbo7FgEkDBviWlYx++53XtQ33kvDU8dHAJm6L8usdwEZn09S3qiPed5lcCSLUpI0eEA8620zLbDl6bh8T+egkI+/7Rl6kegcTSPst1QUKaM+brhrjnF2yUQJNxnrGMnR7KbTw5nYFVjyAl98w2+VdvVlA67Dw3BgROjAKa+yyrpz0BKTbJnez1NT6AKrrnA1bEi1av2v3xaiL90dnxL2Kc0rsXc4WpcQEc8AEtiGrRiejmK6WWeMDIxtVwwKExijB5KFuBYIg1cy8dx0dTQ/yQVc78yBXMIqJ5i/VvvkqHdSjXuM/THKy7w2LQJ6fpJms38QiHGvlzBt+RwJv2JQ2elbjyRtjIi1AIRMAsKPuQduHVzr2YW+kIBE5BTwOzzxLKOiMX8QVuWh00IpqD+S0WHtLlzefpLBOZo/IYvEqQPnTX5dxmy4xookqaCjRuT4mMi8g3bxs2KCkj3GFj4+QSzA0RkeskU8iCJeUiBDv09Jt8OPEV6k7DlP3gxxh/dAPymPh/Kf5d897dIOd9P7H8oEd4G1JV8wPGbRadx52sgLmrRAZ99EZ5+LZgV+v+4Llrg/wX6HRCxgvzAAwAAAABJRU5ErkJggg==");
			}		    
		}
	</style>

	<form name="emep_settings" id="emep_settings" method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
		
		<div id="poststuff">

			 <div id="post-body" class="metabox-holder"> 

				<table class="form-table" role="presentation">
					<tbody>
						<?php do_action( 'emep_settings_table_open' ); ?>
						<tr>
							<th scope="row"><?php _e( 'Connect to Stripe', 'payments-for-elementor' ); ?></th>
							<td>
								<?php if ( 'connected' !== emep_get_option( 'emep_stripe_connect_status' ) ) : ?>
									<p>
										<a href="<?php echo $stripe_connect_url; ?>" class="connect-button">
											<span><?php _e( 'Connect with Stripe', 'payments-for-elementor' ); ?></span>
										</a>
									</p>
									<p>
										<span class="description"><?php _e( 'Connect your website with your Stripe account.', 'payments-for-elementor' ); ?></span>
									</p>
								<?php else : ?>
									<div class="notice notice-success inline">
										<p>
											<?php _e( 'Your website is successfully connected to your Stripe account.', 'payments-for-elementor' ); ?>
										</p>
									</div>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="emep_test_mode"><?php _e( 'Test Mode' ); ?></label></th>
							<td>
								<?php if ( false === emep_is_ssl() ) : ?>
									<div class="notice notice-warning inline">
										<p>
											<?php _e( 'Your website is not configured to use SSL encryption. To process live payments, you will need to install SSL on your website.', 'emdisp-divi-stripe-payments' ); ?>
										</p>
									</div>
								<?php endif; ?>
									<input type="checkbox" id="emep_test_mode" name="emep_test_mode" value="1" <?php echo emep_is_ssl() ? '' : 'disabled="disabled"'; ?> <?php checked( ! emep_is_ssl() || emep_get_option( 'emep_test_mode' ) ); ?>>
									<label for="emep_test_mode"><?php _e( 'Enable Test Mode' ); ?>
								</p>
								<p>
									<span class="description"><?php _e( 'In test mode, no actual payments will be processed. Test payments can be viewed in your Stripe account.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="emep_stripe_statement_descriptor"><?php _e( 'Statement Descriptor', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_stripe_statement_descriptor" type="text" id="emep_stripe_statement_descriptor" value="<?php esc_attr_e( emep_get_option( 'emep_stripe_statement_descriptor' ) ); ?>" class="regular-text" maxlegth="22">
								</p>
								<p>
									<span class="description"><?php _e( 'The statement descriptor appears on customer statements whenever you charge their card.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr <?php echo isset( $_GET['display-keys'] ) ? '' : 'style="display: none;"'; ?>>
							<th scope="row"><label for="emep_stripe_live_pub_key"><?php _e( 'Live Publishable Key', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_stripe_live_pub_key" type="text" id="emep_stripe_live_pub_key" value="<?php esc_attr_e( emep_get_option( 'emep_stripe_live_pub_key' ) ); ?>" class="regular-text">
								</p>
								<p>
									<span class="description"><?php _e( 'Live publishable key of your Stripe account.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr <?php echo isset( $_GET['display-keys'] ) ? '' : 'style="display: none;"'; ?>>
							<th scope="row"><label for="emep_stripe_live_secret_key"><?php _e( 'Live Secret Key', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_stripe_live_secret_key" type="text" id="emep_stripe_live_secret_key" value="<?php esc_attr_e( emep_get_option( 'emep_stripe_live_secret_key' ) ); ?>" class="regular-text">
								</p>
								<p>
									<span class="description"><?php _e( 'Live secret key of your Stripe account.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr <?php echo isset( $_GET['display-keys'] ) ? '' : 'style="display: none;"'; ?>>
							<th scope="row"><label for="emep_stripe_test_pub_key"><?php _e( 'Test Publishable Key', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_stripe_test_pub_key" type="text" id="emep_stripe_test_pub_key" value="<?php esc_attr_e( emep_get_option( 'emep_stripe_test_pub_key' ) ); ?>" class="regular-text">
								</p>
								<p>
									<span class="description"><?php _e( 'Test publishable key of your Stripe account.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr <?php echo isset( $_GET['display-keys'] ) ? '' : 'style="display: none;"'; ?>>
							<th scope="row"><label for="emep_stripe_test_secret_key"><?php _e( 'Test Secret Key', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_stripe_test_secret_key" type="text" id="emep_stripe_test_secret_key" value="<?php esc_attr_e( emep_get_option( 'emep_stripe_test_secret_key' ) ); ?>" class="regular-text">
								</p>
								<p>
									<span class="description"><?php _e( 'Test secret key of your Stripe account.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="emep_currency"><?php _e( 'Currency', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<select name="emep_currency" id="emep_currency">
										<?php foreach ( emep_get_currencies() as $code => $name ) : ?>
											<option value="<?php esc_attr_e( $code ); ?>" <?php selected( emep_get_option( 'emep_currency' ), $code ); ?>><?php echo $name . ' (' . emep_get_currency_symbol( $code ) . ')'; ?></option>
										<?php endforeach; ?>
									</select>
								</p>
								<p>
									<span class="description"><?php _e( 'Select your preferred currency. Not all currencies are supported by Stripe.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="emep_currency_position"><?php _e( 'Currency Position', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<select name="emep_currency_position" id="emep_currency_position">
										<option value="left" <?php selected( emep_get_option( 'emep_currency_position' ), 'left' ); ?>><?php _e( 'Left', 'payments-for-elementor' ); ?></option>
										<option value="right" <?php selected( emep_get_option( 'emep_currency_position' ), 'right' ); ?>><?php _e( 'Right', 'payments-for-elementor' ); ?></option>
										<option value="left_space" <?php selected( emep_get_option( 'emep_currency_position' ), 'left_space' ); ?>><?php _e( 'Left with space', 'payments-for-elementor' ); ?></option>
										<option value="right_space" <?php selected( emep_get_option( 'emep_currency_position' ), 'right_space' ); ?>><?php _e( 'Right with space', 'payments-for-elementor' ); ?></option>
									</select>
								</p>
								<p>
									<span class="description"><?php _e( 'Position of the currency symbol.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="emep_thousand_sep"><?php _e( 'Thousand Separator', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_thousand_sep" type="text" id="emep_thousand_sep" value="<?php esc_attr_e( emep_get_option( 'emep_thousand_sep', ',' ) ); ?>" class="small-text">
								</p>
								<p>
									<span class="description"><?php _e( 'Character to separate thousand values (e.g. 1,000).', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="emep_decimal_sep"><?php _e( 'Decimal Separator', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_decimal_sep" type="text" id="emep_decimal_sep" value="<?php esc_attr_e( emep_get_option( 'emep_decimal_sep', '.' ) ); ?>" class="small-text">
								</p>
								<p>
									<span class="description"><?php _e( 'Character to separate decimal values (e.g. 10.50).', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="emep_decimals"><?php _e( 'Number of Decimals', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_decimals" type="number" id="emep_decimals" value="<?php esc_attr_e( emep_get_option( 'emep_decimals', 2 ) ); ?>" class="small-text">
								</p>
								<p>
									<span class="description"><?php _e( 'Number of decimals to display.', 'payments-for-elementor' ); ?></span>
								</p>
							</td>
						</tr>
						<?php if ( empty( emep_get_option( 'emep_stripe_live_webhook_id' ) ) || empty( emep_get_option( 'emep_stripe_test_webhook_id' ) ) ) : ?>
							<tr>
								<th scope="row"><?php _e( 'Webhook URL', 'payments-for-elementor' ); ?></th>
								<td>
									<p>
										<pre><?php echo EMEP_Webhooks::get_webhook_url(); ?></pre>
									</p>
									<p>
										<span class="description"><?php _e( 'Add this webhook URL to your <a href="https://dashboard.stripe.com/webhooks" target="_blank">Stripe webhooks</a>. The Webhook URL allows Payments for Elementor to process and record payments completed in Stripe. Copy the Webhook URL, and add it to your Stripe settings.', 'payments-for-elementor' ); ?></span>
									</p>
								</td>
							</tr>
						<?php endif; ?>
						<tr>
							<th scope="row"><label for="emep_license_key"><?php _e( 'License Key', 'payments-for-elementor' ); ?></label></th>
							<td>
								<p>
									<input name="emep_license_key" type="text" id="emep_license_key" value="<?php esc_attr_e( emep_get_option( 'emep_license_key' ) ); ?>" class="regular-text">
								</p>
							</td>
						</tr>
						<?php do_action( 'emep_settings_table_close' ); ?>
					</tbody>
				</table>

				<input type="hidden" name="action" value="emep_settings">

				<?php wp_nonce_field( 'emep_settings' ); ?>

				<?php submit_button( __( 'Save Settings', 'payments-for-elementor' ) ); ?>

			 </div> <!-- #post-body -->
		
		</div> <!-- #poststuff -->

	</form>

</div><!-- .wrap -->