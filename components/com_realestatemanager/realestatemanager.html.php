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
jimport( 'joomla.plugin.helper' );
if (version_compare(JVERSION, "3.0.0", "lt"))
    jimport('joomla.html.toolbar');

require_once($mosConfig_absolute_path . "/components/com_realestatemanager/functions.php");
require_once($mosConfig_absolute_path . "/components/com_realestatemanager/realestatemanager.php");
//require_once($mosConfig_absolute_path."/administrator/components/com_realestatemanager/menubar_ext.php");

$GLOBALS['mosConfig_live_site'] = $mosConfig_live_site = JURI::root(); 
$GLOBALS['mosConfig_absolute_path'] = $mosConfig_absolute_path; //for 1.6
$GLOBALS['mainframe'] = $mainframe = JFactory::getApplication();

$templateDir = JPATH_THEMES . DS . JFactory::getApplication()->getTemplate() . DS;
$GLOBALS['templateDir'] = $templateDir;
$GLOBALS['doc'] = $doc = JFactory::getDocument();
$g_item_count = 0;

$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
$doc->addStyleSheet('//maxcdn.bootstrapcdn.com/bootstrap/2.3.2/css/bootstrap.min.css');
//$doc->addScript('//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js');
$doc->addStyleSheet( $mosConfig_live_site . 
      '/components/com_realestatemanager/includes/jquery-ui.css');

class HTML_realestatemanager {



    static function showRentRequest(& $houses, & $currentcat, & $params, & $tabclass,
     & $catid, & $sub_categories, $option) {
        $pageNav = new JPagination(0, 0, 0);
      
        HTML_realestatemanager::displayHouses($houses, $currentcat, $params, $tabclass,
         $catid, $sub_categories, $pageNav, $option);
        // add the formular for send to :-)
    }

    static function displayHouses_empty($rows, $currentcat, &$params, $tabclass, $catid,
           $categories, &$pageNav = null,$is_exist_sub_categories=false, $option) {
        positions_rem($params->get('allcategories01'));
        ?>
        <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
             <?php echo $currentcat->header; ?>
        </div>
        <?php positions_rem($params->get('allcategories02')); ?>
        <table class="basictable table_48" border="0" cellpadding="4" cellspacing="0" width="100%">
            <tr>
                <td>
                    <?php echo $currentcat->descrip; ?>
                </td>     
                <td width="120" align="center">
                    <img src="./components/com_realestatemanager/images/rem_logo.png"
                     align="right" alt="Real Estate Manager logo"/>
                </td>
            </tr>
        </table>
        <?php
        if ($is_exist_sub_categories) {
            ?>
            <?php positions_rem($params->get('singlecategory07')); ?>
            <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
            <?php echo _REALESTATE_MANAGER_LABEL_FETCHED_SUBCATEGORIES . " : " .
             $params->get('category_name'); ?>
            </div>
            <?php positions_rem($params->get('singlecategory08')); ?>
            <?php
            HTML_realestatemanager::listCategories($params, $categories, $catid, $tabclass, $currentcat);
        }
    }

    static function displayHouses(&$rows, $currentcat, &$params, $tabclass, $catid, $categories,
        &$pageNav = null,$is_exist_sub_categories=false, $option, $layout = "default", $type = "alone_category") {
        global $mosConfig_absolute_path, $Itemid;  
        $type = 'alone_category';  
        require getLayoutPath::getLayoutPathCom('com_realestatemanager', $type, $layout);
    }    

    static function displaySearchHouses(&$rows, $currentcat, &$params, $tabclass, $catid, $categories,
        &$pageNav = null,$is_exist_sub_categories=false, $option, $layout = "default", $layoutsearch = "default") {
        global $mosConfig_absolute_path, $Itemid;  
        $type = 'search_result';
        if (PHP_realestatemanager::checkItemidViewHouse())
            $Itemid = PHP_realestatemanager::checkItemidViewHouse();
        PHP_realestatemanager::showSearchHouses($option, $catid, $option, $layoutsearch);  

        require getLayoutPath::getLayoutPathCom('com_realestatemanager', $type, $layout);
    }

