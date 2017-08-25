<?php
/**
 *
 ** @package OS_Vehicle_Touch_Slider_Pro
 * @subpackage  mod_vehicle_OS_TouchSlider
 * @copyright Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Sergey Bunyaev(sergey@bunyaev.ru); Sergey Solovyev(solovyev@solovyev.in.ua)
 * @Homepage: http://www.ordasoft.com
 ** @version: 3.2 
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * */
defined('_JEXEC') or die;

function checkFiles($items, $moduleId) {
    $files = array();
    if (is_array($items)) {
        foreach ($items as $item) {
            $files[$item->file] = true;
        }
    }

    $dir = JPATH_ROOT . '/images/os_touchslider_' . $moduleId.'/';
    delete_old($files, $dir . '/manager');
    delete_old($files, $dir . '/original');
    delete_old($files, $dir . '/slideshow');
    delete_old($files, $dir . '/thumbnail');
}
jimport('joomla.filesystem.folder');
function delete_old($files, $dir) {
    if (!JFolder::exists($dir)) return;
    foreach (JFolder::files($dir) as $file) {
        if (!array_key_exists($file, $files)) {
            JFile::delete($dir . '/' . $file);
        }
    }
}
