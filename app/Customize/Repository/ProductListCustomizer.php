<?php


namespace Customize\Repository;

use Doctrine\ORM\QueryBuilder;
use Eccube\Doctrine\Query\JoinClause;
use Eccube\Doctrine\Query\QueryCustomizer;
use Eccube\Doctrine\Query\WhereClause;
use Eccube\Repository\QueryKey;

class ProductListCustomizer implements QueryCustomizer
{
	/**
     * @param QueryBuilder $builder
     * @param array $params
     * @param string $queryKey
     */
    final public function customize(QueryBuilder $builder, $params, $queryKey)
    {
        if (!isset($params['tag_id'])) {
            return [];
        }

		if(!empty($params['tag_id']) && $params['tag_id']) {
        	JoinClause::innerJoin('p.ProductTag', 'pt')->build($builder);
        	JoinClause::innerJoin('pt.Tag', 't')->build($builder);

        	WhereClause::eq('pt.Tag', ':Tag', $params['tag_id'])->build($builder);
        }
    }

    /**
     * ProductRepository::getQueryBuilderBySearchData に適用する.
     *
     * @return string
     * @see \Eccube\Repository\ProductRepository::getQueryBuilderBySearchData()
     * @see QueryKey
     */
    public function getQueryKey()
    {
        return QueryKey::PRODUCT_SEARCH;
    }
}
