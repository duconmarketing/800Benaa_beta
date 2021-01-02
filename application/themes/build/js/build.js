var build = {
    maintainHeight: function() {
        $(".filteredProduct").css("position", "relative");
        var t = [],
            i = 0,
            e = $(".filteredProduct").width(),
            a = build.getNumberPerRow();
        $(".filteredProduct").find(".product_item").each(function(e, n) {
            $.isArray(t[i]) ? t[i].push($(this)) : (t[i] = [], t[i].push($(this)));
            var r = parseInt(e + 1);
            r % a == 0 && (i += 1)
        });
        var n = 0;
        $.each(t, function(t, i) {}), $(".filteredProduct").height(n)
    },
    removeHeight: function() {},
    isMobileDevice: function() {
        return $(window).width() < 767 ? !0 : !1
    },
    getNumberPerRow: function() {},
    getBatchWidth: function(t, i) {},
    run: function() {
        build.isMobileDevice() ? build.removeHeight() : build.maintainHeight()
    }
};
$(document).ready(function() {
    //build.run(), $(window).resize(function() {
       // build.run()
    //}), setInterval(build.run(), 1e3)
}), $(window).load(function() {
    //build.run()
});