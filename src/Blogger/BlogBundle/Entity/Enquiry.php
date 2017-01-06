<?php
namespace Blogger\BlogBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class Enquiry
{

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3,minMessage="Minimal name length 3 characters")
     */
    protected $name;

    /**
     * @Assert\Email(message="Email is not valid")
     */
    protected $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=3,minMessage="Minimal subject length 3 characters")
     */
    protected $subject;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=10,minMessage="Minimal body length 10 characters")
     */
    protected $body;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

}