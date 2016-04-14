<?php
date_default_timezone_set("America/Los_Angeles");

use Eloquent\Attributes\Immutability;

class DefaultsTestUser extends \Illuminate\Database\Eloquent\Model
{
    use Immutability;

    protected $immutable = ["id", "uuid", "email"];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $int1 = mt_rand(1262055681, 1262055681);
        $int2 = mt_rand(1262055681, 1262055681);

        $attributes = [
            "id"         => 1,
            "uuid"       => "b7b88005-c4e7-47f6-9303-c497358d76bf",
            "first_name" => "David",
            "last_name"  => "Peace",
            "account_id" => 27,
            "email"      => "foo@bar.com",
            "password"   => "suumefRespurAbraF8aten7Huswathed",
            "created_at" => date("Y-m-d H:i:s", $int1),
            "updated_at" => date("Y-m-d H:i:s", $int2),
            "deleted_at" => null
        ];
        
        $this->setRawAttributes($attributes);
    }
}