<?
if ($_POST['action'] == 'agreement') {
    session_start();
    $_SESSION['privacy_agreement'] = 1;
    setcookie('privacy_agreement', 1, time() + (3600 * 24 * 100), '/', 'ya-goloden.ru');
} else {
    if (!$_SESSION['privacy_agreement'] and !$_COOKIE['privacy_agreement']) {
        echo '<div class="notice-wrapper">
                <div class="notice-container">
                    <div class="notice-desc">На данном сайте используются cookie-файлы и аналогичные технологии. Если, прочитав это сообщение, вы остаетесь на сайте, это означает, что вы не возражаете против использования этих технологий.</div>
                    <div class="notice-buttons-wrapper">
                        <a href="/privacy/" target="_blank" class="notice-about">Подробнее</a>
                        <a href="javascript:" class="notice-agree">ОК!</a>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>';
    }
}
?>