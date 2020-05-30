<?php
/**
 * Plugin Name: Image Page Widget
 * Description: Easily add a background image to a widget with a link to the page you choose
 * Version: 1.0.0
 */

/**
 * Do not load this file directly.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Adds widget.
 */
class img_page_widget extends WP_Widget {

		/**
	 * Register widget with WordPress.
	 */

	public function __construct(){	
		
		parent::__construct( 'image_page_widget', 'Image Page Widget', array(
			'classname' => 'img_page_widget',
			'description' => __('Easily add a background image to a widget with a link to the page you choose'))
		);
		add_action( 'admin_enqueue_scripts', array( $this, 'image_page_assets' ) );

		
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */

	public function image_page_assets(){

		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_script('mfc-media-upload', plugin_dir_url(__FILE__) . 'js/mfc-media-upload.js', 
		array( 'jquery' ), true);
		wp_enqueue_style('thickbox');

		wp_enqueue_style( 'wp-color-picker' );

		    wp_enqueue_script(
		        'iris',
		        admin_url( 'js/iris.min.js' ),
		        array( 
		            'jquery-ui-draggable', 
		            'jquery-ui-slider', 
		            'jquery-touch-punch'
		        ),
		        false,
		        1
		    );

			// Now we can enqueue the color-picker script itself, 
		    //    naming iris.js as its dependency
		    wp_enqueue_script(
		        'wp-color-picker',
		        admin_url( 'js/color-picker.min.js' ),
		        array( 'iris' ),
		        false,
		        1
		    );

		    // Manually passing text strings to the JavaScript
		    $colorpicker_l10n = array(
		        'clear' => __( 'Clear' ),
		        'defaultString' => __( 'Default' ),
		        'pick' => __( 'Select Color' ),
		        'current' => __( 'Current Color' ),
		    );
		    wp_localize_script( 
		        'wp-color-picker',
		        'wpColorPickerL10n', 
		        $colorpicker_l10n 
		    ); 


	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance){
		
		$instance = $new_instance;

		$instance['background_color'] = $new_instance['background_color'];
		$instance['font_color'] = $new_instance['font_color'];

		return $new_instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */

	public function form($instance){

		$title = '';
		$image = '';
		$link_text = '';
		$page_id = '';
		$description = '';
		$image_id = '';
		$file = '';
		$file_id = '';

		if( !empty( $instance['title'] ) ) { $title = $instance['title']; }
		if( !empty( $instance['image'] ) ) { $image = $instance['image']; }
		if( !empty( $instance['image_id'] ) ) { $image_id = $instance['image_id']; }
		if( !empty( $instance['file'] ) ) { $file = $instance['file']; }
		if( !empty( $instance['file_id'] ) ) { $file_id = $instance['file_id']; }
		if( !empty( $instance['link_text'] ) ) { $link_text = $instance['link_text']; }
		if( !empty( $instance['page_id'] ) ) { $page_id = $instance['page_id']; } else { $page_id = 0; }
		if( !empty( $instance['description'] ) ) { $description = $instance['description']; }

	?>

	<div id="page_widget">
	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('title')); ?>">
				<?php _e('Title:'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr_e( $title ); ?>"/>
	</p>
	
	<p>
	<label for="<?php echo esc_attr( $this->get_field_name('page_id')); ?>">
				<?php _e('Linked Page:'); ?>
	</label>
	</br>
	<?php 

		$args = array(
			'id' => $this->get_field_id('page_id'),
			'name' => $this->get_field_name('page_id'),
			'selected' => $page_id,
			'show_option_none' => 'Please select a page'
			);

		wp_dropdown_pages($args);

	?>

	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('file')); ?>">
				<?php _e('Linked File:'); ?>
		</label>
		<input class="file widefat" id="<?php echo esc_attr( $this->get_field_id( 'file' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'file' ) ); ?>" type="text" value="<?php echo esc_attr_e( $file ); ?>"/>
		<input class="file_id widefat" id="<?php echo esc_attr( $this->get_field_id( 'file_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'file_id' ) ); ?>" type="text" value="<?php echo esc_attr_e( $file_id ); ?>"/>
		<input type="button" class="select-file" id="<?php echo esc_attr( $this->get_field_id( 'select_file' ) ); ?>" value="Select File" />

	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('image')); ?>">
				<?php _e('Background Image:'); ?>
		</label>
		<input class="img image-page-input widefat" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" type="text" value="<?php echo esc_attr_e( $image ); ?>"/>
		<input class="img_id widefat" id="<?php echo esc_attr( $this->get_field_id( 'image_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image_id' ) ); ?>" type="text" value="<?php echo esc_attr_e( $image_id ); ?>"/>
		<input type="button" class="select-img" id="<?php echo esc_attr( $this->get_field_id( 'select_img' ) ); ?>" value="Select Image" />

		<div id="upload_img_preview" style="min-height: 100px;">
			<img style="max-width: 100%" src="<?php echo esc_url( $image ); ?>" />
		</div>

	</p>

	<p>
	<?php
		$defaults = array(
            'background_color' => '#e3e3e3',
            'font_color' => '#ffffff'
        );

        // Merge the user-selected arguments with the defaults
        $instance = wp_parse_args( (array) $instance, $defaults );

         ?> 

        <script type='text/javascript'>

        	jQuery(document).ready(function($) { 
	            jQuery('.my-color-picker').on('focus', function(){
	            	var params = {
	        			change: function(e, ui){
	        				jQuery(this).val( ui.color.toString() );
	        				jQuery(this).trigger('change');
	        			},
	        		}
	                jQuery(this).wpColorPicker(params);	                
			    }); 
		    }); 
       
        </script> 
        <br>
		 <label for="<?php echo esc_attr( $this->get_field_name('background_color')); ?>"><?php _e( 'Background Color:' ); ?></label>
        <br>
        <input class="my-color-picker" type="text" id="<?php echo $this->get_field_id( 'background_color' ); ?>" name="<?php echo $this->get_field_name( 'background_color' ); ?>" 
        value="<?php echo esc_attr( $instance['background_color'] ); ?>" /> 
        <br>
		 <label for="<?php echo esc_attr( $this->get_field_name('font_color')); ?>"><?php _e( 'Font Color:' ); ?></label>
        <br>
        <input class="my-color-picker" type="text" id="<?php echo $this->get_field_id( 'font_color' ); ?>" name="<?php echo $this->get_field_name( 'font_color' ); ?>" 
        value="<?php echo esc_attr( $instance['font_color'] ); ?>" /> 

	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('description')); ?>">
				<?php _e('Page Description:'); ?>
		</label>
		<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_attr_e( $description ); ?></textarea>
	</p>

