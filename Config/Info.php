<?php
/**
 * SwiftOtter_Base is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SwiftOtter_Base is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with SwiftOtter_Base. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright: 2013 (c) SwiftOtter Studios
 *
 * @author Tyler Schade
 * @copyright Swift Otter Studios, 11/21/16
 * @package default
 **/

namespace SwiftOtter\ShippingSurcharge\Config;

use \Magento\Framework\App\Helper\AbstractHelper;

class Info extends AbstractHelper
{
    const SYSTEM_CONFIG_BASE_PATH = 'shipping_surcharge';

    public function getModuleConfig(string $section, string $field) : string
    {
        return (string) $this->scopeConfig->getValue(
            sprintf('%s/%s/%s', self::SYSTEM_CONFIG_BASE_PATH, $section, $field),
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isFeatureEnabled() : bool
    {
        return (bool) $this->getModuleConfig('general', 'is_enabled');
    }
}