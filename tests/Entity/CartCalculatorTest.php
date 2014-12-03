<?php
namespace inklabs\kommerce\Entity;

use inklabs\kommerce\Service as Service;

class CartCalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTotal()
    {
        $product1 = new Product;
        $product1->setUnitPrice(500);

        $product2 = new Product;
        $product2->setUnitPrice(300);

        $cart = new Cart;
        $cart->addItem($product1, 2);
        $cart->addItem($product2, 1);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1300;
        $expectedCartTotal->subtotal = 1300;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 0;
        $expectedCartTotal->total = 1300;
        $expectedCartTotal->savings = 0;

        $cartCalculator = new CartCalculator($cart);
        $this->assertEquals($expectedCartTotal, $cartCalculator->getTotal(new Service\Pricing));
    }

    public function testGetTotalWithCartPriceRules()
    {
        $productShirt = new Product;
        $productShirt->setId(1);
        $productShirt->setUnitPrice(1200);

        $productPoster = new Product;
        $productPoster->setId(2);
        $productPoster->setUnitPrice(500);

        $cartPriceRule = new CartPriceRule;
        $cartPriceRule->setName('Buy a Shirt get a FREE poster');
        $cartPriceRule->addItem(new CartPriceRule\Product($productShirt, 1));
        $cartPriceRule->addItem(new CartPriceRule\Product($productPoster, 1));
        $cartPriceRule->addDiscount(new CartPriceRuleDiscount($productPoster, 1));

        $cart = new Cart;
        $cart->addCartPriceRule($cartPriceRule);
        $cart->addItem($productShirt, 1);
        $cart->addItem($productPoster, 1);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1700;
        $expectedCartTotal->subtotal = 1200;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 0;
        $expectedCartTotal->total = 1200;
        $expectedCartTotal->savings = 500;
        $expectedCartTotal->cartPriceRules = [$cartPriceRule];

        $cartCalculator = new CartCalculator($cart);
        $this->assertEquals($expectedCartTotal, $cartCalculator->getTotal(new Service\Pricing));
    }

    public function testGetTotalCartPriceRuleInvalidCartItems()
    {
        $productShirt = new Product;
        $productShirt->setId(1);
        $productShirt->setUnitPrice(1200);

        $productPoster = new Product;
        $productPoster->setId(2);
        $productPoster->setUnitPrice(500);

        $productJacket = new Product;
        $productJacket->setId(3);
        $productJacket->setUnitPrice(2500);

        $cartPriceRule = new CartPriceRule;
        $cartPriceRule->setName('Buy a Shirt get a FREE poster');
        $cartPriceRule->addItem(new CartPriceRule\Product($productShirt, 1));
        $cartPriceRule->addItem(new CartPriceRule\Product($productPoster, 1));
        $cartPriceRule->addDiscount(new CartPriceRuleDiscount($productPoster, 1));

        $cart = new Cart;
        $cart->addCartPriceRule($cartPriceRule);
        $cart->addItem($productShirt, 1);
        $cart->addItem($productJacket, 1);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 3700;
        $expectedCartTotal->subtotal = 3700;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 0;
        $expectedCartTotal->total = 3700;
        $expectedCartTotal->savings = 0;
        $expectedCartTotal->cartPriceRules = [];

        $cartCalculator = new CartCalculator($cart);
        $this->assertEquals($expectedCartTotal, $cartCalculator->getTotal(new Service\Pricing));
    }

    public function testGetTotalWithCartPriceRulesAndReducesTaxSubtotal()
    {
        $productShirt = new Product;
        $productShirt->setSku('TS-NAVY-LG');
        $productShirt->setName('Navy T-shirt (large)');
        $productShirt->setUnitPrice(1200);
        $productShirt->setIsTaxable(true);

        $productPoster = new Product;
        $productPoster->setSku('PST-CKN');
        $productPoster->setName('Citizen Kane (1941) Poster');
        $productPoster->setUnitPrice(500);
        $productPoster->setIsTaxable(true);

        $cartPriceRule = new CartPriceRule;
        $cartPriceRule->setName('Buy a Shirt get a FREE poster');
        $cartPriceRule->addItem(new CartPriceRule\Product($productShirt, 1));
        $cartPriceRule->addItem(new CartPriceRule\Product($productPoster, 1));
        $cartPriceRule->addDiscount(new CartPriceRuleDiscount($productPoster, 1));
        $cartPriceRule->setReducesTaxSubtotal(true);

        $cart = new Cart;
        $cart->addCartPriceRule($cartPriceRule);
        $cart->addItem($productShirt, 1);
        $cart->addItem($productPoster, 1);

        $taxRate = new TaxRate;
        $taxRate->setZip5(92606);
        $taxRate->setRate(8.0);
        $taxRate->setApplyToShipping(false);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1700;
        $expectedCartTotal->subtotal = 1200;
        $expectedCartTotal->taxSubtotal = 1200;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 96;
        $expectedCartTotal->total = 1296;
        $expectedCartTotal->savings = 500;
        $expectedCartTotal->cartPriceRules = [$cartPriceRule];
        $expectedCartTotal->taxRate = $taxRate;

        $shippingRate = null;
        $cartCalculator = new CartCalculator($cart);
        $cartTotal = $cartCalculator->getTotal(new Service\Pricing, $shippingRate, $taxRate);
        $this->assertEquals($expectedCartTotal, $cartTotal);
    }

    public function testGetTotalWithCoupon()
    {
        $product = new Product;
        $product->setUnitPrice(500);

        $coupon = new Coupon;
        $coupon->setid(1);
        $coupon->setName('20% Off');
        $coupon->setType('percent');
        $coupon->setValue(20);

        $cart = new Cart;
        $cart->addCoupon($coupon);
        $cart->addItem($product, 5);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 2500;
        $expectedCartTotal->subtotal = 2500;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 500;
        $expectedCartTotal->tax = 0;
        $expectedCartTotal->total = 2000;
        $expectedCartTotal->savings = 500;
        $expectedCartTotal->coupons = [$coupon];

        $cartCalculator = new CartCalculator($cart);
        $this->assertEquals($expectedCartTotal, $cartCalculator->getTotal(new Service\Pricing));
    }

    public function testGetTotalWithCouponWithFreeShipping()
    {
        $coupon = new Coupon;
        $coupon->setid(1);
        $coupon->setName('20% Off');
        $coupon->setType('percent');
        $coupon->setValue(20);
        $coupon->setFlagFreeShipping(true);

        $product = new Product;
        $product->setUnitPrice(500);

        $cart = new Cart;
        $cart->addCoupon($coupon);
        $cart->addItem($product, 2);

        $shippingRate = new Shipping\Rate;
        $shippingRate->code = '4';
        $shippingRate->name = 'Parcel Post';
        $shippingRate->cost = 1000;

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1000;
        $expectedCartTotal->subtotal = 1000;
        $expectedCartTotal->shipping = 1000;
        $expectedCartTotal->shippingDiscount = 1000;
        $expectedCartTotal->discount = 1200;
        $expectedCartTotal->tax = 0;
        $expectedCartTotal->total = 800;
        $expectedCartTotal->savings = 1200;
        $expectedCartTotal->coupons = [$coupon];

        $cartCalculator = new CartCalculator($cart);
        $this->assertEquals($expectedCartTotal, $cartCalculator->getTotal(new Service\Pricing, $shippingRate));
    }

    public function testGetTotalWithShipping()
    {
        $product = new Product;
        $product->setUnitPrice(500);

        $shippingRate = new Shipping\Rate;
        $shippingRate->code = '4';
        $shippingRate->name = 'Parcel Post';
        $shippingRate->cost = 1000;

        $cart = new Cart;
        $cart->addItem($product, 3);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1500;
        $expectedCartTotal->subtotal = 1500;
        $expectedCartTotal->shipping = 1000;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 0;
        $expectedCartTotal->total = 2500;
        $expectedCartTotal->savings = 0;

        $cartCalculator = new CartCalculator($cart);
        $this->assertEquals($expectedCartTotal, $cartCalculator->getTotal(new Service\Pricing, $shippingRate));
    }

    public function testGetTotalWithZip5TaxNotAppliedToShipping()
    {
        $product = new Product;
        $product->setUnitPrice(500);
        $product->setIsTaxable(true);

        $taxRate = new TaxRate;
        $taxRate->setZip5(92606);
        $taxRate->setRate(8.0);
        $taxRate->setApplyToShipping(false);

        $cart = new Cart;
        $cart->addItem($product, 2);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1000;
        $expectedCartTotal->subtotal = 1000;
        $expectedCartTotal->taxSubtotal = 1000;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 80;
        $expectedCartTotal->total = 1080;
        $expectedCartTotal->savings = 0;
        $expectedCartTotal->taxRate = $taxRate;

        $shippingRate = null;
        $cartCalculator = new CartCalculator($cart);
        $cartTotal = $cartCalculator->getTotal(new Service\Pricing, $shippingRate, $taxRate);
        $this->assertEquals($expectedCartTotal, $cartTotal);
    }

    public function testGetTotalWithZip5TaxAppliedToShipping()
    {
        $product = new Product;
        $product->setUnitPrice(500);
        $product->setIsTaxable(true);

        $taxRate = new TaxRate;
        $taxRate->setZip5(92606);
        $taxRate->setRate(8.0);
        $taxRate->setApplyToShipping(true);

        $shippingRate = new Shipping\Rate;
        $shippingRate->code = '4';
        $shippingRate->name = 'Parcel Post';
        $shippingRate->cost = 1000;

        $cart = new Cart;
        $cart->addItem($product, 2);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1000;
        $expectedCartTotal->subtotal = 1000;
        $expectedCartTotal->taxSubtotal = 1000;
        $expectedCartTotal->shipping = 1000;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 160;
        $expectedCartTotal->total = 2160;
        $expectedCartTotal->savings = 0;
        $expectedCartTotal->taxRate = $taxRate;

        $cartCalculator = new CartCalculator($cart);
        $cartTotal = $cartCalculator->getTotal(new Service\Pricing, $shippingRate, $taxRate);
        $this->assertEquals($expectedCartTotal, $cartTotal);

    }

    public function testGetTotalWithZip5TaxAndProductNotTaxable()
    {
        $product = new Product;
        $product->setUnitPrice(500);
        $product->setIsTaxable(false);

        $taxRate = new TaxRate;
        $taxRate->setZip5(92606);
        $taxRate->setRate(8.0);
        $taxRate->setApplyToShipping(false);

        $cart = new Cart;
        $cart->addItem($product, 2);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 1000;
        $expectedCartTotal->subtotal = 1000;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 0;
        $expectedCartTotal->tax = 0;
        $expectedCartTotal->total = 1000;
        $expectedCartTotal->savings = 0;

        $cartCalculator = new CartCalculator($cart);
        $this->assertEquals($expectedCartTotal, $cartCalculator->getTotal(new Service\Pricing));
    }

    public function testGetTotalWithZip5TaxAndCouponNoReduceSubtotal()
    {
        $product = new Product;
        $product->setUnitPrice(2000);
        $product->setIsTaxable(true);

        $taxRate = new TaxRate;
        $taxRate->setZip5(92606);
        $taxRate->setRate(8.0);
        $taxRate->setApplyToShipping(false);

        $coupon = new Coupon;
        $coupon->setName('20% Off orders under $100');
        $coupon->setType('percent');
        $coupon->setValue(20);
        $coupon->setMinOrderValue(1000);
        $coupon->setMaxOrderValue(10000);
        $coupon->setReducesTaxSubtotal(false);

        $cart = new Cart;
        $cart->addCoupon($coupon);
        $cart->addItem($product, 1);

        $expectedCartTotal = new CartTotal;
        $expectedCartTotal->origSubtotal = 2000;
        $expectedCartTotal->subtotal = 2000;
        $expectedCartTotal->taxSubtotal = 2000;
        $expectedCartTotal->shipping = 0;
        $expectedCartTotal->discount = 400;
        $expectedCartTotal->tax = 160;
        $expectedCartTotal->total = 1760;
        $expectedCartTotal->savings = 400;
        $expectedCartTotal->coupons = [$coupon];
        $expectedCartTotal->taxRate = $taxRate;

        $shippingRate = null;
        $cartCalculator = new CartCalculator($cart);
        $cartTotal = $cartCalculator->getTotal(new Service\Pricing, $shippingRate, $taxRate);
        $this->assertEquals($expectedCartTotal, $cartTotal);
    }
}