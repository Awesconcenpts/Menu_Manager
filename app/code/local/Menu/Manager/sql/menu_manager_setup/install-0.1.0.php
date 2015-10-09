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
 * Menu_Manager setup
 *
 * @category    Menu
 * @package     Menu_Manager
 */
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
/* Create table "menu_manager/menu" */
$table = $installer->getConnection()
    ->newTable(
        $installer->getTable('menu_manager/menu')
    )
    ->addColumn(
        'menu_id', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'identity' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'Menu ID'
    )
    ->addColumn(
        'title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false,
        ), 'Menu Title'
    )
    ->addColumn(
        'identifier', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false,
        ), 'Menu String Identifier'
    )
    ->addColumn(
        'type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false,
            'default'   => 'none',
        ), 'Menu Type'
    )
    ->addColumn(
        'css_class', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
            'default'  => null,
        ), 'Menu CSS Class'
    )
    ->addColumn(
        'is_active', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'nullable'  => false,
            'default'   => '1',
        ), 'Is Menu Active'
    )
    ->setComment('MenuManager Menu Table');

$installer->getConnection()->createTable($table);

/* Create table "menu_manager/menu_store" */
$table = $installer->getConnection()
    ->newTable(
        $installer->getTable('menu_manager/menu_store')
    )
    ->addColumn(
        'menu_id', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'nullable'  => false,
            'primary'   => true,
        ), 'Menu ID'
    )
    ->addColumn(
        'store_id', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Store ID'
    )
    ->addIndex(
        $installer->getIdxName('menu_manager/menu_store', array('store_id')), array('store_id')
    )
    ->addForeignKey(
        $installer->getFkName(
            'menu_manager/menu_store', 'menu_id', 'menu_manager/menu', 'menu_id'
        ),
        'menu_id', $installer->getTable('menu_manager/menu'), 'menu_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $installer->getFkName(
            'menu_manager/menu_store', 'store_id', 'core/store', 'store_id'
        ),
        'store_id', $installer->getTable('core/store'), 'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('MenuManager Menu To Store Linkage Table');

$installer->getConnection()->createTable($table);

/* Create table "menu_manager/menu_item" */
$table = $installer->getConnection()
    ->newTable(
        $installer->getTable('menu_manager/menu_item')
    )
    ->addColumn(
        'item_id', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'identity' => true,
            'nullable' => false,
            'primary'  => true,
        ), 'Item ID'
    )
    ->addColumn(
        'menu_id', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'nullable'  => false,
        ), 'Menu ID'
    )
    ->addColumn(
        'parent_id', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'nullable'  => false,
        ), 'Parent ID'
    )
    ->addColumn(
        'title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false,
        ), 'Item Title'
    )
    ->addColumn(
        'identifier', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false,
        ), 'Item String Identifier'
    )
    ->addColumn(
        'url', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
            'default'  => null,
        ), 'Item Url'
    )
	->addColumn(
        'menu_data', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
            'default'  => null,
        ), 'menu data'
    )
    ->addColumn(
        'type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable'  => false,
            'default'   => 'same_window',
        ), 'Item Open Type'
    )
    ->addColumn(
        'css_class', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
            'nullable' => true,
            'default'  => null,
        ), 'Item CSS Class'
    )
    ->addColumn(
        'position', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'nullable'  => false,
            'default'   => '0',
        ), 'Item Position'
    )
    ->addColumn(
        'is_active', Varien_Db_Ddl_Table::TYPE_TINYINT, null, array(
            'nullable'  => false,
            'default'   => '1',
        ), 'Is Item Active'
    )
    ->addIndex(
        $installer->getIdxName('menu_manager/menu_item', array('identifier')), array('identifier')
    )
    ->addForeignKey(
        $installer->getFkName(
            'menu_manager/menu_item', 'menu_id', 'menu_manager/menu', 'menu_id'
        ),
        'menu_id', $installer->getTable('menu_manager/menu'), 'menu_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('MenuManager Menu Item Table');

$installer->getConnection()->createTable($table);
