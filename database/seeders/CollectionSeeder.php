<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class CollectionSeeder extends Seeder
{
    public function run(User $ada, User $grace): void
    {
        // Grab recipe IDs by title so order doesn't matter
        $carbonara  = Recipe::where('title', 'Spaghetti Carbonara')->first();
        $avocado    = Recipe::where('title', 'Avocado Toast with Poached Eggs')->first();
        $caesar     = Recipe::where('title', 'Caesar Salad')->first();
        $shakshuka  = Recipe::where('title', 'Shakshuka')->first();
        $onionSoup  = Recipe::where('title', 'French Onion Soup')->first();
        $tacos      = Recipe::where('title', 'Beef Tacos')->first();
        $lava       = Recipe::where('title', 'Chocolate Lava Cake')->first();
        $risotto    = Recipe::where('title', 'Mushroom Risotto')->first();

        // ── Ada's collections ──────────────────────────────────────────────

        // Public collection with multiple recipes
        $adaWeeknight = Collection::create([
            'user_id'     => $ada->id,
            'name'        => 'Weeknight Staples',
            'description' => 'Quick, reliable recipes for busy evenings.',
            'is_public'   => true,
        ]);

        $adaWeeknight->recipes()->attach([
            $carbonara->id => ['order' => 0],
            $avocado->id   => ['order' => 1],
            $shakshuka->id => ['order' => 2],
        ]);

        // Private collection
        $adaBreakfasts = Collection::create([
            'user_id'     => $ada->id,
            'name'        => 'Breakfast Experiments',
            'description' => 'Things I am testing on weekend mornings.',
            'is_public'   => false,
        ]);

        $adaBreakfasts->recipes()->attach([
            $avocado->id   => ['order' => 0],
            $shakshuka->id => ['order' => 1],
        ]);

        // Empty collection — tests zero-recipe edge case
        Collection::create([
            'user_id'     => $ada->id,
            'name'        => 'To Try',
            'description' => 'Recipes bookmarked but not yet cooked.',
            'is_public'   => false,
        ]);

        // ── Grace's collections ────────────────────────────────────────────

        // Public collection with Grace's own recipes
        $graceComfort = Collection::create([
            'user_id'     => $grace->id,
            'name'        => 'Comfort Food',
            'description' => 'The ones you make when you need a hug.',
            'is_public'   => true,
        ]);

        $graceComfort->recipes()->attach([
            $onionSoup->id => ['order' => 0],
            $risotto->id   => ['order' => 1],
            $lava->id      => ['order' => 2],
        ]);

        // Cross-user collection — Grace saves Ada's recipes
        // Tests that recipe ownership ≠ collection ownership
        $graceSaved = Collection::create([
            'user_id'     => $grace->id,
            'name'        => 'Ada\'s Best',
            'description' => 'Stolen inspiration from a fellow cook.',
            'is_public'   => false,
        ]);

        $graceSaved->recipes()->attach([
            $carbonara->id => ['order' => 0],  // Ada's recipe in Grace's collection
            $caesar->id    => ['order' => 1],
            $shakshuka->id => ['order' => 2],
        ]);

        $this->command->info('Collections seeded.');
    }
}
