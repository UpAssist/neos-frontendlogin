<?php
namespace UpAssist\Neos\FrontendLogin\Security\Authorization\Privilege\Node\Doctrine;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\FalseConditionGenerator;
use TYPO3\Flow\Security\Authorization\Privilege\Entity\Doctrine\PropertyConditionGenerator;

/**
 * Class ConditionGenerator
 * @package UpAssist\Neos\FrontendLogin\Security\Authorization\Privilege\Node\Doctrine
 */
class ConditionGenerator extends \TYPO3\TYPO3CR\Security\Authorization\Privilege\Node\Doctrine\ConditionGenerator
{
    /**
     * @param string $property
     * @param mixed $value
     * @return PropertyConditionGenerator
     */
    public function nodePropertyIs($property, $value)
    {
        $propertyConditionGenerator = new PropertyConditionGenerator('properties');
        if (!is_string($property) || is_array($value)) {
            return new FalseConditionGenerator();
        }
        return $propertyConditionGenerator->like('%"' . trim($property) . '": ' . json_encode($value) . '%');
    }
    /**
     * @param string $property
     * @param mixed $value
     * @return PropertyConditionGenerator
     */
    public function parentNodePropertyIs($property, $value)
    {
        $propertiesConditionGenerator = $this->nodePropertyIs($property, $value);
        $subqueryGenerator = new ParentNodePropertyGenerator($propertiesConditionGenerator);
        return $subqueryGenerator;
    }
}