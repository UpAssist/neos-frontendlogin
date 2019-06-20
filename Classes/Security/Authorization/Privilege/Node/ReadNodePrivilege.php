<?php
namespace UpAssist\Neos\FrontendLogin\Security\Authorization\Privilege\Node;

use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\EntityPrivilege;
use Neos\ContentRepository\Domain\Model\NodeData;
use UpAssist\Neos\FrontendLogin\Security\Authorization\Privilege\Node\Doctrine\ConditionGenerator;

/**
 * Class ReadNodePrivilege
 * @package UpAssist\Neos\FrontendLogin\Security\Authorization\Privilege\Node
 */
class ReadNodePrivilege extends EntityPrivilege
{
    /**
     * @param string $entityType
     * @return boolean
     */
    public function matchesEntityType($entityType)
    {
        return $entityType === NodeData::class;
    }

    /**
     * @return ConditionGenerator
     */
    protected function getConditionGenerator()
    {
        return new ConditionGenerator();
    }
}
