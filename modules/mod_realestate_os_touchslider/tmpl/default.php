<?php
/*
 * @package OS_Touch_Slider
 * @subpackage  mod_realestate_OS_TouchSlider
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Sergey Bunyaev(sergey@bunyaev.ru); Sergey Solovyev(solovyev@solovyev.in.ua)
 * @Homepage: http://www.ordasoft.com
 * @version: 1.2
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
$numSlides = $params->get('countSlides');
?>

<div id="rmTouchSlider<?php echo $module->id;?>" class="<?php echo $sufix; ?>" dir="ltr">

  <div class="realestateSlider <?php echo ($show_type=='horizontal') ? ' horizontal' : ' vertical'; ?>" style="
        <?php if($show_type=='horizontal'){
                 if ($format == '%') {
         ?>        max-width: <?php echo $width;?>%;
                  /* height: <?php echo $height;?>%;*/
           <?php }else { ?>
                    max-width: <?php echo $numSlides * $width;?>px;
                    /*height: <?php echo $height;?>px;*/
           <?php }
              }else{ ?>
            <?php if ($format == '%') {?>
                max-width: <?php echo $width;?>%;
                /*height:<?php echo $numSlides*$height;?>%;*/
            <?php }?>
                max-width: <?php echo $width;?>px;
                /*height:<?php echo $numSlides*$height;?>px;*/
       <?php }?>"
 >
        <?php if ($arrows): ?>
          <span class="arrow-left"></span>
          <span class="arrow-right"></span>
        <?php endif; ?>
                <?php
            $houseid = trim($params->get('house_id') );
            if($houseid != "" ) {
                $houseid = " and h.id in ( ". $houseid ." ) " ;
            }
            $catid = trim($params->get('cat_id') );
            if($catid != "" ) {
                $catid = " and c.id in ( ". $catid ." ) " ;
            }
            if(!is_numeric($max = $params->get('count_house'))) $max = 20;
            $db = JFactory::getDBO();
            $selectstring = "SELECT DISTINCT count(h.id) as max FROM `#__rem_houses` AS h 
                            LEFT JOIN `#__rem_categories` AS hc ON hc.iditem=h.id 
                            LEFT JOIN `#__rem_main_categories` AS c ON c.id=hc.idcat
                            WHERE h.published=1 and h.approved=1 and c.published = 1 ".
                            $catid . $houseid."LIMIT 0, $max;";
            $db->setQuery($selectstring);
            $slides= $db->loadObjectList();       
        ?>
      <?php if($show_type=='horizontal'){ ?>
                <div class="swiper-container" style="height: <?php echo $height;?>px;">
    <?php } else { ?>
               <div class="swiper-container" style="height: <?php echo $numSlides*$height;?>px;">
            <?php  } ?>
          <span id="loaderGif" style="display:block;"></span>
        <div class="swiper-wrapper" style="visibility:hidden;">

          <?php
            $images = modRemOsTouchSliderHelper::getImagesFromRemSlideShow($params,$langContent);
            if (isset($images) && count($images) > 0):
                $num_img = '';
                $i=0;
                $seting_relation = abs($width/$height);
                $temp_relation = 0;
                $good_relation = 0;
                $best_relation = 100;

                foreach ($images as $image):

                    $image = str_replace ('./components','/components', $image->image);
                    $image = JPATH_BASE.$image;
                    $temp_original_img = $image;
                    if (JFile::exists($temp_original_img)) {
                        $info = getimagesize($temp_original_img);
                        $temp_relation = abs($info[0]/$info[1]);
                        $good_relation = abs($seting_relation-$temp_relation);
                        if($good_relation<$best_relation){
                            $best_relation = $good_relation;
                            $num_img = $i;
                        }
                    }
                    $i++;
                endforeach;

                $i=0;

                foreach ($images as $image):
                    //transform and create new image
                    $src=$image->image;
                    $path_parts = pathinfo($src);
                    $filename = $path_parts['dirname'].'/'.basename($src,'.'.$path_parts['extension']).'_'."$width".'_'."$height".'.'.$path_parts['extension'];//new file name
                    
                  $image->image_slideshow = $filename;
                  echo '<div class="swiper-slide">'; // start swiper-slide
                  if ($params->get('link_image',1) == 1) echo '<a href="'.$image->link.'" target="_self">';
                        {
                      echo '<img id="slideImgRm" src="'.modRemOsTouchSliderHelper::isHttpUrl($image->image).'" alt="img"/>';    
                    }
                    
                  if ($params->get('link_image',1) == 1) echo '</a>';

                    if ( $params->get('show_title')
                    || ($params->get('show_desc') && !empty($image->description))
                    || ($params->get('show_price') && !empty($image->price))
                    || ($params->get('show_address') && !empty($image->address))
                    ) :

              echo '<div id="captionSlide">'; // Slide description area START
            // ---------------------------------------------------------------
              if ($params->get('show_title')) {
                echo '<div class="slide-title">';
                  if ($params->get('link_title') && $image->link) {
                    echo '<a href="'.$image->link.'" target="_self">'.$image->title.'</a>';
                  } else {
                      echo $image->title;
                    }
                echo '</div>';
              }
            // ---------------------------------------------------------------
             if ($params->get('show_price') && !empty($image->price) ) {
               echo '<div class="slide-price">';
                   if ($params->get('link_price') && $image->link) {
                  echo '<a href="'.$image->link.'" target="_self">';
                   if ($realestatemanager_configuration['sale_separator']) echo (!$realestatemanager_configuration['price_unit_show'] ? : formatMoney(strip_tags($image->price), false, $realestatemanager_configuration['price_format']).' '.$image->priceunit) ;
                      else echo (!$realestatemanager_configuration['price_unit_show'] ? $image->priceunit.' '.strip_tags($image->price) : strip_tags($image->price).' '.$image->priceunit);
                  echo '</a>';
                   } else {
                      if ($realestatemanager_configuration['sale_separator']) echo (!$realestatemanager_configuration['price_unit_show'] ? $image->priceunit.' '.formatMoney(strip_tags($image->price), false, $realestatemanager_configuration['price_format']) : formatMoney(strip_tags($image->price), false, $realestatemanager_configuration['price_format']).' '.$image->priceunit);
                      else echo (!$realestatemanager_configuration['price_unit_show'] ? $image->priceunit.' '.strip_tags($image->price) : strip_tags($image->price).' '.$image->priceunit);
                   }
                 echo '</div>';
               }
            // ---------------------------------------------------------------
               if ($params->get('show_address')  && !empty($image->address)) {
                echo '<div class="slide-address">';
                   if ($params->get('link_address') && $image->link) {
                      echo '<a href="'.$image->link.'" target="_self">'.strip_tags($image->address).'</a>';
                    } else {
                      echo strip_tags($image->address);
                      }
                  echo '</div>';
               }
            // ---------------------------------------------------------------
               if ($params->get('show_desc')) {
                 echo '<div class="slide-description">';
                   if ($params->get('link_desc') && $image->link) {
                      echo '<a href="'.$image->link.'" target="_self">'.substr(strip_tags($image->description), 0 ,2).'</a>';
                   } else {
                     echo substr(strip_tags($image->description), 0 ,$params->get('limit_desc'));
                   }
                echo '</div>';
              }
            // ---------------------------------------------------------------
            echo '</div>'; // Slide description area END
						 endif;
                echo '</div>'; // swiper-slide END
              endforeach;
            endif; ?>
          </div>
        </div>
  </div>
      <div class="swiperPagination"></div>
