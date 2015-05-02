<?php
class BlueAcorn_MageJsConfig_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getConfig() {
        return json_encode(
            array_merge(
                $this->getSystemValues(),
                $this->getHelperValues()
            )
        );
    }

    protected function getSystemValues() {
        $config = array();
        if (Mage::getConfig()->getNode('jsconfig/system')) {
            // Loop through namespaces defined in frontend/jsconfig node
            foreach(Mage::getConfig()->getNode('jsconfig/system')->asArray() as $namespace=>$groups) {
                // Loop through groups defined as part of namespace node
                foreach($groups as $group=>$fields) {
                    // Loop through fields defined as part of group node
                    foreach($fields as $field=>$value) {
                        // Build a config path variable
                        $path = $namespace . "/" . $group . "/" . $field;

                        // Grab the value from magento configuration
                        $configValue = Mage::getStoreConfig($path);

                        // Check if name attribute exists as part of field node
                        // and use the attribute as the key for config array.
                        // Otherwise, just use the path as the key
                        if (!empty($value['@']['name'])) {
                            $key = $value['@']['name'];
                        } else {
                            $key = $path;
                        }

                        // Store the config value in array
                        $config[$key] = $configValue;
                    }
                }
            }
        }

        // Always include base_url as part of return values
        $config['base_url'] = Mage::getBaseUrl();

        return $config;
    }

    protected function getHelperValues() {
        $config = array();

        if (Mage::getConfig()->getNode('jsconfig/helpers')) {
            foreach(Mage::getConfig()->getNode('jsconfig/helpers')->asArray() as $key=>$children) {
                $method = null;
                $class = null;
                foreach ($children as $node=>$value) {
                    switch ($node) {
                        case 'method':
                            $method = $value;
                            break;
                        case 'class':
                            $class = $value;
                            break;
                    }
                }

                // Check if name attribute exists as part of field node
                // and use the attribute as the key for config array.
                // Otherwise, just use the path as the key
                if (!empty($children['@']['name'])) {
                    $key = $children['@']['name'];
                }

                if ($method && $class) {
                    $class = new $class();
                    $config[$key] = $class->$method();
                }
            }
        }

        return $config;
    }
}
