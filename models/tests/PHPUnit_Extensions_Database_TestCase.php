<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2002-2014, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    DbUnit
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2002-2014 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 1.0.0
 */

/**
 * A TestCase extension that provides functionality for testing and asserting
 * against a real database.
 *
 * @package    DbUnit
 * @author     Mike Lively <m@digitalsandwich.com>
 * @copyright  2010-2014 Mike Lively <m@digitalsandwich.com>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.0.0
 */
abstract class PHPUnit_Extensions_Database_TestCase extends PHPUnit_Framework_TestCase
{

    /**
     * @var PHPUnit_Extensions_Database_ITester
     */
    protected $databaseTester;

    /**
     * Closes the specified connection.
     *
     * @param PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection
     */
    protected function closeConnection(PHPUnit_Extensions_Database_DB_IDatabaseConnection $connection)
    {
        $this->getDatabaseTester()->closeConnection($connection);
    }

    /**
     * Returns the test database connection.
     *
     * @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
     */
    protected abstract function getConnection();

    /**
     * Gets the IDatabaseTester for this testCase. If the IDatabaseTester is
     * not set yet, this method calls newDatabaseTester() to obtain a new
     * instance.
     *
     * @return PHPUnit_Extensions_Database_ITester
     */
    protected function getDatabaseTester()
    {
        if (empty($this->databaseTester)) {
            $this->databaseTester = $this->newDatabaseTester();
        }

        return $this->databaseTester;
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected abstract function getDataSet();

    /**
     * Returns the database operation executed in test setup.
     *
     * @return PHPUnit_Extensions_Database_Operation_DatabaseOperation
     */
    protected function getSetUpOperation()
    {
        return PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT();
    }

    /**
     * Returns the database operation executed in test cleanup.
     *
     * @return PHPUnit_Extensions_Database_Operation_DatabaseOperation
     */
    protected function getTearDownOperation()
    {
        return PHPUnit_Extensions_Database_Operation_Factory::NONE();
    }

    /**
     * Creates a IDatabaseTester for this testCase.
     *
     * @return PHPUnit_Extensions_Database_ITester
     */
    protected function newDatabaseTester()
    {
        return new PHPUnit_Extensions_Database_DefaultTester($this->getConnection());
    }

    /**
     * Creates a new DefaultDatabaseConnection using the given PDO connection
     * and database schema name.
     *
     * @param PDO $connection
     * @param string $schema
     * @return PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection
     */
    protected function createDefaultDBConnection(PDO $connection, $schema = '')
    {
        return new PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection($connection, $schema);
    }

    /**
     * Creates a new FlatXmlDataSet with the given $xmlFile. (absolute path.)
     *
     * @param string $xmlFile
     * @return PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet
     */
    protected function createFlatXMLDataSet($xmlFile)
    {
        return new PHPUnit_Extensions_Database_DataSet_FlatXmlDataSet($xmlFile);
    }

    /**
     * Creates a new XMLDataSet with the given $xmlFile. (absolute path.)
     *
     * @param string $xmlFile
     * @return PHPUnit_Extensions_Database_DataSet_XmlDataSet
     */
    protected function createXMLDataSet($xmlFile)
    {
        return new PHPUnit_Extensions_Database_DataSet_XmlDataSet($xmlFile);
    }

    /**
     * Create a a new MysqlXmlDataSet with the given $xmlFile. (absolute path.)
     *
     * @param string $xmlFile
     * @return PHPUnit_Extensions_Database_DataSet_MysqlXmlDataSet
     * @since  Method available since Release 1.0.0
     */
    protected function createMySQLXMLDataSet($xmlFile)
    {
        return new PHPUnit_Extensions_Database_DataSet_MysqlXmlDataSet($xmlFile);
    }

    /**
     * Returns an operation factory instance that can be used to instantiate
     * new operations.
     *
     * @return PHPUnit_Extensions_Database_Operation_Factory
     */
    protected function getOperations()
    {
        return new PHPUnit_Extensions_Database_Operation_Factory();
    }

    /**
     * Performs operation returned by getSetUpOperation().
     */
    protected function setUp()
    {
        parent::setUp();

        $this->databaseTester = NULL;

        $this->getDatabaseTester()->setSetUpOperation($this->getSetUpOperation());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onSetUp();
    }

    /**
     * Performs operation returned by getSetUpOperation().
     */
    protected function tearDown()
    {
        $this->getDatabaseTester()->setTearDownOperation($this->getTearDownOperation());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onTearDown();

        /**
         * Destroy the tester after the test is run to keep DB connections
         * from piling up.
         */
        $this->databaseTester = NULL;
    }

    /**
     * Asserts that two given tables are equal.
     *
     * @param PHPUnit_Extensions_Database_DataSet_ITable $expected
     * @param PHPUnit_Extensions_Database_DataSet_ITable $actual
     * @param string $message
     */
    public static function assertTablesEqual(PHPUnit_Extensions_Database_DataSet_ITable $expected, PHPUnit_Extensions_Database_DataSet_ITable $actual, $message = '')
    {
        $constraint = new PHPUnit_Extensions_Database_Constraint_TableIsEqual($expected);

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that two given datasets are equal.
     *
     * @param PHPUnit_Extensions_Database_DataSet_ITable $expected
     * @param PHPUnit_Extensions_Database_DataSet_ITable $actual
     * @param string $message
     */
    public static function assertDataSetsEqual(PHPUnit_Extensions_Database_DataSet_IDataSet $expected, PHPUnit_Extensions_Database_DataSet_IDataSet $actual, $message = '')
    {
        $constraint = new PHPUnit_Extensions_Database_Constraint_DataSetIsEqual($expected);

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Assert that a given table has a given amount of rows
     *
     * @param string $tableName Name of the table
     * @param int $expected Expected amount of rows in the table
     * @param string $message Optional message
     */
    public function assertTableRowCount($tableName, $expected, $message = '')
    {
        $constraint = new PHPUnit_Extensions_Database_Constraint_TableRowCount($tableName, $expected);
        $actual = $this->getConnection()->getRowCount($tableName);

        self::assertThat($actual, $constraint, $message);
    }

    /**
     * Asserts that a given table contains a given row
     *
     * @param array $expectedRow Row expected to find
     * @param PHPUnit_Extensions_Database_DataSet_ITable $table Table to look into
     * @param string $message Optional message
     */
    public function assertTableContains(array $expectedRow, PHPUnit_Extensions_Database_DataSet_ITable $table, $message = '')
    {
        self::assertThat($table->assertContainsRow($expectedRow), self::isTrue(), $message);
    }
}