<?php

namespace App\Services\Game;

use App\Models\Game\Level;
use App\Models\Game\Level\Rating;
use App\Models\Game\Level\SharedCreatorPoint;
use App\Models\Game\UserScore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OptimizeService
{
    public function run(): array
    {
        return [
            $this->removeRatingsWithoutLevel(),
            $this->reCalculateCreatorPoints()
        ];
    }

    public function removeRatingsWithoutLevel()
    {
        return Rating::whereDoesntHave('level')->delete();
    }

    public function reCalculateCreatorPoints(): bool
    {
        try {
            DB::transaction(function () {
                UserScore::query()
                    ->where('creator_points', '!=', 0)
                    ->update(['creator_points' => 0]);

                Level::has('rating')
                    ->get()
                    ->map(function (Level $level) {
                        if (!$level->getRelationValue('user.score')) {
                            return false;
                        }

                        $creator_points = 0;
                        if ($level->rating->stars > 0) {
                            $creator_points += config('game.creator_points_count.rated', 1);
                        }

                        if ($level->rating->featured_score > 0) {
                            $creator_points += config('game.creator_points_count.featured', 2);
                        }

                        if ($level->rating->epic) {
                            $creator_points += config('game.creator_points_count.epic', 4);
                        }

                        $level->shared_creator_points()
                            ->get()
                            ->map(function (SharedCreatorPoint $data) use ($creator_points) {
                                if (!$data->getRelationValue('user.score')) {
                                    return false;
                                }

                                return $data->getRelationValue('user')
                                    ->score()
                                    ->increment('creator_points', $creator_points);
                            });

                        $level->getRelationValue('user')
                            ->score()
                            ->increment('creator_points', $creator_points);

                        return true;
                    });
            });

            return true;
        } catch (Throwable $e) {
            Log::channel('gdcn')
                ->notice("[Optimize System] Action: reCalculateCreatorPoints Failed", [
                    'reason' => $e->getMessage()
                ]);

            return false;
        }
    }
}
