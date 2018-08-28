<?php

namespace Drupal\Tests\rdf_entity\Kernel;

/**
 * Tests a field with multiple columns.
 *
 * @group rdf_entity
 */
class RdfMultiColumnTest extends RdfKernelTestBase {

  /**
   * Tests the link field.
   *
   * Ensures that saving a link field with 2 columns mapped will save and load
   * both columns in the same delta as expected.
   */
  public function testLinkField() {
    /** @var \Drupal\rdf_entity\RdfInterface $entity */
    $entity = $this->entityManager->getStorage('rdf_entity')->create([
      'rid' => 'multifield',
      'label' => 'My entity',
      'field_link' => [
        'uri' => 'http://example.com',
        'title' => 'My link title',
      ],
    ]);

    $entity->save();
    $storage = $this->entityManager->getStorage('rdf_entity');
    $storage->resetCache();
    $rdf = $storage->load($entity->id());
    $this->assertEquals('http://example.com', $rdf->get('field_link')->uri);
    $this->assertEquals('My link title', $rdf->get('field_link')->title);
  }

}
