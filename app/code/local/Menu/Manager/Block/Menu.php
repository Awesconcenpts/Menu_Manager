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
 * MenuManager menu block
 *
 * @category    Menu
 * @package     Menu_Manager
 */
class Menu_Manager_Block_Menu extends Mage_Core_Block_Template
{
    /**
     * Menu data tree
     *
     * @var Varien_Data_Tree_Node
     */
    protected $_menu;

    /**
     * Menu model
     *
     * @var Menu_Manager_Model_Menu
     */
    protected $_menuModel;

    /**
     * Current url path
     *
     * @var string
     */
    protected $_currentUrlPath;

    /**
     * Current category url path
     *
     * @var string
     */
    protected $_currentCategoryUrlPath;

    /**
     * Base url path
     *
     * @var string
     */
    protected $_baseUrlPath;

    /**
     * Init menu tree structure and retrieve url paths
     */
    public function _construct()
    {
		
        $this->_currentUrlPath = $this->_getTrimmedPath(Mage::helper('core/url')->getCurrentUrl());
        $this->_menu = new Varien_Data_Tree_Node(array(), 'root', new Varien_Data_Tree());
        $this->_baseUrlPath = $this->_getTrimmedPath(Mage::getBaseUrl());

        if ($currentCategory = Mage::registry('current_category')) {
            $this->_currentCategoryUrlPath = $this->_getTrimmedPath($currentCategory->getUrl());
        }
		
    }

    /**
     * Prepare default template for menu
     *
     * @return Menu_Manager_Block_Menu
     */
    protected function _prepareLayout()
    {
        $this->setTemplate('menu/manager/menu.phtml');
    }

    /**
     * Return loaded menu
     *
     * @return bool | Menu_Manager_Model_Menu
     */
    public function getMenu()
    {
		
        if ($this->_menuModel) {
            return $this->_menuModel;
        }

        if ($menuId = $this->getData('menu_id')) {
			
            $menu = Mage::getModel('menu_manager/menu')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($menuId);

            if ($menu->getIsActive()) {
                if ($this->getData('custom_type')) {
                    $menu->setData('type', $this->getData('custom_type'));
                }

                $this->_menuModel = $menu;

                return $menu;
            }
        }

        return false;
    }

    /**
     * Return menu html
     *
     * @return string | bool
     */
    public function getMenuHtml()
    {
		
        if ($this->getMenu() && $this->_fillMenuTree()) {
            return $this->_getMenuHtml($this->_menu);
        }

        return false;
    }

    /**
     * Return recursively generated menu html
     *
     * @param Varien_Data_Tree_Node $menuTree
     * @return string
     */
    protected function _getMenuHtml(Varien_Data_Tree_Node $menuTree)
    {
        $html = '';
        $counter = 1;
        $children = $menuTree->getChildren();
        $childrenCount = $children->count();
        $parentLevel = $menuTree->getLevel();
        $childLevel = is_null($parentLevel) ? 0 : $parentLevel + 1;

        foreach ($children as $child) {
            $this->_formatItemUrl($child);
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setIsActive($this->_hasCurrentUrl($child));
            $child->setType($child->getType() == 'new_window' ? 'target="_blank"' : '');
			$class=$this->_getMenuItemClasses($child);
            $html .= '<li class="' . $class . '">';
			$link=$child->getMenuData();
			if($link!=''){
				$da=explode('-',$link);
				if(isset($da[0]) && $da[0]=='cms' && isset($da[1])){
					$link=Mage::helper('cms/page')->getPageUrl($da[1]);
				}elseif(isset($da[0]) && $da[0]=='category' && isset($da[1])){
					$link=Mage::getModel("catalog/category")->load($da[1])->getUrl();
				}else{
				$link=$child->getFullUrl();
				}
			}else{
				$link=$child->getFullUrl();
			}
            if ($link) {
                $html .= '<a href="' . $link . '" ' . $child->getType() . '>';
            } else {
                $html .= '';
            }

            $html .= '' . $this->escapeHtml($child->getTitle()) . '';

            if ($link) {
				 if ($child->hasChildren()) {
					$html .='<span class="caret"></span>'; 
				 }
                $html .= '</a>';
            } else {
                $html .= '';
            }

            if ($child->hasChildren()) {
               // $html .= '<div><ul class="level' . $childLevel . '">';
                //$html .= $this->_getMenuHtml($child);
                //$html .= '</ul></div>';
				 $html .= '<ul class="dropdown-menu level' . $childLevel . '">';
                $html .= $this->_getMenuHtml($child);
                $html .= '</ul>';
            }

            $html .= '</li>';
            $counter++;
        }

        return $html;
    }

