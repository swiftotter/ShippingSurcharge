<?php
/**
 * @by SwiftOtter, Inc. 2/28/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Setup\Definition;


class ExplanatoryNoteStaticBlock
{
    const ID = 'surcharge_explanatory_note';

    private $contents = <<<EOD
<p>An additional shipping charge is required due to the product's size or weight, or because it requires additional packaging.</p>
EOD;

    public function getData()
    {
        return [
            'title' => 'Surcharge Note',
            'identifier' => self::ID,
            'stores' => [0],
            'is_active' => 1,
            'content' => $this->contents
        ];
    }
}