<?php

namespace App\Console\Commands;

use App\User;
use Dingo\Api\Auth\Auth;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $userId=$this->ask('输入用户id');
        $user=User::find($userId);
        if (!$user){
            return $this->error('用户不存在');
        }
        //一年以后过期
        $ttl=365 * 60 * 60;
        $this->info(Auth::guard('api')->setTTL($ttl)->fromUser($user));
    }
}
