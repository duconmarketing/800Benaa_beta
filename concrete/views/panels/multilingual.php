<?php
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="ccm-panel-content-inner">
    <h5><?php echo t("Currently Viewing")?></h5>
    <ul class="item-select-list">
        <?php
        $relatedID = $currentSection->getTranslatedPageID($c);
        $icon = $ih->getSectionFlagIcon($currentSection);
        $locale = $currentSection->getLocale();
        ?>
        <li class="ccm-panel-multilingual-current-section"><span><?php echo $icon?> <?php echo $c->getCollectionName()?></span></li>
    </ul>

    <h5><?php echo t('Mapped in Other Sections')?></h5>
    <ul class="item-select-list">
        <?php foreach($list as $m) {
            $relatedID = $m->getTranslatedPageID($c);
            $icon = $ih->getSectionFlagIcon($m);
            $locale = $m->getLocale();
            $cParent = Page::getByID($c->getCollectionParentID());
            $cParentRelatedID = $m->getTranslatedPageID($cParent);
        ?>
            <li>
                <?php if ($relatedID && $currentSection->getCollectionID() != $m->getCollectionID()) {
                    $relatedPage = Page::getByID($relatedID, 'RECENT'); ?>
                    <a href="<?php echo $relatedPage->getCollectionLink()?>"><?php echo $icon?> <?php echo $relatedPage->getCollectionName()?></a>
                <?php } else { ?>
                    <a href="#" class="ccm-panel-multilingual-section-no-mappings" data-launch-multilingual-menu="<?php echo $m->getCollectionID()?>"><?php echo $icon?> <span><?php echo t('None Created')?></span></a>
                    <div class="ccm-popover-inverse popover fade" data-multilingual-menu="<?php echo $m->getCollectionID()?>">
                        <div class="popover-inner">
                            <ul class="dropdown-menu">
                                <?php if ($cParentRelatedID || $c->isPageDraft()) { ?>
                                    <li><a href="#" data-multilingual-create-page="<?php echo $m->getCollectionID()?>"><?php echo t('Create Page')?></a></li>
                                <?php } else { ?>
                                    <li class="disabled"><a href="#" title="<?php echo t('Parent page does not exist. Create the parent page in this tree first.')?>"><?php echo t('Create Page')?></a></li>
                                <?php } ?>
                                <li class="divider"></li>
                                <li><a href="#" data-multilingual-map-page="<?php echo $m->getCollectionID()?>"><?php echo t('Map Existing Page')?></a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </li>
        <?php } ?>
    </ul>
</div>

<script type="text/javascript">
    replaceLinkWithPage = function(menuID, link, icon, name) {
        var $link = $('a[data-launch-multilingual-menu=' + menuID + ']');
        var newLink = '<a href="' + link + '">' + icon + ' ' + name + '<\/a>';
        $link.replaceWith($(newLink));
    }

    $(function() {
        $('a.ccm-panel-multilingual-section-no-mappings').each(function() {
            $(this).concreteMenu({
                menu: 'div[data-multilingual-menu=' + $(this).attr('data-launch-multilingual-menu') + ']',
                menuLauncherHoverClass: 'ccm-panel-multilingual-menu-hover',
                menuContainerClass: 'ccm-panel-multilingual-menu-container'
            });
        });
        $('a[data-multilingual-create-page]').on('click', function(e) {
            e.preventDefault();
            var cID = $(this).attr('data-multilingual-create-page');
            $.concreteAjax({
                url: '<?php echo $multilingualController->action('create_new')?>',
                method: 'post',
                data: {
                    'section': cID,
                    'cID': '<?php echo $c->getCollectionID()?>'
                },
                success: function(r) {
                    ConcreteAlert.notify({
                        'message': r.message,
                        'title': r.title
                    });
                    if (r.link) {
                        ConcreteMenuManager.reset();
                        replaceLinkWithPage(cID, r.link, r.icon, r.name);
                    }
                }
            });
        });

        $('a[data-multilingual-map-page]').on('click', function(e) {
            e.preventDefault();
            var cID = $(this).attr('data-multilingual-map-page');
            ConcretePageAjaxSearch.launchDialog(function(data) {
                $.concreteAjax({
                    url: '<?php echo $multilingualController->action('assign')?>',
                    method: 'post',
                    data: {
                        'destID': data.cID,
                        'cID': cID
                    },
                    success: function(r) {
                        ConcreteAlert.notify({
                            'message': r.message,
                            'title': r.title
                        });
                        if (r.link) {
                            ConcreteMenuManager.reset();
                            replaceLinkWithPage(cID, r.link, r.icon, r.name);
                        }

                    }
                });
            });
        });

    });
</script>
