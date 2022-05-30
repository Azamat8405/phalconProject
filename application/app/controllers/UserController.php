<?php
declare(strict_types=1);


//use Phalcon\Mvc\Model;
use App\Models\User;
use Phalcon\Mvc\View;
use Phalcon\Db\Column;
use Phalcon\Paginator\Adapter\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class UserController extends ControllerBase
{
    /**
     * @SWG\Path(
     *   path="/user/create",
     *   summary="It creates user",
     *   operationId="createUser",
     *   @SWG\Post(
     *      description="It creates user",
     *      tags={"User"},
     *      @SWG\Response(
     *          response=200,
     *          description="User created successfully",
     *          @SWG\Schema(ref="#definitions/User")
     *      )
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     type="string",
     *     name="firstName",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     type="string",
     *     name="secondName",
     *     required=false
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     type="string",
     *     name="patronymic",
     *     required=false
     *   )
     * );
     *
     * @return string
     */
    public function createAction()
    {
        $this->view->disable();

        $firstName = $this->request->get('firstName');
        try{
            if ($firstName == '') {
                throw new Exception('There is no "firstName". It is required.');
            }

            $secondName = $this->request->get('secondName');
            $patronymic = $this->request->get('patronymic');

            $curDate = new \DateTime();
            $user = new User();
            $user->first_name = $firstName;
            $user->second_name = $secondName;
            $user->patronymic = $patronymic;
            $user->created_at = $curDate->format(DateTimeInterface::ATOM);
            $result = $user->create();

            if (false === $result) {
                throw new Exception(implode('; ', $user->getMessages()));
            }
            $response = $user;

        } catch(Exception $e){
            $response = [
                'error_code'    => 1,
                'error_message' => $e->getMessage()
            ];
        }

        return $this->response->setJsonContent($response);
    }

    /**
     * @SWG\Path(
     *   path="/user/read",
     *   summary="It reads user list",
     *   operationId="readUsers",
     *   @SWG\Get(
     *      description="It reads user list",
     *      tags={"User"},
     *      @SWG\Response(
     *          response=200,
     *          description="User list",
     *          @SWG\Schema(ref="#definitions/User")
     *      )
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     type="integer",
     *     name="perPage",
     *     required=false
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     type="integer",
     *     name="curPage",
     *     required=false
     *   )
     * );
     *
     * @return string
     */
    public function readAction()
    {
        $this->view->disable();

        $perPage = (int)$this->request->get('perPage') ?? 20;
        $curPage = (int)$this->request->get('curPage') ?? 1;

        $offset = (--$curPage * $perPage);

        $builder = $this->modelsManager->createBuilder();

        $users = $builder
                    ->columns('id, first_name, second_name, patronymic, created_at')
                    ->from(User::class)
                    ->orderBy('id')
                    ->limit($perPage)
                    ->offset($offset)
                    ->getQuery()
                    ->execute();

        return $this->response->setJsonContent( $users );
    }


    /**
     * @SWG\Path(
     *   path="/user/update",
     *   summary="It updates user",
     *   operationId="updateUser",
     *   @SWG\Post(
     *      description="It updates user",
     *      tags={"User"},
     *      @SWG\Response(
     *          response=200,
     *          description="User updated successfully",
     *          @SWG\Schema(ref="#definitions/User")
     *      )
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     type="integer",
     *     name="id",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     type="string",
     *     name="firstName",
     *     required=true
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     type="string",
     *     name="secondName",
     *     required=false
     *   ),
     *   @SWG\Parameter(
     *     in="formData",
     *     type="string",
     *     name="patronymic",
     *     required=false
     *   )
     * );
     *
     * @return string
     */
    public function updateAction()
    {
        $this->view->disable();

        $id         = (int)$this->request->get('id') ?? 0;
        $firstName  = $this->request->get('firstName') ?? '';
        $secondName = $this->request->get('secondName') ?? '';
        $patronymic = $this->request->get('patronymic') ?? '';

        $curDate = new \DateTime();

        try{
            $user = User::findFirst('id = ' . $id);
            if (!$user) {
                throw new Exception('There is no such user.');
            }
            $user->first_name = $firstName;
            $user->second_name = $secondName;
            $user->patronymic = $patronymic;
            $user->created_at = $curDate->format(DateTimeInterface::ATOM);
            $result = $user->update();

            if (false === $result) {
                throw new Exception(implode('; ', $user->getMessages()));
            }
            $response = $user;
        } catch(Exception $e){
            $response = [
                'error_code'    => 1,
                'error_message' => $e->getMessage()
            ];
        }

        return $this->response->setJsonContent($response);
    }

    /**
     * @SWG\Path(
     *   path="/user/delete",
     *   summary="It deletes user",
     *   operationId="deleteUser",
     *   @SWG\Get(
     *      description="It deletes user",
     *      tags={"User"},
     *      @SWG\Response(
     *          response=200,
     *          description="User deleted successfully",
     *          @SWG\Schema(ref="#definitions/User")
     *      )
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     type="integer",
     *     name="id",
     *     required=true
     *   )
     * );
     *
     * @return string
     */
    public function deleteAction()
    {
        $this->view->disable();

        $id = (int)$this->request->get('id') ?? 0;
        try{
            $user = User::findFirst('id = '.$id);
            if($user)
            {
                $user->delete();
                $response = ['deleted'];
            } else {
                throw new Exception('Something wrong happened!');
            }
        } catch(Exception $e){

            $response = [
                'error_code'    => 1,
                'error_message' => $e->getMessage()
            ];
        }

        return $this->response->setJsonContent($response);
    }

    /**
     * @SWG\Path(
     *   path="/user/search",
     *   summary="Search user",
     *   operationId="searchUser",
     *   @SWG\Get(
     *      description="Search user",
     *      tags={"User"},
     *      @SWG\Response(
     *          response=200,
     *          description="",
     *          @SWG\Schema(ref="#definitions/User")
     *      )
     *   ),
     *   @SWG\Parameter(
     *     in="query",
     *     type="string",
     *     name="query",
     *     required=true
     *   )
     * );
     *
     * @return string
     */
    public function searchAction()
    {
        $this->view->disable();
        $query = $this->request->get('query');

        $response = $this->di->get('db')->query(
            "SELECT first_name, second_name, patronymic, created_at FROM \"user\" WHERE
                    to_tsvector('russian', coalesce(first_name,'') || ' ' || coalesce(second_name,'') || ' ' || coalesce(patronymic,''))
                    @@
                    plainto_tsquery(?)",
                [
                    $query
                ]
        );

        return $this->response->setJsonContent($response->fetchAll());
    }
}

