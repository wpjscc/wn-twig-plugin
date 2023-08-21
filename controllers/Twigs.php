<?php namespace Wpjscc\Twig\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Twigs Backend Controller
 */
class Twigs extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class,
    ];

    protected $requiredPermissions = ['wpjscc.twig.*'];



    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Wpjscc.Twig', 'twig', 'twigs');
    }
}
