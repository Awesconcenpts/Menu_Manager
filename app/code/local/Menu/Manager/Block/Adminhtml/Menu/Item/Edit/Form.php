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
 * Menu_Manager menu Edit form
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Adminhtml_Menu_Item_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        /* @var $model Menu_Manager_Model_Item*/
        $model = Mage::registry('menumanager_menu_item');
        $menuId = $this->getRequest()->getParam('menu_id');

        $form = new Varien_Data_Form(array(
            'method' => 'post',
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save_item', array('menu_id' => $menuId)),
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('menu_manager')->__('General Information'))
        );

        if ($model->getItemId()) {
            $fieldset->addField('item_id', 'hidden', array(
                'name'  => 'item_id',
            ));
        }

        if ($model->getMenuId()) {
            $fieldset->addField('menu_id', 'hidden', array(
                'name'  => 'menu_id',
            ));
        }

        if ($model->getIdentifier()) {
            $fieldset->addField('identifier', 'hidden', array(
                'name' => 'identifier',
            ));
        }
		$fieldset->addField('menu_data', 'hidden', array(
            'name'      => 'menu_data',
        ));
        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('menu_manager')->__('Title'),
            'title'     => Mage::helper('menu_manager')->__('Title'),
            'required'  => true,
        ));

        $fieldset->addField('parent_id', 'select', array(
            'name'      => 'parent_id',
            'label'     => Mage::helper('menu_manager')->__('Parent'),
            'title'     => Mage::helper('menu_manager')->__('Parent'),
            'options'   => $model->getCollection()
                ->addMenuFilter($menuId)
                ->toItemOptionArray(),
            'required'  => true,
        ));
$str=new Menu_Manager_Class();
$str=$str->toHtml();
        $fieldset->addField('url', 'text', array(
            'name'      => 'url',
            'label'     => Mage::helper('menu_manager')->__('Url'),
            'title'     => Mage::helper('menu_manager')->__('Url'),
			'after_element_html' =>$str,// '<small>Comments-----------</small>',
			'autocomplete'=>'false',
            'note'      => Mage::helper('cms')->__('Use " / " For Item With Base Url.')
        ));

        $fieldset->addField('type', 'select', array(
            'name'      => 'type',
            'label'     => Mage::helper('menu_manager')->__('Url Window Type'),
            'title'     => Mage::helper('menu_manager')->__('Url Window Type'),
            'options'   => $model->getAvailableTypes(),
            'required'  => true
        ));

        $fieldset->addField('css_class', 'text', array(
            'name'      => 'css_class',
            'label'     => Mage::helper('menu_manager')->__('CSS Class'),
            'title'     => Mage::helper('menu_manager')->__('CSS Class'),
            'note'      => Mage::helper('cms')->__('Space Separated Class Names')
        ));

        $fieldset->addField('position', 'text', array(
            'name'      => 'position',
            'label'     => Mage::helper('menu_manager')->__('Position'),
            'title'     => Mage::helper('menu_manager')->__('Position'),
            'class'     => 'validate-number',
            'required'  => true
        ));

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('menu_manager')->__('Status'),
            'title'     => Mage::helper('menu_manager')->__('Menu Item Status'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('menu_manager')->__('Enabled'),
                '0' => Mage::helper('menu_manager')->__('Disabled'),
            ),
        ));

        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        Mage::dispatchEvent(
            'adminhtml_cms_menu_item_edit_prepare_form',
            array('form' => $form)
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
