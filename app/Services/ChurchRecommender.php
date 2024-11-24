<?php

namespace App\Services;

use App\Models\User;
use App\Models\Church;
use Carbon\Carbon;
use Phpml\Math\Distance\Euclidean;

class ChurchRecommender
{
    private $churches;
    private $distanceMetric;

    public function __construct()
    {
        $this->churches = Church::all();
        $this->distanceMetric = new Euclidean();
    }

    public function recommend(User $user, $limit = 5)
    {
        $scores = [];
        foreach ($this->churches as $church) {
            if ($church->id !== $user->church_id) {
                $scores[$church->id] = $this->calculateSimilarity($user, $church);
            }
        }

        arsort($scores);
        $recommendedChurchIds = array_slice(array_keys($scores), 0, $limit);
        return Church::whereIn('id', $recommendedChurchIds)->get();
    }

    private function calculateSimilarity(User $user, Church $church)
    {
        $userVector = $this->createUserVector($user);
        $churchVector = $this->createChurchVector($church);

        return 1 / (1 + $this->distanceMetric->distance($userVector, $churchVector));
    }

    private function createUserVector(User $user)
    {
        $birthYear = $user->date_of_birth ? Carbon::parse($user->date_of_birth)->year : null;
        
        return [
            $birthYear,
            $user->gender === 'male' ? 1 : 0,
            $user->marital_status === 'married' ? 1 : 0,
            $user->baptized ? 1 : 0,
            $user->ministry_id,
        ];
    }

    private function createChurchVector(Church $church)
    {
        $users = $church->users;
        $totalUsers = $users->count();
        
        if ($totalUsers === 0) {
            return [null, 0, 0, 0, 0];
        }

        $averageBirthYear = $users->avg(function ($user) {
            return $user->date_of_birth ? Carbon::parse($user->date_of_birth)->year : null;
        });

        return [
            $averageBirthYear,
            $users->where('gender', 'male')->count() / $totalUsers,
            $users->where('marital_status', 'married')->count() / $totalUsers,
            $users->where('baptized', true)->count() / $totalUsers,
            1,
            //$church->ministries()->count(),
        ];
    }
}