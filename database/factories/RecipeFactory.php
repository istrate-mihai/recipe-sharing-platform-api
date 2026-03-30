<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    // ── Pool of realistic recipes per category ────────────────────────────

    private static array $pool = [
        'breakfast' => [
            ['title' => 'Buttermilk Pancakes',         'description' => 'Thick, fluffy pancakes with golden edges and a tender crumb. Serve with maple syrup and cold butter.',      'difficulty' => 'easy',   'prep' => 10, 'cook' => 20, 'servings' => 4],
            ['title' => 'Classic French Omelette',     'description' => 'A silky, pale yellow omelette folded around fresh herbs. Technique over ingredients.',                      'difficulty' => 'medium', 'prep' => 5,  'cook' => 5,  'servings' => 1],
            ['title' => 'Overnight Oats',              'description' => 'Rolled oats soaked overnight in milk with chia seeds, honey, and seasonal fruit.',                          'difficulty' => 'easy',   'prep' => 5,  'cook' => 0,  'servings' => 2],
            ['title' => 'Eggs Benedict',               'description' => 'Toasted English muffins, Canadian bacon, and poached eggs drowned in hollandaise.',                         'difficulty' => 'hard',   'prep' => 20, 'cook' => 20, 'servings' => 2],
            ['title' => 'Banana Bread',                'description' => 'Moist, dense loaf packed with overripe bananas and a hint of cinnamon. Better on day two.',                'difficulty' => 'easy',   'prep' => 15, 'cook' => 55, 'servings' => 8],
            ['title' => 'Greek Yoghurt Parfait',       'description' => 'Layers of thick yoghurt, granola, and fresh berries. No cooking required.',                                 'difficulty' => 'easy',   'prep' => 5,  'cook' => 0,  'servings' => 2],
            ['title' => 'Smoked Salmon Bagel',         'description' => 'Toasted bagel with cream cheese, smoked salmon, capers, and thinly sliced red onion.',                     'difficulty' => 'easy',   'prep' => 10, 'cook' => 5,  'servings' => 2],
            ['title' => 'Breakfast Burrito',           'description' => 'Scrambled eggs, black beans, cheddar, and salsa wrapped in a warm flour tortilla.',                         'difficulty' => 'easy',   'prep' => 10, 'cook' => 15, 'servings' => 2],
            ['title' => 'Ricotta Hotcakes',            'description' => 'Light, pillowy hotcakes made with fresh ricotta. Serve with honeycomb butter and berries.',                 'difficulty' => 'medium', 'prep' => 10, 'cook' => 20, 'servings' => 4],
            ['title' => 'Açaí Bowl',                   'description' => 'Thick blended açaí with banana, topped with granola, sliced fruit, and coconut flakes.',                   'difficulty' => 'easy',   'prep' => 10, 'cook' => 0,  'servings' => 1],
            ['title' => 'Croque Monsieur',             'description' => 'The French grilled cheese — ham and gruyère between toasted bread, finished under the grill with béchamel.','difficulty' => 'medium', 'prep' => 10, 'cook' => 15, 'servings' => 2],
            ['title' => 'Bircher Muesli',              'description' => 'Swiss-style oats soaked in apple juice with grated apple, yoghurt, and toasted nuts.',                     'difficulty' => 'easy',   'prep' => 10, 'cook' => 0,  'servings' => 4],
        ],
        'pasta' => [
            ['title' => 'Cacio e Pepe',                'description' => 'Three ingredients: pasta, pecorino, black pepper. Rome\'s most deceptively difficult dish.',               'difficulty' => 'hard',   'prep' => 5,  'cook' => 15, 'servings' => 2],
            ['title' => 'Penne Arrabbiata',            'description' => 'Penne in a fiery tomato sauce with garlic and dried chilli. Fast, loud, and satisfying.',                  'difficulty' => 'easy',   'prep' => 5,  'cook' => 20, 'servings' => 4],
            ['title' => 'Lasagne al Forno',            'description' => 'Layers of slow-cooked ragù, silky béchamel, and fresh pasta sheets baked until bubbling.',                 'difficulty' => 'hard',   'prep' => 45, 'cook' => 60, 'servings' => 6],
            ['title' => 'Tagliatelle with Bolognese',  'description' => 'The real Bolognese: a slow-cooked meat sauce using beef, pork, wine, and milk. No herbs.',                 'difficulty' => 'medium', 'prep' => 20, 'cook' => 90, 'servings' => 6],
            ['title' => 'Gnocchi al Pomodoro',         'description' => 'Pillowy potato gnocchi tossed in a bright San Marzano tomato sauce with torn basil.',                      'difficulty' => 'medium', 'prep' => 30, 'cook' => 20, 'servings' => 4],
            ['title' => 'Bucatini all\'Amatriciana',   'description' => 'Thick hollow spaghetti with guanciale, pecorino, and tomato. No onion, no garlic — just the classic.',     'difficulty' => 'medium', 'prep' => 10, 'cook' => 25, 'servings' => 4],
            ['title' => 'Pasta e Fagioli',             'description' => 'A thick soupy pasta with borlotti beans, rosemary, and a parmesan rind simmered in broth.',                'difficulty' => 'easy',   'prep' => 15, 'cook' => 40, 'servings' => 6],
            ['title' => 'Orecchiette with Sausage',    'description' => 'Ear-shaped pasta with Italian sausage, broccoli rabe, and plenty of chilli.',                              'difficulty' => 'medium', 'prep' => 10, 'cook' => 25, 'servings' => 4],
            ['title' => 'Pappardelle with Ragù',       'description' => 'Wide flat ribbons with a slow-cooked lamb ragù, olives, and rosemary.',                                    'difficulty' => 'medium', 'prep' => 20, 'cook' => 120,'servings' => 4],
            ['title' => 'Fettuccine Alfredo',          'description' => 'The real Roman version: just pasta, butter, and parmigiano — no cream.',                                   'difficulty' => 'easy',   'prep' => 5,  'cook' => 15, 'servings' => 2],
            ['title' => 'Spaghetti alle Vongole',      'description' => 'Spaghetti with fresh clams, white wine, garlic, and parsley. The sea on a plate.',                        'difficulty' => 'medium', 'prep' => 20, 'cook' => 15, 'servings' => 4],
            ['title' => 'Rigatoni alla Norma',         'description' => 'Sicilian pasta with fried aubergine, tomato sauce, and salted ricotta.',                                   'difficulty' => 'medium', 'prep' => 20, 'cook' => 30, 'servings' => 4],
        ],
        'soup' => [
            ['title' => 'Roasted Tomato Soup',         'description' => 'Tomatoes, garlic, and onion roasted until caramelised then blended into a silky soup.',                   'difficulty' => 'easy',   'prep' => 10, 'cook' => 45, 'servings' => 4],
            ['title' => 'Chicken Noodle Soup',         'description' => 'A deeply comforting broth with shredded chicken, egg noodles, and fresh herbs.',                          'difficulty' => 'easy',   'prep' => 15, 'cook' => 40, 'servings' => 6],
            ['title' => 'Minestrone',                  'description' => 'A thick Italian vegetable soup with cannellini beans, pasta, and a parmesan rind.',                        'difficulty' => 'easy',   'prep' => 20, 'cook' => 40, 'servings' => 6],
            ['title' => 'Butternut Squash Soup',       'description' => 'Roasted butternut squash blended with ginger, coconut milk, and a swirl of crème fraîche.',               'difficulty' => 'easy',   'prep' => 15, 'cook' => 40, 'servings' => 4],
            ['title' => 'Tom Yum Goong',               'description' => 'Hot and sour Thai prawn soup with lemongrass, kaffir lime, galangal, and fish sauce.',                    'difficulty' => 'medium', 'prep' => 20, 'cook' => 20, 'servings' => 4],
            ['title' => 'Lentil Soup',                 'description' => 'Red lentils, cumin, and turmeric simmered with tomatoes and finished with a lemon squeeze.',              'difficulty' => 'easy',   'prep' => 10, 'cook' => 30, 'servings' => 4],
            ['title' => 'Pho Bo',                      'description' => 'Vietnamese beef noodle soup with a long-simmered spiced bone broth and fresh garnishes.',                  'difficulty' => 'hard',   'prep' => 30, 'cook' => 180,'servings' => 6],
            ['title' => 'Clam Chowder',                'description' => 'New England-style creamy chowder with clams, potatoes, and smoked bacon.',                                'difficulty' => 'medium', 'prep' => 20, 'cook' => 35, 'servings' => 4],
            ['title' => 'Gazpacho',                    'description' => 'Cold Andalusian tomato soup blended with peppers, cucumber, and excellent olive oil.',                     'difficulty' => 'easy',   'prep' => 20, 'cook' => 0,  'servings' => 4],
            ['title' => 'Miso Soup with Tofu',         'description' => 'A restorative Japanese broth with white miso, silken tofu, wakame, and spring onions.',                   'difficulty' => 'easy',   'prep' => 5,  'cook' => 10, 'servings' => 2],
            ['title' => 'Ribollita',                   'description' => 'Tuscan bread soup with cavolo nero, cannellini beans, and day-old bread. Better reheated.',               'difficulty' => 'medium', 'prep' => 20, 'cook' => 60, 'servings' => 6],
        ],
        'salad' => [
            ['title' => 'Niçoise Salad',               'description' => 'A composed salad of tuna, green beans, olives, eggs, and anchovies with a sharp vinaigrette.',            'difficulty' => 'easy',   'prep' => 20, 'cook' => 15, 'servings' => 4],
            ['title' => 'Greek Salad',                 'description' => 'Tomatoes, cucumber, Kalamata olives, red onion, and a thick slab of feta. No lettuce.',                   'difficulty' => 'easy',   'prep' => 15, 'cook' => 0,  'servings' => 4],
            ['title' => 'Fattoush',                    'description' => 'Lebanese salad with crispy fried pita, fresh vegetables, and sumac-spiked dressing.',                     'difficulty' => 'easy',   'prep' => 20, 'cook' => 5,  'servings' => 4],
            ['title' => 'Warm Lentil Salad',           'description' => 'Puy lentils with roasted beetroot, goat\'s cheese, walnuts, and a mustardy dressing.',                    'difficulty' => 'easy',   'prep' => 10, 'cook' => 30, 'servings' => 4],
            ['title' => 'Cobb Salad',                  'description' => 'American classic with grilled chicken, bacon, avocado, blue cheese, and hard-boiled egg.',                'difficulty' => 'easy',   'prep' => 20, 'cook' => 15, 'servings' => 4],
            ['title' => 'Watermelon Feta Salad',       'description' => 'Juicy watermelon with crumbled feta, mint, and a drizzle of good olive oil. Summer on a plate.',          'difficulty' => 'easy',   'prep' => 10, 'cook' => 0,  'servings' => 4],
            ['title' => 'Roasted Beetroot Salad',      'description' => 'Slow-roasted beetroot with orange segments, hazelnuts, and crème fraîche.',                               'difficulty' => 'easy',   'prep' => 10, 'cook' => 60, 'servings' => 4],
            ['title' => 'Quinoa Tabbouleh',            'description' => 'A protein-rich spin on the classic — quinoa, tomatoes, cucumber, parsley, and lemon.',                    'difficulty' => 'easy',   'prep' => 15, 'cook' => 15, 'servings' => 4],
            ['title' => 'Panzanella',                  'description' => 'Tuscan bread salad with ripe tomatoes, soaked day-old bread, basil, and red wine vinegar.',               'difficulty' => 'easy',   'prep' => 20, 'cook' => 0,  'servings' => 4],
            ['title' => 'Asian Slaw',                  'description' => 'Shredded cabbage with edamame, mango, sesame, and a ginger-lime dressing.',                               'difficulty' => 'easy',   'prep' => 20, 'cook' => 0,  'servings' => 4],
        ],
        'meat' => [
            ['title' => 'Roast Chicken',               'description' => 'A whole chicken rubbed with herbed butter, roasted until golden and rested properly.',                     'difficulty' => 'easy',   'prep' => 15, 'cook' => 80, 'servings' => 4],
            ['title' => 'Beef Bourguignon',            'description' => 'Braised beef shin with mushrooms, pearl onions, and a whole bottle of Burgundy.',                         'difficulty' => 'hard',   'prep' => 30, 'cook' => 180,'servings' => 6],
            ['title' => 'Lamb Chops with Herbs',       'description' => 'Marinated lamb chops seared in a screaming hot pan with garlic, rosemary, and butter.',                  'difficulty' => 'easy',   'prep' => 10, 'cook' => 10, 'servings' => 2],
            ['title' => 'Chicken Tikka Masala',        'description' => 'Tandoor-charred chicken in a rich, spiced tomato and cream sauce. Britain\'s real national dish.',         'difficulty' => 'medium', 'prep' => 30, 'cook' => 40, 'servings' => 4],
            ['title' => 'Pork Belly',                  'description' => 'Slow-roasted pork belly with crackling, apple sauce, and braised fennel.',                                'difficulty' => 'medium', 'prep' => 20, 'cook' => 150,'servings' => 6],
            ['title' => 'Duck Confit',                 'description' => 'Duck legs cured overnight and slow-cooked in their own fat until yielding and rich.',                     'difficulty' => 'hard',   'prep' => 1440,'cook' => 120,'servings' => 4],
            ['title' => 'Steak au Poivre',             'description' => 'Pepper-crusted sirloin finished in a cognac and cream pan sauce.',                                        'difficulty' => 'medium', 'prep' => 5,  'cook' => 15, 'servings' => 2],
            ['title' => 'Pulled Pork',                 'description' => 'Pork shoulder rubbed with spices and slow-roasted until it falls apart.',                                 'difficulty' => 'easy',   'prep' => 15, 'cook' => 480,'servings' => 8],
            ['title' => 'Jerk Chicken',                'description' => 'Scotch bonnet and allspice marinated chicken charred over charcoal. Heat with fruit underneath.',         'difficulty' => 'medium', 'prep' => 30, 'cook' => 45, 'servings' => 4],
            ['title' => 'Osso Buco',                   'description' => 'Braised veal shin with a gremolata of lemon, garlic, and parsley. Serve with risotto Milanese.',          'difficulty' => 'hard',   'prep' => 20, 'cook' => 120,'servings' => 4],
            ['title' => 'Korean Bulgogi',              'description' => 'Thinly sliced beef marinated in soy, pear, sesame, and sugar. Grilled or pan-fried.',                    'difficulty' => 'easy',   'prep' => 20, 'cook' => 10, 'servings' => 4],
            ['title' => 'Coq au Vin',                  'description' => 'Chicken braised in red wine with lardons, mushrooms, and pearl onions.',                                  'difficulty' => 'medium', 'prep' => 20, 'cook' => 90, 'servings' => 4],
        ],
        'dessert' => [
            ['title' => 'Crème Brûlée',                'description' => 'A trembling vanilla custard beneath a perfectly torched caramel crust.',                                  'difficulty' => 'medium', 'prep' => 20, 'cook' => 45, 'servings' => 4],
            ['title' => 'Tiramisu',                    'description' => 'Espresso-soaked savoiardi, mascarpone cream, and a dusting of dark cocoa. Made the day before.',          'difficulty' => 'medium', 'prep' => 30, 'cook' => 0,  'servings' => 8],
            ['title' => 'Tarte Tatin',                 'description' => 'Upside-down caramelised apple tart in a buttery pastry. The great French accident.',                      'difficulty' => 'medium', 'prep' => 20, 'cook' => 45, 'servings' => 6],
            ['title' => 'Sticky Toffee Pudding',       'description' => 'Dense date sponge soaked in a warm toffee sauce. The finest British pudding.',                            'difficulty' => 'medium', 'prep' => 20, 'cook' => 35, 'servings' => 8],
            ['title' => 'Panna Cotta',                 'description' => 'Set cream with vanilla and a drizzle of seasonal berry coulis. Simple and refined.',                     'difficulty' => 'easy',   'prep' => 15, 'cook' => 5,  'servings' => 4],
            ['title' => 'Pavlova',                     'description' => 'A crisp meringue shell with a marshmallow centre, topped with cream and passion fruit.',                  'difficulty' => 'medium', 'prep' => 20, 'cook' => 90, 'servings' => 8],
            ['title' => 'Profiteroles',                'description' => 'Choux pastry puffs filled with crème pâtissière and drizzled with warm dark chocolate.',                  'difficulty' => 'hard',   'prep' => 30, 'cook' => 30, 'servings' => 6],
            ['title' => 'Mango Sorbet',                'description' => 'Ripe mango, lime juice, and sugar churned into a bright, intensely fruity sorbet.',                       'difficulty' => 'easy',   'prep' => 15, 'cook' => 0,  'servings' => 4],
            ['title' => 'Baked Cheesecake',            'description' => 'New York-style cream cheese cheesecake on a digestive base, baked low and slow.',                        'difficulty' => 'medium', 'prep' => 20, 'cook' => 70, 'servings' => 10],
            ['title' => 'Rice Pudding',                'description' => 'Slow-cooked arborio rice in whole milk with vanilla and a pinch of nutmeg. Serve warm.',                  'difficulty' => 'easy',   'prep' => 5,  'cook' => 40, 'servings' => 4],
            ['title' => 'Chocolate Mousse',            'description' => 'Dark chocolate and whipped egg whites folded into an airy, intensely chocolatey mousse.',                 'difficulty' => 'medium', 'prep' => 20, 'cook' => 0,  'servings' => 6],
        ],
        'vegetarian' => [
            ['title' => 'Palak Paneer',                'description' => 'Fresh cheese cubes in a velvety spiced spinach sauce. Serve with warm roti.',                             'difficulty' => 'medium', 'prep' => 20, 'cook' => 30, 'servings' => 4],
            ['title' => 'Margherita Pizza',            'description' => 'A thin Neapolitan base with San Marzano tomatoes, fresh mozzarella, and basil.',                          'difficulty' => 'medium', 'prep' => 90, 'cook' => 10, 'servings' => 2],
            ['title' => 'Ratatouille',                 'description' => 'A Provençal baked vegetable dish layered with tomato, courgette, aubergine, and peppers.',                'difficulty' => 'medium', 'prep' => 30, 'cook' => 60, 'servings' => 6],
            ['title' => 'Vegetable Curry',             'description' => 'Chickpeas, sweet potato, and spinach in a coconut milk and tomato base with garam masala.',               'difficulty' => 'easy',   'prep' => 15, 'cook' => 35, 'servings' => 4],
            ['title' => 'Stuffed Peppers',             'description' => 'Bell peppers filled with herbed rice, black beans, and melted cheese, baked until tender.',               'difficulty' => 'easy',   'prep' => 20, 'cook' => 40, 'servings' => 4],
            ['title' => 'Caponata',                    'description' => 'Sweet and sour Sicilian aubergine stew with olives, capers, and pine nuts.',                              'difficulty' => 'medium', 'prep' => 20, 'cook' => 40, 'servings' => 6],
            ['title' => 'Falafel',                     'description' => 'Crispy fried chickpea patties with herbs and spices, served with tahini and flatbread.',                  'difficulty' => 'medium', 'prep' => 30, 'cook' => 20, 'servings' => 4],
            ['title' => 'Aubergine Parmigiana',        'description' => 'Fried aubergine slices layered with tomato sauce and mozzarella, baked until golden.',                   'difficulty' => 'medium', 'prep' => 30, 'cook' => 40, 'servings' => 6],
            ['title' => 'Dhal Tadka',                  'description' => 'Yellow lentils simmered with turmeric and finished with a sizzling tarka of cumin and chilli.',          'difficulty' => 'easy',   'prep' => 10, 'cook' => 35, 'servings' => 4],
            ['title' => 'Spring Rolls',                'description' => 'Crispy rolls filled with glass noodles, cabbage, carrot, and mushrooms. Dip in sweet chilli.',           'difficulty' => 'medium', 'prep' => 30, 'cook' => 20, 'servings' => 6],
            ['title' => 'Spanakopita',                 'description' => 'Greek spinach and feta pie wrapped in layers of crispy buttered filo pastry.',                            'difficulty' => 'medium', 'prep' => 30, 'cook' => 45, 'servings' => 8],
            ['title' => 'Shakshuka Verde',             'description' => 'Eggs poached in a tomatillo and green chilli sauce with feta and fresh coriander.',                       'difficulty' => 'easy',   'prep' => 10, 'cook' => 20, 'servings' => 4],
        ],
    ];

    // Generic ingredients per category to keep seeding fast
    private static array $ingredients = [
        'breakfast'  => [['quantity'=>2,'unit'=>null,'name'=>'eggs'],['quantity'=>1,'unit'=>'cup','name'=>'flour'],['quantity'=>200,'unit'=>'ml','name'=>'milk'],['quantity'=>2,'unit'=>'tbsp','name'=>'butter'],['quantity'=>1,'unit'=>'tsp','name'=>'salt']],
        'pasta'      => [['quantity'=>400,'unit'=>'g','name'=>'pasta'],['quantity'=>2,'unit'=>'cloves','name'=>'garlic'],['quantity'=>3,'unit'=>'tbsp','name'=>'olive oil'],['quantity'=>100,'unit'=>'g','name'=>'parmesan'],['quantity'=>null,'unit'=>null,'name'=>'salt and pepper']],
        'soup'       => [['quantity'=>1,'unit'=>'litre','name'=>'vegetable stock'],['quantity'=>1,'unit'=>null,'name'=>'onion, diced'],['quantity'=>2,'unit'=>'cloves','name'=>'garlic'],['quantity'=>2,'unit'=>'tbsp','name'=>'olive oil'],['quantity'=>null,'unit'=>null,'name'=>'salt and pepper']],
        'salad'      => [['quantity'=>100,'unit'=>'g','name'=>'mixed leaves'],['quantity'=>1,'unit'=>null,'name'=>'lemon, juiced'],['quantity'=>3,'unit'=>'tbsp','name'=>'olive oil'],['quantity'=>1,'unit'=>'tsp','name'=>'Dijon mustard'],['quantity'=>null,'unit'=>null,'name'=>'salt and pepper']],
        'meat'       => [['quantity'=>600,'unit'=>'g','name'=>'meat of choice'],['quantity'=>2,'unit'=>'cloves','name'=>'garlic'],['quantity'=>1,'unit'=>'tbsp','name'=>'olive oil'],['quantity'=>1,'unit'=>'tsp','name'=>'salt'],['quantity'=>1,'unit'=>'tsp','name'=>'black pepper']],
        'dessert'    => [['quantity'=>200,'unit'=>'g','name'=>'sugar'],['quantity'=>4,'unit'=>null,'name'=>'eggs'],['quantity'=>100,'unit'=>'g','name'=>'butter'],['quantity'=>200,'unit'=>'ml','name'=>'cream'],['quantity'=>1,'unit'=>'tsp','name'=>'vanilla extract']],
        'vegetarian' => [['quantity'=>400,'unit'=>'g','name'=>'mixed vegetables'],['quantity'=>1,'unit'=>'can','name'=>'chickpeas, drained'],['quantity'=>2,'unit'=>'tbsp','name'=>'olive oil'],['quantity'=>1,'unit'=>'tsp','name'=>'cumin'],['quantity'=>null,'unit'=>null,'name'=>'salt and pepper']],
    ];

    private static array $steps = [
        'breakfast'  => ['Prepare all ingredients.', 'Cook over medium heat until golden.', 'Season to taste and serve immediately.'],
        'pasta'      => ['Bring a large pot of salted water to a boil.', 'Cook pasta until al dente. Reserve pasta water.', 'Make the sauce in a wide pan.', 'Toss pasta with sauce, adding pasta water as needed. Serve.'],
        'soup'       => ['Sauté aromatics in olive oil until soft.', 'Add remaining ingredients and stock. Simmer 25 minutes.', 'Blend if desired. Season and serve.'],
        'salad'      => ['Prepare all vegetables and place in a large bowl.', 'Whisk dressing ingredients together.', 'Toss salad with dressing just before serving.'],
        'meat'       => ['Season the meat generously and bring to room temperature.', 'Sear in a hot pan until browned on all sides.', 'Cook to desired doneness. Rest before serving.'],
        'dessert'    => ['Preheat oven if needed. Prepare your equipment.', 'Combine ingredients as directed.', 'Cook or set as required. Chill before serving.'],
        'vegetarian' => ['Prepare all vegetables.', 'Cook aromatics first, then add remaining ingredients.', 'Simmer until tender. Adjust seasoning and serve.'],
    ];

    public function definition(): array
    {
        $category = $this->faker->randomElement(array_keys(self::$pool));
        $pool     = self::$pool[$category];
        $recipe   = $this->faker->randomElement($pool);

        return [
            'user_id'     => User::inRandomOrder()->first()?->id ?? 1,
            'title'       => $recipe['title'],
            'description' => $recipe['description'],
            'category'    => $category,
            'difficulty'  => $recipe['difficulty'],
            'prep_time'   => $recipe['prep'],
            'cook_time'   => $recipe['cook'],
            'servings'    => $recipe['servings'],
            'status'      => 'published',
            'steps'       => self::$steps[$category],
        ];
    }

    /**
     * Pick a specific category.
     */
    public function category(string $category): static
    {
        return $this->state(function () use ($category) {
            $pool   = self::$pool[$category];
            $recipe = $this->faker->randomElement($pool);
            return [
                'title'      => $recipe['title'],
                'description'=> $recipe['description'],
                'category'   => $category,
                'difficulty' => $recipe['difficulty'],
                'prep_time'  => $recipe['prep'],
                'cook_time'  => $recipe['cook'],
                'servings'   => $recipe['servings'],
                'steps'      => self::$steps[$category],
            ];
        });
    }

    /**
     * Return the ingredient list for a given category.
     */
    public static function ingredientsFor(string $category): array
    {
        return self::$ingredients[$category] ?? self::$ingredients['vegetarian'];
    }
}
