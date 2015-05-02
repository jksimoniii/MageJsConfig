# MageJsConfig

MageJsConfig is a module developed for Magento Community and Enterprise editions that allows developers to make entries
of core_config_data available for usage on the frontend via a javascript object named *mageConfig*.

## Usage
*See below for an example.*  This module introduces an additional node as a child to the ```<config>``` node known as the ```<jsconfig>``` node.
Within the ```<jsconfig>``` node there are two methods of populating the resulting *mageConfig* object with data.
  * ```<system>```: The following 3 nested nodes define the config path to populate the resulting *mageConfig* object.
  * ```<helpers>```: We can optionally use this node to define a class and method to populate the resulting *mageConfig* object.

Lastly, we have further control of the data in *mageConfig* by specifying a *name* attribute

```xml
<jsconfig>
    <system>
        <checkout>
            <cart>
                <redirect_to_cart name="cart_redirect" />
            </cart>
        </checkout>
    </system>
    <helpers>
        <demo_message name="demo_message">
            <class>BlueAcorn_MageJsConfig_Helper_Config</class>
            <method>sayHello</method>
        </demo_message>
    </helpers>
</jsconfig>
```