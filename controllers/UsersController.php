<?php

namespace app\controllers;

use app\models\User;
use app\core\Request;
use app\core\Response;
use app\models\Demand;
use app\core\Controller;
use app\core\Application;
use app\core\middlewares\AuthMiddleware;
use app\core\middlewares\AdminMiddleware;
use app\core\Model;
use app\models\UserPasswordForm;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->registerMiddleware(new AuthMiddleware(['index']));
        $this->registerMiddleware(new AdminMiddleware(['index','createUser','updateUser','destroyUser','storeUser','editUser']));
        Application::$app->view->title = 'Kullanıcılar';
    }
    public function index()
    {
        $userlist = new User();
        $result = $userlist->select();
        $params = [
            'users' => $result
        ];
        return $this->render('users/index', $params);
    }
    private function validateForm(Model $model, Response $response)
    {
        $msg = $model->convertErrorMessagesToString();
        Application::$app->session->setErrorFlashMessage('İşlem iptal edildi.' . $msg);
        return $response->redirect('/users');
    }
    public function createUser()
    {
        $userEntity = new User();
        return $this->renderOnlyView('users/forms/createUser', ['model' => $userEntity]);
    }
    public function hasUserRelation($user, $param)
    {
        $demandEntity = new Demand();
        $isOwnerDemand = $user->isExist(["owner_id" => $param], $demandEntity->tableName());
        $isUnderTakeDemand = $user->isExist(["undertaking_id" => $param], $demandEntity->tableName());

        if ($isOwnerDemand || $isUnderTakeDemand) {
            Application::$app->session->setErrorFlashMessage('Kullanıcıyla ilişkili talep bulunmuştur. İşlem iptal edilmiştir.');
            return true;
        }
        return false;
    }
    public function destroyUser(Request $request)
    {
        $userEntity = new User();
        if ($request->isDelete() && Application::$app->isAdmin()) {
            $param = $request->params['id'];
            $demandEntity = new Demand();
            if($demandEntity->hasRelation(["owner_id" => $param,"undertaking_id" => $param])){
                return Application::$app->response->redirect('/users');
            }
            if ($userEntity->delete($param)) {
                Application::$app->session->setSuccessFlashMessage('Kullanıcı başarıyla silindi');
                return Application::$app->response->redirect('/users');
            }
        }
        Application::$app->session->setErrorFlashMessage('Bir hata ile karşılaşıldı');
        return Application::$app->response->redirect('/users');
    }
    public function storeUser(Request $request, Response $response)
    {
        $userEntity = new User();
        if ($request->isPost()) {
            $userEntity->loadData($request->getBody());
            $userEntity->password =  password_hash($userEntity->password, PASSWORD_DEFAULT);
            if (!$userEntity->validate() || !$userEntity->save()) {
                return $this->validateForm($userEntity, $response);
            }
            Application::$app->session->setSuccessFlashMessage('Kullanıcı başarılı şekilde kaydedildi.');
            return Application::$app->response->redirect('/users');
        }
        Application::$app->session->setErrorFlashMessage('Bir hata ile karşılaşıldı');
        return Application::$app->response->redirect('/users');
    }

    public function updateUser(Request $request, Response $response)
    {
        $userEntity = new User();
        if ($request->isPost()) {
            $userEntity->loadData($request->getBody());
            if (!$userEntity->validate() || !$userEntity->update()) {
                return $this->validateForm($userEntity, $response);
            }
            Application::$app->session->setSuccessFlashMessage('Kullanıcı başarılı şekilde güncellendi.');
            return Application::$app->response->redirect('/users');
        }
        Application::$app->session->setErrorFlashMessage('Bir hata ile karşılaşıldı');
        return Application::$app->response->redirect('/users');
    }
    public function editUser(Request $request)
    {
        $userEntity = new User();

        if ($request->isGet()) {
            $param = $request->params['id'];
            $result = $userEntity->where(['id' => $param]);
            return $this->renderOnlyView('users/forms/editUser', ['model' => $result]);
        }
        Application::$app->session->setErrorFlashMessage('Bir hata ile karşılaşıldı');
        return Application::$app->response->redirect('/users');
    }
    public function changeUserPassword(Request $request)
    {
        $userEntity = new UserPasswordForm();
        $param = $request->params['id'];
        $userEntity->id=$param;
        return $this->renderOnlyView('users/forms/changePassword', ['model' => $userEntity]);
    }
    public function changePassword(Request $request, Response $response)
    {
        $userEntity = new UserPasswordForm();
        $userEntity->loadData($request->getBody());
        $userItem = $userEntity->where(["id" => $userEntity->id]);
        if (!$userEntity->validate()) {
            return $this->validateForm($userEntity, $response);
        }
        $userEntity->password =  password_hash($userEntity->password, PASSWORD_DEFAULT);
        $result = $userItem->updateWhere([
            "password" => $userEntity->password
        ], [$userEntity->primaryKey() => $userEntity->id]);
        if ($result) {
            Application::$app->session->setSuccessFlashMessage('Parola başarıyla değiştirildi.');
            return Application::$app->response->redirect('/demands');
        }
        Application::$app->session->setErrorFlashMessage('Bir hata ile karşılaşıldı');
        return Application::$app->response->redirect('/demands');
    }
}
