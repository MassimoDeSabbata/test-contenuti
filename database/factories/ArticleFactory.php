<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $statuses = config('contents.statuses');
        $status = $this->faker->randomElement($statuses);
        return [

            'title' => $this->faker->sentence(4),
            'abstract' => $this->faker->sentence(8),
            'contents' => $this->faker->sentence(100),
            'status' => $status,
            'publishedOn' => $status === config('contents.statuses.ONLINE') ?  $this->faker->dateTimeBetween('2000-01-01', 'now') : null,
            'author_id' => User::all()->random()->id,
            'category_id' => Category::all()->random()->id
        ];
    }
}
