<?php
/**
 Admin Page Framework v3.5.12 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
abstract class AdminPageFramework_Page_Controller extends AdminPageFramework_Page_View {
    public function addInPageTabs() {
        foreach (func_get_args() as $asTab) {
            $this->addInPageTab($asTab);
        }
    }
    public function addInPageTab($asInPageTab) {
        static $__sTargetPageSlug;
        if (!is_array($asInPageTab)) {
            $__sTargetPageSlug = is_string($asInPageTab) ? $asInPageTab : $__sTargetPageSlug;
            return;
        }
        $aInPageTab = $this->oUtil->uniteArrays($asInPageTab, self::$_aStructure_InPageTabElements, array('page_slug' => $__sTargetPageSlug));
        $__sTargetPageSlug = $aInPageTab['page_slug'];
        if (!isset($aInPageTab['page_slug'], $aInPageTab['tab_slug'])) {
            return;
        }
        $iCountElement = isset($this->oProp->aInPageTabs[$aInPageTab['page_slug']]) ? count($this->oProp->aInPageTabs[$aInPageTab['page_slug']]) : 0;
        $aInPageTab = array('page_slug' => $this->oUtil->sanitizeSlug($aInPageTab['page_slug']), 'tab_slug' => $this->oUtil->sanitizeSlug($aInPageTab['tab_slug']), 'order' => is_numeric($aInPageTab['order']) ? $aInPageTab['order'] : $iCountElement + 10,) + $aInPageTab;
        $this->oProp->aInPageTabs[$aInPageTab['page_slug']][$aInPageTab['tab_slug']] = $aInPageTab;
    }
    public function setPageTitleVisibility($bShow = true, $sPageSlug = '') {
        $sPageSlug = $this->oUtil->sanitizeSlug($sPageSlug);
        if ($sPageSlug) {
            $this->oProp->aPages[$sPageSlug]['show_page_title'] = $bShow;
            return;
        }
        $this->oProp->bShowPageTitle = $bShow;
        foreach ($this->oProp->aPages as & $aPage) {
            $aPage['show_page_title'] = $bShow;
        }
    }
    public function setPageHeadingTabsVisibility($bShow = true, $sPageSlug = '') {
        $sPageSlug = $this->oUtil->sanitizeSlug($sPageSlug);
        if ($sPageSlug) {
            $this->oProp->aPages[$sPageSlug]['show_page_heading_tabs'] = $bShow;
            return;
        }
        $this->oProp->bShowPageHeadingTabs = $bShow;
        foreach ($this->oProp->aPages as & $aPage) {
            $aPage['show_page_heading_tabs'] = $bShow;
        }
    }
    public function setInPageTabsVisibility($bShow = true, $sPageSlug = '') {
        $sPageSlug = $this->oUtil->sanitizeSlug($sPageSlug);
        if ($sPageSlug) {
            $this->oProp->aPages[$sPageSlug]['show_in_page_tabs'] = $bShow;
            return;
        }
        $this->oProp->bShowInPageTabs = $bShow;
        foreach ($this->oProp->aPages as & $aPage) {
            $aPage['show_in_page_tabs'] = $bShow;
        }
    }
    public function setInPageTabTag($sTag = 'h3', $sPageSlug = '') {
        $sPageSlug = $this->oUtil->sanitizeSlug($sPageSlug);
        if ($sPageSlug) {
            $this->oProp->aPages[$sPageSlug]['in_page_tab_tag'] = $sTag;
            return;
        }
        $this->oProp->sInPageTabTag = $sTag;
        foreach ($this->oProp->aPages as & $aPage) {
            $aPage['in_page_tab_tag'] = $sTag;
        }
    }
    public function setPageHeadingTabTag($sTag = 'h2', $sPageSlug = '') {
        $sPageSlug = $this->oUtil->sanitizeSlug($sPageSlug);
        if ($sPageSlug) {
            $this->oProp->aPages[$sPageSlug]['page_heading_tab_tag'] = $sTag;
            return;
        }
        $this->oProp->sPageHeadingTabTag = $sTag;
        foreach ($this->oProp->aPages as & $aPage) {
            $aPage[$sPageSlug]['page_heading_tab_tag'] = $sTag;
        }
    }
}