<div class='fs14'>
	<div class='fs14amazonitem fs14panel fs14panel-default fs14panel-body'>

		<div class=' fs14imagewidth'>
			<a <?php $options = get_option('amazonlist'); if (strcmp($options['TARGET_BLANK'],'usetargetblank') == 0) echo "target='_blank'"; ?>   href='<?php echo $itemObject->DetailPageURL; ?>'><img src='<?php echo $itemObject->LargeImage->URL; ?>'></a>
		</div>

		<div class="fs14description fs14_borderleft">
			<div class="fs14_detaildescription">
				<div class='fs14col-xs-12 fs14_product_title'>
					<h5><?php echo $itemObject->ItemAttributes->Title; ?></h5>
				</div>
				<ul class="fs14col-xs-12 fs14_p_desc">
					<?php 
					if ( $itemObject->ItemAttributes->Feature)
					{

						foreach ( $itemObject->ItemAttributes->Feature as $feature) {
							echo "<li>" . $feature . "</li>";
						}
					}


					 ?>
				</ul>
			</div>

			<div class="fs14meta">
				<div class='fs14col-xs-4'>
					<?php if ($itemObject->OfferSummary->LowestNewPrice) { 
						echo "<p class='fs_price'><span class=''>" . $itemObject->OfferSummary->LowestNewPrice->FormattedPrice . "</p>";
					} else  if ($itemObject->OfferSummary->LowestRefurbishedPrice) { 
						echo "<p class='fs_price'><span class=''>" . $itemObject->OfferSummary->LowestRefurbishedPrice->FormattedPrice . "</p>";
					} else if ($itemObject->OfferSummary->LowestUsedPrice) { 
						echo "<p class='fs_price'><span class=''>" . $itemObject->OfferSummary->LowestUsedPrice->FormattedPrice . "</p>";
					} ?>
				</div>
				<div class='fs14ol-xs-8'>
					<a <?php  $options = get_option('amazonlist'); if (strcmp($options['TARGET_BLANK'],'usetargetblank') == 0) echo "target='_blank'"; ?>  class='fs14btn fs14btn-primary fs14pull-right' href='<?php echo $itemObject->DetailPageURL; ?>'>
					<?php $options = get_option('amazonlist');
						echo  $options['BUY_BUTTON']; ?></a>
				</div>
				<div class="fs14spacer" style="clear: both;"></div>
			</div>
		<div class="fs14spacer" style="clear: both;"></div>
	
		</div>
		<div class="fs14spacer" style="clear: both;"></div>
	
	</div>
</div>