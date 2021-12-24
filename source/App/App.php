<?php

namespace Source\App;

use DateTime;
use Source\Core\Controller;
use Source\Core\Session;
use Source\Core\View;
use Source\Models\Activity\Activity;
use Source\Models\Activity\Type;
use Source\Models\Auth;
use Source\Models\CafeApp\AppCategory;
use Source\Models\CafeApp\AppInvoice;
use Source\Models\CafeApp\AppWallet;
use Source\Models\Post;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Email;
use Source\Support\Message;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * Class App
 * @package Source\App
 */
class App extends Controller
{
    /** @var User */
    private $user;

    /**
     * App constructor.
     */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");

        if (!$this->user = Auth::user()) {
            $this->message->warning("Efetue login para acessar o APP.")->flash();
            redirect("/entrar");
        }

        //UNCONFIRMED EMAIL
        if ($this->user->status != "confirmed") {
            $session = new Session();
            if (!$session->has("appconfirmed")) {
                $this->message->info("IMPORTANTE: Acesse seu e-mail para confirmar seu cadastro e ativar todos os recursos.")->flash();
                $session->set("appconfirmed", true);
                (new Auth())->register($this->user);
            }
        }
    }


    /**
     * @return void
     */
    public function home(): void
    {
        $head = $this->seo->render(
            "Olá {$this->user->first_name}. Vamos controlar? - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        //INCOME && EXPENSE

        $whereWallet = "";
        if ((new Session())->has("walletfilter")) {
            $whereWallet = "AND wallet_id = " . (new Session())->walletfilter;
        }

        $income = (new Activity())
            ->find("user_id = :user AND status = 'open' ",
                "user={$this->user->id}")
            ->order("titulo")
            ->fetch(true);

        $expense = (new Activity())
            ->find("user_id = :user AND status = 'closed' ",
                "user={$this->user->id}")
            ->order("titulo")
            ->fetch(true);
        //END INCOME && EXPENSE

        echo $this->view->render("home", [
            "head" => $head,
            "income" => $income,
            "expense" => $expense,
        ]);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function filter(array $data): void
    {

        $status = (!empty($data["status"]) ? $data["status"] : "all");
        $category = (!empty($data["category"]) ? $data["category"] : "all");
        $date = (!empty($data["date"]) ? $data["date"] : date("m/Y"));

        list($m, $y) = explode("/", $date);
        $m = ($m >= 1 && $m <= 12 ? $m : date("m"));
        $y = ($y <= date("Y", strtotime("+10year")) ? $y : date("Y", strtotime("+10year")));

        $redirect = ($data["filter"] == "closed" ? "aberto" : "atividades");
        $json["redirect"] = url("/app/{$redirect}/{$status}/{$category}/{$m}-{$y}");
        echo json_encode($json);

    }

    /**
     * @param array|null $data
     */
    public function income(?array $data): void
    {
        $head = $this->seo->render(
            "Minhas receitas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $status = (new Activity())->find("status = :status', 'status='open'")->fetch();
        $paid = 0;
        $categories = (new Type())
            ->find()
            ->fetch(true);

        echo $this->view->render("invoices", [
            "user" => $this->user,
            "head" => $head,
            "type" => "income",
            "categories" => $categories,
            "status" => $status,
            "invoices" => (new AppInvoice())->filter($this->user, "open", ($data ?? null)),
            "filter" => (object)[
                "status" => ($data["status"] ?? null),
                "category" => ($data["category"] ?? null),
                "date" => (!empty($data["date"]) ? str_replace("-", "/", $data["date"]) : null)
            ]
        ]);
    }

    /**
     * @param array|null $data
     */
    public function expense(?array $data): void
    {
        $head = $this->seo->render(
            "Minhas despesas - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $paid = 0;
        $status = (new Activity())->find("status = :status', 'status='open'")->fetch();
        $categories = (new Type())
            ->find()
            ->fetch("true");

        echo $this->view->render("invoices", [
            "user" => $this->user,
            "head" => $head,
            "type" => "expense",
            "categories" => $categories,
            "status" => $status,
            "invoices" => (new Activity())->filter($this->user, "closed", ($data ?? null)),
            "filter" => (object)[
                "status" => ($data["status"] ?? null),
                "category" => ($data["category"] ?? null),
                "date" => (!empty($data["date"]) ? str_replace("-", "/", $data["date"]) : null)
            ]
        ]);
    }

    /**
     * @param array $data
     */
    public function launch(array $data): void
    {

        if (request_limit("applaunch", 20, 60 * 5)) {
            $json["message"] = $this->message->warning("Foi muito rápido {$this->user->first_name}! Por favor aguarde 5 minutos para novos lançamentos.")->render();
            echo json_encode($json);
            return;
        }

        if (request_repeti("description", $data["description"])) {
            $json["message"] = $this->message->info("Já recebemos sua solicitação {$this->user->first_name}, agradecemos o contato")->render();
            echo json_encode($json);
            return;
        }

        if (!empty($data["type_activity"]) == 4 && $this->deadline() === 'Sexta' && $this->time() <= '01:00:00 AM') {
            $json["message"] = $this->message->warning("Whoops {$this->user->first_name}, depois das 13:00 horas da sexta não se pode abri chamado com o status urgente")->render();
            echo json_encode($json);
            return;
        }

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);


        $activity = (new Activity());
        $activity->user_id = $this->user->id;
        $activity->tipo_id = $data["type_activity"];
        $activity->titulo = $data["title"];
        $activity->descricao = $data["description"];

        if (!$activity->save()) {
            $json["message"] = $activity->message()->before("Whoops! ")->render();
            echo json_encode($json);
            return;
        }

        if ($activity) {
            $this->message->success("Chamado aberto com sucesso {$this->user->first_name}. Use o filtro para controlar.")->flash();
        }

        $json["reload"] = true;
        echo json_encode($json);
    }

    /**
     * @param array $data
     * @return void
     */
    public function onpaid(array $data): void
    {
        $activity = (new Activity())
            ->find("user_id = :user AND id = :id", "user={$this->user->id}&id={$data["invoice"]}")
            ->fetch();

        if (!$activity) {
            $this->message->error("Whoops, ocorreu um error ao atualizar :/")->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }

        $activity->status = ($activity->status == "open" ? "closed" : "open");
        $activity->save();

    }

    /**
     * @param array $data
     */
    public function invoice(array $data)
    {

        if (!empty($data["update"])) {
            $activity = (new Activity())->find("user_id = :user AND id = :id",
                "user={$this->user->id}&id={$data["invoice"]}")->fetch();

            if (!$activity) {
                $json["message"] = $this->message->error("Whoops, Não foi possível carregar a fatura {$this->user->first_name}. você pode tentar novamente.")->render();
                echo json_encode($json);
                return;
            }

            if (!empty($data["type_activity"]) == 4 && $this->deadline() === 'Sexta' && $this->time() <= '01:00:00 AM') {
                $json["message"] = $this->message->warning("Whoops {$this->user->first_name}, depois das 13:00 horas da sexta não se pode abri chamado com o status urgente")->render();
                echo json_encode($json);
                return;
            }

          
            $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);

            $activity->tipo_id =  $data["category"];
            $activity->titulo = $data["title"];
            $activity->descricao = $data["description"];
            $activity->status = $data["status"];
        
            if (!$activity->save()) {
                $json["message"] = $activity->message()->before("Whoops! ")->after(" {$this->user->first_name}.")->render();
                echo json_encode($json);
                return;
            }


            $json["message"] = $this->message->success("Pronto {$this->user->first_name}, a atualização foi feita com sucesso!")->render();
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Aluguel - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        $invoice = (new Activity())->find("user_id = :user AND id = :invoice",
            "user={$this->user->id}&invoice={$data["invoice_id"]}"
        )->fetch();

        if (!$invoice) {
            var_dump($invoice);die;
            $this->message->error("Whoops, você tentou acessar uma fatura que não existe!")->flash();
            redirect("/app");
        }

        echo $this->view->render("invoice", [
            "head" => $head,
            "invoice" => $invoice,
            "categories" => (new Type())
                ->find()
                ->fetch(true)
        ]);
    }

    /**
     * @param array $data
     * @return void
     */
    public function remove(array $data): void
    {
        $invoice = (new Activity())->find("user_id = :user AND id = :invoice",
            "user={$this->user->id}&invoice={$data["invoice"]}")
            ->fetch();

        if ($invoice) {
            $invoice->destroy();
        }

        $this->message->success("Tudo pronto {$this->user->first_name}, o lançamento removido com sucesso!")->flash();
        $json["redirect"] = url("/app");
        echo json_encode($json);
    }

    /**
     * @param array|null $data
     * @return void
     */
    public function profile(?array $data)
    {
        if (!empty($data["update"])) {
            list($d, $m, $y) = explode("/", $data["datebirth"]);
            $user = (new User())->findById($this->user->id);
            $user->first_name = $data["first_name"];
            $user->last_name = $data["last_name"];
            $user->genre = $data["genre"];
            $user->datebirth = "{$y}-{$m}-{$d}";
            $user->document = preg_replace("/[^0-9]/", "", $data["document"]);

            if (!empty($_FILES["photo"])) {
                $file = $_FILES["photo"];
                $upload = new Upload();

                if ($this->user->photo()) {
                    (new Thumb())->flush("storage/{$this->user->photo}");
                    $upload->remove("storage/{$this->user->photo}");
                }

                if (!$user->photo = $upload->image($file, "{$user->first_name} {$user->last_name}" . time(), 360)) {
                    $json["message"] = $upload->message()->before("Whoops {$this->user->first_name}")->after(".")->render();
                    echo json_encode($json);
                    return;
                }
            }

            if (!empty($data["password"])) {
                if (empty($data["password_re"]) || $data["password"] != $data["password_re"]) {
                    $json["message"] = $this->message->warning("Para alterar sua senha, informe e repita a nova senha")->render();
                    echo json_encode($json);
                    return;
                }
                $user->password = $data["password"];
            }

            if (!$user->save()) {
                $json["message"] = $user->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success("Pronto {$this->user->first_name}. Seus dados foram atualizados com sucesso! ")->render();
            echo json_encode($json);
            return;
        }

        $head = $this->seo->render(
            "Meu perfil - " . CONF_SITE_NAME,
            CONF_SITE_DESC,
            url(),
            theme("/assets/images/share.jpg"),
            false
        );

        echo $this->view->render("profile", [
            "head" => $head,
            "user" => $this->user,
            "photo" => ($this->user->photo() ? image($this->user->photo, 360, 360) :
                theme("/assets/images/avatar.jpg", CONF_VIEW_APP))
        ]);
    }

    /**
     * APP LOGOUT
     */
    public function logout()
    {
        (new Message())->info("Você saiu com sucesso " . Auth::user()->first_name . ". Volte logo :)")->flash();
        $session = new Session();
        $session->unset("appconfirmed");
        Auth::logout();
        redirect("/entrar");
    }

   public function deadline()
    {
        $weekDay = array('Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sabado');
        $date = date('Y-m-d');
        $weekDayNumber = date('w', strtotime($date));
        return $weekDay[$weekDayNumber];
    }

    public function time()
    {
       return $time = date('h:i:s A');

    }
}
