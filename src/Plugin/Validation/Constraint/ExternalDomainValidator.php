<?php

namespace Drupal\blog\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the ExternalDomain constraint.
 */
class ExternalDomainValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($items, Constraint $constraint) {
    $item = $items->first();
    if (!isset($item)) {
      return NULL;
    }
    foreach ($items as $item) {
      if (strpos($item->uri, $_SERVER['HTTP_HOST']) > 0){
        $this->context->addViolation($constraint->notExternalDomain, ['%uri' => $item->uri]);
      }
    }

  }
}
