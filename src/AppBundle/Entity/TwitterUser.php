<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TwitterUser
 *
 * @ORM\Table(name="twitter_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TwitterUserRepository")
 */
class TwitterUser
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
     * @ORM\OneToMany(targetEntity="Tweet", mappedBy="twitterUser")
     */
    private $tweets;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="screen_name", type="string", length=255, unique=true)
     */
    private $screenName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="followers_count", type="integer")
     */
    private $followersCount;


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
     * Set name
     *
     * @param string $name
     *
     * @return TwitterUser
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set screenName
     *
     * @param string $screenName
     *
     * @return TwitterUser
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return TwitterUser
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set followersCount
     *
     * @param integer $followersCount
     *
     * @return TwitterUser
     */
    public function setFollowersCount($followersCount)
    {
        $this->followersCount = $followersCount;

        return $this;
    }

    /**
     * Get followersCount
     *
     * @return int
     */
    public function getFollowersCount()
    {
        return $this->followersCount;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tweets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tweet
     *
     * @param \AppBundle\Entity\Tweet $tweet
     *
     * @return TwitterUser
     */
    public function addTweet(\AppBundle\Entity\Tweet $tweet)
    {
        $this->tweets[] = $tweet;

        return $this;
    }

    /**
     * Remove tweet
     *
     * @param \AppBundle\Entity\Tweet $tweet
     */
    public function removeTweet(\AppBundle\Entity\Tweet $tweet)
    {
        $this->tweets->removeElement($tweet);
    }

    /**
     * Get tweets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTweets()
    {
        return $this->tweets;
    }
}
