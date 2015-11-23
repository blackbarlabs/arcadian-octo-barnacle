<?php
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles',99);
function child_enqueue_styles() {
    $parent_style = 'parent-style';
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	 wp_enqueue_style( 'child-style',get_stylesheet_directory_uri() . '/custom.css', array( $parent_style ));
}
if ( get_stylesheet() !== get_template() ) {
    add_filter( 'pre_update_option_theme_mods_' . get_stylesheet(), function ( $value, $old_value ) {
         update_option( 'theme_mods_' . get_template(), $value );
         return $old_value; // prevent update to child theme mods
    }, 10, 2 );
    add_filter( 'pre_option_theme_mods_' . get_stylesheet(), function ( $default ) {
        return get_option( 'theme_mods_' . get_template(), $default );
    } );
}
?>


/*****************************************/
/******          WIDGETS     *************/
/*****************************************/

add_action('widgets_init', 'aob_register_widgets');


function aob_register_widgets() {    

	register_widget('aob_team_widget');
}

/****************************/
/****** team member widget **/
/***************************/

add_action('admin_enqueue_scripts', 'aob_team_widget_scripts');

function aob_team_widget_scripts() {    

	wp_enqueue_media();

	//wp_enqueue_script('aob_team_widget_script', get_template_directory_uri() . '/js/widget-team.js', false, '1.0', true);

}

class aob_team_widget extends WP_Widget{	

	public function __construct() {
		parent::__construct(
			'zerif_team-widget',
			__( 'AOB - Team member widget', 'zerif-lite' )
		);
	}

    function widget($args, $instance) {

        extract($args);

        echo $before_widget;

        ?>

        <div class="col-lg-3 col-sm-3 team-box">

            <div class="team-member">

				<?php if( !empty($instance['image_uri']) ): ?>
				
					<figure class="profile-pic">

						<img src="<?php echo esc_url($instance['image_uri']); ?>" alt="<?php _e( 'Uploaded image', 'zerif-lite' ); ?>" />

					</figure>
				
				<?php endif; ?>

                <div class="member-details">

					<?php if( !empty($instance['name']) ): ?>
					
						<h3 class="dark-text red-border-bottom"><?php echo apply_filters('widget_title', $instance['name']); ?></h3>
						
					<?php endif; ?>	

					<?php if( !empty($instance['position']) ): ?>
					
						<div class="position"><?php echo htmlspecialchars_decode(apply_filters('widget_title', $instance['position'])); ?></div>
				
					<?php endif; ?>

                </div>

                <div class="social-icons">

                    <ul>
                        <?php
                            $zerif_team_target = '_self';
                            if( !empty($instance['open_new_window']) ):
                                $zerif_team_target = '_blank';
                            endif;
                        ?>

                        <?php if ( !empty($instance['fb_link']) ): ?>
                            <li><a href="<?php echo apply_filters('widget_title', $instance['fb_link']); ?>" target="<?php echo $zerif_team_target; ?>"><i
                                        class="fa fa-facebook"></i></a></li>
                        <?php endif; ?>

                        <?php if ( !empty($instance['tw_link']) ): ?>
                            <li><a href="<?php echo apply_filters('widget_title', $instance['tw_link']); ?>" target="<?php echo $zerif_team_target; ?>"><i
                                        class="fa fa-twitter"></i></a></li>
                        <?php endif; ?>

                        <?php if ( !empty($instance['bh_link']) ): ?>
                            <li><a href="<?php echo apply_filters('widget_title', $instance['bh_link']); ?>" target="<?php echo $zerif_team_target; ?>"><i
                                        class="fa fa-behance"></i></a></li>
                        <?php endif; ?>

                        <?php if ( !empty($instance['db_link']) ): ?>
                            <li><a href="<?php echo apply_filters('widget_title', $instance['db_link']); ?>" target="<?php echo $zerif_team_target; ?>"><i
                                        class="fa fa-dribbble"></i></a></li>
                        <?php endif; ?>
						
						<?php if ( !empty($instance['ln_link']) ): ?>
                            <li><a href="<?php echo apply_filters('widget_title', $instance['ln_link']); ?>" target="<?php echo $zerif_team_target; ?>"><i
                                        class="fa fa-linkedin"></i></a></li>
                        <?php endif; ?>

                    </ul>

                </div>

				<?php if( !empty($instance['description']) ): ?>
                <div class="details">

                    <?php echo htmlspecialchars_decode(apply_filters('widget_title', $instance['description'])); ?>

                </div>
				<?php endif; ?>

            </div>

        </div>

        <?php

        echo $after_widget;

    }

