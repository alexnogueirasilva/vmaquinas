<?php

namespace Source\Models\Activity;

use Source\Core\Model;
use Source\Models\User;

/**
 * @property null $tipo_id
 * @property null $user_id
 * @property null $descricao
 * @property null $titulo
 * @property null $id
 * @property null $status
 */
class Activity extends Model
{

    public function __construct()
    {
        parent::__construct("atividades", ["id"], ["titulo", "descricao", "tipo_id"]);
    }


    public function types(): Type
    {
        return (new Type())->findById($this->tipo_id);
    }

    public function filter(User $user, ?string $type, ?array $filter, ?int $limit = null)
    {
        $category = (!empty($filter["category"]) && $filter["category"] != "all" ? "AND tipo_id = '{$filter["category"]}'" : null);
        $status = (!empty($filter["status"]) && $filter["status"] == "open" ? "AND status = 'open'" : (!empty($filter["status"]) && $filter["status"] == "closed" ? "AND status = 'closed'" : null));

        $due_year = (!empty($filter["date"]) ? explode("-", $filter["date"])[1] : date("Y"));
        $due_month = (!empty($filter["date"]) ? explode("-", $filter["date"])[0] : date("m"));
        $due_at = "AND (year(created_at) = '{$due_year}' AND month(created_at) = '{$due_month}')";

        $due = $this->find(
            "user_id = :user {$status} {$category} {$due_at}",
            "user={$user->id}"
        )->order("day(created_at) ASC");

        if ($limit) {
            $due->limit($limit);
        }

        return $due->fetch(true);
    }

    public function category():Type
    {
        return (new Type())->findById($this->tipo_id);
    }
}
