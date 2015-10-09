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
 * Menu_Manager menu item collection
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Model_Resource_Item_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('menu_manager/item');
    }

    /**
     * Add menu filter to item collection
     *
     * @param   int | Menu_Manager_Model_Menu $menu
     * @return  Menu_Manager_Model_Resource_Item_Collection
     */
    public function addMenuFilter($menu)
    {
        if ($menu instanceof Menu_Manager_Model_Menu) {
            $menu = $menu->getId();
        }

        $this->addFilter('menu_id', $menu);

        return $this;
    }

    /**
     * Add status filter to item collection
     *
     * @return  Menu_Manager_Model_Resource_Item_Collection
     */
    public function addStatusFilter()
    {
        $this->addFilter('is_active', 1);

        return $this;
    }

    /**
     * Set order to item collection
     *
     * @return Menu_Manager_Model_Resource_Item_Collection
     */
    public function setPositionOrder()
    {
        $this->setOrder('parent_id', 'asc');
        $this->setOrder('position', 'asc');

        return $this;
    }

    /**
     * Collection to option array method
     *
     * @return array
     */
    public function toItemOptionArray()
    {
        $result = array();
        $result['0'] = Mage::helper('menu_manager')->__('Root');

        foreach ($this as $item) {
            $result[$item->getData('item_id')] = $item->getData('title');
        }

        return $result;
    }
}