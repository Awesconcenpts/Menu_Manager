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
 * Menu_Manager menu Edit form container
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Adminhtml_Menu_Item_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId   = 'item_id';
        $this->_blockGroup = 'menu_manager';
        $this->_controller = 'adminhtml_menu_item';

        parent::__construct();

        $this->_updateButton('back', 'onclick', 'setLocation(\'' . $this->_getBackUrl() . '\')');
        $this->_updateButton('delete', 'onclick', 'setLocation(\'' . $this->_getDeleteUrl() . '\')');
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('menumanager_menu_item')->getId()) {
            return Mage::helper('menu_manager')->__("Edit Menu Item '%s'",
                $this->escapeHtml(Mage::registry('menumanager_menu_item')->getTitle()));
        } else {
            return Mage::helper('menu_manager')->__('New Menu Item');
        }
    }

    /**
     * get header css class
     *
     * @return string
     */
    public function getHeaderCssClass()
    {
        return 'icon-head head-cms-block ' . strtr($this->_controller, '_', '-');
    }

    /**
     * Getter of url for "Back" button
     *
     * @return string
     */
    protected function _getBackUrl()
    {
        return $this->getUrl('*/*/edit', array(
            'menu_id' => $this->getRequest()->getParam('menu_id'),
            '_current' => true
        ));
    }

    /**
     * Getter of url for "Delete" button
     *
     * @return string
     */
    protected function _getDeleteUrl()
    {
        return $this->getUrl('*/*/delete_item', array(
            'menu_id' => $this->getRequest()->getParam('menu_id'),
            'item_id' => $this->getRequest()->getParam('item_id')
        ));
    }
}