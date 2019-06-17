<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 * 
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */

namespace CsnUser\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Doctrine ORM implementation of User entity
 * 
 * @ORM\Entity(repositoryClass="CsnUser\Entity\Repository\UserRepository")
 * @ORM\Table(name="`OPERATER`",
 *   indexes={@ORM\Index(name="search_idx", columns={"korisnik", "ime", "prezime", "zastupnik"})}
 * )
 * @Annotation\Name("User")
 */
class User
{
    /**
     * @var string
     *
     * @ORM\Column(name="korisnik", type="string", length=30, nullable=false, unique=true)
     * @ORM\Id
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":3, "max":30,
     *       
     *      "messages":{
     *          "stringLengthTooShort":"Korisničko ime mora imati najmanje 3 znaka!"
     *      }
     * }})
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"text",
     *   "required":"true"
     * })
     */
    protected $korisnik;

    /**
     * @var string
     *
     * @ORM\Column(name="ime", type="string", length=40, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
     */
    protected $ime;

    /**
     * @var string
     *
     * @ORM\Column(name="prezime", type="string", length=40, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
     */
    protected $prezime;

    /**
     * @var string
     *
     * @ORM\Column(name="lozinka", type="string", length=60, nullable=false)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":3, "max":20,
     * 
     *      "messages":{
     *          "stringLengthTooShort":"Lozinka mora imati najmanje 3 znaka!"
     *      }
     * }})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"password",
     *   "required":"true"
     * })
     */
    protected $lozinka;

    public function __construct()
    {

    }

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
     * Set korisnik
     *
     * @param  string $korisnik
     * @return User
     */
    public function setKorisnik($korisnik)
    {
        $this->korisnik = $korisnik;

        return $this;
    }

    /**
     * Get korisnik
     *
     * @return string
     */
    public function getKorisnik()
    {
        return $this->korisnik;
    }


    /**
     * Set ime
     *
     * @param  string $ime
     * @return User
     */
    public function setIme($ime)
    {
        $this->ime = $ime;

        return $this;
    }

    /**
     * Get ime
     *
     * @return string
     */
    public function getIme()
    {
        return $this->ime;
    }

    /**
     * Set prezime
     *
     * @param  string $prezime
     * @return User
     */
    public function setPrezime($prezime)
    {
        $this->prezime = $prezime;

        return $this;
    }

    /**
     * Get prezime
     *
     * @return string
     */
    public function getPrezime()
    {
        return $this->prezime;
    }

    /**
     * Set lozinka
     *
     * @param  string $lozinka
     * @return User
     */
    public function setLozinka($lozinka)
    {
        $this->lozinka = $lozinka;

        return $this;
    }

    /**
     * Get lozinka
     *
     * @return string
     */
    public function getLozinka()
    {
        return $this->lozinka;
    }

}
