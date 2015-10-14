<?php
/**
 Admin Page Framework v3.5.12 by Michael Uno
 Generated by PHP Class Files Script Generator <https://github.com/michaeluno/PHP-Class-Files-Script-Generator>
 <http://en.michaeluno.jp/admin-page-framework>
 Copyright (c) 2013-2015, Michael Uno; Licensed under MIT <http://opensource.org/licenses/MIT>
 */
class AdminPageFramework_FormTable_Row extends AdminPageFramework_FormTable_Base {
    public function getFieldRows(array $aFields, $hfCallback) {
        if (!is_callable($hfCallback)) {
            return '';
        }
        $_aOutput = array();
        foreach ($aFields as $_aField) {
            $_aOutput[] = $this->_getFieldRow($_aField, $hfCallback);
        }
        return implode(PHP_EOL, $_aOutput);
    }
    private function _getFieldRow(array $aField, $hfCallback) {
        if ('section_title' === $aField['type']) {
            return '';
        }
        $_aFieldFinal = $this->_mergeFieldTypeDefault($aField);
        return $this->_getFieldByContainer($aField, $_aFieldFinal, $hfCallback, array('open_container' => "<tr " . $this->_getFieldContainerAttributes($_aFieldFinal, array('id' => 'fieldrow-' . AdminPageFramework_FormField::_getInputTagBaseID($_aFieldFinal), 'valign' => 'top', 'class' => 'admin-page-framework-fieldrow',), 'fieldrow') . ">", 'close_container' => "</tr>", 'open_title' => "<th>", 'close_title' => "</th>", 'open_main' => "<td " . $this->generateAttributes(array('colspan' => $_aFieldFinal['show_title_column'] ? 1 : 2, 'class' => $_aFieldFinal['show_title_column'] ? null : 'admin-page-framework-field-td-no-title',)) . ">", 'close_main' => "</td>",));
    }
    public function getFields(array $aFields, $hfCallback) {
        if (!is_callable($hfCallback)) {
            return '';
        }
        $_aOutput = array();
        foreach ($aFields as $_aField) {
            $_aOutput[] = $this->_getField($_aField, $hfCallback);
        }
        return implode(PHP_EOL, $_aOutput);
    }
    private function _getField(array $aField, $hfCallback) {
        if ('section_title' === $aField['type']) {
            return '';
        }
        $_aFieldFinal = $this->_mergeFieldTypeDefault($aField);
        return $this->_getFieldByContainer($aField, $_aFieldFinal, $hfCallback, array('open_main' => "<div " . $this->_getFieldContainerAttributes($_aFieldFinal, array(), 'fieldrow') . ">", 'close_main' => "</div>",));
    }
    private function _getFieldByContainer(array $aField, array $aFieldFinal, $hfCallback, array $aOpenCloseTags) {
        $aOpenCloseTags = $aOpenCloseTags + array('open_container' => '', 'close_container' => '', 'open_title' => '', 'close_title' => '', 'open_main' => '', 'close_main' => '',);
        $_aOutput = array();
        if ($aField['show_title_column']) {
            $_aOutput[] = $aOpenCloseTags['open_title'] . $this->_getFieldTitle($aFieldFinal) . $aOpenCloseTags['close_title'];
        }
        $_aOutput[] = $aOpenCloseTags['open_main'] . call_user_func_array($hfCallback, array($aField)) . $aOpenCloseTags['close_main'];
        return $aOpenCloseTags['open_container'] . implode(PHP_EOL, $_aOutput) . $aOpenCloseTags['close_container'];
    }
    private function _mergeFieldTypeDefault(array $aField) {
        return $this->uniteArrays($aField, $this->getElementAsArray($this->aFieldTypeDefinitions, array($aField['type'], 'aDefaultKeys'), array()));
    }
    private function _getFieldTitle(array $aField) {
        return "<label for='" . AdminPageFramework_FormField::_getInputID($aField) . "'>" . "<a id='{$aField['field_id']}'></a>" . "<span title='" . esc_attr(strip_tags(isset($aField['tip']) ? $aField['tip'] : (is_array($aField['description'] ? implode('&#10;', $aField['description']) : $aField['description'])))) . "'>" . $aField['title'] . (in_array($aField['_fields_type'], array('widget', 'post_meta_box', 'page_meta_box')) && isset($aField['title']) && '' !== $aField['title'] ? "<span class='title-colon'>:</span>" : '') . "</span>" . "</label>";
    }
}