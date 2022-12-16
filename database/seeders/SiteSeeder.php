<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dummy sites
        $sites = [
            ['name' => 'Trendy News', 'url' => 'https://trendynews.site'],
            ['name' => 'Tech Posts', 'url' => 'https://techposts.com'],
            ['name' => 'Politics Today', 'url' => 'https://politicstoday.com'],
        ];

        // Save each, if it does not already exist
        foreach($sites as $site){
            if(Site::where('url', $site['url'])->first() == null){
                Site::create($site);
            }
        }
    }
}
