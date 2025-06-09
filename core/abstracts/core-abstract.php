<?php

namespace WPSysMaster\Core\Abstracts;

if (!defined('ABSPATH')) exit;

abstract class CoreAbstract { 

    /**
     * Lấy instance của class
     * Get instance of the class
     * @access public
     * @return CoreAbstract|null
     */
    public static abstract function getInstance();

    /**
     * Constructor
     * @access private
     * @return void
     */
    protected abstract function __construct();

    /**
     * Khởi tạo hooks
     * Initialize hooks
     * @access private
     * @return void
     */
    protected abstract function initHooks(): void;

    /**
     * Đăng ký settings
     * Register settings
     * @access public
     * @return void
     */
    public abstract function registerSettings(): void;
    
    /**
     * Render view
     * @access public
     * @return void
     */
    protected abstract function renderView(): void;
}