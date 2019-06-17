<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;


/** 
 * @ORM\Entity
 * @ORM\Table(name="polao3")
 * @Annotation\Name("Polao3")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *  */
class Polao3
{
    /**
    * @ORM\ID
    * @ORM\Column(type="integer")
    * @ORM\OneToOne(targetEntity="Polisa", inversedBy="pol_brpol")
    */
    protected $ao3_brpol;
       
    /**
     * @ORM\Column(type="float")
     */
    protected $osnpremao;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $uk_prem_ao;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $iznosbonmal;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $bonusmalus;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $izn_doplatka;   

    /**
     * @ORM\Column(type="float")
     */
    protected $izn_popusta;
    
    /**
     * @var Application\Entity\Polao2
     *
     * @ORM\ManyToOne(targetEntity="Application\Entity\Zonriz")
     * @ORM\JoinColumn(name="zonr", referencedColumnName="zr_sifra")
     * 
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"multiple":false})
     * @Annotation\Options({
     * "label":"Zone rizika:",
     * "empty_option": "SVE",
     * "target_class":"Application\Entity\Zonriz",
     * "property": "naziv"})
     */
    protected $zonr;

    /**
     *  @ORM\Column(type="integer") 
     */
    protected $targrupa;
    
    /**
     *  @ORM\Column(type="integer")
     */
    protected $tarpodgrupa;


    

    /**
     * Get ao3_brpol
     *
     * @return integer
     */
    public function getao3_brpol()
    {
    	return $this->ao3_brpol;
    }
    
    /**
     * Get osnpremao
     *
     * @return float
     */
    public function getOsnpremao()
    {
    	return $this->osnpremao;
    }   

    /**
     * Set osnpremao
     *
     * @param float $osnpremao
     *
     * @return polao3
     */
    public function setOsnpremao($osnpremao)
    {
    	$this->osnpremao = $osnpremao;
    
    	return $this;
    }    
    
    /**
     * Get uk_prem_ao
     *
     * @return float
     */
    public function getUk_prem_ao()
    {
    	return $this->uk_prem_ao;
    }
    
    /**
     * Set name
     *
     * @param float $uk_prem_ao
     *
     * @return polao3
     */
    public function setUk_prem_ao($uk_prem_ao)
    {
    	$this->uk_prem_ao = $uk_prem_ao;
    
    	return $this;
    }    
    
    /**
     * Get iznosbonmal
     *
     * @return float
     */
    public function getIznosbonmal()
    {
    	return $this->iznosbonmal;
    }
    
    /**
     * Set iznosbonmal
     *
     * @param float $iznosbonmal
     *
     * @return polao3
     */
    public function setIznosbonmal($iznosbonmal)
    {
    	$this->iznosbonmal = $iznosbonmal;
    
    	return $this;
    }    
    
    /**
     * Get bonusmalus
     *
     * @return float
     */
    public function getBonusmalus()
    {
    	return $this->bonusmalus;
    }
    
    /**
     * Set bonusmalus
     *
     * @param float $bonusmalus
     *
     * @return polao3
     */
    public function setBonusmalus($bonusmalus)
    {
    	$this->bonusmalus = $bonusmalus;
    
    	return $this;
    }    
    
    /**
     * Get izn_doplatka
     *
     * @return float
     */
    public function getIzn_doplatka()
    {
    	return $this->izn_doplatka;
    }
    
    /**
     * Set izn_doplatka
     *
     * @param float $izn_doplatka
     *
     * @return polao3
     */
    public function setIzn_doplatka($izn_doplatka)
    {
    	$this->izn_doplatka = $izn_doplatka;
    
    	return $this;
    } 

    /**
     * Get izn_popusta
     *
     * @return float
     */
    public function getIzn_popusta()
    {
    	return $this->izn_popusta;
    }
    
    /**
     * Set izn_popusta
     *
     * @param float $izn_popusta
     *
     * @return polao3
     */
    public function setIzn_popusta($izn_popusta)
    {
    	$this->izn_popusta = $izn_popusta;
    
    	return $this;
    }    
    
    /**
     * Set zonr
     *
     * @param integer $zonr
     *
     * @return Polao3
     */
    public function setZonr($zonr)
    {
        $this->zonr = $zonr;

        return $this;
    }

    /**
     * Get zonr
     *
     * @return ORM\ManyToMany\Entity\Zonriz
     */
    public function getZonr()
    {
        return $this->zonr;
    }
    
    /**
     * Set targrupa
     *
     * @param integer $targrupa
     *
     * @return Polao3
     */
    public function setTargrupa($targrupa)
    {
    	$this->targrupa = $targrupa;
    
    	return $this;
    }
    
    /**
     * Get targrupa
     *
     * @return integer
     */
    public function getTargrupa()
    {
    	return $this->targrupa;
    }
    
    /**
     * Set tarpodgrupa
     *
     * @param integer $tarpodgrupa
     *
     * @return Polao3
     */
    public function setTarpodgrupa($tarpodgrupa)
    {
    	$this->tarpodgrupa = $tarpodgrupa;
    
    	return $this;
    }
    
    /**
     * Get tarpodgrupa
     *
     * @return integer
     */
    public function getTarpodgrupa()
    {
    	return $this->tarpodgrupa;
    }
}
