<?php
/*
 * @package OS_Touch_Slider
 * @subpackage  mod_vehicle_OS_TouchSlider
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Sergey Bunyaev(sergey@bunyaev.ru); Sergey Solovyev(solovyev@solovyev.in.ua)
 * @Homepage: http://www.ordasoft.com
 * @version: 1.2
 * @license GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
global $mosConfig_absolute_path,$mosConfig_live_site;?>

<div id="vmTouchSlider<?php echo $module->id;?>" class="<?php echo $sufix; ?>">

  <div class="vehicleSlider <?php echo ($show_type=='horizontal') ? ' horizontal' : ' vertical'; ?>" style="
        <?php if($show_type=='horizontal'){
                 if ($format == '%') {
         ?>        max-width: <?php echo $width;?>%;
                   /*height: <?php echo $height;?>%;*/
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
            $carid = trim($params->get('car_id') );
            if($carid != "" ) {
                $carid = " and h.id in ( ". $carid ." ) " ;
            }
            $catid = trim($params->get('cat_id') );
            if($catid != "" ) {
                $catid = " and c.id in ( ". $catid ." ) " ;
            }
            if(!is_numeric($max = $params->get('count_car'))) $max = 20;
            $db = JFactory::getDBO();
            $selectstring = "SELECT DISTINCT count(h.id) as max FROM `#__vehiclemanager_vehicles` AS h 
                            LEFT JOIN `#__vehiclemanager_categories` AS hc ON hc.iditem=h.id 
                            LEFT JOIN `#__vehiclemanager_main_categories` AS c ON c.id=hc.idcat
                            WHERE h.published=1 and h.approved=1 and c.published = 1 ".
                            $catid . $carid."LIMIT 0, $max;";
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
            $images = modVehSlideShowHelper::getImagesFromVehSlideShow($params,$langContent);
            if (isset($images) && count($images) > 0):
                $num_img = '';
                $i=0;
                $seting_relation = abs($width/$height);
                $temp_relation = 0;
                $good_relation = 0;
                $best_relation = 100;

                foreach ($images as $image):
                    $image = JPATH_BASE.'/components/com_vehiclemanager/photos/'.$image->src;
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
                    $filename = $path_parts['dirname'].'/'.
                      basename($src,'.'.$path_parts['extension']).'_'."$width".'_'."$height".
                       '.'.$path_parts['extension'];//new file name
                    $image->image_slideshow = $filename;
                    echo '<div class="swiper-slide">'; // start swiper-slide
                    if ($params->get('link_image',1) == 1) echo '<a href="'.$image->link.'" target="_self">';
                   {
                        echo '<img id="slideImgVm" src="'.modVehSlideShowHelper::isHttpUrl($image->image).'" alt="img"/>'; 
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
                   if ($vehiclemanager_configuration['sale_separator']) echo (!$vehiclemanager_configuration['price_unit_show'] ? : formatMoney(strip_tags($image->price), false, $vehiclemanager_configuration['price_format']).' '.$image->priceunit) ;
                      else echo (!$vehiclemanager_configuration['price_unit_show'] ? $image->priceunit.' '.strip_tags($image->price) : strip_tags($image->price).' '.$image->priceunit);
                  echo '</a>';
                   } else {
                      if ($vehiclemanager_configuration['sale_separator']) echo (!$vehiclemanager_configuration['price_unit_show'] ? $image->priceunit.' '.formatMoney(strip_tags($image->price), false, $vehiclemanager_configuration['price_format']) : formatMoney(strip_tags($image->price), false, $vehiclemanager_configuration['price_format']).' '.$image->priceunit);
                      else echo (!$vehiclemanager_configuration['price_unit_show'] ? $image->priceunit.' '.strip_tags($image->price) : strip_tags($image->price).' '.$image->priceunit);
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

    var mySwiper<?php echo $module->id;?> = new Swiper('#vmTouchSlider<?php echo $module->id;?> .swiper-container',{
      <?php if($dots): ?>
      pagination: '#vmTouchSlider<?php echo $module->id;?> .swiperPagination',
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
                  if($params->get('count_car') < $params->get('countSlides')) {
                     echo $params->get('count_car');
                  } else {
                     echo $params->get('countSlides');
                  }
              }
          } else {
            echo '1';
           } ?>

    });
    
    <?php if($arrows): ?>
      jQuery('#vmTouchSlider<?php echo $module->id;?> .arrow-left').on('click', function() {
        mySwiper<?php echo $module->id;?>.swipePrev();
      });
      jQuery('#vmTouchSlider<?php echo $module->id;?> .arrow-right').on('click', function() {
        mySwiper<?php echo $module->id;?>.swipeNext();
      });
    <?php endif; ?>
    <?php if($params->get('countSlides') != '1' && ($show_type=='horizontal')) {?>
     jQuery('.swiper-slide img#slideImgVm').css("maxWidth","90%");
     <?php }?>
    setTimeout(
      function(){
        jQuery(".swiper-wrapper").css("visibility", "visible");
        jQuery("#loaderGif").css("display", "none");
      }, 1400);
    var kw=0;
    function vmSizeOnLoad() {
        jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgVm").css('height', '100%');
        jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgVm").css('width', '100%');  
        var captionHeight = null;
        jQuery('#vmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgVm').each(function(){
                var wid = jQuery('#vmTouchSlider<?php echo $module->id;?>  .vehicleSlider').width();
                if ('<?php echo $show_type;?>' =='horizontal'){
                    //var h = <?php echo $height ;?>;
                    var h = <?php echo $height ;?> * wid /  <?php echo $width*$numSlides ;?>;
                }else{
                    var h = <?php echo $numSlides;?> * <?php echo $height ;?>;
                }
                kw=h/wid;
                captionHeight = kw*wid;
                jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-container").height(captionHeight);
                jQuery("#vmTouchSlider<?php echo $module->id;?> .vehicleSlider").height(captionHeight);
        });
    }
    vmSizeOnLoad();
    jQuery(window).resize(function resize () {
        jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgVm").css('height', '100%');
        jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgVm").css('width', '100%');
        var captionHeight = null;
        var hi = 0;
        jQuery('#vmTouchSlider<?php echo $module->id;?> .swiper-slide img#slideImgVm').each(function(){
            //var w = jQuery('.vehicleSlider').width();
            var w = jQuery('#vmTouchSlider<?php echo $module->id;?>  .vehicleSlider').width();
            captionHeight=kw*w;
            if ('<?php echo $show_type ?>' == 'horizontal') {
                jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-container").height(captionHeight);
                jQuery("#vmTouchSlider<?php echo $module->id;?> .vehicleSlider").height(captionHeight);
                jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-slide").height(captionHeight);
            }  else{
                jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-container").height(captionHeight);
                jQuery("#vmTouchSlider<?php echo $module->id;?> .vehicleSlider").height(captionHeight);
                jQuery("#vmTouchSlider<?php echo $module->id;?> .swiper-slide").height(captionHeight);
                (mySwiper<?php echo $module->id;?>).reInit();
            }
        });
    });
 });

</script>