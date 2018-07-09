<div class="line"></div>
<div class="search-block">
    <h3>Поиск по ингредиентам</h3>

    <div class="note">выберите понравившиеся ингредиенты</div>
</div>
<div class="line"></div>
<div class="ingr-slider">
    <form method="post" action="/search/" id="search-main">
        <div class="arrow-gallery arrow-left"></div>
        <div class="arrow-gallery arrow-right"></div>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?
                $_GET['sort_id'] = "UF_SORT";
                $_GET['sort_type'] = "ASC";
                $APPLICATION->IncludeComponent("bitrix:highloadblock.list", "index", Array(
                        "BLOCK_ID" => "3"
                    )
                ); ?>
            </div>
        </div>
        <div class="centered" style="margin:16px 0;">
            <a href="#" class="btn-with-icon btn-search"
               onclick="$('#search-main').submit();return false;"><span>искать</span></a>
        </div>
    </form>
</div>