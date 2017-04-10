<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tweet
 *
 * @ORM\Table(name="tweets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TweetRepository")
 */
class Tweet
{
    use EntityResolverTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     *
     * @ORM\ManyToOne(targetEntity="TwitterUser", inversedBy="tweets")
     * @ORM\JoinColumn(name="twitter_user_id", referencedColumnName="id")
     */
    private $twitterUser;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Tweet
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = new \DateTime($createdAt);

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set twitterUser
     *
     * @param \AppBundle\Entity\TwitterUser $twitterUser
     *
     * @return Tweet
     */
    public function setTwitterUser(\AppBundle\Entity\TwitterUser $twitterUser = null)
    {
        $this->twitterUser = $twitterUser;

        return $this;
    }

    /**
     * Get twitterUser
     *
     * @return \AppBundle\Entity\TwitterUser
     */
    public function getTwitterUser()
    {
        return $this->twitterUser;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Tweet
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
}
