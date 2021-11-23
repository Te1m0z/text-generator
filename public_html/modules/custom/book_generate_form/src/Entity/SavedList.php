<?php

namespace Drupal\book_generate_form\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Url;
// interfaces
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;


class SavedList extends ContentEntityBase implements ContentEntityInterface
{

    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        // Standard field, used as unique if primary index.
        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel('ID')
            ->setDescription('The ID of the Bonus entity.')
            ->setReadOnly(TRUE);

        // Standard field, unique outside of the scope of the current project.
        $fields['uuid'] = BaseFieldDefinition::create('uuid')
            ->setLabel('UUID')
            ->setDescription('The UUID of the Bonus entity.')
            ->setReadOnly(TRUE);

        // Int field.
        $fields['fint'] = BaseFieldDefinition::create('integer')
            ->setLabel('Int field')
            ->setDescription('Example int field.');

        // Record creation date.
        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel('Created')
            ->setDescription('The time that example was created.');

        // String field.
        $fields['fstring'] = BaseFieldDefinition::create('string')
            ->setLabel('String field')
            ->setDescription('Example string field.')
            ->setSettings(array(
                'default_value' => 'Hello world!',
                'max_length' => 100,
                'text_processing' => 0,
            ));

        return $fields;
    }

    public function toUrl($rel = 'canonical', array $options = [])
    {
        // Return default URI as a base scheme as we do not have routes yet.
        return Url::fromUri('base:/example/' . $this->id(), $options);
    }
}
