<?php

namespace Drupal\link_normalizer\Normalizer;

use Drupal\Core\Url;
use Drupal\serialization\Normalizer\NormalizerBase;
use Drupal\Core\TypedData\TypedDataInterface;

/**
 * Converts typed data objects to arrays.
 */
class LinkNormalizer extends NormalizerBase
{
    /**
     * The interface or class that this Normalizer supports.
     *
     * @var string
     */
    protected $supportedInterfaceOrClass = TypedDataInterface::class;

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        $value = $object->getValue();
        $parent = $object->getParent();
        $type = $parent->getFieldDefinition()->getType();
        if ($type === 'link') {
            $internalLink = $parent->get('uri')->getValue();
            $link = Url::fromUri($internalLink, ['absolute' => false])->toString();
            $object->getParent()->get('uri')->setValue('placeholder');
            $value = ['url' => $link];
        }
        return $value;
    }
}
