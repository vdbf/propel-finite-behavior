<?php namespace Vdbf\Propel\Behaviors\Finite;

use Propel\Generator\Model\Behavior;

class FiniteBehavior extends Behavior
{

    protected $properties = [];

    protected $parameters = [
        'property_path' => null
    ];

    /**
     * Add a state column to the table defined by the property_path parameter
     * Add a state_table if
     * @return void
     */
    public function modifyTable()
    {
        $table = $this->getTable();

        if (is_null($propertyPath = $this->getParameter('property_path'))) {
            throw new \InvalidArgumentException(sprintf(
                'You need to supply a \'property_path\' parameter for table %s',
                $table->getName()
            ));
        }

        if (!$table->hasColumn($propertyPath)) {
            $table->addColumn([
                'name' => $propertyPath,
                'type' => 'BIGINT'
            ]);
        }
    }
}