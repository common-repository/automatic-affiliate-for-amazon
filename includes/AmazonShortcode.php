<?php

class amazonShortcode 
{
	
	public function __construct()
	{
        add_shortcode('fs14_amazonlink', array($this,'addAmazonList'));
	}




	function addAmazonList($atts) {

	        $output = "";

	        $aid = $atts['aid'];

	            try
	            {
					$options = get_option('amazonlist');

					$amazonEcs = new AmazonECS($options['AWS_API_KEY'], $options['AWS_API_SECRET_KEY'], $options['country'], $options['AWS_ASSOCIATE_TAG']);
	                $response = $amazonEcs->responseGroup('OfferSummary,ItemAttributes,Images,Reviews')->lookup($aid);

	                $itemObject = $response->Items->Item;


	                $output = $this->requireToVar(LIST_AMAZON_PATH . "includes/views/productshortcodetemplate.php",$itemObject);
	            }
	            catch(Exception $e)
	            {
	              echo $e->getMessage();
	            }

	        return $output;


	}

	function requireToVar($file,$itemObject)
	{
	    ob_start();
	    include($file);
	    return ob_get_clean();
	}

	
 }	

?>
