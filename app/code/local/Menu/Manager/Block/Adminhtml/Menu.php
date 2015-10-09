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
 * MenuManager menu grid container
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Adminhtml_Menu extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_menu';
        $this->_blockGroup = 'menu_manager';
        $this->_headerText = Mage::helper('menu_manager')->__('Manage Menus');

        parent::__construct();
    }

    /**
     * Get header css class
     *
     * @return string
     */
    public function getHeaderCssClass()
    {
        return 'icon-head head-cms-block ' . parent::getHeaderCssClass();
    }
}