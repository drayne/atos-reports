<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Filijala;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="orgjed")
 * @Annotation\Name("Orgjed")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *  */
class Orgjed
{
	/**
	 * @ORM\ID
	 * @ORM\Column(type="integer")
	 */
	protected $oj_sifra;

    /**
     * @var Filijala
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Filijala")
     * @ORM\JoinColumn(name="filijala", referencedColumnName="fil_sifra")
     * @Annotation\Exclude()
     */
    protected $filijala;


    /**
     * @ORM\Column(type="string")
     */
    protected $naziv;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getOj_sifra()
	{
		return $this->oj_sifra;
	}


    /**
     * Get filijala
     *
     * @return ORM\ManyToMany\Entity\Filijala
     */
    public function getFilijala()
    {
        return $this->filijala;
    }


    /** @ORM\Column(type="integer") */
    protected $zonr;

    /**
     * Get naziv
     *
     * @return string
     */
    public function getNaziv()
    {
        return $this->naziv;
    }

    /**
     * Get zonr
     *
     * @return integer
     */
    public function getZonr()
    {
        return $this->zonr;
    }

	
}