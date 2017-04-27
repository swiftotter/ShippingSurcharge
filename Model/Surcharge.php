<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model;


class Surcharge
{
    const SURCHARGE = 'shipping_surcharge';
    const BASE_SURCHARGE = 'base_shipping_surcharge';

    const SURCHARGE_REFUNDED = 'shipping_surcharge_refunded';
    const BASE_SURCHARGE_REFUNDED = 'base_shipping_surcharge_refunded';

    const SURCHARGE_REQUESTED_REFUND = 'shipping_surcharge_refund_request';
}