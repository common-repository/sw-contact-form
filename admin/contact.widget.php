<?php
Class contactFormWidget extends WP_Widget{
function __construct() {
parent::__construct(
'SideMenuWidget', 

__('Sidebar SW Contact Form', 'wp_load_contact_form_widget'), 

array( 'description' => __( 'setup contact form to sidebar ', 'wp_load_contact_form_widget' ), ) 
);
}
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
$frontend = new FormFrontend();
$frontend->showForm("widget");
include_once(SWCF_PATH.'/js/frontend.js.php');
?>
<?php
echo $args['after_widget'];
}		
public function form( $instance ) {
	if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Contact Form', 'wp_load_contact_form_widget' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
}
function wp_load_contact_form_widget() {
	register_widget( 'contactFormWidget' );
	
}
add_action( 'widgets_init', 'wp_load_contact_form_widget' );
?>