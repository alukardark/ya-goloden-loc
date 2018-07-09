<div class="search-country">
    <div class="title">Поиск по<br/> странам<span class="arrow"></span></div>

    <div class="cont">
        <form method="post" action="/search-country/" id="search-aside-country">
            <?
            $_GET['sort_id'] = "UF_SORT";
            $_GET['sort_type'] = "ASC";
            $APPLICATION->IncludeComponent("bitrix:highloadblock.list", "aside-country", Array(
                    "BLOCK_ID" => "8"
                )
            ); ?>
            <div class="centered"><a href="#" class="btn-with-icon btn-search"
                                     onclick="$('#search-aside-country').submit();return false;"><span>искать</span></a></div>
        </form>
    </div>
</div>
