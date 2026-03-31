<?php

namespace App\Console\Commands;

use App\Domains\Identity\RepositoryInterface\IUserRepository;
use App\Domains\Identity\Services\UserStatusService;
use App\Domains\Identity\Enums\UserStatus;
use Illuminate\Console\Command;

class MarkInactiveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:mark-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark users as INACTIVE after 30 days of no login';

    /**
     * Execute the console command.
     */
    public function handle(IUserRepository $userRepository, UserStatusService $statusService)
    {
        $this->info('Checking for inactive users...');

        // In a real scenario, we might want a specific repository method to get potential candidates
        // to avoid loading all users into memory. For now, we'll iterate through active ones.
        // For optimization, we only fetch users who are currently ACTIVE.
        
        $count = 0;
        
        // We use a simple loop or chunking if needed. 
        // For the sake of this implementation, we'll use the repository filter.
        $paginator = $userRepository->filter(['status' => UserStatus::ACTIVE], [], null, 100);

        foreach ($paginator as $user) {
            if ($statusService->syncInactivityStatus($user)) {
                $userRepository->save($user);
                $count++;
            }
        }

        $this->info("Successfully marked {$count} users as INACTIVE.");
    }
}
