<?php

namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\BaseInfo")
 */
trait BaseInfoTrait
{
    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, options={"unsigned":true, "default":0})
     */
    private $entry_point_value = '0';

    /**
     * @return string
     */
    public function getEntryPointValue()
    {
        return $this->entry_point_value;
    }

    /**
     * @param string $entryPointValue
     *
     * @return $this;
     */
    public function setEntryPointValue($entryPointValue)
    {
        $this->entry_point_value = $entryPointValue;

        return $this;
    }
}
