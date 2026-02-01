<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MStationsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MStationsTable Test Case
 */
class MStationsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MStationsTable
     */
    protected $MStations;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.MStations',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MStations') ? [] : ['className' => MStationsTable::class];
        $this->MStations = $this->getTableLocator()->get('MStations', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MStations);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MStationsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
