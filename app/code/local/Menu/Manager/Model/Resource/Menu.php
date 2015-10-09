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
 * Menu_Manager menu model
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Model_Resource_Menu extends Mage_Core_Model_Resource_Db_Abstract
{
    protected function _construct()
    {
        $this->_init('menu_manager/menu', 'menu_id');
    }

    /**
     * Load an object using 'identifier' field
     *
     * @param   Mage_Core_Model_Abstract    $object
     * @param   mixed                       $value
     * @param   string                      $field
     * @return  Menu_Manager_Model_Resource_Menu
     */
    public function load(Mage_Core_Model_Abstract $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Perform operations before object save - check for unique 'identifier'
     *
     * @param Menu_Manager_Model_Menu $object
     * @return Menu_Manager_Model_Resource_Menu
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if (!$this->getIsUniqueMenuToStores($object)) {
            Mage::throwException(Mage::helper('menu_manager')
                ->__('A menu identifier with the same properties already exists in the selected store.'));
        }

        Mage::app()->cleanCache(Menu_Manager_Model_Menu::CACHE_TAG);

        return $this;
    }

    /**
     * Perform operations after object load - add stores data
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Menu_Manager_Model_Resource_Menu
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());

            $object->setData('store_id', $stores);
            $object->setData('stores', $stores);
        }

        return parent::_afterLoad($object);
    }

    /**
     * Perform operations after object save - update menu stores data
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Menu_Manager_Model_Resource_Menu
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();

        $table  = $this->getTable('menu_manager/menu_store');

        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'menu_id = ?'     => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );

            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();

            foreach ($insert as $storeId) {
                $data[] = array(
                    'menu_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }

            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }

        return parent::_afterSave($object);
    }

    /**
     * Check if menu identifier is unique in store(s).
     *
     * @param Mage_Core_Model_Abstract $object
     * @return bool
     */
    public function getIsUniqueMenuToStores(Mage_Core_Model_Abstract $object)
    {
        if (Mage::app()->isSingleStoreMode()) {
            $stores = array(Mage_Core_Model_App::ADMIN_STORE_ID);
        } else {
            $stores = (array)$object->getData('stores');
        }

        $select = $this->_getReadAdapter()->select()
            ->from(
                array('menu' => $this->getMainTable())
            )
            ->join(
                array('menu_stores' => $this->getTable('menu_manager/menu_store')),
                'menu.menu_id = menu_stores.menu_id', array()
            )
            ->where('menu.identifier = ?', $object->getData('identifier'))
            ->where('menu_stores.store_id IN (?)', $stores);

        if ($object->getId()) {
            $select->where('menu.menu_id <> ?', $object->getId());
        }

        if ($this->_getReadAdapter()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * Get store IDs to which menu is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getTable('menu_manager/menu_store'), 'store_id')
            ->where('menu_id = :menu_id');

        $binds = array(
            ':menu_id' => (int) $id
        );

        return $adapter->fetchCol($select, $binds);
    }

    /**
     * Load only appropriate menu to specified store
     *
     * @param string $field
     * @param mixed  $value
     * @param Menu_Manager_Model_Menu $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = array(
                (int) $object->getStoreId(),
                Mage_Core_Model_App::ADMIN_STORE_ID
            );

            $select->join(
                array('menu_store' => $this->getTable('menu_manager/menu_store')),
                $this->getMainTable() . '.menu_id = menu_store.menu_id',
                array('store_id')
            )
            ->where('menu_store.store_id in (?) ', $stores)
            ->where('is_active = ?', 1)
            ->order('store_id DESC')
            ->limit(1);
        }

        return $select;
    }
}
