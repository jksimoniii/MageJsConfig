<?php
class BlueAcorn_MageJsConfig_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getConfig() {
        return json_encode(
            array_merge(
                $this->getMageConfig(),
                $this->getCustomValues()
            )
        );
    }

    protected function getMageConfig() {
        $config = array();
        if (Mage::getConfig()->getNode('frontend/jsconfig')) {
            // Loop through namespaces defined in frontend/jsconfig node
            foreach(Mage::getConfig()->getNode('frontend/jsconfig')->asArray() as $namespace=>$groups) {
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

                        // Check if type attribute exists as part of field node
                        // and use the attribute to convert into correct data type
                        if(!empty($value['@']['type'])) {
                            $filter = 'FILTER_VALIDATE_' . strtoupper($value['@']['type']);
                            $val = constant($filter);
                            $configValue = filter_var($configValue, $val);
                        }

                        // Store the config value in array
                        $config[$key] = $configValue;
                    }
                }
            }
        }

        return $config;
    }

    protected function getCustomValues() {
        $config = array();

        if (Mage::getConfig()->getNode('jsconfig')) {
            foreach(Mage::getConfig()->getNode('jsconfig')->asArray() as $namespace=>$children) {
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

                if ($method && $class) {
                    $class = new $class();
                    $config[$namespace] = $class->$method();
                }
            }
        }
        $config['base_url'] = Mage::getBaseUrl();

        return $config;
    }
}
