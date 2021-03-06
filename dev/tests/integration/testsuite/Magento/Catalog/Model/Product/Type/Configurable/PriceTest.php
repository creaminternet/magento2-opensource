<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Catalog\Model\Product\Type\Configurable;

class PriceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @magentoDataFixture Magento/Catalog/_files/product_configurable.php
     */
    public function testGetFinalPrice()
    {
        /** @var $product \Magento\Catalog\Model\Product */
        $product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Catalog\Model\Product');
        $product->load(1); // fixture

        /** @var $model \Magento\Catalog\Model\Product\Type\Configurable\Price */
        $model = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Catalog\Model\Product\Type\Configurable\Price');

        // without configurable options
        $this->assertEquals(100.0, $model->getFinalPrice(1, $product));

        // with configurable options
        $attributes = $product->getTypeInstance()->getConfigurableAttributes($product);
        foreach ($attributes as $attribute) {
            $prices = $attribute->getPrices();
            $product->addCustomOption(
                'attributes',
                serialize(array($attribute->getProductAttribute()->getId() => $prices[0]['value_index']))
            );
            break;
        }
        $this->assertEquals(105.0, $model->getFinalPrice(1, $product));
    }
}
