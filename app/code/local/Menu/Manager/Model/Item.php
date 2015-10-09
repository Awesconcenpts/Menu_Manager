<?php
 /**
 * Menu_Manager
 *
 * @category Menu Manager
 * @package Menu_Manager
 * @author Alex<info@qdesignstudio.nl>
 * @copyright Copyright (c) 2015 Qdesogn Studio Pvt. Ltd (http://www.qdesignstudio.nl)
 * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
 */


/**
 * Menu_Manager menu item model
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Model_Item extends Mage_Core_Model_Abstract
{
    /**
     * Menu item url open window types
     */
    const TYPE_NEW_WINDOW = 'new_window';
    const TYPE_SAME_WINDOW = 'same_window';

    protected function _construct()
    {
        $this->_init('menu_manager/item');
    }

    /**
     * Prepare menu item url open window types
     *
     * @return array
     */
    public function getAvailableTypes()
    {
        $types = array(
            self::TYPE_SAME_WINDOW => Mage::helper('menu_manager')->__('Same Window'),
            self::TYPE_NEW_WINDOW => Mage::helper('menu_manager')->__('New Window'),
        );

        return $types;
    }
}
