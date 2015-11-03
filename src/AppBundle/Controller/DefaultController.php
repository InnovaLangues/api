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
        $client = $this->get('app.client');
        $request = $client->createRequest('GET', 'apps');
        $response = $client->send($request);
        return $response->json();
    }

    /**
     * Get single App.
     *
     * @ApiDoc(
     *     resource = true,
     *     description = "Gets a App for a given guid",
     *     output = "AppBundle\Entity\App",
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the app is not found"
     *     }
     * )
     *
     * @Annotations\View() 
     * @param int $guid the app guid
     * @return array
     * @throws NotFoundHttpException when page not exist
     */
    public function getAppAction($guid)
    {
        $client = $this->get('app.client');
        $request = $client->createRequest('GET', 'apps/' . $guid);
        $response = $client->send($request);
        return $response->json();
    }

    
}
