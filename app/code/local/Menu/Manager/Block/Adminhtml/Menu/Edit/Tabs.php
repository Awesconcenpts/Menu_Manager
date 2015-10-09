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
 * Menu_Menu edit page left menu
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Adminhtml_Menu_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('menu_page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('menu_manager')->__('Menu Information'));
    }

    /**
     * Add "Menu Items" tab and its content
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        if ($this->getRequest()->getParam('menu_id')) {
            $itemsTabContent = $this->getLayout()
                ->createBlock('menu_manager/adminhtml_menu_edit_tab_items')
                ->toHtml();
        } else {
            $itemsTabContent = Mage::helper('menu_manager')->__(
                '<ul class="messages"><li class="notice-msg"><ul><li><span>%s</span></li></ul></li></ul>',
                Mage::helper('menu_manager')->__('You will be able to manage items after saving this menu.')
            );
        }

        $itemSectionStatus = $this->getRequest()
            ->getParam('active_tab') == 'menu_page_tabs_items_section' ? true : false;

        $this->addTab('items_section', array(
            'label' => $this->__('Menu Items'),
            'title' => $this->__('Menu Items'),
            'active' => $itemSectionStatus,
            'content' => $itemsTabContent,
        ));

        return parent::_beforeToHtml();
    }
}