	<p>
		<label for="<?php echo esc_attr( $this->get_field_name('link_text')); ?>">
				<?php _e('Page Link text:'); ?>
		</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'link_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link_text' ) ); ?>" type="text" value="<?php echo esc_attr_e( $link_text ); ?>"/>
	</p>
	</div>


	<?php

		}

	public function widget($args, $instance){

	echo $args['before_widget'];

	$page = get_post($instance['page_id']);

    $image_media_id = $instance['image_id'];

    ?>
		

			<?php $parsed_url = esc_url( wp_get_attachment_url($image_media_id) ); ?>

			<div class="widget-content">

							<h3 class="title-medium title-shadow-a mb10" style="color: <?php echo esc_attr_e( $instance['font_color'] );  ?>;">
							<?php if( !empty( $instance['title'] ) ) { echo esc_attr_e($instance['title']); } else { echo $page->post_title; }  ?>											
							</h3>

							<p style="color: <?php echo esc_attr_e( $instance['font_color'] );  ?>;">
							<?php if( !empty( $instance['description'] ) ){ echo esc_attr_e( $instance['description'] ); } else { echo $page->post_excerpt; } ?>
							</p>

							<button class="button" onclick="location.href='<?php

							if( !empty( $instance['page_id'] ) ) { 

									echo get_permalink($page->ID); 

								} else if( !empty( $instance['file'] ) )  {

									echo esc_attr_e($instance['file']);

								} else {

									_e('#');

							}


							?>'"><?php
							
							if( !empty( $instance['link_text'] ) ) { echo esc_attr_e($instance['link_text']); } 

							?></button> 

			</div>

			<div class="background-overlay" style="background-color: <?php echo esc_attr_e( $instance['background_color'] );  ?>;"></div>

			<div class="box-widget" style="background-image: url('<?php echo $parsed_url; ?>'); ">

			</div>




	

	<?php

			echo $args['after_widget'];

	}

}

add_action( 'admin_init','callback_for_setting_up_scripts');
add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');

function callback_for_setting_up_scripts() {

		wp_register_style( 'page-widget-style', plugins_url( 'css/image-page-widget.css', __FILE__));
	    wp_enqueue_style( 'page-widget-style' );

}

add_action( 'widgets_init',	function(){
	return register_widget("img_page_widget");
});

?>