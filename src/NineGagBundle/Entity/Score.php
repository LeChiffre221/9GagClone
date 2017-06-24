<?php

namespace NineGagBundle\Entity;

/**
 * Score
 */
class Score
{
    /**
     * @var int
     */
    private $id;
    
    /**
     * @var \NineGagBundle\Entity\Post
     */
    private $post;
    
    /**
     * @var \NineGagBundle\Entity\User
     */
    private $user;

    /**
     * @var int
     */
    private $value;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Score
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
    
    function getPost(): \NineGagBundle\Entity\Post {
        return $this->post;
    }

    function getUser(): \NineGagBundle\Entity\User {
        return $this->user;
    }

    function setPost(\NineGagBundle\Entity\Post $post) {
        $this->post = $post;
    }

    function setUser(\NineGagBundle\Entity\User $user) {
        $this->user = $user;
    }


}

