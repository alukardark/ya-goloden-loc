function get_loader() {
    var loader = $("#loader")[0];
    if (!loader) {
        $("<div id='loader' title='Идет загрузка'></div>").appendTo("body");
    }

}

function set_link_loader(link) {
    $(link).data("title", $(link).text()).css("position", "relative").append("<div class='loader'></div>");
}

var loader = null;

function show_loader(el) {
    $(loader).css({
        left: $(el).position().left + $(el).outerWidth() + 5,
        top: Math.round($(el).position().top + ($(el).outerHeight() - 16) / 2)
    }).show();
    $(el).after(loader);
}

function hide_loader(el) {
    $(el).parent().find(".loader").remove();
}

var fancy_sets = {
    padding: 0,
    margin: 30,
    closeBtn: true,
    maxHeight: 800,
    fitToView: true,
    width: '95%',
    height: '95%',
    loop: false,
    autoSize: true,
    closeClick: false,
    openEffect: 'none',
    closeEffect: 'none',
    live: true,
    nextEffect: 'none',
    prevEffect: 'none',
    tpl: {
        next: '<a title="Следующая" class="fancybox-nav fancybox-next" href="javascript:;"><span></span></a>',
        prev: '<a title="Предыдущая" class="fancybox-nav fancybox-prev" href="javascript:;"><span></span></a>',
        closeBtn: '<a title="Закрыть" class="fancybox-item fancybox-close" href="javascript:;"></a>'
    },
    beforeLoad: function() {
        this.href = $(this.element).attr("data-popup_href");
    },
    beforeShow: function() {
        $("body").css({
            'overflow-y': 'hidden'
        });
    },
    afterShow: function() {
        $("body").css({
            'overflow-y': 'visible'
        });
    },
    helpers: {
        title: {
            type: 'inside'
        },
        overlay: {
            css: {
                background: 'rgba(0, 0, 0, 0.8)'
            }
        }
    }
};

function gotoSubdomain() {
    var city_id = $(this).parents("form:first").find("select[name=city]").val();
    document.location.href = json_cities[city_id];
    return false;
}

$(document).ready(function() {
    var city_id = $(".popup-form").attr("data-auto-city");
    if (city_id) {
        $("select[name=city] option[value="+city_id+"]").attr("selected","selected");
    }
    $("select[name=city]").hide();
    if ($("select[name=city] option").length > 0) {
        $("select[name=city]").selectBox();
        if ($("select[name=city] option").length == 1)
            $("select[name=city]").data('selectBox-control').addClass('selectBox-disabled');
    } else {
        $("select[name=city]").after("<br/><br/>");
    }
    var changed = false;
    $("select[name=city]").bind("beforeopen", function(e) {
        var x = -12;
        $("ul.selname-city li").each(function() {
            $(this).css({
                left: x
            });
            x -= 16;
        });
    });
    $(".popup-form input[type=submit]").click(gotoSubdomain);
    $("h1:not(.sect)").wrapInner("<span/>");
    $("h1:not(.sect) span").append("<span class='line2 line-left'></span>", "<span class='line2 line-right'></span>");
});