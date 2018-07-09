/*
 function ImgShw(ID, width, height, alt)
 {
 var scroll = "no";
 var top=0, left=0;
 var w, h;
 if(navigator.userAgent.toLowerCase().indexOf("opera") != -1)
 {
 w = document.body.offsetWidth;
 h = document.body.offsetHeight;
 }
 else
 {
 w = screen.width;
 h = screen.height;
 }
 if(width > w-10 || height > h-28)
 scroll = "yes";
 if(height < h-28)
 top = Math.floor((h - height)/2-14);
 if(width < w-10)
 left = Math.floor((w - width)/2-5);
 width = Math.min(width, w-10);
 height = Math.min(height, h-28);
 var wnd = window.open("","","scrollbars="+scroll+",resizable=yes,width="+width+",height="+height+",left="+left+",top="+top);
 wnd.document.write(
 "<html><head>"+
 "<"+"script type=\"text/javascript\">"+
 "function KeyPress(e)"+
 "{"+
 "	if (!e) e = window.event;"+
 "	if(e.keyCode == 27) "+
 "		window.close();"+
 "}"+
 "</"+"script>"+
 "<title>"+(alt == ""? "'.GetMessage("main_js_img_title").'":alt)+"</title></head>"+
 "<body topmargin=\"0\" leftmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onKeyDown=\"KeyPress(arguments[0])\">"+
 "<img src=\""+ID+"\" border=\"0\" alt=\""+alt+"\" />"+
 "</body></html>"
 );
 wnd.document.close();
 wnd.focus();
 }
 */
function get_loader() {
    var loader = $("#loader")[0];
    if (!loader) {
        $("<div id='loader' title='Идет загрузка'></div>").appendTo("body");
    }

}

function set_link_loader(link) {
    $(link).data("title", $(link).text()).css("position", "relative").append("<div class='loader'></div>");
}

function flyToBasket(container, is_small) {
    if (!$("#fly_container")[0]) {
        $("body").append("<div id='fly_container'></div>");
    }
    if (!is_small)
        is_small = false;
    var fly_container = $("#fly_container")[0];
    $(fly_container).toggleClass("items", is_small).toggleClass("small-items", is_small);
    $(">.clone", fly_container).stop(true, true);
    $(fly_container).empty();
    $(container).clone().addClass("clone").appendTo(fly_container);
    var c = $(">.clone:first", fly_container)[0];
    $(c).removeAttr("id").find("script").remove();
    var pos = $(container).offset();
    var basket_pos = $("#bx_cart_block").offset();
    $(c).css({
        opacity: 0.75,
        left: pos.left,
        top: pos.top,
        position: 'absolute'
    }).animate({
        zIndex: 999,
        opacity: 0,
        left: basket_pos.left - $(c).width() / 2,
        top: basket_pos.top
    }, is_small ? 800 : 800, function () {
        $(fly_container).empty();
    });
}

function flyToConstructor(container, is_small, block) {
    if (!$("#fly_container")[0]) {
        $("body").append("<div id='fly_container'></div>");
    }
    if (!is_small)
        is_small = false;
    var fly_container = $("#fly_container")[0];
    $(fly_container).toggleClass("items", is_small).toggleClass("small-items", is_small);
    $(">.clone", fly_container).stop(true, true);
    $(fly_container).empty();
    $(container).clone().addClass("clone").appendTo(fly_container);
    var c = $(">.clone:first", fly_container)[0];
    $(c).removeAttr("id").find("script").remove();
    var pos = $(container).offset();
    var basket_pos = $(block).offset();
    $(c).css({
        opacity: 0.8,
        left: pos.left,
        top: pos.top,
        position: 'absolute'
    }).animate({
        zIndex: 999,
        opacity: 0,
        left: basket_pos.left,
        top: basket_pos.top
    }, is_small ? 800 : 400, function () {
        $(fly_container).empty();
    });
}


function ajaxCheckout() {
    var form = $("#basket_form");
    $.post($(form).attr("action"), $(form).serialize(), function (result) {
        $("#order-form").html(result);
    }, 'html');
}

isMobile = {
    Android: function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function () {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function () {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function (needle) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] === needle) {
                return i;
            }
        }
        return -1;
    };
}

jQuery.validator.addMethod("phone", function (value, element) {
    value = value.replace(/_/g, "");
    return this.optional(element) || (/^[\+ \d]+$/i.test(value) && value.length == 13);
}, "Введите правильный телефон, например: +7 1112223344");

function checkArrows(swiper) {
    if ($(swiper.container).width() >= $(swiper.container).find(".swiper-wrapper").width() - 3) {
        $(swiper.container).parent().find(">.arrow-left").hide();
        $(swiper.container).parent().find(">.arrow-right").hide();
        return;
    }
    var x = swiper.getWrapperTranslate("x");
    $(swiper.container).parent().find(">.arrow-left").toggle(x < 0);
    $(swiper.container).parent().find(">.arrow-right").toggle(-x + $(swiper.container).width() < $(swiper.container).find(".swiper-wrapper").width() - 15);
}


