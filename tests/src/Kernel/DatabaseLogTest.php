<?php

declare(strict_types = 1);

namespace Drupal\Tests\rdf_entity\Kernel;

use Drupal\Core\Database\Database;
use Drupal\KernelTests\KernelTestBase;
use Drupal\Tests\rdf_entity\Traits\RdfDatabaseConnectionTrait;

/**
 * Tests the query logging.
 *
 * @group rdf_entity
 *
 * @coversDefaultClass \Drupal\rdf_entity\Database\Driver\sparql\Connection
 */
class DatabaseLogTest extends KernelTestBase {

  use RdfDatabaseConnectionTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->setUpSparql();
  }

  /**
   * Tests the log.
   *
   * @param string $method
   *   The method name 'query' or 'update'.
   * @param string $query
   *   The query.
   * @param array $args
   *   The query arguments.
   * @param \Throwable|null $expected_exception
   *   The expected exception, if any.
   *
   * @dataProvider provider
   */
  public function testLog(string $method, string $query, array $args, ?\Throwable $expected_exception): void {
    if ($expected_exception) {
      $this->expectException(get_class($expected_exception));
      $this->expectExceptionMessage($expected_exception->getMessage());
    }

    Database::startLog('log_test', 'sparql_default');
    $this->sparql->{$method}($query, $args);
    $log = $this->sparql->getLogger()->get('log_test');

    $this->assertCount(1, $log);

    $log_entry = reset($log);
    $this->assertEquals($query, $log_entry['query']);
    $this->assertSame($args, $log_entry['args']);
    $this->assertEquals('default', $log_entry['target']);
    $this->assertEquals('double', gettype($log_entry['time']));
    $this->assertGreaterThan(0, $log_entry['time']);
    // @todo Inspect also $log_entry['caller'] when
    // https://www.drupal.org/project/drupal/issues/2867788 lands.
    // @see https://www.drupal.org/project/drupal/issues/2867788
  }

  /**
   * Data provider for ::testLog().
   *
   * @return array
   *   Test cases.
   *
   * @see DatabaseLogTest::testLog()
   */
  public function provider(): array {
    return [
      'query' => [
        'query',
        'SELECT DISTINCT ?s ?p ?o WHERE { ?s ?p ?o } LIMIT 100',
        [],
        NULL,
      ],
      'update' => [
        'update',
        'CLEAR GRAPH <http://example.com>;',
        [],
        NULL,
      ],
      'query with arguments' => [
        'query',
        'SELECT DISTINCT ?s ?p ?o WHERE { <:subject> ?p ?o } LIMIT 100',
        [':subject' => 'http://example.com'],
        new \InvalidArgumentException('Replacement arguments are not yet supported.'),
      ],
    ];
  }

}
