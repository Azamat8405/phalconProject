<?php

namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * @SWG\Definition(
 *     required={"firstName"},
 *     type="object",
 *     @SWG\Xml(name="User")
 *  )
 */
class User extends Model
{
    public ?int $id = null;

    public string $first_name;

    public ?string $second_name;

    public ?string $patronymic;

    public $created_at;
}
