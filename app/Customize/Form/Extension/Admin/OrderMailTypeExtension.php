<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Form\Extension\Admin;

use Doctrine\ORM\EntityRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\Admin\OrderMailType;
use Eccube\Form\Type\Master\MailTemplateType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class OrderMailTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * OrderMailTypeExtension constructor.
     */
    public function __construct(
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $templateIds = [
            $this->eccubeConfig['eccube_order_mail_template_id'],
            $this->eccubeConfig['eccube_order_accept_mail_template_id']
        ];

        $builder
            ->add('template', MailTemplateType::class, [
                'required' => false,
                'mapped' => false,
                'query_builder' => function (EntityRepository $er) use ($templateIds) {
                    return $er->createQueryBuilder('mt')
                        ->andWhere('mt.id IN (:ids)')
                        ->setParameter('ids', $templateIds)
                        ->orderBy('mt.id', 'ASC');
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return OrderMailType::class;
    }
    
    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        yield OrderMailType::class;
    }
}
