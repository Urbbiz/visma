<?php


class FrontController {




    public function index()
    {
        $pageTitle = 'Syllable Word';
        $randDigit = Helper::getRandom();


        require DIR.'views/index.php';
    }

    public function create()
    {
        $pageTitle = 'New Pattern';
        require DIR.'views/create.php';
    }




}