<?php defined('C5_EXECUTE') or die("Access Denied."); ?>

<script type="text/javascript">
$(function() {
	$('i.icon-question-sign').parent().tooltip();
});
</script>


    <div class="my-account">
        <h2 class="page-header">Hello <?php echo $profile->getUserDisplayName(); ?></h2>
        <p><?php echo t('You are currently logged in as <strong>%s</strong>', $profile->getUserDisplayName())?>.</p>
			
		
		<ul class="account-nav">
        <?php foreach($pages as $p) {
			if(!$p->getAttribute('exclude_nav')){
			 ?>
            <li>
                <a href="<?php echo $p->getCollectionLink()?>"><?php echo h(t($p->getCollectionName()))?></a>
                <?php
                $description = $p->getCollectionDescription();
                if ($description) { ?>
                    <span><?php echo h(t($description))?></span>
                <?php } ?>
            </li>
        <?php } ?>
			
        <?php
        $profileURL = $profile->getUserPublicProfileURL();
        if ($profileURL) { ?>
            <div>
                <a href="<?php echo $profileURL?>"><?php echo t("View Public Profile")?></a>
                <p><?php echo t('View your public user profile and the information you are sharing.')?></p>
            </div>


        <?php } } ?>
            
		</ul>
    </div>

