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
 * Menu_Manager menu collection
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Model_Resource_Menu_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('menu_manager/menu');
    }

    /**
     * Add store filter to menu collection
     *
     * @param   int | Mage_Core_Model_Store $store
     * @param   bool $withAdmin
     * @return  Menu_Manager_Model_Resource_Menu_Collection
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = array($store->getId());
        }

        if (!is_array($store)) {
            $store = array($store);
        }

        if ($withAdmin) {
            $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
        }

        $this->addFilter('store_id', array('in' => $store), 'public');

        return $this;
    }

    /**
     * Join store relation table data if store filter is used
     *
     * @return Menu_Manager_Model_Resource_Menu_Collection
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store_id')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('menu_manager/menu_store')),
                'main_table.menu_id = store_table.menu_id',array()
            )->group('main_table.menu_id');
        }

        return parent::_renderFiltersBefore();
    }
}