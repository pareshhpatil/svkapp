<?php

/**
 * Controls transaction listing for merchant
 * 
 */
class Comments extends Controller {

    function __construct() {
        parent::__construct();
        $this->validateSession('merchant');
    }

    public function view($link) {
        try {
            if ($link == NULL || $link == '') {
                $this->setGenericError();
                die();
            } else {
                $parent_id = $this->encrypt->decode($link);
                $list = $this->model->getCommentsList($parent_id);
                $int = 0;
                foreach ($list as $item) {
                    $list[$int]['link'] = $this->encrypt->encode($item['id']);
                    $int++;
                }
            }
            $this->smarty->assign("parent_id", $parent_id);
            $this->smarty->assign("list", $list);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/comments/list.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E1]Error while display comments Error: for merchant [' . $this->user_id . '] and for payment transaction id [' . $parent_id . ']' . $e->getMessage());
        }
    }

    public function save() {
        try {
            $this->model->saveComment($this->user_id, $_POST['parent_id'], $_POST['comment'], $this->session->get('user_name'));
            $link = $this->encrypt->encode($_POST['parent_id']);
            $this->session->set('successMessage', 'Comment have been saved.');
            header('Location: /merchant/comments/view/' . $link);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2]Error while update comments  Error: for merchant [' . $this->user_id . '] and for comment id [' . $_POST['id'] . ']' . $e->getMessage());
        }
    }

    public function updatesave() {
        try {
            $this->model->updateComment($this->user_id, $_POST['id'], $_POST['comment'], $this->session->get('user_name'));
            $link = $this->encrypt->encode($_POST['parent_id']);
            $this->session->set('successMessage', 'Comment have been saved.');
            header('Location: /merchant/comments/view/' . $link);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E2]Error while update comments  Error: for merchant [' . $this->user_id . '] and for comment id [' . $_POST['id'] . ']' . $e->getMessage());
        }
    }

    public function update($link) {
        try {
            $id = $this->encrypt->decode($link);
            $detail = $this->model->getCommentsDetail($id);
            $this->smarty->assign("id", $detail['id']);
            $this->smarty->assign("detail", $detail);
            $this->view->render('header/guest');
            $this->smarty->display(VIEW . 'merchant/comments/update.tpl');
            $this->view->render('footer/nonfooter');
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E4]Error while update comment Error: for merchant [' . $this->user_id . '] and for id [' . $id . ']' . $e->getMessage());
        }
    }

    public function delete($link) {
        try {
            $id = $this->encrypt->decode($link);
            $detail = $this->model->getCommentsDetail($id);
            $this->model->deleteComment($this->user_id, $id);
            $link = $this->encrypt->encode($detail['parent_id']);
            $this->session->set('successMessage', 'Comment have been deleted.');
            header('Location: /merchant/comments/view/' . $link);
        } catch (Exception $e) {
Sentry\captureException($e);
            
SwipezLogger::error(__CLASS__, '[E5]Error while delete comments  Error: for merchant [' . $this->user_id . '] and for comment id [' . $id . ']' . $e->getMessage());
        }
    }

}
