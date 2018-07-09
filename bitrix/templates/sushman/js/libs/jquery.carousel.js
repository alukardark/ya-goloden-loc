(function($){
    // Determine if we on iPhone or iPad
    var isiOS = false;
    var agent = navigator.userAgent.toLowerCase();
    if(agent.indexOf('iphone') >= 0 || agent.indexOf('ipad') >= 0){
        isiOS = true;
    }

    $.fn.doubletap = function(onDoubleTapCallback, onTapCallback, delay){
        var eventName, action;
        delay = delay == null? 500 : delay;
        eventName = isiOS == true? 'touchend' : 'click';

        $(this).bind(eventName, function(event){
            var now = new Date().getTime();
            var lastTouch = $(this).data('lastTouch') || now + 1 /** the first time this will make delta a negative number */;
            var delta = now - lastTouch;
            clearTimeout(action);
            if(delta<500 && delta>0){
                if(onDoubleTapCallback != null && typeof onDoubleTapCallback == 'function'){
                    onDoubleTapCallback(event);
                }
            }else{
                $(this).data('lastTouch', now);
                action = setTimeout(function(evt){
                    if(onTapCallback != null && typeof onTapCallback == 'function'){
                        onTapCallback(evt);
                    }
                    clearTimeout(action);   // clear the timeout
                }, delay, [event]);
            }
            $(this).data('lastTouch', now);
        });
    };
    
    //iPAD Support
    $.fn.addTouch = function(){
        this.each(function(i,el){
            $(el).bind('touchstart touchmove touchend touchcancel',function(){
                //we pass the original event object because the jQuery event
                //object is normalized to w3c specs and does not provide the TouchList
                handleTouch(event);
            });
        });

        var handleTouch = function(event)
        {
            var touches = event.changedTouches,
                first = touches[0],
                type = '';

            switch(event.type)
            {
                case 'touchstart':
                    type = 'mousedown';
                    break;

                case 'touchmove':
                    type = 'mousemove';
                    event.preventDefault();
                    break;

                case 'touchend':
                    type = 'mouseup';
                    break;

                default:
                    return;
            }

            var simulatedEvent = document.createEvent('MouseEvent');
            simulatedEvent.initMouseEvent(type, true, true, window, 1, first.screenX, first.screenY, first.clientX, first.clientY, false, false, false, false, 0/*left*/, null);
            first.target.dispatchEvent(simulatedEvent);
        };
    };
    
    $.fn.carousel = function() {

        var lineWidth = 0;

        this.find('ul li').each(function(){
            lineWidth += $(this).outerWidth();
        })

        this.find('ul').width(lineWidth);

        var $this = this;

        return this.each(function(){
            var startPosX = 0;
            var posX = 0;

            var lineLeft = 0;

            $(this).mousemove(function(e){
                e.preventDefault()


                var mouseX = e.pageX;

                var shift = (mouseX - $this.offset().left) * (lineWidth - $this.width()) / $this.width();
                $this.scrollLeft(shift);

                return false;
            })
        })
    };
    
    $.fn.slide = function() {

        var lineWidth = 0;

        this.find('ul li a').each(function(){
            lineWidth += $(this).width()
        })

        this.find('ul').width(lineWidth);

        var $this = this;

        return this.each(function(){
            var startPosX = 0;
            var posX = 0;
            var onDrag = false;

            var lineLeft = 0;

            $(this).mousedown(function(e) {
                e.preventDefault();
                onDrag = true;
                startPosX = e.pageX;
                lineLeft = $(this).find('ul').position().left;
                return false
            })

            $(this).mouseup(function() {
                onDrag = false;
            })

            $(this).mouseleave(function() {
                onDrag = false;
            })


            $(this).mousemove(function(e){
                e.preventDefault()
                 posX = e.pageX;

                if (onDrag) {
                    var offset = posX - startPosX;
                    var left = lineLeft + offset;


                    if (left > 0) {
                        return
                    }

                    if (left + (lineWidth - $(this).width()) >= 0) {
                        $this.find('ul').css({left: left});
                    }
                }
                return false;
            })
        })
    };
    
})(jQuery);