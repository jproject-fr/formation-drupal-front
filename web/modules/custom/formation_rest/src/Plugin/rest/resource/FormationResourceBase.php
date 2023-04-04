<?php

namespace Drupal\formation_rest\Plugin\rest\resource;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\rest\Plugin\ResourceBase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

/**
 * Class FormationResourceBase.
 *
 * @package Drupal\formation_rest\Plugin\rest\resource
 */

/**
 * Formation Resource Base.
 */
class FormationResourceBase extends ResourceBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Session\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * The term storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $termStorage;

  /**
   * The user storage.
   *
   * @var \Drupal\Core\Session\EntityStorageInterface
   */
  protected $userStorage;

  /**
   * Logger interface.
   *
   * @var Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * The mail validator.
   *
   * @var \Drupal\Component\Utility\EmailValidatorInterface
   */
  protected $emailValidator;

  /**
   * CamelCase to Underscore name converter.
   *
   * @var \Symfony\Component\Serializer\NameConverter\NameConverterInterface
   */
  protected static $converter;

  /**
   * The errors message.
   *
   * @var array
   */
  protected $result = [];

  /**
   * To update or create user.
   *
   * @var bool
   */
  protected $update = TRUE;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a FormationResource instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Component\Utility\EmailValidatorInterface $emailValidator
   *   The email validator service.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    EntityTypeManagerInterface $entityTypeManager,
    EmailValidatorInterface $emailValidator,
    Connection $connection
  ) {
    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $serializer_formats,
      $logger
    );
    $this->entityTypeManager = $entityTypeManager;
    $this->nodeStorage = $this->entityTypeManager->getStorage('node');
    $this->termStorage = $this->entityTypeManager->getStorage('taxonomy_term');
    $this->userStorage = $this->entityTypeManager->getStorage('user');
    $this->logger = $logger;
    $this->emailValidator = $emailValidator;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('entity_type.manager'),
      $container->get('email.validator'),
      $container->get('database')
    );
  }

  /**
   * Get camel case to snake case converter.
   *
   * @return \Symfony\Component\Serializer\NameConverter\NameConverterInterface
   *   Camel case to snake case converter.
   */
  protected static function getCamelCaseToSnakeCaseNameConverter() {
    if (!isset(static::$converter)) {
      static::$converter = new CamelCaseToSnakeCaseNameConverter();
    }
    return static::$converter;
  }

  /**
   * Converts camel case to snake case (i.e. underscores).
   *
   * @param string $string
   *   String to be converted.
   * @param bool $fieldPrefix
   *   Include field_ as a prefix or not.
   *
   * @return string
   *   String with camel case converted to snake case.
   */
  public static function camelToSnake($string, $fieldPrefix = TRUE) {
    $fieldName = $fieldPrefix ? "field_" : "";
    $fieldName .= static::getCamelCaseToSnakeCaseNameConverter()->normalize($string);
    return $fieldName;
  }

}
