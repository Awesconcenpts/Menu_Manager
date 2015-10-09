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
 * Menu_Manager menu item parent title renderer
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Adminhtml_Menu_Edit_Tab_Renderer_Parent
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Returns item title by id or 'Root' if no parent specified
     *
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        if ($value = $row->getData($this->getColumn()->getIndex())) {
            return Mage::getModel('menu_manager/item')->load($value)->getTitle();
        }

        return Mage::helper('menu_manager')->__('Root');
    }
}