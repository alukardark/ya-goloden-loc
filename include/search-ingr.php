<div class="search-ingr">
    <div class="title">Поиск по ингредиентам<span class="arrow"></span></div>

    <div class="cont">
        <form method="post" action="/search/" id="search-aside">
            <?
            $_GET['sort_id'] = "UF_SORT";
            $_GET['sort_type'] = "ASC";
            $APPLICATION->IncludeComponent("bitrix:highloadblock.list", "aside", Array(
                    "BLOCK_ID" => "3"
                )
            ); ?>
            <div class="centered"><a href="#" class="btn-with-icon btn-search"
                                     onclick="$('#search-aside').submit();return false;"><span>искать</span></a></div>
        </form>
    </div>
</div>
