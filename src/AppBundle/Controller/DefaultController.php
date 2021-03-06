<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations\Prefix;

use  Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultController extends FOSRestController
{
    /**
     * List all apps.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     * @Annotations\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing apps.")
     * @Annotations\QueryParam(name="limit", requirements="\d+", default="5", description="How many apps to return.")
     *
     * @Annotations\View()
     *
     * @param Request               $request      the request object
     * @param ParamFetcherInterface $paramFetcher param fetcher service
     *
     * @return array
     */
    public function getAppsAction(Request $request, ParamFetcherInterface $paramFetcher)
    {
        $client   = $this->get('guzzle.client.ws_pusher');

        $response = $client->request('GET', '/apps');

        return json_decode($response->getBody(), true);
    }

    /**
     * Get single App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Gets a App for a given appGuid",
     *     output = "AppBundle\Entity\App",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $appGuid the app appGuid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function getAppAction($appGuid)
    {
        $client   = $this->get('guzzle.client.ws_pusher');
        $response = $client->get('apps/' . $appGuid);

        return json_decode($response->getBody(), true);
    }

    /**
     * Delete single App Token.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Deletes an App Token for a given appGuid and tokenKey",
     *     output = "AppBundle\Entity\App",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $appGuid the app appGuid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function deleteAppTokenAction($appGuid, $tokenKey)
    {
        $client   = $this->get('guzzle.client.ws_pusher');
        $response = $client->delete('apps/' . $appGuid . '/tokens/' . $tokenKey);

        return json_decode($response->getBody(), true);
    }

    /**
     * Delete single App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Deletes an App Token for a given appGuid",
     *     output = "AppBundle\Entity\App",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $appGuid the app appGuid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function deleteAppAction($appGuid)
    {
        $client   = $this->get('guzzle.client.ws_pusher');
        $response = $client->delete('apps/' . $appGuid);

        return json_decode($response->getBody(), true);
    }

    /**
     * Creates a new App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Gets Tokens for a given App",
     *     output = "AppBundle\Entity\App",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         500 = "Returned when the app cannot be added"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $appGuid the app appGuid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function postAppAction()
    {
        $content = $this->get("request")->getContent();

        if (!empty($content))
        {
            $params = json_decode($content); // 2nd param to get as array
        }

        if (!property_exists($params, 'slug')) {
            //TODO throw error
            die('NO SLUG');
        }

        $client = $this->get('guzzle.client.ws_pusher');

        $response = $client->request('POST', '/apps', [
            'json' => [
                'slug' => $params->slug,
            ]   
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * Get tokens for a given App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Gets Tokens for a given App",
     *     output = "AppBundle\Entity\Token",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $appGuid the app appGuid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function postAppTokensAction($appGuid)
    {
        $client = $this->get('guzzle.client.ws_pusher');

        $response = $client->request('POST', '/apps/' . $appGuid . '/tokens');

        return json_decode($response->getBody(), true);
    }
}