    /**
     * Display links to categories
     */
    static function showCategories(&$params, &$categories, &$catid, &$tabclass, &$currentcat, $layout) {
        global $mosConfig_absolute_path;
        $type = 'all_categories';
        require getLayoutPath::getLayoutPathCom('com_realestatemanager', $type, $layout);
    }

    static function showSearchHouses($params, $currentcat, $clist, $option, $layout = "default") {
        global $mosConfig_absolute_path, $task;
        $type = $task == "search" ? "show_search_result" : "show_search_house";
        // $type = 'show_search_house';
        require getLayoutPath::getLayoutPathCom('com_realestatemanager', $type, $layout);
    }

    static function displayAllHouses(&$rows, &$params, $tabclass, &$pageNav, $layout = "default") {
        
        global $mosConfig_absolute_path,$Itemid;
        $type = 'all_houses';

        if (PHP_realestatemanager::checkItemidViewHouse())
            $Itemid = PHP_realestatemanager::checkItemidViewHouse();

        require getLayoutPath::getLayoutPathCom('com_realestatemanager', $type, $layout);
    }

    /**
     * Displays the house
     */
    static function displayHouse(& $house, & $tabclass, & $params, & $currentcat, & $rating,
     & $house_photos,$videos,$tracks, $id, $catid, $option, & $house_feature, & $currencys_price, $layout = "default") {
        global $mosConfig_absolute_path;
        $type = 'view_house';
        require getLayoutPath::getLayoutPathCom('com_realestatemanager', $type, $layout);
    }

    static function showRentRequestThanks($params, $backlink, $currentcat,$houseid=NULL,$time_difference=NULL) { 
            global $Itemid, $doc, $mosConfig_live_site, $hide_js, $catid,
             $option, $realestatemanager_configuration;;
            $doc->addStyleSheet($mosConfig_live_site .
             '/components/com_realestatemanager/includes/realestatemanager.css');
            ?>
        <div class="componentheading<?php echo $params->get('pageclass_sfx'); ?>">
        </div>
        <?php
        if($houseid){
           $item_name = $houseid->htitle;							
            if($time_difference){
                $amount = $time_difference[0]; // price
                $currency_code = $time_difference[1] ; // priceunit  
            }
            else{
                $amount= $houseid->price;
                $currency_code = $houseid->priceunit;
            }
        
        $amountLine='';
        $amountLine .= '<input type="hidden" name="amount" value="'.$amount.'" />'."\n"; 
        }
        
        ?> 
        
        <div class="save_add_table">
  
            <div class="descrip"><?php echo $currentcat->descrip; ?></div>  
        </div>

        <?php
        if ($option != 'com_realestatemanager') {
            $form_action = "index.php?option=" . $option . "&Itemid=" . $Itemid  ;
        }
        else
            $form_action = "index.php?option=com_realestatemanager&Itemid=" . $Itemid;
        ?>
        <div class="basictable_15 basictable">
                <span>
                        <input class="button" type="submit" ONCLICK="window.location.href='<?php
                       $user = JFactory::getUser(); 
                              echo $backlink;                        
                            ?>'" value="OK">
        <?php
    }

    static function showTabs(&$params, &$userid, &$username, &$comprofiler, &$option) {

        global $mosConfig_live_site, $doc;
        $doc->addStyleSheet($mosConfig_live_site . '/components/com_realestatemanager/TABS/tabcontent.css');
        $doc->addScript($mosConfig_live_site . '/components/com_realestatemanager/TABS/tabcontent.js');


?>

        <script type="text/javascript" src="<?php echo $mosConfig_live_site
         . '/components/com_realestatemanager/lightbox/js/jQuerREL-1.2.6.js'; ?>"></script>
        <script type="text/javascript">jQuerREL=jQuerREL.noConflict();</script>
        </br> <!-- br for plugin simplemembership!!! -->
        <div class='tabs_buttons'>
            <ul id="countrytabs" class="shadetabs">
        <?php

        if ($params->get('show_edit_registrationlevel')) {
                        ?>
                <li>
                    <a class="my_houses_edit" href="<?php echo JRoute::_('index.php?option='
                     . $option .'&userId='.$userid . '&task=edit_my_houses' . $comprofiler, false);
                     ?>"><?php echo _REALESTATE_MANAGER_SHOW_TABS_SHOW_MY_HOUSES; ?></a>
                </li>
            <?php
        }

