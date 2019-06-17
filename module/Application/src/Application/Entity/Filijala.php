<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="filijala")
 * @Annotation\Name("Filijala")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *  */
class Filijala
{
	/**
	 * @ORM\ID
	 * @ORM\Column(type="integer")
	 */
	protected $fil_sifra;

    /**
     * @ORM\Column(type="string")
     */
    protected $naziv;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getFil_sifra()
	{
		return $this->fil_sifra;
	}

    /**
     * Get naziv
     *
     * @return string
     */
    public function getNaziv()
    {
        return $this->naziv;
    }



	
}