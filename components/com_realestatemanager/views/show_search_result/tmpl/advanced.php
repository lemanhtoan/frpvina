<?php
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . 
    basename(__FILE__) . ' is not allowed.');
/**
 *
 * @package  RealEstateManager
 * @copyright 2012 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com); 
 * Homepage: http://www.ordasoft.com
 * @version: 3.9 Free
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 *
 */
global $hide_js, $Itemid, $mainframe, $mosConfig_live_site, $doc,
   $realestatemanager_configuration, $database, $task;
$doc->addStyleSheet($mosConfig_live_site .
 '/components/com_realestatemanager/includes/realestatemanager.css');
?>

<noscript>
    Javascript is required to use Real Estate Manager 
    <a href="http://ordasoft.com/Real-Estate-Manager-Software-Joomla.html">
        Real Estate this is commercial software component for Joomla 
    </a>, 
    <a href="http://ordasoft.com/real-estate-website-templates">
        Real Estate Website Templates 
    </a>
</noscript>

<?php
$query = "SELECT max(price+0) FROM #__rem_houses";
$database->setQuery($query);
$max_price = $database->loadResult();

if ($task == 'search') {
    $jinput = JFactory::getApplication()->input;
    $checkbox = array();
    $check = 'checked = "checked"';
    $checkbox['Houseid'] = $jinput->get('Houseid') == 'on' ? $check : '';
    $checkbox['Description'] = $jinput->get('Description') == 'on' ? $check : '';
    $checkbox['Title'] = $jinput->get('Title') == 'on' ? $check : '';
    $checkbox['Country'] = $jinput->get('Country') == 'on' ? $check : '';
    $checkbox['Address'] = $jinput->get('Address') == 'on' ? $check : '';
    $checkbox['Region'] = $jinput->get('Region') == 'on' ? $check : '';
    $checkbox['City'] = $jinput->get('City') == 'on' ? $check : '';
    $checkbox['Zipcode'] = $jinput->get('Zipcode') == 'on' ? $check : '';
    $checkbox['ownername'] = $jinput->get('ownername') == 'on' ? $check : '';
    $checkbox['rooms'] = $jinput->get('rooms') == 'on' ? $check : '';
    $checkbox['Bathrooms'] = $jinput->get('Bathrooms') == 'on' ? $check : '';
    $checkbox['Bedrooms'] = $jinput->get('Bedrooms') == 'on' ? $check : '';
    $checkbox['Garages'] = $jinput->get('Garages') == 'on' ? $check : '';
    $checkbox['year'] = $jinput->get('year') == 'on' ? $check : '';
    $checkbox['Contacts'] = $jinput->get('Contacts') == 'on' ? $check : '';
    $checkbox['Agent'] = $jinput->get('Agent') == 'on' ? $check : '';
    $checkbox['house_size'] = $jinput->get('house_size') == 'on' ? $check : '';
    $checkbox['lot_size'] = $jinput->get('lot_size') == 'on' ? $check : '';
    $checkbox['exactly'] = $jinput->get('exactly') == 'on' ? $check : '';
}
?>
<script type="text/javascript" src="<?php echo $mosConfig_live_site 
  ?>/components/com_realestatemanager/lightbox/js/jQuerREL-1.2.6.js"></script>
<script type="text/javascript" src="<?php echo $mosConfig_live_site
  ?>/components/com_realestatemanager/includes/jquery-ui.js"></script>

<?php positions_rem($params->get('showsearch01')); ?>
<?php positions_rem($params->get('showsearch02')); ?>

<script type="text/javascript">
    function showDate(){
        if(document.adminForm.search_date_box.checked){
            var x=document.getElementById("search_date_from");
            document.adminForm.search_date_from.type="text";
            var x=document.getElementById("search_date_until");
            document.adminForm.search_date_until.type="text";
        } else{
            var x=document.getElementById("search_date_from");
            document.adminForm.search_date_from.type="hidden";
            var x=document.getElementById("search_date_until");
            document.adminForm.search_date_until.type="hidden";}
    }
</script>
<script language="javascript" type="text/javascript">      
 
    jQuerREL(document).ready(function() {
      jQuerREL( "#search_date_from, #search_date_until" ).datepicker(
      {
          minDate: "+0",
          dateFormat: "<?php echo transforDateFromPhpToJquery();?>"
         
      });
    });
</script>
                
