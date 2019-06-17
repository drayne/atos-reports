<?php
namespace Application\Entity;
use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;


/** 
 * @ORM\Entity
 * @ORM\Table(name="zonriz")
 * @Annotation\Name("Zonriz")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 *  */
class Zonriz
{
    /**
    * @ORM\ID
    * @ORM\Column(type="integer")
    */
    protected $zr_sifra;
       
    /**
     * @ORM\Column(type="string")
     */
    protected $naziv; 

    /**
     * Get zr_sifra
     *
     * @return integer
     */
    public function getZr_sifra()
    {
    	return $this->zr_sifra;
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

    /**
     * Set naziv
     *
     * @param string $naziv
     *
     * @return Zonriz
     */
    public function setNaziv($naziv)
    {
    	$this->naziv = $naziv;
    
    	return $this;
    }    
    
   
}
