<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Application\Entity\Polao3;
use Application\Entity\Polao2;
use Zend\Form\Annotation;
use Symfony\Component\Console\Application;
use Zend\Db\Sql\Ddl\Column\BigInteger;


/** 
 * @ORM\Entity
 * @ORM\Table(name="polisa")
 * @Annotation\Name("Polisa")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *  */
class Polisa
{
    
	/**
	 * @ORM\ID
     * @ORM\Column(type="integer", name="pol_brpol")
	 */
    protected $pol_brpol;
    
    /**
     * @var Polao2
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Polao2")
     * @ORM\JoinColumn(name="pol_brpol", referencedColumnName="ao2_brpol")
     * @Annotation\Exclude()
     */
    protected $polao2;
    
    /**
     * @var Polao3
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Polao3")
     * @ORM\JoinColumn(name="pol_brpol", referencedColumnName="ao3_brpol")
     * @Annotation\Exclude()
     */
    protected $polao3;
           
    /**
     * @ORM\Column(type="integer")
     */
    protected $bm_prenos_polisa;
    
    /**
     * @ORM\Column(type="bigint")
     */
    protected $jmbg;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $vros;

    /** @ORM\Column(type="string") */
    protected $nazivugov;

    /** @ORM\Column(type="date")
     * @Annotation\Attributes({"type":"Zend\Form\Element\Date", "id": "datdok", "min":"2010-01-01", "max":"2020-01-01", "step":"1"})
     * @Annotation\Options({"label":"Datum:", "format":"Y-m-d"})
     */
    protected $datdok;
    
    /** @ORM\Column(type="datetime") */
    protected $datpoc;
    
    /** @ORM\Column(type="datetime") */
    protected $datist;
    
    /** @ORM\Column(type="integer") */
    protected $mbrzastup;    

    public function __construct()
    {

    }
        
    /**
     * Get nazivugov
     *
     * @return string
     */
    public function getNazivugov()
    {
    	return $this->nazivugov;
    }   

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Polisa
     */
    public function setNazivugov($nazivugov)
    {
    	$this->nazivugov = $nazivugov;
    
    	return $this->nazivugov;
    }    
    
    /**
     * Set pol_brpol
     *
     * @param integer $pol_brpol
     *
     * @return Polisa
     */
    public function setPol_brpol($pol_brpol)
    {
        $this->pol_brpol = $pol_brpol;

        return $this;
    }

    /**
     * Get pol_brpol
     *
     * @return integer
     */
    public function getPol_brpol()
    {
        return $this->pol_brpol;
    }
    
    /**
     * Set jmbg
     *
     * @param bigint $jmbg
     *
     * @return Polisa
     */
    public function setJmbg($jmbg)
    {
    	$this->jmbg = $jmbg;
    
    	return $this;
    }
    
    /**
     * Get jmbg
     *
     * @return bigint
     */
    public function getJmbg()
    {
    	return $this->jmbg;
    }
    
    /**
     * Set vros
     *
     * @param integer $vros
     *
     * @return Polisa
     */
    public function setVros($vros)
    {
    	$this->vros = $vros;
    
    	return $this;
    }
    
    /**
     * Get vros
     *
     * @return integer
     */
    public function getVros()
    {
    	return $this->vros;
    }
    
    /**
     * Set bm_prenos_polisa
     *
     * @param integer $bm_prenos_polisa
     *
     * @return Polisa
     */
    public function setBm_prenos_polisa($bm_prenos_polisa)
    {
    	$this->bm_prenos_polisa = $bm_prenos_polisa;
    
    	return $this;
    }
    
    /**
     * Get bm_prenos_polisa
     *
     * @return integer
     */
    public function getBm_prenos_polisa()
    {
    	return $this->bm_prenos_polisa;
    }
    
    /**
     * Set datdok
     *
     * @param integer $datdok
     *
     * @return Polisa
     */
    public function setDatdok($datdok)
    {
    	$this->datdok = $datdok;
    
    	return $this;
    }
    
    /**
     * Get datdok
     *
     * @return datetime
     */
    public function getDatdok()
    {
    	return $this->datdok;
    }   

    /**
     * Set datist
     *
     * @param integer $datist
     *
     * @return Polisa
     */
    public function setDatist($datist)
    {
    	$this->datist = $datist;
    
    	return $this;
    }
    
    /**
     * Get datist
     *
     * @return datetime
     */
    public function getDatist()
    {
    	return $this->datist;
    }
    
    /**
     * Set datpoc
     *
     * @param integer $datpoc
     *
     * @return Polisa
     */
    public function setDatpoc($datpoc)
    {
    	$this->datpoc = $datpoc;
    
    	return $this;
    }
    
    /**
     * Get datpoc
     *
     * @return date
     */
    public function getDatpoc()
    {
    	return $this->datpoc;
    }
    
    /**
     * Get polao2
     *
     * @return ORM\ManyToMany\Entity\Polao2
     */
    public function getPolao2()
    {
    	return $this->polao2;
    }
    
    /**
     * Get polao3
     *
     * @return ORM\ManyToMany\Entity\Polao3
     */
    public function getPolao3()
    {
    	return $this->polao3;
    } 

    /**
     * Set mbrzastup
     *
     * @param integer $mbrzastup
     *
     * @return Polisa
     */
    public function setMbrzastup($mbrzastup)
    {
    	$this->mbrzastup = $mbrzastup;
    
    	return $this;
    }
    
    /**
     * Get mbrzastup
     *
     * @return integer
     */
    public function getMbrzastup()
    {
    	return $this->mbrzastup;
    }
}
