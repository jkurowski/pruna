<?php
class Admin_InwestycjePlanController extends kCMS_Admin
{

    public function preDispatch() {
        $array = array(
            'controlname' => 'Plan inwestycji'
        );
        $this->view->assign($array);
    }

// Formularz
    public function addAction() {
        $id = (int)$this->getRequest()->getParam('id');

        $investmentModel = new Model_InvestmentModel();
        $investment = $investmentModel->getById($id);

        $planModel = new Model_PlanModel();
        $investmentPlan = $planModel->fetchRow($planModel->select()->where('id_inwest =?', $id));

        $array = array(
            'inwestycja' => $investment,
            'plan' => $investmentPlan,
            'planszerokosc' => $planModel::IMG_WIDTH,
            'planwysokosc' => $planModel::IMG_HEIGHT
        );
        $this->view->assign($array);
    }

// Zmiana planu inwestycji
    public function uploadAction()
    {
        $db = Zend_Registry::get('db');
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $id = (int)$this->getRequest()->getParam('id');

        $investmentModel = new Model_InvestmentModel();
        $investment = $investmentModel->getById($id);

        $planModel = new Model_PlanModel();
        $investmentPlan = $planModel->fetchRow($planModel->select()->where('id_inwest =?', $id));

        if ($investmentPlan->plik) {
            unlink(FILES_PATH . "/inwestycje/plan/" . $investmentPlan->plik);
            $db->delete('inwestycje_plan', 'id_inwest = ' . $id);
        }

        $obrazek = $_FILES['qqfile']['name'];
        if ($_FILES['qqfile']['size'] > 0) {

            $fileName = date('mdhis') . '-' . slugImg($investment->nazwa, $obrazek);

            if (move_uploaded_file($_FILES['qqfile']['tmp_name'], FILES_PATH . '/inwestycje/plan/' . $fileName)) {
                $file = FILES_PATH . '/inwestycje/plan/' . $fileName;
                chmod($file, 0755);

                $db->insert('inwestycje_plan', array('id_inwest' => $id, 'plik' => $fileName));

                $response = array("success" => true);
                header("Content-Type: text/plain");
                echo Zend_Json::encode($response);
            }
        }
    }
}
