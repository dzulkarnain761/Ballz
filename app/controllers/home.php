<?php

class Home extends Controller
{
    public function index()
    {
        // cout('Home Controller Loaded');
        $this->view('homepage');
    }

}
