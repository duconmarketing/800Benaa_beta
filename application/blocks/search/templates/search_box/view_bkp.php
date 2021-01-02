<?php
defined('C5_EXECUTE') or die('Access Denied.');

if (isset($error)) {
    ?><?php echo $error ?><br/><br/><?php
}

if (!isset($query) || !is_string($query)) {
    $query = '';
}
?>
<form action="<?php echo $view->url($resultTargetURL) ?>" method="get" id="ccm-search-block-form-<?php echo $bID ?>" class="ccm-search-block-form"><?php
    if ($query === '') {
        ?>

        <?php /* ?><input name="search_paths[]" type="hidden" value="<?php echo htmlentities($baseSearchPath, ENT_COMPAT, APP_CHARSET) ?>" /><?php */ ?>

        <?php
    } elseif (isset($_REQUEST['search_paths']) && is_array($_REQUEST['search_paths'])) {
        foreach ($_REQUEST['search_paths'] as $search_path) {
            ?><?php /* ?><input name="search_paths[]" type="hidden" value="<?php echo htmlentities($search_path, ENT_COMPAT, APP_CHARSET) ?>" /><?php */ ?><?php
        }
    }
    ?>
    <div class="col-xs-12">

    </div>
    <ul class="custom_ul">

        <li class="custom_li">

            <?php ?>
            <select <?php if($_REQUEST['category']=='') { ?>id="resizing_select" <?php } ?> class="search-select" class="selectpicker" data-width="fit" <?php if ($_REQUEST['keywords'] != '') { ?> onchange="this.form.submit()" <?php } ?> name="category" disabled style="background-color: #fff; color: #fff;">
                <option  value=""></option>
                <?php
                $pl = Core::Make('PageList');
                $pl = new PageList();
                $pl->filterByParentID('261');
                $pl->sortByDisplayOrder();
                $pages = $pl->get();
                // Display Page Name and Description
                foreach ($pages as $page) {
                    if ($page->getCollectionID() == $_GET['category']) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    if(!$page->getCollectionAttributeValue('exclude_page_list')){
                        echo '<option ' . $selected . ' value="' . $page->getCollectionID() . '">' . $page->getCollectionName() . '</option>';
                    }
                }
                ?>

            </select> <?php ?>
            <select id="width_tmp_select">
                <option id="width_tmp_option"></option>
            </select>
            <a class="search mob-search" title="Search" href="javascript:$('#ccm-search-block-form-<?php echo $bID ?>').submit();"><i class="fa fa-search"></i></a>
            <?php if ($query == '') { ?>
                <input name="keywords" type="text" id="Search-box" placeholder="Type your product name..." value="<?php
                if ($_GET['keywords'] != '') {
                    echo $_GET['keywords'];
                }
                ?>" class="ccm-search-block-text" />
                   <?php } else { ?>
                <input name="keywords" type="text" id="Search-box" placeholder="<?php echo htmlentities($query, ENT_COMPAT, APP_CHARSET) ?>" class="ccm-search-block-text" />
            <?php } ?>

        </li>
    </ul>



</form><?php ?>
<script>
    $(document).ready(function () {
        $('#resizing_select').change(function () {
            $("#width_tmp_option").html($('#resizing_select option:selected').text());
            $(this).width($("#width_tmp_select").width());
             $("#Search-box").focus();
        });
    });
</script>
