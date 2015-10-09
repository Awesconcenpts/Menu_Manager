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
 * Menu_Manager menu model helper
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Model_Menu extends Mage_Core_Model_Abstract
{
    /**
     * Menu types
     */
    const TYPE_HORIZONTAL = 'horizontal';
    const TYPE_VERTICAL = 'vertical';
    const TYPE_NONE = 'none';

    /**
     * Cache tag
     */
    const CACHE_TAG = 'menumanager_menu';

    protected function _construct()
    {
        $this->_init('menu_manager/menu');
    }

    /**
     * Prepare menu types
     *
     * @return array
     */
    public function getAvailableTypes()
    {
        $types = array(
            self::TYPE_NONE => Mage::helper('menu_manager')->__('None'),
            self::TYPE_VERTICAL => Mage::helper('menu_manager')->__('Vertical'),
            self::TYPE_HORIZONTAL => Mage::helper('menu_manager')->__('Horizontal'),
        );

        return $types;
    }
}