    function update($new_instance, $old_instance) {

        $instance = $old_instance;

        $instance['name'] = strip_tags($new_instance['name']);
        $instance['position'] = stripslashes(wp_filter_post_kses($new_instance['position']));
        $instance['description'] = stripslashes(wp_filter_post_kses($new_instance['description']));
        $instance['fb_link'] = strip_tags($new_instance['fb_link']);
        $instance['tw_link'] = strip_tags($new_instance['tw_link']);
        $instance['bh_link'] = strip_tags($new_instance['bh_link']);
        $instance['db_link'] = strip_tags($new_instance['db_link']);
		$instance['ln_link'] = strip_tags($new_instance['ln_link']);
        $instance['image_uri'] = strip_tags($new_instance['image_uri']);
        $instance['open_new_window'] = strip_tags($new_instance['open_new_window']);

        return $instance;

    }

    function form($instance) {

        ?>

        <p>
            <label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Name', 'zerif-lite'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('name'); ?>" id="<?php echo $this->get_field_id('name'); ?>" value="<?php if( !empty($instance['name']) ): echo $instance['name']; endif; ?>" class="widefat"/>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('position'); ?>"><?php _e('Position', 'zerif-lite'); ?></label><br/>
            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name('position'); ?>" id="<?php echo $this->get_field_id('position'); ?>"><?php if( !empty($instance['position']) ): echo htmlspecialchars_decode($instance['position']); endif; ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>"><?php _e('Description', 'zerif-lite'); ?></label><br/>
            <textarea class="widefat" rows="8" cols="20" name="<?php echo $this->get_field_name('description'); ?>"
                      id="<?php echo $this->get_field_id('description'); ?>"><?php
                if( !empty($instance['description']) ): echo htmlspecialchars_decode($instance['description']); endif;
            ?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('fb_link'); ?>"><?php _e('Facebook link', 'zerif-lite'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('fb_link'); ?>" id="<?php echo $this->get_field_id('fb_link'); ?>" value="<?php if( !empty($instance['fb_link']) ): echo $instance['fb_link']; endif; ?>" class="widefat">

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tw_link'); ?>"><?php _e('Twitter link', 'zerif-lite'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('tw_link'); ?>" id="<?php echo $this->get_field_id('tw_link'); ?>" value="<?php if( !empty($instance['tw_link']) ): echo $instance['tw_link']; endif; ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('bh_link'); ?>"><?php _e('Behance link', 'zerif-lite'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('bh_link'); ?>" id="<?php echo $this->get_field_id('bh_link'); ?>" value="<?php if( !empty($instance['bh_link']) ): echo $instance['bh_link']; endif; ?>" class="widefat">

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('db_link'); ?>"><?php _e('Dribble link', 'zerif-lite'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('db_link'); ?>" id="<?php echo $this->get_field_id('db_link'); ?>" value="<?php if( !empty($instance['db_link']) ): echo $instance['db_link']; endif; ?>" class="widefat">
        </p>
		<p>
            <label for="<?php echo $this->get_field_id('ln_link'); ?>"><?php _e('Linkedin link', 'zerif-lite'); ?></label><br/>
            <input type="text" name="<?php echo $this->get_field_name('ln_link'); ?>" id="<?php echo $this->get_field_id('ln_link'); ?>" value="<?php if( !empty($instance['ln_link']) ): echo $instance['ln_link']; endif; ?>" class="widefat">
        </p>
        <p>
            <input type="checkbox" name="<?php echo $this->get_field_name('open_new_window'); ?>" id="<?php echo $this->get_field_id('open_new_window'); ?>" <?php if( !empty($instance['open_new_window']) ): checked( (bool) $instance['open_new_window'], true ); endif; ?> ><?php _e( 'Open links in new window?','zerif-lite' ); ?><br>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_uri'); ?>"><?php _e('Image', 'zerif-lite'); ?></label><br/>

            <?php

            if ( !empty($instance['image_uri']) ) :

                echo '<img class="custom_media_image_team" src="' . $instance['image_uri'] . '" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" alt="'.__( 'Uploaded image', 'zerif-lite' ).'" /><br />';

            endif;

            ?>

            <input type="text" class="widefat custom_media_url_team" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php if( !empty($instance['image_uri']) ): echo $instance['image_uri']; endif; ?>" style="margin-top:5px;">
            <input type="button" class="button button-primary custom_media_button_team" id="custom_media_button_clients" name="<?php echo $this->get_field_name('image_uri'); ?>" value="<?php _e('Upload Image','zerif-lite'); ?>" style="margin-top:5px;">
        </p>

    <?php

    }

}
