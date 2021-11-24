<?php

namespace Drupal\saved_list\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

class SavedList extends ContentEntityBase implements ContentEntityInterface
{
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel('ID')
            ->setDescription('The ID of the Bonus entity.')
            ->setReadOnly(TRUE);

        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel('UUID')
            ->setDescription('The UUID of the Bonus entity.')
            ->setReadOnly(TRUE);

        return $fields;
    }
}