    /**
     * Returns string of menu item's classes
     *
     * @param Varien_Data_Tree_Node $item
     * @return string
     */
    protected function _getMenuItemClasses(Varien_Data_Tree_Node $item)
    {
        $classes = 'level' . $item->getLevel() . ' ';

        if ($item->getIsFirst()) {
            $classes .= 'first ';
        }

        if ($item->getIsActive()) {
            $classes .= 'active ';
        }

        if ($item->getIsLast()) {
            $classes .= 'last ';
        }

        if (!$item->getFullUrl()) {
            $classes .= 'title ';
        }

        if ($item->getCssClass()) {
            $classes .= $item->getCssClass();
        }

        if ($item->hasChildren()) {
            $classes .= ' parent';
        }

        return $classes;
    }

    /**
     * Checks if menu item's or child's url is same as current url
     *
     * @param Varien_Data_Tree_Node $item
     * @return bool
     */
    protected function _hasCurrentUrl(Varien_Data_Tree_Node $item)
    {
        $this->_formatItemUrl($item);
        $homeFlag = $item->getUrl() == '/' ? true : false;
        $itemUrl = $this->_getTrimmedPath($item->getFullUrl());

        if ($this->_baseUrlPath == $this->_currentUrlPath && $homeFlag) {
            return true;
        }

        if ($itemUrl) {
            if ($this->_currentUrlPath == $itemUrl && !$homeFlag) {
                return true;
            }

            if ($this->_currentCategoryUrlPath == $itemUrl) {
                return true;
            }
        }

        if ($item->hasChildren()) {
            foreach ($item->getChildren() as $child) {
                if ($this->_hasCurrentUrl($child)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Format item url
     *
     * @param Varien_Data_Tree_Node $item
     */
    protected function _formatItemUrl(Varien_Data_Tree_Node $item)
    {
        if (!($itemFullUrl = $item->getFullUrl()) && ($itemUrl = $item->getUrl())) {
            if (strpos($itemUrl, '://') === false) {
                $itemUrl = $this->_getUrlModel()->getDirectUrl($itemUrl != '/' ? $itemUrl : '');
            }

            $item->setFullUrl($itemUrl);
        };
    }

    /**
     * Retrieve parsed and trimmed url path
     *
     * @param $url
     * @return string
     */
    protected function _getTrimmedPath($url)
    {
        $url = parse_url($url);
        return rtrim($url['path'], '/');
    }

    /**
     * Fill menu data tree
     *
     * @return bool
     */
    protected  function _fillMenuTree()
    {
        $collection = $this->_getMenuItemCollection();

        if (!$collection->count()) {
            return false;
        }

        $nodes = array();
        $nodes['0'] = $this->_menu;

        foreach ($collection as $item) {
            if (!isset($nodes[$item->getParentId()])) {
                continue;
            }

            $parentItemNode = $nodes[$item->getParentId()];

            $itemNode = new Varien_Data_Tree_Node(
                $item->getData(), 'item_id', $parentItemNode->getTree(), $parentItemNode
            );

            $nodes[$item->getId()] = $itemNode;
            $parentItemNode->addChild($itemNode);
        }

        return true;
    }

    /**
     * Return filtered menu item collection
     *
     * @return Menu_Manager_Model_Resource_Item_Collection
     */
    protected function _getMenuItemCollection()
    {
        return Mage::getModel('menu_manager/item')->getCollection()
            ->addMenuFilter($this->_menuModel)
            ->setPositionOrder()
            ->addStatusFilter();
    }

    /**
     * Get tags array for saving cache
     *
     * @return array
     */
    public function getCacheTags()
    {
        return array(Menu_Manager_Model_Menu::CACHE_TAG);
    }

    /**
     * Get block cache life time
     *
     * @return int
     */
    public function getCacheLifetime()
    {
        return null;
    }

    /**
     * Get cache key informative items
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array(
            'tag' => Menu_Manager_Model_Menu::CACHE_TAG,
            'url' => base64_encode($this->_currentUrlPath),
            'mid' => $this->getMenu()->getId(),
        );
    }
}
