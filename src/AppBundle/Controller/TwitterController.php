<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tweet;
use AppBundle\Entity\TwitterUser;
use AppBundle\Form\TwitterSearchType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TwitterController extends Controller
{
    /**
     * @Route("/", name="twitter_user_catalog")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $twitterUsers = $em->getRepository('AppBundle:TwitterUser')->findAll();

        return $this->render('twitter/index.html.twig', compact('twitterUsers'));
    }

    /**
     * @Route("/search/{page}", name="search", requirements={"page": "\d+"})
     */
    public function searchAction(Request $request, $page = 1)
    {
        $em = $this->getDoctrine()->getManager();

        $resultsPerPage = $this->container->getParameter('results_per_page');

        $form = $this->createForm(TwitterSearchType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $searchResults = $em
                ->getRepository('AppBundle:Tweet')
                ->search(
                    $data['query'],
                    $data['user'],
                    $page,
                    $resultsPerPage
                );

            $totalResults = $em
                ->getRepository('AppBundle:Tweet')
                ->countTweets(
                    $data['query'],
                    $data['user']
                );

            return $this->render('twitter/search.html.twig', array(
                'form' => $form->createView(),
                'searchResults' => $searchResults,
                'totalResults' => $totalResults,
                'resultsPerPage' => $resultsPerPage,
                'queryString' => $data['query'],
                'queryUser' => isset($data['user']) ? $data['user']->getId() : null
            ));
        }

        return $this->render('twitter/search.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/{username}", name="twitter_user_page")
     */
    public function showAction($username)
    {
        $em = $this->getDoctrine()->getManager();
        $twitter = $this->get('endroid.twitter');

        $resultsPerPage = $this->container->getParameter('results_per_page');

        $twitterUser = $em->getRepository('AppBundle:TwitterUser')->findOneByScreenName($username);

        if (!$twitterUser) {
            $response = $twitter->query('/users/show', 'GET', 'json', [
                'screen_name' => $username,
                'count' => $resultsPerPage
            ]);

            $twitterUserData = json_decode($response->getContent());

            if (isset($twitterUserData->errors)) {
                throw new NotFoundHttpException('Twitter user ' . $username . ' not found.');
            }

            $twitterUser = TwitterUser::resolveFromArray((array)$twitterUserData);

            $em->persist($twitterUser);
        }

        $tweetsResponse = $twitter->query('/statuses/user_timeline', 'GET', 'json', [
            'screen_name' => $twitterUser->getScreenName(),
            'count' => $resultsPerPage
        ]);

        $freshTweets = json_decode($tweetsResponse->getContent());

        // This could be separated as a queue job
        $this->saveNewTweetsOnly($freshTweets, $twitterUser);

        $em->flush();

        return $this->render('twitter/show.html.twig', array(
            'twitterUser' => $twitterUser,
            'tweets' => $freshTweets
        ));
    }

    /**
     *
     * This function checks our entries in db and persists only the new ones
     * There is probably a MUCH better way to avoid duplicates :)
     * And also this shouldn't be in controller but yeah... :)
     *
     * @param $freshTweets
     */
    private function saveNewTweetsOnly($freshTweets, $twitterUser)
    {
        $em = $this->getDoctrine()->getManager();

        $existingTweets = $em->getRepository('AppBundle:Tweet')->findByTwitterUser($twitterUser);

        $existingTweets = array_map(function ($tweet) {
            return $tweet->getContent();
        }, $existingTweets);

        foreach ($freshTweets as $tweetData) {
            if (in_array($tweetData->text, $existingTweets)) {
                continue;
            }

            $tweet = new Tweet();
            $tweet->setContent($tweetData->text);
            $tweet->setCreatedAt($tweetData->created_at);
            $tweet->setTwitterUser($twitterUser);
            $em->persist($tweet);
        }
    }
}
