<?php

namespace App\Providers;

use App\Facades\AclManagerFacade;
use App\Facades\RvMediaFacade;
use App\Http\Middleware\Admin\Authenticate;
use App\Http\Middleware\Admin\RedirectIfAuthenticated;
use App\Models\Activation;
use App\Models\Article;
use App\Models\AuditHistory;
use App\Models\Category;
use App\Models\ContentBlock;
use App\Models\District;
use App\Models\Estate;
use App\Models\EstateUnit;
use App\Models\MediaFile;
use App\Models\MediaFolder;
use App\Models\MediaSetting;
use App\Models\MenuItem;
use App\Models\Message;
use App\Models\MetaSeo;
use App\Models\Role;
use App\Models\User;
use App\Models\Utility;
use App\Repositories\Acl\Caches\RoleCacheDecorator;
use App\Repositories\Acl\Eloquent\ActivationRepository;
use App\Repositories\Acl\Eloquent\RoleRepository;
use App\Repositories\Acl\Eloquent\UserRepository;
use App\Repositories\Acl\Interfaces\ActivationInterface;
use App\Repositories\Acl\Interfaces\RoleInterface;
use App\Repositories\Acl\Interfaces\UserInterface;
use App\Repositories\Article\Caches\ArticleCacheDecorator;
use App\Repositories\Article\Eloquent\ArticleRepository;
use App\Repositories\Article\Interfaces\ArticleInterface;
use App\Repositories\AuditHistory\Caches\AuditHistoryCacheDecorator;
use App\Repositories\AuditHistory\Eloquent\AuditHistoryRepository;
use App\Repositories\AuditHistory\Interfaces\AuditHistoryInterface;
use App\Repositories\Category\Caches\CategoryCacheDecorator;
use App\Repositories\Category\Eloquent\CategoryRepository;
use App\Repositories\Category\Interfaces\CategoryInterface;
use App\Repositories\ContentBlock\Caches\ContentBlockDecorator;
use App\Repositories\ContentBlock\Eloquent\ContentBlockRepository;
use App\Repositories\ContentBlock\Interfaces\ContentBlockInterface;
use App\Repositories\District\Caches\DistrictCacheDecorator;
use App\Repositories\District\Eloquent\DistrictRepository;
use App\Repositories\District\Interfaces\DistrictInterface;
use App\Repositories\Estate\Caches\EstateCacheDecorator;
use App\Repositories\Estate\Eloquent\EstateRepository;
use App\Repositories\Estate\Interfaces\EstateInterface;
use App\Repositories\Media\Caches\MediaFileCacheDecorator;
use App\Repositories\Media\Caches\MediaFolderCacheDecorator;
use App\Repositories\Media\Caches\MediaSettingCacheDecorator;
use App\Repositories\Media\Eloquent\MediaFileRepository;
use App\Repositories\Media\Eloquent\MediaFolderRepository;
use App\Repositories\Media\Eloquent\MediaSettingRepository;
use App\Repositories\Media\Interfaces\MediaFileInterface;
use App\Repositories\Media\Interfaces\MediaFolderInterface;
use App\Repositories\MenuItem\Caches\MenuItemCacheDecorator;
use App\Repositories\MenuItem\Eloquent\MenuItemRepository;
use App\Repositories\MenuItem\Interfaces\MenuItemInterface;
use App\Repositories\Message\Eloquent\MessageRepository;
use App\Repositories\Message\Interfaces\MessageInterface;
use App\Repositories\MetaSeo\Caches\MetaSeoCacheDecorator;
use App\Repositories\MetaSeo\Eloquent\MetaSeoRepository;
use App\Repositories\MetaSeo\Interfaces\MetaSeoInterface;
use App\Repositories\Unit\Caches\EstateUnitCacheDecorator;
use App\Repositories\Unit\Eloquent\EstateEstateUnitRepositry;
use App\Repositories\Unit\Interfaces\EstateUnitInterface;
use App\Repositories\Utility\Caches\UtilityCacheDecorator;
use App\Repositories\Utility\Eloquent\UtilityRepository;
use App\Repositories\Utility\Interfaces\UtilityInterface;
use App\Supports\Helper;
use App\Supports\Routes\CustomResourceRegistrar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class BasicServiceProvider extends ServiceProvider
{
    protected $app;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ResourceRegistrar::class, function ($app) {
            return new CustomResourceRegistrar($app['router']);
        });
        /**
         * @var Router $router
         */
        $router = $this->app['router'];
        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);

        $this->app->bind(UserInterface::class, function () {
            return new UserRepository(new User);
        });

        $this->app->bind(ActivationInterface::class, function () {
            return new ActivationRepository(new Activation);
        });

        $this->app->bind(RoleInterface::class, function () {
            return new RoleCacheDecorator(new RoleRepository(new Role));
        });

        $this->app->bind(EstateUnitInterface::class, function () {
            return new EstateUnitCacheDecorator(new EstateEstateUnitRepositry(new EstateUnit));
        });

        $loader = AliasLoader::getInstance();
        $loader->alias('AclManager', AclManagerFacade::class);
        $loader->alias('RvMedia', RvMediaFacade::class);

        //$this->app->singleton(UserRepository::class);

        $this->app->bind(UtilityInterface::class, function () {
            return new UtilityCacheDecorator(new UtilityRepository(new Utility));
        });

        $this->app->bind(DistrictInterface::class, function () {
            return new DistrictCacheDecorator(new DistrictRepository(new District));
        });

        $this->app->bind(MenuItemInterface::class, function () {
            return new MenuItemCacheDecorator(new MenuItemRepository(new MenuItem));
        });

        $this->app->bind(ContentBlockInterface::class, function () {
            return new ContentBlockDecorator(new ContentBlockRepository(new ContentBlock));
        });

        $this->app->bind(CategoryInterface::class, function () {
            return new CategoryCacheDecorator(new CategoryRepository(new Category));
        });

        $this->app->bind(ArticleInterface::class, function () {
            return new ArticleCacheDecorator(new ArticleRepository(new Article));
        });

        $this->app->bind(EstateInterface::class, function () {
            return new EstateCacheDecorator(new EstateRepository(new Estate));
        });

        $this->app->bind(MessageInterface::class, function () {
            return new MessageRepository(new Message);
        });

        $this->app->singleton(
            \App\Repositories\Eloquent\ArticleResponsitoryInterface::class,
            \App\Repositories\Eloquent\ArticleEloquentResponsitory::class
        );

        $this->app->singleton(
            \App\Repositories\Eloquent\ProjectRespositoryInterface::class,
            \App\Repositories\Eloquent\ProjectEloquentResponsitory::class
        );

        $this->app->singleton(
            \App\Repositories\Eloquent\SliderResponsitoryInterface::class,
            \App\Repositories\Eloquent\SliderEloquentResponsitory::class
        );

        $this->app->singleton(
            \App\Repositories\Eloquent\PartnerResponsitoryInterface::class,
            \App\Repositories\Eloquent\PartnerEloquentResponsitory::class
        );

        $this->app->bind(AuditHistoryInterface::class, function () {
            return new AuditHistoryCacheDecorator(new AuditHistoryRepository(new AuditHistory));
        });

        $this->app->bind(MetaSeoInterface::class, function () {
            return new MetaSeoCacheDecorator(new MetaSeoRepository(new MetaSeo));
        });

        $this->app->bind(MediaFileInterface::class, function () {
            return new MediaFileCacheDecorator(
                new MediaFileRepository(new MediaFile)
            );
        });

        $this->app->bind(MediaFolderInterface::class, function () {
            return new MediaFolderCacheDecorator(
                new MediaFolderRepository(new MediaFolder)
            );
        });

        $this->app->bind(MediaSettingInterface::class, function () {
            return new MediaSettingCacheDecorator(
                new MediaSettingRepository(new MediaSetting)
            );
        });
        Helper::autoload(__DIR__ . '/../Helpers');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $siteSetting = get_setting_site();
        \URL::forceScheme('https');
        if (!defined('SITENAME')) define('SITENAME', $siteSetting->sitename);
        if (!defined('META_DESCRIPTION')) define('META_DESCRIPTION', $siteSetting->meta_description);
        if (!defined('META_KEYWORDS')) define('META_KEYWORDS', $siteSetting->meta_keywords);
        if (isset($siteSetting->logo)) {
            if (!defined('LOGO')) define('LOGO', asset('upload/settings/' . $siteSetting->logo));
        }
        config()->set(['auth.providers.users.model' => User::class]);
    }


    /**
     * Garbage collect activations and reminders.
     *
     * @return void
     */
    protected function garbageCollect()
    {
        $config = $this->app['config']->get('cms.general');
        $this->sweep($this->app->make(ActivationInterface::class), $config['activations']['lottery']);
    }

    /**
     * Sweep expired codes.
     *
     * @param  mixed $repository
     * @param  array $lottery
     * @return void
     */
    protected function sweep($repository, array $lottery)
    {
        if ($this->configHitsLottery($lottery)) {
            try {
                $repository->removeExpired();
            } catch (\Exception $exception) {
                info($exception->getMessage());
            }
        }
    }

    /**
     * Determine if the configuration odds hit the lottery.
     *
     * @param  array $lottery
     * @return bool
     */
    protected function configHitsLottery(array $lottery)
    {
        return mt_rand(1, $lottery[1]) <= $lottery[0];
    }
}
