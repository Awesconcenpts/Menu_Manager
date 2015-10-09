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
class Menu_Manager_Model_Resource_Item extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('menu_manager/menu_item', 'item_id');
    }

    /**
     * Load an object using 'identifier' field
     *
     * @param   Mage_Core_Model_Abstract    $object
     * @param   mixed                       $value
     * @param   string                      $field
     * @return  Menu_Manager_Model_Resource_Item
     */
    public function load(Mage_Core_Model_Abstract $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Perform operations before object save - add unique 'identifier' and check item parent
     *
     * @param Menu_Manager_Model_Item $object
     * @return Menu_Manager_Model_Resource_Item
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId() && $object->getId() == $object->getParentId()) {
            Mage::throwException(Mage::helper('menu_manager')
                ->__('Menu item can not be parent to itself.'));

            return $this;
        }

        if (!$object->getMenuId()) {
            Mage::throwException(Mage::helper('menu_manager')
                ->__('Menu item parent menu must be specified.'));

            return $this;
        }

        if (!$object->getIdentifier()) {
            $object->setIdentifier('menu_' . $object->getMenuId() . '_item_' . date('Y_m_d_H_i_s'));
        }

        Mage::app()->cleanCache(Menu_Manager_Model_Menu::CACHE_TAG);

        return $this;
    }
}
