<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;


/** 
 * @ORM\Entity
 * @ORM\Table(name="polao2")
 * @Annotation\Name("Polao2")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *  */
class Polao2
{
    /**
    * @ORM\ID
    * @ORM\Column(type="integer")    
    */
    protected $ao2_brpol;
       
    /**
     * @ORM\Column(type="string")
     */
    protected $brojsasije;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $regbroj;

    /** @ORM\Column(type="integer") */
    protected $snagakw;

    

    /**
     * Get ao2_brpol
     *
     * @return integer
     */
    public function getAo2_brpol()
    {
    	return $this->ao2_brpol;
    }
    
    /**
     * Get brojsasije
     *
     * @return string
     */
    public function getBrojsasije()
    {
    	return $this->brojsasije;
    }   

    /**
     * Set name
     *
     * @param string $brojsasije
     *
     * @return polao2
     */
    public function setbrojsasije($brojsasije)
    {
    	$this->brojsasije = $brojsasije;
    
    	return $this;
    }    
    
    /**
     * Set regbroj
     *
     * @param string $regbroj
     *
     * @return Polao2
     */
    public function setRegbroj($regbroj)
    {
        $this->regbroj = $regbroj;

        return $this;
    }

    /**
     * Get regbroj
     *
     * @return string
     */
    public function getRegbroj()
    {
        return $this->regbroj;
    }
    
    /**
     * Set snagakw
     *
     * @param integer $snagakw
     *
     * @return Polao2
     */
    public function setSnagakw($snagakw)
    {
    	$this->snagakw = $snagakw;
    
    	return $this;
    }
    
    /**
     * Get snagakw
     *
     * @return integer
     */
    public function getSnagakw()
    {
    	return $this->snagakw;
    }
}
