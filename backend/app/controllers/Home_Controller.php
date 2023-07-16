<?php
class HomeController
{
    public function render()
    {
        require '../frontend/build/index.html';
    }

    public function test()
    {
        echo json_encode([
            "hello" => "test123"
        ]);
    }
}
