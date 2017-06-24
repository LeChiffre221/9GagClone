<?php

namespace NineGagBundle\Entity;

/**
 * Comment
 */
class Comment
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \NineGagBundle\Entity\Post
     */
    private $post;
    
    /**
     * @var int
     */
    private $idPost;
    
    /** 
     * @var \NineGagBundle\Entity\User
     */
    private $user;
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
     * Set content
     *
     * @param string $content
     *
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }



    /**
     * Set post
     *
     * @param \NineGagBundle\Entity\Post $post
     *
     * @return Comment
     */
    public function setPost(\NineGagBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \NineGagBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }
    
    function getIdPost() {
        return $this->idPost;
    }

    function setIdPost($idPost) {
        $this->idPost = $idPost;
    }

    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }



}
