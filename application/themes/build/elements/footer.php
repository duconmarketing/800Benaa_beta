<footer>
    <div class="footer_banner">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="left_text">
                        <?php
                        $area = new GlobalArea('Footer Top_highlight');
                        $area->display();
                        ?>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="right_text"> 
                        <?php
                        $area = new GlobalArea('Footer top_button');
                        $area->display();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="footer_left">
                        <?php
                        $area = new GlobalArea('Footer_links');
                        $area->display();
                        ?>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="footer_right">
                        <?php
                        $area = new GlobalArea('Footer_map');
                        $area->display();
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="foot_news">
                    <div class="col-md-6">
                        <div class="news_left">
                            <?php
                            $area = new GlobalArea('Footer Bottom_left');
                            $area->display()
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="foot_search">
                            <?php
                            $area = new GlobalArea('Footer_newsletter');
                            $area->display();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--            <div class="row" style="color:#ffffff;font-size: 10px;">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                *Free Delivery starts from 500 AED
                            </div>
                        </div>-->
            <div class="row">
                <div class="foot_copy">
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <p>© Copyright <script type="text/javascript">document.write(new Date().getFullYear())</script>. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <?php
                        $area = new GlobalArea('Footer Bottom_links');
                        $area->display();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@14.0.0/dist/lazyload.min.js"></script>
<script>
                            (function () {
                                var ll = new LazyLoad({
                                });
                            })();
</script>

<script>
    $('#fastTrackForm').submit(function(event) {
        event.preventDefault();
        var ph=$('#phone2').val();
        if(!(ph)) {
            $("#phone-info2").html("Please enter mobile number");
            return false;
        } else if(isNaN(ph)) {
            $("#phone-info2").html("Please enter digits only");
            return false;
        } else if(ph.length < 9) {
            $("#phone-info2").html("Invalid mobile number");
            return false;
        } else if(phone_validate(ph)) {
            $.post($(this).attr('action'), $(this).serialize(), function (res) {
            });
            $('#fastTrackForm').hide();
            $('.modal-body .modal-succ-msg').show();
            setTimeout(function () {
                $('#myModal').modal('hide');
            }, 5000);
            return false;
        }
    });

    $('#fastTrackFormHome').submit(function(event) {
        event.preventDefault();
        var ph=$('#phone1').val();
        if(!(ph)) {
            $("#phone-info").html("Please enter mobile number");
            return false;
        } else if(isNaN(ph)) {
            $("#phone-info").html("Please enter digits only");
            return false;
        } else if(ph.length < 9) {
            $("#phone-info").html("Invalid mobile number");
            return false;
        } else if(phone_validate(ph)) {
            $("#phone-info").html("");
            $.post($(this).attr('action'), $(this).serialize(), function (res) {
            });
            $('#fastTrackForm').hide();
            $('#myModal').modal();
            $('.modal-body .modal-succ-msg').show();
            setTimeout(function () {
                $('#myModal').modal('hide');
            }, 5000);
            $(this).find("input[type=text]").val("");
            return false;
        }
    });

    $('#myModal').on('hidden.bs.modal', function () {
        $('.modal-content').find('input:text').val('');
        $('#fastTrackForm').show();
        $('.modal-body .modal-succ-msg').hide();
    });

    function phone_validate(phno)
    {
        var regexPattern=new RegExp(/^[0-9-+]+$/);    // regular expression pattern
        return regexPattern.test(phno);
    }
</script>

<script>
    $(function () {
//       $(".customerAttach").find(".alert").delay(2000).fadeOut();
        $(".customerAttach").find(".alert").css('display', 'none');
        $(".customerAttach").find("#Question7").prop('required', true);

    });
</script>

</body>
<?php Loader::element('footer_required'); ?>
</html>
