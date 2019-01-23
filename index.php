<?php
/*
Plugin Name: Cms assignment
Plugin URI: 
Description: Search Bar based on Categories ;)
Version: 1.3
Author: Dinesh Paradesi V jc477161
Author URI:
*/
class blog_search_Widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'blog_search', 
			'Search Assist', 
			array( 'classname' => 'widget_search','description' => __( 'A simple search.', 'text_domain' ), ) // Arguments submitted here
		);
	}

	public function widget( $args, $instance ) {
	 extract( $args );
	 $title = apply_filters( 'widget_title', $instance['title'] );
	 $show_hierarchy = ! empty( $instance['show_hierarchy'] ) ? '1' : '0';
 	 $default_cat    = isset( $instance['default_cat'] ) ? absint( $instance['default_cat'] ) : 0;
     if(isset($_GET['cat']))
     $default_cat =absint($_GET['cat']);
	 $cat_args = array('selected'=>$default_cat, 'hierarchical' => $show_hierarchy,'show_option_all'=>$default_select_text,'echo'=>0,'id'=>'searchform_cat','orderby'=>'name','order' => 'asc');
     ?>
	 <?php echo $before_widget; ?>
	 <?php if ( $title ) echo $before_title . $title . $after_title; ?>
	 
	 <?php 		 
	   $form= '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" />
    '.wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args)).'
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</div>
	</form>';
	echo apply_filters('get_search_form', $form);
    ?>
    
		
	 <?php echo $after_widget; ?>
     <?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['default_select_text'] = 'Any Category';
    	$instance['default_cat'] = ($new_instance['default_cat']);
		$instance['show_hierarchy'] = !empty($new_instance['show_hierarchy']) ? 1 : 0;
		return $instance;
	}
	
	public function form( $instance ) {
 	$title = $instance['title'];
	  $default_select_text = __( 'Any Category', 'text_domain' );
 	  $show_hierarchy = isset( $instance['show_hierarchy'] ) ? (bool) $instance['show_hierarchy'] : true;
	 $default_cat    = isset($instance['default_cat'] ) ? absint($instance['default_cat']) : 0;

	?>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	   <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	 </p>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'default_cat' ); ?>"><?php _e( 'Default select category :' ); ?></label> 
	   <?php $cat_arg = array( 'hierarchical' => 1,'selected'=>$default_cat,'show_option_all'=>($default_select_text!='')?esc_attr( $default_select_text):esc_attr( $default_select_text),'echo'=>0,'id'=>$this->get_field_id( 'default_cat' ),'name'=>$this->get_field_name( 'default_cat' ),'exclude'=>$exclude,'class'=>'widefat');
          echo wp_dropdown_categories($cat_arg);?>
	 </p>	 
	 <p>
	  <input class="checkbox" type="checkbox" <?php checked( $show_hierarchy ); ?> id="<?php echo $this->get_field_id( 'show_hierarchy' ); ?>" name="<?php echo $this->get_field_name( 'show_hierarchy' ); ?>" />
	  <label for="<?php echo $this->get_field_id( 'show_hierarchy' ); ?>"><?php _e( 'Show hierarchy' ); ?></label><br />
	 </p>
	 <?php 
	}
} 
add_action( 'widgets_init', create_function( '', 'register_widget( "blog_search_Widget" );' ) );
register_deactivation_hook(__FILE__, 'search_deactivate');
function search_deactivate ()
{
 unregister_widget('blog_search_Widget');
}
?>