</div>

<script type="text/javascript">

 jQuery(window).load(function() {

    var mySwiper<?php echo $module->id;?> = new Swiper('#rmTouchSlider<?php echo $module->id;?> .swiper-container',{
      <?php if($dots): ?>
      pagination: '#rmTouchSlider<?php echo $module->id;?> .swiperPagination',
      <?php endif; ?>
      loop: true,
      mode:'<?php echo $params->get('type','horizontal')?>',
      speed:<?php echo $params->get('time-interval','2000')?>,
      autoplay:<?php echo $autoplay ? $params->get('effect-speed') : '0'?>,
      autoResize: true,
      DOMAnimation: true,
      preventLinks: true,
      grabCursor: false,
      createPagination: <?php echo $dots?'true':'false'?>,
      paginationClickable: true,
      slidesPerView:
                          <?php if($params->get('countSlides') != '1') {
                              if(($slides[0]->max < $params->get('countSlides'))) { 
                                  echo $slides[0]->max;
                                   } else { 
                                  if($params->get('count_house') < $params->get('countSlides')) {
                                     echo $params->get('count_house');
                                  } else {
                                     echo $params->get('countSlides');
                                  }
                              }
                          } else {
                            echo '1';
                           } ?>
    });
    <?php if($arrows): ?>
      jQuery('#rmTouchSlider<?php echo $module->id;?> .arrow-left').on('click', function() {
        mySwiper<?php echo $module->id;?>.swipePrev();
      });
      jQuery('#rmTouchSlider<?php echo $module->id;?> .arrow-right').on('click', function() {
        mySwiper<?php echo $module->id;?>.swipeNext();
      });
    <?php endif; ?>

     <?php if($params->get('countSlides') != '1' && ($show_type=='horizontal')) {?>
     jQuery('.swiper-slide img#slideImgRm').css("maxWidth","90%");
     <?php }?>
    
    setTimeout(
      function(){
        jQuery(".swiper-wrapper").css("visibility", "visible");
        jQuery("#loaderGif").css("display", "none");
      }, 1400);
    var kw=0;
    function rmSizeOnLoad() {
        jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgRm").css('height', '100%');
        jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgRm").css('width', '100%');
        var captionHeight = null;
        jQuery('#rmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgRm').each(function(){
                var wid = jQuery('#rmTouchSlider<?php echo $module->id;?>  .realestateSlider').width();
                if ('<?php echo $show_type;?>' =='horizontal'){
                    //var h = <?php echo $height ;?>;
                    var h = <?php echo $height ;?> * wid /  <?php echo $width*$numSlides ;?>;
                }else{
                    var h = <?php echo $numSlides;?> * <?php echo $height ;?>;
                }

                kw=h/wid;
                captionHeight = kw*wid;
            jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-container").height(captionHeight);
            jQuery("#rmTouchSlider<?php echo $module->id;?> .realestateSlider").height(captionHeight);
        });
    }
    rmSizeOnLoad();
    jQuery(window).resize(function() { 
        jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgRm").css('height', '100%');
        jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgRm").css('width', '100%');
        var captionHeight = null;
        var hi = 0;
        jQuery('#rmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgRm').each(function(){
            //var w = jQuery('.realestateSlider').width();
            var w = jQuery('#rmTouchSlider<?php echo $module->id;?>  .realestateSlider').width();
            captionHeight=kw*w;
            if ('<?php echo $show_type ?>' == 'horizontal') {
                jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-container").height(captionHeight);
                jQuery("#rmTouchSlider<?php echo $module->id;?> .realestateSlider").height(captionHeight);
                jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-slide").height(captionHeight);
            }  else{
                jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-container").height(captionHeight);
                jQuery("#rmTouchSlider<?php echo $module->id;?> .realestateSlider").height(captionHeight);
                jQuery("#rmTouchSlider<?php echo $module->id;?> .swiper-slide").height(captionHeight);
                (mySwiper<?php echo $module->id;?>).reInit();
           }
      });
    });
 });
</script>
<noscript>Javascript is required to use <a href="http://ordasoft.com/Real-Estate-Manager-Software-Joomla.html">Real Estate Manager</a> software 
Real Estate Manager allows you to create an estate portal or real estate agency website in minutes. This Joomla component is perfect for independent estate agents, property rental companies and agencies, hotel booking, hotel manage, motel booking, motel manage.</noscript>