function formShowErrors(errorMap, errorList) {
    $.each(this.validElements(), function (index, element) {
        var $element = $(element);
        $element.parent().find(".error-label").remove();
        $element.data("title", "")
            .removeClass("error");
    });
    $.each(errorList, function (index, error) {
        var $element = $(error.element);
        $element
            .data("title", error.message)
            .addClass("error");
        if (index == 0) {
            $element.parent().find(".error-label").remove();
            $element.after(
                $("<div/>")
                    .addClass("error-label")
                    .text(error.message)
            );
        }
    });
}

function toggleTopButton() {
    $("#bx_cart_block").toggleClass("fixed", $(document).scrollTop() > 200);
    if ($(document).height() >= 1.2 * $(window).height() && $(window).height() <= $(document).scrollTop() + 300) {
        $('.btnUp:hidden').fadeIn(100);
    } else {
        $('.btnUp:visible').stop(true, true).fadeOut(100);
    }
}

function bindTopButton() {
    $(window).bind("scroll resize", toggleTopButton).trigger("resize");
    $('.btnUp').click(function () {
        $('html, body').animate({
            scrollTop: 0
        }, 500);
        return false;
    });
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

function popupFormSubmit(container, success_text) {
    if (!container)
        return;
    var form = $("form", container)[0];
    var submit_label = $("input[type=submit]", container).val();

    $(form).validate({
        ignore: "",
        onfocusin: null,
        onfocusout: null,
        onclick: null,
        showErrors: formShowErrors,
        messages: {
            contact_phone: {
                required: "Введите телефон",
                email: "Введите правильный телефон, например: +7 1112223344"
            },
            contact_email: {
                required: "Введите e-mail",
                email: "Введите правильный e-mail"
            },
            contact_type: {
                required: "Выберите предмет аренды"
            }
        },
        rules: {
            contact_phone: {
                required: true,
                phone: true,
            },
            contact_email: {
                required: true,
                email: true,
            },
            contact_type: {
                required: true
            }
        },
        submitHandler: function (form) {
            $("input[type=submit]", container).attr("disabled", "true");
            show_loader($("input[type=submit]", container)[0]);
            $.ajax({
                url: $(form).attr("action"),
                type: 'post',
                data: $(form).serialize(),
                dataType: 'json',
                success: function (result) {
                    hide_loader($("input[type=submit]", container)[0]);
                    $("input[type=submit]", container).removeAttr("disabled");
                    if (!result || result.errors || result == "0") {
                        alert("Во время отправки произошла ошибка, попробуйте ещё раз.");
                        return;
                    }
                    if (!success_text) {
                        success_text = "Ваше заявка успешно отправлена.<br/>Мы перезвоним вам в течение 10 минут.";
                    }

                    $(form).hide().after("<div class='success'>" + success_text + "</div>");
                },
                error: function () {
                    hide_loader($("input[type=submit]", container)[0]);
                    $("input[type=submit]", container).removeAttr("disabled");
                    alert("Во время отправки произошла ошибка, попробуйте ещё раз.");
                }
            });
            return false;
        }
    });
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
    beforeLoad: function () {
        this.href = $(this.element).attr("data-popup_href");
    },
    beforeShow: function () {
        $("body").css({
            'overflow-y': 'hidden'
        });
    },
    afterShow: function () {
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

function ie8fix() {
    fancy_sets.helpers.overlay.css = {};
}

var myMap = null;

function bindMap() {
    if (!$("section#map")[0])
        return;
    c = [51.549015, 85.90696];
    $("section#map #yandex-map").attr("loaded", true);
    myMap = new ymaps.Map($("section#map #yandex-map")[0], {
        center: [c[0] + 2.2, c[1] - 2],
        zoom: 6
    });
    //myMap.setType('yandex#hybrid');
    myMap.controls
        .add('zoomControl', {
            left: 5,
            top: 15
        });

    var placemark = new ymaps.Placemark(c, {
        balloonContentBody: "База отдыха &laquo;Турсиб&raquo;<br/>" + address,
        closeButton: true
    });
    myMap.geoObjects.add(placemark);
    placemark.balloon.open();
}

function updateActiveTab(tab_cont) {
    if (!tab_cont)
        return false;
    var swiper = $(".swiper-container:visible", tab_cont).data('swiper');
    if (swiper) {
        //$(swiper).reInit();
        return false;
    }
    $(".swiper-container:visible", tab_cont).swiper({
        mode: 'horizontal',
        freeMode: true,
        freeModeFluid: true,
        slidesPerView: 'auto',
        slidesPerViewFit: false,
        preventLinks: true,
        preventLinksPropagation: true,
        roundLengths: true,
        centeredSlides: false
    });
}

$(document).ready(function () {

    $('.notice-agree').bind('click', function () {
        $.ajax({
            url: '/include/privacy-agreement.php',
            type: "post",
            data: {
                action: 'agreement'
            },
            dataType: "json"
        });
        $('.notice-wrapper').fadeOut();
    });

    if ($('body').hasClass('new-year-design')) {
        $('#wrapper').snowfall({image: "/bitrix/templates/sushman/img/flake.png", minSize: 10, maxSize: 32});
    }

    if ($('body').hasClass('show-modal-window')) {
        var phone = $('header .phone').html();
        if (phone) {
            //var text = 'Заказы с сайта временно не принимаются.<br/><br/> Звоните на ' + phone;
            var text = 'Друзья, у нас отключили электричество.<br><br>Нет возможности сегодня принять заказы.<br><br>Приносим свои извинения.';
            $.fancybox(text, {
                type: 'html',
                autoHeight: true,
                minHeight: 20,
                padding: 40,
                wrapCSS: 'fancybox-responseMessage',
                closeBtn: false,
                closeClick: false, // prevents closing when clicking INSIDE fancybox
                helpers: {
                    overlay: {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
                }
            });
        }
    }

    $('.paysystems a').fancybox({
        //type: 'text',
        autoHeight: true,
        minHeight: 20,
        padding: 40,
        wrapCSS: 'fancybox-box',
        maxWidth: 1000
    });


    $('.recall-form-wrapper form').submit(function (e) {

        e.preventDefault();

        var form = $(this);
        var phone = form.find('input[name=phone]').val();

        form.find('.error').removeClass('error');

        if (!phone.length || ~phone.indexOf('_')) form.find('input[name=phone]').addClass('error');
        if (form.find('input[name=hidden]').val().length) form.find('input[name=phone]').addClass('error');

        if (form.find('.error').length) return;

        $.ajax({
            url: '/include/recall.php/',
            type: 'post',
            dataType: "json",
            data: {
                phone: phone,
                action: 'submit'
            },
            success: function (response) {

                $('.recall-form-wrapper').removeClass('active');

                $.fancybox(response.message, {
                    type: 'html',
                    minHeight: 50,
                    padding: 40,
                    wrapCSS: 'fancybox-responseMessage',
                });
            }
        });
    });

    $(document).bind('click touchstart', function (e) {

        var array = ['recall-button', 'recall-form-wrapper'], returnFunction = false;

        array.forEach(function (classname) {

            if ($(e.target).closest('.' + classname).length) {

                returnFunction = true;
                return;
            }
        });

        if (returnFunction) return;

        $('.recall-form-wrapper').removeClass('active');
    });

    $('a.recall-button').bind('click', function () {

        $('.recall-form-wrapper').toggleClass('active');
        $('.recall-form-wrapper .error').removeClass('error');
    });

    if ($.cookie("city_first") == "1") {
        var uri = new URI(document.location.href);
        $.removeCookie("city_first", {path: '/', domain: uri.domain(true)});
        /*        var popup = $("<div class='popup-form' style='display:none;height:auto;'><h2>Определение города</h2><form style='height:160px;'><p style='text-align:center;color:#fff;font-size:22px;line-height:28px;padding:20px 50px;'>Ваш город определен как <span class='city-name'></span></p><div class='fields'><input type='submit' value='Перейти на сайт'/></div></form></div>");
         popup.find('.city-name').text( $("header select[name=city] option[selected]").text() );
         popup.appendTo(document.body);
         popup.find("input[type=submit]").click(function() {
         $(".fancybox-close").trigger("click");
         return false;
         });
         $.fancybox(popup,{
         margin:20,
         padding:0,
         maxHeight: 800,
         fitToView: true,
         loop: false,
         autoSize: true,
         type: 'inline',
         tpl:{
         wrap     : '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin popup-form-wrapper"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
         closeBtn: '<a title="Закрыть" class="fancybox-item fancybox-close" href="javascript:;"></a>'
         },
         helpers: {
         title: null,
         overlay: {
         css: {
         background: 'rgba(0, 0, 0, 0.8)'
         }
         }
         }
         });        */
    }

    $("footer .right-block a").click(function () {
        return false;
    });
    bindTopButton();
    $("section select").not("#ORDER_PROP_10").selectBox();
    $("select[name=city]").hide();
    if ($("select[name=city] option").length > 0) {
        $("select[name=city]").selectBox();
        if ($("select[name=city] option").length == 1)
            $("select[name=city]").data('selectBox-control').addClass('selectBox-disabled');
    } else {
        $("select[name=city]").after("<br/><br/>");
    }
    var changed = false;
    $("select[name=city]").bind("beforeopen", function (e) {
        var x = -12;
        $("ul.selname-city li").each(function () {
            $(this).css({
                left: x
            });
            x -= 16;
        });
    }).bind("change", function () {
        changed = true;
        return false;
    }).bind("beforeclose", function (e) {
        return changed;
    });
    if ($("select#ORDER_PROP_10")[0]) {
        $("select#ORDER_PROP_10").chosen();
        if (/Android/i.test(window.navigator.userAgent)) {
            if (/Mobile/i.test(window.navigator.userAgent)) {
                var oldstr;
                setInterval(function () {
                    var s = $("select#ORDER_PROP_10").data('chosen').search_field.val();
                    if (s && oldstr && oldstr != s) {
                        oldstr = s;
                        var chsn = $("select#ORDER_PROP_10").data('chosen');
                        if (chsn.results_showing) {
                            chsn.search_field.trigger("keyup.chosen");
                        }
                    }
                }, 1000);
            }
        }
    }


    if ($.browser.msie && $.browser.version < 9) {
        ie8fix();
    }
    $(".galery").fancybox(fancy_sets);
    $(".fancy-button").fancybox({
        padding: 0,
        margin: 0,
        closeBtn: true,
        loop: false,
        closeClick: false,
        openEffect: 'none',
        closeEffect: 'none',
        nextEffect: 'none',
        prevEffect: 'none',
        tpl: {
            wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin popup-form-wrapper"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
            closeBtn: '<a title="Закрыть" class="fancybox-item fancybox-close" href="javascript:;"></a>',
        },
        helpers: {
            title: null
        }
    });

    // Phone mask
    // $("input#ORDER_PROP_3").inputmask({
    //     mask: "+7 9999999999",
    //     showMaskOnHover: false,
    //     onincomplete: function() {
    //         this.value = $(this).val().replace(/_/g, "");
    //     }
    // });

    $('input#ORDER_PROP_3').mask("+7 (999) 999-9999");
    $("input#total_cash").inputmask({'alias': 'numeric'});

    $(document).on('change keyup', 'input#total_cash', function () {
        var total = parseInt($(this).parent().prev().text().replace(" ", ""));
        var tt = parseInt($(this).val().replace(" ", ""));
        if (isNaN(tt)) {
            return false;
        }
        //tt = Math.ceil(tt/500)*500;
        $(this).parent().next().find(".change").text((tt - total));
        return true;
    });
    $(document).on('change', "select[name='phone_type']", function (e) {
        var val = $(this).val();
        switch (val) {
            case "mob":
                $('input#ORDER_PROP_3').mask("+7 (999) 999-9999");
                break;

            case "dom":
                $('input#ORDER_PROP_3').mask("(3843) 99-99-99");
                break;
        }
    });

    $(document).on('change', ".filter-block select", function (e) {
        $.cookie("my_sort", $(this).val(), {path: "/"});
        $(this).parents("form").submit();
    });
    if ($('.filter-block select').length) {
        var selectBox = new SelectBox($('.filter-block select'), settings = {});
        selectBox.refresh();
    }

    $(window).scroll(function (e) {
        var b = $(".search-country+.inner-block");
        if (!b.length) return;
        var b_top = b.prev().offset().top + b.prev().outerHeight() + 20;
        var b_left = b.offset().left;
        var st = $(window).scrollTop();
        //var max_offset = 843;
        var bot_offset = 0;
        if ($(".tabs-wrapper")[0]) {
            bot_offset = 1000;
        } else {
            bot_offset = 667;
        }
        if (!$("#wok-garnish")[0] && st > b_top && $('li.bx_catalog_item_container').length > 12) {
            if (bot_offset + st + 82 < $(document).height()) {
                b.css({
                    "position": "fixed",
                    "left": "50%",
                    "margin-left": -493
                });
                y = 20;//st-220;
            } else {
                b.css({
                    "position": "absolute",
                    "left": 0,
                    "margin-left": 0
                });
                y = $("section.menu > .center-col").height() - bot_offset + 162;
            }
            b.css({top: y});
        } else {
            //console.log(st, b_top);
            if (st < b_top) {
                b.css({
                    "position": "static",
                    "left": "auto",
                    "top": "auto",
                    "margin-left": 0

                });
            }
        }
    });

    $('input, textarea').placeholder();
    if ($(".ingr-slider")[0]) {
        $(".ingr-slider .swiper-container").swiper({
            mode: 'horizontal',
            freeMode: true,
            freeModeFluid: true,
            slidesPerView: 'auto',
            slidesPerViewFit: false,
            roundLengths: true,
            onSlideClick: function (swiper) {
                var el = swiper.clickedSlide;
                $("input[type=checkbox]", el).trigger("click");
            },
            onTouchMove: function (swiper) {
                checkArrows(swiper);
            },
            onSlideChangeEnd: function (swiper) {
                checkArrows(swiper);
            }
        });
        checkArrows($(".ingr-slider .swiper-container").data('swiper'));
        $(".ingr-slider .arrow-gallery").click(function () {
            var swiper = $(this).parent().find(".swiper-container").data('swiper');
            swiper.swipeToDiff(($(this).is(".arrow-right") ? -1 : 1) * $(swiper.container).outerWidth() * 2 / 3);
        });
    }
    if ($(".country-slider")[0]) {
        $(".country-slider .swiper-container").swiper({
            mode: 'horizontal',
            freeMode: true,
            freeModeFluid: true,
            slidesPerView: 'auto',
            slidesPerViewFit: false,
            roundLengths: true,
            onSlideClick: function (swiper) {
                var el = swiper.clickedSlide;
                $("input[type=checkbox]", el).trigger("click");
            },
            onTouchMove: function (swiper) {
                checkArrows(swiper);
            },
            onSlideChangeEnd: function (swiper) {
                checkArrows(swiper);
            }
        });
        checkArrows($(".country-slider .swiper-container").data('swiper'));
        $(".country-slider .arrow-gallery").click(function () {
            var swiper = $(this).parent().find(".swiper-container").data('swiper');
            swiper.swipeToDiff(($(this).is(".arrow-right") ? -1 : 1) * $(swiper.container).outerWidth() * 2 / 3);
        });
    }
    if ($("#main-slider")[0]) {
        $(".swiper-pagination").css({marginTop: -$("#main-slider .swiper-container .swiper-slide").length * 10 - 1});
        $("#main-slider .swiper-container").swiper({
            mode: 'horizontal',
            loop: $("#main-slider .swiper-container .swiper-slide").length > 1,
            pagination: $("#main-slider .swiper-container .swiper-slide").length > 1 ? ".swiper-pagination" : "",
            paginationClickable: true,
            autoplayDisableOnInteraction: true,
            autoplay: 5000
        });
    }

    $(".line-append:not(.sect)").wrapInner("<span/>");
    $(".line-append:not(.sect) span").append("<span class='line2 line-left'></span>", "<span class='line2 line-right'></span>");

    /*    $(".items .bx_catalog_item_container .pic,.inner-block .recommends > li > a,.inner-block .bcont a.pic").fancybox($.extend({}, fancy_sets, {
     type: 'ajax',
     fitToView:false,
     autoSize:false,
     autoWidth:true,
     autoHeight:true,
     scrolling:'no',
     minHeight:1000,
     beforeShow: function() {
     this.minHeight = $(".fancybox-inner .item-info").outerHeight();
     },
     afterShow:function() {
     var n = noty({
     text: $(".fancybox-inner p.gray").html(),
     theme:'relax',
     layout:'bottomRight',
     killer:true,
     animation: {
     open: 'animated bounceInLeft', // Animate.css class names
     close: 'animated bounceOutLeft', // Animate.css class names
     }
     });
     },
     afterClose:function() {
     //$.noty.closeAll();
     },

     helpers: {
     title: null,
     overlay: fancy_sets.helpers.overlay
     }
     }));*/

    if ($(".item-info")[0]) {
        /*var n = noty({
         text: $(".item-info p.gray").html(),
         theme:'relax',
         layout:'bottomRight',
         killer:true,
         animation: {
         open: 'animated bounceInLeft', // Animate.css class names
         close: 'animated bounceOutLeft', // Animate.css class names
         }
         });*/
    }

    getWokPrice = function (el) {
        var price = weight = 0;
        var pel_id = $(el).find(".t").attr("el_id");
        if (pel_id) {
            result = {
                price: parseInt($("#" + pel_id).find(".bx_price").text(), 10),
                weight: parseInt($("#" + pel_id).find(".weight").text(), 10)
            };
            if (isNaN(result.price) || result.price < 0)
                result.price = 0;
            if (isNaN(result.weight) || result.weight < 0)
                result.weight = 0;
            return result;
        }
        return {
            price: 0,
            weight: 0
        };
    };

    wok_add_handler = function (block_selector, el) {

        $(block_selector).removeClass("err");
        var el = $(el).parents(".bx_catalog_item_container:first")[0];

        var const_pic = $(el).find(".pic").attr("const_pic");
        var pic_el = $("#wok-container").find($(block_selector).is("#wok-selected-garnish") ? ".wok-pic" : ".wok-pic-filling")[0];

        if (const_pic) {
            $("#wok-container .wok-pic ").stop(true, true).fadeOut(300);
            $.imgpreload([const_pic], function () {
                $(pic_el).css("background-image", "url(" + const_pic + ")");
                $("#wok-container .wok-pic ").stop(true, true).fadeIn(300);
            });
        } else {
            $(pic_el).css("background-image", "none");
        }

        var title = $(el).find(".title").text();
        $(block_selector + " .t").text(title);
        flyToConstructor(el, true, block_selector);
        var el_id = $(el).attr("id");
        $(block_selector + " .t").attr("el_id", el_id);
        $("#wok-container").removeAttr("cid");
        a = getWokPrice("#wok-selected-garnish");
        b = getWokPrice("#wok-selected-filling");
        if (a['price'] + b['price'] > 0) {
            var price = a['price'] + b['price'];
            var weight = a['weight'] + b['weight'];
            $("#wok-container .price-block").show();
            $("#wok-container .price-block .weight").text(weight > 0 ? weight + " г." : "");
            $("#wok-container .price-block .price").html(price + " <span class='ico-rub'></span>");
        } else
            $("#wok-container .price-block").hide();

        if (a['price'] > 0 && b['price'] > 0) {
            $("#wok-container .wok-pic").removeClass("pic1").addClass("full");
            return false;
        }
        if (a['price'] > 0) {
            $("#wok-container .wok-pic").addClass("pic1");
            return false;
        }
        return false;
    };

    get_wok_product_id = function (el) {
        var pel_id = $(el).find(".t").attr("el_id");
        var product_id = '';
        if (pel_id) {
            product_id = $('#' + pel_id).find(".addto").attr("data-product-id");
        }
        return product_id;
    };

    if ($("#wok-container")[0]) {

        $("#wok-container .buy").click(function () {
            var garnish_id = get_wok_product_id('#wok-selected-garnish');
            var filling_id = get_wok_product_id('#wok-selected-filling');
            var cid = $("#wok-container").attr("cid");
            if (!cid) {
                cid = new Date().getTime();
                $("#wok-container").attr("cid", cid);
            }
            $("#wok-selected-garnish").toggleClass("err", !garnish_id);
            $("#wok-selected-filling").toggleClass("err", !filling_id);
            if (garnish_id && filling_id) {
                $.post("/cart/add_complex/", {
                    ids: [garnish_id, filling_id].join(),
                    cid: cid,
                    cid_type: 'wok'
                }, function () {
                    sbbl.refreshCart();
                    $("#wok-container .price-block").hide();
                    $("#wok-container .wok-pic").removeClass("pic1 full").css("background-image", "url(/bitrix/templates/sushman/img/wok-pic-empty.png);");
                    $("#wok-container .wok-pic-filling").css("background-image", "none");
                    $("#wok-selected-garnish .t").removeAttr("el_id").text("Выберите гарнир");
                    $("#wok-selected-filling .t").removeAttr("el_id").text("Выберите начинку");
                });
            }
            return false;
        });

        $.imgpreload(['/bitrix/templates/sushman/img/wok-pic1.png', '/bitrix/templates/sushman/img/wok-pic-empty.png', '/bitrix/templates/sushman/img/wok-pic-full.png']);

        $("#wok-garnish .addto").click(function () {
            return wok_add_handler("#wok-selected-garnish", this);
        });
        $("#wok-filling .addto").click(function () {
            return wok_add_handler("#wok-selected-filling", this);
        });

    }


    if ($("#pizza-container")[0]) {
        $.imgpreload([
            '/bitrix/templates/sushman/img/pizza-pic-empty.png',
            //            '/bitrix/templates/sushman/img/pizza-pic-l.png',
            //            '/bitrix/templates/sushman/img/pizza-pic-r.png',
            //            '/bitrix/templates/sushman/img/pizza-pic-full.png',
        ]);

        pizza_update = function () {
            a = getWokPrice("#wok-selected-pizza1");
            b = getWokPrice("#wok-selected-pizza2");
            if (a['price'] + b['price'] > 0) {
                var price = a['price'] + b['price'];
                var weight = a['weight'] + b['weight'];
                $("#pizza-container .price-block").show();
                $("#pizza-container .price-block .weight").text(weight > 0 ? weight + " г." : "");
                $("#pizza-container .price-block .price").html(price + " <span class='ico-rub'></span>");
            } else
                $("#pizza-container .price-block").hide();
            if (a['price'] > 0 && b['price'] > 0) {
                $("#pizza-container .pizza-pic").removeClass("r l").addClass('full');
                return;
            }
            if (a['price'] > 0) {
                $("#pizza-container .pizza-pic").removeClass("r full").addClass('l');
                $("#pizza-container .pizza-pic .rp").css("background-image", "none");
                return;
            }
            if (b['price'] > 0) {
                $("#pizza-container .pizza-pic").removeClass("l full").addClass('r');
                $("#pizza-container .pizza-pic .lp").css("background-image", "none");
                return;
            }
            $("#pizza-container .pizza-pic").removeClass("l r full");
            $("#pizza-container .pizza-pic span").css("background-image", "none");
        };

        pizza_add_handler = function (block_selector, el) {
            $(block_selector).removeClass("err").addClass("selected");
            var el = $(el).parents(".bx_catalog_item_container:first")[0];
            var title = $(el).find(".title").text();
            var const_pic = $(el).find(".pic").attr("const_pic");
            var pic_el = $("#pizza-container .pizza-pic").find($(block_selector).is("#wok-selected-pizza1") ? ".lp" : ".rp")[0];

            if (const_pic) {
                $(pic_el).css("background-image", "url(" + const_pic + ")");
            } else {
                $(pic_el).css("background-image", "none");
            }

            $(block_selector).find(" .t").text(title);
            flyToConstructor(el, true, block_selector);
            var el_id = $(el).attr("id");
            $(block_selector).find(" .t").attr("el_id", el_id);
            $("#pizza-container").removeAttr("cid");
            pizza_update();
            return false;
        };
        var el = $("#pizza-container .steps li .close").click(function () {
            var cont = $(this).parents("li:first")[0];
            $(cont).removeClass("selected");
            $(cont).find('.t').removeAttr("el_id").text("Добавьте половинку");
            pizza_update();
            return false;
        });

        $(".addto").click(function () {
            var el = $("#pizza-container .steps li:not(.selected)")[0];
            if (!el) {
                el = $("#pizza-container .steps li:first")[0];
            }
            return pizza_add_handler(el, this);
        });
        $("#pizza-container .buy").click(function () {
            var pizza1_id = get_wok_product_id('#wok-selected-pizza1');
            var pizza2_id = get_wok_product_id('#wok-selected-pizza2');
            var cid = $("#pizza-container").attr("cid");
            if (!cid) {
                cid = new Date().getTime();
                $("#pizza-container").attr("cid", cid);
            }
            $("#pizza1-selected-garnish").toggleClass("err", !pizza1_id);
            $("#pizza2-selected-filling").toggleClass("err", !pizza2_id);
            if (pizza1_id && pizza2_id) {
                $.post("/cart/add_complex/", {
                    ids: [pizza1_id, pizza2_id].join(),
                    cid: cid,
                    cid_type: 'pizza'
                }, function () {
                    sbbl.refreshCart();
                    $("#pizza-container .steps li .close").trigger("click");
                });
            }
            return false;
        });
    }


    $(".items.small-items .buy").click(function () {
        if ($(this).is(".btn-disabled"))
            return false;
        if ($(this).is(".btn-addto-constructor"))
            return true;
        var el = $(this).parents(".bx_catalog_item_container:first")[0];
        flyToBasket(el, true);
        var url = $(this).attr("data-add-url");
        if (!url) {
            url = $(this).attr("href");
        } else {
            url += "&ajax_basket=Y&quantity=1";
        }
        BX.ajax.loadJSON(
            url, {},
            BX.delegate(function (result) {
                var successful = (result && 'OK' === result.STATUS);
                if (successful) {
                    sbbl.refreshCart();
                }
            }, this)
        );
        return false;
    });

    $(".tabs-wrapper .tabs li").click(function () {
        if ($(this).is(".active"))
            return false;
        var index = $(".tabs-wrapper .tabs li").index(this);

        $(".tabs-wrapper .tabs .active").removeClass("active");
        $(this).addClass("active");
        $(".tabs-wrapper .conts > .tab-cont.active").removeClass("active");
        var tab_cont = $(".tabs-wrapper .conts > .tab-cont:eq(" + index + ")").addClass("active")[0];
        updateActiveTab(tab_cont);
        return false;
    });
    updateActiveTab($(".tabs-wrapper .conts > .tab-cont.active")[0]);

    $(".basket-item-remove").click(function () {
        var row = $(this).parents("tr:first")[0];
        $("#basket_items_list .bg").show();
        var cid = $(this).attr("data-cid");
        if (!cid)
            cid = '';
        $.post($(this).attr("href"), function (result) {
            if (cid) {
                $("#basket_items tr[data-cid=" + cid + "]").hide();
            }
            var row_id = $(row).attr("id");
            $(row).hide().after(
                $("<tr id='deleted_" + row_id + "'><td colspan='9'><p style='text-align:center;'><a class='undelete' data-cid='" + cid + "' href='/cart/undelete/?BASKET_ITEM_ID=" + row_id + "&CID=" + cid + "'>Восстановить</a></p></td></tr>")
            );
            recalcBasketAjax();
        });
        return false;
    });

    $("#basket_form_container").on("click", ".undelete", function () {
        var row = $(this).parents("tr:first")[0];
        $("#basket_items_list .bg").show();
        var cid = $(this).attr("data-cid");
        var el = this;
        $.post($(this).attr("href"), function (result) {
            var row = $(el).parents("tr:first")[0];
            var row_id = $(row).attr("id").replace("deleted_", "");
            $("#" + row_id).show();
            if (cid) {
                $("#basket_items tr[data-cid=" + cid + "]").show();

            }

            $(row).remove();
            recalcBasketAjax();
        });
        return false;
    });

    $("header select[name=city]").change(function () {
        if ($(document.body).is(".no-order")) {
            $(this).parents("form:first").submit();
        }
        var postData = {
            'sessid': BX.bitrix_sessid(),
            'site_id': BX.message('SITE_ID'),
            'action': 'recalculate',
            'action_var': 'action'
        };
        var that = this;
        BX.ajax({
            url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
            method: 'POST',
            data: postData,
            dataType: 'json',
            onsuccess: function (result) {
                BX.closeWait();
                sbbl.refreshCart();
                $(that).parents("form:first").submit();
            }
        });
    });

    $("#bx_cart_block").on("click", ".arrow-down", function () {
        var cartlist = $("#bx_cart_block .bx_item_listincart");
        if (cartlist.is(":visible")) {
            cartlist.hide();
        } else {
            cartlist.show();
        }
        $(document).on("click touchstart", function (e) {
            if ($(e.target).parents(".bx_item_listincart")[0]) {
                return true;
            }
            $("#bx_cart_block .bx_item_listincart").hide();
            $(document).off("click touchstart");
            return false;
        });
        return false;
    });

    $(".bx_order_make").on("change", ".city-district", function () {
        var price = $("option:selected", this).attr("data-price");
        if (!price)
            price = '0';
        if ($('#ID_DELIVERY_Plain_all')[0])
            $('#ID_DELIVERY_Plain_all')[0].checked = true;
        submitForm();
        return true;
    });
    $(".bx_order_make").on("change", ".city-street, input#ORDER_PROP_11", function () {
        if ($('.city-block option:selected').val() == 3) {
            submitForm();
            return true;
        }
    });


    $(".bx_order_make .city-district").trigger("change");
    if ($(".search-ingr input[type=checkbox]:checked").length > 0) {
        $(".search-ingr").addClass("expanded");
    }
    $(".search-ingr .title").click(function () {
        $(".search-ingr").toggleClass("expanded");
        return false;
    });

    if ($(".search-country input[type=checkbox]:checked").length > 0) {
        $(".search-country").addClass("expanded");
    }
    $(".search-country .title").click(function () {
        $(".search-country").toggleClass("expanded");
        return false;
    });

    $("#wok-filling .price-block .price-block2, .wok-filling.bx_catalog_item_container .price-block2,#wok-garnish .price-block .price-block2, .wok-garnish.bx_catalog_item_container .price-block2").click(function () {
        $(this).parent().parent().find(".addto").trigger("click");
        return false;
    });

    $("#discounts label").click(function () {
        $(this).toggleClass("ccc");
        if (!$(this).is(".ccc")) {
            //return false;            
        }
        $("#discounts label.ccc").not(this).removeClass("ccc");
        return true;
    });
    $("#discounts input[type=radio]").click(function () {
        if ($(this).is("#discount_none"))
            return true;
        if (!$(this).next().is(".ccc")) {
            $("#discount_none")[0].checked = true;
        }
        // $("#ORDER_PROP_ORDER_DISCOUNT_ID").val($("#discounts input[type=radio]:checked").val());
        $valueDiscountId = $('.li-discount-id').text();
        if ($(this).next().is(".ccc")) {
            $("#ORDER_PROP_ORDER_DISCOUNT_ID").val( $valueDiscountId );
        }else{
            $("#ORDER_PROP_ORDER_DISCOUNT_ID").val('');
        }
        submitForm();
        return true;
    });
	
	


    $(".ajaxform").fancybox({
        margin: 20,
        padding: 0,
        maxHeight: 800,
        fitToView: true,
        width: 948,
        height: 807,
        loop: false,
        autoSize: true,
        type: 'ajax',
        tpl: {
            wrap: '<div class="fancybox-wrap" tabIndex="-1"><div class="fancybox-skin popup-form-wrapper"><div class="fancybox-outer"><div class="fancybox-inner"></div></div></div></div>',
            closeBtn: '<a title="Закрыть" class="fancybox-item fancybox-close" href="javascript:;"></a>'
        },
        helpers: {
            title: null,

            overlay: {
                css: {
                    background: 'rgba(0, 0, 0, 0.8)'
                }
            }
        },
        beforeLoad: function (el) {
            if ($(this.element).attr("data-popup_href")) {
                this.href = $(this.element).attr("data-popup_href");
            }
        },
        beforeShow: function () {
            $(".fancybox-inner input[placeholder], .fancybox-inner textarea[placeholder]").placeholder();
        }
    });
    if (window.location.hash == '#feedback') {
        $("a.ajaxform[href*=feedback]:first").trigger("click");
    }

    addToCons = function (data_id) {
        if ($(".fancybox-inner .item-info")[0]) {
            $(".fancybox-close").trigger("click");
            $(document.body).stop(true, true).delay(600).animate({scrollTop: 0}, 200, function () {
                $("a.addto[data-id=" + data_id + "]").trigger("click");
            });
        } else {
            $(document.body).stop(true, true).delay(300).animate({scrollTop: 0}, 200, function () {
                $("a.addto[data-id=" + data_id + "]").trigger("click");
            });
        }
    }
    $(document).on("click", ".btn-addto-constructor", function () {
        var data_id = $(this).attr("data-id");
        var dir = URI(document.location.href).directory().replace('/menu/', '');
        if ($(this).is('.wok-cons')) {
            if (dir == 'wok')
                addToCons(data_id);
            else
                document.location.href = '/menu/wok/#wok-add-' + data_id;
        } else if ($(this).is('.pizza-cons')) {
            if (dir == 'half')
                addToCons(data_id);
            else
                document.location.href = '/menu/half/#pizza-add-' + data_id;
        }
        return false;
    });

    if (window.location.hash.substring(0, 9) == '#wok-add-') {
        var data_id = window.location.hash.substring(9);
        $("a.addto[data-id=" + data_id + "]").trigger("click");
        //window.location.hash = '';
    }
    if (window.location.hash.substring(0, 11) == '#pizza-add-') {
        var data_id = window.location.hash.substring(11);
        $("a.addto[data-id=" + data_id + "]").trigger("click");
        //window.location.hash = '';
    }

});