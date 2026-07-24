<?php
require_once __DIR__ . '/../repositories/MenuRepository.php';

class MenuService {
    private $repository;

    public function __construct($db) {
        $this->repository = new MenuRepository($db);
    }

    public function getAllMenus() {
        return $this->repository->findAll();
    }

    public function getMenuById($id) {
        $menu = $this->repository->findById($id);
        if (!$menu) {
            return null;
        }
        return $menu;
    }
}