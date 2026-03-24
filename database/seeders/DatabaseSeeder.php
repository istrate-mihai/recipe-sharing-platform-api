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
                'servings'    => 4,
                'steps'       => [
                    'Bring a large pot of heavily salted water to a boil and cook spaghetti until al dente.',
                    'Fry pancetta in a pan over medium heat until crispy. Remove from heat.',
                    'Whisk egg yolks, whole egg, and pecorino together in a bowl. Season with black pepper.',
                    'Reserve 1 cup of pasta water before draining.',
                    'Add hot pasta to the pan with pancetta off the heat. Pour egg mixture over and toss quickly.',
                    'Add pasta water a splash at a time, tossing until glossy and creamy. Serve immediately.',
                ],
                'ingredients' => [
                    ['quantity' => 400,  'unit' => 'g',   'name' => 'spaghetti'],
                    ['quantity' => 150,  'unit' => 'g',   'name' => 'pancetta or guanciale'],
                    ['quantity' => 4,    'unit' => null,  'name' => 'egg yolks'],
                    ['quantity' => 1,    'unit' => null,  'name' => 'whole egg'],
                    ['quantity' => 80,   'unit' => 'g',   'name' => 'pecorino romano, grated'],
                    ['quantity' => 1,    'unit' => 'tsp', 'name' => 'black pepper, freshly cracked'],
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
                'servings'    => 4,
                'steps'       => [
                    'Melt butter with olive oil in a heavy pot over low heat. Add onions and a pinch of salt.',
                    'Cook onions for 45–60 minutes, stirring every 10 minutes, until deeply caramelised and golden brown.',
                    'Add white wine and scrape up any browned bits. Simmer 5 minutes.',
                    'Add beef stock and simmer for 20 minutes. Season to taste.',
                    'Ladle soup into oven-safe bowls. Top with toasted baguette and grated gruyère.',
                    'Broil until cheese is bubbling and golden. Serve immediately.',
                ],
                'ingredients' => [
                    ['quantity' => 1000, 'unit' => 'g',      'name' => 'yellow onions, thinly sliced'],
                    ['quantity' => 4,    'unit' => 'tbsp',   'name' => 'unsalted butter'],
                    ['quantity' => 1,    'unit' => 'tbsp',   'name' => 'olive oil'],
                    ['quantity' => 150,  'unit' => 'ml',     'name' => 'dry white wine'],
                    ['quantity' => 1500, 'unit' => 'ml',     'name' => 'beef stock'],
                    ['quantity' => 4,    'unit' => 'slices', 'name' => 'baguette, toasted'],
                    ['quantity' => 120,  'unit' => 'g',      'name' => 'gruyère, grated'],
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
                'servings'    => 2,
                'steps'       => [
                    'Toast the sourdough until golden and crisp.',
                    'Scoop avocado into a bowl. Add lemon juice, salt, and pepper. Smash with a fork.',
                    'Bring a pot of water to a gentle simmer. Add vinegar.',
                    'Crack each egg into a small cup. Swirl the water and slide each egg in. Poach 3 minutes.',
                    'Spread avocado over toast. Top with drained poached eggs.',
                    'Finish with chilli flakes and a crack of black pepper.',
                ],
                'ingredients' => [
                    ['quantity' => 2,   'unit' => 'slices', 'name' => 'sourdough bread'],
                    ['quantity' => 1,   'unit' => null,     'name' => 'ripe avocado'],
                    ['quantity' => 2,   'unit' => null,     'name' => 'eggs'],
                    ['quantity' => 1,   'unit' => 'tbsp',   'name' => 'white wine vinegar'],
                    ['quantity' => 0.5, 'unit' => 'tsp',    'name' => 'chilli flakes'],
                    ['quantity' => 1,   'unit' => null,     'name' => 'lemon, juice only'],
                    ['quantity' => null,'unit' => null,     'name' => 'salt and black pepper'],
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
                'servings'    => 4,
                'steps'       => [
                    'Mix red onion with lime juice and a pinch of salt. Set aside to quick-pickle.',
                    'Brown ground beef in a pan over high heat. Drain excess fat.',
                    'Add cumin, paprika, garlic powder, salt and pepper. Cook 2 more minutes.',
                    'Mix diced tomatoes with coriander and a squeeze of lime for salsa.',
                    'Warm tortillas in a dry pan until lightly charred on each side.',
                    'Fill tortillas with beef, pickled onion, and salsa. Serve with lime wedges.',
                ],
                'ingredients' => [
                    ['quantity' => 500, 'unit' => 'g',    'name' => 'ground beef'],
                    ['quantity' => 8,   'unit' => null,   'name' => 'small corn tortillas'],
                    ['quantity' => 1,   'unit' => 'tsp',  'name' => 'ground cumin'],
                    ['quantity' => 1,   'unit' => 'tsp',  'name' => 'smoked paprika'],
                    ['quantity' => 0.5, 'unit' => 'tsp',  'name' => 'garlic powder'],
                    ['quantity' => 1,   'unit' => null,   'name' => 'red onion, finely diced'],
                    ['quantity' => 2,   'unit' => null,   'name' => 'limes'],
                    ['quantity' => 1,   'unit' => 'cup',  'name' => 'fresh coriander, chopped'],
                    ['quantity' => 2,   'unit' => null,   'name' => 'tomatoes, diced'],
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
                'servings'    => 2,
                'steps'       => [
                    'Crush anchovies and garlic into a paste using the back of a knife.',
                    'Whisk together anchovy paste, egg yolk, lemon juice, and mustard.',
                    'Slowly drizzle in olive oil while whisking to emulsify into a creamy dressing.',
                    'Toss torn sourdough in olive oil and toast in a pan until golden.',
                    'Toss romaine with dressing until every leaf is coated.',
                    'Top with croutons and a generous shower of parmesan.',
                ],
                'ingredients' => [
                    ['quantity' => 2,  'unit' => 'heads',  'name' => 'romaine lettuce, chopped'],
                    ['quantity' => 4,  'unit' => null,     'name' => 'anchovy fillets'],
                    ['quantity' => 1,  'unit' => 'clove',  'name' => 'garlic'],
                    ['quantity' => 1,  'unit' => null,     'name' => 'egg yolk'],
                    ['quantity' => 2,  'unit' => 'tbsp',   'name' => 'lemon juice'],
                    ['quantity' => 1,  'unit' => 'tsp',    'name' => 'Dijon mustard'],
                    ['quantity' => 80, 'unit' => 'ml',     'name' => 'extra virgin olive oil'],
                    ['quantity' => 40, 'unit' => 'g',      'name' => 'parmesan, finely grated'],
                    ['quantity' => 2,  'unit' => 'slices', 'name' => 'sourdough, torn and toasted'],
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
                'servings'    => 4,
                'steps'       => [
                    'Preheat oven to 220°C. Butter four ramekins and dust with cocoa powder.',
                    'Melt chocolate and butter together over a bain-marie. Stir until smooth.',
                    'Whisk eggs, yolks, and sugar until pale and thick, about 3 minutes.',
                    'Fold chocolate mixture into egg mixture. Sift in flour and salt. Fold gently.',
                    'Divide batter between ramekins. Refrigerate up to 24 hours, or bake immediately.',
                    'Bake 10–12 minutes until edges are set but centre still wobbles. Invert onto plates and serve.',
                ],
                'ingredients' => [
                    ['quantity' => 170, 'unit' => 'g',    'name' => 'dark chocolate (70%), chopped'],
                    ['quantity' => 115, 'unit' => 'g',    'name' => 'unsalted butter'],
                    ['quantity' => 2,   'unit' => null,   'name' => 'whole eggs'],
                    ['quantity' => 2,   'unit' => null,   'name' => 'egg yolks'],
                    ['quantity' => 100, 'unit' => 'g',    'name' => 'caster sugar'],
                    ['quantity' => 2,   'unit' => 'tbsp', 'name' => 'plain flour'],
                    ['quantity' => null,'unit' => null,   'name' => 'sea salt, pinch'],
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
                'servings'    => 2,
                'steps'       => [
                    'Heat olive oil in a wide pan. Sauté onion and peppers until soft, about 8 minutes.',
                    'Add garlic, cumin, paprika, and cayenne. Cook 1 minute until fragrant.',
                    'Pour in crushed tomatoes. Simmer 10 minutes until sauce thickens. Season well.',
                    'Make six wells in the sauce with a spoon. Crack an egg into each well.',
                    'Cover and cook over low heat for 6–8 minutes until whites are set, yolks still runny.',
                    'Scatter parsley over the top and serve straight from the pan with crusty bread.',
                ],
                'ingredients' => [
                    ['quantity' => 6,   'unit' => null,  'name' => 'eggs'],
                    ['quantity' => 400, 'unit' => 'g',   'name' => 'canned crushed tomatoes'],
                    ['quantity' => 2,   'unit' => null,  'name' => 'red peppers, diced'],
                    ['quantity' => 1,   'unit' => null,  'name' => 'white onion, diced'],
                    ['quantity' => 3,   'unit' => null,  'name' => 'garlic cloves, minced'],
                    ['quantity' => 1,   'unit' => 'tsp', 'name' => 'ground cumin'],
                    ['quantity' => 1,   'unit' => 'tsp', 'name' => 'smoked paprika'],
                    ['quantity' => 0.5, 'unit' => 'tsp', 'name' => 'cayenne pepper'],
                    ['quantity' => 1,   'unit' => 'tbsp','name' => 'olive oil'],
                    ['quantity' => null,'unit' => null,  'name' => 'fresh parsley, handful'],
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
                'servings'    => 4,
                'steps'       => [
                    'Sauté mushrooms in olive oil over high heat until golden. Season and set aside.',
                    'In the same pan, melt half the butter over medium heat. Soften onion 5 minutes.',
                    'Add garlic and rice. Toast rice for 2 minutes, stirring constantly.',
                    'Pour in wine. Stir until fully absorbed.',
                    'Add warm stock one ladle at a time, stirring constantly and waiting until each ladle is absorbed. About 20 minutes.',
                    'When rice is al dente and creamy, remove from heat. Stir in remaining butter and parmesan.',
                    'Fold in mushrooms. Rest 2 minutes, then serve.',
                ],
                'ingredients' => [
                    ['quantity' => 320,  'unit' => 'g',    'name' => 'arborio rice'],
                    ['quantity' => 400,  'unit' => 'g',    'name' => 'mixed mushrooms, sliced'],
                    ['quantity' => 1200, 'unit' => 'ml',   'name' => 'vegetable stock, warm'],
                    ['quantity' => 150,  'unit' => 'ml',   'name' => 'dry white wine'],
                    ['quantity' => 1,    'unit' => null,   'name' => 'white onion, finely diced'],
                    ['quantity' => 2,    'unit' => null,   'name' => 'garlic cloves, minced'],
                    ['quantity' => 60,   'unit' => 'g',    'name' => 'unsalted butter'],
                    ['quantity' => 60,   'unit' => 'g',    'name' => 'parmesan, grated'],
                    ['quantity' => 2,    'unit' => 'tbsp', 'name' => 'olive oil'],
                ],
            ],
        ];

        // ── Create recipes + ingredients ──────────────────────────────────
        foreach ($recipes as $data) {
            $ingredients = $data['ingredients'];
            unset($data['ingredients']);

            $recipe = Recipe::create($data);

            collect($ingredients)->each(function ($ing, $i) use ($recipe) {
                $recipe->ingredients()->create([
                    'quantity' => $ing['quantity'] ?? null,
                    'unit'     => $ing['unit']     ?? null,
                    'name'     => $ing['name'],
                    'order'    => $i,
                ]);
            });
        }

        $this->command->info('Database seeded successfully.');
        $this->command->info('ada@example.com / password');
        $this->command->info('grace@example.com / password');
    }
}
