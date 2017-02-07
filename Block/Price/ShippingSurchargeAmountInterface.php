<?php
/**
 * @by SwiftOtter, Inc., 2/2/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Price;

interface ShippingSurchargeAmountInterface
{
    public function getSurcharge(): string;
    public function getSurchargeLabel(): string;
}