<script type="text/javascript"> 

       jQuerREL(function() {
            jQuerREL(".show_search_house #rem_slider").slider({
                min: 0,
                max: <?php echo $max_price; ?>,
                values: [0,<?php echo $max_price; ?>],
                range: true,
                stop: function(event, ui) {
                    jQuerREL(".show_search_house input#pricefrom").val(jQuerREL(".show_search_house #rem_slider").slider("values",0));
                    jQuerREL(".show_search_house input#priceto").val(jQuerREL(".show_search_house #rem_slider").slider("values",1));
                },
                slide: function(event, ui){
                    jQuerREL(".show_search_house input#pricefrom").val(jQuerREL(".show_search_house #rem_slider").slider("values",0));
                    jQuerREL(".show_search_house input#priceto").val(jQuerREL(".show_search_house #rem_slider").slider("values",1));
                }
            });

            jQuerREL(".show_search_house input#pricefrom").change(function(){
                var value1=jQuerREL(".show_search_house input#pricefrom").val();
                var value2=jQuerREL(".show_search_house input#priceto").val();
              
                if(parseInt(value1) > parseInt(value2)){
                    value1 = value2;
                    jQuerREL(".show_search_house input#pricefrom").val(value1);
                }
                jQuerREL(".show_search_house #rem_slider").slider("values",0,value1);
            });
                  
            jQuerREL(".show_search_house input#priceto").change(function(){
                var value1=jQuerREL(".show_search_house input#pricefrom").val();
                var value2=jQuerREL(".show_search_house input#priceto").val();
              
                if(parseInt(value1) > parseInt(value2)){
                    value2 = value1;
                    jQuerREL(".show_search_house input#priceto").val(value2);
                }
                if(value2 > <?php echo $max_price; ?>){
                    jQuerREL(".show_search_house input#priceto").val(<?php echo $max_price; ?>)
                }
                jQuerREL(".show_search_house #rem_slider").slider("values",1,value2);
            });
        });
    </script>


<?php 
$path = "index.php?option=" . $option . "&amp;task=search_houses&amp;Itemid=" . $Itemid;
$view = JRequest::getVar('view');
if(JRequest::getVar('typeLayout')) $view = JRequest::getVar('typeLayout');
if($view == "") $view = "alone_category";
//if($layout == "") $layout = "default";
?>

<form action="<?php echo sefRelToAbs($path /* "index.php" */
);
?>" method="get" name="userForm1">
    <input type="hidden" name="typeLayout" value="<?php echo $view; ?>" />
    <!--input type="hidden" name="searchLayout" value="<?php echo $layout; ?>" /-->
    <input type="hidden" name="option" value="<?php echo $option; ?>" />
    <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" /> 
    <input type="hidden" name="task" value="search" />

    <div class="show_search_house">
        <div class="container_box_1">
          <div class="rem_show_search_result">  
            <div class="rem_searchtext_input">
                <input class="inputbox" type="text" name="searchtext" size="20" maxlength="20"
                 placeholder="<?php echo _REALESTATE_MANAGER_LABEL_SEARCH_KEYWORD; ?>"/>
            </div>

            <div class="col_box_2 container_box_2">
                <span><?php echo _REALESTATE_MANAGER_LABEL_CATEGORY; ?></span>
                <?php echo $clist; ?>
            </div>

            <div class="col_box_1">
                <div class="box_from">
                    <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_REQUEST_FROM; ?></span>
                    <input type="text" id="search_date_from" name="search_date_from">
                </div>

                <div  class="box_until">
                    <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_REQUEST_UNTIL; ?></span>
                    <input type="text" id="search_date_until" name="search_date_until"></div>
            </div>
          </div>
          
            <div class="rem_show_search_result">
                <div class="col_box_1">
                    <span class="price_label"><?php echo _REALESTATE_MANAGER_LABEL_PRICE; ?></span>
                    <div id="rem_slider" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all rem_show_search_result_price"></div>
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
            </div>

        <div class="rem_show_search_result">
            <div class="container_box_2 container_box_3">
                <div class="col_box_2">
                    <span><?php echo _REALESTATE_MANAGER_LABEL_LISTING_TYPE; ?></span>
                    <?php echo $params->get('listing_type_list'); ?>
                </div>
                <div class="col_box_2">
                    <span><?php echo _REALESTATE_MANAGER_LABEL_LISTING_STATUS; ?></span>
                    <?php echo $params->get('listing_status_list'); ?>
                </div>
                <div class="col_box_2">
                    <span><?php echo _REALESTATE_MANAGER_LABEL_PROPERTY_TYPE; ?></span>
                    <?php echo $params->get('property_type_list'); ?>
                </div>
            </div>
        </div>
