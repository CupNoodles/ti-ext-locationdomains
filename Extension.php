<?php 

namespace CupNoodles\LocationDomains;

use System\Classes\BaseExtension;
use Event;
use Config;
use Admin\Widgets\Form;
use Admin\Models\Locations_model;

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
            if(app('location')->getModel()->use_alternate_domain){
                app('url')->forceRootUrl(app('location')->getModel()->alternate_domain);
            }

            
            //echo app('location')->getId(); die();
            
        });

        //echo app('location'); 
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
