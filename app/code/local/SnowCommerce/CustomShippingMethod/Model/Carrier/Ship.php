<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Admin
 * Date: 22.07.13
 * Time: 14:58
 * To change this template use File | Settings | File Templates.
 */
class SnowCommerce_CustomShippingMethod_Model_Carrier_Ship extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface //CustomShippingMethod - имя метода
{
    protected $_code = 'sc_custom_shipping';        //имя метода

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if(!Mage::getStoreConfig('carriers/'.$this->_code.'/active'))
        {
            return false;
        }
        /*
        $price = $this->getConfigData('price');

        //далее разные способы рассчета стоимости доставки

         //стоимость доставки зависит от пункта назначения
         echo $destCountry = $request->getDestCountryId().': Dest Country<br/>';
         echo $destRegion = $request->getDestRegionId().': Dest Region<br/>';
         echo $destRegionCode = $request->getDestRegionCode().': Dest Region Code<br/>';
         print_r($destStreet = $request->getDestStreet()); echo ': Dest Street<br/>';
         echo $destCity = $request->getDestCity().': Dest City<br/>';
         echo $destPostcode = $request->getDestPostcode().': Dest Postcode<br/>';
         echo $country_id = $request->getCountryId().': Package Source Country ID<br/>';
         echo $region_id = $request->getRegionId().': Package Source Region ID<br/>';
         echo $city = $request->getCity().': Package Source City<br/>';
         echo $postcode = $request->getPostcode().': Package Source Post Code<br/>';

         //стоимость доставки доставки зависит от веса и количества итемов
         echo $packageValue = $request->getPackageValue().': Dest Package Value<br/>';
         echo $packageValueDiscout = $request->getPackageValueWithDiscount().': Dest Package Value After Discount<br/>';
         echo $packageWeight = $request->getPackageWeight().': Package Weight<br/>';
         echo $packageQty = $request->getPackageQty().': Package Quantity <br/>';
         echo $packageCurrency = $request->getPackageCurrency().': Package Currency <br/>';

         //стоимость доставки зависит от размеров посылки
         echo $packageheight = $request->getPackageHeight() .': Package height <br/>';
         echo $request->getPackageWeight().': Package Width <br/>';
         echo $request->getPackageDepth().': Package Depth <br/>';



        //стоимость доставки зависит от аттрибутов
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $product_id = $child->getProductId();
                            $productObj = Mage::getModel('catalog/product')->load($product_id);
                            $ship_price = $productObj->getData('shipping_price'); //our shipping attribute code
                            $price += (float)$ship_price;
                        }
                    }
                } else {
                    $product_id = $item->getProductId();
                    $productObj = Mage::getModel('catalog/product')->load($product_id);
                    $ship_price = $productObj->getData('shipping_price'); //our shipping attribute code
                    $price += (float)$ship_price;
                }
            }
        }

        //стоимость доставки зависит от конфигурации опций товара
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $product_id = $child->getProductId();
                            $value = $item->getOptionByCode('info_buyRequest')->getValue();
                            $params = unserialize($value);
                            $attributeObj = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY,'shirt_size'); //конфигурируемый аттрибут, от которого все и зависит
                            $attribute_id = $attributeObj->getAttributeId();
                            $attribute_selected = $params['super_attribute'][$attribute_id];

                            $label = '';
                            foreach($attributeObj->getSource()->getAllOptions(false) as $option){
                                if($option['value'] == $attribute_selected){
                                    $label =  $option['label'];
                                }
                            }
                            if($label = 'Small'){
                                $price += 15;
                            } else if($label = 'Medium'){
                                $price += 20;
                            } else if($label = 'Large'){
                                $price += 22;
                            }
                        }
                    }
                } else {
                    $product_id = $item->getProductId();
                    $value = $item->getOptionByCode('info_buyRequest')->getValue();
                    $params = unserialize($value);
                    $attributeObj = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY,'shirt_size'); //конфигурируемый аттрибут, от которого все и зависит
                    $attribute_id = $attributeObj->getAttributeId();
                    $attribute_selected = $params['super_attribute'][$attribute_id];

                    $label = '';
                    foreach($attributeObj->getSource()->getAllOptions(false) as $option){
                        if($option['value'] == $attribute_selected){
                            $label =  $option['label'];
                        }
                    }
                    if($label = 'Small'){
                        $price += 15;
                    } else if($label = 'Medium'){
                        $price += 20;
                    } else if($label = 'Large'){
                        $price += 22;
                    }
                }
            }
        }


        //стоимость доставки зависит от кастомных опций
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }
                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    foreach ($item->getChildren() as $child) {
                        if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                            $product_id = $child->getProductId();
                            $value = $item->getOptionByCode('info_buyRequest')->getValue();
                            $params = unserialize($value);
                            $options_select = $params['options'];

                            $product = Mage::getModel('catalog/product')->load($product_id);
                            $options = $product->getOptions();
                            foreach ($options as $option) {
                                if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
                                    $option_id =  $option->getId();
                                    foreach ($option->getValues() as $value) {
                                        if($value->getId() == $options_select[$option_id]){
                                            if($value->getTitle() == 'Express'){
                                                $price += 50;
                                            }else if($value->getTitle() == 'Normal'){
                                                $price += 10;
                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }
                } else {
                    $product_id = $item->getProductId();
                    $value = $item->getOptionByCode('info_buyRequest')->getValue();
                    $params = unserialize($value);
                    $options_select = $params['options'];

                    $product = Mage::getModel('catalog/product')->load($product_id);
                    $options = $product->getOptions();
                    foreach ($options as $option) {
                        if ($option->getGroupByType() == Mage_Catalog_Model_Product_Option::OPTION_GROUP_SELECT) {
                            $option_id =  $option->getId();
                            foreach ($option->getValues() as $value) {
                                if($value->getId() == $options_select[$option_id]){
                                    if($value->getTitle() == 'Express'){
                                        $price += 50;
                                    }else if($value->getTitle() == 'Normal'){
                                        $price += 10;
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
        */

        $handling = Mage::getStoreConfig('carriers/'.$this->_code.'/handling');
        $result = Mage::getModel('shipping/rate_result');
        $show = true;
        if($show)
        {

            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier($this->_code);
            $method->setMethod($this->_code);
            $method->setCarrierTitle($this->getConfigData('title'));
            $method->setMethodTitle($this->getConfigData('name'));
            $method->setPrice($this->getConfigData('price'));
            $method->setCost($this->getConfigData('price'));
            $result->append($method);

        }
        else
        {
            $error = Mage::getModel('shipping/rate_result_error');
            $error->setCarrier($this->_code);
            $error->setCarrierTitle($this->getConfigData('name'));
            $error->setErrorMessage($this->getConfigData('specificerrmsg'));
            $result->append($error);
        }

        return $result;
    }

    public function getAllowedMethods()
    {
        return array('excellence'=>$this->getConfigData('name'));
    }
}