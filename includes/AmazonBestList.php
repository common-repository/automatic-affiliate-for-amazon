<?php

class amazonBestseller
{
	
	public function __construct()
	{
        add_shortcode('fs14_bestseller', array($this,'addAmazonBestseller'));
	}




	function addAmazonBestseller($atts) {

	        $output = "";



	        $aid = $atts['keyword'];

	        $limit = 5;
			$counter = 0;



	        if(isset($atts['limit']))
	        	$limit = intval($atts['limit']);




	        $detail = false;

	        if(isset($atts['detail']))
	        {
	        	if ($atts['detail'] == "1")
	         			$detail = true;
	        }


	            try
	            {
					$options = get_option('amazonlist');

					$amazonEcs = new AmazonECS($options['AWS_API_KEY'], $options['AWS_API_SECRET_KEY'], $options['country'], $options['AWS_ASSOCIATE_TAG']);

					$amazonEcs = $amazonEcs->category('All');

	                $response = $amazonEcs->responseGroup('OfferSummary,ItemAttributes,Images,Reviews')->search($aid);


		            if (is_object($response->Items->Item))
					{


						$itemObject = $response->Items->Item;

	               		 $output .= $this->requireToVar(LIST_AMAZON_PATH . "includes/views/producttemplate.php",$itemObject);
						
					}
					else if (is_array($response->Items->Item) )
					{

						foreach ($response->Items->Item as $itemObject) 
						{


							if ($counter < $limit)
							{
								if ($detail)
								{
		               			 $output .= $this->requireToVar(LIST_AMAZON_PATH . "includes/views/productshortcodetemplate.php",$itemObject);
								}
								else
								{

		               			 $output .= $this->requireToVar(LIST_AMAZON_PATH . "includes/views/producttemplate.php",$itemObject);
								}

								$counter++;
							}
						}





					}


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
