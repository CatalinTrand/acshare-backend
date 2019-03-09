<?php
/**
 * Created by PhpStorm.
 * User: Catalin
 * Date: 09/03/2019
 * Time: 14:53
 */

namespace App;


class File
{
    protected $table = 'files';

    protected $fillable = [
        "name",
        "owner_id" ,
        "shared_with"
    ];

    protected $hidden = [
      "created_at",
      "updated_at"
    ];
}