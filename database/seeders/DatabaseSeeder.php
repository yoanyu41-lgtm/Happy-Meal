<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        \App\Models\User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@ecommerce.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        \App\Models\Product::create([
            'name' => 'អាម៉ុកត្រី (Fish Amok)',
            'description' => 'ម្ហូបប្រពៃណីខ្មែរដ៏ល្បីល្បាញ ធ្វើពីសាច់ត្រីស្រស់ចំហុយជាមួយគ្រឿងសម្លខ្មែរ ខ្ទិះដូង និងស្លឹកញរ ខ្ចប់ក្នុងស្លឹកចេកយ៉ាងមានសោភ័ណភាព និងរសជាតិឈ្ងុយឆ្ងាញ់។ (Traditional Khmer steamed fish curry in banana leaf, rich in coconut cream and local spices.)',
            'price' => 6.50,
            'image' => 'images/featured_fish_amok.png',
            'stock' => 20,
            'category' => 'alacarte',
            'prep_time_minutes' => 25,
        ]);

        \App\Models\Product::create([
            'name' => 'ឡុកឡាក់សាច់គោ (Beef Lok Lak)',
            'description' => 'សាច់គោផុយៗឆាជាមួយទឹកជ្រលក់រសជាតិដិត ញ៉ាំជាមួយបាយក្តៅៗ អមដោយបន្លែស្រស់ៗដូចជា សាឡាត់ ប៉េងប៉ោះ ខ្ទឹមបារាំង និងទឹកជ្រលក់អំបិលម្រេចក្រូចឆ្មា។ (Stir-fried marinated tender beef cubes served with fresh vegetables, rice, and traditional lime-pepper dipping sauce.)',
            'price' => 5.00,
            'image' => 'images/beef_lok_lak.png',
            'stock' => 25,
            'category' => 'alacarte',
            'prep_time_minutes' => 20,
        ]);

        \App\Models\Product::create([
            'name' => 'នំបញ្ចុកសម្លខ្មែរ (Nom Banh Chok)',
            'description' => 'នំបញ្ចុកស្រស់ស្រូបជាមួយសម្លប្រហើរត្រីបុក ខ្ទិះដូង ញ៉ាំជាមួយល្បោយបន្លែស្រស់ៗជាច្រើនមុខដូចជា ត្រយូងចេក ត្រសក់ ល្ពៅខ្ចី និងជីគ្រប់មុខ។ (Traditional Khmer rice noodles served with warm fish-based green curry broth and a rich variety of fresh local herbs and vegetables.)',
            'price' => 3.50,
            'image' => 'images/nom_banh_chok.png',
            'stock' => 15,
            'category' => 'breakfast',
            'prep_time_minutes' => 10,
        ]);

        \App\Models\Product::create([
            'name' => 'បាយសាច់ជ្រូក (Bai Sach Chrouk)',
            'description' => 'បាយក្តៅៗជាមួយសាច់ជ្រូកអាំងប្រឡាក់គ្រឿងឈ្ងុយឆ្ងាញ់ អមដោយជ្រក់ល្ហុង ត្រសក់ និងទឹកស៊ុបក្តៅឧណ្ហៗ ជារបបអាហារពេលព្រឹកដ៏ពេញនិយមបំផុតរបស់ជនជាតិខ្មែរ។ (Classic Cambodian breakfast featuring sweet marinated grilled pork over warm broken rice, served with pickled vegetables and cucumber soup.)',
            'price' => 2.50,
            'image' => 'images/bai_sach_chrouk.png',
            'stock' => 30,
            'category' => 'breakfast',
            'prep_time_minutes' => 10,
        ]);

        \App\Models\Product::create([
            'name' => 'មីឆាខ្មែរ (Khmer Fried Noodles)',
            'description' => 'មីឆាជាមួយសាច់គោ បន្លែស្រស់ៗ និងពងទាចៀនពីលើ រសជាតិឈ្ងុយឆ្ងាញ់ ល្អបំផុតសម្រាប់ការញ៉ាំពេលល្ងាច ឬពេលយប់។ (Classic Cambodian stir-fried noodles with beef, fresh greens, topped with a fried egg, perfect for late night cravings.)',
            'price' => 2.50,
            'image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=600&q=80',
            'stock' => 20,
            'category' => 'night',
            'prep_time_minutes' => 10,
        ]);

        \App\Models\Product::create([
            'name' => 'ស៊ុបសាច់គោ (Khmer Beef Soup)',
            'description' => 'ស៊ុបសាច់គោស្លឹកក្រសាំងក្តៅៗ មានរសជាតិជូរអែមដិតឈ្ងុយ ញ៉ាំជាមួយបន្លែ និងសាច់គោទន់ៗ ល្អបំផុតសម្រាប់ពេលល្ងាច។ (Warm, savory and sour Khmer beef soup cooked with local herbs and tender beef, ideal for dinner.)',
            'price' => 4.50,
            'image' => 'images/khmer_beef_soup.png',
            'stock' => 12,
            'category' => 'night',
            'prep_time_minutes' => 15,
        ]);

        \App\Models\Product::create([
            'name' => 'តែបៃតងដោះគោទឹកកក (Iced Green Milk Tea)',
            'description' => 'តែបៃតងឆុងជាមួយទឹកដោះគោខាប់ និងទឹកកក រសជាតិផ្អែមមុត ត្រជាក់ចិត្ត និងបំបាត់ភាពក្តៅ។ (Rich, sweet iced green milk tea, a refreshing local drink.)',
            'price' => 1.80,
            'image' => 'images/iced_green_milk_tea.png',
            'stock' => 50,
            'category' => 'drinks',
            'prep_time_minutes' => 5,
        ]);

        \App\Models\Product::create([
            'name' => 'ទឹកអំពៅស្រស់ (Fresh Sugarcane Juice)',
            'description' => 'ទឹកអំពៅច្របាច់ថ្មីៗត្រជាក់ចិត្ត រសជាតិផ្អែមស្រទន់បែបធម្មជាតិ។ (Freshly squeezed sugarcane juice served with ice, sweet and natural.)',
            'price' => 1.00,
            'image' => 'images/fresh_sugarcane_juice.png',
            'stock' => 40,
            'category' => 'drinks',
            'prep_time_minutes' => 3,
        ]);

        \App\Models\Product::create([
            'name' => 'គុយទាវភ្នំពេញ (Phnom Penh Noodle Soup)',
            'description' => 'គុយទាវសរសៃតូចស្រូបទឹកស៊ុបឆ្អឹងជ្រូកផ្អែមឈ្ងុយ អមដោយប្រហិតត្រី សាច់ជ្រូកចិញ្ច្រាំ ថ្លើម និងបង្គាស្រស់ ជានិមិត្តរូបនៃអាហារពេលព្រឹករបស់ខ្មែរ។ (Iconic Phnom Penh rice noodle soup featuring a rich pork bone broth, topped with minced pork, shrimp, meatballs, and fresh herbs.)',
            'price' => 3.80,
            'image' => 'images/phnom_penh_noodle_soup.png',
            'stock' => 15,
            'category' => 'breakfast',
            'prep_time_minutes' => 12,
        ]);

        \App\Models\Product::create([
            'name' => 'ឆាក្តៅសាច់មាន់ (Spicy Basil Chicken)',
            'description' => 'សាច់មាន់ឆាជាមួយម្ទេស ខ្ទឹមស ស្លឹកគ្រៃ និងស្លឹកកម្រះព្រៅ រសជាតិហឹរដិតឈ្ងុយឆ្ងាញ់ប្លែកមាត់ ញ៉ាំជាមួយបាយក្តៅៗ។ (Stir-fried spicy chicken with lemongrass, hot chilies, garlic, and wild holy basil, served hot.)',
            'price' => 4.50,
            'image' => 'images/spicy_basil_chicken.png',
            'stock' => 20,
            'category' => 'alacarte',
            'prep_time_minutes' => 18,
        ]);

        \App\Models\Product::create([
            'name' => 'បុកល្ហុងខ្មែរ (Khmer Green Papaya Salad)',
            'description' => 'ល្ហុងបុកជាមួយកាពិ ទឹកត្រី ក្រូចឆ្មា ម្ទេស និងប្រហុកខ្មែរ អមដោយបង្គាក្រៀម និងសណ្តែកដី ល្អបំផុតសម្រាប់ការញ៉ាំលម្ហែពេលល្ងាច។ (Traditional green papaya salad pounded with chili, lime, fish sauce, dried shrimp, and peanuts, delivering a vibrant mix of flavors.)',
            'price' => 3.00,
            'image' => 'images/khmer_green_papaya_salad.png',
            'stock' => 18,
            'category' => 'night',
            'prep_time_minutes' => 10,
        ]);

        \App\Models\Product::create([
            'name' => 'កាហ្វេទឹកដោះគោទឹកកក (Iced Milk Coffee)',
            'description' => 'កាហ្វេខ្មៅឆុងស្រស់ៗ លាយជាមួយទឹកដោះគោខាប់ និងទឹកកក មានរសជាតិឈ្ងុយដិត និងជួយឲ្យស្បាងស្បើយពីភាពងងុយគេង។ (Strong dark-roasted Cambodian coffee brewed and combined with sweet condensed milk over ice.)',
            'price' => 1.50,
            'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=600&q=80',
            'stock' => 35,
            'category' => 'drinks',
            'prep_time_minutes' => 4,
        ]);

        \App\Models\Product::create([
            'name' => 'ទឹកដូងខ្ចីស្រស់ (Fresh Young Coconut)',
            'description' => 'ទឹកដូងផ្អែមត្រជាក់ចិត្តធម្មជាតិ បានមកពីផ្លែដូងខ្ចីចិតថ្មីៗ។ (Naturally sweet, hydrating, and chilled young coconut water served straight from the fruit.)',
            'price' => 1.20,
            'image' => 'images/fresh_young_coconut.png',
            'stock' => 30,
            'category' => 'drinks',
            'prep_time_minutes' => 2,
        ]);

        \App\Models\Product::create([
            'name' => 'បាយឆាគ្រឿងសមុទ្រ (Seafood Fried Rice)',
            'description' => 'បាយឆាជាមួយគ្រឿងសមុទ្រស្រស់ៗ រួមមានមឹក និងបង្គា លាយជាមួយបន្លែ និងស៊ុត រសជាតិឆ្ងាញ់ពេញចិត្តគ្រប់គ្នា។ (Delicious stir-fried rice with fresh squid and shrimp, mixed vegetables, and egg.)',
            'price' => 3.75,
            'image' => 'images/seafood_fried_rice.png',
            'stock' => 20,
            'category' => 'alacarte',
            'prep_time_minutes' => 12,
        ]);

        \App\Models\Product::create([
            'name' => 'ឆាក្តាមម្រេចកំពត (Kampot Pepper Fried Crab)',
            'description' => 'ក្តាមថ្មស្រស់ៗឆាជាមួយម្រេចខ្ចីកំពតដ៏ល្បីល្បាញ លាយជាមួយទឹកជ្រលក់រសជាតិដិតល្មមឈ្ងុយឆ្ងាញ់។ (Fresh crab stir-fried with the famous Kampot green peppercorns in a savory sauce.)',
            'price' => 8.50,
            'image' => 'images/pepper_fried_crab.png',
            'stock' => 10,
            'category' => 'alacarte',
            'prep_time_minutes' => 20,
        ]);

        \App\Models\Product::create([
            'name' => 'តែក្រូចឆ្មាទឹកកក (Iced Lemon Tea)',
            'description' => 'តែខ្មៅឆុងស្រស់លាយជាមួយទឹកក្រូចឆ្មា និងទឹកកក រសជាតិជូរអែមបំបាត់ការស្រេកទឹក។ (Freshly brewed black tea mixed with fresh lime juice and ice, refreshing and sweet-sour.)',
            'price' => 1.50,
            'image' => 'images/iced_lemon_tea.png',
            'stock' => 45,
            'category' => 'drinks',
            'prep_time_minutes' => 3,
        ]);

        \App\Models\Product::create([
            'name' => 'ផាសិនដោះគោទឹកកក (Iced Passion Fruit Milk)',
            'description' => 'ទឹកផាសិនច្របាច់ស្រស់លាយជាមួយទឹកដោះគោ និងទឹកកក រសជាតិផ្អែមជូរស្រទន់។ (Fresh passion fruit juice mixed with condensed milk and ice, a perfect creamy and tangy local drink.)',
            'price' => 1.80,
            'image' => 'images/passion_fruit_milk.png',
            'stock' => 40,
            'category' => 'drinks',
            'prep_time_minutes' => 4,
        ]);

        \App\Models\Product::create([
            'name' => 'បបរសាច់ត្រី/សាច់ជ្រូក (Khmer Rice Porridge)',
            'description' => 'បបរដ៏ក្តៅឧណ្ហៗ ញ៉ាំជាមួយសាច់ត្រីស្រស់ ឬសាច់ជ្រូក លាយជាមួយខ្ញី ខ្ទឹមបំពង និងស្លឹកខ្ទឹម។ (Warm, comforting Khmer rice porridge cooked with choice of fish or pork, topped with fresh ginger, scallions, and fried garlic.)',
            'price' => 2.50,
            'image' => 'images/khmer_rice_porridge.png',
            'stock' => 20,
            'category' => 'breakfast',
            'prep_time_minutes' => 8,
        ]);

        \App\Models\Product::create([
            'name' => 'នំបុ័ងសាច់ប៉ាតេ (Khmer Pate Bread)',
            'description' => 'នំបុ័ងស្រួយៗ ក្តៅៗ ញាត់ជាមួយប៉ាតេ សាច់ជ្រូកផាត់ បន្លែជ្រក់ និងទឹកជ្រលក់ពិសេស។ (Crispy Cambodian baguette stuffed with house-made pate, pork loaf, pickled vegetables, and savory sauce.)',
            'price' => 2.00,
            'image' => 'images/khmer_pate_bread.png',
            'stock' => 30,
            'category' => 'breakfast',
            'prep_time_minutes' => 5,
        ]);

        \App\Models\Product::create([
            'name' => 'សម្លកកូរប្រពៃណី (Traditional Samlor Kako)',
            'description' => 'សម្លកកូរជាសម្លតំណាងជាតិខ្មែរ ចម្អិនពីបន្លែចម្រុះគ្រប់មុខ លាយជាមួយអង្ករលីងបុក និងគ្រឿងសម្លខ្មែរដិតរសជាតិ។ (Cambodia\'s national vegetable stew cooked with roasted ground rice, fresh lemongrass Kroeung paste, and mixed local greens.)',
            'price' => 5.50,
            'image' => 'https://images.unsplash.com/photo-1607532941433-304659e8198a?auto=format&fit=crop&w=600&q=80',
            'stock' => 15,
            'category' => 'alacarte',
            'prep_time_minutes' => 22,
        ]);

        \App\Models\Product::create([
            'name' => 'ឆាត្រកួនសាច់ជ្រូក (Stir-fried Morning Glory)',
            'description' => 'ត្រកួនស្រស់ៗឆាជាមួយសាច់ជ្រូកបីជាន់ ខ្ទឹមស និងប្រេងខ្យង រសជាតិឈ្ងុយឆ្ងាញ់។ (Crispy water spinach / morning glory stir-fried with pork belly, garlic, and oyster sauce.)',
            'price' => 3.50,
            'image' => 'https://images.unsplash.com/photo-1580959375944-abd7e991f971?auto=format&fit=crop&w=600&q=80',
            'stock' => 25,
            'category' => 'alacarte',
            'prep_time_minutes' => 10,
        ]);

        \App\Models\Product::create([
            'name' => 'សម្លម្ជូរគ្រឿងសាច់គោ (Khmer Sour Beef Soup)',
            'description' => 'សម្លម្ជូរគ្រឿងសាច់គោស្លឹកកន្លែង ក្តៅៗ រសជាតិជូរហឹរដិតឈ្ងុយ លាយជាមួយគ្រឿងបុក។ (Traditional sour soup featuring tender beef cubes, water spinach, cooked with local lemongrass paste and tamarind.)',
            'price' => 6.00,
            'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?auto=format&fit=crop&w=600&q=80',
            'stock' => 12,
            'category' => 'alacarte',
            'prep_time_minutes' => 20,
        ]);

        \App\Models\Product::create([
            'name' => 'សាច់ចង្កាក់អាំងជ្រក់ល្ហុង (Khmer Beef Skewers)',
            'description' => 'សាច់គោអាំងចង្កាក់ប្រឡាក់គ្រឿងសម្លស្លឹកគ្រៃ លាយល្មៀត ញ៉ាំជាមួយជ្រក់ល្ហុង។ (Grilled lemongrass beef skewers marinated in Khmer yellow Kroeung paste, served with green papaya pickles.)',
            'price' => 3.00,
            'image' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=600&q=80',
            'stock' => 30,
            'category' => 'night',
            'prep_time_minutes' => 12,
        ]);

        \App\Models\Product::create([
            'name' => 'បាញ់ឆែវប្រណីត (Classic Banh Xeo)',
            'description' => 'នំបញ្ចុកបាញ់ឆែវស្រួយៗ លឿងឆ្អិន ខ្ចប់សាច់ជ្រូកចិញ្ច្រាំ និងសណ្តែកបណ្តុះ ញ៉ាំជាមួយបន្លែ និងទឹកត្រីផ្អែម។ (Crispy rice pancake stuffed with minced pork and bean sprouts, served with fresh herbs and sweet-sour fish sauce.)',
            'price' => 4.00,
            'image' => 'https://images.unsplash.com/photo-1627308595229-7830a5c91f9f?auto=format&fit=crop&w=600&q=80',
            'stock' => 15,
            'category' => 'night',
            'prep_time_minutes' => 15,
        ]);

        \App\Models\Product::create([
            'name' => 'ទឹកផ្លែបឺរក្រឡុក (Fresh Avocado Smoothie)',
            'description' => 'ផ្លែបឺរស្រស់ៗក្រឡុកជាមួយដោះគោខាប់ និងទឹកដោះគោស្រស់ រសជាតិឈ្ងុយខ្ទិះ និងផ្អែមស្រទន់។ (Creamy fresh avocado blended with condensed milk and fresh milk.)',
            'price' => 2.50,
            'image' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?auto=format&fit=crop&w=600&q=80',
            'stock' => 30,
            'category' => 'drinks',
            'prep_time_minutes' => 5,
        ]);

        \App\Models\Product::create([
            'name' => 'ទឹកស្វាយក្រឡុកស្រស់ (Fresh Mango Smoothie)',
            'description' => 'ផ្លែស្វាយទុំផ្អែមធម្មជាតិ ក្រឡុកជាមួយទឹកកក ត្រជាក់ចិត្តបំបាត់ភាពក្តៅ។ (Naturally sweet ripe mangoes blended with ice, a tropical refreshing smoothie.)',
            'price' => 2.00,
            'image' => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=600&q=80',
            'stock' => 30,
            'category' => 'drinks',
            'prep_time_minutes' => 4,
        ]);

        \App\Models\Product::create([
            'name' => 'តែទឹកដោះគោគុជ (Classic Pearl Milk Tea)',
            'description' => 'តែទឹកដោះគោរសជាតិដិតឈ្ងុយ ញ៉ាំជាមួយគុជទន់ៗស្វិតៗ។ (Brewed black tea combined with chewy tapioca pearls.)',
            'price' => 2.00,
            'image' => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?auto=format&fit=crop&w=600&q=80',
            'stock' => 40,
            'category' => 'drinks',
            'prep_time_minutes' => 5,
        ]);

        \App\Models\Product::create([
            'name' => 'សូដាក្រូចឆ្មា (Lemon Soda)',
            'description' => 'ទឹកក្រូចឆ្មាច្របាច់ស្រស់លាយជាមួយទឹកសូដា និងទឹកកក រសជាតិជូរអែមដិតផ្អែមស្រស់ស្រាយ。 (Fizzy club soda with freshly squeezed lime juice, ice, and sweet syrup.)',
            'price' => 1.50,
            'image' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=600&q=80',
            'stock' => 45,
            'category' => 'drinks',
            'prep_time_minutes' => 3,
        ]);

        \App\Models\Product::create([
            'name' => 'កាហ្វេខ្មៅទឹកកក (Iced Black Coffee)',
            'description' => 'កាហ្វេខ្មៅឆុងស្រស់រសជាតិដិតខ្លាំង បន្ថែម និងទឹកកក។ (Classic strong-brewed Cambodian black coffee served over ice.)',
            'price' => 1.20,
            'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=600&q=80',
            'stock' => 45,
            'category' => 'drinks',
            'prep_time_minutes' => 3,
        ]);
    }
}
