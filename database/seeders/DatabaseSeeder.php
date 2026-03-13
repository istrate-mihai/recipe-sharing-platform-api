<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ─────────────────────────────────────────────────────────
        $ada = User::create([
            'name'     => 'Ada Lovelace',
            'email'    => 'ada@example.com',
            'password' => Hash::make('password'),
            'avatar'   => 'AL',
            'bio'      => 'Mathematician & first programmer.',
        ]);

        $grace = User::create([
            'name'     => 'Grace Hopper',
            'email'    => 'grace@example.com',
            'password' => Hash::make('password'),
            'avatar'   => 'GH',
            'bio'      => 'Pioneer of computer programming.',
        ]);

        // ── Recipes ───────────────────────────────────────────────────────
        $recipes = [
            [
                'user_id'     => $ada->id,
                'title'       => 'Spaghetti Carbonara',
                'description' => 'A classic Roman pasta with crispy pancetta, eggs, and pecorino. Creamy without a drop of cream.',
                'category'    => 'pasta',
                'difficulty'  => 'medium',
                'prep_time'   => 10,
                'cook_time'   => 20,
                'likes_count' => 54,
                'ingredients' => [
                    ['amount' => '400g',  'name' => 'spaghetti'],
                    ['amount' => '150g',  'name' => 'pancetta or guanciale'],
                    ['amount' => '4',     'name' => 'egg yolks'],
                    ['amount' => '1',     'name' => 'whole egg'],
                    ['amount' => '80g',   'name' => 'pecorino romano, grated'],
                    ['amount' => '1 tsp', 'name' => 'black pepper, freshly cracked'],
                ],
                'steps' => [
                    'Bring a large pot of heavily salted water to a boil and cook spaghetti until al dente.',
                    'Fry pancetta in a pan over medium heat until crispy. Remove from heat.',
                    'Whisk egg yolks, whole egg, and pecorino together in a bowl. Season with black pepper.',
                    'Reserve 1 cup of pasta water before draining.',
                    'Add hot pasta to the pan with pancetta off the heat. Pour egg mixture over and toss quickly.',
                    'Add pasta water a splash at a time, tossing until glossy and creamy. Serve immediately.',
                ],
            ],
            [
                'user_id'     => $grace->id,
                'title'       => 'French Onion Soup',
                'description' => 'Slow-caramelised onions in a rich beef broth, topped with toasted bread and melted gruyère.',
                'category'    => 'soup',
                'difficulty'  => 'medium',
                'prep_time'   => 15,
                'cook_time'   => 75,
                'likes_count' => 41,
                'ingredients' => [
                    ['amount' => '1kg',      'name' => 'yellow onions, thinly sliced'],
                    ['amount' => '4 tbsp',   'name' => 'unsalted butter'],
                    ['amount' => '1 tbsp',   'name' => 'olive oil'],
                    ['amount' => '150ml',    'name' => 'dry white wine'],
                    ['amount' => '1.5L',     'name' => 'beef stock'],
                    ['amount' => '4 slices', 'name' => 'baguette, toasted'],
                    ['amount' => '120g',     'name' => 'gruyère, grated'],
                ],
                'steps' => [
                    'Melt butter with olive oil in a heavy pot over low heat. Add onions and a pinch of salt.',
                    'Cook onions for 45–60 minutes, stirring every 10 minutes, until deeply caramelised and golden brown.',
                    'Add white wine and scrape up any browned bits. Simmer 5 minutes.',
                    'Add beef stock and simmer for 20 minutes. Season to taste.',
                    'Ladle soup into oven-safe bowls. Top with toasted baguette and grated gruyère.',
                    'Broil until cheese is bubbling and golden. Serve immediately.',
                ],
            ],
            [
                'user_id'     => $ada->id,
                'title'       => 'Avocado Toast with Poached Eggs',
                'description' => 'Creamy smashed avocado on sourdough, topped with perfectly poached eggs and chilli flakes.',
                'category'    => 'breakfast',
                'difficulty'  => 'easy',
                'prep_time'   => 5,
                'cook_time'   => 10,
                'likes_count' => 37,
                'ingredients' => [
                    ['amount' => '2 slices', 'name' => 'sourdough bread'],
                    ['amount' => '1 large',  'name' => 'ripe avocado'],
                    ['amount' => '2',        'name' => 'eggs'],
                    ['amount' => '1 tbsp',   'name' => 'white wine vinegar'],
                    ['amount' => '½ tsp',    'name' => 'chilli flakes'],
                    ['amount' => '1',        'name' => 'lemon, juice only'],
                    ['amount' => 'to taste', 'name' => 'salt and black pepper'],
                ],
                'steps' => [
                    'Toast the sourdough until golden and crisp.',
                    'Scoop avocado into a bowl. Add lemon juice, salt, and pepper. Smash with a fork.',
                    'Bring a pot of water to a gentle simmer. Add vinegar.',
                    'Crack each egg into a small cup. Swirl the water and slide each egg in. Poach 3 minutes.',
                    'Spread avocado over toast. Top with drained poached eggs.',
                    'Finish with chilli flakes and a crack of black pepper.',
                ],
            ],
            [
                'user_id'     => $grace->id,
                'title'       => 'Beef Tacos',
                'description' => 'Crispy fried corn tortillas loaded with spiced ground beef, pickled onion, and fresh salsa.',
                'category'    => 'meat',
                'difficulty'  => 'easy',
                'prep_time'   => 20,
                'cook_time'   => 15,
                'likes_count' => 49,
                'ingredients' => [
                    ['amount' => '500g',  'name' => 'ground beef'],
                    ['amount' => '8',     'name' => 'small corn tortillas'],
                    ['amount' => '1 tsp', 'name' => 'ground cumin'],
                    ['amount' => '1 tsp', 'name' => 'smoked paprika'],
                    ['amount' => '½ tsp', 'name' => 'garlic powder'],
                    ['amount' => '1',     'name' => 'red onion, finely diced'],
                    ['amount' => '2',     'name' => 'limes'],
                    ['amount' => '1 cup', 'name' => 'fresh coriander, chopped'],
                    ['amount' => '2',     'name' => 'tomatoes, diced'],
                ],
                'steps' => [
                    'Mix red onion with lime juice and a pinch of salt. Set aside to quick-pickle.',
                    'Brown ground beef in a pan over high heat. Drain excess fat.',
                    'Add cumin, paprika, garlic powder, salt and pepper. Cook 2 more minutes.',
                    'Mix diced tomatoes with coriander and a squeeze of lime for salsa.',
                    'Warm tortillas in a dry pan until lightly charred on each side.',
                    'Fill tortillas with beef, pickled onion, and salsa. Serve with lime wedges.',
                ],
            ],
            [
                'user_id'     => $ada->id,
                'title'       => 'Caesar Salad',
                'description' => 'Crisp romaine, homemade anchovy dressing, and sourdough croutons. The real version.',
                'category'    => 'salad',
                'difficulty'  => 'easy',
                'prep_time'   => 15,
                'cook_time'   => 10,
                'likes_count' => 28,
                'ingredients' => [
                    ['amount' => '2 heads',  'name' => 'romaine lettuce, chopped'],
                    ['amount' => '4',        'name' => 'anchovy fillets'],
                    ['amount' => '1 clove',  'name' => 'garlic'],
                    ['amount' => '1',        'name' => 'egg yolk'],
                    ['amount' => '2 tbsp',   'name' => 'lemon juice'],
                    ['amount' => '1 tsp',    'name' => 'Dijon mustard'],
                    ['amount' => '80ml',     'name' => 'extra virgin olive oil'],
                    ['amount' => '40g',      'name' => 'parmesan, finely grated'],
                    ['amount' => '2 slices', 'name' => 'sourdough, torn and toasted'],
                ],
                'steps' => [
                    'Crush anchovies and garlic into a paste using the back of a knife.',
                    'Whisk together anchovy paste, egg yolk, lemon juice, and mustard.',
                    'Slowly drizzle in olive oil while whisking to emulsify into a creamy dressing.',
                    'Toss torn sourdough in olive oil and toast in a pan until golden.',
                    'Toss romaine with dressing until every leaf is coated.',
                    'Top with croutons and a generous shower of parmesan.',
                ],
            ],
            [
                'user_id'     => $grace->id,
                'title'       => 'Chocolate Lava Cake',
                'description' => 'Warm dark chocolate cake with a molten centre. Eight minutes in the oven, unforgettable.',
                'category'    => 'dessert',
                'difficulty'  => 'medium',
                'prep_time'   => 15,
                'cook_time'   => 12,
                'likes_count' => 67,
                'ingredients' => [
                    ['amount' => '170g',   'name' => 'dark chocolate (70%), chopped'],
                    ['amount' => '115g',   'name' => 'unsalted butter'],
                    ['amount' => '2',      'name' => 'whole eggs'],
                    ['amount' => '2',      'name' => 'egg yolks'],
                    ['amount' => '100g',   'name' => 'caster sugar'],
                    ['amount' => '2 tbsp', 'name' => 'plain flour'],
                    ['amount' => 'pinch',  'name' => 'sea salt'],
                ],
                'steps' => [
                    'Preheat oven to 220°C. Butter four ramekins and dust with cocoa powder.',
                    'Melt chocolate and butter together over a bain-marie. Stir until smooth.',
                    'Whisk eggs, yolks, and sugar until pale and thick, about 3 minutes.',
                    'Fold chocolate mixture into egg mixture. Sift in flour and salt. Fold gently.',
                    'Divide batter between ramekins. Refrigerate up to 24 hours, or bake immediately.',
                    'Bake 10–12 minutes until edges are set but centre still wobbles. Invert onto plates and serve.',
                ],
            ],
            [
                'user_id'     => $ada->id,
                'title'       => 'Shakshuka',
                'description' => 'Eggs poached in a spiced tomato and pepper sauce. One pan, deeply satisfying.',
                'category'    => 'breakfast',
                'difficulty'  => 'easy',
                'prep_time'   => 10,
                'cook_time'   => 25,
                'likes_count' => 45,
                'ingredients' => [
                    ['amount' => '6',       'name' => 'eggs'],
                    ['amount' => '400g',    'name' => 'canned crushed tomatoes'],
                    ['amount' => '2',       'name' => 'red peppers, diced'],
                    ['amount' => '1',       'name' => 'white onion, diced'],
                    ['amount' => '3 cloves','name' => 'garlic, minced'],
                    ['amount' => '1 tsp',   'name' => 'ground cumin'],
                    ['amount' => '1 tsp',   'name' => 'smoked paprika'],
                    ['amount' => '½ tsp',   'name' => 'cayenne pepper'],
                    ['amount' => '1 tbsp',  'name' => 'olive oil'],
                    ['amount' => 'handful', 'name' => 'fresh parsley, chopped'],
                ],
                'steps' => [
                    'Heat olive oil in a wide pan. Sauté onion and peppers until soft, about 8 minutes.',
                    'Add garlic, cumin, paprika, and cayenne. Cook 1 minute until fragrant.',
                    'Pour in crushed tomatoes. Simmer 10 minutes until sauce thickens. Season well.',
                    'Make six wells in the sauce with a spoon. Crack an egg into each well.',
                    'Cover and cook over low heat for 6–8 minutes until whites are set, yolks still runny.',
                    'Scatter parsley over the top and serve straight from the pan with crusty bread.',
                ],
            ],
            [
                'user_id'     => $grace->id,
                'title'       => 'Mushroom Risotto',
                'description' => 'Slow-stirred arborio rice with mixed mushrooms, white wine, and a generous finish of butter.',
                'category'    => 'vegetarian',
                'difficulty'  => 'hard',
                'prep_time'   => 15,
                'cook_time'   => 35,
                'likes_count' => 33,
                'ingredients' => [
                    ['amount' => '320g',    'name' => 'arborio rice'],
                    ['amount' => '400g',    'name' => 'mixed mushrooms, sliced'],
                    ['amount' => '1.2L',    'name' => 'vegetable stock, warm'],
                    ['amount' => '150ml',   'name' => 'dry white wine'],
                    ['amount' => '1',       'name' => 'white onion, finely diced'],
                    ['amount' => '2 cloves','name' => 'garlic, minced'],
                    ['amount' => '60g',     'name' => 'unsalted butter'],
                    ['amount' => '60g',     'name' => 'parmesan, grated'],
                    ['amount' => '2 tbsp',  'name' => 'olive oil'],
                ],
                'steps' => [
                    'Sauté mushrooms in olive oil over high heat until golden. Season and set aside.',
                    'In the same pan, melt half the butter over medium heat. Soften onion 5 minutes.',
                    'Add garlic and rice. Toast rice for 2 minutes, stirring constantly.',
                    'Pour in wine. Stir until fully absorbed.',
                    'Add warm stock one ladle at a time, stirring constantly and waiting until each ladle is absorbed. About 20 minutes.',
                    'When rice is al dente and creamy, remove from heat. Stir in remaining butter and parmesan.',
                    'Fold in mushrooms. Rest 2 minutes, then serve.',
                ],
            ],
        ];

        foreach ($recipes as $data) {
            Recipe::create($data);
        }

        $this->command->info('Database seeded successfully.');
        $this->command->info('  ada@example.com / password');
        $this->command->info('  grace@example.com / password');
    }
}
