<?php
defined('C5_EXECUTE') or die('Access Denied.');

if (isset($error)) {
    ?><?php echo $error ?><br/><br/><?php
}

if (!isset($query) || !is_string($query)) {
    $query = '';
}
?>
<style>
    #ajax-result{
        box-shadow: 0 5px 5px rgba(0,0,0,.7);
        background-color: #fff;
        padding: 10px 10px;
        border: 1px solid black;
        display: none;
        position:absolute;
        z-index: 50;
        max-height: 500px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    .product_item_ajx{margin: 0 0 15px 0;text-align: center;border: 1px solid rgba(236, 124, 5, 0.43);}
    .store-product-list-item_ajx{white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word; margin-bottom: 20px;}
    .a_title_ajx{display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;overflow: hidden; color: #2b2a2a !important; font-weight: bold;}
    .store-product-list-item_ajx h6 {padding: 5px 0 0 0;margin: 0;font-size: 15px;background: none;color: #ec7c05;}
    p.search-result{background-color: #ec7c05; font-weight: bold;}
</style>



<form action="<?php echo $view->url($resultTargetURL) ?>" method="get" id="ccm-search-block-form-<?php echo $bID ?>" class="ccm-search-block-form" style="position: relative;"><?php
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
    <ul class="custom_ul" >

        <li class="custom_li">

            <?php ?>
            <!--<select <?php if ($_REQUEST['category'] == '') { ?>id="resizing_select" <?php } ?> class="search-select" class="selectpicker" data-width="fit" <?php if ($_REQUEST['keywords'] != '') { ?> onchange="this.form.submit()" <?php } ?> name="category" disabled style="background-color: #fff; color: #fff;">-->
                <!--<option  value=""></option>-->
                <?php
//                $pl = Core::Make('PageList');
//                $pl = new PageList();
//                $pl->filterByParentID('261');
//                $pl->sortByDisplayOrder();
//                $pages = $pl->get();
//                // Display Page Name and Description
//                foreach ($pages as $page) {
//                    if ($page->getCollectionID() == $_GET['category']) {
//                        $selected = 'selected';
//                    } else {
//                        $selected = '';
//                    }
//                    if (!$page->getCollectionAttributeValue('exclude_page_list')) {
//                        echo '<option ' . $selected . ' value="' . $page->getCollectionID() . '">' . $page->getCollectionName() . '</option>';
//                    }
//                }
                ?>

            <!--</select>-->
                <?php ?>
            <select id="width_tmp_select">
                <option id="width_tmp_option"></option>
            </select>
            <a class="search mob-search" title="Search" href="javascript:$('#ccm-search-block-form-<?php echo $bID ?>').submit();" style="top:auto !important;"><i class="fa fa-search"></i></a>
            <div class="input-group">
                <div class="input-group-addon" style="background-color:#ec7c05;color: #fff;border-color: #ec7c05;">
                    <div class="input-group-text">Search</div>
                </div>
            <?php if ($query == '') { ?>
                <input name="keywords" type="search" id="Search-box" placeholder="Type your product name..." value="<?php
                if ($_GET['keywords'] != '') {
                    echo $_GET['keywords'];
                }
                ?>" class="ccm-search-block-text" style="font-weight: bold; width: 100%; padding-left: 20px;" autocomplete="off" />
                   <?php } else { ?>
                <input name="keywords" type="search" id="Search-box" placeholder="<?php echo htmlentities($query, ENT_COMPAT, APP_CHARSET) ?>" class="ccm-search-block-text" style="font-weight: bold; width: 100%; padding-left: 20px;" autocomplete="off" />
            <?php } ?>
            </div>

        </li>
        <div class="row" style="margin-left: 0px;">
            <div id="loaddiv" class="hidden">
                <div style="height: 100%;width: 100%;z-index: 9999999;text-align: center;background-color: #ffffffbd;position: absolute;margin-right: -15px;margin-left: -15px;">
                    <img src="<?= $this->getThemePath(); ?>/images/ajax-searching3.gif" id="loader" class="bg-transparent" style="margin: 0;position: absolute;top: 50%;left: 50%;margin-right: -50%;transform: translate(-50%, -50%);" />
                </div>
            </div>
            <div class="col-md-12" id="ajax-result" style="width: 100%;">
                
            </div>
        </div>
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

    $("#Search-box").on('input', function () {
        clearTimeout(this.delay);
        this.delay = setTimeout(function () {
            $(this).trigger('search');
        }.bind(this), 800);
    }).on('search', function () {
        searchKey = $("#Search-box").val();
        if (searchKey == "") {
            $("#ajax-result").hide();
        } else {
            var load = $("#loaddiv").html();
            $("#ajax-result").prepend(load);
            $("#ajax-result").show();            
            
            $.ajax({
                url: CARTURL + '/ajaxSearch',
                data: {key: searchKey},
                type: 'POST',
                success: function (data) {
                    var res = jQuery.parseJSON(data);
                    $("#ajax-result").html(res.html);
                }
            });
        }
    });

    $(document).on('click', function (e) {
        if ($(e.target).closest("#ajax-result").length === 0) {
            $("#ajax-result").hide();
        }
    });
    
    function suggestSearch(key){
        event.preventDefault();
        $("#Search-box").val(key.replace(/_/gi, " "));
        $(".ccm-search-block-form").submit();
        
    }
</script>