        if ($params->get('show_rent')) {

            if ($params->get('show_rent_registrationlevel')) {
                ?>
                    <li>
                        <a class="my_houses_rent" href="<?php echo JRoute::_('index.php?option='
                         . $option . '&userId='.$userid . '&task=rent_requests' . $comprofiler , false);
                          ?>"><?php echo _REALESTATE_MANAGER_SHOW_TABS_RENT_REQUESTS; ?></a>
                    </li>
                <?php
            }
        }
        if ($params->get('show_buy')) {
            if ($params->get('show_buy_registrationlevel')) {
                ?>
                    <li>
                        <a class="my_houses_buy" href="<?php echo JRoute::_('index.php?option='
                         . $option . '&userId='.$userid . '&task=buying_requests' . $comprofiler , false);
                          ?>"><?php echo _REALESTATE_MANAGER_SHOW_TABS_BUYING_REQUESTS; ?></a>
                    </li>
                <?php
            }
        }
        if ($params->get('show_history')) {
            if ($params->get('show_history_registrationlevel')) {
                ?>
            <li>
                <a class="my_houses_history" href="<?php echo JRoute::_('index.php?option='
                 . $option . '&userId='.$userid . '&task=rent_history' . $comprofiler , false);
                 ?>"><?php echo _REALESTATE_MANAGER_LABEL_CBHISTORY_ML; ?></a>
            </li>
                <?php
            }
        }
        ?>
            </ul>
        </div>
<script type="text/javascript">
    jQuerREL(document).ready(function(){
        var atr = jQuerREL("#adminForm div:first-child").attr("id");
        if(!atr){
            atr = jQuerREL("#adminForm table:first-child").attr("id");
        }
        jQuerREL("#countrytabs > li > a."+atr).addClass("selected");
         jQuerREL("#countrytabs > li > a").click(function(){
             jQuerREL("#countrytabs > li > a").removeClass("selected");
             jQuerREL(this).addClass("selected");
        });
    });

