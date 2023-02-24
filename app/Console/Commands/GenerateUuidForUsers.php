<?php

namespace App\Console\Commands;

use App\Models\User\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateUuidForUsers extends Command
{

    protected $signature = 'user:generate-uuid';

    protected $description = 'Generates uuid for users with uuid NULL';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        $users = User::whereNull('uuid')->get();

        for($i = 0; $i < count($users); $i++){
            $users[$i]->uuid = Str::uuid()->toString();
            $users[$i]->save();
        }
    }
}
