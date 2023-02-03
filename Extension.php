<?php 

namespace CupNoodles\LocationDomains;

use System\Classes\BaseExtension;
use Event;
use Config;
use Admin\Widgets\Form;
use Admin\Models\Locations_model;
use Illuminate\Http\Request;
use Igniter\Local\Classes\Location;

class Extension extends BaseExtension
{
    /**
     * Returns information about this extension.
     *
     * @return array
     */
    public function extensionMeta()
    {
        return [
            'name'        => 'LocationDomains',
            'author'      => 'CupNoodles',
            'description' => 'Allow use of domains per-location',
            'icon'        => 'fa-folder-plus',
            'version'     => '1.0.0'
        ];
    }


    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
        // admin setting
        Event::listen('admin.form.extendFieldsBefore', function (Form $form) {
            
            if ($form->model instanceof Locations_model) {
                

                $domain_switch = ['use_alternate_domain' => [
                    'label' => 'lang:cupnoodles.locationdomains::default.use_alternate_domain',
                    'type' => 'switch',

                ]];

                $alternate_domain = ['alternate_domain' => [
                    'label' => 'lang:cupnoodles.locationdomains::default.alternate_domain',
                    'type' => 'text',
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'use_alternate_domain',
                        'condition' => 'checked',
                    ]
                    ]
                ];
                

                $form->tabs['fields'] = $this->array_insert_after($form->tabs['fields'], 'permalink_slug', $domain_switch);
                $form->tabs['fields'] = $this->array_insert_after($form->tabs['fields'], 'permalink_slug', $alternate_domain);
                
                $form->addJS('extensions/cupnoodles/locationdomains/assets/js/locationdomains.js', 'cupnoodles-locationdomains');
            }
        });

        // frontend
        Event::listen('router.beforeRoute', function ($url, $router) {
            
            if(
                (
                    strpos($url, 'menus') !== false 
                    ||
                    strpos($url, 'checkout') !== false 
                )
                && app('location')->getModel()->use_alternate_domain){
                app('url')->forceRootUrl(app('location')->getModel()->alternate_domain);
            } 
            
        });


        Event::listen('main.page.init',function ($page) {

            $location_by_url = Locations_model::where('use_alternate_domain', 1)->where('alternate_domain', (isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://'.  $_SERVER['HTTP_HOST'])->first();
            if(is_object($location_by_url) && get_class($location_by_url) == 'Admin\Models\Locations_model'){
                app('location')->setCurrent($location_by_url);
            }
            else{
                $location_permalink = explode('/', url()->current());
                $location_permalink = $location_permalink[3];
                $location_by_uri = Locations_model::where('permalink_slug', $location_permalink)->first();
                if(is_object($location_by_uri) && get_class($location_by_uri) == 'Admin\Models\Locations_model'){
                    app('location')->setCurrent($location_by_uri);
                }
            }


        });

    }

    public function register()
    {
        //$this->app->singleton('currency', Currency);
    }

    function array_insert_after( array $array, $key, array $new ) {
        $keys = array_keys( $array );
        $index = array_search( $key, $keys );
        $pos = false === $index ? count( $array ) : $index + 1;
    
        return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
    }

   
    /**
     * Registers any admin permissions used by this extension.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [

        ];
    }

    public function registerNavigation()
    {
        return [

        ];
    }



}
