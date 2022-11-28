<?php

class Promotion extends Controller {

    function __construct() {
        parent::__construct();
    }

//     function august() {
//         try {
//             $promotion_details = $this->common->getSingleValue('promotion', 'id', 1);
//             $this->smarty->assign("details", $promotion_details);
//             $this->view->title = 'Promotion August';
//             $this->view->canonical = $_GET['url'];
//             $this->view->render('header/guest');
//             $this->smarty->display(VIEW . 'promotion/august.tpl');
//             $this->view->render('footer/footer');
//         } catch (Exception $e) {
// Sentry\captureException($e);
            
// SwipezLogger::error(__CLASS__, '[E003]Error while promotion august' . $e->getMessage());
//             $this->setGenericError();
//         }
//     }

}
