<?

include $_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include.php";

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class Streets
{
    const code = 'Oe2F80R3ux';
    const HLBlockID = 7;
    const cityID = 2;

    protected $streets;

    function __construct()
    {
        if ($_GET['code'] != self::code) {
            die('No access denied');
        }
        if (!CModule::IncludeModule("highloadblock")) {
            die('Can\'t include highloadblock module');
        }

        $this->streets = file($_SERVER['DOCUMENT_ROOT'] . '/kemStreets.txt');
    }

    function auto_run()
    {
        $hlblock = HL\HighloadBlockTable::getById(self::HLBlockID)->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        foreach ($this->streets as $street) {

            $data = array(
                "UF_NAME" => $street,
                "UF_CITY_ID" => self::cityID
            );

            $entity_data_class::add($data);
        }
    }
}

$obj = new Streets;
$obj->auto_run();

?>