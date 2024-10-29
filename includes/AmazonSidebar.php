<?php
/**
 * Adds My_Widget widget.
 */
class HD_Amazon_Sidebar extends WP_Widget {


	    /**
     * @TODO - Rename "widget-name" to the name your your widget
     *
     * Unique identifier for your widget.
     *
     *
     * The variable name is used as the text domain when internationalizing strings
     * of text. Its value should match the Text Domain file header in the main
     * widget file.
     *
     * @since    1.0.0
     *
     * @var      string
     */
	    protected $widget_slug = 'Amazon_Affiliate_Sidebar';

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'HD_Amazon_Sidebar', // Base ID
			__('FS-14 AAAP', 'text_domain'), // Name
			array( 'description' => __( 'Short description of the widget goes here!', 'text_domain' ), ) // Args

			);


		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	}

	  /**
     * Return the widget slug.
     *
     * @since    1.0.0
     *
     * @return    Plugin slug variable.
     */
	  public function get_widget_slug() {
	  	return $this->widget_slug;
	  }



	 /**
     *
     * @get URLs from string (string maybe a url)
     *
     * @param string $string
     * @return array
     *
     */
	 public function getUrls($string) {
	 	$regex = '/https?\:\/\/[^\" ]+/i';
	 	preg_match_all($regex, $string, $matches);
        //return (array_reverse($matches[0]));
	 	return ($matches[0]);
	 }


	 public function searchForAmazonIDs($allLinks)
	 {
	 	foreach ($allLinks as $link) 
		{
			$result = "";

		    $pattern = "([a-zA-Z0-9]{10})(?:[/?]|$)";
		    $pattern = escapeshellarg($pattern);

		    preg_match($pattern, $link, $matches);


		    if($matches && isset($matches[1])) {
		        $result = $matches[1];
		        $aids[] = $result;
		    } 

		}

		return $aids;
	 }

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) 
	{


		//echo __( 'Hello, World!', 'text_domain' );

		global $wp_query;
		$post = $wp_query->post;


		$values = get_post_custom( $post->ID );
		$check = isset( $values['aap-meta-box_check'] ) ?  $values['aap-meta-box_check'] : '';


		if ($check[0] == 'on')
		{
			// error_log("disabled");
		}
		else
		{

			$allLinks = $this->getUrls($post->post_content);
			$aids = $this->searchForAmazonIDs($allLinks);



			if (count($aids) > 0)
			{

				$comma_seperated_aids = implode(",",$aids);
				$options = get_option('amazonlist');

				$amazonEcs = new AmazonECS($options['AWS_API_KEY'], $options['AWS_API_SECRET_KEY'], $options['country'], $options['AWS_ASSOCIATE_TAG']);
				$amazonEcs->associateTag($options['AWS_ASSOCIATE_TAG']);
				$response = $amazonEcs->responseGroup('OfferSummary,ItemAttributes,Images')->lookup($comma_seperated_aids);


				$output = "";


				if (is_object($response->Items->Item))
				{
					echo $args['before_widget'];
					if ( ! empty( $instance['title'] ) ) {
						echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
					}

					$itemObject = $response->Items->Item;
					include(LIST_AMAZON_PATH . "includes/views/producttemplate.php");
					
				}
				else if (is_array($response->Items->Item) )
				{

					echo $args['before_widget'];
					if ( ! empty( $instance['title'] ) ) {
						echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
					}

					foreach ($response->Items->Item as $itemObject) 
					{
						include(LIST_AMAZON_PATH . "includes/views/producttemplate.php");
					}

				}

			
			}

		
		}






		//echo $args['after_widget'];
	}





	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Offers in this article', 'text_domain' );
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
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
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

		/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() 
	{
			wp_enqueue_style( 'fs14', plugins_url('/koffm.css', __FILE__));

	} // end register_widget_styles

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {

		// TODO be sure to change 'widget-name' to the name of *your* plugin
		load_plugin_textdomain( $this->get_widget_slug(), false, plugin_dir_path( __FILE__ ) . 'lang/' );

	} // end widget_textdomain

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		// TODO define activation functionality here
	} // end activate

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog
	 */
	public function deactivate( $network_wide ) {
		// TODO define deactivation functionality here
	} // end deactivate

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array('jquery') );

	} // end register_admin_scripts


	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

		//wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url( 'js/widget.js', __FILE__ ), array('jquery') );

	} // end register_widget_scripts


	public function flush_widget_cache() 
	{
		wp_cache_delete( $this->get_widget_slug(), 'widget' );
	}

} // class My_Widget

?>