<?php

declare(strict_types = 1);

namespace Drupal\rdf_entity;

/**
 * Provides an interface to RDF entity graph handler service.
 */
interface RdfGraphHandlerInterface {

  /**
   * The structure of static cache property.
   *
   * This is the contents of the static cache when the cache is empty.
   *
   * @var array
   */
  const EMPTY_CACHE = [
    'definition' => [],
    'structure' => [],
  ];

  /**
   * Get the defined graph types for this entity type.
   *
   * A default graph is provided here already because there has to exist at
   * least one available graph for the entities to be saved in.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   *
   * @return array
   *   A structured array of graph definitions containing a title and a
   *   description. The array keys are the machine names of the graphs.
   */
  public function getGraphDefinitions(string $entity_type_id): array;

  /**
   * Returns a list of RDF entity graph IDs given a entity type ID.
   *
   * This is similar to ::getGraphDefinitions() but it returns only the RDF
   * entity graph IDs as an indexed array. Additionally, the list can be limited
   * to a given set of IDs by passing the second argument.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string[]|null $limit_to_graph_ids
   *   (optional) A list of RDF entity graph IDs to restrict the results. If
   *   omitted all the RDF entity graph IDs supported by the given entity type
   *   will be returned.
   *
   * @return array
   *   A list of RDF entity graph IDs.
   */
  public function getEntityTypeGraphIds(string $entity_type_id, array $limit_to_graph_ids = NULL): array;

  /**
   * Gets the default graph ID for an entity type.
   *
   * The default graph is the topmost graph entity when sorting all enabled
   * graphs by weight ascending for a given entity type.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   *
   * @return string
   *   The default graph ID.
   */
  public function getDefaultGraphId(string $entity_type_id): string;

  /**
   * Returns the graph URI given an entity type ID, a bundle and the graph ID.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $bundle
   *   The entity bundle.
   * @param string $graph_id
   *   The graph ID.
   *
   * @return string|null
   *   The URI of the requested graph.
   */
  public function getBundleGraphUri(string $entity_type_id, string $bundle, string $graph_id): ?string;

  /**
   * Returns the graph URIs for a given entity type.
   *
   * The list could be restricted to match only the passed graph IDs.
   *
   * @param string $entity_type_id
   *   The entity type to be checked.
   * @param string[]|null $limit_to_graph_ids
   *   (optional) A list of graph IDs to limit the results. NULL means that all
   *   graphs are allowed. Defaults to NULL.
   *
   * @return string[]
   *   A list of graph URIs.
   */
  public function getEntityTypeGraphUris(string $entity_type_id, array $limit_to_graph_ids = NULL): array;

  /**
   * Checks is a graph is available for a given bundle.
   *
   * @param string $entity_type_id
   *   The entity type of the bundle.
   * @param string $bundle
   *   The bundle to be checked.
   * @param string $graph_id
   *   The graph to be checked.
   *
   * @return bool
   *   If the graph is available for the specific bundle.
   */
  public function bundleHasGraph(string $entity_type_id, string $bundle, string $graph_id): bool;

  /**
   * Returns a plain, flat list of graph URIs related to the passed entity type.
   *
   * @param string $entity_type_id
   *   The ID of the entity type.
   * @param array $limit_to_graph_ids
   *   Optionally filter the graphs to be returned.
   *
   * @return array
   *   A flat list of graph URIs.
   */
  public function getEntityTypeGraphUrisFlatList(string $entity_type_id, array $limit_to_graph_ids = NULL): array;

  /**
   * Returns the graph ID for a given graph URI.
   *
   * This is basically a reverse search to get the ID of the graph for a given
   * entity type and bundle.
   *
   * @param string $entity_type_id
   *   The entity type ID.
   * @param string $bundle
   *   The bundle.
   * @param string $graph_uri
   *   The URI of the graph.
   *
   * @return string|null
   *   The ID of the graph or NULL.
   */
  public function getBundleGraphId(string $entity_type_id, string $bundle, string $graph_uri): ?string;

  /**
   * Clear the internal cache.
   *
   * @param string[]|null $path
   *   (optional) The path to a specific cache entry to be cleared. This
   *   parameter allows to clear only a specific cache entry. Defaults to NULL.
   *
   * @see \Drupal\Component\Utility\NestedArray::unsetValue()
   */
  public function clearCache(array $path = NULL): void;

}
