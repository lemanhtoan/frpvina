<?php
if(!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to '.basename(__FILE__).' is not allowed.');

/**
 * @version 3.0
 * @package RealEstateManager 
 * @copyright 2012 OrdaSoft
 * @author 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru)
 * @description Search RealEstate for RealEstateManager Component
 * Homepage: http://www.ordasoft.com
*/

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
require_once ( JPATH_BASE .DS.'administrator'.DS.'components'.DS.'com_realestatemanager'.DS.'realestatemanager.class.others.php');
require_once ( JPATH_BASE .DS.'administrator'.DS.'components'.DS.'com_realestatemanager'.DS.'realestatemanager.class.others.php' );
require_once ( JPATH_BASE .DS.'components'.DS.'com_realestatemanager'.DS.'functions.php' );
require_once ( JPATH_BASE .DS.'components'.DS.'com_realestatemanager'.DS.'realestatemanager.main.categories.class.php' );
$doc = JFactory::getDocument(); 
$doc->addStyleSheet( JURI::base(true) .DS. 'components'.DS.'com_realestatemanager'.DS.'includes'.DS.'realestatemanager.css');
$doc->addStyleSheet( JURI::base(true) .DS. 'components'.DS.'com_realestatemanager'.DS.'includes'.DS.'jquery-ui.css');


$database = JFactory::getDBO();
// load language
$languagelocale = "";
$query = "SELECT l.title, l.lang_code, l.sef ";
$query .= "FROM #__rem_const_languages as cl ";
$query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id ";
$query .= "GROUP BY  l.title";
$database->setQuery($query);
$languages = $database->loadObjectList();

$lang = JFactory::getLanguage();
foreach ($lang->getLocale() as $locale) {
    foreach ($languages as $language) {
        if (strtolower($locale) == strtolower($language->title)
            || strtolower($locale) == strtolower($language->lang_code)
            || strtolower($locale) == strtolower($language->sef) ) {
            $mosConfig_lang = $locale;
            $languagelocale = $language->lang_code;
            break;
        }
    }
}

if ($languagelocale == '')
    $languagelocale = "en-GB";

global $langContent;
$langContent = substr($languagelocale, 0, 2);

$query = "SELECT c.const, cl.value_const ";
$query .= "FROM #__rem_const_languages as cl ";
$query .= "LEFT JOIN #__rem_languages AS l ON cl.fk_languagesid=l.id ";
$query .= "LEFT JOIN #__rem_const AS c ON cl.fk_constid=c.id ";
$query .= "WHERE l.lang_code = '$languagelocale'";
$database->setQuery($query);
$langConst = $database->loadObjectList();

foreach ($langConst as $item) {
   if(!defined($item->const) )  define($item->const, $item->value_const); // $database->quote()
}

if(!function_exists('sefRelToAbs')){
    function sefRelToAbs($value){
        //Need check!!!
        //Replace all &amp; with & as the router doesn't understand &amp;
        $url = str_replace('&amp;', '&', $value);
        if(substr(strtolower($url),0,9) != "index.php") return $url;
        $uri    = JURI::getInstance();
        $prefix = $uri->toString(array('scheme', 'host', 'port'));
        return $prefix.JRoute::_($url);
    }
}


global $mosConfig_absolute_path, $mosConfig_allowUserRegistration, $mosConfig_lang, $database, $mosConfig_live_site;
require_once('./components/com_realestatemanager/compat.joomla1.5.php');

$moduleclass_sfx=$params-> get('moduleclass_sfx');
$ItemId_tmp_from_params=$params->get ('ItemId');

$database->setQuery("SELECT id FROM #__menu WHERE link LIKE'%option=com_realestatemanager%' AND params LIKE '%back_button%'");
$ItemId_tmp_from_db = $database->loadResult();
if($ItemId_tmp_from_params=="") $ItemId=$ItemId_tmp_from_db; else $ItemId=$ItemId_tmp_from_params;

//categories
if (isset($langContent)) {
    $lang = $langContent;
    $query = "SELECT lang_code FROM #__languages WHERE sef = '$lang'";
    $database->setQuery($query);
    $lang = $database->loadResult();
    $lang = " c.language = '$lang' or c.language like 'all' or c.language like '' "
     . " or c.language like '*' or c.language is null ";
} else {
    $lang = "";
}


$categories[] = mosHTML :: makeOption(_REALESTATE_MANAGER_LABEL_ALL, _REALESTATE_MANAGER_LABEL_ALL);
$clist = com_house_categoryTreeList(0, '', true, $categories, '', $lang);

//select list for listing type
$listing_type[]=mosHtml::makeOption(_REALESTATE_MANAGER_LABEL_ALL,_REALESTATE_MANAGER_LABEL_ALL);
$listing_type[]=mosHtml::makeOption(1,_REALESTATE_MANAGER_OPTION_FOR_RENT);
$listing_type[]=mosHtml::makeOption(2,_REALESTATE_MANAGER_OPTION_FOR_SALE);
$listing_type_list = mosHTML :: selectList($listing_type, 'listing_type', 'class="inputbox modSearchSelect" size="1" style="width: 115px"', 'value', 'text', _REALESTATE_MANAGER_LABEL_ALL);
$params->def('listing_type_list',$listing_type_list);

//select list for listing status
$listing_status[] = mosHtml::makeOption(_REALESTATE_MANAGER_LABEL_ALL,_REALESTATE_MANAGER_LABEL_ALL);
$listing_status1=explode(',',_REALESTATE_MANAGER_OPTION_LISTING_STATUS);
$i=1; foreach($listing_status1 as $listing_status2) {$listing_status[]=mosHtml::makeOption($i,$listing_status2);$i++;}
$listing_status_list = mosHTML :: selectList($listing_status, 'listing_status', 'class="inputbox modSearchSelect" size="1" style="width: 115px"', 'value', 'text',_REALESTATE_MANAGER_LABEL_ALL);
$params->def('listing_status_list', $listing_status_list);

//price 
$query = "SELECT max(price+0) FROM #__rem_houses";
$database->setQuery($query);
$max_price = $database->loadResult();
$prices = $database->loadColumn();
rsort($prices,SORT_NUMERIC);
?>

<?php
function filterDateSearchMod() {
    global $realestatemanager_configuration;
    $DateToFormat = str_replace("d", 'dd', (str_replace("m", 'mm', (str_replace("Y", 'yy', (str_replace('%', '', $realestatemanager_configuration['date_format'])))))));
    return $DateToFormat;
}
?>

<script type="text/javascript" src="<?php echo $mosConfig_live_site ?>/components/com_realestatemanager/lightbox/js/jQuerREL-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site ?>/components/com_realestatemanager/includes/jquery-ui.js"></script>
<script>
jQuerREL(function() {
    jQuerREL( "#search_date_from_mod, #search_date_until_mod" ).datepicker(
    {
    minDate: "+0",
    dateFormat: "<?php echo filterDateSearchMod(); ?>",
    });
});
</script>
<script type="text/javascript"> 

       jQuerREL(function() {
            jQuerREL("#rem_slider_mod").slider({
                min: 0,
                max: <?php echo $max_price; ?>,
                values: [0,<?php echo $max_price; ?>],
                range: true,
                stop: function(event, ui) {
                    jQuerREL("input#pricefrom").val(jQuerREL("#rem_slider_mod").slider("values",0));
                    jQuerREL("input#priceto").val(jQuerREL("#rem_slider_mod").slider("values",1));
                },
                slide: function(event, ui){
                    jQuerREL("input#pricefrom").val(jQuerREL("#rem_slider_mod").slider("values",0));
                    jQuerREL("input#priceto").val(jQuerREL("#rem_slider_mod").slider("values",1));
                }
            });

            jQuerREL("input#pricefrom").change(function(){
                var value1=jQuerREL("input#pricefrom").val();
                var value2=jQuerREL("input#priceto").val();
              
                if(parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    jQuerREL("input#pricefrom").val(value1);
                }
                jQuerREL("#rem_slider_mod").slider("values",0,value1);
            });
                  
            jQuerREL("input#priceto").change(function(){
                var value1=jQuerREL("input#pricefrom").val();
                var value2=jQuerREL("input#priceto").val();
                if(parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    jQuerREL("input#priceto").val(value2);
                }
                jQuerREL("#rem_slider_mod").slider("values",1,value2);
            });
        });
    </script>

<div class="com_realestatemanager<?php echo $moduleclass_sfx;?>">
    <div id="rem_mod_search" style="display:inline-block;">
    <form action="<?php echo sefRelToAbs("index.php?option=com_realestatemanager&amp;task=search&amp;catid=0&amp;Itemid=".$ItemId); ?>" method="get" name="mod_realestatlibsearchForm">
        <div style="display:inline-block; margin-right:10px;">
        <div class="search_houses">
            <div class="search_title">
                <?php echo _REALESTATE_MANAGER_LABEL_SEARCH_KEYWORD; ?>
            </div>
                <input class="inputbox" type="text" name="searchtext" size="20" maxlength="20"/>
        </div>
        <div class="col_box">
                <span><?php echo _REALESTATE_MANAGER_LABEL_CATEGORY; ?></span>
                <?php echo $clist; ?>
        </div>
        </div>
        <?php if($params->get('rent') == 0){ ?>
            <div class="search_rent" style="display:inline-block; margin-right:10px;">
                <div class="box_from" >
                    <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_REQUEST_FROM; ?></span>
                    <input type="text" id="search_date_from_mod" name="search_date_from">
                </div>
                <div class="box_from" >
                    <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_REQUEST_UNTIL; ?></span>
                    <input type="text" id="search_date_until_mod" name="search_date_until">
                </div>
            </div>
        <?php } if($params->get('price') == 0){ ?>
            <div class="col_box_1" style="display:inline-block; margin-right:10px;">
                <span class="price_label"><?php echo _REALESTATE_MANAGER_LABEL_PRICE; ?></span>
                <div id="rem_slider_mod" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>
                <div class="pricefrom_2">
                    <span><?php echo _REALESTATE_MANAGER_LABEL_FROM; ?></span>
                    <input type="text" id="pricefrom" name="pricefrom2" value="0"/>
                </div>
                <div class="priceto_2">
                    <span><?php echo _REALESTATE_MANAGER_LABEL_TO; ?></span>
                    <input type="text" id="priceto" name="priceto2" value="<?php echo $max_price; ?>"/>
                </div>
                <div style="clear: both;"></div>
            </div>
        <?php } ?>
            <div style="display:inline-block; margin-right:10px;">
          <?php 
          if($params->get('listing_type_list') == 0){?>
                    <div class="col_box_2">
                        <span><?php echo _REALESTATE_MANAGER_LABEL_LISTING_TYPE; ?></span>
                        <?php echo $listing_type_list; ?>
                    </div>
          <?php }?>
          <?php if($params->get('listing_status_list') == 0){?>
                    <div class="col_box_2">
                        <span><?php echo _REALESTATE_MANAGER_LABEL_LISTING_STATUS; ?></span>
                        <?php echo $listing_status_list; ?>
                    </div>
          <?php }?>
          </div>
<div class="search_button">
    <input type="submit" value="<?php echo _REALESTATE_MANAGER_LABEL_SEARCH_BUTTON; ?>" class="button"  ></input>
</div>
        <input type="hidden" name="Title" value="on" />
        <input type="hidden" name="typeLayout" value="alone_category" />
        <input type="hidden" name="searchLayout" value="Gallery" />
        <input type="hidden" name="option" value="com_realestatemanager" />
        <input type="hidden" name="task" value="search" />
        <input type="hidden" name="Itemid" value="<?php echo $ItemId ?>" />
</form>
</div>
</div>
