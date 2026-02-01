<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MLinesTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MLinesTable Test Case
 */
class MLinesTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\MLinesTable
     */
    protected $MLines;

    /**
     * Fixtures
     *
     * @var list<string>
     */
    protected array $fixtures = [
        'app.MLines',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('MLines') ? [] : ['className' => MLinesTable::class];
        $this->MLines = $this->getTableLocator()->get('MLines', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->MLines);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @uses \App\Model\Table\MLinesTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
