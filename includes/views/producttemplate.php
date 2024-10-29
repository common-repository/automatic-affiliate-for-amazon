<div class='fs14'>
	<div class='fs14amazonitem2 fs14panel fs14panel-default fs14panel-body'>

		<div class='fs14image  fs14col-xs-12'>
			<a <?php if  (strcmp($options['TARGET_BLANK'],'usetargetblank') == 0) echo "target='_blank'"; ?>   href='<?php echo $itemObject->DetailPageURL; ?>'><img src='<?php echo $itemObject->MediumImage->URL; ?>'></a>
		</div>


		<div class='fs14col-xs-12 fs14_product_title'>
			<h5><?php echo $itemObject->ItemAttributes->Title; ?></h5>
		</div>

		<div class="fs14meta2">
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
				<a <?php if (strcmp($options['TARGET_BLANK'],'usetargetblank') == 0) echo "target='_blank'"; ?>   class='fs14btn fs14btn-primary fs14pull-right' href='<?php echo $itemObject->DetailPageURL; ?>'><?php  $options = get_option('amazonlist');  echo  $options['BUY_BUTTON']; ?></a>
			</div>
			<div class="fs14spacer" style="clear: both;"></div>
		</div>


	</div>
</div>