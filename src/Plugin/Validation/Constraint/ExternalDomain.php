<?php

namespace Drupal\blog\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Checks that the submitted URL is External Domain Link://
 *
 * @Constraint(
 *   id = "external_domain",
 *   label = @Translation("ExternalDomain", context = "Validation"),
 *   type = "string"
 * )
 */
class ExternalDomain extends Constraint {
  public $notExternalDomain = 'Add External Domain Link %uri is Internal Domain Link.';
}