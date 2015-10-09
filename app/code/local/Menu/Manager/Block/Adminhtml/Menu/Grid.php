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
 * Menu_Manager menu grid
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Adminhtml_Menu_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('cmsMenuGrid');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection for grid
     *
     * @return Menu_Manager_Block_Adminhtml_Menu_Grid
     */
    protected function _prepareCollection()
    {
        /* @var $collection Menu_Manager_Model_Resource_Menu_Collection */
        $collection = Mage::getModel('menu_manager/menu')
            ->getResourceCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return Menu_Manager_Block_Adminhtml_Menu_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('title', array(
            'header'    => Mage::helper('menu_manager')->__('Title'),
            'index'     => 'title',
        ));

        $this->addColumn('identifier', array(
            'header'    => Mage::helper('menu_manager')->__('Identifier'),
            'index'     => 'identifier',
        ));

        $this->addColumn('type', array(
            'header'    => Mage::helper('menu_manager')->__('Type'),
            'index'     => 'type',
            'type'      => 'options',
            'options'   => Mage::getSingleton('menu_manager/menu')->getAvailableTypes(),
        ));

        $this->addColumn('css_class', array(
            'header'    => Mage::helper('menu_manager')->__('CSS Class'),
            'index'     => 'css_class',
        ));

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'        => Mage::helper('menu_manager')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback' => array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('is_active', array(
            'header'    => Mage::helper('menu_manager')->__('Status'),
            'index'     => 'is_active',
            'type'      => 'options',
            'options'   => array(
                0 => Mage::helper('menu_manager')->__('Disabled'),
                1 => Mage::helper('menu_manager')->__('Enabled')
            ),
        ));

        return parent::_prepareColumns();
    }

    /**
     * After collection load operations - load to add store data
     *
     * @return Mage_Adminhtml_Block_Widget_Grid | void
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * Store filter condition callback - add store filter when needed
     *
     * @param $collection Menu_Manager_Model_Resource_Menu_Collection
     * @param $column Mage_Adminhtml_Block_Widget_Grid_Column
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }

    /**
     * Return row url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('menu_id' => $row->getId()));
    }
}