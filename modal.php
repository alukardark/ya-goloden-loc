<?
$DO_THIS = 0;

if ($DO_THIS) {
    global $city;
    $dontShowModal = ($city != 1);//false;//$_SESSION['dont-show-modal-window'];
    $bodyModalClass = '';

    if (!$dontShowModal) {
        $bodyModalClass = 'show-modal-window';
        $_SESSION['dont-show-modal-window'] = true;
    }
}

?>