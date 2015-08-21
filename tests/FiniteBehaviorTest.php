<?php

class FiniteBehaviorTest extends \PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $container = \Propel\Runtime\Propel::getServiceContainer();
        $container->setAdapterClass('finite-test', 'sqlite');

        $connectionManager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
        $connectionManager->setConfiguration([
            'dsn' => '/tmp/test.db',
            'classname' => '\\Propel\\Runtime\\Connection\\ConnectionWrapper'
        ]);

        $connectionManager->setName('finite-test');

        $container->setConnectionManager('finite-test', $connectionManager);
    }

    protected function buildInvalidSchema()
    {
        $schemaXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<database name="finite-test">
  <table name="documents">
    <column name="id" primaryKey="true" autoIncrement="true" type="BIGINT"/>
    <column name="path" type="VARCHAR"/>
    <column name="status" type="VARCHAR"/>
    <behavior name="Vdbf\Propel\Behaviors\Finite\FiniteBehavior">
        <parameter name="state_table" value="complex_state" />
    </behavior>
  </table>
</database>
EOT;

        return $this->buildSchemaFromXml($schemaXml);
    }

    protected function buildComplexSchema()
    {
        $schemaXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<database name="finite-test">
  <table name="documents">
    <column name="id" primaryKey="true" autoIncrement="true" type="BIGINT"/>
    <column name="path" type="VARCHAR"/>
    <column name="status" type="VARCHAR"/>
    <behavior name="Vdbf\Propel\Behaviors\Finite\FiniteBehavior">
        <parameter name="property_path" value="complexState" />
        <parameter name="state_table" value="complex_state" />
    </behavior>
  </table>
</database>
EOT;

        return $this->buildSchemaFromXml($schemaXml);
    }

    protected function buildSimpleSchema()
    {
        $schemaXml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<database name="finite-test">
  <table name="documents">
    <column name="id" primaryKey="true" autoIncrement="true" type="BIGINT"/>
    <column name="path" type="VARCHAR"/>
    <column name="status" type="VARCHAR"/>
    <behavior name="Vdbf\Propel\Behaviors\Finite\FiniteBehavior">
        <parameter name="property_path" value="state" />
    </behavior>
  </table>
</database>
EOT;

        return $this->buildSchemaFromXml($schemaXml);
    }

    protected function buildSchemaFromXml($schemaXml)
    {
        $builder = new \Propel\Generator\Builder\Util\SchemaReader(
            new \Propel\Generator\Platform\SqlitePlatform()
        );

        return $builder->parseString($schemaXml);
    }

    protected function validateSchema(\Propel\Generator\Model\Schema $schema)
    {
        $validator = new \Propel\Generator\Util\SchemaValidator($schema);

        $this->assertTrue($validator->validate());
    }

    public function test_building_schema()
    {
        $simpleSchema = $this->buildSimpleSchema();

        $this->validateSchema($simpleSchema);

        $complexSchema = $this->buildComplexSchema();

        $this->validateSchema($complexSchema);

        $this->setExpectedException('InvalidArgumentException');
        $invalidSchema = $this->buildInvalidSchema();
        $this->validateSchema($invalidSchema);
    }

}