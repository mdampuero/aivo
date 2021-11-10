<?php

//
//  Created by Mauricio Ampuero <mdampuero@gmail.com> on 2021.
//  Copyright © 2021.
//

namespace Aivo\ApiV1Bundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;

class AlbumsController extends FOSRestController
{   
    public function indexAction(Request $request){ 
        try{
            return $this->handleView($this->view($this->get('apiCall')->getAlbums($request->query->get('q', null)), Response::HTTP_OK));
		}catch (Exception $excepcion) {
			return $this->handleView($this->view(array('message'=>$excepcion->getMessage()), $excepcion->getCode()));
		}
        
    }


}