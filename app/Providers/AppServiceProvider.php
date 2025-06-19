<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * Registers a custom Blade directive named 'options'.
         *
         * This directive allows you to use @options in Blade templates to generate
         * HTML option elements dynamically. It delegates the generation logic to
         * the static method App\Helper\HtmlHelper::generateOptionType, passing the
         * provided expression as an argument.
         *
         * Usage in Blade template:
         *   @options($data)
         *
         * @param string $expression The expression passed to the directive, typically data for generating options.
         * @return void
         */
        Blade::directive('options', function ($expression) {
            return "<?php echo \\App\\Helper\\HtmlHelper::generateOptionType($expression); ?>";
        });
    }
}