</script>
                <?php
            }

            static function showMyHouses(&$houses, &$params, &$pageNav, $option, $layout = "default") {
                global $mosConfig_absolute_path, $Itemid;
                //require($mosConfig_absolute_path.
                // "/components/com_realestatemanager/views/my_houses/tmpl/".$layout.".php");
                $type = 'my_houses';
                require getLayoutPath::getLayoutPathCom('com_realestatemanager', $type, $layout);
            }

           static function showRentHouses($option, $house1, $rows, & $userlist, $type) {
                global $my, $mosConfig_live_site, $mainframe, $doc, $Itemid, $realestatemanager_configuration;
        ?>
        <script type="text/javascript" src="<?php echo $mosConfig_live_site .
          '/components/com_realestatemanager/lightbox/js/jQuerREL-1.2.6.js'; ?>"></script>
        <script type="text/javascript">jQuerREL=jQuerREL.noConflict();</script>
        <script type="text/javascript" src="<?php
          echo $mosConfig_live_site; ?>/components/com_realestatemanager/includes/jquery-ui.js"></script>
      <?php
        $doc->addScript($mosConfig_live_site . '/components/com_realestatemanager/includes/functions.js');
        $doc->addStyleSheet($mosConfig_live_site .
                 '/components/com_realestatemanager/includes/realestatemanager.css');
        ?>
        <div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
        <form action="index.php" method="get" name="adminForm" id="adminForm">
                <?php
                if ($type == "rent" || $type == "edit_rent") {
                    ?>
                <div class="my_real_table_rent">
                    <div class="my_real">
                        <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_TO . ":"; ?></span>
                        <span class="col_02"><?php echo $userlist; ?></span>
                    </div>
                    <div class="my_real">
                        <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_USER . ":"; ?></span>
                        <span class="col_02"><input type="text" name="user_name" class="inputbox" /></span>
                    </div>
                    <div class="my_real">
                        <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_EMAIL . ":"; ?></span>
                        <span class="col_02"><input type="text" name="user_email" class="inputbox" /></span>
                    </div>
                    <script>
                        Date.prototype.toLocaleFormat = function(format) {
                            var f = {Y : this.getYear() + 1900,m : this.getMonth() + 
                              1,d : this.getDate(),H : this.getHours(),M : this.getMinutes(),S : this.getSeconds()}
                            for(k in f)
                                format = format.replace('%' + k, f[k] < 10 ? "0" + f[k] : f[k]);
                            return format;
                        };
                                
                        window.onload = function ()

                        {
                            var today = new Date();
                            var date = today.toLocaleFormat("<?php echo $realestatemanager_configuration['date_format'] ?>");
                            document.getElementById('rent_from').value = date;
                            document.getElementById('rent_until').value = date;
                        };

                    </script>
 <!--///////////////////////////////calendar///////////////////////////////////////-->
                   <script language="javascript" type="text/javascript">
        <?php
                  $house_id_fordate =  $house1->id;
                  $date_NA = available_dates($house_id_fordate);
          ?>
                  var unavailableDates = Array();
                  jQuerREL(document).ready(function() {
                      //var unavailableDates = Array();
                      var k=0;
                      <?php if(!empty($date_NA)){?>
                          <?php foreach ($date_NA as $N_A){ ?>
                               unavailableDates[k]= '<?php echo $N_A; ?>';
                              k++;
                          <?php } ?>
                      <?php } ?>

                      function unavailable(date) {
                          dmy = date.getFullYear() + "-" + ('0'+(date.getMonth() + 1)).slice(-2) +
                            "-" + ('0'+date.getDate()).slice(-2);
                          if (jQuerREL.inArray(dmy, unavailableDates) == -1) {
                              return [true, ""];
                          } else {
                              return [false, "", "Unavailable"];
                          }
                      }

                      jQuerREL( "#rent_from, #rent_until" ).datepicker(
                      {

                        minDate: "+0",
                        dateFormat: "<?php echo transforDateFromPhpToJquery();?>",
                        beforeShowDay: unavailable,

                      });
                  });



                  </script>


<!--///////////////////////////////////////////////////////////////////////////-->
                    <div class="my_real">
                        <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_FROM . ":"; ?></span>
                           <p><input type="text" id="rent_from" name="rent_from"></p>
                    </div>
                    <div class="my_real">
                        <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_TIME; ?></span>
                        <p><input type="text" id="rent_until" name="rent_until"></p>
                    </div>
                </div>


        <?php } else { 
               echo "";
        } 
                $all = JFactory::getDBO();
                $query = "SELECT * FROM #__rem_rent";
                $all->setQuery($query);
                $num = $all->loadObjectList();
                ?>
            <div class="table_63">

                <div class="row_01">
                    <span class="col_01">
        <?php if ($type != 'rent') {
            ?>
                            <input type="checkbox" name="toggle" value="" onClick="rem_checkAll(this);" />
                            <span class="toggle_check">check All</span>
        <?php } ?>
                    </span>
        
                </div>

        <?php
        
        
        if ($type == "rent")
        {
        ?>
            <td align="center">  <input class="inputbox"  type="checkbox"
              name="checkHouse" id="checkHouse" size="0" maxlength="0" value="on" /></td>
         <?php
        } else if ($type == "edit_rent"){ ?>
          <input type="hidden"  name="checkHouse" id="checkHouse" value="on" /></td>
         <?php
         }
        $assoc_title = '';
        for ($t = 0, $z = count($rows); $t < $z; $t++) {
          if($rows[$t]->id != $house1->id) $assoc_title .= " ".$rows[$t]->htitle;
        }
        
               print_r("
                  <td align=\"center\">". $house1->id ."</td>
                  <td align=\"center\">" . $house1->houseid . "</td>
                  <td align=\"center\">" . $house1->htitle . " ( " . $assoc_title ." ) " . "</td>
                  </tr>");
        

              for ($j = 0, $n = count($rows); $j < $n; $j++) {
                $row = $rows[$j];
                    
              ?>
                     
              
                    <input class="inputbox" type="hidden" name="houseid" id="houseid"
                     size="0" maxlength="0" value="<?php echo $house1->houseid; ?>" />
                    <input class="inputbox" type="hidden" name="id" id="id" size="0"
                     maxlength="0" value="<?php echo $row->id; ?>" />
                    <input class="inputbox" type="hidden" name="htitle" id="htitle"
                     size="0" maxlength="0" value="<?php echo $row->htitle; ?>" />
                <?php
                
                    $house = $row->id;
                    $data = JFactory::getDBO();
                    $query = "SELECT * FROM #__rem_rent WHERE fk_houseid=" . $house . " ORDER BY rent_return ";
                    $data->setQuery($query);
                    $allrent = $data->loadObjectList();
                    
                
               
                    $num = 1;
                    for ($i = 0, $nn = count($allrent); $i < $nn; $i++) {
                        ?>

                        <div class="box_rent_real">

                            <div class="row_01 row_rent_real">
                        <?php if (!isset($allrent[$i]->rent_return) && $type != "rent") {
                            ?>
                                    <span class="rent_check_vid">
                                        <input type="checkbox" id="cb<?php echo $i; ?>" 
                                          name="bid[]" value="<?php echo $allrent[$i]->id; ?>" 
                                          onClick="isChecked(this.checked);" />
                                    </span>
                        <?php } else {
                            ?>
                        <?php } ?>
                                <span class="col_01">id</span>
                                <span class="col_02"><?php echo $num; ?></span>
                            </div>

                            <div class="row_02 row_rent_real">
                                <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_PROPERTYID; ?></span>  
                                <span class="col_02"><?php echo $row->houseid; ?></span>
                            </div>

                            <div class="row_03 row_rent_real">
                        <?php echo $row->htitle; ?>
                            </div>

                            <div class="from_until_return">
                                <div class="row_04 row_rent_real">
                                    <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_FROM; ?></span>  
                                    <span class="col_02"><?php echo data_transform_rem($allrent[$i]->rent_from); ?></span>
                                </div>
                                <br />
                                <div class="row_05 row_rent_real">
                                    <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_UNTIL; ?></span>  
                                    <span class="col_02"><?php echo data_transform_rem($allrent[$i]->rent_until); ?></span>
                                </div>
                                <br />
                                <div class="row_06 row_rent_real">
                                    <span class="col_01"><?php echo _REALESTATE_MANAGER_LABEL_RENT_RETURN; ?></span>  
                                    <span class="col_02"><?php echo data_transform_rem($allrent[$i]->rent_return); ?></span>
                                </div>
                            </div>

                        </div>
                        <?php
                        if ($allrent[$i]->fk_userid != null)
                            print_r("<div class='rent_user'>" . $allrent[$i]->user_name . "</div>");
                        else
                            print_r("<div class='rent_user'>" . $allrent[$i]->user_name . $allrent[$i]->user_email . "</div>");
                        $num++;
                    }
            
                }
                ?>
            </div> <!-- table_63 -->


            <input type="hidden" name="option" value="<?php echo $option; ?>" />
            <input type="hidden" id="adminFormTaskInput" name="task" value="" />
            <input type="hidden" name="save" value="1" />
            <input type="hidden" name="boxchecked" value="1" />
                <?php
                if ($option != "com_realestatemanager") {
                    ?>
                <input type="hidden" name="is_show_data" value="1" />
                    <?php
                }
                ?>
            <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />

        <?php if ($type == "rent") { ?>
                <input type="button" name="rent_save" value="<?php 
                  echo _REALESTATE_MANAGER_LABEL_BUTTON_RENT; ?>" onclick="rem_buttonClickRent(this)"/>
        <?php } ?>
        <?php if ($type == "rent_return") { ?>
                <input type="button" name="rentout_save" value="<?php 
                  echo _REALESTATE_MANAGER_LABEL_RENT_RETURN; ?>" onclick="rem_buttonClickRent(this)"/>
        <?php } ?>
        </form>
        <?php
    }

 static function listCategories(&$params, $cat_all, $catid, $tabclass, $currentcat) {
                global $Itemid, $mosConfig_live_site, $doc;
                $doc->addStyleSheet($mosConfig_live_site .
                 '/components/com_realestatemanager/includes/realestatemanager.css');
                ?>
        <?php positions_rem($params->get('allcategories04')); ?>
        <div class="basictable table_58">
            <div class="row_01">
                <span class="col_01 sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>">
            <?php echo _REALESTATE_MANAGER_LABEL_CATEGORY; ?>
                </span>

                <span class="col_02 sectiontableheader<?php echo $params->get('pageclass_sfx'); ?>">
        <?php echo _REALESTATE_MANAGER_LABEL_HOUSES; ?> 
                </span>
            </div>
            <br/>
            <div class="row_02">
        <?php positions_rem($params->get('allcategories05')); ?>
        <?php HTML_realestatemanager::showInsertSubCategory($catid, $cat_all, $params, $tabclass, $Itemid, 0); ?>
            </div>
        </div>

        <?php positions_rem($params->get('allcategories06')); ?>
    <?php
    }

    /* function for show subcategory */

    static function showInsertSubCategory($id, $cat_all, $params, $tabclass, $Itemid, $deep) {
        global $g_item_count, $realestatemanager_configuration, $mosConfig_live_site;
        global $doc;

        $doc->addStyleSheet($mosConfig_live_site .
         '/components/com_realestatemanager/includes/realestatemanager.css');

        $deep++;
        for ($i = 0; $i < count($cat_all); $i++) {
            if (($id == $cat_all[$i]->parent_id) && ($cat_all[$i]->display == 1)) {
                $g_item_count++;

                $link = 'index.php?option=com_realestatemanager&amp;task=showCategory&amp;catid='
                   . $cat_all[$i]->id . '&amp;Itemid=' . $Itemid;
                ?>
                <div class="table_59 <?php echo $tabclass[($g_item_count % 2)]; ?>">
                    <span class="col_01">
                <?php
                if ($deep != 1) {
                    $jj = $deep;
                    while ($jj--) {
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                    echo "&nbsp;|_";
                }
                ?>
                    </span>
                    <span class="col_01">
                <?php if (($params->get('show_cat_pic')) && ($cat_all[$i]->image != "")) { ?>
                            <img src="./images/stories/<?php echo $cat_all[$i]->image; ?>"
                               alt="picture for subcategory" height="48" width="48" />&nbsp;
                    <?php } else {
                    ?>
                            <a <?php echo "href=" . sefRelToAbs($link); ?> class="category<?php
                             echo $params->get('pageclass_sfx'); ?>" style="text-decoration: none"><img
                              src="./components/com_realestatemanager/images/folder.png"
                               alt="picture for subcategory" height="48" width="48" /></a>&nbsp;
                <?php } ?>
                    </span>
                    <span class="col_02">
                        <a href="<?php echo sefRelToAbs($link); ?>"
                         class="category<?php echo $params->get('pageclass_sfx'); ?>">
                <?php echo $cat_all[$i]->title; ?>
                        </a>
                    </span>
                    <span class="col_03">
                <?php if ($cat_all[$i]->houses == '') echo "0";else echo $cat_all[$i]->houses; ?>
                    </span>
                </div>
                <?php
              if ($realestatemanager_configuration['subcategory']['show'])
                    HTML_realestatemanager::showInsertSubCategory($cat_all[$i]->id,
                     $cat_all, $params, $tabclass, $Itemid, $deep);
            }//end if ($id == $cat_all[$i]->parent_id)
        }//end for(...) 
    }

  static function add_google_map(&$rows) {
     global $realestatemanager_configuration, $doc, $mosConfig_live_site, $database, $Itemid;
          $api_key = $realestatemanager_configuration['api_key'] ? "key=" . $realestatemanager_configuration['api_key'] : JFactory::getApplication()->enqueueMessage("<a target='_blank' href='//developers.google.com/maps/documentation/geocoding/get-api-key'>" . _REALESTATE_MANAGER_GOOGLEMAP_API_KEY_LINK_MESSAGE . "</a>", _REALESTATE_MANAGER_GOOGLEMAP_API_KEY_ERROR); 
          $doc->addScript("//maps.googleapis.com/maps/api/js?$api_key"); ?>

      <script type="text/javascript">
      
      window.addEvent('domready', function() {
          initialize2();
      });

      function initialize2(){
          var map;
          var marker = new Array();
          var myOptions = {
              scrollwheel: false,
              zoomControlOptions: {
                  style: google.maps.ZoomControlStyle.LARGE
              },
              mapTypeId: google.maps.MapTypeId.ROADMAP
          };
          var imgCatalogPath = "<?php echo $mosConfig_live_site; ?>/components/com_realestatemanager/";
          var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
          var bounds = new google.maps.LatLngBounds ();

          <?php
          $newArr = explode(",", _REALESTATE_MANAGER_HOUSE_MARKER);
          $j = 0; 
          for ($i = 0;$i < count($rows);$i++) { 
            if ($rows[$i]->hlatitude && $rows[$i]->hlongitude) {
              $numPick = '';
              if (isset($newArr[$rows[$i]->property_type])) {
                  $numPick = $newArr[$rows[$i]->property_type];
              }
      ?>

              var srcForPic = "<?php echo $numPick; ?>";
              var image = '';
              if(srcForPic.length){
                  var image = new google.maps.MarkerImage(imgCatalogPath + srcForPic,
                      new google.maps.Size(32, 32),
                      new google.maps.Point(0,0),
                      new google.maps.Point(16, 32));
              }
                  
              marker.push(new google.maps.Marker({
                  icon: image,
                  position: new google.maps.LatLng(<?php echo $rows[$i]->hlatitude; ?>,
                   <?php echo $rows[$i]->hlongitude; ?>),
                  map: map,
                  title: "<?php echo $database->Quote($rows[$i]->htitle); ?>"
              }));
              bounds.extend(new google.maps.LatLng(<?php echo $rows[$i]->hlatitude; ?>,
                <?php echo $rows[$i]->hlongitude; ?>));


              var infowindow  = new google.maps.InfoWindow({});
              google.maps.event.addListener(marker[<?php echo $j; ?>], 'mouseover', 
              function() {
                <?php
                if (strlen($rows[$i]->htitle) > 45)
                    $htitle = mb_substr($rows[$i]->htitle, 0, 25) . '...';
                else {
                    $htitle = $rows[$i]->htitle;
                }
                ?>
                        
                var title =  "<?php echo $htitle ?>";
                <?php 
                  //for local images
                  $imageURL = ($rows[$i]->image_link);

                  if ($imageURL == '') $imageURL = _REALESTATE_MANAGER_NO_PICTURE_BIG;
                      $file_name = rem_picture_thumbnail($imageURL,
                         $realestatemanager_configuration['fotogal']['width'],
                         $realestatemanager_configuration['fotogal']['high']);
                      $file = $mosConfig_live_site . '/components/com_realestatemanager/photos/' . $file_name;
                ?>
                var imgUrl =  "<?php echo $file; ?>";
                      
                var price =  "<?php echo $rows[$i]->price; ?>";
                var priceunit =  "<?php echo $rows[$i]->priceunit; ?>";

                var contentStr = '<div>'+
                '<a onclick=window.open("<?php echo JRoute::_("index.php?option=com_realestatemanager&task=view&id={$rows[$i]->id}&catid={$rows[$i]->idcat}&Itemid={$Itemid}");?>") >'+
               '<img width = "100%" src = "'+imgUrl+'">'+
               '</a>' +
                  '</div>'+
                  '<div id="marker_link">'+
                      '<a onclick=window.open("<?php echo JRoute::_("index.php?option=com_realestatemanager&task=view&id={$rows[$i]->id}&catid={$rows[$i]->idcat}&Itemid={$Itemid}");?>") >' + title + '</a>'+
                  '</div>'+
                  '<div id="marker_price">'+
                      '<a onclick=window.open("<?php echo JRoute::_("index.php?option=com_realestatemanager&task=view&id={$rows[$i]->id}&catid={$rows[$i]->idcat}&Itemid={$Itemid}");?>") >' + price +' ' + priceunit + '</a>'+
              '</div>';

                   infowindow.setContent(contentStr);
                   infowindow.open(map,marker[<?php echo $j; ?>]);
              });


              var myLatlng = new google.maps.LatLng(<?php echo $rows[$i]->hlatitude;
               ?>,<?php echo $rows[$i]->hlongitude; ?>);
              var myZoom = <?php echo $rows[$i]->map_zoom; ?>;
              <?php
              $j++;
            }
          }
  ?>
          if (<?php echo $j; ?>>1) map.fitBounds(bounds);
          else if (<?php echo $j; ?>==1) {map.setCenter(myLatlng);map.setZoom(myZoom)}
          else {map.setCenter(new google.maps.LatLng(0,0));map.setZoom(0);}
        }
      </script>
  <?php    
  }
}
//class html