<?php

class amazonSettings {

	protected $option_amazon = 'amazonlist';



    public function __construct() {


        add_action('init', array($this, 'init'));


         // Admin sub-menu
        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'add_page'));

        // Admin post menu
        add_action( 'add_meta_boxes', array($this, 'cd_meta_box_add')  );
        add_action( 'save_post',array($this,  'cd_meta_box_save' ));


        $options = get_option($this->option_amazon);


        if (empty($options))
        {
            update_option($this->option_amazon, $this->data);
        }


    }


//post Metabox

    function cd_meta_box_add()
    {
        add_meta_box( 'aap-meta-box-id', 'FS-14 AAAP', array($this,'cd_meta_box_cb'), 'post', 'side', 'default' );
    }


    function cd_meta_box_cb()
    {

       global $post;
       $values = get_post_custom( $post->ID );
       $check = isset( $values['aap-meta-box_check'] ) ?  $values['aap-meta-box_check'] : '';


          // We'll use this nonce field later on when saving.
       wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
       ?>

       <input type="checkbox" name="aap-meta-box-show-sidebar" id="aap-meta-box-show-sidebar" <?php checked( $check[0], 'on' ); ?>> </input>
       <label for="my_meta_box_text">Sidebar Widget nicht anzeigen</label>
       <?php
   }

   function cd_meta_box_save( $post_id )
   {
        // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

        // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;



    $chk = isset( $_POST['aap-meta-box-show-sidebar'] ) && $_POST['aap-meta-box-show-sidebar'] ? 'on' : 'off';

    update_post_meta( $post_id, 'aap-meta-box_check', $chk );
}

//admin settings

public function activate() {

}

public function deactivate() {
    delete_option($this->option_amazon);
}

public function init() {

}




	    // White list our options using the Settings API
public function admin_init() {
    register_setting('listamazon_list_options', $this->option_amazon, array($this, 'validate'));
}

        // Add entry in the settings menu
public function add_page() {
    add_options_page('FS-14 AAAP', 'FS-14 AAAP', 'manage_options', 'listamazon_list_options', array($this, 'options_do_page'));
}

        // Print the menu page itself
public function options_do_page() {
    $options = get_option($this->option_amazon);
    ?>

    <div class="wrap">
        <h2>
        FS-14 Automatic Amazon Affiliate Plugin Settings</h2>
        <form method="post" action="options.php">
            <?php settings_fields('listamazon_list_options'); ?>
						<h3>General Settings</h3>
						<a class="button-secondary" href="http://www.wordpress-affiliate-plugins.com/">&#8680; Upgrade to <b>Premium Version</b> &#8678;</a>
            <table class="form-table">
                <tr valign="top"><th >Amazon country code:</th>
                    <td><input type="text" name="<?php echo $this->option_amazon?>[country]" value="<?php echo $options['country']; ?>" /><br><br>Please choose your Amazon Partnership country. Insert the respective country code into the field above.<br><br> <b>For example:</b>
									  <ul>
											<li><b>com</b> (United States)</li>
											<li><b>de</b> (Germany)</li>
											<li><b>es</b> (Spain)</li>
											<li><b>fr</b> (France)</li>
											<li><b>co.uk</b> (United Kingdom)</li>
											<li><b>it</b> (Italy)</li>
											<li><b>cn</b> (Canada)</li>
											<li>etc ...</li>
										</ul>
										</td>
                    <td></td>
                </tr>
                <tr valign="top"><th >AWS_API_KEY:</th>
                    <td><input type="text" name="<?php echo $this->option_amazon?>[AWS_API_KEY]" value="<?php echo $options['AWS_API_KEY']; ?>" /></td>
                </tr>
                <tr valign="top"><th >AWS_API_SECRET_KEY:</th>
                    <td><input type="text" name="<?php echo $this->option_amazon?>[AWS_API_SECRET_KEY]" value="<?php echo $options['AWS_API_SECRET_KEY']; ?>" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                    To make calls to Amazon SQS programmatically (for pricing updates, pictures, etc.), you need an access key ID and a secret access key. Please read the official instructions on how to obtain your <i>personal</i> AWS API key here:<br>
                    <a href="http://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSGettingStartedGuide/AWSCredentials.html">Getting Your Access Key ID and Secret Access Key</a></td>
                </tr>
                <tr>
                    <th>Choose your Design:</th>
                    <td>
                        <fieldset >
                            <input type="radio" name="<?php echo $this->option_amazon?>[csslayout]"  value="fs14" <?php if (strcmp($options['csslayout'],'fs14') == 0) echo "checked"; ?>><label >FS-14</label></input> <br>
                            A pre-designed style.<br><br>
                            <input type="radio" name="<?php echo $this->option_amazon?>[csslayout]"  value="bootstrap" <?php if (strcmp($options['csslayout'],'bootstrap') == 0) echo "checked"; ?>><label >Bootstrap</label></input> <br>
                            A pre-designed style using bootstrap components.<br><br>
                            <input type="radio" name="<?php echo $this->option_amazon?>[csslayout]"  value="plain" <?php if (strcmp($options['csslayout'],'plain') == 0) echo "checked"; ?>><label >Plain</label></input> <br>
                            A pre-designed style based on the CSS of your theme.<br><br>
                            <input type="radio" name="<?php echo $this->option_amazon?>[csslayout]"  value="nolayout" <?php if (strcmp($options['csslayout'],'nolayout') == 0) echo "checked"; ?>><label >No Layout</label> </input> <br>
                            Use your own css to design the content (/css/nolayout.css).<br>
                        </fieldset>
                    </td>
                </tr>
                <tr valign="top"><th >Buy Button Text:</th>
                    <td><input type="text" name="<?php echo $this->option_amazon?>[BUY_BUTTON]" value="<?php echo $options['BUY_BUTTON']; ?>" /></td>
                </tr>
                <tr valign="top"><th >Use target="_blank":</th>
                    <td><input type="checkbox" name="<?php echo $this->option_amazon?>[TARGET_BLANK]" value="usetargetblank" <?php if (strcmp($options['TARGET_BLANK'],'usetargetblank') == 0) echo "checked"; ?> /></td>
                </tr>

              </table>

            <h3>&#9733; Premium Version Settings &#9733;</h3>
            <table class="form-table">
                <tr valign="top"><th scope="row">AWS_ASSOCIATE_TAG:</th>
                    <td>*********</td>
                </tr>
                <tr valign="top"><th scope="row">Button Design:</th>
                    <td><?php echo $options['button_color']; ?></td>
                </tr>

            </table>
            <a class="button-secondary" href="http://www.wordpress-affiliate-plugins.com/">&#8680; Upgrade to <b>Premium Version</b> &#8678;</a>

						<p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>

        </form>

    </div>
    <?php
}

public function validate($input) {

    $valid = array();
    $valid['country'] = sanitize_text_field($input['country']);
    $valid['AWS_API_KEY'] = sanitize_text_field($input['AWS_API_KEY']);
    $valid['AWS_API_SECRET_KEY'] = sanitize_text_field($input['AWS_API_SECRET_KEY']);
    $valid['AWS_ASSOCIATE_TAG'] = sanitize_text_field($input['AWS_ASSOCIATE_TAG']);
    $valid['BUY_BUTTON'] = sanitize_text_field($input['BUY_BUTTON']);
    $valid['AWS_ASSOCIATE_TAG'] = '';
    $valid['csslayout'] = sanitize_text_field($input['csslayout']);
    $valid['TARGET_BLANK'] = sanitize_text_field($input['TARGET_BLANK']);

    return $valid;
}



private function print_Item($item)
{




}




}

?>