<?php positions_rem($params->get('showsearch03')); ?>
        <div class="container_box_2 container_checkbox">
            <div class="rem_show_search_result_button">
            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_PROPERTYID; ?></span>
                    <?php if ($task == 'search') { ?>
                    <input type="checkbox" name="Houseid" id="Houseid" <?php echo $checkbox["Houseid"] ?>/>
                    <?php } else { ?>
                    <input type="checkbox" name="Houseid" id="Houseid" checked="checked"/>
                    <?php } ?>
                </label>
            </div>

            
            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_DESCRIPTION; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Description" id="Description" <?php echo $checkbox["Description"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Description" id="Description" checked = "checked"/>
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_TITLE; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Title" id="Title" <?php echo $checkbox["Title"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Title" id="Title" checked = "checked"/>
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_COUNTRY; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Country" id="Country" <?php echo $checkbox["Country"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Country" id="Country" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_ADDRESS; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Address" id="Address" <?php echo $checkbox["Address"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Address" id="Address" checked = "checked" />
                    <?php } ?>                
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_REGION; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Region" id="Region" <?php echo $checkbox["Region"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Region" id="Region" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_CITY; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="City" id="City" <?php echo $checkbox["City"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="City" id="City" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_ZIPCODE; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Zipcode" id="Zipcode" <?php echo $checkbox["Zipcode"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Zipcode" id="Zipcode" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_SHOW_SEARCH_OWNER; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="ownername" id="ownername" <?php echo $checkbox["ownername"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="ownername" id="ownername" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_ROOMS; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="rooms" id="rooms" <?php echo $checkbox["rooms"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="rooms" id="rooms" checked = "checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_BATHROOMS; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Bathrooms" id="Bathrooms" <?php echo $checkbox["Bathrooms"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Bathrooms" id="Bathrooms" checked = "checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_BEDROOMS; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Bedrooms" id="Bedrooms" <?php echo $checkbox["Bedrooms"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Bedrooms" id="Bedrooms" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_GARAGES; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Garages" id="Garages" <?php echo $checkbox["Garages"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Garages" id="Garages" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_BUILD_YEAR; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="year" id="year" <?php echo $checkbox["year"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="year" id="year" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_CONTACTS; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Contacts" id="Contacts" <?php echo $checkbox["Contacts"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Contacts" id="Contacts" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_AGENT; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="Agent" id="Agent" <?php echo $checkbox["Agent"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="Agent" id="Agent" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_HOUSE_SIZE; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="house_size" id="house_size" <?php echo $checkbox["house_size"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="house_size" id="house_size" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_LABEL_LOT_SIZE; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="lot_size" id="lot_size" <?php echo $checkbox["lot_size"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="lot_size" id="lot_size" checked="checked" />
                    <?php } ?>                    
                </label>
            </div>

            <?php
            for ($i = 1; $i <= 5; $i++) {
                if ($realestatemanager_configuration['extra' . $i] != 0) {
                    ?> 
                    <div class="col_box_3">
                        <label>
                            <span><?php echo _REALESTATE_MANAGER_LABEL_EXTRA . $i; ?></span>
                            <input type="checkbox" name="extra<?php echo $i ?>" id="extra<?php echo $i ?>" checked="checked"/>                            
                        </label>
                    </div>
    <?php }
}
?>
            <div class="col_box_3">
                <label>
                    <span><?php echo _REALESTATE_MANAGER_SHOW_SEARCH_EXACTLY; ?></span>
                    <?php if ($task == 'search') { ?>                    
                    <input type="checkbox" name="exactly" id="exactly" <?php echo $checkbox["exactly"] ?>/>
                    <?php } else { ?>                                        
                    <input type="checkbox" name="exactly" id="exactly"/>
                    <?php } ?> 
                </label>
            </div>
        </div>
        </div>

        <div class="col_box_button">
        <input class="button" type="submit" name="submit" 
          value="<?php echo _REALESTATE_MANAGER_LABEL_SEARCH_BUTTON; ?>" />
        </div>
        </div>

    </div>
    <div style="clear: both;"></div>
</form>
<?php positions_rem($params->get('showsearch04')); ?>