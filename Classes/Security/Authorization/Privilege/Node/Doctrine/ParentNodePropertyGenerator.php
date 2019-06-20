<?php
namespace UpAssist\Neos\FrontendLogin\Security\Authorization\Privilege\Node\Doctrine;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter as DoctrineSqlFilter;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Authorization\Privilege\Entity\Doctrine\SqlGeneratorInterface;
use Doctrine\ORM\Query\Expr;

/**
 * A sql generator to create a sql subquery.
 */
class ParentNodePropertyGenerator implements SqlGeneratorInterface
{
    /**
     * @var SqlGeneratorInterface
     */
    protected $expression;

    /**
     * @param SqlGeneratorInterface $expression
     */
    public function __construct($expression)
    {
        $this->expression = $expression;
    }

    /**
     * @param DoctrineSqlFilter $sqlFilter
     * @param ClassMetadata $targetEntity Metadata object for the target entity to create the constraint for
     * @param string $targetTableAlias The target table alias used in the current query
     * @return string
     */
    public function getSql(DoctrineSqlFilter $sqlFilter, ClassMetadata $targetEntity, $targetTableAlias)
    {
        return '(
          SELECT COUNT(*) FROM typo3_typo3cr_domain_model_nodedata as parent
          WHERE ' . $targetTableAlias . '.path LIKE CONCAT(parent.path, "%")
          AND ' . $this->expression->getSql($sqlFilter, $targetEntity, 'parent') . ')';
    }
}