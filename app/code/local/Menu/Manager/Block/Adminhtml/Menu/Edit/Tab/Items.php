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
 * Menu_Manager menu item grid
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Adminhtml_Menu_Edit_Tab_Items
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('cmsMenuItemsGrid');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare collection for grid
     *
     * @return Menu_Manager_Block_Adminhtml_Menu_Edit_Tab_Items
     */
    protected function _prepareCollection()
    {
        /* @var $collection Menu_Manager_Model_Resource_Item_Collection */
        $collection = Mage::getModel('menu_manager/item')->getResourceCollection()
            ->addMenuFilter(Mage::registry('menumanager_menu'))
            ->setPositionOrder();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns
     *
     * @return Menu_Manager_Block_Adminhtml_Menu_Edit_Tab_Items
     */
    protected function _prepareColumns()
    {
        /* @var $model Menu_Manager_Model_Menu*/
        $menuModel = Mage::registry('menumanager_menu');

        /* @var $model Menu_Manager_Model_Item*/
        $ItemModel = Mage::getModel('menu_manager/item');

        $this->addColumn('item_title', array(
            'header'    => Mage::helper('menu_manager')->__('Title'),
            'index'     => 'title',
        ));

        $this->addColumn('item_parent_id', array(
            'header'    => Mage::helper('menu_manager')->__('Parent'),
            'index'     => 'parent_id',
            'type'      => 'options',
            'renderer'  => 'Menu_Manager_Block_Adminhtml_Menu_Edit_Tab_Renderer_Parent',
            'options'   => $ItemModel->getCollection()
                ->addMenuFilter($menuModel)
                ->toItemOptionArray(),
        ));

        $this->addColumn('item_url', array(
            'header'    => Mage::helper('menu_manager')->__('Url'),
            'index'     => 'url',
        ));

        $this->addColumn('item_type', array(
            'header'    => Mage::helper('menu_manager')->__('Type'),
            'index'     => 'type',
            'type'      => 'options',
            'options'   => $ItemModel->getAvailableTypes(),
        ));

        $this->addColumn('item_css_class', array(
            'header'    => Mage::helper('menu_manager')->__('CSS Class'),
            'index'     => 'css_class',
        ));

        $this->addColumn('item_position', array(
            'header'    => Mage::helper('menu_manager')->__('Position'),
            'index'     => 'position',
        ));

        $this->addColumn('item_is_active', array(
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
     * Return row url
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit_item', array(
            'item_id' => $row->getId(),
            'active_tab' => 'menu_page_tabs_items_section',
            'menu_id' => $this->getRequest()->getParam('menu_id'),
        ));
    }
}