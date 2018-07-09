<?

include $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include.php";

class CopyTradingOffers{
	
	const IBlockID = 3;
	const code = 'xVYw0Xh8Y3';
	
	protected $el;
	protected $items;
	
	protected $catsWithSales = array(50, 51, 52);
	
	function __construct(){
		
		if($_GET['code'] != self::code) die('No access denied');
		
		if(!CModule::IncludeModule("iblock")) die('Can\'t include iblock module');
		if(!CModule::IncludeModule("sale")) die('Can\'t include sale module');
		if(!CModule::IncludeModule("catalog")) die('Can\'t include catalog module');
		
		$this->el = new CIBlockElement;
		$this->items = $this->getItems();
	}
	
	function auto_run(){
		
		switch($_GET['action']){
			
			case 'delete':
				$this->deleteAction();
				break;
				
			case 'show':
				$this->showItems();
				break;
				
			case 'copy':
				$this->copyAction();
				break;
				
			default:
				die('No action selected');
		}
	}
	
	function showItems(){
		
		echo '<pre>';
		var_dump($this->items);
		echo '</pre>';
	}
	
	function deleteAction(){
		
		$arr = array(28,29);
		
		foreach($this->items as $item){
			
			if($item['PROPERTY_CITY_VALUE'] == 'kem'){
				
				if(in_array($item['PRODUCT_CATEGORY_ID'], $arr)){
				
					$this->deleteItem($item['ID']);
				}
			}
		}
	}
	
	function copyAction(){
		
		$arr = array(28,29);
		
		foreach($this->items as $item){
		
			if($item['PROPERTY_CITY_VALUE'] == 'nsk'){
				
				if(in_array($item['PRODUCT_CATEGORY_ID'], $arr)){
			
					$price = in_array($item['PRODUCT_CATEGORY_ID'], $this->catsWithSales) ? 
						$item['CATALOG_PRICE_1'] : 
						$item['PURCHASING_PRICE'];
						
					$price = $item['CATALOG_PRICE_1'];
				
					$arLoadArray = array(
						"MODIFIED_BY"       => CUser::GetID(),
						"IBLOCK_SECTION_ID" => false,
						"IBLOCK_ID"         => self::IBlockID,
						"NAME"              => $item['NAME'],
						"ACTIVE"            => $item['ACTIVE'],
						"DETAIL_TEXT"		=> array("TEXT" => $item['DETAIL_TEXT'], "TYPE" => "html"),
						"PROPERTY_VALUES"   => array(
							"CML2_LINK" 	=> $item["PROPERTY_CML2_LINK_VALUE"],
							"CITY"      	=> 'kem'
						),
					);
					
					$id = $this->el->Add($arLoadArray);
					
					$arFields = in_array($item['PRODUCT_CATEGORY_ID'], $this->catsWithSales) ? 
						array('ID' => $id, 'PURCHASING_PRICE' => $item['PURCHASING_PRICE']) :
						array('ID' => $id);
					
					if(CCatalogProduct::Add($arFields)){

						$arFields = Array(
							"PRODUCT_ID"        => $id,
							"CATALOG_GROUP_ID"  => 1,
							"PRICE"             => $price,
							"CURRENCY"          => "RUB"
						);

						CPrice::Add($arFields);
					}
					
					/*
					$this->el->SetPropertyValues(
						$id,
						$this->IBlockID,
						array($item['ID']),
						"CML2_LINK"
					);
					
					$arProps = array(

						//"CML2_LINK"	=> array($item['ID']),
						"CITY"		=> 'kem',
						//"TYPE_REF"	=> 
					);

					$this->el->SetPropertyValuesEx($id, $this->IBlockID, $arProps);
					*/
					
					echo 'Item added: ID '.$id.'<br/>';
				}
			}
		}
	}
	
	function deleteItem($id){
		
		$t = CIBlockElement::Delete($id);
		echo 'Item deleted: ID '.$id.'<br/>';
	}
	
	function getProductCategory($id){
		
		$return = array();
		
		$res = $this->el->GetList(
			array("SORT" => "ASC"),
			array("IBLOCK_ID" => 2, "ID" => $id),
			false,
			false,
			array('ID', 'IBLOCK_SECTION_ID')
		);
		
		while($row = $res->GetNext()){
		
			$return = $row;
		}
		
		return $return['IBLOCK_SECTION_ID'];
	}
	
	function getItems(){
		
		$return = array();
		$i = 0;
		
		$res = $this->el->GetList(
			array("SORT" => "ASC"),
			array("IBLOCK_ID" => self::IBlockID),
			false,
			false,
			array('ID', 'NAME', 'PROPERTY_CITY', 'ACTIVE', 'CATALOG_GROUP_1', 'DETAIL_TEXT', 'PROPERTY_CML2_LINK')
		);
		
		while($row = $res->GetNext()){
			
			$return[$i] = $row;
			$_t = array();
			
			$db_res = CCatalogProduct::GetList(
				array(),
				array("ID" => $row['ID']),
				false,
				array('PURCHASING_PRICE')
			);
			
			while($ar_res = $db_res->Fetch()){
				
				$_t = $ar_res;
			}
			
			$return[$i]['PURCHASING_PRICE'] = $_t['PURCHASING_PRICE'];
			$return[$i]['PRODUCT_CATEGORY_ID'] = $this->getProductCategory($row['PROPERTY_CML2_LINK_VALUE']);
			
			$i++;
		}
		
		return $return;
	}
}

$obj = new CopyTradingOffers;
$obj->auto_run();

?>