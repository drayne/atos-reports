<?php
namespace Application\Entity;

use Application\Entity\Orgjed as Orgjed;
use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

/**
 * @ORM\Entity
 * @ORM\Table(name="anlanl")
 * @Annotation\Name("Anlanl")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *  */
class Anlanl
{
	/**
	 * @ORM\ID
	 * @ORM\Column(type="integer")
	 */
	protected $id;

    /**
     * @var Orgjed
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Orgjed")
     * @ORM\JoinColumn(name="rj", referencedColumnName="oj_sifra")
     * @Annotation\Exclude()
     */
    protected $rj;
	
	/** @ORM\Column(type="integer") */
	protected $anl_vlasnik;
	
	/** @ORM\Column(type="integer") */
	protected $anl_radnja;
	
	/** @ORM\Column(type="integer") */
	protected $anl_nalog;
	
	/** @ORM\Column(type="integer") */
	protected $anl_stavka;
	
//	/** @ORM\Column(type="integer") */
//	protected $anl_radnja;
	
	/** @ORM\Column(type="date")
	 * @Annotation\Attributes({"type":"Zend\Form\Element\Date", "id": "datdok", "min":"2010-01-01", "max":"2020-01-01", "step":"1"})
	 * @Annotation\Options({"label":"Datum:", "format":"Y-m-d"})
	 */
	protected $datdok;
	
	/**
	 * @ORM\Column(type="float")
	 */
	protected $duguje;
	
	/**
	 * @ORM\Column(type="float")
	 */
	protected $potrazuje;
	
	/**
	 * @ORM\Column(type="float")
	 */
	protected $dev_duguje;
	
	/**
	 * @ORM\Column(type="float")
	 */
	protected $dev_potrazuje;
	
	/** @ORM\Column(type="integer") */
	protected $komitent;
	
	/** @ORM\Column(type="integer") */
	protected $konto;

	/** @ORM\Column(type="integer") */
	protected $pol_brpol;


	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Get anl_vlasnik
	 *
	 * @return integer
	 */
	public function getAnl_vlasnik()
	{
		return $this->anl_vlasnik;
	}
	
	/**
	 * Get anl_radnja
	 *
	 * @return integer
	 */
	public function getAnl_radnja()
	{
		return $this->anl_radnja;
	}
	
	/**
	 * Get $anl_nalog
	 *
	 * @return integer
	 */
	public function getAnl_nalog()
	{
		return $this->anl_nalog;
	}	
	
	/**
	 * Get $anl_stavka
	 *
	 * @return integer
	 */
	public function getAnl_stavka()
	{
		return $this->anl_stavka;
	}
	
//	/**
//	 * Get $anl_radnja
//	 *
//	 * @return integer
//	 */
//	public function getAnl_radnja()
//	{
//		return $this->$anl_radnja;
//	}
	
	/**
	 * Get $datdok
	 *
	 * @return date
	 */
	public function getDatdok()
	{
		return $this->datdok;
	}
	
	/**
	 * Get $duguje
	 *
	 * @return float
	 */
	public function getDuguje()
	{
		return $this->duguje;
	}
	
	/**
	 * Get $potrazuje
	 *
	 * @return float
	 */
	public function getPotrazuje()
	{
		return $this->potrazuje;
	}
	
	/**
	 * Get $dev_duguje
	 *
	 * @return float
	 */
	public function getDev_duguje()
	{
		return $this->dev_duguje;
	}
	
	/**
	 * Get $dev_potrazuje
	 *
	 * @return float
	 */
	public function getDev_potrazuje()
	{
		return $this->dev_potrazuje;
	}	
	
	/**
	 * Get $komitent
	 *
	 * @return integer
	 */
	public function getKomitent()
	{
		return $this->komitent;
	}	
	
	/**
	 * Get $konto
	 *
	 * @return integer
	 */
	public function getKonto()
	{
		return $this->konto;
	}	
	
	/**
	 * Get $pol_brpol
	 *
	 * @return integer
	 */
	public function getPol_brpol()
	{
		return $this->pol_brpol;
	}


}