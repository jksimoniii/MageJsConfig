<?php

class BlueAcorn_MageJsConfig_Model_Container_MageJsConfig extends Enterprise_PageCache_Model_Container_Abstract {
    protected function _getCacheId() {
        return 'MAGEJSCONFIG_' . md5($_COOKIE['frontend']);
    }

    public function applyInApp(&$content)
    {
        $block = $this->_placeholder->getAttribute('block');
        $template = $this->_placeholder->getAttribute('template');

        $block = new $block;
        $block->setTemplate($template);
        $block->setLayout(Mage::app()->getLayout());

        $blockContent = $block->toHtml();

        $this->_applyToContent($content, $blockContent);

        return true;
    }

    protected function _saveCache($data, $id, $tags = array(), $lifetime = null)
    {
        return false;
    }
}